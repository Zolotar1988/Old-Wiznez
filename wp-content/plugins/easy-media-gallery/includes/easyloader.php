<?php
error_reporting(0);ini_set('display_errors', 0);
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

if ( isset( $_GET['id'] ) && !empty( $_GET['id'] ) ) {
ajax_req_handle( $_GET['id'] );
}

else {
}

function ajax_req_handle( $id ) {
	
	$boxmediattl = get_post_meta( $id, 'easmedia_metabox_title', true );
	$boxmediasbttl = get_post_meta( $id, 'easmedia_metabox_sub_title', true );
	$imgsrc = get_post_meta( $id, 'easmedia_metabox_img', true );
	$mediatype = get_post_meta( $id, 'easmedia_metabox_media_type', true );
		
	switch ( $mediatype ) {
		case 'Single Image':
		$boxlink = $imgsrc;
	        break;		
			
		case 'Video':
		$boxlink = get_post_meta( $id, 'easmedia_metabox_media_video', true );
	        break;
			
		case 'Audio':
		$boxlink = get_post_meta( $id, 'easmedia_metabox_media_audio', true );
	        break;			
					
	}
	
	if ( $boxmediasbttl == '' ) {$boxmediasbttl = 'none';}
	if ( $boxmediattl == '' ) {$boxmediattl = 'none';}

$therest = array( $boxmediattl,$boxmediasbttl );
echo json_encode( $therest );
exit;
}

?>