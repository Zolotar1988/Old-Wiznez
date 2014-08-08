<?php
/**
 * Module Name: WooDojo - Muso Widgets Pack
 * Module Description: Add widgets for musicians and music lovers alike. Connect your WordPress installation to Last.fm and SoundCloud.
 * Module Version: 1.0.4
 *
 * @package WooDojo
 * @subpackage Downloadable
 * @author Patrick & Matty
 * @since 1.0.0
*/

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

 /* Instantiate WooDojo Muso Widgets Pack */
 if ( class_exists( 'WooDojo' ) ) {
 	require_once( 'classes/class-woodojo-muso-widgets-pack.php' );
 	global $woodojo_muso_widgets_pack;
 	$woodojo_muso_widgets_pack = new WooDojo_Muso_Widgets_Pack( __FILE__ );
 }
?>