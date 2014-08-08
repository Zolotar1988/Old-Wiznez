<?php
/**
	* Plugin Name: BP-SearchFriends
	* Plugin URI: http://themingpress.com/bp-searchfriends
	* Description: A simple ajax based buddypress friends search plugin. Use Shortcode [BP_SearchFriends] to embed it into your page.
	* Version: 1.0
	* Author: Themingpress
	* Author URI: http://themingpress.com
	* License: GPLv2 or later
	* License URI: http://www.gnu.org/licenses/gpl-2.0.html
**/

wp_enqueue_script('jquery');

if ( ! class_exists('BP_SearchFriends') ) {

	class BP_SearchFriends {
	
		public function register_plugin_styles() {
			wp_register_style( 'BP_SearchFriends', plugins_url( '/BP_SearchFriends/css/bpds_default.css' ) );
			wp_enqueue_style( 'BP_SearchFriends' );
		}

		function BP_SearchFriends() {
			
			// Initialize the AJAX hooks
			add_action( 'init', array( $this, 'register_ajax' ) );

			// Create shortcode to use the plugin
			add_shortcode( 'BP_SearchFriends', array( $this, 'ajax_load' ) );
			
			add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );
		}

		function register_ajax() {

			// BP Search Friends Ajax Settings

			// Include a hidden field in your form called "action" with the value
			// "BP_SearchFriends_ajax_settings" to trigger these callbacks

			// For authenticated visitors, trigger this one
			add_action( 'wp_ajax_BP_SearchFriends_ajax_settings', array( $this, 'ajax_BP_SearchFriends_settings' ) );

			// For unauthenticated visitors, trigger this one
			add_action( 'wp_ajax_nopriv_BP_SearchFriends_ajax_settings', array( $this, 'ajax_BP_SearchFriends_settings' ) );
		}

		function ajax_BP_SearchFriends_settings() {
			if ( ! isset( $_POST ) ) {
				die();
			}
			else{
				$q=$_POST['s'];
				
				global $wpdb;
				$posts = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE display_name like '%$q%' or user_email like '%$q%' or user_nicename like '%$q%' or user_login like '%$q%' order by ID LIMIT 10");
			?>
			<ul class="BPSF-results">
			<?php
				foreach($posts as $row){
					$name = $row -> display_name;
					$member_id=$row -> ID;
			?>
				<li>
					<a href="<?php echo bp_core_get_user_domain( $member_id ) ?>">
						<span class="BPSF-memberavatar">
							<?php echo bp_core_fetch_avatar ( array( 'item_id' => $member_id, 'width' => '40px', 'height' => '40px' ) ) ?>
						</span>
						<span class="BPSF-userdetails">
							<p><?php echo $name; ?></p>
						</span>
					</a>
					<a class="BPSF-send-msg" href="<?php echo bp_send_private_message_link(); ?>">Send Message </a>
				</li>
			<?php
				}
			?>
			</ul>
			<?php
			}		
			// Make sure we don't continue after sending back our data or messaging
			die();
		}

		function ajax_load() {
?>
			<form id="BP_SearchFriends" action="" method="post">

				<input type="text" name="s" id="s" class="search" placeholder="Enter your friend's name"autocomplete="off" />
				<!-- this field is how Wordpress knows what ajax callback to trigger -->
				<!-- You can also pass this via the data in your ajax call -->
				<input type="hidden" name="action" value="BP_SearchFriends_ajax_settings" />
				
				<div class="BPSF-response" style="display:none;"></div>

			</form>

			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery("#BP_SearchFriends #s").keyup(function(){
						// For this example we require POST, but GET will work as well.
						jQuery.post(
							// This is the url to the ajax processing page in Wordpress
							'<?php echo admin_url( 'admin-ajax.php' ) ?>',
							// We can pass all the values of the form this way, or it can be defined explictly
							jQuery( '#BP_SearchFriends' ).serialize(),
							function( response ){
								jQuery( '#BP_SearchFriends .BPSF-response').html( response ).show();
							}
						);
						return false;
					});
					
					jQuery("#BP_SearchFriends .BPSF-response").live("click",function(e){ 
						  var $clicked = jQuery(e.target);
						  var $name = $clicked.find('.name').html();
						  var decoded = jQuery("<div/>").html($name).text();
						  jQuery('#BP_SearchFriends #s').val(decoded);
					});
					jQuery(document).live("click", function(e) { 
						  var $clicked = jQuery(e.target);
						  if (! $clicked.hasClass("search")){
						  jQuery("#BP_SearchFriends .BPSF-response").fadeOut(); 
						  }
					});
					jQuery('#BP_SearchFriends #s').click(function(){
						  jQuery("#BP_SearchFriends .BPSF-response").fadeIn();
					});
				});
			</script>
			<?php
		}
	}
	
	$BP_SearchFriends = new BP_SearchFriends();

}

?>