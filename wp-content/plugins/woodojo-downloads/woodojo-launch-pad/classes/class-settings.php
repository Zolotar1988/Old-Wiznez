<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*-----------------------------------------------------------------------------------

CLASS INFORMATION

Description: Settings Screen Data
Date Created: 2012-03-23.
Last Modified: 2011-03-25.
Author: Patrick
Since: 1.0.0


TABLE OF CONTENTS

- function __construct
- function init_sections
- function init_fields

-----------------------------------------------------------------------------------*/

class WooDojo_Launch_Pad_Settings extends WooDojo_Settings_API {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct () {

	    parent::__construct(); // Required in extended classes.
	    $this->theme_location = trailingslashit( WP_PLUGIN_DIR ) . trailingslashit( plugin_basename( dirname( dirname( __FILE__ ) ) ) ) . 'themes';

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

		$sections['general'] = array(
			'name' => __('General Settings' , 'woodojo-launch-pad'),
			'description' => sprintf( __( 'Visit the %sMaintenance Mode%s screen to set the title and note text.', 'woodojo-launch-pad' ), '<a href="' . esc_url( admin_url( 'admin.php?page=woodojo-maintenance-mode' ) ) . '">', '</a>' )
		);

		$sections['custom-html'] = array(
			'name' 			=> __( 'Custom Code', 'woodojo-launch-pad' ),
			'description'	=> __( 'Add custom code to your Launch Pad page. This is ideal for tracking code.', 'woodojo-launch-pad' )
		);

		$sections['styling_options'] = array(
			'name' 			=> __( 'Styling Options', 'woodojo-launch-pad' ),
			'description'	=> __( 'Add custom styling to your launch page.', 'woodojo-launch-pad' )
		);

		$sections['social'] = array(
			'name'			=> __( 'Social Links', 'woodojo-launch-pad' ),
			'description'	=> __( 'The links to social profiles. You must include the full url.', 'woodojo-launch-pad' )
		);

		$sections['newsletter'] = array(
			'name' 			=> __( 'Newsletter', 'woodojo-launch-pad' ),
			'description'	=> __( 'Add a subscription form for your mailing list.', 'woodojo-launch-pad' )
		);

		$sections['countdown'] = array(
			'name'			=> __( 'Countdown Timer', 'woodojo-launch-pad' ),
			'description'	=> __( 'Add a countdown timer for when your site will be launching', 'woodojo-launch-pad' )
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

		$background_image_repeat_options = array( 'no-repeat' => __( 'No Repeat', 'woodojo-launch-pad' ), 'repeat-x' => __( 'Repeat Horizontally Only', 'woodojo-launch-pad' ), 'repeat-y' => __( 'Repeat Vertically Only', 'woodojo-launch-pad' ), 'repeat' => __( 'Repeat', 'woodojo-launch-pad' ) );


		$background_image_position_options = array(
			'left top' => __( 'Top Left', 'woodojo-launch-pad' ),
	    	'right top' => __( 'Top Right', 'woodojo-launch-pad' ),
	    	'center top' => __( 'Top Center', 'woodojo-launch-pad' ),
	    	'left center' => __( 'Center Left', 'woodojo-launch-pad' ),
	    	'center center' => __( 'Center Center', 'woodojo-launch-pad' ),
	    	'right center' => __( 'Center Right', 'woodojo-launch-pad' ),
	    	'left bottom' => __( 'Bottom Left', 'woodojo-launch-pad' ),
	    	'center bottom' => __( 'Bottom Center', 'woodojo-launch-pad' ),
	    	'right bottom' => __( 'Bottom Right', 'woodojo-launch-pad' )
	    );

	    $newsletter_service_options = array(
	    	'feedburner' => 'FeedBurner',
	    	'campaignmonitor' => 'Campaign Monitor',
	    	'mailchimp' => 'MailChimp'
	    );

	     if ( current_user_can( 'unfiltered_html' ) ) {
		    $fields['custom-html-code-head'] = array(
		    						'name' => __( 'Inside the &lt;head&gt; Tags', 'woodojo' ),
		    						'description' => __( 'Output custom HTML code inside the &lt;head&gt; tags of your website.', 'woodojo' ),
		    						'type' => 'html',
		    						'default' => '',
		    						'section' => 'custom-html',
		    						'required' => 0,
		    						'form' => 'form_field_textarea',
		    						'validate' => 'validate_field_html'
		    						);

		    $fields['custom-html-code-footer'] = array(
		    						'name' => __( 'Before the closing &lt;/body&gt; Tag', 'woodojo' ),
		    						'description' => __( 'Output custom HTML code before the closing &lt;/body&gt; tag of your website.', 'woodojo' ),
		    						'type' => 'html',
		    						'default' => '',
		    						'section' => 'custom-html',
		    						'required' => 0,
		    						'form' => 'form_field_textarea',
		    						'validate' => 'validate_field_html'
		    						);
		}

		$fields['custom_footer'] = array(
			'name' => __( 'Custom Footer Text', 'woodojo-launch-pad' ),
		    'description' => __( 'Display a line of text in the footer area of your Launch Pad.', 'woodojo-launch-pad' ),
		    'type' => 'textarea',
		    'default' => '',
		    'section' => 'general'
		);

		$fields['logo_image'] = array(
		    'name' => __( 'Your logo', 'woodojo-launch-pad' ),
		    'description' => sprintf( __('Add your logo. Ensure you enter the full URL (eg: %s). Leave empty to use the default WordPress site title.', 'woodojo-launch-pad' ), site_url( '/images/logo.png' ) ),
		    'type' => 'file',
		    'default' => '',
		    'section' => 'styling_options'
		);

		$fields['custom_css'] = array(
		    'name' => __( 'Custom CSS', 'woodojo-launch-pad' ),
		    'description' => __('Add custom CSS to be output to the page.', 'woodojo-launch-pad' ),
		    'type' => 'textarea',
		    'default' => '',
		    'section' => 'styling_options'
		);

		$fields['background_image'] = array(
		    'name' => __( 'Background Image', 'woodojo-launch-pad' ),
		    'description' => __('Add custom background to the page. Ensure you enter the full url.', 'woodojo-launch-pad' ),
		    'type' => 'file',
		    'default' => '',
		    'section' => 'styling_options'
		);

		$fields['background_image_repeat'] = array(
		    'name' => __( 'Background Image Repeat', 'woodojo-launch-pad' ),
		    'description' => __('Select how you would like to repeat the background-image.', 'woodojo-launch-pad' ),
		    'type' => 'select',
		    'default' => '',
		    'options' => $background_image_repeat_options ,
		    'section' => 'styling_options'
		);

		$fields['background_image_position'] = array(
		    'name' => __( 'Background Image Position', 'woodojo-launch-pad' ),
		    'description' => __('Select how you would like to position the background.', 'woodojo-launch-pad' ),
		    'type' => 'select',
		    'default' => '',
		    'options' => $background_image_position_options ,
		    'section' => 'styling_options'
		);

		$fields['enable_social'] = array(
		    'name' => __( 'Enable the social area', 'woodojo-launch-pad' ),
		    'description' => '',
		    'type' => 'checkbox',
		    'default' => false,
		    'section' => 'social'
		);

		$fields['social_heading'] = array(
			'name' => __( 'Social Heading' , 'woodojo-launch-pad' ),
			'description' => __( 'The heading to display in the Social Area' , 'woodojo-launch-pad' ),
			'type' => 'text',
			'default' => '',
			'section' => 'social'
		);

		$fields['twitter_account'] = array(
			'name' => __( 'Twitter Account' , 'woodojo-launch-pad' ),
			'description' => __( 'The full URL to your Twitter account.' , 'woodojo-launch-pad' ),
			'type' => 'text',
			'default' => '',
			'section' => 'social'
		);

		$fields['facebook_account'] = array(
			'name' => __( 'Facebook Page' , 'woodojo-launch-pad' ),
			'description' => __( 'The full URL to your Facebook page.' , 'woodojo-launch-pad' ),
			'type' => 'text',
			'default' => '',
			'section' => 'social'
		);

		$fields['show_rss'] = array(
			'name' => __( 'RSS Feed' , 'woodojo-launch-pad' ),
			'description' => __( 'Show RSS Feed link.', 'woodojo-launch-pad' ),
			'type' => 'checkbox',
			'default' => '',
			'section' => 'social'
		);

		$fields['email'] = array(
			'name' => __( 'Email Address', 'woodojo-launch-pad' ),
			'description' => '',
			'type' => 'text',
			'default' => '',
			'section' => 'social'
		);

	    $fields['enable_countdown'] = array(
		    'name' => __( 'Enable the Countdown area', 'woodojo-launch-pad' ),
		    'description' => __( '', 'woodojo-launch-pad' ),
		    'type' => 'checkbox',
		    'default' => false,
		    'section' => 'countdown'
		);

	    $fields['countdown_heading'] = array(
		    'name' => __( 'Countdown Heading', 'woodojo-launch-pad' ),
		    'description' => __( 'Enter the countdown heading.', 'woodojo-launch-pad' ),
		    'type' => 'text',
		    'default' => false,
		    'section' => 'countdown'
		);

		$fields['countdown_launch'] = array(
		    'name' => __( 'Countdown Launch', 'woodojo-launch-pad' ),
		    'description' => __( "Enter the date and time you'll be launching.", 'woodojo-launch-pad' ),
		    'type' => 'timestamp',
		    'default' => false,
		    'section' => 'countdown'
		);

		$fields['enable_newsletter'] = array(
		    'name' => __( 'Enable the newsletter area.', 'woodojo-launch-pad' ),
		    'description' => __( '', 'woodojo-launch-pad' ),
		    'type' => 'checkbox',
		    'default' => false,
		    'section' => 'newsletter'
		);

	    $fields['newsletter_heading'] = array(
		    'name' => __( 'Newsletter Text', 'woodojo-launch-pad' ),
		    'description' => __( 'Enter the newsletter text.', 'woodojo-launch-pad' ),
		    'type' => 'text',
		    'default' => false,
		    'section' => 'newsletter'
		);

		$fields['newsletter_subscribe_button'] = array(
		    'name' => __( 'Submit Button Text', 'woodojo-launch-pad' ),
		    'description' => __( 'Enter the submit button text.', 'woodojo-launch-pad' ),
		    'type' => 'text',
		    'default' => false,
		    'section' => 'newsletter'
		);

		$fields['newsletter_service'] = array(
		    'name' => __( 'Newsletter Service', 'woodojo-launch-pad' ),
		    'description' => __( 'Select which Newsletter service you are using.', 'woodojo-launch-pad' ),
		    'type' => 'select',
		    'options' => $newsletter_service_options ,
		    'default' => '' ,
		    'section' => 'newsletter'
		);

		$fields['newsletter_service_id'] = array(
		    'name' => __( 'Newsletter Service ID', 'woodojo-launch-pad' ),
		    'description' => sprintf( __( 'Enter the your Newsletter Service ID %s(?)%s.', 'woodojo-launch-pad' ), '<a href="' . esc_url( 'http://support.google.com/feedburner/bin/answer.py?hl=en&answer=78982' ) . '" target="_blank">', '</a>' ),
		    'type' => 'text',
		    'default' => '' ,
		    'section' => 'newsletter'
		);

		$fields['newsletter_service_form_action'] = array(
		    'name' => __( 'Newsletter Service Form Action', 'woodojo-launch-pad' ),
		    'description' => __( 'Enter the the form action if required.', 'woodojo-launch-pad' ),
		    'type' => 'text',
		    'default' => '' ,
		    'section' => 'newsletter'
		);

		$fields['newsletter_mail_chimp_list_subscription_url'] = array(
		    'name' => __( 'MailChimp List Subscription URL', 'woodojo-launch-pad' ),
		    'description' => sprintf( __( 'If you have a MailChimp account you can enter the %sMailChimp List Subscribe URL%s to allow your users to subscribe to a MailChimp List.', 'woodojo-launch-pad' ), '<a href="' . esc_url( 'http://woochimp.heroku.com/' ) . '" target="_blank">', '</a>' ),
		    'type' => 'text',
		    'default' => '' ,
		    'section' => 'newsletter'
		);

		$this->fields = $fields;

	} // End init_fields()

} // End Class WooDojo_Maintenance_Mode_Settings
?>