<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooDojo Launch Pad Class
 *
 * All functionality pertaining to the Launch Pad feature for WooDojo Maintenance Mode.
 *
 * @package WordPress
 * @subpackage WooDojo
 * @category Downloadable
 * @author WooThemes
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * private $templates_dir
 *
 * - __construct()
 * - register_launch_pad_settings()
 * - enqueue_countdown_js()
 * - register_templates()
 * - find_templates()
 * - get_template_data()
 * - load_settings_screen()
 * - countdown_markup()
 * - countdown_javascript_init()
 * - logo_image_markup()
 * - newsletter_subscribe_markup()
 * - social_profiles_markup()
 * - custom_footer_text_markup()
 * - print_custom_css()
 * - add_admin_notice()
 * - print_background_styling()
 * - print_custom_html_code_head()
 */
class WooDojo_Launch_Pad {
	private $templates_dir;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct ( $file ) {
		/* Settings Screen */
    	$this->load_settings_screen();
		$this->settings = $this->settings_screen->get_settings();
		$this->templates_dir = trailingslashit( trailingslashit( dirname( $file ) ) . 'templates' );

		add_filter( 'woodojo_maintenance_mode_templates', array( &$this, 'register_templates' ) );//Add backwards compatibility
		add_filter( 'settings_woodojo-maintenance-mode_template', array( &$this, 'register_templates' ) );
		add_filter( 'woodojo_maintenance_mode_template_settings', array( &$this, 'register_launch_pad_settings' ) , 10 , 1 );
		add_action( 'woodojo_maintenance_mode_head' , array( &$this , 'enqueue_countdown_js' ) , 10 );
		add_action( 'admin_notices', array( &$this, 'add_admin_notice' ) );
		add_action( 'woodojo_maintenance_mode_head', array( &$this, 'print_background_styling' ) , 20 );
		add_action( 'woodojo_maintenance_mode_head', array( &$this, 'print_custom_css' ) , 10 );
		add_action( 'woodojo_maintenance_mode_head', array( &$this, 'print_custom_html_code_head' ) , 10 );
		add_action( 'woodojo_maintenance_mode_end_body', array( &$this, 'print_custom_html_body_end'), 10 );

	} // End __construct()

	/**
	 * register_launch_pad_settings function.
	 * @since  1.0.0
	 * @param  array $settings An array of the existing settings.
	 * @return array           An array of settings with launch pad settings added.
	 */
	public function register_launch_pad_settings ( $settings ) {
		$settings = array_merge( $this->settings, $settings );
		$settings['launchpad_path'] = str_replace( 'woodojo-maintenance-mode', 'woodojo-launch-pad', $settings['path'] );

		return $settings;
	} // End register_launch_pad_settings()


	/**
	 * enqueue_coundown_js function.
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_countdown_js () {
		//Remove all scripts
		remove_all_actions( 'wp_print_scripts' );

		if( $this->settings['countdown_launch'] < time() ) return false;

		if( 1 == $this->settings['enable_countdown'] ) {
			//Enqueue only the required scripts
			wp_enqueue_script( 'countdown', plugin_dir_url( plugin_basename( dirname( __FILE__ ) ) ) . 'assets/js/jquery.countdown.min.js', array( 'jquery' ) );
		}
	} // End enqueue_countdown_js()

	/**
	 * register_templates function.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array $templates The existing array of available template files
	 * @return array $templates
	 */
	public function register_templates ( $templates ) {
		$results = $this->find_templates( );
		if ( is_array( $results ) && count( $results ) > 0 ) {
			foreach ( $results as $k => $v ) {
				$templates[$k] = $v;
			}
		}
		return $templates;
	} // End register_templates()

	/**
	 * find_templates function.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return array $results
	 */
	public function find_templates ( ) {
		$results = array( );

		$files = WooDojo_Utils::glob_php( '*.php', GLOB_MARK, $this->templates_dir );

		if ( is_array( $files ) && count( $files ) > 0 ) {
			foreach ( $files as $k => $v ) {
				$data = $this->get_template_data( $v );
				if ( is_object( $data ) && isset( $data->title ) ) {
					$results[$v] = $data->title;
				}
			}
		}

		return $results;
	} // End find_templates()

	/**
	 * get_template_data function.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param string $file The path to the file to be scanned for template file data.
	 * @return object/boolean
	 */
	public function get_template_data ( $file ) {
		$headers = array(
			'title' => 'Template Name',
			'description' => 'Description',
			'version' => 'Version'
		);
		$mod = get_file_data( $file, $headers );
		if ( ! empty( $mod['title'] ) ) {
			$obj = new StdClass();

			foreach ( $mod as $k => $v ) {
				$obj->$k = $v;
			}

			return $obj;
		}
		return false;
	} // End get_template_data()

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
		$this->settings_screen = new WooDojo_Launch_Pad_Settings();

		/* Setup login branding data */
		$this->settings_screen->token = 'woodojo-launch-pad';

		if ( is_admin() ) {
			$this->settings_screen->name 		= __( 'Launch Pad', 'woodojo-launch-pad' );
			$this->settings_screen->menu_label	= __( 'Launch Pad', 'woodojo-launch-pad' );
			$this->settings_screen->page_slug	= 'woodojo-launch-pad';
			$this->settings_screen->has_tabs	= true;
		}
		$this->settings_screen->setup_settings();
	} // End load_settings_screen()


	/**
	 * countdown_markup function.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function countdown_markup () {
		$settings = $this->settings_screen->get_settings();

		if( $settings['countdown_launch'] < time() ) return false;

		if ( 1 != $settings['enable_countdown'] ) { return; }
		?>
		<div id="countdown" class="block">
    		<h3><span><?php echo stripslashes($this->settings['countdown_heading']); ?></span></h3>
    		<div id="timer"></div>
   		</div>
   		<?php
	} // End countdown_markup()

	/**
	 * countdown_javascript_init function.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function countdown_javascript_init () {
		$settings = $this->settings_screen->get_settings();

		if( $settings['countdown_launch'] < time() ) { return false; }

		if ( 1 != $settings['enable_countdown'] ) { return; }

 		// Set date
		$time_stamp = $settings['countdown_launch'];
		?>
		<script type="text/javascript">
		jQuery(function () {
			jQuery( '#timer' ).countdown(
				{
					until: new Date(<?php echo $time_stamp * 1000 ?>), format: 'DHMS'
				});
		});
		</script>
	<?php
	} // End countdown_javascript_init()

	/**
	 * logo_image_markup function
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function logo_image_markup () {
		$settings = $this->settings_screen->get_settings();
		if ( '' != $settings['logo_image'] ) {
		?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>">
				<img src="<?php echo esc_url( $settings['logo_image'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
			</a>
		<?php
		} else {
			echo '<h1 class="site-title">' . get_bloginfo( 'name' ) . '</h1>' . "\n" . '<span class="site-description">' . get_bloginfo( 'description' ) . '</span>' . "\n";
		}
	}// End logo_image_markup()

	/**
	 * newsletter_subscribe_markup function
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function newsletter_subscribe_markup () {
		$settings = $this->settings_screen->get_settings();
		if ( 1 != $settings['enable_newsletter'] ) { return; } ?>

		<div id="newsletter">
   			<p><?php echo esc_html( $settings['newsletter_heading'] ); ?></p>

    		<?php if ( 'feedburner' == $settings['newsletter_service'] ) { ?>

			<form class="newsletter-form" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $woo_options['woo_newsletter_ID']; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
				<input class="email" type="text" name="email" value="<?php _e('E-mail','woothemes'); ?>" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}" />
				<input type="hidden" value="<?php echo $woo_options['woo_newsletter_ID']; ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo('name'); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit" type="submit" name="submit" value="<?php echo stripslashes( $settings['newsletter_subscribe_button'] ); ?>" />
			</form>

    	   	<?php  } elseif ( 'campaignmonitor' == $settings['newsletter_service'] ) { ?>

			<form name="campaignmonitorform" class="newsletter-form" action="<?php echo $settings['newsletter_service_form_action']; ?>" method="post">
				<input type="text" class="email" name="cm-<?php echo $settings['newsletter_service_id']; ?>" id="<?php echo $settings['newsletter_service_id']; ?>" value="<?php _e('E-mail','woothemes'); ?>" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}" />
				<input class="submit" type="submit" name="submit" value="<?php echo stripslashes( $settings['newsletter_subscribe_button'] ); ?>" />
			</form>


    	   	<?php } elseif ( 'mailchimp' == $settings['newsletter_service'] ) { ?>

			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form" action="<?php echo $settings['newsletter_mail_chimp_list_subscription_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $settings['newsletter_mail_chimp_list_subscription_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','woothemes'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}">
					<input type="submit" value="<?php echo stripslashes( $settings['newsletter_subscribe_button'] ); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->

    	   	<?php } ?>

   			<div class="fix"></div>
   		</div><!-- /#newsletter -->
<?php
	} // End newsletter_subscribe_markup()

	/**
	 * social_profiles_markup function
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function social_profiles_markup () {
		$settings = $this->settings_screen->get_settings();
		if ( 1 != $settings['enable_social'] ) { return false; }
?>
		<div id="social" class="block">
    		<div class="social-links">
	    		<?php if ( '' != $settings['twitter_account'] ) { ?>
	    		<div class="link">
	    	   		<a href="<?php echo $settings['twitter_account']; ?>" class="twitter"><?php _e( 'Twitter', 'woodojo-launch-pad' ); ?></a>
	    	   	</div>
	    	   	<?php  } ?>
	    		<?php if (  '' != $settings['facebook_account'] ) { ?>
	    		<div class="link">
	    	   		<a href="<?php echo $settings['facebook_account']; ?>" class="facebook"><?php _e( 'Facebook', 'woodojo-launch-pad' ); ?></a>
	    	   	</div>
	    	   	<?php } ?>
	    		<?php if ( '' !=  $settings['email'] && is_email( $settings['email'] ) ) { ?>
	    		<div class="link">
	    	   		<a href="mailto:<?php echo $settings['email']; ?>" class="contact"><?php _e( 'Contact', 'woodojo-launch-pad' ); ?></a>
	    	   	</div>
	    	   	<?php } ?>
	    		<?php if ( 1 == $settings['show_rss'] ) { ?>
	    		<div class="link last">
	    	   		<a href="<?php if ( $settings['rss_feed_url'] ) { echo $settings['rss_feed_url']; } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe"><?php _e( 'Subscribe', 'woodojo-launch-pad' ); ?></a>
	    	   	</div>
	    	   	<?php } ?>
	    	   	<div class="fix"></div>
    	   	</div>
   		</div><!-- #social -->
<?php
	} // End social_profiles_markup()

	/**
	 * custom_footer_text_markup function
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function custom_footer_text_markup () {
		$settings = $this->settings_screen->get_settings();

		if ( '' != $settings['custom_footer'] ) {
			echo wpautop( stripslashes( esc_html( $settings['custom_footer'] ) ) );
		}
	} // End custom_footer_text_markup()

	/**
	 * print_custom_css function
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function print_custom_css () {
		if( '' != $this->settings['custom_css'] ){?>
			<style>
				<?php echo strip_tags( $this->settings['custom_css'] ); ?>
			</style>
		<?php
		}
	} // End print_custom_css()

	/**
	 * add_admin_notice function.
	 * @since  1.0.0
	 * @return  void
	 */
	public function add_admin_notice () {
		if ( isset( $_GET['page'] ) && ( esc_attr( $_GET['page'] ) == $this->settings_screen->page_slug ) ) {
			echo '<div class="updated fade"><p>' . sprintf( __( 'Visit the %sMaintenance Mode%s screen, after saving your settings, to select your Launch Pad template.', 'woodojo-launch-pad' ), '<a href="' . esc_url( admin_url( 'admin.php?page=woodojo-maintenance-mode' ) ) . '">', '</a>' ) . '</p></div>' . "\n";
		}
	} // End add_admin_notice()

	/**
	 * print_background_styling function.
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function print_background_styling () {

		$output = '';

		// Get options
		$body_img = $this->settings['background_image'];
		$body_repeat = $this->settings['background_image_repeat'];
		$body_position = $this->settings['background_image_position'];

		// Add CSS to output
		if ($body_img)
			$output .= 'body {background-image:url('.$body_img.')}' . "\n";

		if ($body_img && $body_repeat && $body_position)
			$output .= 'body {background-repeat:'.$body_repeat.'}' . "\n";

		if ($body_img && $body_position)
			$output .= 'body {background-position:'.$body_position.'}' . "\n";

		// Output styles
		if (isset($output) && $output != '') {
			$output = strip_tags($output);
			$output = "<!-- LaunchPad Background Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}

	} // End print_background_styling()

	/**
	 * print_custom_html_code_head
	 * @return void
	 */
	public function print_custom_html_code_head () {
		$output = $this->settings['custom-html-code-head'];
		if ( $output <> "" )
			echo stripslashes($output) . "\n";
	}

	/**
	 * print_custom_html_body_end
	 * @return void
	 */
	public function print_custom_html_body_end(){
		$output = $this->settings['custom-html-code-footer'];
		if ( $output <> "" )
			echo stripslashes($output) . "\n";
	}
} // End Class
?>