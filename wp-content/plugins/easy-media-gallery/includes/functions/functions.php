<?php


/**
* Easy Media Gallery Lite Version
* Get a Easy Media Gallery specific option
*
* @param string $name The option name
* @return object|bool Option value on success, false if no value exists
*/
 
 
 /*
|--------------------------------------------------------------------------
| Easymedia (Lite) Get Control Panel Options
|--------------------------------------------------------------------------
*/
function easy_get_option( $name ){
    $easymedia_values = get_option( 'easy_media_opt' );
    if ( is_array( $easymedia_values ) && array_key_exists( $name, $easymedia_values ) ) return $easymedia_values[$name];
    return false;
}
 
/*-------------------------------------------------------------------------------*/
/*   ADMIN Register JS & CSS
/*-------------------------------------------------------------------------------*/
function easymedia_reg_script() {
	// CSS ( emg-settings.php, tinymce-dlg.php, metaboxes.php )
	wp_register_style( 'easymedia-cpstyles', plugins_url( 'css/funcstyle.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION, 'all');
	wp_register_style( 'easymedia-colorpickercss', plugins_url( 'css/colorpicker.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION );
	wp_register_style( 'easymedia-sldr', plugins_url( 'css/slider.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION );
	wp_register_style( 'easymedia-ibutton', plugins_url( 'css/ibutton.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION );	
	wp_register_style( 'easymedia-tinymce', plugins_url( 'css/tinymce.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION );
	wp_register_style( 'jquery-ui-themes-redmond', plugins_url( 'css/jquery/jquery-ui/themes/smoothness/jquery-ui-1.10.0.custom.min.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION );
	wp_register_style( 'easymedia-tinymce', plugins_url( 'css/tinymce.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION );	
	wp_register_style( 'jquery-multiselect-css', plugins_url( 'css/jquery/multiselect/jquery.multiselect.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION );
	wp_register_style( 'easymedia-comparison-css', plugins_url( 'css/compare.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION );
	wp_register_style( 'jquery-messi-css', plugins_url( 'css/messi.css' , dirname(__FILE__) ), false, EASYMEDIA_VERSION );
	
	// JS ( emg-settings.php ) 
	wp_register_script( 'easymedia-jquery-easing', plugins_url( 'js/jquery/jquery.easing.js' , dirname(__FILE__) ) );	
	wp_register_script( 'easymedia-colorpicker', plugins_url( 'js/colorpicker/colorpicker.js' , dirname(__FILE__) ) );	
	wp_register_script( 'colorpicker-eye', plugins_url( 'js/colorpicker/eye.js' , dirname(__FILE__) ) );
	wp_register_script( 'colorpicker-utils', plugins_url( 'js/colorpicker/utils.js' , dirname(__FILE__) ) );
	
	// JS ( tinymce-dlg.php, emg-settings.php ) 
	wp_register_script( 'jquery-multi-sel', plugins_url( 'js/jquery/multiselect/jquery.multiselect.js' , dirname(__FILE__) ) );
	
	// JS ( metaboxes.php, ) 
	wp_register_script( 'easymedia-comparison-js', plugins_url( 'js/func/compare.js' , dirname(__FILE__) ) );
	wp_register_script( 'jquery-messi-js', plugins_url( 'js/jquery/jquery.messi.min.js' , dirname(__FILE__) ) );
		
}
add_action( 'admin_init', 'easymedia_reg_script' );


function easymedia_frontend_js() {
	// JS ( frontend.php ) 		
	wp_register_script( 'fittext', plugins_url( 'js/jquery/jquery.fittext.js' , dirname(__FILE__) ) );		
	wp_register_script( 'mootools-core', plugins_url( 'js/mootools/mootools-' .easy_get_option( 'easymedia_plugin_core' ). '.js' , dirname(__FILE__) ) );	
	wp_register_script( 'easymedia-core', plugins_url( 'js/mootools/easymedia.js' , dirname(__FILE__) ) );			
	wp_register_script( 'easymedia-frontend', plugins_url( 'js/func/frontend.js' , dirname(__FILE__) ) );		
	wp_register_script( 'easymedia-ajaxfrontend', plugins_url( 'js/func/ajax-frontend.js' , dirname(__FILE__) ) );	
}
add_action( 'wp_enqueue_scripts', 'easymedia_frontend_js' );

/*
|--------------------------------------------------------------------------
| Defines
|--------------------------------------------------------------------------
*/
define( 'EMG_IS_AJAX', easy_get_option( 'easymedia_disen_ajax' ) );
$emgmemory = (int) ini_get('memory_limit');
$emgmemory = empty($emgmemory) ? __('N/A') : $emgmemory . __(' MB');

/* These files build out the plugin specific options and associated functions. */
require_once (EASYMEDG_PLUGIN_DIR . 'includes/options.php'); 

/*-------------------------------------------------------------------------------*/
/*   Load Control Panel
/*-------------------------------------------------------------------------------*/
include_once( EASYMEDG_PLUGIN_DIR . 'includes/emg-settings.php' );

/*-------------------------------------------------------------------------------*/
/*   Load Front End Script
/*-------------------------------------------------------------------------------*/
if ( easy_get_option( 'easymedia_disen_plug' ) == '1' ) {	
include_once( EASYMEDG_PLUGIN_DIR . 'includes/frontend.php' );
}
/*-------------------------------------------------------------------------------*/
/*  Add Metabox & Shortcode
/*-------------------------------------------------------------------------------*/
include_once( EASYMEDG_PLUGIN_DIR . 'includes/metaboxes.php' ); 
include_once( EASYMEDG_PLUGIN_DIR . 'includes/shortcode.php' ); 
include_once( EASYMEDG_PLUGIN_DIR . 'includes/tinymce-dlg.php' ); 
include_once( EASYMEDG_PLUGIN_DIR . 'includes/taxonomy.php' );
include_once( EASYMEDG_PLUGIN_DIR . 'includes/easywidget.php' );

/*
|--------------------------------------------------------------------------
| AJAX RESET SETTINGS
|--------------------------------------------------------------------------
*/
function emg_cp_reset() {
	
	check_ajax_referer( 'easymedia-lite-nonce', 'security' );
	
	if ( !isset( $_POST['cmd'] ) ) {
		echo '0';
		die;
		}
		
		else {
			if ( $_POST['cmd'] == 'reset' ){
				echo '1';
				easymedia_restore_to_default($_POST['cmd']);			
				die;
				}
	}
}
add_action( 'wp_ajax_emg_cp_reset', 'emg_cp_reset' );

/*
|--------------------------------------------------------------------------
| AJAX LIST MEDIA (TINYMCE)
|--------------------------------------------------------------------------
*/
function emg_load_media_list() {
	
	if ( !isset( $_POST['taxo'] ) ) {
		echo '<p>Ajax request failed, please try again.</p>';
		die;
		}
		else {
			global $post;
			$taxoterm = $_POST['taxo'];
			
			
$args = array(
'tax_query' => array(
    array(
        'taxonomy' => 'emediagallery',
        'field' => 'id'
        //'terms' => $taxoterm
    )
)
);

query_posts( $args );			
			
if ( have_posts() ) :
	while ( have_posts() ) :
			$show_media .= '
			<input name="'.$post->ID.'" id="'.$post->ID.'" type="text" value="'.get_post_meta( $id, 'easmedia_metabox_title', true ).'" />';

	echo $show_media;
	die();
	
	endwhile;	
else:
  echo 'Sorry, no media matched your criteria.';		
  die();	
endif;
wp_reset_query();			
			
	}
}
add_action( 'wp_ajax_emg_load_media_list', 'emg_load_media_list' );

/*
|--------------------------------------------------------------------------
| AJAX DELETE MEDIA IMAGE
|--------------------------------------------------------------------------
*/
function easmedia_img_media_remv() {
	
	if ( !isset( $_POST['pstid'] ) || !isset( $_POST['type'] ) ) {
		echo '0';
		die;
		}
		
		else {
			if ( !current_user_can( 'edit_theme_options' ) )
			die('-1');
			
			if ( $_POST['type'] == 'image' ){
				$data = $_POST['pstid'];
				update_post_meta($data, 'easmedia_metabox_img', '');
				echo '1';
				die;
				}
	
	elseif ( $_POST['type'] == 'audio' ){
		$data = $_POST['pstid'];
				update_post_meta($data, 'easmedia_metabox_media_audio', '');
				echo '1';
	    die;
		}
	}
}
add_action( 'wp_ajax_easmedia_img_media_remv', 'easmedia_img_media_remv' );

/*
|--------------------------------------------------------------------------
| Easymedia Custom Category Box (Metabox)
|--------------------------------------------------------------------------
*/
function easymediagallery_categories_meta_box( $post, $box ) {
	$defaults = array('taxonomy' => 'emediagallery');
	if ( !isset( $box['args'] ) || !is_array( $box['args'] ) )
		$args = array();
	else
		$args = $box['args'];
	extract( wp_parse_args($args, $defaults), EXTR_SKIP );
	$tax = get_taxonomy( $taxonomy );

	?>
	<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
		<ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
			<li class="tabs"><a href="#<?php echo $taxonomy; ?>-all"><?php echo $tax->labels->all_items; ?></a></li>
			<li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop"><?php _e( 'Most Used' ); ?></a></li>
		</ul>

		<div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
			<ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
				<?php $popular_ids = wp_popular_terms_checklist($taxonomy); ?>
			</ul>
		</div>

		<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
			<?php
            $name = ( $taxonomy == 'emediagallery' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';
            echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
            ?>
			<ul id="<?php echo $taxonomy; ?>checklist" data-wp-lists="list:<?php echo $taxonomy?>" class="categorychecklist form-no-clear">
				<?php wp_terms_checklist($post->ID, array( 'taxonomy' => $taxonomy, 'popular_cats' => $popular_ids ) ) ?>
			</ul>
		</div>
	<?php if ( current_user_can($tax->cap->edit_terms) ) : ?>
			<div id="<?php echo $taxonomy; ?>-adder" class="wp-hidden-children">
				<h4>
					<a id="<?php echo $taxonomy; ?>-add-toggle" href="#<?php echo $taxonomy; ?>-add" class="hide-if-no-js">
						<?php
							/* translators: %s: add new taxonomy label */
							printf( __( '+ %s' ), $tax->labels->add_new_item );
						?>
					</a>
				</h4>
				<p id="<?php echo $taxonomy; ?>-add" class="category-add wp-hidden-child">
					<label class="screen-reader-text" for="new<?php echo $taxonomy; ?>"><?php echo $tax->labels->add_new_item; ?></label>
					<input type="text" name="new<?php echo $taxonomy; ?>" id="new<?php echo $taxonomy; ?>" class="form-required form-input-tip" value="<?php echo esc_attr( $tax->labels->new_item_name ); ?>" aria-required="true"/>
					<label class="screen-reader-text" for="new<?php echo $taxonomy; ?>_parent">
						<?php echo $tax->labels->parent_item_colon; ?>
					</label>
					<?php wp_dropdown_categories( array( 'taxonomy' => $taxonomy, 'hide_empty' => 0, 'name' => 'new'.$taxonomy.'_parent', 'orderby' => 'name', 'hierarchical' => 1, 'show_option_none' => '&mdash; ' . $tax->labels->parent_item . ' &mdash;' ) ); ?>
					<input type="button" id="<?php echo $taxonomy; ?>-add-submit" data-wp-lists="add:<?php echo $taxonomy ?>checklist:<?php echo $taxonomy ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $tax->labels->add_new_item ); ?>" />
					<?php wp_nonce_field( 'add-'.$taxonomy, '_ajax_nonce-add-'.$taxonomy, false ); ?>
					<span id="<?php echo $taxonomy; ?>-ajax-response"></span>
				</p>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/*-------------------------------------------------------------------------------*/
/* Add Post Thumbnails and Custom Thumbnails size
/*-------------------------------------------------------------------------------*/
function easmedia_add_thumbnail_support() {
	if ( !current_theme_supports( 'post-thumbnails' ))  {
add_theme_support( 'post-thumbnails', array( 'easymediagallery' ) );
add_image_size( 'emg-admin-thumb', 70, 70, true ); // Used in the easymedia edit page
	}
}
add_action('init', 'easmedia_add_thumbnail_support');

/*-------------------------------------------------------------------------------*/
/* Add credits in admin page
/*-------------------------------------------------------------------------------*/
function easymediagallery_add_footer_credits( $text ) {
	$t = '';
	if ( get_post_type() === 'easymediagallery' ) {
		$t .= "<div id=\"credits\" style=\"line-height: 22px;\">";
		$t .= "<p>Easy Media Gallery plugin Lite is created by <a href=\"http://ghozylab.com/plugins/\" target=\"_blank\">GhozyLab, Inc</a>.</p>";
		$t .= "<p>If you have some support issue, don't hesitate to <a href=\"http://ghozylab.com/plugins/submit-support-request/\" target=\"_blank\">contact us here</a>. The GhozyLab team will be happy to support you on any issue.</p>";
		$t .= "</div>";
	}else{
		$t = $text;
	}

	return $t;
}
add_filter( 'admin_footer_text', 'easymediagallery_add_footer_credits' );

/*-------------------------------------------------------------------------------*/
/*  Get the patterns list 
/*-------------------------------------------------------------------------------*/
function easmedia_patterns_ls() {
	$patterns = array();
	$patterns_list = scandir( EMG_DIR."/css/images/patterns" );
	
	foreach( $patterns_list as $pattern_name ) {
		if ( $pattern_name != '.' && $pattern_name != '..' ) {
			$patterns[] = $pattern_name;
		}
	}
	return $patterns;	
}

/*-------------------------------------------------------------------------------*/
/*  HEX to RGB
/*-------------------------------------------------------------------------------*/
function easymedia_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return implode(",", $rgb); // returns an array with the rgb values
}

/*-------------------------------------------------------------------------------*/
/*  Shortcode Handler
/*-------------------------------------------------------------------------------*/
function easymedia_sc_handler( $scdata, $scl ) {
	
	switch ( $scl ) {
		
		case '0':
			if ( $scdata != '' && $scdata <= 3 ) {
				$finaldata = $scdata;
				} else {
					$finaldata = easy_get_option( 'easymedia_columns' );
					}
		break;

		case '1':
			if ( ( $scdata != '' && $scdata == 'center' ) || ( $scdata != '' && $scdata == 'none' ) ) {
				$finaldata = $scdata;
				} else {
					$finaldata = strtolower( easy_get_option( 'easymedia_alignstyle' ) );
					}			
		break;	
	
		default:
			break;	
	}
	
return $finaldata;

}

/*-------------------------------------------------------------------------------*/
/*  Get attachment image id 
/*-------------------------------------------------------------------------------*/
function emg_get_attachment_id_from_src ($link) {
    global $wpdb;
        $link = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $link);
        return $wpdb->get_var("SELECT ID FROM {$wpdb->posts} WHERE guid='$link'");
}

/*-------------------------------------------------------------------------------*/
/*  Image Resize ( Aspect Ratio )
/*-------------------------------------------------------------------------------*/
function easymedia_imgresize($img, $limit, $isres, $imw, $imh) {
	
	if ( $img == '' ) {
		$img = plugins_url( 'images/no-image-available.jpg' , dirname(__FILE__) ) ;
	}
		else {
			$img = $img;
		}	
	
	if ( $isres == 'on' ) {
		if ( $imw > $limit ) {
			$tempimgratio = $imh / $imw;
			$fih = (int)($tempimgratio * $limit); // final image height
			$fiw = $limit; // fixed image width
			$allimgdata = array( easymedia_resizer( $img, $imw, $imh, $fiw, $fih, true ), $fiw, $fih );
			}
		else {
			$allimgdata = array( $img, $imw, $imh );
			}		
		}
	else { $allimgdata = array( $img, $imw, $imh );	
	}
return implode(",", $allimgdata);
}

/*-------------------------------------------------------------------------------*/
/*  Image Resize ( Aspect Ratio ) AJAX
/*-------------------------------------------------------------------------------*/
function easymedia_imgresize_ajax() {
	if ( !isset( $_POST['imgurl'] ) || !isset( $_POST['limiter'] ) || $_POST['imgurl'] == '' || $_POST['limiter'] == '' ) {
		echo '<p>Ajax request failed, please refresh your browser window.</p>';
		die;
		}
		else {
			
		$imgurl = $_POST['imgurl'];
		$limiter = $_POST['limiter'];
		$attid = wp_get_attachment_image_src( emg_get_attachment_id_from_src( $imgurl ), 'full' );
				
				$tmpimgratio = $attid[2] / $attid[1]; //get image aspec ratio

		if ( $attid[1] > $limiter ) {
			$tmph = (int)($tmpimgratio * $limiter); // final image height
			$tmpw = $limiter; // fixed image width
			$finimgurl = easymedia_resizer( $imgurl, $attid[1], $attid[2], $tmpw, $tmph, true );
			$allimgdata = array( $finimgurl, $tmpw, $tmph );
			echo implode(",", $allimgdata);
			die;
			}
		else {
			$finimgurl = $imgurl;
			$allimgdata = array( $finimgurl, $attid[1], $attid[2] );
			echo implode(",", $allimgdata);
			die;
			}		
		}
}
add_action( 'wp_ajax_easymedia_imgresize_ajax', 'easymedia_imgresize_ajax' );

/*
|--------------------------------------------------------------------------
| REMOVE PERMALINK
|--------------------------------------------------------------------------
*/
function emg_hide_permalink() {
global $post_type;
if( $post_type == 'easymediagallery' ) {
echo '<style type="text/css">#edit-slug-box{display: none;}</style>';
}
}
add_action('admin_head', 'emg_hide_permalink'); 

/*--------------------------------------------------------------------------------*/
/*  REMOVE THE PARENT FIELD FOR THE CUSTOM TEXONOMY
/*--------------------------------------------------------------------------------*/
function emg_remove_cat_parent(){
    global $current_screen;
    switch ( $current_screen->id ) {
        case 'edit-emediagallery':
            
			?>
			<script type="text/javascript">
            jQuery(document).ready( function($) {
                jQuery('#parent').parents('.form-field').remove();
				jQuery('#tag-slug, #tag-description').parents('.form-field').hide();
            });
            </script>
            <?php
			
            break;
    }
}
add_action('admin_footer-edit-tags.php', 'emg_remove_cat_parent');

/*-------------------------------------------------------------------------------*/
/*  Get WP Info
/*-------------------------------------------------------------------------------*/
function easmedia_get_wpinfo() {
	
// Get Site URL	
$getwpinfo = array();
$getwpinfo[0] = "- Site URL : " .get_site_url();

// Get Multisite status
if ( is_multisite() ) { $getwpinfo[1] = '- WP Multisite : YES'; } else { $getwpinfo[1] = '- WP Multisite : NO'; }

// Get PHP deny from all status	
$url = plugins_url( 'dynamic-style.php' , dirname(__FILE__) );
$headers = get_headers($url);

if (strpos($headers[0],'Forbidden') !== false) {
    $getwpinfo[2] = '- PHP Direct Access : Forbidden';
} else {
	$getwpinfo[2] = '- PHP Direct Access : YES';
	}
	
global $wp_version, $emgmemory;		
echo "- WP Version : ".$wp_version."\n";
echo  "- EMG-Lite Version : ".EASYMEDIA_VERSION."\n";	
echo $getwpinfo[0]."\n";
echo $getwpinfo[1]."\n";	
echo $getwpinfo[2]."\n";
echo "- Memory Limit : ".$emgmemory."\n";
$theme_name = wp_get_theme();
echo "- Active Theme : ".$theme_name->get('Name')."\n";
echo "- Active Plugins : \n";

// Get Active Plugin
if ( is_multisite() ) { 
	$the_plugs = get_site_option('active_sitewide_plugins');
	foreach($the_plugs as $key => $value) {
		$string = explode('/',$key);
		$string[0] = str_replace( "-"," ",$string[0] );
		echo " &nbsp;&nbsp;&nbsp;&nbsp;".ucwords( $string[0] )."\n";
	}
} else {
	$the_plugs = get_option('active_plugins');
	foreach($the_plugs as $key => $value) {
		$string = explode('/',$value);
		$string[0] = str_replace( "-"," ",$string[0] );
        echo " &nbsp;&nbsp;&nbsp;&nbsp;".ucwords( $string[0] )."\n";
		}
	}
}

/*-------------------------------------------------------------------------------*/
/*  Get Plugin Version (@return string Plugin version)
/*-------------------------------------------------------------------------------*/
function easymedia_get_plugin_version() {
    $plugin_data = get_plugin_data( EMG_DIR . '/easy-media-gallery.php' );
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}

/*-------------------------------------------------------------------------------*/
/*  Enable Sorting of the Media 
/*-------------------------------------------------------------------------------*/
function easmedia_create_easymedia_sort_page() {
    $easmedia_sort_page = add_submenu_page('edit.php?post_type=easymediagallery', 'Sorter', __('Sorter', 'easmedia'), 'edit_posts', 'easymedia-order', 'easmedia_easymedia_sort');
    
    add_action('admin_print_styles-' . $easmedia_sort_page, 'easmedia_print_sort_styles');
    add_action('admin_print_scripts-' . $easmedia_sort_page, 'easmedia_print_sort_scripts');
}
add_action( 'admin_menu', 'easmedia_create_easymedia_sort_page' );


function easmedia_easymedia_sort() {
    $easymedias = new WP_Query('post_type=easymediagallery&posts_per_page=-1&orderby=menu_order&order=ASC'); 
	if (  $easymedias->have_posts() ) :
	?>
    <div class="wrap">
        <div id="icon-edit" class="icon32 icon32-posts-easymedia"><br /></div>
        <h2><?php _e('Sorter', 'easmedia'); ?></h2>
        <p><?php _e('Simply drag the Media up or down and they will be saved in that order. Media at the top will appear first.', 'easmedia'); ?></p>

		<div class="metabox-holder">
			<div class="postbox">
				<h3><?php _e( 'Sort Media', 'easmedia' ); ?>:</h3>


        <ul id="easymedia_list" style="padding-left:10px !important;">
            <?php while( $easymedias->have_posts() ) : $easymedias->the_post(); ?>        
                    <li id="<?php the_id(); ?>" class="menu-item">
                        <dl class="menu-item-bar">
                            <dt class="menu-item-handle">
                                <img style="float:left; vertical-align:middle;padding-top: 4px; margin-right:10px;" src="<?php echo plugins_url( 'images/sort.png' , dirname(__FILE__) ) ?>" height="28px;" width="28px;"/><span class="item-title"><?php echo esc_html(esc_js(the_title(NULL, NULL, FALSE))); ?></span>
                            </dt>
                        </dl>
                        <ul class="menu-item-transport"></ul>
                    </li>
            <?php endwhile; 
/*
Thanks to Kevin Falcoz (aka 0pc0deFR) for this discovery and this patch.
::: esc_html(esc_js(the_title(NULL, NULL, FALSE))); :::
*/ 
?>

				<?php else: ?>
<div class="wrap">
<div id="icon-edit" class="icon32 icon32-posts-easymedia"><br /></div>  
<h2><?php _e('Sorter', 'easmedia'); ?></h2> 
		<div class="metabox-holder">
			<div class="postbox">
				<h3><?php _e( 'Sort Media', 'easmedia' ); ?>:</h3>             
<p style="padding:10px;"><?php printf( __('No items found, why not %screate one%s?	', 'easmedia'), '<a href="post-new.php?post_type=easymediagallery">', '</a>'); ?> </p></div></div></div>				
<?php endif; ?>            
            
            <?php wp_reset_postdata(); ?>
        </ul>
    </div><div style="padding-left:33px; margin-bottom:30px"><img src="<?php echo plugins_url( 'images/dragdrop.png' , dirname(__FILE__) ) ?>" height="23px;" width="161px;"/></div>
  </div>
 </div>  
	<?php 
}

/*-------------------------------------------------------------------------------*/
/*  RENAME POST BUTTON
/*-------------------------------------------------------------------------------*/
add_filter( 'gettext', 'change_publish_button', 10, 2 );
function change_publish_button( $translation, $text ) {
if ( 'easymediagallery' == get_post_type())
if ( $text == 'Publish' ) {
    return 'Save Media'; }
else if ( $text == 'Update' ) {
    return 'Update Media'; }	

return $translation;
}

add_filter( 'gettext', 'change_insert_media', 10, 2 );
function change_insert_media( $translation, $text ) {
if ( 'easymediagallery' == get_post_type())
if ( $text == 'Insert into post' ) {
    return 'Insert into media'; }	

return $translation;
}

/*-------------------------------------------------------------------------------*/
/*   Load Dasboard News
/*-------------------------------------------------------------------------------*/

function emg_download_count() {
	
    $args = (object) array( 'slug' => 'easy-media-gallery' );
    $request = array( 'action' => 'plugin_information', 'timeout' => 15, 'request' => serialize( $args) );
    $url = 'http://api.wordpress.org/plugins/info/1.0/';
    $response = wp_remote_post( $url, array( 'body' => $request ) );
    $plugin_info = unserialize( $response['body'] );
	$ttldl = $plugin_info->downloaded;
    $ttldls = 'Downloaded : ' .number_format( $ttldl ) . ' times';
	return $ttldls;
}

if ( easy_get_option( 'easymedia_disen_dasnews' ) == '1' ) {
function emg_register_dashboard_widgets() {
	if ( current_user_can( apply_filters( 'emg_dashboard_stats_cap', 'edit_pages' ) ) ) {
		wp_add_dashboard_widget( 'emg_dashboard_stat', __('Easy Media Gallery (Lite) '.emg_download_count().'', 'easmedia'), 'emg_dashboard_widget' );
	}
}
add_action('wp_dashboard_setup', 'emg_register_dashboard_widgets' );

function emg_dashboard_widget() {
?>
    <div class="emg_dashboard_widget">
<p class="sub">If you ever have any questions at all, please post them on our <a target="_blank" href="http://ghozylab.com/plugins/submit-support-request/">Submit Support Request</a> form. Our dedicated support team will gladly assist you with your inquiries directly via the ticket or email. Lastly, if you really love Easy Media Gallery, please recommend our plugin to your friends.</p>	
<div style="position:relative;">
<ul class='easymedia-social' id='easymedia-cssanime'>
<li class='easymedia-facebook'>
<a onclick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=Check out the Best Wordpress Portfolio and Gallery plugin&amp;p[summary]=Easy Media Gallery for WordPress that is powerful and so easy to create portfolio or media gallery&amp;p[url]=http://ghozylab.com/plugins/&amp;p[images][0]=<?php echo plugins_url( 'images/easymediabox.png' , dirname(__FILE__) ) ?>', 'sharer', 'toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)" title="Share"><strong>Facebook</strong></a>
</li>
<li class='easymedia-twitter'>
<a onclick="window.open('https://twitter.com/share?text=Check out the Best Wordpress Portfolio and Gallery Plugin &url=http://ghozylab.com/plugins/', 'sharer', 'toolbar=0,status=0,width=548,height=325');" title="Twitter" class="circle"><strong>Twitter</strong></a>
</li>
<li class='easymedia-googleplus'>
<a onclick="window.open('https://plus.google.com/share?url=http://ghozylab.com/plugins/','','width=415,height=450');"><strong>Google+</strong></a>
</li>
<li class='easymedia-pinterest'>
<a onclick="window.open('http://pinterest.com/pin/create/button/?url=http://ghozylab.com/plugins/;media=<?php echo plugins_url( 'images/easymediabox.png' , dirname(__FILE__) ) ?>;description=Easy Media Gallery for WordPress that is powerful and so easy to create portfolio or media gallery','','width=600,height=300');"><strong>Pinterest</strong></a>
</li>
</ul>
</div></div>

    <?php
	}
}


function emg_share() {
?>
<div style="position:relative; margin-top:6px;">
<ul class='easymedia-social' id='easymedia-cssanime'>
<li class='easymedia-facebook'>
<a onclick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=Check out the Best Wordpress Portfolio and Gallery plugin&amp;p[summary]=Easy Media Gallery for WordPress that is powerful and so easy to create portfolio or media gallery&amp;p[url]=http://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-gallery-grid-galleries-plugin/&amp;p[images][0]=<?php echo plugins_url( 'images/easymediabox.png' , dirname(__FILE__) ) ?>', 'sharer', 'toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)" title="Share"><strong>Facebook</strong></a>
</li>
<li class='easymedia-twitter'>
<a onclick="window.open('https://twitter.com/share?text=Check out the Best Wordpress Portfolio and Gallery Plugin &url=http://ghozylab.com/', 'sharer', 'toolbar=0,status=0,width=548,height=325');" title="Twitter" class="circle"><strong>Twitter</strong></a>
</li>
<li class='easymedia-googleplus'>
<a onclick="window.open('https://plus.google.com/share?url=http://ghozylab.com/','','width=415,height=450');"><strong>Google+</strong></a>
</li>
<li class='easymedia-pinterest'>
<a onclick="window.open('http://pinterest.com/pin/create/button/?url=http://ghozylab.com/;media=<?php echo plugins_url( 'images/easymediabox.png' , dirname(__FILE__) ) ?>;description=Easy Media Gallery for WordPress that is powerful and so easy to create portfolio or media gallery','','width=600,height=300');"><strong>Pinterest</strong></a>
</li>
</ul>
</div>

    <?php
	}

/*
|--------------------------------------------------------------------------
| AJAX HIDE NOTIFY
|--------------------------------------------------------------------------
*/
function emg_hide_noty() {
	
	check_ajax_referer( 'easymedia-lite-nonce-button', 'hidesecurity' );
	
	if ( !isset( $_POST['clickcmd'] ) ) {
		echo '0';
		die;
		}
		
		else {
			if ( $_POST['clickcmd'] == 'hide' ){
				echo '1';
				$emg_upd_options = get_option('easy_media_opt');
				$emg_upd_options['easymedia_disen_admnotify']['id'] = '0';
				update_option('easy_media_opt', $emg_upd_options);					
				die;
				}
	}
}
add_action( 'wp_ajax_emg_hide_noty', 'emg_hide_noty' );


/*-------------------------------------------------------------------------------*/
/*   Admin Notifications
/*-------------------------------------------------------------------------------*/
function emg_admin_bar_menu(){
            global $wp_admin_bar;

            /* Add the main siteadmin menu item */
                $wp_admin_bar->add_menu( array(
                    'id'     => 'emg-upgrade-bar',
                    'href' => 'http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=topbar&utm_campaign=order',
                    'parent' => 'top-secondary',
					'title' => __('<img src="'.plugins_url( 'images/easymedia-cp-icon.png' , dirname(__FILE__) ).'" style="vertical-align:middle;margin-right:5px" alt="Upgrade Now!" title="Upgrade Now!" />Upgrade Easy Media Gallery to PRO Version', 'easmedia' ),
                    'meta'   => array('class' => 'emg-upgrade-to-pro', 'target' => '_blank' ),
                ) );
}
if ( easy_get_option( 'easymedia_disen_admnotify' ) == '1' ) {
add_action( 'admin_bar_menu', 'emg_admin_bar_menu', 1000);
}

/* @since 1.2.35
if ( easy_get_option( 'easymedia_disen_admnotify' ) == '1' ) {
function emg_upgradepro_message() {
	if ( isset( $_GET['page'] ) && $_GET['page'] != 'emg_settings' || isset( $_GET['page'] ) && $_GET['page'] != 'docs' || isset( $_GET['page'] ) && $_GET['page'] != 'comparison' || isset( $_GET['page'] ) && $_GET['page'] != 'easymedia-order' || get_post_type() != 'easymediagallery' ) {

		echo'<div id="emgadminnotice" class="updated"><div class="easymedia_message"><img class="easymedia_icon" title="" src="' . plugins_url( 'images/message_icon.png', dirname(__FILE__) ) . '" alt=""/><div class="easymedia_text">It\'s time to upgrade your <strong>Easy Media Gallery Lite</strong> to <strong>PRO</strong> version!<br /><span>Extend standard plugin functionality with a tons of awesome features!</span></div><a class="button easymedia_button" href="edit.php?post_type=easymediagallery&page=comparison">Learn More</a><a class="button easymedia_button" target="_blank" href="http://ghozylab.com/best-photo-albums-wordpress-plugin/">DEMO</a><a class="button easymedia_button" target="_blank" href="http://ghozylab.com/order/?utm_source=easymediagallerylite&utm_medium=popuptop&utm_campaign=order">UPGRADE $'.EASYMEDIA_PRICE.'</a></div></div>';
	}
}
add_action( 'admin_notices', 'emg_upgradepro_message', 1 );
}
*/

/*-------------------------------------------------------------------------------*/
/*   Admin Notifications ( Setting Area )
/*-------------------------------------------------------------------------------*/

if ( easy_get_option( 'easymedia_disen_admnotify' ) == '1' ) {
		add_action( 'admin_enqueue_scripts', 'easmedia_put_notify_script' );
		add_action('admin_head', 'easmedia_put_notify_head');
}

function easmedia_put_notify_script() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'emg_settings' || isset( $_GET['page'] ) && $_GET['page'] == 'docs' || isset( $_GET['page'] ) && $_GET['page'] == 'comparison' || isset( $_GET['page'] ) && $_GET['page'] == 'easymedia-order' || get_post_type() == 'easymediagallery' ) {			
		wp_enqueue_script( 'easymedia-notify-js', plugins_url( 'js/jquery/noty/jquery.noty.packaged.min.js' , dirname(__FILE__) ) );
		}
	}

function easmedia_put_notify_head() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'emg_settings' ) {	
	?>
    <script type="text/javascript">
	/*<![CDATA[*/
	/* Easy Media Gallery */
function generate(e){
	var emgNews = new Array(); /* Random Heading temporary disabled */
      emgNews[0] = "#1 Best Selling Gallery Plugin for WordPress<br />19,800+ PRO users from around the World can not be wrong...";
      emgNews[1] = "Easy to use, looks nice and has a very good feel";
      emgNews[2] = "Powerfull control panel and Shortcode Manager make getting started super easy";
      emgNews[3] = "Easy Media Gallery PRO can be used to embed more than 12 video. Not only from Youtube and Vimeo";
      emgNews[4] = "Powerfull control panel and Shortcode Manager make getting started super easy";	  
	  var showH = Math.floor(Math.random()*emgNews.length);

	var t=noty({text:'Upgrade your <strong>EASY MEDIA GALLERY LITE</strong> to <strong>PRO VERSION</strong> and extend standard plugin functionality with a tons of awesome features!',type:"warning",animation:{open:{height:"toggle"},close:{height:"toggle"},easing:"swing",speed:700},dismissQueue:true,modal:true,layout:"bottom",killer: true,
theme:"defaultTheme",
template:'<div class="noty_message"><div id="emg_noty_container"><div id="emg_noty_images"><img id="emg_hero" src="<?php echo plugins_url('images/emg_hero.png' , dirname(__FILE__) ); ?>" width="100%" height="auto"/></div><div id="emg_noty_content"><h2>'+emgNews[0]+'</h2><span class="noty_text"></span></div></div><div class="noty_close"></div></div>',buttons:[{
	addClass:"emgnotyclosepermanent",text:"Disable notifications",onClick:function(e){
		e.close()}},
	{addClass:"tsc_buttons2 green",text:"UPGRADE NOW",onClick:function(e){e.close();noty({layout:"top",modal:true,text:'<span style="display:none;" id="emgordernote">Please click order button below and you will be redirected to order page shortly.</span><br /><a id="emgordrnow" style="display:none; margin: 15px 0 15px 0; "class="tsc_buttons2 green" href="http://ghozylab.com/plugins/ordernow.php?order=proplus&utm_source=lite&utm_medium=popup&utm_campaign=order" target="_blank">ORDER NOW</a><img id="emgorderspin" src="<?php echo plugins_url('images/ajax-loader.gif' , dirname(__FILE__) ); ?>" width="32" height="32"/><br /><p>Great! Please wait a moment...</p>',type:"success"});setTimeout(function(){jQuery("#emgorderspin").hide();jQuery("#emgordernote").fadeIn("slow");jQuery("#emgordrnow").fadeIn("slow");jQuery(".noty_text p").hide()},5e3)}},{addClass:"tsc_buttons2 blue",text:"DEMO",onClick:function(e){window.location.href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/";e.close()}},{addClass:"tsc_buttons2 orange",text:"Learn More",onClick:function(e){window.location.href="edit.php?post_type=easymediagallery&page=comparison";e.close()}},{addClass:"tsc_buttons2 red",text:"Close",onClick:function(e){e.close()}}],callback:{onShow:function(){jQuery("#ux_buy_pro").hide();jQuery("#emgadminnotice").hide()}}})}function generateAll(){generate("alert")}jQuery(document).ready(function(){
<?php if ( isset( $_GET['page'] ) && $_GET['page'] == 'comparison' ) { ?>
setTimeout(function() { jQuery.noty.closeAll(); }, 100); <?php } ?>	generateAll();jQuery('.emgnotyclosepermanent').click(function(){var clickcmd = 'hide';
emg_hide_noty(clickcmd);});	function emg_hide_noty(clickcmd) {var data = {action: 'emg_hide_noty',hidesecurity: '<?php echo wp_create_nonce( "easymedia-lite-nonce-button"); ?>',clickcmd: clickcmd,};jQuery.post(ajaxurl, data, function(response) {if (response == 0) {alert('Ajax request failed, please refresh your browser window.');return false;}});}})	
/*]]>*/</script>	
    
<?php
	}
}
/*-------------------------------------------------------------------------------*/
/*   Clean up our custom post/page
/*-------------------------------------------------------------------------------*/
function easmedia_cleanup_page() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'emg_settings' || isset( $_GET['page'] ) && $_GET['page'] == 'docs' || isset( $_GET['page'] ) && $_GET['page'] == 'comparison' || isset( $_GET['page'] ) && $_GET['page'] == 'easymedia-order' || get_post_type() == 'easymediagallery' ) {	
?>
    <script type="text/javascript">
	/*<![CDATA[*/
	/* Easy Media Gallery */	
	jQuery(document).ready(function(){jQuery("#ux_buy_pro").hide();});
/*]]>*/</script>
<?php
	}
}
if ( easy_get_option( 'easymedia_disen_admnotify' ) == '0' ) {
		add_action('admin_head', 'easmedia_cleanup_page');
}

function emg_cleanup_adminbar() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'emg_settings' || isset( $_GET['page'] ) && $_GET['page'] == 'docs' || isset( $_GET['page'] ) && $_GET['page'] == 'comparison' || isset( $_GET['page'] ) && $_GET['page'] == 'easymedia-order' || get_post_type() == 'easymediagallery' ) {
	global $wp_admin_bar;$wp_admin_bar->remove_node('gallery_bank_links');}
}
add_action( 'wp_before_admin_bar_render', 'emg_cleanup_adminbar' );


/*-------------------------------------------------------------------------------*/
/*   Comparison Page
/*-------------------------------------------------------------------------------*/
function easmedia_create_docs_page() {
    $easmedia_docs_page = add_submenu_page('edit.php?post_type=easymediagallery', 'Documentation', __('Documentation', 'easmedia'), 'edit_posts', 'docs', 'easmedia_easymedia_docs');
}
add_action( 'admin_menu', 'easmedia_create_docs_page' );


function easmedia_easymedia_docs() {
	?>
    <div class="wrap">
        <div id="icon-edit" class="icon32 icon32-posts-easymedia"><br /></div>
        <h2><?php _e('Documentation', 'easmedia'); ?></h2>
        <p><?php _e('This plugin comes with instructional training videos that walk you through every aspect of setting up your new media gallery. We recommend following these videos to setup your media gallery. This user manual is only intended to be a reference guide.', 'easmedia'); ?></p>


<div class="metabox-holder">
			<div class="postbox">
				<h3><?php _e( 'Subscribe and Get Free Updates', 'easmedia' ); ?></h3>
        <div id="easymedia_docs2" style="padding:10px !important; ">
<script src="https://apis.google.com/js/plusone.js"></script>  <div class="g-ytsubscribe" data-channel="GhozyLab" data-layout="full"></div>
    </div>
    </div>
  </div>
  
 <?php 
 if ( easy_get_option( 'easymedia_disen_dasnews' ) == '1' ) {  ?>
 <div class="metabox-holder">
			<div class="postbox">
				<h3><?php _e( 'Share Easy Media Gallery', 'easmedia' ); ?></h3>
        <div id="easymedia_docs2" style="padding: 3px 3px 3px 17px !important; ">
        <?php emg_dashboard_widget(); ?>
    </div>
    </div>
  </div>
  <?php } ?>

		<div class="metabox-holder">
			<div class="postbox">
				<h3><?php _e( 'Video Tutorials', 'easmedia' ); ?></h3>
        <div id="easymedia_docs1" style="padding-left:10px !important;">
        <ul style="list-style: square; position:relative; margin-left:15px; margin-bottom:25px">
        <li><a href="http://www.youtube.com/watch?v=dXFBNY5t6E8" target="_blank" >How to Create Single Image Media</a></li>
        <li><a href="http://www.youtube.com/watch?v=htxwZw_aPF0" target="_blank" >How to Create Video Media Types</a></li>  
        <li><a href="http://www.youtube.com/watch?v=Bsn-CB5Hpbw" target="_blank" >How to Create Audio (mp3) Media Types</a></li>
        <li><a href="http://www.youtube.com/watch?v=Z2qwXz7GIRw" target="_blank" >How to Publish Easy Media Gallery</a></li>                  
        <li><a href="http://www.youtube.com/watch?v=2T73wvt_wOA" target="_blank" >How to Change Media Border Size and Color</a></li>
        <li><a href="http://www.youtube.com/watch?v=56f_C7OXiAE" target="_blank" >How to Change Media Columns</a></li>
        <li><a href="http://www.youtube.com/watch?v=TQ1MMxhsyD8" target="_blank" >How to Create Grid Gallery</a>&nbsp;&nbsp;<i>(Pro version)</i></li> 
		<li><a href="http://www.youtube.com/watch?v=OEoNB2LpnSE" target="_blank" >How to Create Filterable Media</a>&nbsp;&nbsp;<i>(Pro version)</i></li>
        
		<li><a href="http://www.youtube.com/watch?v=-N0JNcToHOI" target="_blank" >How to Create Grid Gallery with Pagination</a>&nbsp;&nbsp;<i>(Pro version)</i></li>        
        
		<li><a href="http://www.youtube.com/watch?v=skCMKvVLD5o" target="_blank" >How to Set Order of Image</a>&nbsp;&nbsp;<i>(Pro version)</i></li>
        <li><a href="http://www.youtube.com/watch?v=Oee2cpKT-kE" target="_blank" >How to Create Audio Soundcloud</a>&nbsp;&nbsp;<i>(Pro version)</i></li>
        
<li><a href="http://www.youtube.com/watch?v=uAGWUcs5ofE" target="_blank" >How to Fetch Youtube or Vimeo Thumbnail</a>&nbsp;&nbsp;<i>(Pro version)</i></li>        
        
        <li><a href="http://www.youtube.com/watch?v=SYH8Yl2SQd4" target="_blank" >How to Create Audio Reverbnation</a>&nbsp;&nbsp;<i>(Pro version)</i></li>    
        <li><a href="http://www.youtube.com/watch?v=PEgfleRf6hg" target="_blank" >How to Create Google Maps</a>&nbsp;&nbsp;<i>(Pro version)</i></li>               
        <li><a href="http://www.youtube.com/watch?v=9cuYyBMKx2k" target="_blank" >How to Insert Image into Media Description</a>&nbsp;&nbsp;<i>(Pro version)</i></li>        
                          
        </ul>
    </div>
  </div> 
 </div>     
 		<!--<div class="metabox-holder">
			<div class="postbox">
				<h3><?php //_e( 'Troubleshooting (issue for version 1.1.7 and below)', 'easmedia' ); ?></h3>
        <div id="easymedia_docs2" style="padding-left:10px !important;">
        <ul style="list-style: square; position:relative; margin-left:15px;">
        <li><a href="http://wordpress.org/extend/plugins/easy-media-gallery/faq/" target="_blank" >Images not showing up</a></li>
        <li><a href="http://wordpress.org/extend/plugins/easy-media-gallery/faq/" target="_blank" >FAQ (Wordpress.org)</a></li>       
        </ul>
    </div>
    </div>    
 </div>-->
  </div> 
	<?php 
}

/*-------------------------------------------------------------------------------*/
/*   Comparison Page
/*-------------------------------------------------------------------------------*/
function easmedia_create_comparison_page() {
    $easmedia_comparison_page = add_submenu_page('edit.php?post_type=easymediagallery', 'Comparison', __('UPGRADE to PRO', 'easmedia'), 'edit_posts', 'comparison', 'easymedia_comparison');
}
add_action( 'admin_menu', 'easmedia_create_comparison_page' );

function easmedia_put_compare_style() {
	if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'comparison' ){
		wp_enqueue_style( 'easymedia-comparison-css' );	
		wp_enqueue_script( 'easymedia-comparison-js' );
		}
}
add_action( 'admin_enqueue_scripts', 'easmedia_put_compare_style' );

/*-------------------------------------------------------------------------------*/
/*   Generate Comparison Page
/*-------------------------------------------------------------------------------*/
function easymedia_comparison() {
?>

<!-- DC Pricing Tables:3 Start -->

    <div class="wrap">
        <div id="icon-edit" class="icon32 icon32-posts-easymedia"><br /></div>
        <h2><?php _e('Comparison', 'easmedia'); ?></h2>
         <div class="emg-badge-com"><div class="badge-com-item"><img src="<?php echo plugins_url( 'images/best-seller.png' , dirname(__FILE__) ) ?>" /></div><div class="badge-com-item"><img src="<?php echo plugins_url( 'images/satisfaction-guaranteed.png' , dirname(__FILE__) ) ?>" /></div><div class="badge-com-item"><img src="<?php echo plugins_url( 'images/excelent-support.png' , dirname(__FILE__) ) ?>" /></div></div>       
  <div class="tsc_pricingtable03 tsc_pt3_style1" style="margin-bottom:110px; height:1330px;">
    <div class="caption_column">
      <ul>
        <li class="header_row_1 align_center radius5_topleft"><?php emg_share(); ?></li>
        <li class="header_row_2">
          <h2 class="caption">Easy Media Gallery</h2>
        </li> 
        <li class="row_style_2"><span>License</span></li>
        <li class="row_style_4"><span>Single Image</span></li>
        <li class="row_style_2"><span>Video Media</span></li>       
        <li class="row_style_4"><span>Audio Media</span></li>
        <li class="row_style_2"><span>HTML5 Video/Audio (mp4, webm, ogv)</span></li>   
        <li class="row_style_4"><span>Auto Fetch Youtube/Vimeo Thumbnail</span><span class="newftr"></span></li>    
        <li class="row_style_2"><span>Photo Albums</span><a target="_blank" href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-gallery-and-photo-albums-demo/" style="text-decoration:underline !important;"> Click for Sample</a>&nbsp;&nbsp;<span class="newftr"></span></li>                 
        <li class="row_style_4"><span>Image Slider</span><a target="_blank" href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-wordpress-image-slider-plugin/" style="text-decoration:underline !important;"> Click for Sample</a>&nbsp;&nbsp;<span class="newftr"></span></li>
        <li class="row_style_2"><span>Grid Gallery</span><a target="_blank" href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-gallery-grid-galleries-plugin/" style="text-decoration:underline !important;"> Click for Sample</a></li>  
         <li class="row_style_4"><span>Filterable Media</span><a target="_blank" href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-wordpress-portfolio-plugin/" style="text-decoration:underline !important;"> Click for Sample</a></li>                     
        <li class="row_style_2"><span>Pagination</span><a target="_blank" href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-wordpress-plugin-gallery-using-pagination/" style="text-decoration:underline !important;"> Click for Sample</a>&nbsp;&nbsp;<span class="newftr"></span></li>        
        <li class="row_style_4"><span>Link Media</span></li>
        <li class="row_style_2"><span>Google Maps / Street View</span></li>
        <li class="row_style_4"><span>Custom CSS</span></li>
        <li class="row_style_2"><span>Custom Columns</span></li>
        <li class="row_style_4"><span>Custom Content</span></li>
        <li class="row_style_2"><span>Custom Media Alignment</span></li>
        <li class="row_style_4"><span>Custom Thumbnail Size</span></li>
        <li class="row_style_2"><span>Image &amp; Video Custom Size</span></li>
        <li class="row_style_4"><span>Unlimited colors and layout</span></li>
        <li class="row_style_2"><span>Place media wherever you want</span></li>
        <li class="row_style_4"><span>Media Style</span><a target="_blank" href="http://ghozylab.com/" style="text-decoration:underline !important;"> Click for Sample</a></li>
        <li class="row_style_2"><span>Pattern Overlay</span></li>
        <li class="row_style_4"><span>Powerfull Control Panel </span> <a href="<?php echo plugins_url( 'images/pro-version-control-panel.png' , dirname(__FILE__) ) ?>   " style="text-decoration:underline !important;">Screenshot</a></li>
        <li class="row_style_2"><span>Advanced Shortcode </span><a href="<?php echo plugins_url( 'images/pro-version-shortcode-manager.png' , dirname(__FILE__) ) ?>" style="text-decoration:underline !important;">Screenshot</a></li>
        <li class="row_style_4"><span>Facebook, Twitter &amp; Pinterest share buttons</span></li>
         <li class="row_style_2"><span>AJAX page/post load Support</span></li>
        <li class="row_style_2"><span>WP Multisite</span></li>
        <li class="row_style_4"><span>Support</span></li>
        <li class="row_style_2"><span>Update</span></li>
        <li class="footer_row"></li>
      </ul>
    </div>
    <div class="column_1">
      <ul>
        <li class="header_row_1 align_center">
          <h2 class="col1">Lite</h2>
        </li>
        <li class="header_row_2 align_center">
          <h1 class="col1">Free</h1>
        </li>
        <li class="row_style_3 align_center">None</li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>  
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>  
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>      
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>        
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>        
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>      
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>        
        <li class="row_style_3 align_center"><span>max 3 columns</span></li>
        <li class="row_style_1 align_center"><span>title &amp; subtitle ONLY</span></li>
        <li class="row_style_3 align_center"><span>center</span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center"><span>light</span></li>
        <li class="row_style_3 align_center"><span>3 patterns</span></li>
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
		<li class="row_style_3 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_1 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
         
        <li class="footer_row"></li>
      </ul>
    </div>
    
    <div class="column_2">
      <ul>
        <li class="header_row_1 align_center">
          <h2 class="col2">Pro</h2>
        </li>
        <li class="header_row_2 align_center">
          <h1 class="col2">$<span><?php echo EASYMEDIA_PRO_PRICE; ?></span></h1>
        </li>
        <li class="row_style_4 align_center"><span style="font-weight: bold; color:#F77448; font-size:14px;">1 Site</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li> 
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li> 
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>                             
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span>up to 8 columns</span></li>
        <li class="row_style_2 align_center"><span>title, subtitle &amp; unlimited content</span></li>
        <li class="row_style_4 align_center"><span>left, right, center</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span>light, dark &amp; transparent</span></li>
        <li class="row_style_4 align_center"><span>15 patterns</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
		<li class="row_style_4 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_2 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_4 align_center"><span>1 Month</span></li>
        <li class="row_style_2 align_center"><span>1 Year</span></li>
        <li class="footer_row"><a target="_blank" href="http://ghozylab.com/plugins/ordernow.php?order=pro&utm_source=lite&utm_medium=comparisonpage&utm_campaign=order" class="tsc_buttons2 red">Upgrade Now</a></li>
      </ul>
    </div>    
    
    <div class="column_2">
      <ul>
        <li class="header_row_1 align_center">
          <h2 class="col2">Pro+</h2>
        </li>
        <li class="header_row_2 align_center">
          <h1 class="col2">$<span><?php echo EASYMEDIA_PRICE; ?></span></h1>
        </li>
        <li class="row_style_4 align_center"><span style="font-weight: bold; color:#F77448; font-size:14px;">3 Sites</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li> 
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li> 
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>                             
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span>up to 8 columns</span></li>
        <li class="row_style_2 align_center"><span>title, subtitle &amp; unlimited content</span></li>
        <li class="row_style_4 align_center"><span>left, right, center</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span>light, dark &amp; transparent</span></li>
        <li class="row_style_4 align_center"><span>15 patterns</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
		<li class="row_style_4 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_2 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_4 align_center"><span>1 year</span></li>
        <li class="row_style_2 align_center"><span>1 year</span></li>
        <li class="footer_row"><a target="_blank" href="http://ghozylab.com/plugins/ordernow.php?order=proplus&utm_source=lite&utm_medium=comparisonpage&utm_campaign=order" class="tsc_buttons2 red">Upgrade Now</a></li>
      </ul>
    </div>
    <div class="column_2">
      <ul>
        <li class="header_row_1 align_center">
          <h2 class="col2">Pro++</h2>
        </li>
        <li class="header_row_2 align_center">
          <h1 class="col2">$<span><?php echo EASYMEDIA_PLUS_PRICE; ?></span></h1>
        </li>
        <li class="row_style_4 align_center"><span style="font-weight: bold; color:#F77448; font-size:14px;">5 Sites</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li> 
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li> 
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>                             
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span>up to 8 columns</span></li>
        <li class="row_style_2 align_center"><span>title, subtitle &amp; unlimited content</span></li>
        <li class="row_style_4 align_center"><span>left, right, center</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span>light, dark &amp; transparent</span></li>
        <li class="row_style_4 align_center"><span>15 patterns</span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_4 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_2 align_center"><span class="pricing_yes"></span></li>
		<li class="row_style_4 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_2 align_center"><span class="pricing_no"></span></li>
        <li class="row_style_4 align_center"><span>1 year</span></li>
        <li class="row_style_2 align_center"><span>1 year</span></li>
        <li class="footer_row"><a target="_blank" href="http://ghozylab.com/plugins/ordernow.php?order=proplusplus&utm_source=lite&utm_medium=comparisonpage&utm_campaign=order" class="tsc_buttons2 red">Upgrade Now</a></li>
      </ul>
    </div>    
     <div class="column_4">
      <ul>
        <li class="header_row_1 align_center">
          <h2 class="col2">Developer</h2>
        </li>
        <li class="header_row_2 align_center">
          <h1 class="col2">$<span><?php echo EASYMEDIA_DEV_PRICE; ?></span></h1>
        </li>
        <li class="row_style_3 align_center"><span style="font-weight: bold; color: #F77448; font-size:14px;">15 Sites</span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li> 
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>  
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>                            
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span>up to 8 columns</span></li>
        <li class="row_style_1 align_center"><span>title, subtitle &amp; unlimited content</span></li>
        <li class="row_style_3 align_center"><span>left, right, center</span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_1 align_center"><span>light, dark &amp; transparent</span></li>
        <li class="row_style_3 align_center"><span>15 patterns</span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
		<li class="row_style_3 align_center"><span class="pricing_yes"></span></li>        
        <li class="row_style_1 align_center"><span class="pricing_yes"></span></li>
        <li class="row_style_3 align_center"><span>1 year</span></li>
        <li class="row_style_1 align_center"><span>1 year</span></li>
        <li class="footer_row"><a target="_blank" href="http://ghozylab.com/plugins/ordernow.php?order=dev&utm_source=lite&utm_medium=comparisonpage&utm_campaign=order" class="tsc_buttons2 red">Upgrade Now</a></li>
      </ul>
    </div>   
    
    
    </div>
  </div>
<!-- DC Pricing Tables:3 End -->
<div class="tsc_clear"></div> <!-- line break/clear line -->
<?php
}

/*-------------------------------------------------------------------------------*/
/*   Metabox Donation
/*-------------------------------------------------------------------------------*/
function easmedia_donate_metabox() {
$emgdonate = '<p>';
$emgdonate .= '<span style="color:#666666;margin-left:2px; font-size:12px;">If you love Easy Media Gallery, any donation would be appreciated! It helps to continue the development and support of the plugin.</span><br /><br />
<a id="easymediadonatebtn" class="thickbox"><img style="cursor:pointer;" src="'.plugins_url( 'images/btn_donate_LG.gif' , dirname(__FILE__) ).'" width="92" height="26" alt="Donate Us" ></a>';
$emgdonate .= '</p>';
echo $emgdonate;
}

add_filter('admin_footer','easmedia_donate_metabox_form');
function easmedia_donate_metabox_form(){
$emgdonatefrm = '<div id="easymedia_donate" style="display:none">';	
$emgdonatefrm .= '<p style="padding:1px;"><span style="color:#666666;margin-left:2px; font-size:12px;">If you love Easy Media Gallery, any donation would be appreciated! It helps to continue the development and support of the plugin.</span></p><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="BDUYH4QK698CY">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>';
$emgdonatefrm .= '</div>';
echo $emgdonatefrm;
}

/*-------------------------------------------------------------------------------*/
/*  Add WordPress Pointers 
/*-------------------------------------------------------------------------------*/

add_action( 'admin_enqueue_scripts', 'easmedia_pointer_pointer_header' );
function easmedia_pointer_pointer_header() {
    $enqueue = false;

    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

    if ( ! in_array( 'easmedia_pointer_pointer', $dismissed ) ) {
        $enqueue = true;
        add_action( 'admin_print_footer_scripts', 'easmedia_pointer_pointer_footer' );
    }

    if ( $enqueue ) {
        // Enqueue pointers
        wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_style( 'wp-pointer' );
    }
}

function easmedia_pointer_pointer_footer() {
    $pointer_content = '<h3>Thank You!</h3>';
	  $pointer_content .= '<p>You&#39;ve just installed Easy Media Gallery Lite Version. Click <a class="close" href="edit.php?post_type=easymediagallery&page=docs">here</a> to watch video tutorials and user guide plugin.</p>';
?>

<script type="text/javascript">// <![CDATA[
jQuery(document).ready(function($) {
	
if (typeof(jQuery().pointer) != 'undefined') {	
    $('#menu-posts-easymediagallery').pointer({
        content: '<?php echo $pointer_content; ?>',
        position: {
            edge: 'left',
            align: 'center'
        },
        close: function() {
            $.post( ajaxurl, {
                pointer: 'easmedia_pointer_pointer',
               action: 'dismiss-wp-pointer'
            });
        }
    }).pointer('open');
	
}

});
// ]]></script>
<?php
}


?>