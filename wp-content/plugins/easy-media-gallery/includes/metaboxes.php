<?php


/*-----------------------------------------------------------------------------------*/
/*  Featured Image Meta
/*-----------------------------------------------------------------------------------*/
function emg_customposttype_image_box() {
	remove_meta_box( 'postimagediv', 'easymediagallery', 'side' );
	remove_meta_box( 'emediagallerydiv', 'easymediagallery', 'side' );
	add_meta_box( 'categorydiv', __( 'Media Categories' ), 'easymediagallery_categories_meta_box', 'easymediagallery', 'normal', 'high' );
	add_meta_box( 'donatediv', __( 'Donate Us' ), 'easmedia_donate_metabox', 'easymediagallery', 'side', 'default' );
}
add_action( 'do_meta_boxes', 'emg_customposttype_image_box' );

/*-----------------------------------------------------------------------------------*/
/*	get rid of WordPress SEO metabox - http://wordpress.stackexchange.com/a/91184/2015
/*-----------------------------------------------------------------------------------*/
	function emg_prefix_remove_wp_seo_meta_box() {
	    remove_meta_box( 'wpseo_meta', 'easymediagallery', 'normal' );
		remove_meta_box( 'aiosp', 'easymediagallery', 'normal' );
	}
    add_action( 'add_meta_boxes', 'emg_prefix_remove_wp_seo_meta_box', 100000 );
	
/*-----------------------------------------------------------------------------------*/
/*	META VIDEO CORE
/*-----------------------------------------------------------------------------------*/
if ( strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post-new.php' ) || strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post.php' ) ) {
	
	add_action( "admin_head", 'emg_load_wp_enqueue' );
	
			function emg_load_wp_enqueue() {
				
				if ( get_post_type( get_the_ID() ) == 'easymediagallery' ) {
					
					if ( EMG_WP_VER == "g35" ) {wp_enqueue_media();}					
					
					wp_enqueue_script( 'jquery-multi-sel' );
					wp_enqueue_style( 'jquery-multiselect-css' );
					wp_enqueue_style( 'jquery-ui-themes-redmond' );
					wp_enqueue_script( 'jquery-ui' );
					wp_enqueue_script( 'easymedia-jplayer-js', plugins_url( 'js/jplayer/jquery.jplayer.min.js' , __FILE__ ) );
					wp_enqueue_script( 'cpscript', plugins_url( 'functions/funcscript.js' , __FILE__ ) );
					wp_enqueue_script( 'jquery-i-button', plugins_url( 'js/jquery/jquery.ibutton.js' , __FILE__ ) );
					wp_enqueue_style( 'metabox-ibuttoneditor', plugins_url( 'css/ibutton.css' , __FILE__ ), false, EASYMEDIA_VERSION );
					wp_enqueue_style( 'easymedia-jplayer-css', plugins_url( 'css/jplayer/skin/pink.flag/jplayer.pink.flag.css' , __FILE__ ), false, EASYMEDIA_VERSION );	
					wp_enqueue_style( 'jquery-messi-css' );
					wp_enqueue_script( 'jquery-messi-js' );	
									
			}
		}	
	
	add_action( "admin_footer", 'emg_showhide_metabox' );
	function emg_showhide_metabox() { 
	if ( get_post_type( get_the_ID() ) == 'easymediagallery' ) { 
	?>
    
        <script type="text/javascript">
			/*<![CDATA[*/
			/* Easy Media Gallery */  
			
jQuery(document).ready(function($) {
	
		jQuery("#easmedia_metabox_media_video").change(function() {
			vdo_url = jQuery("#easmedia_metabox_media_video").val();
				if (vdo_url.match('http://new\.livestream\.com')) {
				vdols = vdo_url.replace(/.*src="([^&]*)\?autoPlay.*/,'$1');
				jQuery('#easmedia_metabox_media_video').val(vdols);
			}
		})
		
		jQuery("#easmedia_metabox_media_video").change(function() {
			vdo_url = jQuery("#easmedia_metabox_media_video").val();
				if (vdo_url.match(/ustream\.tv/i)) {
				vdols = vdo_url.replace(/.*src="([^&]*)\?v=.*/,'$1');
				jQuery('#easmedia_metabox_media_video').val(vdols);
			}
		})		
	
		jQuery('select[id=easmedia_metabox_media_type] option').each(function() {
    if (jQuery(this).text().indexOf('PRO ONLY') >= 0) jQuery(this).attr('disabled', 'disabled');
});
	
// MESSI POPUP	
		jQuery('#videofrmt').on('click', function() {
          new Messi('<p> - <strong>Youtube 1 :</strong> http://www.youtube.com/watch?v=JaNH56Vpg-A</p><p> - <strong>Youtube 2 :</strong> http://www.youtube.com/embed/JaNH56Vpg-A</p><p> - <strong>Youtube 3 :</strong> http://youtu.be/BWmWAPb_z90</p><p> - <strong>Youtube Playlist :</strong> http://www.youtube.com/watch?v=S_Az2Zg5OLc&list=PLFrmfElpm4lwVff3JvmtSJzxYFFb2093q</p><p> - <strong>Vimeo :</strong> http://vimeo.com/798022</p><p> - <strong>DailyMotion :</strong> http://www.dailymotion.com/video/xzefrs_steven-spielberg-s-obama_shortfilms#.UX8g_O8kZM4</p><p> - <strong>MetaCafe :</strong> http://www.metacafe.com/watch/2185365/spot_electrabel_gdf_suez_happy_new_year_2009/</p><p> - <strong>Facebook :</strong> https://www.facebook.com/video/embed?video_id=557900707562656</p><p> - <strong>Veoh :</strong> http://www.veoh.com/watch/v20943320Dz9Z45Qj</p><p> - <strong>Flickr video :</strong> http://www.flickr.com/photos/bhl1/2402027765/in/pool-video</p><p> - <strong>Google video :</strong> http://video.google.com/videoplay?docid=-8111235669135653751</p><p> - <strong>Quietube + Youtube :</strong> http://quietube.com/v.php/http://www.youtube.com/watch?v=b5Ff2X_3P_4</p><p> - <strong>Quietube + Vimeo :</strong> http://quietube.com/v.php/http://vimeo.com/2295261</p><p> - <strong>Tudou :</strong> http://www.tudou.com/programs/view/KG2UG_U4DMY/</p><p> - <strong>YouKu :</strong> http://v.youku.com/v_show/id_XNDI1MDkyMDQ</p>', {title: 'Sample video format', modal: true});
		  });	
		  		  
		jQuery('#medvidtut').on('click', function() {
          new Messi('<iframe width="853" height="480" src="http://www.youtube.com/embed/htxwZw_aPF0" frameborder="0" allowfullscreen></iframe>', {title: 'Video Tutorial', modal: true});
		  });			  
	  	
		jQuery('#medsingimgtut').on('click', function() {
          new Messi('<iframe width="853" height="480" src="http://www.youtube.com/embed/dXFBNY5t6E8" frameborder="0" allowfullscreen></iframe>', {title: 'Video Tutorial', modal: true});
		  });	
		  		  
		jQuery('#medaudiotut').on('click', function() {
          new Messi('<iframe width="853" height="480" src="http://www.youtube.com/embed/Bsn-CB5Hpbw" frameborder="0" allowfullscreen></iframe>', {title: 'Video Tutorial', modal: true});
		  });			  
	
// -------- DELETE MEDIA IMAGE (AJAX)
			function easmedia_img_media_remv(type) {
				var data = {
				action: 'easmedia_img_media_remv',
				pstid: '<?php echo get_the_ID(); ?>',
				type: type
				};
			
				jQuery.post(ajaxurl, data, function(response) {
					if (response == 1 && type == 'image') {
						jQuery('#upload_'+type+'').val(''); jQuery("#imgpreviewbox").hide("slow"); jQuery(".deleteimage").hide("slow");}
					else if (response == 1 && type == 'audio') {
						jQuery('#upload_'+type+'').val(''); jQuery(".deleteaudio").hide("slow"); jQuery("#audioprev").hide("slow");}						
					else {
						alert('Ajax request failed, please refresh your browser window.');
						}
					});
			}
			
// -------------------------------------------------------------------------------

	jQuery('a.deleteimage').click(function() {
		var answer = confirm('Are you sure you want to delete this image?');
			if (answer){ 	
				var type = 'image';
				easmedia_img_media_remv(type);
		}
			else {}
	});
	
		jQuery('a.deleteaudio').click(function() {
		var answer = confirm('Are you sure you want to delete this audio?');
			if (answer){ 	
				var type = 'audio';
				easmedia_img_media_remv(type);
		}
			else {}
	});
// -------- END DELETE MEDIA IMAGE


      jQuery("#easmedia_metabox_media_type").multiselect({
		multiple: false,
		noneSelectedText: "Select",
		selectedList: 1,
		header: false
	});
			
			jQuery("#notifynovalidaudio").hide("slow");				
			jQuery("#upload_audio").change(function() {
				aud_url = jQuery("#upload_audio").val();
				if (jQuery("#upload_audio").val().length > 0 ) {
				IsValidAuUrl(aud_url);					
					} 
					else if (jQuery("#upload_audio").val().length <= 0 ) {
						jQuery("#notifynovalidaudio").hide("slow");
						jQuery(".deleteaudio").hide("fast");
						jQuery("#audioprev").hide("fast");
						} 
				});								
			});   
									
function IsValidAuUrl1(aurl1) {
	jQuery("#jquery_jplayer_1").jPlayer("destroy");
		jQuery("#jquery_jplayer_1").jPlayer({
			ready: function (event) {
				jQuery(this).jPlayer("setMedia", {
					mp3: aurl1 });
					},
					swfPath: "<?php echo plugins_url( 'swf/' , __FILE__ ); ?>",
					supplied: "mp3",
					volume: 100,
					wmode: "window"
	});
}

			
 function IsValidAuUrl(aurl) {

					jQuery(function () {
						jQuery.ajax({
							url : aurl,
							success : function () {
								IsValidAuUrl1(aurl);
								jQuery("#notifynovalidaudio").hide("slow");
								jQuery(".deleteaudio").show("fast");
								jQuery("#audioprev").show("slow");
								},
								
								fail : function (xhr, d, e) {
									if (xhr.status != 200) {
										jQuery("#notifynovalidaudio").show("slow");
										jQuery('#notifynovalidaudio').html("Unable to load audio from url above. Please make sure it's the <strong>full</strong> URL and a valid one at that.");
										jQuery(".deleteaudio").hide("fast");
										jQuery("#audioprev").hide("fast");
								}
							}
						});								
					});
			}  
	 
			
 function IsValidImageUrl(url) {
    jQuery("<img>", {
        src: url,
        fail: function() {
			jQuery("#notifynovalidimg").show("slow");
			jQuery('#notifynovalidimg').html("Unable to load image from url above. Please make sure it's the <strong>full</strong> URL and a valid one at that.");
			   // jQuery('#imgthumbnailprv').attr('src','http://placehold.it/300x190');
				jQuery("#imgpreviewbox").hide("slow");
				jQuery(".deleteimage").hide("slow");

			},
        load: function() {

				var data = {
				action: 'easymedia_imgresize_ajax',
				imgurl: jQuery("#upload_image").val(),
				limiter: '210'
			};
			
			jQuery('#imgthumbnailprv').html('<div style="height: 30px;" class="img_loading"></div>');
			jQuery.post(ajaxurl, data, function(response) {
				jQuery("#imgpreviewbox").hide();
				var sprdata = response.split(",");
				jQuery("#imgpreviewbox").css("width", sprdata[1] + "px"); jQuery("#imgpreviewbox").css("height", sprdata[2] + "px");
				jQuery("#imgpreviewbox").fadeIn(500);
				jQuery("#notifynovalidimg").hide("slow");
				jQuery('#imgthumbnailprv').attr('src',sprdata[0]);
				
				
				jQuery("#imgpreviewbox").show("slow");
				jQuery(".deleteimage").show("fast"); 
					
			});	
			return true;
			}
    });
}       
  	/*]]>*/
		</script> 
		<?php
		}
	}	
} 

/**
 * Add a custom Meta Box
 *
 * @param array $meta_box Meta box input data
 */
 
function easmedia_add_meta_box( $meta_box )
{
    if ( !is_array( $meta_box ) ) return false;
    
    // Create a callback function
    $callback = create_function( '$post,$meta_box', 'easmedia_create_meta_box( $post, $meta_box["args"] );' );
    add_meta_box( $meta_box['id'], $meta_box['title'], $callback, $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box );
}

/**
 * Create content for a custom Meta Box
 *
 * @param array $meta_box Meta box input data
 */
function easmedia_create_meta_box( $post, $meta_box )
{
						if ( EMG_WP_VER == "l35" ) {
						$uploaderclass = 'thickbox button add_media';
						$emghref = "media-upload.php?type=image&TB_iframe=1";
						$isdatacnt = ' data-editor="content" ';
						$emgepver = EMG_WP_VER;	
						
						} else {
							$uploaderclass = 'button';
							$emghref = "#";
							$isdatacnt = '';
							$emgepver = EMG_WP_VER;
							}
	
    if ( !is_array( $meta_box ) ) return false;
    
    if ( isset( $meta_box['description'] ) && $meta_box['description'] != '' ){
    	echo '<p>'. $meta_box['description'] .'</p>';
    }
    
	wp_nonce_field( basename( __FILE__ ), 'easmedia_meta_box_nonce' );
	echo '<table class="form-table easmedia-metabox-table">';
 
	foreach ( $meta_box['fields'] as $field ){
		// Get current post meta data
		$meta = get_post_meta( $post->ID, $field['id'], true );
		echo '<tr class="'. $field['id'] .'"><th><label for="'. $field['id'] .'"><strong>'. $field['name'] .' '. ( $field['defflimit'] == '1' ? '<br>(Default limit : ' .easy_get_option( 'easymedia_img_size_limit' ) . 'px)': ''  ).'</strong>
			  <span>'. $field['desc'] .'</span></label></th>';
		
		switch( $field['type'] ){	
			case 'text':
				echo '<td><input type="text" name="easmedia_meta['. $field['id'] .']" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['std']) .'" size="30" /></td>';
				break;	
				
			case 'video':
				echo '				<div id="videofrmt" style="text-decoration:underline;font-weight:bold;cursor:Pointer; color:#1A91F2 !important; margin-bottom:8px;">Sample video format</div>
				<div id="medvidtut" style="text-decoration:underline;font-weight:bold;cursor:Pointer; color:#1A91F2 !important; margin-bottom:8px;">Video Tutorial</div><td>				
				
				<input type="text" name="easmedia_meta['. $field['id'] .']" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['std']) .'" size="30" />
<div style="color:red; display:none;" id="emgvideopreview"></div>				
<div class="videobox" id="" style="display:none;">
<span class="roll" ></span>
<img id="videothumbnailprv" style="display:none;" src="http://placehold.it/300x190" height="190" width="300"/></div>
				</td>';
				break;	
			
			case 'images': 
			
$dsplynone = 'display:none;';		
if ( get_post_meta( $post->ID, 'easmedia_metabox_img', true ) ) {
$attid = wp_get_attachment_image_src( emg_get_attachment_id_from_src( get_post_meta( $post->ID, 'easmedia_metabox_img', true ) ), 'full' );
$curimgpth = easymedia_imgresize( $attid[0], '210', 'on', $attid[1], $attid[2] );
$curimgpth = explode(",", $curimgpth);

( $curimgpth[0] > '10' ) ? $curimgpth[0] = $curimgpth[0] : $curimgpth[0] = '';
( $curimgpth[0] > '10' ) ? $dsplynone = '' : $dsplynone = 'display:none;';	
} else {
	 $dsplynone = 'display:none;';
	 $curimgpth[0] = '';
	 $curimgpth[1] = '';
	 $curimgpth[2] = '';
	}	

echo '<div id="medsingimgtut" style="text-decoration:underline;font-weight:bold;cursor:Pointer; color:#1A91F2 !important; margin-bottom:8px;">Video Tutorial</div><td id="imgupld"><input id="upload_image" type="text" name="easmedia_meta['. $field['id'] .']" value="'. ($meta ? $meta : $field['std']) .'" style="margin-bottom:5px;"/><div style="color:red;" id="notifynovalidimg"></div>
<div class="addmed"><a rel="image-'.$emgepver.'" class="' . $uploaderclass . '" title="Add Media" '.$isdatacnt.' href="'.$emghref.'"><span class="emg-media-buttons-icon"></span>Add Media</a></div>
<a onClick="return false;" style="'. $dsplynone .';" class="deleteimage button" title="Delete Image" href="#"><span class="emg-media-buttons-icon-del"></span>Delete Image</a><div style="'. $dsplynone .' width:'.$curimgpth[1].'px; height:'.$curimgpth[2].'px" id="imgpreviewbox" class="imgpreviewboxc">
<img id="imgthumbnailprv" src="' . $curimgpth[0] . '"/></div>
</td>';
			    break;

			case 'audio':
		
$adsplynone = 'display:none;';
$curaudiopth = get_post_meta($post->ID, 'easmedia_metabox_media_audio', true);
( $curaudiopth != '' ) ? $adsplynone = '' : $adsplynone = 'display:none;';	

if ( $curaudiopth != '' ) { echo '
<script type="text/javascript">
    jQuery(function () {
		var thisaudiourl = "' .$curaudiopth. '";
    IsValidAuUrl1(thisaudiourl);
    });
    </script>	
'; }

echo '<div id="medaudiotut" style="text-decoration:underline;font-weight:bold;cursor:Pointer; color:#1A91F2 !important; margin-bottom:8px;">Video Tutorial</div><td id="audioupld"><input id="upload_audio" type="text" name="easmedia_meta['. $field['id'] .']" value="'. ($meta ? $meta : $field['std']) .'" style="margin-bottom:5px;"/><div style="color:red;" id="notifynovalidaudio"></div><div class="addmed"><a rel="audio-'.$emgepver.'" class="' . $uploaderclass . '" title="Add Media" '.$isdatacnt.' href="'.$emghref.'"><span class="emg-media-buttons-icon"></span>Add Media</a></div>
<a onClick="return false;" style="'. $adsplynone .';" class="deleteaudio button" title="Delete Audio" href="#"><span class="emg-media-buttons-icon-del"></span>Delete Audio</a>

<div style="'. $adsplynone .';" id="audioprev" class="vidpreviewboxc">
	<div id="jquery_jplayer_1" class="jp-jplayer"></div>
		<div id="jp_container_1" class="jp-audio">
			<div class="jp-type-single">
				<div class="jp-gui jp-interface">
					<ul class="jp-controls">
						<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
						<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
						<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
						<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
						<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
						<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
					</ul>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
		</div>

</div>
</td>';
			    break;				
	
			case 'select':
				echo'<td><select style="width:200px;" name="easmedia_meta['. $field['id'] .']" id="'. $field['id'] .'">';
				foreach ( $field['options'] as $key => $option ){
					echo '<option value="' . $option . '"';
					if ( $meta ){ 
						if ( $meta == $option ) echo ' selected="selected"'; 
					}
					echo'>'. $option .'</option>';
				}
				echo'</select></td>';
				break;

			case 'radio':
				echo '<td>';
				foreach ( $field['options'] as $key => $option ){
					echo '<label class="radio-label"><input type="radio" name="easmedia_meta['. $field['id'] .']" value="'. $key .'" class="radio"';
					if ( $meta ){ 
						if ( $meta == $key ) echo ' checked="checked"'; 
					} else {
						if ( $field['std'] == $key ) echo ' checked="checked"';
					}
					echo ' /> '. $option .'</label> ';
				}
				echo '</td>';
				break;
			
			case 'color':
			    if ( array_key_exists('val', $field) ) $val = ' value="' . $field['val'] . '"';
			    if ( $meta ) $val = ' value="' . $meta . '"';
			    echo '<td>';
                echo '<div class="colorpicker-wrapper">';
                echo '<input type="text" id="'. $field['id'] .'_cp" name="easmedia_meta[' . $field['id'] .']"' . $val . ' />';
                echo '<div id="' . $field['id'] . '" class="colorpicker"></div>';
                echo '</div>';
                echo '</td>';
			    break;
				
			case 'checkbox':
			    echo '<td>';
			    $val = '';
                if ( $meta ) {
                    if ( $meta == 'on' ) $val = ' checked="checked"';
                } else {
                    if ( $field['std'] == 'on' ) $val = ' checked="checked"';
                }

                echo '<input type="hidden" name="easmedia_meta['. $field['id'] .']" value="off" />
                <input class="switch" type="checkbox" id="'. $field['id'] .'" name="easmedia_meta['. $field['id'] .']" value="on"'. $val .' /> ';
			    echo '</td>';
			    break;


			case 'checkboxoptdef':
			    echo '<td>';
			    $val = '';
                if ( $meta ) {
                    if ( $meta == 'on' ) { $val = ' checked="checked"';
					}
                } else {

                    if ( $field['std'] == 'on' ) { $val = ' checked="checked"';
					}
                }

                echo '<div style="margin-bottom:15px !important;"><input type="hidden" name="easmedia_meta['. $field['id'] .']" value="off" />
                <input class="switch" type="checkbox" id="'. $field['id'] .'" name="easmedia_meta['. $field['id'] .']" value="on" '. $val .' /></div>
				';
			    echo '</td>';
			    break;	
				
			case 'checkboxopt':
			    echo '<td>';
			    $val = '';
                if ( $meta ) {
                    if ( $meta == 'on' ) { $val = ' checked="checked"';
					
					echo '<script type="text/javascript">
    jQuery(function () {
	jQuery("#vidcustomsize").hide("slow");
    });
    </script>'; }
	
		else {
		
	echo '<script type="text/javascript">
    jQuery(function () {
	jQuery("#vidcustomsize").show("slow");
    });
    </script>';
	}
                } else {
						echo '<script type="text/javascript">
    jQuery(function () {
	jQuery("#vidcustomsize").show("slow");
    });
    </script>';
					
                    if ( $field['std'] == 'on' ) { $val = ' checked="checked"';
					
	echo '<script type="text/javascript">
    jQuery(function () {
	jQuery("#vidcustomsize").hide("slow");
    });
    </script>';
					}
	else {	echo '<script type="text/javascript">
    jQuery(function () {
	jQuery("#vidcustomsize").show("slow");
    });
    </script>';
	}
                }

                echo '<div style="margin-bottom:15px !important;"><input type="hidden" name="easmedia_meta['. $field['id'] .']" value="off" />
                <input class="switch" type="checkbox" id="'. $field['id'] .'" name="easmedia_meta['. $field['id'] .']" value="on" '. $val .' /></div>
			<div id="vidcustomsize" style="border-top: 1px solid #ccc; padding-top: 10px;">
				 	Video custom size : <div style="margin-top:10px; margin-bottom:10px;"><strong>Width</strong> <input style="margin-right:5px !important; margin-left:3px; width:43px !important; float:none !important;" name="easmedia_meta['. $field['id'] .'_'.$field['width'].']" id="'. $field['id'] .'[width]" type="text" value="' .get_post_meta($post->ID, 'easmedia_metabox_media_video_size_'. $field['width'] .'', true).'" />  ' .$field['pixopr']. '

<span style="border-right:solid 1px #CCC;margin-left:9px; margin-right:10px !important; "></span>

 	<strong>Height</strong> <input style="margin-left:3px; margin-right:5px !important; width:43px !important; float:none !important;" name="easmedia_meta['. $field['id'] .'_'.$field['height'].']" id="'. $field['id'] .'[height]" type="text" value="' .get_post_meta($post->ID, 'easmedia_metabox_media_video_size_'. $field['height'] .'', true).'" /> ' .$field['pixopr']. ' </div></div>

				';
			    echo '</td>';
			    break;				
				
		}
		
		echo '</tr>';
	}
 
	echo '</table>';
}

/*-----------------------------------------------------------------------------------*/
/*	Register related Scripts and Styles
/*-----------------------------------------------------------------------------------*/
function easmedia_metabox_media_scripts() {
	wp_enqueue_script( 'thickbox' );
}
function easmedia_metabox_media_styles() {
	wp_enqueue_style( 'thickbox' );
}
if ( EMG_WP_VER == "l35" && get_post_type( get_the_ID() ) == 'easymediagallery' ) {
add_action( 'admin_enqueue_scripts', 'easmedia_metabox_media_scripts' );
add_action( 'admin_print_styles', 'easmedia_metabox_media_styles' );
}


	// SELECT MEDIA METABOX
add_action( 'add_meta_boxes', 'easmedia_metabox_work' );
function easmedia_metabox_work(){
	    $meta_box = array(
		'id' => 'easmedia_metaboxmediatypeselect',
		'title' =>  __( 'Media Options', 'easmedia' ),
		'description' => __( '<div class="emginfobox"><span class="emg_blink">Upgrade to PRO</span> and you can select <a href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-gallery-grid-galleries-plugin/" target="_blank">Grid Gallery</a>, <a href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-gallery-and-photo-albums-demo/" target="_blank">Photo Albums</a>, <a href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/best-wordpress-portfolio-plugin/" target="_blank">Filterable Media</a>, HTML5 Video/Audio, Google Maps/Street View, embed from Soundcloud or Reverbnation and also Link to specific URL. You can learn more and see version comparison <a href="edit.php?post_type=easymediagallery&page=comparison">here</a> or go to Pro Version DEMO <a href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/" target="_blank">here</a></div><br>Select videos, images, gallery or audio files.', 'easmedia' ),
		'page' => 'easymediagallery',
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
			array(
		
					'name' => __( 'Media Type', 'easmedia' ),
					'desc' => __( 'Choose the item type.', 'easmedia' ),
					'id' => 'easmedia_metabox_media_type',
					'type' => 'select',
					'defflimit' => '0',
					'options' => array( 'Select', 'Single Image', 'Video', 'Audio', 'Link (PRO ONLY)', 'Google Maps (PRO ONLY)', 'Multiple Images (PRO ONLY)' ),
					'std' => 'Select')
				),				
				
	);
    easmedia_add_meta_box( $meta_box );	
	
	
	// VIDEO METABOX
	    $meta_box = array(
		'id' => 'easmedia_metaboxmediavideo',
		'title' =>  __( 'Video Options', 'easmedia' ),
		'description' => __( 'Paste video URL to field below.', 'easmedia' ),
		'page' => 'easymediagallery',
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
		
			array(
		
					'name' => __( 'Video URL', 'easmedia' ),
					'desc' => __( '', 'easmedia' ),
					'id' => 'easmedia_metabox_media_video',
					'type' => 'video',
					'defflimit' => '0',
					'std' => ''
					
				 ),
							
			array(
					'name' => __( 'Video Size', 'easmedia' ),
					'desc' => __( 'If ON, video size will use the default settings on the control panel.', 'easmedia' ),
					'id' => 'easmedia_metabox_media_video_size',
					'type' => 'checkboxopt',
					'defflimit' => '0',
					'width' => 'vidw',
					'height' => 'vidh',
					'std' => 'on',
					"pixopr" => 'px',
					)
				)
	);
    easmedia_add_meta_box( $meta_box );
	
	
	
	// AUDIO METABOX		
	    $meta_box = array(
		'id' => 'easmedia_metaboxmediaaudio',
		'title' =>  __( 'Audio Options', 'easmedia' ),
		'description' => __( 'Upload audio or paste audio URL on field below. Is it possible to use audio external source.', 'easmedia' ),
		'page' => 'easymediagallery',
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
		
			array(
		
					'name' => __( 'Audio Path', 'easmedia' ),
					'desc' => __( '', 'easmedia' ),
					'id' => 'easmedia_metabox_media_audio',
					'type' => 'audio',
					'defflimit' => '0',
					'std' => ''
					)
				 
				)
	);
    easmedia_add_meta_box( $meta_box );		
			

	// SINGLE IMAGE (FOR ALL MEDIA) 
	    $meta_box = array(
		'id' => 'emediaimagediv',
		'title' =>  __( 'Select Image', 'easmedia' ),
		'description' => __( 'You can upload image with supported file types: jpg, jpeg, gif, png.', 'easmedia' ),
		'page' => 'easymediagallery',
		'context' => 'normal',
		'priority' => 'default',
		'fields' => array(
			array(
					'name' => __( 'Image URL', 'easmedia' ),
					'desc' => __( 'Select or upload your image.', 'easmedia' ),
					'id' => 'easmedia_metabox_img',
					'type' => 'images',
					'defflimit' => '0',
					'std' => ''
				 ),
							
			array(
					'name' => __( 'Full-size image control', 'easmedia' ),
					'desc' => __( 'If ON, image which exceeds the specified size limit will be automatically resized. You can change image size limit through plugin control panel.', 'easmedia' ),
					'id' => 'easmedia_metabox_media_image_opt1',
					'type' => 'checkboxoptdef',
					'defflimit' => '1',
					'std' => 'on'
					)
				)					
				
	);
    easmedia_add_meta_box( $meta_box );		


	// MEDIA DESC METABOX
    $meta_box = array(
		'id' => 'easmedia_metabox_media_desc',
		'title' =>  __( 'Media Information', 'easmedia' ),
		'description' => __( 'Input basic info for this media.', 'easmedia' ),
		'page' => 'easymediagallery',
		'context' => 'normal',
		'priority' => 'low',
		'fields' => array(
			array(
					'name' => __( 'Media Title', 'easmedia' ),
					'desc' => __( 'Enter a media title.', 'easmedia' ),
					'id' => 'easmedia_metabox_title',
					'type' => 'text',
					'defflimit' => '0',
					'std' => ''
				),
				
			array(
					'name' => __( 'Media Subtitle', 'easmedia' ),
					'desc' => __( 'You can use this field for (ex: author, title track, etc.)', 'easmedia' ),
					'id' => 'easmedia_metabox_sub_title',
					'type' => 'text',
					'defflimit' => '0',
					'std' => ''
				)			
		)
	);
    easmedia_add_meta_box( $meta_box );

}

//-----------------------------------------------------------------------------------------------------------------

/**
 * Save custom Meta Box
 *
 * @param int $post_id The post ID
 */
function easmedia_save_meta_box( $post_id ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	if ( !isset( $_POST['easmedia_meta'] ) || !isset( $_POST['easmedia_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['easmedia_meta_box_nonce'], basename( __FILE__ ) ) )
		return;
	
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) ) return;
	}
			foreach( $_POST['easmedia_meta'] as $key => $val ) {
			if ( !is_array( $val ) ) {
				$_POST['easmedia_meta'][$key] = stripslashes( $val );
			}
			else {
				$_POST['easmedia_meta'][$key] = array();
				foreach( $val as $arr_val ) {$_POST['easmedia_meta'][$key][] = stripslashes( $arr_val );}
			}
		}
		// save data
		foreach( $_POST['easmedia_meta'] as $key => $val ) {
			delete_post_meta( $post_id, $key );
			add_post_meta( $post_id, $key, $_POST['easmedia_meta'][$key], true ); 
		}
}
add_action( 'save_post', 'easmedia_save_meta_box' );

?>