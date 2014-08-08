<?php
require_once( '../../../wp-load.php' );
require_once( dirname(__FILE__) . '/functions.php' );
require_once( dirname(__FILE__) . '/DropboxUploader.php' );
require_once( dirname(__FILE__) . '/a.charset.php' );

if( isset( $_POST["count"] ) ) { 
	$count = $_POST["count"];
	dropNow( $count, 'run2' );
}
?>