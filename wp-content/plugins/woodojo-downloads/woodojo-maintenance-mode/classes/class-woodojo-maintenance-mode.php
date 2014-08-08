<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WooDojo Maintenance Mode Class
 *
 * Don't let people see your site when you are maintaining it.
 *
 * @package WordPress
 * @subpackage WooDojo
 * @category Downloadable
 * @author WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * var $token
 * var $settings_screen
 * var $templates_dir
 * 
 * - __construct()
 * - maintenance_mode()
 * - admin_notice()
 * - add_toolbar_button()
 * - load_toolbar_button_css()
 * - login_message()
 * - load_settings_screen()
 * - maintenance_head()
 * - page_title()
 * - the_title()
 * - the_note()
 * - maintenance_end_body()
 * - http_header_unavailable()
 */
class WooDojo_Maintenance_Mode {
		
	/* Variable Declarations */
	var $token;
	var $settings_screen;
	var $templates_dir;
	private $file;
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct ( $file ) {
		$this->templates_dir = trailingslashit( trailingslashit( dirname( $file ) ) . 'templates' );
    	/* Settings Screen */
    	$this->load_settings_screen();
		$this->settings = $this->settings_screen->get_settings();
		
		if( $this->settings['enable'] == true ) {
			if( ! is_admin() && ! in_array( $GLOBALS['pagenow'], array( 'wp-login.php' ) ) && ! current_user_can( $this->settings['role'] ) ) {
				add_action( 'init', array( &$this, 'maintenance_mode' ) );
			} else {
				add_filter( 'login_message', array( &$this, 'login_message' ) );
				add_filter( 'admin_notices', array( &$this, 'admin_notice' ) );
			}
		}

		add_action( 'admin_bar_menu', array( &$this, 'add_toolbar_button' ), 10 );
		add_action( 'admin_print_styles', array( &$this, 'load_toolbar_button_css' ), 10 );
		add_action( 'wp_print_styles', array( &$this, 'load_toolbar_button_css' ), 10 );
	} // End __construct()
	
	/**
	 * maintenance_mode function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function maintenance_mode () {
		// Credit: Michael Wöhrer : http://sw-guide.de/
		// Never display Maintenance Mode in these cases.
		if( strstr($_SERVER['PHP_SELF'], 'wp-login.php' ) 
			|| strstr( $_SERVER['PHP_SELF'], 'async-upload.php' ) // Otherwise media uploader does not work 
			|| strstr( htmlspecialchars( $_SERVER['REQUEST_URI'] ), '/plugins/' ) // So that currently enabled plugins work while in maintenance mode.
			|| strstr( $_SERVER['PHP_SELF'], 'upgrade.php' )
		){ 
			return;
		}

		// Credit: Michael Wöhrer : http://sw-guide.de/
		// Feeds
		if( strstr( htmlspecialchars( $_SERVER['REQUEST_URI'] ), '/feed' ) || strstr( htmlspecialchars( $_SERVER['REQUEST_URI'] ), 'feed=' ) ) {
			if ( true == $this->settings['enable_feeds'] ) {
				// Display feeds
				return;
			} else {
				// Don't display feeds and apply HTTP header
				nocache_headers(); // Sets the headers to prevent caching for the different browsers
				$this->http_header_unavailable(); 
				exit();
			}
		}

		// Credit: Michael Wöhrer : http://sw-guide.de/
		// Trackbacks
		if( strstr( htmlspecialchars( $_SERVER['REQUEST_URI'] ), '/trackback' ) || strstr( $_SERVER['PHP_SELF'], 'wp-trackback.php' ) ) {
			if ( true == $this->settings['enable_trackbacks'] ) { 				
				// Display trackbacks
				return;
			} else {
				// Don't display trackbacks and apply HTTP header
				nocache_headers(); // Sets the headers to prevent caching for the different browsers
				$this->http_header_unavailable(); 
				exit();
			}
		}

		// Credit: Michael Wöhrer : http://sw-guide.de/
		// XML-RPC
		if( strstr( $_SERVER['PHP_SELF'], 'xmlrpc.php' ) ) {
			if ( true == $this->settings['enable_xmlrpc'] ) { 				
				// Allow XML RPC publishing
				return;
			} else {
				// Don't allow XML RPC publishing
				$this->http_header_unavailable(); 
				exit();
			}
		}

		nocache_headers(); // Let's not cachify the maintenance mode.

		$location = trailingslashit( trailingslashit( WP_PLUGIN_DIR ) . trailingslashit( plugin_basename( dirname( dirname( __FILE__ ) ) ) ) . 'templates' );
		$location_url = trailingslashit( trailingslashit( WP_PLUGIN_URL ) . trailingslashit( plugin_basename( dirname( dirname( __FILE__ ) ) ) ) . 'templates' );

		$template = $this->settings['template'];
		$path_to_load = '';

		// Determine where to look for the template file.
		if ( stristr( $template, '503.php' ) != '' ) {
			$path_to_load = trailingslashit( trailingslashit( WP_CONTENT_DIR ) . 'themes' ) . $template;
		} else {
			// Check for custom paths (not in theme).
			if ( stristr( $template, DIRECTORY_SEPARATOR ) != '' ) {
				$path_to_load = $template;
			}
		}

		if ( $path_to_load == '' ) {
			$path_to_load = $location . $template;
		}

		// Settings Variables
		$settings = array();
		$settings['note'] = (isset($this->settings['note']) && $this->settings['note'] != '') ? $this->settings['note'] : __( 'This website is currently in maintenance mode.', 'woodojo-maintenance-mode' );
		$settings['title'] = (isset($this->settings['title']) && $this->settings['title'] != '') ? $this->settings['title'] : __( 'Maintenance Mode', 'woodojo-maintenance-mode' );
		$settings['page_title'] = (isset($this->settings['page_title']) && $this->settings['page_title'] != '') ? $this->settings['page_title'] : get_bloginfo( 'name' );
		$settings['title'] = stripslashes( $settings['title'] );
		$settings['note'] = stripslashes( $settings['note'] );
		$settings['page_title'] = stripslashes( $settings['page_title'] );

		if( $template != 'wp_die' && $template != 'theme-503' && file_exists( $path_to_load ) ) {

			// Template path
			$settings['path'] = trailingslashit( $location_url . basename( dirname( $path_to_load ) ) );

			// Allow themes/plugins to filter here.
			$settings = apply_filters( 'woodojo_maintenance_mode_template_settings', $settings );

			include( $path_to_load );
			exit();
		}
		
		if ( $template == 'theme-503' && file_exists( esc_attr( trailingslashit( get_stylesheet_directory() ) . '503.php' ) ) ) {
			locate_template( array( '503.php' ), true, true );
			exit;
		}

		// If all else fails... DIE!
		wp_die( $settings['note'], $settings['page_title'] );
	} // End maintenance_mode()
	
	/**
	 * admin_notice function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_notice () {
		if( !isset( $_GET['page'] ) || $_GET['page'] != 'woodojo-maintenance-mode' ) {
			echo '<div class="error"><p>' . __( 'Maintenance mode is enabled.','woodojo-maintenance-mode' ) . '</p></div>' . "\n";
    	}
	} // End admin_notice()
	
	/**
	 * Add a button to the WordPress Toolbar.
	 * @since  1.0.2
	 * @return  void
	 */
	public function add_toolbar_button () {
		global $wp_admin_bar, $current_user;

		if ( ! is_admin_bar_showing() || ! is_object( $wp_admin_bar ) )
			return false;

		$title = __( 'Maintenance Mode', 'woodojo-maintenance-mode' );
		$id = 'woodojo-maintenance-mode';
		if ( true == $this->settings['enable'] ) {
			$title = __( 'Maintenance Mode is Active', 'woodojo-maintenance-mode' );
			$id = 'woodojo-maintenance-mode-active';
		}

		if ( 'woodojo-maintenance-mode' == $id && ! current_user_can( 'manage_options' ) ) {
			// Silence is golden...
		} else {
			$wp_admin_bar->add_menu( array( 'parent' => 'top-secondary', 'id' => esc_attr( $id ), 'title' => $title, 'href' => esc_url( add_query_arg( 'page', 'woodojo-maintenance-mode', admin_url( 'admin.php' ) ) ) ) );
		}
	} // End add_toolbar_button()

	/**
	 * Load custom CSS for the new button on the WordPress Toolbar.
	 * @since  1.0.2
	 * @return  void
	 */
	public function load_toolbar_button_css () {
		global $wp_admin_bar;

		if ( ! is_admin_bar_showing() || ! is_object( $wp_admin_bar ) )
			return false;

		$html = '<style type="text/css">' . "\n";
		$html .= '#wpadminbar #wp-admin-bar-woodojo-maintenance-mode-active { background-color: #d00;
	background-image: -moz-linear-gradient(bottom, #f44, #d00 );
	background-image: -webkit-gradient(linear, left bottom, left top, from(#f44), to(#d00)); }';
		$html .= '#wp-admin-bar-woodojo-maintenance-mode-active .ab-item { color: #EBEBEB; }';
		$html .= '#wp-admin-bar-woodojo-maintenance-mode-active .ab-item:hover, #wp-admin-bar-woodojo-maintenance-mode-active .ab-item:focus, #wp-admin-bar-woodojo-maintenance-mode-active .ab-item:active { background: none !important; color: #FFFFFF; }';
		$html .= '</style>' . "\n";

		echo $html;
	} // End load_toolbar_button_css()
	
	/**
	 * login_message function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function login_message () {
		return '<div id="login_error"><p>' . __( 'Maintenance mode is enabled.', 'woodojo-maintenance-mode' ) . '</p></div>' . "\n";
	} // End login_message()
	
	/**
	 * load_settings_screen function.
	 * 
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function load_settings_screen () {
		/* Settings Screen */
		require_once('class-settings.php');
		$this->settings_screen = new WooDojo_Maintenance_Mode_Settings();
		
		/* Setup Settings Data */
		$this->settings_screen->token = 'woodojo-maintenance-mode';
		if ( is_admin() ) {
			$this->settings_screen->name 		= __( 'Maintenance Mode', 'woodojo-maintenance-mode' );
			$this->settings_screen->menu_label	= __( 'Maintenance Mode', 'woodojo-maintenance-mode' );
			$this->settings_screen->page_slug	= 'woodojo-maintenance-mode';
		}
		$this->settings_screen->templates_dir = $this->templates_dir;
		$this->settings_screen->setup_settings();
	
	} // End load_settings_screen()

	/**
	 * maintenance_head function.
	 * @since  1.0.0
	 * @return void 
	 */
	public function maintenance_head () {
		do_action( 'woodojo_maintenance_mode_head' );

		wp_print_scripts();
	}// End maintenance_head()

	/**
	 * page_title function.
	 * @since  1.0.0
	 * @return void 
	 */
	public function page_title () {
		$title = apply_filters( 'woodojo_maintenance_mode_page_title', $this->settings['page_title'] );

		if ( $title == '' ) {
			$title = get_bloginfo( 'name' );
		}

		echo $title;
	}// End page_title()

	/**
	 * Sanitize and display the title text.
	 * @since  1.0.2
	 * @return void 
	 */
	public function the_title () {
		$title = apply_filters( 'woodojo_maintenance_mode_the_title', $this->settings['title'] );

		echo esc_html( $title );
	}// End the_title()

	/**
	 * Sanitize and display the note text.
	 * @since  1.0.2
	 * @return void 
	 */
	public function the_note () {
		$text = apply_filters( 'woodojo_maintenance_mode_the_note', $this->settings['note'] );

		echo do_shortcode( wp_kses_post( html_entity_decode( $text ) ) );
	}// End the_note()

	/**
	 * maintenance_end_body function.
	 * @since  1.0.0
	 * @return void 
	 */
	public function maintenance_end_body () {
		do_action( 'woodojo_maintenance_mode_end_body' );

	}// End maintenance_end_body()

	/**
	 * Apply HTTP header
	 * @since  1.0.2
	 * @author Michael Wöhrer : http://sw-guide.de/
	 */
	private function http_header_unavailable () {
	   	header( 'HTTP/1.0 503 Service Unavailable' );
		header( 'Retry-After: ' . 60 * 60 ); // 1 hour.
	} // End http_header_unavailable()
}
?>