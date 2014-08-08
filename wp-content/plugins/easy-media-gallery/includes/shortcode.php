<?php 

global $theopt;

function easy_media_shortcode( $atts ) {
	
if ( easy_get_option( 'easymedia_disen_plug' ) == '1' ) {	

	  extract( shortcode_atts( array(
      'cat' => -1,
	  'col' => '',
	  'align' => '',		  
	  'med' => -1
   ), $atts ) );	
   
  
ob_start();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; // for pagination

if ( $med <= '0' && $cat > '0' ) {
$emgargs = array( 
    'post_type' => 'easymediagallery',
    'showposts' => -1,
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
    'order' => 'ASC',
    'tax_query' => array(
        array(
            'taxonomy' => 'emediagallery',
            'terms' => $cat,
            'field' => 'term_id',
        )
    ),
);	
}

else if ( $cat <= '0' && $med > '0' ) {
	$fnlid = explode(",", $med);
	
	$emgargs = array(
	'post__in' => $fnlid, 
	'post_type' => 'easymediagallery',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'menu_order',
  	'paged' => $paged
	);
	}

// Get media options
$deff_img_limit = easy_get_option( 'easymedia_img_size_limit' ); // get the default image size limit
$theopt = easy_get_option( 'easymedia_frm_size' ); 
$imwidth = stripslashes( $theopt['width'] );
$imheight = stripslashes( $theopt['height'] );


// Custom columns filter	
$num_cols = easymedia_sc_handler( $col, '0' );

// Custom Align	filter
$cus_align = easymedia_sc_handler( $align, '1' );

// Load Media
$emg_query = new WP_Query( $emgargs );
if ( $emg_query->have_posts() ):

echo '<div class="pfwrpr"><div id="alignstyle" class="easymedia_'.$cus_align.'">';
  for ( $i=1 ; $i <= $num_cols; $i++ ) :
    echo '<div id="col-'.$i.'" class="thecol">';
    $counter = $num_cols + 1 - $i;

	while ( $emg_query->have_posts() ) : $emg_query->the_post();

		$image = get_post_meta( get_the_id(), 'easmedia_metabox_img', true );
		$mediattl = get_post_meta( get_the_id(), 'easmedia_metabox_title', true );	
		$mediattl = esc_html(esc_js($mediattl)); $mediattl = stripslashes($mediattl);
		$mediatype = get_post_meta( get_the_id(), 'easmedia_metabox_media_type', true );
		$isvidsize = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_size', true );
		$isresize1 = get_post_meta( get_the_id(), 'easmedia_metabox_media_image_opt1', true );
		$thepostid = get_the_id();
		
		if ( $image == '' ) {
			$image = plugins_url( 'images/no-image-available.jpg' , __FILE__ ) ;
			}
		else {
			$image = $image;
			$globalsize = wp_get_attachment_image_src( emg_get_attachment_id_from_src( $image ), 'full' );
		}

		if ( $mediatype == 'Video' && $isvidsize == 'off' ) {
			$cusvidw = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_size_vidw', true );
			$cusvidh = get_post_meta( get_the_id(), 'easmedia_metabox_media_video_size_vidh', true );
			$therell = "easymedia&#91;".$cusvidw." " .$cusvidh."&#93;";
			}
		elseif ( $mediatype == 'Video' && $isvidsize == 'on' ) {
			$getarry = easy_get_option( 'easymedia_vid_size' );
			$defvidw = stripslashes( $getarry['width'] );
			$defvidh = stripslashes( $getarry['height'] );
			$therell = "easymedia&#91;".$defvidw." " .$defvidh."&#93;";
			}
		else {
			$therell = "easymedia";
			}

		switch ( $mediatype ) {
			case 'Single Image':
				if ( basename( $image ) == 'no-image-available.jpg' ) {
					$medialink = $image;
				}
					else {
				$attid = wp_get_attachment_image_src( emg_get_attachment_id_from_src( $image ), 'full' );
				$medialink = easymedia_imgresize( $attid[0], $deff_img_limit, $isresize1, $attid[1], $attid[2] );
				$medialink = explode(",", $medialink); $medialink = $medialink[0];
					}
				$therell = "easymedia";

	    	break;
			
						
			case 'Video':
				$medialink = get_post_meta( get_the_id(), 'easmedia_metabox_media_video', true );
	        break;
			
			case 'Audio':
				$medialink = get_post_meta( get_the_id(), 'easmedia_metabox_media_audio', true );
				$therell = "easymedia";
	        break;			
			
		}
		
      if( $counter%$num_cols == 0 ) :

	  	$curimgnmane = basename($image);
	if ( $curimgnmane == 'no-image-available.jpg' ) {
		$image = $image;
		} else {
			$image = easymedia_resizer( $image, $globalsize[1], $globalsize[2], $imwidth, $imheight, true );
			}
	  
	  if ( easy_get_option( 'easymedia_disen_hovstyle' ) == '1' ) { ?>
     <div style="width:<?php echo $imwidth; ?>px; height:<?php echo $imheight; ?>px;" class="view da-thumbs"><div class="iehand"><img width="<?php echo $imwidth; ?>" height="<?php echo $imheight; ?>" src="<?php echo $image; ?>" alt="<?php echo $mediattl; ?>" /><a class="<?php echo $thepostid; ?>" rel="<?php echo $therell; ?>" href="<?php echo $medialink; ?>"><article class="da-animate da-slideFromRight"><p <?php if ( $mediattl == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext"><?php echo $mediattl; ?></p><div class="forspan"><span class="zoom"></span></div></article></a></div></div>
            
<?php } elseif ( easy_get_option( 'easymedia_disen_hovstyle' ) == '' ) { ?>
<div class="view da-thumbs"><div class="iehand"><a class="<?php echo $thepostid; ?>" rel="<?php echo $therell; ?>" href="<?php echo $medialink; ?>"><img width="<?php echo $imwidth; ?>" height="<?php echo $imheight; ?>" src="<?php echo $image; ?>" /></a></div></div>
<?php	}

	  endif;
      $counter++;
    endwhile;
    //rewind_posts();
    echo '</div>'; //closes the column div
  endfor;
else:
  echo '<div class="pfwrpr"><div class="alignstyle"><div class="thecol">'; ?>
  <div class="view"><img src="<?php echo plugins_url('images/ajax-loader.gif' , __FILE__); ?>" width="32" height="32"/></div>
  
  <?php
endif;
wp_reset_postdata();
echo '<div style="clear:both;"></div>';
echo '</div></div>';
	
$content = ob_get_clean();
return $content;

}
else {
ob_start();	
echo '<div style="display: none;"></div>';	
$content = ob_get_clean();
return $content;
	}

}

add_shortcode( 'easy-media', 'easy_media_shortcode' );

?>