<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WooDojo Muso Widgets Pack Settings.
 *
 * @category Downloadables
 * @package WordPress
 * @subpackage WooDojo
 * @author Matty at WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - function __construct()
 * - function init_sections()
 * - function init_fields()
 */

class WooDojo_Muso_Widgets_Pack_Settings extends WooDojo_Settings_API {
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct () {
	    parent::__construct(); // Required in extended classes.
	} // End __construct()
	
	/**
	 * init_sections function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function init_sections () {
		$sections = array();

		$sections['soundcloud'] = array(
			'name' => __('SoundCloud', 'woodojo-muso-widgets-pack'),
			'description' => sprintf( __( 'Display tracks and playlists from your %sSoundCloud%s account.', 'woodojo-muso-widgets-pack' ), '<a href="' . esc_url( 'http://soundcloud.com/' ) . '" target="_blank">', '</a>' )
		);

		$sections['lastfm'] = array(
			'name' => __('Last.fm', 'woodojo-muso-widgets-pack'),
			'description' => sprintf( __( 'Display top tracks, top artists and recent tracks from your %sLast.fm%s account.', 'woodojo-muso-widgets-pack' ), '<a href="' . esc_url( 'http://last.fm/' ) . '" target="_blank">', '</a>' )
		);

		$sections['general'] = array(
			'name' => __('General Settings', 'woodojo-muso-widgets-pack'),
			'description' => __( 'Settings applying to all widgets in the WooDojo Muso Widgets Pack.', 'woodojo-muso-widgets-pack' )
		);

		$this->sections = $sections;
	} // End init_sections()
	
	/**
	 * init_fields function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function init_fields () {
		$fields = array();

		$fields['enable_soundcloud'] = array(
		    'name' => __( 'Enable SoundCloud Widgets', 'woodojo-muso-widgets-pack' ), 
		    'description' => '', 
		    'type' => 'checkbox', 
		    'default' => false, 
		    'section' => 'soundcloud'
		);

		$fields['soundcloud_username'] = array(
		    'name' => __( 'Username', 'woodojo-muso-widgets-pack' ), 
		    'description' =>  __( 'Enter the your SoundCloud username (required).', 'woodojo-muso-widgets-pack' ), 
		    'type' => 'text',
		    'default' => '', 
		    'section' => 'soundcloud'
		);

		$fields['soundcloud_player_colour'] = array(
		    'name' => __( 'Player Colour', 'woodojo-muso-widgets-pack' ), 
		    'description' =>  __( 'Choose a colour for your SoundCloud players.', 'woodojo-muso-widgets-pack' ), 
		    'type' => 'colourpicker',
		    'default' => '#FF7700', 
		    'section' => 'soundcloud'
		);

		// Display the "Refresh" button if the SoundCloud username has previously been stored.
		$settings = $this->get_settings();
		if ( isset( $settings['soundcloud_username'] ) && ( $settings['soundcloud_username'] != '' ) ) {
			$fields['soundcloud_refresh'] = array(
			    'name' => __( 'Refresh SoundCloud Data', 'woodojo-muso-widgets-pack' ), 
			    'description' =>  sprintf( __( 'In order to speed up your website, we check your SoundCloud account for updated information once a month. It is, however, possible to force this check to happen if you\'ve updated your SoundCloud profile.%s', 'woodojo-muso-widgets-pack' ), '<br /><br /><a href="#" class="soundcloud-refresh button">' . __( 'Refresh SoundCloud Data', 'woodojo-muso-widgets-pack' ) . '</a> <small>(' . __( 'This may take a few seconds', 'woodojo-muso-widgets-pack' ) . ')</small><img src="' . admin_url( 'images/wpspin_light.gif' ) . '" class="ajax-loading" id="ajax-loading" alt="' . __( 'Loading', 'woodojo-muso-widgets-pack' ) . '" />' ),
			    'type' => 'info',
			    'default' => '', 
			    'section' => 'soundcloud'
			);
		}

		$fields['enable_lastfm'] = array(
		    'name' => __( 'Enable Last.fm Widgets', 'woodojo-muso-widgets-pack' ), 
		    'description' => __( '', 'woodojo-muso-widgets-pack' ), 
		    'type' => 'checkbox', 
		    'default' => false, 
		    'section' => 'lastfm'
		);

		$fields['load_widget_styles'] = array(
		    'name' => __( 'Load Widget Pack CSS', 'woodojo-muso-widgets-pack' ), 
		    'description' => '', 
		    'type' => 'checkbox', 
		    'default' => true, 
		    'section' => 'general'
		);

		$this->fields = $fields;
	} // End init_fields()
} // End Class
?>