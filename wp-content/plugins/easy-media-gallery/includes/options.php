<?php

/*------------------------------------------------------------------------------------*/
/*  Easy Media Gallery Lite Version
/*  Option Control Panel
/*  require_once emg-settings.php
/*------------------------------------------------------------------------------------*/

// VARIABLES
$emgplugname = "Easy Media Gallery";
$theshort = "easymedia";

// Set the Options Array
$theopt = array (
 
array( "name" => $emgplugname." Options",
	"type" => "title"),
 
array( "name" => "General",
	"type" => "section"),
array( "type" => "open"),

array( "name" => "Columns",
	"desc" => "Select default Column for each media block, you can also set this manually when adding shortcode.<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and you can choose up to 8 columns.",
	"id" => $theshort."_columns",
	"type" => "select",
	"options" => array( "1", "2", "3" ),
	"std" => "3"),	
	
array( "name" => "Alignment",
	"desc" => "Select thumbnails alignment. Default: Center<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and you can choose center, left or right alignment.",
	"id" => $theshort."_alignstyle",
	"type" => "select",
	"options" => array( "none", "Center" ),
	"std" => "Center"),		
	
array( "name" => "Full-size image limit",
	"desc" => "Set the limit for image width on full-size, image height will adjust automatically. Default: 940px.",
	"id" => $theshort."_img_size_limit",
	"type" => "slider",
	"usestep" => "0",	
	"max" => "2560",
	"pixopr" => "px",	
	"std" => "940"),	
	
array( "name" => "Video Size",
	"desc" => "Adjust the video size. Default size for all video : width 700px,  height 400px.<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and get HTML5 video player.",
	"id" => $theshort."_vid_size",
	"type" => "size",
	'std' => array( 'width' => '700', 'height' => '400' ),
	"pixopr" => "px"),
	
array( "name" => "Video Auto Play",
	"desc" => "Use this to enable/disable video auto play. This feature only work on MP4, M4V, Youtube, Vimeo, Google Video and Veoh.<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and get HTML5 video player.",
	"id" => $theshort."_disen_autoplv",
	"type" => "checkbox",
	"std" => "1"),		
		
array( "name" => "Audio Auto Play",
	"desc" => "Use this to enable/disable audio auto play. This feature only work for MP3 media type.<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and get HTML5 audio player.",
	"id" => $theshort."_disen_autopl",
	"type" => "checkbox",
	"std" => "1"),	
	
array( "name" => "Audio Loop",
	"desc" => "Use this to enable/disable audio loop. This feature only work for MP3 media type.<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and get HTML5 audio player.",
	"id" => $theshort."_disen_audio_loop",
	"type" => "checkbox",
	"std" => "1"),	
	
array( "name" => "Audio Volume",
	"desc" => "Set the default volume for audio media. <br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and get HTML5 audio player.",
	"id" => $theshort."_audio_vol",
	"type" => "slider",
	"step" => "10",
	"usestep" => "1",	
	"max" => "100",
	"pixopr" => "",	
	"std" => "100"),	
	
array( "type" => "close"),
array( "name" => "Style Options",
	"type" => "section"),
array( "type" => "open"),

array( "name" => "Media Box Style",
	"desc" => "Select media box style. Default: Light<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and get more styles such as Dark Style or Transparent Style.",
	"id" => $theshort."_box_style",
	"type" => "select",
	"options" => array( "Light" ),
	"std" => "1"),	

array( "name" => "Cursor",
	"desc" => "Move the mouse pointer on top the media to see the effect. Default: Pointer",
	"id" => $theshort."_cur_style",
	"type" => "select",
	"options" => array( "Pointer", "Crosshair", "Move", "Default" ),
	"std" => "3"),
	
array( "name" => "Zoom Icon",
	"desc" => "Select Zoom Icon. Default: Icon-0",
	"id" => $theshort."_mag_icon",
	"type" => "select",
	"options" => array( "Icon-0", "Icon-1", "Icon-2", "Icon-3", "Icon-4" ),
	"std" => "Icon-0"),
	
array( "name" => "Thumbnail Size",
	"desc" => "Allow for editing thumbnail sizes. Default sizes width 180px, height : 180px.<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and you can set this manually when adding shortcode.",
	"id" => $theshort."_frm_size",
	"type" => "size",
	'std' => array( 'width' => '160', 'height' => '160' ),
	"pixopr" => "px"),		
	
array( "name" => "Thumbnail Border Color",
	"desc" => "Define thumbnail Border Color. Default color is white (#FFF)",
	"id" => $theshort."_frm_col",
	"type" => "color",
	"std" => "#FFFFFF"),	
	
array( "name" => "Thumbnail Title Color",
	"desc" => "Define thumbnail Title Color. Default color is dark grey",
	"id" => $theshort."_ttl_col",
	"type" => "color",
	"std" => "#C7C7C7"),	
	
array( "name" => "Thumbnail Border Radius",
	"desc" => "This style not work in IE7-8 due to lack of border-radius support. Default border radius : 3px",
	"id" => $theshort."_brdr_rds",
	"type" => "slider",
	"usestep" => "0",	
	"max" => "100",
	"pixopr" => "px",		
	"std" => "3"),		
	
array( "name" => "Thumbnail Hover Color",
	"desc" => "Define your Thumbnail Hover Color. Default color is dark (#000000). NOTE : This function may not work on IE browser.",
	"id" => $theshort."_thumb_col",
	"type" => "color",
	"std" => "#000000"),	
	
array( "name" => "Thumbnail Hover Opacity",
	"desc" => "Opacity of the thumbnail that appears on media mouseover. Default : 40%",
	"id" => $theshort."_hover_opcty",
	"type" => "slider",
	"max" => "100",
	"step" => "10",
	"usestep" => "1",
	"pixopr" => "%",		
	"std" => "40"),			
	
array( "name" => "Pattern Overlay",
	"desc" => "Define pattern for box overlay.<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and you can choose up to 15 patterns.",
	"id" => $theshort."_style_pattern",
	"type" => "pattern",
	"std" => "pattern-01.png"),	
	
array( "name" => "Thumbnail Border",
	"desc" => "Enable or disable border. Set the value to 0 to hide the border.",
	"id" => $theshort."_disen_bor",
	"type" => "checkbox",
	"std" => "1"),	
	
array( "name" => "Hover Style",
	"desc" => "Enable or disable image hover effect.<br><br><a target='_blank' href='http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/?utm_source=lite&utm_medium=settingspage&utm_campaign=gotopricing'>Upgrade</a> to PRO and you can set the title to show permanent on top or bottom position.",
	"id" => $theshort."_disen_hovstyle",
	"type" => "checkbox",
	"std" => "1"),
	
array( "type" => "close"),
array( "name" => "Miscellaneous",
	"type" => "section"),
array( "type" => "open"),
	
array( "name" => "Enable Plugin",
	"desc" => "Enable or temporarily disable this plugin.",
	"id" => $theshort."_disen_plug",
	"type" => "checkbox",
	"std" => "1"),	
	
array( "name" => "Disable right click",
	"desc" => "Enable or disable right click on images.",
	"id" => $theshort."_disen_rclick",
	"type" => "checkbox",
	"std" => "1"),		
	
array( "name" => "Keep data when uninstall/update",
	"desc" => "Enable this option to keep all plugin data and settings before you uninstall for upgrade this plugin.",
	"id" => $theshort."_disen_databk",
	"type" => "checkbox",
	"std" => "1"),		
	
array( "name" => "Upgrade Notification",
	"desc" => "Enable/Disable upgrade notifications.",
	"id" => $theshort."_disen_admnotify",
	"type" => "checkbox",
	"std" => "1"),	
	
array( "name" => "Dashboard News",
	"desc" => "Enable or temporarily disable dashboard news.",
	"id" => $theshort."_disen_dasnews",
	"type" => "checkbox",
	"std" => "1"),		
	
array( "name" => "Enable AJAX support",
	"desc" => "Turn this on if your site use AJAX page load.",
	"id" => $theshort."_disen_ajax",
	"type" => "checkbox",
	"std" => ""),
	
array( "name" => "Ajax container ID",
	"desc" => "Provide the ids of the parent tag that handled by AJAX. Commonly used are #content or #main. Please contact us if you have any problems.",
	"id" => $theshort."_ajax_con_id",
	"type" => "text",
	"std" => "#content"),				
	
array( "name" => "Plugin Core Compatibility Mode",
	"desc" => "Could solve issues on old plugin core versions, Use it ONLY IF you notice some issues. Default: core-1.4.5-min",
	"id" => $theshort."_plugin_core",
	"type" => "select",
	"options" => array( "core-1.4.5-min", "core-1.4.5-full-compat-yc", "core-1.2.5-core-yc", "none" ),
	"std" => "core-1.4.5-min"),	
	
array( "name" => "Wordpress Info",
	"desc" => "You can provide this wordpress information to our support staff when you face any issue with this plugin.",
	"id" => $theshort."_plugin_wpinfo",
	"type" => "textareainfo",
	"std" => ""),		
	
array( "type" => "close")
	
);


/*------------------------------------------------------------------------------------*/
/*  RESTORE DEFAULT SETTINGS
/*------------------------------------------------------------------------------------*/

function easymedia_restore_to_default($cmd) {
	
	if ( $cmd == 'reset' ) {
		
		delete_option( 'easy_media_opt' );
		
				$theshort = "easymedia";
		
				$arr = array(
				$theshort.'_deff_init' => '1',
				$theshort.'_box_style' => 'Light',				
				$theshort.'_frm_col' => '#FFFFFF',
				$theshort.'_ttl_col' => '#C7C7C7',
				$theshort.'_shdw_col' => '#4A4A4A',
				$theshort.'_thumb_col' => '#000000',
				$theshort.'_overlay_col' => '#F7F0D7',
				$theshort.'_pbar_col' => '#2187e7',					
				$theshort.'_overlay_opcty' => '80',
				$theshort.'_lightbox_style' => 'EMG Lightbox ( Default )',
				$theshort.'_hovstyle' => 'Style 01 ( Default )',
				$theshort.'_mag_icon' => 'Icon-0',				
				$theshort.'_thumb_border_opcty' => '100',							
				$theshort.'_icon_col' => '#474747',				
				$theshort.'_hover_style' => 'Dark',
				$theshort.'_hover_opcty' => '40',
				$theshort.'_slide_intv' => '5',					
				$theshort.'_img_size_limit' => '940',
				$theshort.'_filterstyle' => 'Default-Grey',
				$theshort.'_disen_fb' => '1',
				$theshort.'_disen_twt' => '1',				
				$theshort.'_disen_pin' => '1',				
				$theshort.'_disen_gp' => '1',	
				$theshort.'_disen_eml' => '1',				
				$theshort.'_disen_upchk' => '1',
				$theshort.'_disen_dasnews' => '1',	
				$theshort.'_disen_preload_ef' => '1',	
				$theshort.'_disen_galnav' => '1',			
				$theshort.'_columns' => '3',
				$theshort.'_thumbcrop' => 'Center',
				$theshort.'_filter_pos' => 'Center',
				$theshort.'_pag_style' => 'Light',
				$theshort.'_margin_box' => '12',				
				$theshort.'_disen_showcntr' => '1',
				$theshort.'_disen_showcntrthumb' => '1',								
				$theshort.'_pag_effect' => 'flipInX',	
				$theshort.'_carousel_effect' => 'easeInOutCubic',
				$theshort.'_badge_pos' => 'Top Right',
				$theshort.'_badge_col' => 'Blue',											
				$theshort.'_audio_vol' => '100',
				$theshort.'_disen_audio_loop' => '1',				
				$theshort.'_frm_border' => '5',
				$theshort.'_plugin_core' => 'core-1.4.5-min',
				$theshort.'_cur_style' => 'Pointer',
				$theshort.'_alignstyle' => 'Center',
				$theshort.'_style_pattern' => 'pattern-01.png',
				$theshort.'_cls_pos' => 'Bottom',	
				$theshort.'_sos_pos' => 'Bottom',							
				$theshort.'_disen_ticon' => '1',
				$theshort.'_disen_icocol' => '1',	
				$theshort.'_disen_style_man' => '1',					
				$theshort.'_brdr_rds' => '3',
				$theshort.'_disen_bor' => '1',	
				$theshort.'_disen_rclick' => '1',				
				$theshort.'_disen_ajax' => '',							
				$theshort.'_disen_sdw' => '1',
				$theshort.'_disen_databk' => '1',				
				$theshort.'_disen_autopl' => '1',
				$theshort.'_disen_autoplv' => '1',								
				$theshort.'_disen_hovstyle' => '1',
				$theshort.'_ajax_con_id' => '#content',								
				$theshort.'_disen_admnotify' => '1',				
				$theshort.'_disen_plug' => '1',
				$theshort.'_disen_galsshow' => '1',
				$theshort.'_text_showall' => 'Show All',
				$theshort.'_text_backtoalbum' => 'BACK to ALBUM',				
				$theshort.'_countsing' => 'image',
				$theshort.'_countplural' => 'images',								
				$theshort.'_frm_size' => array('width' => '160','height' => '160',),
				$theshort.'_vid_size' => array('width' => '700','height' => '400',)											
				);
				update_option('easy_media_opt', $arr);
				return;
	}
}



/*------------------------------------------------------------------------------------*/
/*  1ST CONFIGURATION
/*------------------------------------------------------------------------------------*/

function easymedia_1st_config() {

				$thshort = "easymedia";
				
				$arr = array(
				$thshort.'_deff_init' => '1',
				$thshort.'_frm_col' => '#FFFFFF',
				$thshort.'_ttl_col' => '#C7C7C7',
				$thshort.'_shdw_col' => '#4A4A4A',
				$thshort.'_icon_col' => '#474747',
				$thshort.'_box_style' => 'Light',
				$thshort.'_overlay_col' => '#F7F0D7',
				$thshort.'_thumb_col' => '#000000',
				$thshort.'_pbar_col' => '#2187e7',				
				$thshort.'_hover_style' => 'Dark',
				$thshort.'_lightbox_style' => 'EMG Lightbox ( Default )',
				$thshort.'_hovstyle' => 'Style 01 ( Default )',
				$thshort.'_mag_icon' => 'Icon-0',				
				$thshort.'_plugin_core' => 'core-1.4.5-min',			
				$thshort.'_hover_opcty' => '40',
				$thshort.'_overlay_opcty' => '80',	
				$thshort.'_thumb_border_opcty' => '100',	
				$thshort.'_slide_intv' => '5',								
				$thshort.'_img_size_limit' => '940',
				$thshort.'_filterstyle' => 'Default-Grey',
				$thshort.'_columns' => '3',
				$thshort.'_margin_box' => '12',
				$thshort.'_audio_vol' => '100',
				$thshort.'_disen_audio_loop' => '1',								
				$thshort.'_frm_border' => '5',
				$thshort.'_cur_style' => 'Pointer',
				$thshort.'_alignstyle' => 'Center',
				$thshort.'_pag_effect' => 'flipInX',
				$thshort.'_carousel_effect' => 'easeInOutCubic',
				$thshort.'_badge_pos' => 'Top Right',
				$thshort.'_disen_showcntr' => '1',
				$thshort.'_disen_showcntrthumb' => '1',
				$thshort.'_badge_col' => 'Blue',
				$thshort.'_thumbcrop' => 'Center',
				$thshort.'_filter_pos' => 'Center',	
				$thshort.'_pag_style' => 'Light',						
				$thshort.'_disen_fb' => '1',
				$thshort.'_disen_twt' => '1',				
				$thshort.'_disen_pin' => '1',
				$thshort.'_disen_gp' => '1',	
				$thshort.'_disen_eml' => '1',
				$thshort.'_disen_upchk' => '1',
				$thshort.'_disen_rclick' => '1',	
				$thshort.'_cls_pos' => 'Bottom',
				$thshort.'_sos_pos' => 'Bottom',											
				$thshort.'_disen_dasnews' => '1',	
				$thshort.'_disen_preload_ef' => '1',	
				$thshort.'_disen_galnav' => '1',				
				$thshort.'_brdr_rds' => '3',
				$thshort.'_disen_bor' => '1',
				$thshort.'_disen_sdw' => '1',
				$thshort.'_style_pattern' => 'pattern-01.png',
				$thshort.'_disen_ticon' => '1',
				$thshort.'_disen_icocol' => '1',
				$thshort.'_disen_databk' => '1',
				$thshort.'_ajax_con_id' => '#content',				
				$thshort.'_disen_ajax' => '',								
				$thshort.'_disen_style_man' => '1',			
				$thshort.'_disen_hovstyle' => '1',
				$thshort.'_disen_plug' => '1',
				$thshort.'_disen_autopl' => '1',
				$thshort.'_disen_autoplv' => '1',	
				$thshort.'_disen_admnotify' => '1',	
				$thshort.'_disen_galsshow' => '1',	
				$theshort.'_text_showall' => 'Show All',
				$theshort.'_text_backtoalbum' => 'BACK to ALBUM',				
				$theshort.'_countsing' => 'image',
				$theshort.'_countplural' => 'images',																	
				$thshort.'_frm_size' => array( 'width' => '160','height' => '160', ),
				$thshort.'_vid_size' => array( 'width' => '700','height' => '400', ));
				
				update_option( 'easy_media_opt', $arr, '', 'yes' );
				return;
}


?>