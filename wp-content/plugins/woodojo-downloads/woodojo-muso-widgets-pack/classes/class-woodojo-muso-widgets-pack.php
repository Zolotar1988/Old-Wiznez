<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WooDojo Muso Widgets Pack Class
 *
 * All functionality pertaining to the Muso Widgets Pack.
 *
 * @package WordPress
 * @subpackage WooDojo
 * @category Downloadables
 * @author Matty & Patrick
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * var $dir = ''
 * var $settings_screen
 * var $soundcloud
 *
 * - __construct()
 * - load_settings_screen()
 * - register_widgets()
 * - init_soundcloud()
 * - register_style()
 * - enqueue_style()
 */
class WooDojo_Muso_Widgets_Pack {
	var $dir = '';
	var $settings_screen;
	var $soundcloud;

	/**
	 * Constructor.
	 * @since 1.0.0
	 * @return void
	 */
 	public function __construct ( $file ) {
 		$this->dir = trailingslashit( dirname( $file ) );
 		$this->plugin_url = trailingslashit( plugins_url( '', $file ) );
 		/* Settings Screen */
    	$this->load_settings_screen();
		$this->settings = $this->settings_screen->get_settings();

		/* Initialize SoundCloud */
		$this->init_soundcloud();
		add_action( 'widgets_init', array(&$this, 'register_widgets' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_style' ) );
 	} // End __construct()

 	/**
	 * load_settings_screen function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_settings_screen () {
		
		/* Settings Screen */
		require_once( 'class-settings.php' );
		$this->settings_screen = new WooDojo_Muso_Widgets_Pack_Settings();
		
		/* Setup Settings Data */
		$this->settings_screen->token = 'woodojo-muso-widgets-pack';
		if ( is_admin() ) {
			$this->settings_screen->name 		= __( 'Muso Widgets Pack', 'woodojo-muso-widgets-pack' );
			$this->settings_screen->menu_label	= __( 'Muso Widgets Pack', 'woodojo-muso-widgets-pack' );
			$this->settings_screen->page_slug	= 'woodojo-muso-widgets-pack';
		}
		$this->settings_screen->setup_settings();
	} // End load_settings_screen()

	/**
	 * Register the desired widgets.
	 * @since  1.0.0
	 * @return void
	 */
	public function register_widgets () {
	 	$widgets = array();

	 	// Last.fm, if applicable.
	 	if ( $this->settings['enable_lastfm'] == 1 ) {
		 	$widgets = array(
		 					'WooDojo_Widget_Lastfm_Profile'			=> 'widgets/widget-woodojo-lastfm-profile.php', 
		 					'WooDojo_Widget_Lastfm_Recent_Tracks'	=> 'widgets/widget-woodojo-lastfm-recent-tracks.php',
		 					'WooDojo_Widget_Lastfm_Top_Albums'		=> 'widgets/widget-woodojo-lastfm-top-albums.php',
		 					'WooDojo_Widget_Lastfm_Top_Artists'		=> 'widgets/widget-woodojo-lastfm-top-artists.php',
		 				);
		 }

	 	// SoundCloud, if applicable.
	 	if ( $this->settings['enable_soundcloud'] == 1 && $this->settings['soundcloud_username'] != '' ) {
	 		$widgets['WooDojo_Widget_SoundCloud_Playlist'] = 'widgets/widget-woodojo-soundcloud-playlist.php';
	 		$widgets['WooDojo_Widget_SoundCloud_Tracks'] = 'widgets/widget-woodojo-soundcloud-tracks.php';
	 	}

	 	if ( count( $widgets ) > 0 ) {
	 		foreach ( $widgets as $k => $v ) {
	 			if ( file_exists( $this->dir . $v ) ) {
	 				require_once( $this->dir . $v );

	 				register_widget( $k );
	 			}
	 		}
	 	}
	 } // End register_widgets()

	 /**
	  * Initialize SoundCloud.
	  * @since  1.0.0
	  * @return void
	  */
	 public function init_soundcloud () {
	 	if ( ! isset( $this->settings['soundcloud_username'] ) || ( isset( $this->settings['soundcloud_username'] ) && ( apply_filters( 'woodojo_soundcloud_username', $this->settings['soundcloud_username'] ) != '' ) ) ) {
			require_once( $this->dir . 'classes/class-soundcloud.php' );
			
			if ( class_exists( 'WooDojo_SoundCloud' ) ) {
				$settings = array( 'colour' => $this->settings['soundcloud_player_colour'] );
				
				$this->soundcloud = new WooDojo_SoundCloud( sanitize_user($this->settings['soundcloud_username'] ), $settings );
				$this->soundcloud->plugin_path = $this->dir;
				$this->soundcloud->plugin_url = $this->plugin_url;
				$this->soundcloud->init();
			}
		}
	 } // End init_soundcloud()

	 /**
	  * Register the stylesheet (enqueued via widgets if need be)
	  * @since  1.0.0
	  * @return void
	  */
	 public function register_style () {
	 	wp_register_style( 'woodojo-muso-widgets-pack', $this->plugin_url . 'assets/css/style.css', array(), '1.0.0' );
	 } // End register_style()

	 /**
	  * Enqueue the stylesheet.
	  * @since  1.0.0
	  * @return void
	  */
	 public function enqueue_style () {
	 	wp_enqueue_style( 'woodojo-muso-widgets-pack' );
	 } // End enqueue_style()
 } // End Class
?>