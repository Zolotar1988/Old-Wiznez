<?php
/**
 * Plugin Name: appear.in WP
 * Plugin URI: http://vandercar.net/wp/appear-in-wp
 * Description: Adds appear.in rooms to your site via shortcode
 * Version: 1.6
 * Author: UaMV
 * Author URI: http://vandercar.net
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package appear.in WP
 * @version 1.6
 * @author UaMV
 * @copyright Copyright (c) 2013, UaMV
 * @link http://vandercar.net/wp/appear-in-wp
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Define constants.
 */

define( 'AIWP_VERSION', '1.6' );
define( 'AIWP_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'AIWP_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Include files.
 */

require_once AIWP_DIR_PATH . 'class-aiwp-admin.php';
is_admin() ? require_once AIWP_DIR_PATH . 'wp-side-notice/class-wp-side-notice.php' : FALSE;

/**
 * Get instance of class.
 */

Appear_In_WP::get_instance();

/**
 * Glance That Class
 *
 * Extends functionality of the Dashboard's At a Glance metabox
 *
 * @package Glance That
 * @author  UaMV
 */
class Appear_In_WP {

	/*---------------------------------------------------------------------------------*
	 * Attributes
	 *---------------------------------------------------------------------------------*/

	/**
	 * Instance of this class
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Custom plugin settings
	 *
	 * @since    4.0
	 *
	 * @var      object
	 */
	protected $options;

	/*---------------------------------------------------------------------------------*
	 * Constructor
	 *---------------------------------------------------------------------------------*/

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0
	 */
	private function __construct() {

		// retrieve custom plugin settings
		$this->options = get_option( 'aiwp_settings', array() );

		// if admin area, get instance of admin class
		if ( is_admin() ) { Appear_In_WP_Admin::get_instance(); }
		// else, check for shortcode presence and respond accordingly
		else {
			add_action( 'template_redirect', array( $this, 'respond_to_shortcode' ) );
		}

		// add the shortcode
		add_shortcode( 'appear_in', array( $this, 'display_shortcode' ) );

		// include the ajax library on the front end
		add_action( 'wp_head', array( &$this, 'add_ajax_library' ) );

		// ajax action callback for sending email invites
		add_action( 'wp_ajax_aiwp_invite', array( &$this, 'email_invites' ) );  // if logged-in
		add_action( 'wp_ajax_nopriv_aiwp_invite', array( &$this, 'email_invites' ) );  // if not logged-in

		// ajax action callback for counting sessions
		add_action( 'wp_ajax_aiwp_session', array( &$this, 'count_session' ) );  // if logged-in
		add_action( 'wp_ajax_nopriv_aiwp_session', array( &$this, 'count_session' ) );  // if not logged-in

		// ajax action callback for counting accepted invitations
		add_action( 'wp_ajax_aiwp_direct_session', array( &$this, 'count_direct_session' ) );  // if logged-in
		add_action( 'wp_ajax_nopriv_aiwp_direct_session', array( &$this, 'count_direct_session' ) );  //if not logged-in

	} // end __construct

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

		// if the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		} // end if

		return self::$instance;

	} // end get_instance


	/**
	 * Check for shortcode and enqueue scripts if shortcode is present.
	 *
	 * @since     1.0
	 */
	public function respond_to_shortcode() {

		// get post object
		global $post;
		
		// get post object
		$post_obj = get_post( $post->ID );

		// check if shortcode is present in post content
		if ( stripos( $post_obj->post_content, '[appear_in' ) !== FALSE ) {

			// call to enqueue scripts and styles needed for shortcode
			add_action( 'wp_enqueue_scripts', array( $this, 'add_stylesheets_and_javascript' ) );

		} // end if

	} // end respond_to_shortcode

	/**
	 * Registers the front-end stylesheets and scripts
	 *
	 * @since    1.0
	 */
	public function add_stylesheets_and_javascript() {

		// enqueue stylesheet
		wp_enqueue_style( 'aiwp-style', AIWP_DIR_URL . 'aiwp.css', array(), AIWP_VERSION, 'screen' );

		// enqueue jquery on front-end
		wp_enqueue_script( 'jquery' );

		// enqueue appear-in API library
		wp_enqueue_script( 'appearin-library', 'http://iswebrtcready.appear.in/apiv2.js', array(), AIWP_VERSION );

		// enqueue script for handling local interaction
		wp_enqueue_script( 'aiwp', AIWP_DIR_URL . 'aiwp.js', array(), AIWP_VERSION );

	} // end add_stylesheets_and_javascript

	/**
	 * Adds the WordPress Ajax Library to the frontend.
	 *
	 * @since    1.0
	 */
	public function add_ajax_library() {
	
		// build the inline script
		$html = '<script type="text/javascript">';
			$html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
		$html .= '</script>';
	
		// echo the inline script
		echo $html;
	
	} // end add_ajax_library

	/**
	 * Returns the shortcode content
	 *
	 * @since    1.0
	 *
	 * @return   string   HTML content displayed by shortcode.
	 */
	public function display_shortcode( $atts, $content = '' ) {

		// extract the shortcode parameters
		extract( shortcode_atts( array(
			'room' => '',
			'type' => 'public',
			'public_invites' => NULL,
			'private_invites' => NULL,
			'post_invites' => NULL,
		), $atts ) );

		// push the shortcode defined rooom types to an array
		$aiwp_room_types = explode( ',', str_replace( ' ', '', $type ) );

		// get public room name from shortcode, otherwise from custom options, otherwise from plugins randomly assigned & daily dynamic name
		if ( '' != $room ) {
			$custom_room_name = $room;
		} elseif ( isset( $this->options['room'] ) && '' != $this->options['room'] ) {
			$custom_room_name = $this->options['room'];
		} else {
			$custom_room_name = get_option( 'aiwp_public_room' );
		}

		// put allowable invites from shortcode into array
		$invites = array(
			'public' => $public_invites,
			'private' => $private_invites,
			'post' => $post_invites,
			);

		// if null, get from custom options
		foreach ( $invites as $room_type => $invite_count ) {
			if ( is_null( $invites[ $room_type ] ) ) {
				$invites[ $room_type ] = $this->options['invites'][ $room_type ];
			}
		}

		// build room selection wrapper
		$html = '<div id="aiwp-room-type-selection">';

			foreach ( $aiwp_room_types as $room_type ) {

				$room_button_text = isset( $atts[ $room_type . '_room_button' ] ) ? $atts[ $room_type . '_room_button' ] : ucfirst( $room_type ) . ' Room';
				$invite_button_text = isset( $atts[ $room_type . '_invite_button' ] ) ? $atts[ $room_type . '_invite_button' ] : 'Send Invitations & Enter ' . ucfirst( $room_type ) . ' Room';

				// display public room button, depending on which types of rooms are to be displayed
				$html .= '<div id="aiwp-' . $room_type . '" style="width:' . ( 100 / count( $aiwp_room_types ) ) . '%"><button id="aiwp-select-' . $room_type . '-room" data-room-type="' . $room_type . '" data-room-invites="';
					$html .= (int) $invites[ $room_type ] > 0 ? 'enabled"' : 'disabled"';
					$html .= '>' . apply_filters( 'aiwp_room_button', $room_button_text, $room_type ) . '</button>' . $this->invite_form( (int) $invites[ $room_type ], $room_type, $invite_button_text ) . '</div>';
			
			}

		$html .= '</div>';

		// build compatibility test result
		$html .= '<span id="appearin-incompatibility" style="display:none;">' . apply_filters( 'aiwp_unsupported_browser_message', 'It appears your browser is not capable of displaying this content. Try connecting with Chrome, Firefox, or Opera.' ) . '</span>';

		// include appearin iframe populated by API
		$html .= '<iframe id="appearin-room" data-room-name="' . $custom_room_name . '" data-security="' . wp_create_nonce( 'aiwp-action-on_' . get_option( 'aiwp_public_room' ) ) . '"></iframe>';
		$html .= '<div id="appearin-room-label"></div>';

		return $html;

	} // end display_shortcode

	/**
	 * Build an invitation form
	 *
	 * @since    1.0
	 *
	 * @return   string   HTML content for invite form.
	 */
	public function invite_form( $invites = 0, $room_type, $invite_button_text ) {

		// if invites enabled for room type, then build a hidden form
		// otherwise, include hidden content to trigger room launch
		if ( $invites > 0 ) {

			$html = '<div id="aiwp-' . $room_type . '-invite-form" style="display:none;">';

				// include replacement button for invite submission and room entrance
				$html .= '<button id="aiwp-send-' . $room_type . '-invites" data-room-type="' . $room_type . '" tabindex="12">' . apply_filters( 'aiwp_invite_button', $invite_button_text, $room_type ) . '</button>';
				
				// inform of optional invites (delay hide with js)
				$html .= '<span>Optionally Invite</span>';

				// include input for users name & email
				$html .= '<input type="text" id="aiwp-' . $room_type . '-username" name="aiwp_username" placeholder="Your Name" value="" tabindex="3"></input>';
				$html .= '<input type="text" id="aiwp-' . $room_type . '-email" name="aiwp_email" placeholder="Your Email" value="" tabindex="4"></input>';
				
				// include some number of inputs for invitation emails (defined by custom settings)
				for ( $i=1; $i<($invites+1); $i++ ) {
					$html .= '<input type="text" id="aiwp-' . $room_type . '-invite-' . $i . '" name="aiwp_' . $room_type . '_invite_' . $i . '" placeholder="Email (' . $i . '/' . $invites . ')"';
					$html .= $i>1 ? ' style="display:none;"' : ''; // ensure the first field is displayed
					$html .= ' value="" tabindex="' . ( 4 + $i ) . '"></input>';
				}

			$html .= '</div>';

		} else {

			$html = '<span id="aiwp-launch-' . $room_type . '" style="display:none;"></span>';

		} // end if

		return $html;
	}

	/**
	 * Callback for ajax send email invites
	 *
	 * @since    1.0
	 */
	public function email_invites() {
		
		if ( check_ajax_referer( 'aiwp-action-on_' . get_option( 'aiwp_public_room' ), 'aiwp_security' ) ) {
			// get the submitted parameters
			$aiwp_username = $_POST['aiwp_username'];
			$aiwp_email = $_POST['aiwp_email'];
			$aiwp_room_url = $_POST['aiwp_room_url'];
			$aiwp_room = $_POST['aiwp_room'];
			$aiwp_room_type = $_POST['aiwp_room_type'];
			$aiwp_invites = array();

			// get the submitted emails and fill array
			for( $i=1; $i<8; $i++ ) {
				( isset( $_POST[ 'aiwp_invite_' . $i ] ) && '' != $_POST[ 'aiwp_invite_' . $i ] ) ? $aiwp_invites[] = $_POST[ 'aiwp_invite_' . $i ] : FALSE;
			}

			// if no invite emails set, then return true to enter room
			// otherwise, process email invites
			if ( empty( $aiwp_invites ) ) {
				echo TRUE;
			} else {

				// validate invite email addresses
				foreach ( $aiwp_invites as $email ) {

					// if any fail, then return false triggering error message
					if ( ! is_email( $email ) ) {
						echo FALSE;
						die();
					}

				}

				// if from email set and valid, build header
				// otherwise, set empty header
				if ( '' != $aiwp_email && is_email( $aiwp_email ) ) {
					$header = 'From: ';
					$header .= '' != $aiwp_username ? $aiwp_username : '';
					$header .= '<' . $aiwp_email . '>';
				} else {
					$header = '';
				}

				// build the message content
				$message = 'You have been invited';
				$message .= '' != $aiwp_username ? ' by ' . $aiwp_username : '';
				$message .= ' to join a videochat happening now at ' . $aiwp_room_url . '?appear-in=' . $aiwp_room . '&aiwp-ref=invite';

				// send each message separately
				foreach ( $aiwp_invites as $email ) {
					
					// send the message
					$email_sent = wp_mail( $email, apply_filters( 'aiwp_invitation_subject', 'Invitation to Appear In', $aiwp_room_type ), apply_filters( 'aiwp_invitation_message', $message, $aiwp_room_type ), $header );
					
					// if send fails, return false triggering error message
					// otherwise, add 1 to invite count stats
					if ( ! $email_sent ) {
						echo FALSE;
						die();
					} else {
						$aiwp_stats = get_option( 'aiwp_stats' );
						$aiwp_stats[ $aiwp_room_type ]['invites_sent'] ++;
						update_option( 'aiwp_stats', $aiwp_stats );
					}
				}

				// return success to enter room
				echo TRUE;
			}
		} else {
			echo FALSE;
		}

		die();

	}

	/**
	 * Callback for ajax counting rooms triggered
	 *
	 * @since    1.0
	 */
	public function count_session() {
		if ( check_ajax_referer( 'aiwp-action-on_' . get_option( 'aiwp_public_room' ), 'aiwp_security' ) ) {
			$aiwp_room_type = $_POST['aiwp_room_type'];
			$aiwp_stats = get_option( 'aiwp_stats' );
			$aiwp_stats[ $aiwp_room_type ]['rooms_triggered'] ++;
			update_option( 'aiwp_stats', $aiwp_stats );
			echo TRUE;
		} else {
			echo FALSE;
		}
		die();
	}

	/**
	 * Callback for ajax counting accepted invites
	 *
	 * @since    1.0
	 */
	public function count_direct_session() {
		if ( check_ajax_referer( 'aiwp-action-on_' . get_option( 'aiwp_public_room' ), 'aiwp_security' ) ) {

			$aiwp_room = $_POST['aiwp_room'];
			$aiwp_ref = $_POST['aiwp_ref'];
			if ( strpos( $aiwp_room, '://' ) > 0 ) {
				$aiwp_room_type = 'post';
			} elseif ( strpos( ' ' . $aiwp_room, 'private-' ) > 0 ) {
				$aiwp_room_type = 'private';
			} else {
				$aiwp_room_type = 'public';
			}
			$aiwp_stats = get_option( 'aiwp_stats' );

			if ( 'invite' == $aiwp_ref ) {
				$aiwp_stats[ $aiwp_room_type ]['invites_accepted'] ++;
			}
			update_option( 'aiwp_stats', $aiwp_stats );
			echo TRUE;
		} else {
			echo FALSE;
		}
		die();
	}

} // end class



/**
 * Call to include room form
 */
function aiwp_include( $args ) {

	$aiwp_defaults = array(
		'room' => '',
		'type' => 'public',
		'public_invites' => NULL,
		'private_invites' => NULL,
		'post_invites' => NULL,
		);

	$args = wp_parse_args( $args, $aiwp_defaults );

	$shortcode = '[appear_in ';

	foreach ( $args as $parameter => $value ) {
		if ( ! empty( $value ) && ! is_null( $value ) ) {
			$shortcode .= $parameter . '="' . $value . '" ';
		}
	}

	$shortcode .= ']';

	Appear_In_WP::add_stylesheets_and_javascript();
	echo do_shortcode( $shortcode );

}

/**
 * Create a random room code
 */
function aiwp_random_room() {

	// predefine the alphabet used
	$aiwp_alphabet = 'qwertyuiopasdfghjklzxcvbnm1234567890';

	// set the length of the string
	$aiwp_stringLength = 30;

	// initialize the room name as an empty string
	$aiwp_randomString = '';

	// select and add 30 random character to the string
	for ( $i=0; $i<$aiwp_stringLength; $i++) {

		$aiwp_character = $aiwp_alphabet[ round( ( rand(0,100)/100 )*(strlen($aiwp_alphabet)-1) ) ];
		$aiwp_randomString .= $aiwp_character;

	} // end for

	// return the result
	return $aiwp_randomString;
}

/**
 * Creates a new room code and saves to db
 */
function aiwp_expire_room() {
	$aiwp_room = aiwp_random_room();
	update_option( 'aiwp_public_room', $aiwp_room );
}
// Hook function to expireroom cron event (found in admin class)
add_action( 'expireroom', 'aiwp_expire_room' );
?>