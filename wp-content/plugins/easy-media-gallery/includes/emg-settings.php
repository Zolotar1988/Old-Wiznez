<?php
error_reporting(0);ini_set('display_errors', 0);
/*------------------------------------------------------------------------------------*/
/*  Plugin Control Panel ( Thanks & Credit to Nettuts A.K.A http://net.tutsplus.com ) 
/*  require_once options.php
/*------------------------------------------------------------------------------------*/

function register_easy_setting() {
	register_setting( 'easy_options_group', 'easy_media_opt', 'easmedia_validate_options' ); 
} 
add_action( 'admin_init', 'register_easy_setting' );


function spg_add_admin() {
	 if (  isset($_POST['_wp_http_referer']) && strpos( $_REQUEST['_wp_http_referer'], 'post_type=easymediagallery&page=emg_settings' ) !== FALSE && isset( $_REQUEST['_wpnonce'] ) && check_admin_referer( 'easy_options_group-options' ) ) { // Thanks to Nikolai Tschacher for this security patch
global $emgplugname, $theshort, $theopt;
	if ( is_admin() && ( isset( $_GET['page'] ) == 'emg_settings' ) && ( isset( $_GET['post_type'] ) == 'easymediagallery' ) ){		
		if ( isset( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {
			$curtosv = get_option( 'easy_media_opt' );
		foreach ( $theopt as $theval ) {
			if ( isset( $theval['id'] ) ) {
				if ( isset( $_REQUEST[ $theval['id'] ] ) ) {
					$curtosv[ $theval['id'] ] = $_REQUEST[ $theval['id'] ];
					}
					else {
						$curtosv[ $theval['id'] ] = '';
						}
				}
				update_option( 'easy_media_opt', $curtosv );
			}	
	header("Location: edit.php?post_type=easymediagallery&page=emg_settings&saved=true");
die;
				}
	}
}
 
 	add_submenu_page(
		'edit.php?post_type=easymediagallery',
		__('Easy Media Gallery Settings', 'easmedia' ),
		__( 'Settings', 'easmedia' ),
		'manage_options',
		'emg_settings',
		'spg_admin'
	);
	
}


/*
|--------------------------------------------------------------------------
| REGISTER & ENQUEUE SCRIPTS/STYLES ONLY for a Specific Post Type 
|--------------------------------------------------------------------------
*/			
if ( is_admin() && ( isset( $_GET['page'] ) == 'emg_settings' ) && $_GET['page'] == 'emg_settings' ){ //@since 1.2.33
	
	add_action( "admin_head", 'easymedia_admin_head_script' );
	add_action( 'admin_enqueue_scripts', 'easymedia_cp_script' );
	
	function easymedia_cp_script() {

	wp_enqueue_style( 'easymedia-colorpickercss' );	
	wp_enqueue_style( 'easymedia-sldr' );	
	wp_enqueue_style( 'easymedia-ibutton' );			
	wp_enqueue_style( 'easymedia-cpstyles' );	

	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-slider' );
	wp_enqueue_script( 'jquery-ui-widget' );
	wp_enqueue_script( 'jquery-ui-mouse' );

	wp_enqueue_script( 'easymedia-colorpicker' );
	wp_enqueue_script( 'easymedia-jquery-easing' );
	wp_enqueue_script( 'colorpicker-eye' );
	wp_enqueue_script( 'colorpicker-utils' );
	wp_enqueue_script( 'easymedia-cpscript', plugins_url( 'functions/funcscript.js' , __FILE__ ) );
}


	function easymedia_admin_head_script() { ?>


<script type="text/javascript">
/*<![CDATA[*/
/* Easy Media Gallery */
(function($) {
		jQuery(document).ready(function() {
			
// -------- RESET SETTINGS (AJAX)		
	jQuery('a.emgresetnow').click(function() {
		var answer = confirm('Are you sure? This will restore these settings to default.');
			if (answer){ 	
				var cmd = 'reset';
				emg_cp_reset(cmd);
		}
			else {}
	});	
	
			function emg_cp_reset(cmd) {
				var data = {
				action: 'emg_cp_reset',
				security: '<?php echo wp_create_nonce( "easymedia-lite-nonce"); ?>',				
				cmd: cmd,
				};
			
				jQuery.post(ajaxurl, data, function(response) {
					if (response == 1) {
						window.location.href = 'edit.php?post_type=easymediagallery&page=emg_settings&reset=true';
						}						
					else {
						alert('Ajax request failed, please refresh your browser window.');
						}
					});
			}						
						
			// Replace checkboxes with switch
			jQuery("input[type=checkbox].switch").each(function() {
				// Insert switch
				jQuery(this).before('<span class="switch"><span class="background" /><span class="mask" /></span>');
				// Hide checkbox
				jQuery(this).hide();
				if (!jQuery(this)[0].checked) jQuery(this).prev().find(".background").css({left: "-49px"});
				if (jQuery(this)[0].checked) jQuery(this).prev().find(".background").css({left: "-2px"});	
			});
			// Toggle switch when clicked
			jQuery("span.switch").click(function() {
				// Slide switch off
				if (jQuery(this).next()[0].checked) {
					jQuery(this).find(".background").animate({left: "-49px"}, 200);
				// Slide switch on
				} else {
					jQuery(this).find(".background").animate({left: "-2px"}, 200);
				}
				// Toggle state of checkbox
				jQuery(this).next()[0].checked = !jQuery(this).next()[0].checked;
			});
			
			

		/*  Control Panel info box */
		initSlideboxes();
 		function initSlideboxes()
		{
			setTimeout(function()
			{
				jQuery('.infoboxsaveorreset').slideUp("slow");
			}, 2000);
		};
		
		
/* Slider init */
		jQuery(function() {
				 <?php 
				 global $theopt;
				 foreach ( $theopt as $theval ) {
					 if ( $theval['type'] == 'slider' ){
					 $valtmp = easy_get_option( $theval['id'] ); 
				 //echo $valtmp;
				 ?>	
	
        jQuery( '#<?php echo $theval['id']; ?>_slider' ).slider({
            range: 'min',
            min: 0,
            max: <?php echo $theval['max']; ?>,
			
			<?php if ( $theval['usestep'] == '1' ) { ?>
			step: <?php echo $theval['step']; ?>,
			<?php } ?>
            value: '<?php echo $valtmp; ?>',
            slide: function( event, ui ) {
                jQuery( "#<?php echo $theval['id']; ?>" ).val( ui.value );
            	}
        	});
		
		 <?php  }} ?>
		});

	// Pattern Selector
	jQuery('.pattern_overlay').on('click', function() {
		var pattern = jQuery(this).attr('id');
		
		jQuery('.pattern_overlay').removeClass('pattern_selected');
		jQuery(this).addClass('pattern_selected'); 
		jQuery('#easymedia_style_pattern').val(pattern);
	});			
			
	});
})(jQuery);
/*]]>*/
</script>   

<script type="text/javascript">
/*<![CDATA[*/
/**
 *
 * Color picker
 * Author: Stefan Petre www.eyecon.ro
 * 
 * Dependencies: jQuery
 *
 */
 
(function($){
	var initLayout = function() {
		var hash = window.location.hash.replace('#', '');
		var currentTab = $('ul.navigationTabs a')
							.bind('click', showTab)
							.filter('a[rel=' + hash + ']');
		if (currentTab.size() == 0) {
			currentTab = $('ul.navigationTabs a:first');
		}
		showTab.apply(currentTab.get(0));
		$('#colorpickerHolder').ColorPicker({flat: true});
		$('#colorpickerHolder2').ColorPicker({
			flat: true,
			color: '#00ff00',
			onSubmit: function(hsb, hex, rgb) {
				$('#colorSelector2 div').css('backgroundColor', '#' + hex);
			}
		});
		$('#colorpickerHolder2>div').css('position', 'absolute');
		var widt = false;
		$('#colorSelector2').bind('click', function() {
			$('#colorpickerHolder2').stop().animate({height: widt ? 0 : 173}, 500);
			widt = !widt;
		});
		$('#colorpickerField1, #colorpickerField2, #colorpickerField3').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
		})
		.bind('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
		});
				 <?php 
				 
				 global $theopt;
				 foreach ( $theopt as $theval ) {
				 if ( $theval['type'] == 'color' ){
				 $colortmp = easy_get_option( $theval['id'] ); 
				 ?>
				 
				 jQuery('#<?php echo $theval['id']; ?>_picker').children('div').css('backgroundColor', '<?php echo $colortmp; ?>');    
				 jQuery('#<?php echo $theval['id']; ?>_picker').ColorPicker({
					color: '<?php echo $colortmp; ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						//jQuery(this).css('border','1px solid red');
						jQuery('#<?php echo $theval['id']; ?>_picker').children('div').css('backgroundColor', '#' + hex);						
						jQuery('#<?php echo $theval['id']; ?>_picker').next('input').attr('value','#' + hex);
					}
				  });
				  
				  <?php  }} ?>
	};
	
	var showTab = function(e) {
		var tabIndex = $('ul.navigationTabs a')
							.removeClass('active')
							.index(this);
		$(this)
			.addClass('active')
			.blur();
		$('div.tab')
			.hide()
				.eq(tabIndex)
				.show();
	};
	
	EYE.register(initLayout, 'init');
})(jQuery)
/*]]>*/
</script>

<?php } 

}

/*
END REGISTER & ENQUEUE SCRIPTS/STYLES
*/	

/*
|--------------------------------------------------------------------------
| MAIN FORM - DISPLAY ELEMENT
|--------------------------------------------------------------------------
*/		
function spg_admin() {
	global $emgplugname, $theshort, $theopt;
	$i=0;
	$msgicon = plugins_url( 'images/confirm-check.png' , __FILE__ );
if ( isset( $_REQUEST['saved'] ) ) { echo '<script type="text/javascript">
    jQuery(function () {
    jQuery(".infoboxsaveorreset").show("slow");
    });
    </script>';
	$saveresmsg = 'Settings saved...'; }

if ( isset( $_REQUEST['reset'] ) ) { echo '<script type="text/javascript">
    jQuery(function () {
    jQuery(".infoboxsaveorreset").show("slow");
    });
    </script>';
	$saveresmsg = 'Settings reset...'; }
 
?>
<div id="spg_container">
    <div id="header">
      <div class="logo">
      <div class="emg-icon-option-left"></div>
        <div class="emg-cp-title"><h2><?php echo $emgplugname . "  LITE (v " . easymedia_get_plugin_version() . ")"; ?></h2></div>
      </div>
      <div class="emg-icon-option-right"> </div>
      <div style="clear: both;"></div>
    </div>

<div id="main">
<div class="infoboxsaveorreset"><?php echo $saveresmsg; ?></div>
<form method="post">
<div class="sps_wrap">
<div class="sps_opts">


<?php settings_fields('easy_options_group'); ?>

<?php foreach ( $theopt as $theval ) {
switch ( $theval['type'] ) {
	case "open":
	?>
	<?php break;
	case "close":
	?>
 
</div>
</div>
<br />

 
<?php break;
case 'text':
?>

<div class="sps_input sps_text">
	<label for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>
 	<input name="<?php echo $theval['id']; ?>" id="<?php echo $theval['id']; ?>" type="<?php echo $theval['type']; ?>" value="<?php if ( easy_get_option( $theval['id'] ) != "") { echo stripslashes( easy_get_option( $theval['id'] )  ); } else { echo $theval['std']; } ?>" />
 <small><?php echo $theval['desc']; ?></small><div class="clearfix"></div>
 
 </div>
<?php
break;
case 'margin':
?>

<div class="sps_input sps_text">
	<label for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>
 	<input style="width:43px !important;" name="<?php echo $theval['id']; ?>" id="<?php echo $theval['id']; ?>" type="text" value="<?php if ( easy_get_option( $theval['id'] ) != "") { echo stripslashes( easy_get_option( $theval['id'] ) ); } else { echo $theval['std']; } ?>" /> <?php echo $theval['pixopr']; ?>
 <small><?php echo $theval['desc']; ?></small><div class="clearfix"></div>
 
 </div>
<?php
break;
case 'size':

$default = $theval['std'];
$sizeall = easy_get_option( $theval['id'] );
if ( ! is_array( $sizeall ) || empty( $sizeall ) ) {
		$sizeall = $default;
}
?>
	
<div class="sps_input sps_text">
	<label for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>
   
 	<strong>Width</strong> <input style="margin-left:3px; width:43px !important;" name="<?php echo $theval['id']; ?>[width]" id="<?php echo $theval['id']; ?>[width]" type="text" value="<?php if ( $sizeall['width'] != "") { echo stripslashes( $sizeall['width'] ); } else { echo $default; } ?>" /> <?php echo $theval['pixopr']; ?>
    
<span style="border-right:solid 1px #CCC; margin-right:11px; margin-left:9px;"></span>
 	<strong>Height</strong> <input style="margin-left:3px; width:43px !important;" name="<?php echo $theval['id']; ?>[height]" id="<?php echo $theval['id']; ?>[height]" type="text" value="<?php if ( $sizeall['height'] != "") { echo stripslashes( $sizeall['height'] ); } else { echo $default; } ?>" /> <?php echo $theval['pixopr']; ?>

 <small><?php echo $theval['desc']; ?></small><div class="clearfix"></div>
 
 </div>
<?php
break;
case 'textarea':
?>

<div class="sps_input sps_textarea">
	<label for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>
 	<textarea style="vertical-align:top !important;" name="<?php echo $theval['id']; ?>" type="<?php echo $theval['type']; ?>" cols="" rows=""><?php if ( easy_get_option( $theval['id'] ) != "") { echo stripslashes(easy_get_option( $theval['id'] ) ); } else { echo $theval['std']; } ?></textarea>
 <small><?php echo $theval['desc']; ?></small><div class="clearfix"></div>
 
 </div>
  
<?php
break;
case 'textareainfo':
?>

<div class="sps_input sps_textarea">
	<label for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>
 	<textarea id="emgwpinfo" style="vertical-align:top !important;" name="<?php echo $theval['id']; ?>" type="<?php echo $theval['type']; ?>" cols="" rows="" readonly><?php echo easmedia_get_wpinfo(); ?></textarea>
 <small><?php echo $theval['desc']; ?></small><div class="clearfix"></div>
 
 </div>
  
<?php
break;
case 'select':
?>

<div class="sps_input sps_select">
	<label for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>
	
<select name="<?php echo $theval['id']; ?>" id="<?php echo $theval['id']; ?>">
<?php foreach ( $theval['options'] as $option ) { ?>
		<option <?php if ( easy_get_option( $theval['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select>

	<small><?php echo $theval['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;
case "checkbox":
?>

<div class="sps_input sps_checkbox">
	<label for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>
<?php ( easy_get_option( $theval['id'] ) == 1) ? $checked = 'checked="checked"' : $checked = ''; ?>
<input name="<?php echo $theval['id']; ?>" id="<?php echo $theval['id']; ?>" class="switch" type="checkbox" <?php echo $checked; ?> value="1"></input>
	<small><?php echo $theval['desc']; ?></small><div class="clearfix"></div>
 
 </div>

<?php break;
case 'slider':
?>

<div class="sps_input">
	<label for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>
	
    <div id="<?php echo $theval['id']; ?>_slider" ></div><input style="margin-left:10px; width:43px !important;" name="<?php echo $theval['id']; ?>" id="<?php echo $theval['id']; ?>" type="text" value="<?php if ( easy_get_option( $theval['id'] ) != "") { echo stripslashes( easy_get_option( $theval['id'] ) ); } else { echo $theval['std']; } ?>" /> <?php echo $theval['pixopr']; ?>

	<small><?php echo $theval['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;
case "color":
?>

<div class="sps_input sps_text">
<label for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>

<div id="<?php echo $theval['id']; ?>_picker" class="colorSelector"><div></div></div>
<input style="margin-left:3px; width:75px !important;" name="<?php echo $theval['id']; ?>" id="<?php echo $theval['id']; ?>" type="text" value="<?php if ( easy_get_option( $theval['id'] ) != "") { echo stripslashes( easy_get_option( $theval['id'] ) ); } else { echo $theval['std']; } ?>" />
<small><?php echo $theval['desc']; ?></small>
<div class="clearfix"></div>
</div>


<?php break;
case 'pattern':
?>

<div class="sps_input">
	<label style="vertical-align:top !important;" for="<?php echo $theval['id']; ?>"><?php echo $theval['name']; ?></label>
    <input type="hidden" value="<?php if ( easy_get_option( $theval['id'] ) != "") { echo stripslashes( easy_get_option( $theval['id'] ) ); } else { echo $theval['std']; } ?>" name="<?php echo $theval['id']; ?>" id="<?php echo $theval['id']; ?>" />
    
    <div class="pattern_box">
    
                	<div style="float: left;" class="pattern_overlay <?php if (!easy_get_option( $theval['id'] ) || easy_get_option( $theval['id'] ) == 'none') {echo 'pattern_selected';} ?>" id="no_pattern"> no pattern </div>
    
                <?php 
				foreach ( easmedia_patterns_ls() as $pattern ) {
					(easy_get_option( $theval['id'] ) == $pattern) ? $sel = 'pattern_selected' : $sel = '';  
					echo '<div class="pattern_overlay '.$sel.'" id="'.$pattern.'" style="background: url('.plugins_url( 'css/images/patterns/' , dirname(__FILE__) ).$pattern.') repeat top left transparent;"></div>';	
					
				}
				?>  
</div>
	<small><?php echo $theval['desc']; ?></small><div class="clearfix"></div>
</div>


<?php break;
case "section":
$i++;
?>

<div class="sps_section">
<?php $imgpth = plugins_url('images/trans.png' , __FILE__); ?>
<div class="sps_title"><h3><img src="<?php echo $imgpth; ?>" class="inactive" alt="""><?php echo $theval['name']; ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save Changes" class="button button-primary" />
</span><div class="clearfix"></div></div>
<div class="sps_options">

<?php break;
 
}
}
?>
 
<input type="hidden" name="action" value="save" />
<p><a target="_blank" href="http://ghozylab.com/plugins/ordernow.php?order=proplus&utm_source=lite&utm_medium=settingspage&utm_campaign=order" class="tsc_buttons2 red">Upgrade to Pro Version  &nbsp;for only $<?php echo EASYMEDIA_PRICE; ?></a> <span style="color:#666666;margin-left:2px; font-size:11px;">&nbsp; Need More Features? Upgrade to Pro Version!</span></p>
 </div> </div>
 </form>
 </div>
 
<p class="submit">
<a onClick="return false;" class="emgresetnow button-secondary" title="Reset Options" href="#">Reset Options</a>
<span style="color:#666666;margin-left:2px; font-size:11px;"> Use this button to restore these settings to default.</span></p>
 </div>



<?php
}
?>
<?php
//add_action('admin_init', 'add_cp_stylesheet');
add_action('admin_menu', 'spg_add_admin');


/*
|--------------------------------------------------------------------------
| Sanitize and validate input. Accepts an array, return a sanitized array.
|--------------------------------------------------------------------------
*/	
function easmedia_validate_options($input) {
	 // strip html from textboxes
	 if ( isset( $input['text'] ) ) {
		 $input['text'] =  wp_filter_nohtml_kses($input['text']);
		 }
	if ( isset( $input['textarea'] ) ) {
		$input['textarea'] =  wp_filter_nohtml_kses($input['textarea']);
		}
	return $input;
}

?>