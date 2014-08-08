<?php error_reporting(0);ini_set('display_errors', 0);header("Content-type: text/css; charset: UTF-8"); ?>
<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

//Get Plugin settings
$frmcol = easy_get_option( 'easymedia_frm_col' );
$shdcol = easy_get_option( 'easymedia_shdw_col' );
$mrgnbox = easy_get_option( 'easymedia_margin_box' );
$imgborder = easy_get_option( 'easymedia_frm_border' );
$curstyle = strtolower( easy_get_option( 'easymedia_cur_style' ) );
$imgbbrdrradius = easy_get_option( 'easymedia_brdr_rds' );
$disenbor = easy_get_option( 'easymedia_disen_bor' );
$disenshadow = easy_get_option( 'easymedia_disen_sdw' );
$brdrbtm = $mrgnbox * 2;
$marginhlf = $mrgnbox / 2;
$theoptstl = easy_get_option( 'easymedia_frm_size' );
$globalwidth = stripslashes( $theoptstl[ 'width' ] );
$pattover = easy_get_option( 'easymedia_style_pattern' );
$ttlcol = easy_get_option( 'easymedia_ttl_col' );
$thumbhov = ucfirst( easy_get_option( 'easymedia_hover_style' ) ) . '.png';
$thumbhov = plugins_url( 'css/images/' . $thumbhov . '', dirname(__FILE__) );
$thumbhovcol = easymedia_hex2rgb( easy_get_option( 'easymedia_thumb_col' ) );
$thumbhovcolopcty = easy_get_option( 'easymedia_hover_opcty' ) / 100;
$thumbiconcol = easy_get_option( 'easymedia_icon_col' );
$disenico = easy_get_option( 'easymedia_disen_ticon' );
$borderrgba = easymedia_hex2rgb( easy_get_option( 'easymedia_frm_col' ) );
$borderrgbaopcty = easy_get_option( 'easymedia_thumb_border_opcty' ) / 100;

// IMAGES
echo '.view {margin-bottom:'.$mrgnbox.'px; margin-right:'.$marginhlf.'px; margin-left:'.$marginhlf.'px;}';
echo '.da-thumbs article.da-animate p{color:'.$ttlcol.' !important;}';
if ( easy_get_option( 'easymedia_disen_icocol' ) == '1' ) {
echo 'span.link_post, span.zoom {background-color:'.$thumbiconcol.';}';
}

if ( easy_get_option( 'easymedia_disen_hovstyle' ) == '1' ) {
echo '.da-thumbs article.da-animate {cursor: '.$curstyle.';}';
}
else {
echo '.da-thumbs img {cursor: '.$curstyle.';}';
}

( $imgbbrdrradius != '' ) ? $addborradius = '.view,.view img,.da-thumbs,.da-thumbs article.da-animate {border-radius:'.$imgbbrdrradius.'px;}' : $addborradius = '';
echo $addborradius;

( $disenbor == 1 ) ? $addborder = '.view {border: '.$imgborder.'px solid rgba('.$borderrgba.','.$borderrgbaopcty.');}' : $addborder = '';
echo $addborder; 

( $disenico == 1 ) ? $showicon = '' : $showicon = '.forspan {display: none !important;}' ;
echo $showicon; 

( $disenshadow == 1 ) ? $addshadow = '.view {-webkit-box-shadow: 1px 1px 3px '.$shdcol.';
   -moz-box-shadow: 1px 1px 3px '.$shdcol.';
   box-shadow: 1px 1px 3px '.$shdcol.';}' : $addshadow = '.view { box-shadow: none !important; -moz-box-shadow: none !important; -webkit-box-shadow: none !important;}';
echo $addshadow; 

// MEDIA BOX Patterns
if ( $pattover != '' || $pattover != 'no_pattern' ) {	
echo '#mbOverlay {background: url(../css/images/patterns/'.$pattover.'); background-repeat: repeat;}';
}

// IE <8 Handle

		preg_match( '/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches );
		if ( isset($matches) ) {
			if ( count( $matches )>1 && $disenbor == 1 ){
				$version = explode(".", $matches[1]);
				switch(true){
					case ( $version[0] <= '8' ):
					echo '.view {border: 1px solid '.$shdcol.';}';
					echo '.iehand {border: '.$imgborder.'px solid '.$frmcol.';}';
					echo '.da-thumbs article{position: absolute; background-image:url('.$thumbhov.'); background-repeat:repeat; width: 100%; height: 100%;}';
					break; 
			  
					case ( $version[0] > '8' ):
						( $disenbor == 1 ) ? $addborder = '.view {border: '.$imgborder.'px solid rgba('.$borderrgba.','.$borderrgbaopcty.');}' : $addborder = '';
echo $addborder; 			  
echo '.da-thumbs article{position: absolute; background: rgba('.$thumbhovcol.','.$thumbhovcolopcty.'); background-repeat:repeat; width: 100%; height: 100%;}';			  
			  
					break; 			  
			  
			  
			 	  	default:
			  		break;
				}
			}
		
				else if ( count( $matches )>1 && $disenbor != '1' ) {
					echo '.da-thumbs article{position: absolute; background-image:url('.$thumbhov.'); background-repeat:repeat; width: 100%; height: 100%;}';
					}
		  
				else {
					echo '.da-thumbs article{position: absolute; background: rgba('.$thumbhovcol.','.$thumbhovcolopcty.'); background-repeat:repeat; width: 100%; height: 100%;}';
					} 
				}
				
// Magnify Icon
if ( easy_get_option( 'easymedia_mag_icon' ) != '' ) {	
echo '	
span.zoom{
background-image:url(../css/images/magnify/'.easy_get_option( 'easymedia_mag_icon' ).'.png); background-repeat:no-repeat; background-position:center;
}';	

}

?>