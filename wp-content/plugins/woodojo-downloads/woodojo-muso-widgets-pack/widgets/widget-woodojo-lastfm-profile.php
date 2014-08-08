<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WooDojo Last.fm Profile Widget
 *
 * A WooDojo Last.fm profile widget.
 *
 * @package WordPress
 * @subpackage WooDojo
 * @category Downloadable
 * @author Patrick
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * var $woo_widget_cssclass
 * var $woo_widget_description
 * var $woo_widget_idbase
 * var $woo_widget_title
 * 
 * var $transient_expire_time
 * private $api_key
 * private $api_secret
 * private $api_url
 * 
 * - __construct()
 * - widget()
 * - update()
 * - form()
 * - request()
 * - generate_profile_box()
 * - get_checkbox_settings()
 */
class WooDojo_Widget_Lastfm_Profile extends WP_Widget {

	/* Variable Declarations */
	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_title;

	var $transient_expire_time;
	private $api_key;
	private $api_url = 'http://ws.audioscrobbler.com/2.0/';

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @uses WooDojo
	 * @return void
	 */
	public function __construct () {
		global $woodojo_muso_widgets_pack;

		/* Widget variable settings. */
		$this->woo_widget_cssclass = 'widget_woodojo_lastfm_profile';
		$this->woo_widget_description = __( 'Display your Last.fm profile on your site', 'woodojo-muso-widgets-pack' );
		$this->woo_widget_idbase = 'woodojo_lastfm_profile';
		$this->woo_widget_title = __('Last.fm - Profile', 'woodojo-muso-widgets-pack' );

		$this->api_key = '055930be28ded505a670a7e998c5b1d7';
		
		$this->transient_expire_time = 60 * 60 * 24; // 1 Day

		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => $this->woo_widget_idbase );

		/* Create the widget. */
		$this->WP_Widget( $this->woo_widget_idbase, $this->woo_widget_title, $widget_ops, $control_ops );

		/* Load in assets for the widget. */
		$settings = $woodojo_muso_widgets_pack->settings;
		if ( isset( $settings['load_widget_styles'] ) && $settings['load_widget_styles'] == 1 ) {
			add_action( 'wp_enqueue_scripts', array( &$woodojo_muso_widgets_pack, 'enqueue_style' ) );
		}
	} // End Constructor

	/**
	 * widget function.
	 * 
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget( $args, $instance ) {

		if ( ! isset( $instance['username'] ) ) { return; }

		extract( $args, EXTR_SKIP );
		
		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title ) {
		
			echo $before_title . $title . $after_title;
		
		} // End IF Statement
		
		/* Widget content. */
		
		// Add actions for plugins/themes to hook onto.
		do_action( $this->woo_widget_cssclass . '_top' );

		// Load widget content here.
		$html = '';

		$html .= $this->generate_profile_box( $instance );

		echo $html; // If using the $html variable to store the output, you need this. ;)

		// Add actions for plugins/themes to hook onto.
		do_action( $this->woo_widget_cssclass . '_bottom' );

		/* After widget (defined by themes). */
		echo $after_widget;

	} // End widget()

	/**
	 * update function.
	 * 
	 * @access public
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 */
	public function update ( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		/* Strip tags for the username, and sanitize it as if it were a WordPress username. */
		$instance['username'] = strip_tags( sanitize_user( $new_instance['username'] ) );
		
		/* The select box is returning a text value, so we escape it. */
		$instance['avatar_alignment'] = esc_attr( $new_instance['avatar_alignment'] );

		$checkboxes = array_keys( $this->get_checkbox_settings() );

		/* The checkbox is returning a Boolean (true/false), so we check for that. */
		foreach ( $checkboxes as $k => $v ) {
			$instance[$v] = (bool) esc_attr( $new_instance[$v] );
		}
			
		// Allow child themes/plugins to act here.
		$instance = apply_filters( $this->woo_widget_idbase . '_widget_save', $instance, $new_instance, $this );

		return $instance;
	} // End update()

   /**
    * form function.
    * 
    * @access public
    * @param array $instance
    * @return void
    */
   public function form ( $instance ) {
		/* Set up some default widget settings. */
		/* Make sure all keys are added here, even with empty string values. */
		$defaults = array(
			'title' => __( 'Last.fm Profile', 'woodojo-muso-widgets-pack' ),
			'username' => '',
			'avatar_alignment' => 'left', 
			'display_avatar' => 1,
			'display_name' => 1,
			'display_screen_name' => 1,
			'display_url' => 1,
			'display_friends_count' => 1,
			'display_playlist_count' => 1,
			'display_loved_songs_count' => 1,
		);
		
		// Allow child themes/plugins to filter here.
		$defaults = apply_filters( $this->woo_widget_idbase . '_widget_defaults', $defaults, $this );
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$checkboxes = $this->get_checkbox_settings();
?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):', 'woodojo-muso-widgets-pack' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>"  value="<?php echo $instance['title']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" />
		</p>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Username:', 'woodojo-muso-widgets-pack' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'username' ); ?>"  value="<?php echo $instance['username']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" />
		</p>
		<!-- Widget Avatar Alignment: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'avatar_alignment' ); ?>"><?php _e( 'Avatar Alignment:', 'woothemes' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'avatar_alignment' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'avatar_alignment' ); ?>">
				<option value="left"<?php selected( $instance['avatar_alignment'], 'left' ); ?>><?php _e( 'Left', 'woodojo-muso-widgets-pack' ); ?></option>
				<option value="centre"<?php selected( $instance['avatar_alignment'], 'centre' ); ?>><?php _e( 'Centre', 'woodojo-muso-widgets-pack' ); ?></option>
				<option value="right"<?php selected( $instance['avatar_alignment'], 'right' ); ?>><?php _e( 'Right', 'woodojo-muso-widgets-pack' ); ?></option>         
			</select>
		</p>
		<?php foreach ( $checkboxes as $k => $v ) { ?>
		<!-- Widget <?php echo $v; ?>: Checkbox Input -->
		<p>
			<input id="<?php echo $this->get_field_id( $k ); ?>" name="<?php echo $this->get_field_name( $k ); ?>" type="checkbox"<?php checked( $instance[$k], 1 ); ?> />
	    	<label for="<?php echo $this->get_field_id( $k ); ?>"><?php echo $v; ?></label>
		</p>
		<?php } ?>
<?php		
		// Allow child themes/plugins to act here.
		do_action( $this->woo_widget_idbase . '_widget_settings', $instance, $this );

	} // End form()

	/**
	 * Make a request to the API.
	 * @param	array	$methods	Array of API methods to be called.
	 * @param	array	$params		Array of API params being included.
	 * @return	object				API reponse object.
	 */
	private function request ( $methods = array(), $params = array() ) {
		global $woodojo;
		$data = array();
		if( !isset( $params['user'] ) ) return false; // require a username
		foreach( $methods as $method ) {
			$transient_name = substr( 'woodojo_lastfm_' . $method . '_' . $params['user'], 0, 45 ); // transient names are 45 chars or less
			// Check if we've cached this API response, otherwise call it and cache it
			if( get_transient( $transient_name ) ) {
				$json = get_transient( $transient_name );
			} else {
				$args = wp_parse_args( $params, array(
						'method'	=> $method,
						'api_key'	=> $this->api_key,
						'format'	=> 'json'
					)
				);
				$url = add_query_arg( $args, $this->api_url );	
				$args = array(
					'user-agent'	=> 'WooDojo/' . $woodojo->version,
					'sslverify'		=> apply_filters( 'https_local_ssl_verify', false ),
				);
				$response = wp_remote_post( $url, $args );
				if( is_wp_error( $response ) ) {
					return false;
				} else {
					$json = $response['body'];
					set_transient( $transient_name, $json, $this->transient_expire_time );
				}
			}
			
			$data = (object) array_merge( (array) $data, (array) json_decode( $json ) );
		}
		
		return $data;
	} // End request()

	/**
	 * generate_profile_box function.
	 * @param  object $data The data returned from the API.
	 * @return string       The HTML for the profile box.
	 */
	private function generate_profile_box ( $instance ) {
		$html = '';
		
		if ( isset( $instance['username'] ) && !empty( $instance['username'] ) ) {
						
			$data = $this->request( array( 'user.getInfo', 'user.getFriends', 'user.getLovedTracks' ), array( 'user' => $instance['username'] ) );
			
			// Determine whether or not we have stats.
			$has_stats = false;

			if (
				( $instance['display_friends_count'] == 1 ) || 
				( $instance['display_playlist_count'] == 1 ) || 
				( $instance['display_loved_songs_count'] == 1 )
			   ) {
				$has_stats = true;
			}

			$html .= '<div class="profile-box">' . "\n";
		
			if ( $instance['display_avatar'] == true ) {
				$image = (array) $data->user->image[2];
				if( isset( $image ) && !empty( $image['#text'] ) ) {
					$html .= '<img src="' . esc_url( $image['#text'] ) . '"  class="avatar align' . esc_attr( $instance['avatar_alignment'] ) . '" />' . "\n";
				}
			}
			if ( $instance['display_name'] == true && isset( $data->user->realname ) ) {
				$html .= '<h4 class="name">' . $data->user->realname;
				if ( $instance['display_screen_name'] == true && isset( $data->user->name ) ) {
					$html .= ' (' . $data->user->name . ')';
				}
				$html .= '</h4>' . "\n";
			} else {
				if ( $instance['display_screen_name'] == true && isset( $data->user->name ) ) {
					$html .= '<h4 class="name">' . $data->user->name . '</h4>' . "\n";
				}
			}
			$html .= '<div class="profile-content">' . "\n";
			if ( $instance['display_url'] == 1 && !empty( $data->user->url ) ) {
				$html .= '<span class="url"><a href="' . esc_url( $data->user->url ) . '">' . $data->user->url . '</a></span>' . "\n";
			}
			$html .= '</div><!--/.profile-content-->' . "\n";

			if ( $has_stats == true ) {
				$html .= '<div class="stats">' . "\n";
			}
			
			if ( $instance['display_friends_count'] == 1 && isset( $data->friends ) ) {
				$friends = get_object_vars( $data->friends );
				$html .= '<p class="friends stat"><span class="number">' . $friends['@attr']->total . '</span> <span class="stat-label">' . __( 'Friends', 'woodojo-muso-widgets-pack' ) . '</span></p>' . "\n";
			}

			if ( $instance['display_playlist_count'] == 1 && isset( $data->user->playlists ) ) {
				$html .= '<p class="playlists stat"><span class="number">' . $data->user->playlists . '</span> <span class="stat-label">' . __( 'Playlists', 'woodojo-muso-widgets-pack' ) . '</span></p>' . "\n";
			}

			if ( $instance['display_loved_songs_count'] == 1 && isset( $data->lovedtracks ) ) {
				$lovedtracks = get_object_vars( $data->lovedtracks );
				$html .= '<p class="loved_songs stat"><span class="number">' . $lovedtracks['@attr']->total . '</span> <span class="stat-label">' . __( 'Loved Songs', 'woodojo-muso-widgets-pack' ) . '</span></p>' . "\n";
			}
			
			if ( $has_stats == true ) {
				$html .= '</div>' . "\n";
			}

			$html .= '</div><!--/.profile-box-->' . "\n";
		}

		return $html;
	} // End generate_profile_box()

	/**
	 * Return an array of key/value pairs for use with checkboxes.
	 * @return array
	 */
	private function get_checkbox_settings () {
		return array(
			'display_avatar' => __( 'Display Avatar', 'woodojo-muso-widgets-pack' ), 
			'display_name' => __( 'Display Name', 'woodojo-muso-widgets-pack' ), 
			'display_screen_name' => __( 'Display Screen Name', 'woodojo-muso-widgets-pack' ), 
			'display_url' => __( 'Display URL', 'woodojo-muso-widgets-pack' ),  
			'display_friends_count' => __( 'Display Friends Count', 'woodojo-muso-widgets-pack' ), 
			'display_playlist_count' => __( 'Display Playlist Count', 'woodojo-muso-widgets-pack' ), 
			'display_loved_songs_count' => __( 'Display Loved Songs Count', 'woodojo-muso-widgets-pack' )
		);
	} // End get_checkbox_settings()
	
} // End Class
?>