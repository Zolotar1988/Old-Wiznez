<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WooDojo Last.fm Top Albums Widget
 *
 * A WooDojo Last.fm top albums widget.
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
 * - enqueue_styles()
 * - get_checkbox_settings()
 */
class WooDojo_Widget_Lastfm_Top_Albums extends WP_Widget {

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
		$this->woo_widget_cssclass = 'widget_woodojo_lastfm_top_albums';
		$this->woo_widget_description = __( 'Your top Last.fm albums on your site', 'woodojo-muso-widgets-pack' );
		$this->woo_widget_idbase = 'woodojo_lastfm_top_albums';
		$this->woo_widget_title = __('Last.fm - Top Albums', 'woodojo-muso-widgets-pack' );
		
		$this->api_key = '055930be28ded505a670a7e998c5b1d7';
		$this->transient_expire_time = (int) apply_filters( 'woodojo_lastfm_top_albums_cache', 60 * 60 * 24 ); // 1 Day

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
		if ( !isset( $instance['username'] ) || empty( $instance['username'] ) ) { return; }

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
		$limit = (int) $instance['limit'];
		$data = $this->request( array( 'user.getTopAlbums' ), array( 'user' => $instance['username'], 'limit' => $limit, 'period' => $instance['period'] ) );
		
		foreach( $data->topalbums as $albums ) {
			if( is_array( $albums ) && $limit > 0 ) {
				$html .= '<ol id="woodojo_lastfm_top_list">';
				for($i = 1; $i <= $limit; $i++) { 
					if( isset( $albums[$i] ) ) {
						$html .= '<li class="woodojo_lastfm_top_' . $i . '">';
							if( $instance['link'] == 1 ) {
								$html .= '<a href="'.$albums[$i]->url.'">';
							}
								if( $instance['include_thumb'] == 1 ) {
									$image = (array) $albums[$i]->image[1];
									$html .= '<img class="woodojo_lastfm_thumb" src="' . $image['#text']. '" />';
								}
								$html .= '<span class="album">' . $albums[$i]->name . '</span>';
								if( $instance['include_artist'] == 1 ) {
									$html .= '<span class="artist">' . $instance['separator'] . $albums[$i]->artist->name  . '</span>';
								}
								if( $instance['include_playcount'] == 1 ) {
									$html .= ' <span class="playcount">' . $albums[$i]->playcount . '</span>';
								}
							if( $instance['link_albums'] == 1 ) {
								$html .= '</a>';
							}
						$html .= '</li>';
					}
				}
				$html .= '</ol>';
			}
		}
		

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
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['period'] = strip_tags( $new_instance['period'] );
		
		/* Clean the separator */
		$instance['separator'] = esc_attr( $new_instance['separator'] );
		
		/* Strip tags for the username, and sanitize it as if it were a WordPress username. */
		$instance['username'] = strip_tags( sanitize_user( $new_instance['username'] ) );

		if( $old_instance['limit'] != $new_instance['limit'] ) {
			delete_transient( 'woodojo_lastfm_user.getTopAlbums_' . $instance['username'] );
		}

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
			'title' => __( 'Last.fm Top Albums', 'woodojo-muso-widgets-pack' ),
			'username' => '',
			'link' => 1,
			'include_artist' => 1,
			'include_playcount' => 1,
			'include_thumb' => 1,
			'period' => 'overall',
			'separator' => __(' by ', 'woodojo-muso-widgets-pack'),
			'limit' => 5,
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
		<!-- Username: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Username:', 'woodojo-muso-widgets-pack' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'username' ); ?>"  value="<?php echo $instance['username']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" />
		</p>
		<!-- Number of Albums: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of Albums:', 'woodojo-muso-widgets-pack' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'limit' ); ?>"  value="<?php echo $instance['limit']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" />
		</p>
		<!-- Seprator for artist/album: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'separator' ); ?>"><?php _e( 'Artist/Album Separator:', 'woodojo-muso-widgets-pack' ); ?></label>
			<input type="text" name="<?php echo $this->get_field_name( 'separator' ); ?>"  value="<?php echo $instance['separator']; ?>" class="widefat" id="<?php echo $this->get_field_id( 'separator' ); ?>" />
		</p>
		<!-- Widget Avatar Alignment: Select Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'period' ); ?>"><?php _e( 'Period:', 'woodojo-muso-widgets-pack' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'period' ); ?>" class="widefat" id="<?php echo $this->get_field_id( 'period' ); ?>">
				<option value="overall"<?php selected( $instance['period'], 'overall' ); ?>><?php _e( 'Overall', 'woodojo-muso-widgets-pack' ); ?></option>
				<option value="7day"<?php selected( $instance['period'], '7day' ); ?>><?php _e( '7 Days', 'woodojo-muso-widgets-pack' ); ?></option>
				<option value="3month"<?php selected( $instance['period'], '3month' ); ?>><?php _e( '3 Months', 'woodojo-muso-widgets-pack' ); ?></option>
				<option value="6month"<?php selected( $instance['period'], '6month' ); ?>><?php _e( '6 Months', 'woodojo-muso-widgets-pack' ); ?></option>
				<option value="12month"<?php selected( $instance['period'], '12month' ); ?>><?php _e( '12 Months', 'woodojo-muso-widgets-pack' ); ?></option>
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
	 * Return an array of key/value pairs for use with checkboxes.
	 * @return array
	 */
	private function get_checkbox_settings () {
		return array(
			'link' => __( 'Link Tracks', 'woodojo-muso-widgets-pack' ),
			'include_artist' => __( 'Include Artist', 'woodojo-muso-widgets-pack' ),
			'include_playcount' => __( 'Include Playcount', 'woodojo-muso-widgets-pack' ),
			'include_thumb' => __( 'Include Thumb', 'woodojo-muso-widgets-pack' ),
		);
	} // End get_checkbox_settings()
	
} // End Class
?>