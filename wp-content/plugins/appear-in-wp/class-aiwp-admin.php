<?php
/**
 * Amen
 *
 * @package   Appear In WP
 * @author    UaMV
 * @license   GPL-2.0+
 * @link      http://vandercar.net/wp
 * @copyright 2013 UaMV
 */

/**
 * Appear_In_WP_Admin
 *
 * Handles the admin section
 *
 * @package Appear In WordPress
 * @author  UaMV
 */
class Appear_In_WP_Admin {

	/*---------------------------------------------------------------------------------*
	 * Attributes
	 *---------------------------------------------------------------------------------*/

	/**
	 * Instance of this class.
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Custom plugin settings
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected $options;

	/*---------------------------------------------------------------------------------*
	 * Consturctor / The Singleton Pattern
	 *---------------------------------------------------------------------------------*/

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0
	 */
	private function __construct() {

		// check if plugin has updated and respond accordingly
		add_action( 'admin_init', array( $this, 'check_plugin_update' ) );

		// load activation notice to guide users to the next step
		add_action( 'admin_notices', array( $this, 'display_plugin_activation_message' ) );

		// initialize stats on plugin activation
		register_activation_hook( AIWP_DIR_PATH . 'appear-in-wp.php', array( $this, 'initialize_stats' ) );

		// add notices on plugin activation
		register_activation_hook( AIWP_DIR_PATH . 'appear-in-wp.php', array( $this, 'add_wpsn_notices' ) );

		// schedule crons on plugin activation
		register_activation_hook( AIWP_DIR_PATH . 'appear-in-wp.php', array( $this, 'schedule_cron' ) );

		// unschedule crons on plugin de-activation
		register_deactivation_hook( AIWP_DIR_PATH . 'appear-in-wp.php', array( $this, 'unschedule_cron' ) );

		// remove active plugin marker
		register_deactivation_hook( AIWP_DIR_PATH . 'appear-in-wp.php', array( $this, 'remove_activation_marker' ) );

		// retrieve cutom plugin settings
		$this->options = get_option( 'aiwp_settings', array() );

		// initialize custom room name
		$this->options['room'] = isset( $this->options['room'] ) ? $this->options['room'] : '';
		$this->options['invites'] = isset( $this->options['invites'] ) ? $this->options['invites'] : array( 'post' => 0, 'public' => 0, 'private' => 0 );

		// register the settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// call to enqueue admin scripts and styles
		add_action( 'admin_enqueue_scripts', array( $this, 'add_stylesheets_and_javascript' ) );

	} // end constructor

	/*---------------------------------------------------------------------------------*
	 * Public Functions
	 *---------------------------------------------------------------------------------*/

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		} // end if

		return self::$instance;

	} // end get_instance

	/**
	 * Displays a plugin message as soon as the plugin is activated.
	 *
	 * @since    1.0
	 */
	public function display_plugin_activation_message() {

		if ( ! get_option( 'aiwp_activated' ) ) {

			// Show the notice
			$html = '<div class="updated">';
				$html .= '<a href="http://appear.in"><img src="' . AIWP_DIR_URL . 'appearin-logo.png" style="float: left; width: 2em; height: 2em; margin-right: 0.4em; margin-top: 0.4em" /></a>';
				$html .= '<p style="display: inline-block">';
					$html .= __( "<strong>Yes!</strong> One more thing. <a href='options-media.php#aiwp-section'>Click here</a> to customize your appear.in WP settings.", 'aiwp-locale' );
				$html .= '</p>';
			$html .= '</div><!-- /.updated -->';

			echo $html;

			update_option( 'aiwp_activated', TRUE );

		} // end if

	} // end display_plugin_activation_message

	/**
	 * Deletes activation marker so it can be displayed when the plugin is reinstalled or reactivated
	 *
	 * @since    1.0
	 */
	public static function remove_activation_marker() {

		delete_option( 'aiwp_activated' );

	} // end remove_activation_marker

	/**
	 * Check for plugin update and updates notices
	 *
	 * @since    1.0
	 */
	public function check_plugin_update() {

		// if current version greater than previous version stored in database, then update notices
		(float) AIWP_VERSION > (float) get_option( 'aiwp_db_version' ) ? $this->add_wpsn_notices() : FALSE;

	} // end check_plugin_update

	/**
	 * Initialize the stats
	 *
	 * @since    1.0
	 */
	public function initialize_stats() {

		// retrieve the stats
		$ai_stats = get_option( 'aiwp_stats' );

		// if stats did not previously exist, initialize them
		if ( '' == $ai_stats || (float) get_option( 'aiwp_db_version' ) < 1.4 ) {

			$ai_initialize_stats = array(
				'public'  => array( 'rooms_triggered' => 0, 'invites_sent' => 0, 'invites_accepted' => 0 ),
				'private' => array( 'rooms_triggered' => 0, 'invites_sent' => 0, 'invites_accepted' => 0 ),
				'post'    => array( 'rooms_triggered' => 0, 'invites_sent' => 0, 'invites_accepted' => 0 ),
				);

			update_option( 'aiwp_stats', $ai_initialize_stats );
		}

	} // end initialize_stats

	/**
	 * Define WP Side Notices for use in plugin
	 *
	 * @since    1.0
	 */
	public function add_wpsn_notices() {

		// Initialize a new side notice
		$wpsn = new WP_Side_Notice( 'aiwp' );

		// Define the notices
		$aiwp_notices = array(
			'ai-info' => array(
				'name' => 'ai-info',
				'trigger' => TRUE,
				'time' => time() - 5,
				'dismiss' => 'none',
				'content' => '<a href="http://wordpress.org/plugins/appear-in-wp">appear.in WP</a> plugin developed by <a href="http://vandercar.net/wp">UaMV</a>.',
				'style' => array( 'height' => '72px', 'color' => '#85ae9b', 'icon' => 'f348' ),
				'location' => array( 'options-media.php' ),
				),
			'ai-support' => array(
				'name' => 'ai-support',
				'trigger' => TRUE,
				'time' => time() - 5,
				'dismiss' => 'none',
				'content' => 'Require assistance? Visit our <a href="http://wordpress.org/support/plugin/appear-in-wp/">support forum</a>.&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;All is well? Consider <a href="http://wordpress.org/support/view/plugin-reviews/appear-in-wp#postform">reviewing the plugin</a>.',
				'style' => array( 'height' => '72px', 'color' => '#85ae9b', 'icon' => 'f240' ),
				'location' => array( 'options-media.php' ),
				),
			'ai-stats' => array(
				'name' => 'ai-stats',
				'trigger' => TRUE,
				'time' => time() - 5,
				'dismiss' => 'none',
				'content' => '',
				'style' => array( 'height' => '130px', 'color' => '#85ae9b', 'icon' => 'f185' ),
				'location' => array( 'options-media.php' ),
				),
			);
		
		// Add each notice
		foreach ( $aiwp_notices as $notice => $args ) {
			$wpsn->add( $args );
		}

		// Update the aiwp database version
		update_option( 'aiwp_db_version', AIWP_VERSION );
		
	} // end add_wpsn_notices

	/**
	 * Add stats to the content section of stats notice.
	 *
	 * @since    1.0
	 */
	public function add_stats_content( $content, $notice, $current_user ) {

		$aiwp_stats = get_option( 'aiwp_stats' );

		$post_invites_percent = $aiwp_stats['post']['invites_sent'] == 0 ? 0 : round( 100 * ( $aiwp_stats['post']['invites_accepted'] / $aiwp_stats['post']['invites_sent'] ), 2 );
		$public_invites_percent = $aiwp_stats['public']['invites_sent'] == 0 ? 0 : round( 100 * ( $aiwp_stats['public']['invites_accepted'] / $aiwp_stats['public']['invites_sent'] ), 2 );
		$private_invites_percent = $aiwp_stats['private']['invites_sent'] == 0 ? 0 : round( 100 * ( $aiwp_stats['private']['invites_accepted'] / $aiwp_stats['private']['invites_sent'] ), 2 );
		$post_room_avg = $aiwp_stats['post']['rooms_triggered'] == 0 ? 0 : round( ( $aiwp_stats['post']['rooms_triggered'] + $aiwp_stats['post']['invites_accepted'] ) / $aiwp_stats['post']['rooms_triggered'], 2 );
		$public_room_avg = $aiwp_stats['public']['rooms_triggered'] == 0 ? 0 : round( ( $aiwp_stats['public']['rooms_triggered'] + $aiwp_stats['public']['invites_accepted'] ) / $aiwp_stats['public']['rooms_triggered'], 2 );
		$private_room_avg = $aiwp_stats['private']['rooms_triggered'] == 0 ? 0 : round( ( $aiwp_stats['private']['rooms_triggered'] + $aiwp_stats['private']['invites_accepted'] ) / $aiwp_stats['private']['rooms_triggered'], 2 );

		$html = '<strong>Post Rooms</strong>';
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp';
		$html .= '<strong>' . $aiwp_stats['post']['rooms_triggered'] . '</strong> rooms triggered';
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp';
		$html .= '<strong>' . $aiwp_stats['post']['invites_accepted'] . ' of ' . $aiwp_stats['post']['invites_sent'] . '</strong> invites accepted';
		$html .= ' <strong>' . $post_invites_percent . '%</strong>';
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp';
		$html .= '<strong>' . $post_room_avg . '</strong> average users per room';

		$html .= '<br />';

		$html .= '<strong>Public Rooms</strong>';
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp';
		$html .= '<strong>' . $aiwp_stats['public']['rooms_triggered'] . '</strong> rooms triggered';
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp';
		$html .= '<strong>' . $aiwp_stats['public']['invites_accepted'] . ' of ' . $aiwp_stats['public']['invites_sent'] . '</strong> invites accepted';
		$html .= ' <strong>' . $public_invites_percent . '%</strong>';
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp';
		$html .= '<strong>' . $public_room_avg . '</strong> average users per room';

		$html .= '<br />';

		$html .= '<strong>Private Rooms</strong>';
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp';
		$html .= '<strong>' . $aiwp_stats['private']['rooms_triggered'] . '</strong> rooms triggered';
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp';
		$html .= '<strong>' . $aiwp_stats['private']['invites_accepted'] . ' of ' . $aiwp_stats['private']['invites_sent'] . '</strong> invites accepted';
		$html .= ' <strong>' . $private_invites_percent . '%</strong>';
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp';
		$html .= '<strong>' . $private_room_avg . '</strong> average users per room';

		return $html;

	}

	/**
	 * Schedules the cron for expiring and resetting a daily public room name.
	 *
	 * @since    1.0
	 */
	public function schedule_cron() {

		// on plugin activation, get random room code
		$random_room = aiwp_random_room();

		// assign to public room
		add_option( 'aiwp_public_room', $random_room );

		// schedule default public room name to reset each day at midnight
		wp_schedule_event( strtotime('today midnight'), 'daily', 'expireroom' );

	} // end schedule_cron

	/**
	 * Unschedules the cron when plugin is disabled
	 *
	 * @since    1.0
	 */
	public function unschedule_cron() {

		// get the cron array
		$crons = _get_cron_array();

		// loop through the cron schedule
		foreach ( $crons as $time => $cron_data ) {

			// loop through the cron events
			foreach ( $cron_data as $cron_event => $data ) {

				// if it's the expireroom event, unschedule it
				'expireroom' == $cron_event ? wp_unschedule_event( $time, $cron_event ) : FALSE;

			} // end foreach

		} // end foreach

		// remove the current room code
		delete_option( 'aiwp_public_room' );

	} // end unschedule cron

	/**
	 * Registers the admin stylesheets and scripts
	 *
	 * @since    1.0
	 */
	public function add_stylesheets_and_javascript() {

		wp_enqueue_style( 'aiwp-admin-style', AIWP_DIR_URL . 'aiwp-admin.css', array(), AIWP_VERSION, 'screen' );

	}

	/**
	 * Registers the settings fields with the WordPress Settings API.
	 *
	 * @since    1.0
	 */
	public function register_settings() {

		// First, register a settings section
		add_settings_section( 'aiwp', 'appear.in', array( $this, 'display_section' ), 'media' );

		// Then, register the settings for the fields
		register_setting( 'media', 'aiwp_settings' );

		// Now introduce the settings fields
		add_settings_field(
			'aiwp_settings',
			__( '<div id="aiwp-section"><a href="https://appear.in"><img src="' . AIWP_DIR_URL . 'appearin-logo.png" style="border-radius:100%;"/></a></div>' , 'aiwp-locale' ),
			array( $this, 'display_settings' ) ,
			'media',
			'aiwp'
		);

	} // end register_settings

	/**
	 * Renders the intro to the settings section.
	 *
	 * @since    1.0
	 */
	public function display_section() {

		// get the aiwp side notices setting height of 500px
		$notices = new WP_Side_Notice( 'aiwp', 700 );

		add_filter( 'ai-stats_side_notice_content', array( $this, 'add_stats_content' ), 10, 3 );

		// display the notices
		$notices->display();

	}

	/**
	 * Renders the setting fields.
	 *
	 * @since    1.0
	 */
	public function display_settings() {

		// build the fields
		$html = '<fieldset>';

		// allow default public room name
		$html .= '<label>' . __( 'Public Room Name:', 'aiwp-locale' );
			$html .= ' <input type="text" id="appear_in_room" name="aiwp_settings[room]" value="' . $this->options['room'] . '"></input>';
		$html .= '</label>';

		$html .= '<br />';

		$html .= ' <span class="description">' . __( '(if not defined here or in shortcode, public room is given random name that expires daily)' , 'aiwp-locale' ) . '</span>';

		$html .= '<br /><br />';

		// allow some number of invites for public and private rooms
		$html .= 'Enable some number of email invitations upon entering...&nbsp;&nbsp;&nbsp;';

		$html .= '<nobr>';

		$html .= __( 'a post room', 'aiwp-locale' );
		$html .= ' <select id="appear_in_post_invites" name="aiwp_settings[invites][post]">';

				$html .= '<option value="0" ' . selected( $this->options['invites']['post'], '0', FALSE ) . '>' . __( '0', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="1" ' . selected( $this->options['invites']['post'], '1', FALSE ) . '>' . __( '1', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="2" ' . selected( $this->options['invites']['post'], '2', FALSE ) . '>' . __( '2', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="3" ' . selected( $this->options['invites']['post'], '3', FALSE ) . '>' . __( '3', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="4" ' . selected( $this->options['invites']['post'], '4', FALSE ) . '>' . __( '4', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="5" ' . selected( $this->options['invites']['post'], '5', FALSE ) . '>' . __( '5', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="6" ' . selected( $this->options['invites']['post'], '6', FALSE ) . '>' . __( '6', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="7" ' . selected( $this->options['invites']['post'], '7', FALSE ) . '>' . __( '7', 'aiwp-locale' ) . '</option>';

		$html .= '</select>';
		
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;';

		$html .= __( 'the public room', 'aiwp-locale' );
		$html .= ' <select id="appear_in_public_invites" name="aiwp_settings[invites][public]">';

				$html .= '<option value="0" ' . selected( $this->options['invites']['public'], '0', FALSE ) . '>' . __( '0', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="1" ' . selected( $this->options['invites']['public'], '1', FALSE ) . '>' . __( '1', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="2" ' . selected( $this->options['invites']['public'], '2', FALSE ) . '>' . __( '2', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="3" ' . selected( $this->options['invites']['public'], '3', FALSE ) . '>' . __( '3', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="4" ' . selected( $this->options['invites']['public'], '4', FALSE ) . '>' . __( '4', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="5" ' . selected( $this->options['invites']['public'], '5', FALSE ) . '>' . __( '5', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="6" ' . selected( $this->options['invites']['public'], '6', FALSE ) . '>' . __( '6', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="7" ' . selected( $this->options['invites']['public'], '7', FALSE ) . '>' . __( '7', 'aiwp-locale' ) . '</option>';

		$html .= '</select>';
		
		$html .= '&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;';

		$html .= __( 'a private room', 'aiwp-locale' );
		$html .= ' <select id="appear_in_private_invites" name="aiwp_settings[invites][private]">';

				$html .= '<option value="0" ' . selected( $this->options['invites']['private'], '0', FALSE ) . '>' . __( '0', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="1" ' . selected( $this->options['invites']['private'], '1', FALSE ) . '>' . __( '1', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="2" ' . selected( $this->options['invites']['private'], '2', FALSE ) . '>' . __( '2', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="3" ' . selected( $this->options['invites']['private'], '3', FALSE ) . '>' . __( '3', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="4" ' . selected( $this->options['invites']['private'], '4', FALSE ) . '>' . __( '4', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="5" ' . selected( $this->options['invites']['private'], '5', FALSE ) . '>' . __( '5', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="6" ' . selected( $this->options['invites']['private'], '6', FALSE ) . '>' . __( '6', 'aiwp-locale' ) . '</option>';
				$html .= '<option value="7" ' . selected( $this->options['invites']['private'], '7', FALSE ) . '>' . __( '7', 'aiwp-locale' ) . '</option>';

		$html .= '</select>';

		$html .= '</nobr>';

		$html .= '<br /><br /><br /><br /><br />';

		$html .= '</fieldset>';

		echo $html;

	} // end display_settings

} // end class