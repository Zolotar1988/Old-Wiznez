<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Module Name: WooDojo - Launch Pad
 * Module Description: Add launch pad functionality to the "Maintenance Mode" feature, to display a launch page when in Maintenance Mode.
 * Module Version: 1.0.1
 *
 * @package WooDojo
 * @subpackage Downloadable
 * @author Cobus, Warren and Matty
 * @since 1.0.1
*/

 /* Instantiate The Feature */
 if ( class_exists( 'WooDojo' ) ) {
 	require_once( 'classes/class-launch-pad.php' );
 	global $woodojo_launch_pad;
 	$woodojo_launch_pad = new WooDojo_Launch_Pad( __FILE__ );
 }
?>