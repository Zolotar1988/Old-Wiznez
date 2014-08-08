<?php
/*
Plugin Name: Mass Messaging in Buddypress
Plugin URI: http://www.stormation.info/portfolio-item/mass-messaging-in-buddypress/
Version: 1.2.0
Author: Eliott Robson
Author URI: http://stormation.info
Description: Ever wanted to send a message to many people at once? Now you can, introducing - Mass Messaging.
Tested up to: 3.9
*/

/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function mass_messaging_init_loader() {
    require( dirname( __FILE__ ) . '/mass-messaging.php' );
}
add_action( 'bp_include', 'mass_messaging_init_loader' );

/* If you have code that does not need BuddyPress to run, then add it here. */
?>