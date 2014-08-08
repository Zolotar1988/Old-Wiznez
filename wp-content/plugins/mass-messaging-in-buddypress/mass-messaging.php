<?php

register_activation_hook(__FILE__, 'install_massmessaging');

if(!class_exists("StormationMassMessagingPlugin")){
	class StormationMassMessagingPlugin{
		public function __construct(){
			define('MassMessagingGroupsOverrideShowOnlyPublic', 'false');
			define('MassMessagingBlogsOverrideShowOnlyPublic', 'true');
		}

		function addHeaderCode(){
			global $bp;
			?>
			<!-- Using Stormation's 'Mass Messaging' plugin from stormation.info -->
			<?php
			if($bp->current_component == 'messages' && $bp->current_action == 'mass-messaging'){
				?>
				<script language="JavaScript">
					function toggle(source, action) {
						checkboxes = document.getElementsByName(action);
						for(var i in checkboxes)
    						checkboxes[i].checked = source.checked;
					}
				</script>
			<?php
			}
		}
		function addAdminMenus(){
			if(!menuExists('Stormation')){
				add_menu_page('Stormation.info', 'Stormation', 'manage_options', 'Stormation', 'displayStormationOptionsPage', plugins_url('icon16.png', __FILE__));
			}
			add_submenu_page('Stormation', 'Mass Messaging', 'Mass Messaging', 'manage_options', 'MassMessagingOptions', 'displayAdminOptionsPage');
			function displayStormationOptionsPage(){
				echo '<br /><iframe id="StormationFrame" src="http://www.stormation.info/portfolio-category/wordpress/" width="99%" height="600px"></iframe>';
			}
			function displayAdminOptionsPage(){
    			if(!current_user_can('manage_options')){
      				wp_die(__('You do not have sufficient permissions to access this page.'));
    			}else{	
    				echo "<div class=\"wrap\">";
					echo "<div id=\"icon-mass-messaging-options\" class=\"icon32\"><img src=\"".plugins_url('icon32.png', __FILE__)."\" width=32 height=32 /></div><h2>Mass Messaging Options</h2>";
				if(isset($_POST['update_MassMessagingOptions'])){
					do_action('mass_messaging_in_buddypress_before_update_settings');
					if(isset($_POST['update_MassMessagingGroups'])){
               			update_site_option('MassMessagingGroups',$_POST['update_MassMessagingGroups']);
                	}  
               		if(isset($_POST['update_MassMessagingAllGroups'])){
               			update_site_option('MassMessagingAllGroups',$_POST['update_MassMessagingAllGroups']);
                	}  
                	if(isset($_POST['update_MassMessagingAllGroupsOverride'])){
               			update_site_option('MassMessagingAllGroupsOverride',$_POST['update_MassMessagingAllGroupsOverride']);
                	}else{
                		update_site_option('MassMessagingAllGroupsOverride','false');
                	}
                	if(isset($_POST['update_MassMessagingMembers'])){
                    		update_site_option('MassMessagingMembers',$_POST['update_MassMessagingMembers']);
                	}   
                	if(isset($_POST['update_MassMessagingAllMembers'])){
                    		update_site_option('MassMessagingAllMembers',$_POST['update_MassMessagingAllMembers']);
                	}
                	if(isset($_POST['update_MassMessagingAllMembersOverride'])){
               			update_site_option('MassMessagingAllMembersOverride',$_POST['update_MassMessagingAllMembersOverride']);
                	}else{
                		update_site_option('MassMessagingAllMembersOverride','false');
                	}
                	if(isset($_POST['update_MassMessagingBlogs'])){
                    		update_site_option('MassMessagingBlogs',$_POST['update_MassMessagingBlogs']);
                	}   
                	if(isset($_POST['update_MassMessagingAllBlogs'])){
                    		update_site_option('MassMessagingAllBlogs',$_POST['update_MassMessagingAllBlogs']);
                	}
                	if(isset($_POST['update_MassMessagingAllBlogsOverride'])){
               			update_site_option('MassMessagingAllBlogsOverride',$_POST['update_MassMessagingAllBlogsOverride']);
                	}else{
                		update_site_option('MassMessagingAllBlogsOverride','false');
                	}
					if(isset($_POST['update_MassMessagingMinimumType'])){
                    	update_site_option('MassMessagingMinimumType',$_POST['update_MassMessagingMinimumType']);
                	}
                	do_action('mass_messaging_in_buddypress_after_update_settings');
                	?>
					<div class="updated"><p><strong>Options Updated</strong></p></div>
					<?php
					}
					if(isset($_POST['reset_MassMessagingOptions'])){
						do_action('mass_messaging_in_buddypress_before_reset_settings');
						update_site_option('MassMessagingGroups', 'true');
						update_site_option('MassMessagingAllGroups', 'true');
						update_site_option('MassMessagingAllGroupsOverride', 'false');
						update_site_option('MassMessagingMembers', 'true');
						update_site_option('MassMessagingAllMembers', 'true');
						update_site_option('MassMessagingAllMembersOverride', 'true');
						update_site_option('MassMessagingBlogs', 'true');
						update_site_option('MassMessagingAllBlogs', 'true');
						update_site_option('MassMessagingAllBlogsOverride', 'true');
						update_site_option('MassMessagingMinimumType', 'administrator');
						do_action('mass_messaging_in_buddypress_after_reset_settings');
						?>
						<div class="updated"><p><strong>Options Reset</strong></p></div>
						<?php
					}
					$oldMassMessagingGroups = get_site_option('MassMessagingGroups');
					$oldMassMessagingAllGroups = get_site_option('MassMessagingAllGroups');
					$oldMassMessagingAllGroupsOverride = get_site_option('MassMessagingAllGroupsOverride');
					$oldMassMessagingMembers = get_site_option('MassMessagingMembers');
					$oldMassMessagingAllMembers = get_site_option('MassMessagingAllMembers');
					$oldMassMessagingAllMembersOverride = get_site_option('MassMessagingAllMembersOverride');
					$oldMassMessagingBlogs = get_site_option('MassMessagingBlogs');
					$oldMassMessagingAllBlogs = get_site_option('MassMessagingAllBlogs');
					$oldMassMessagingAllBlogsOverride = get_site_option('MassMessagingAllBlogsOverride');
					$oldMassMessagingMinimumType = get_site_option('MassMessagingMinimumType');
					?>
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<table class="form-table">
						<tr>
						<th scope="row">Allow mass messaging to groups?</th>
						<td>
							<fieldset>
							<label title='Yes'><input type='radio' name='update_MassMessagingGroups' value='true' <?php if($oldMassMessagingGroups == 'true'){echo "checked";} ?> /> <span>Yes</span></label>
							<label title='No'><input type='radio' name='update_MassMessagingGroups' value='false' <?php if($oldMassMessagingGroups == 'false'){echo "checked";} ?> /> <span>No</span></label>
							</fieldset>
						</td>
						</tr>
						<tr>
						<th scope="row">Allow mass messaging to select all groups?</th>
						<td>
							<fieldset>
							<label title='Yes'><input type='radio' name='update_MassMessagingAllGroups' value='true' <?php if($oldMassMessagingAllGroups == 'true'){echo "checked";} ?> /> <span>Yes</span></label>
							<label title='No'><input type='radio' name='update_MassMessagingAllGroups' value='false' <?php if($oldMassMessagingAllGroups == 'false'){echo "checked";} ?> /> <span>No</span></label>
							</fieldset>
						</td>
						</tr>
						<tr>
						<th scope="row">Show ALL groups, not just those with membership?</th>
						<td>
							<fieldset>
							<label for='update_MassMessagingAllGroupsOverride'><input type='Checkbox' name='update_MassMessagingAllGroupsOverride' id='update_MassMessagingAllGroupsOverride' value='true' <?php if($oldMassMessagingAllGroupsOverride == 'true'){echo "checked";} ?> /> Yes</label>
							</fieldset>
						</td>
						</tr>
						<tr>
						<th scope="row">Allow mass messaging to members?</th>
						<td>
							<fieldset>
							<label title='Yes'><input type='radio' name='update_MassMessagingMembers' value='true' <?php if($oldMassMessagingMembers == 'true'){echo "checked";} ?> /> <span>Yes</span></label>
							<label title='No'><input type='radio' name='update_MassMessagingMembers' value='false' <?php if($oldMassMessagingMembers == 'false'){echo "checked";} ?> /> <span>No</span></label>
							</fieldset>
						</td>
						</tr>
						<tr>
						<th scope="row">Allow mass messaging to select all members?</th>
						<td>
							<fieldset>
							<label title='Yes'><input type='radio' name='update_MassMessagingAllMembers' value='true' <?php if($oldMassMessagingAllMembers == 'true'){echo "checked";} ?> /> <span>Yes</span></label>
							<label title='No'><input type='radio' name='update_MassMessagingAllMembers' value='false' <?php if($oldMassMessagingAllMembers == 'false'){echo "checked";} ?> /> <span>No</span></label>
							</fieldset>
						</td>
						</tr>
						<tr>
						<th scope="row">Show ALL members, not just friends?</th>
						<td>
							<fieldset>
							<label for='update_MassMessagingAllMembersOverride'><input type='Checkbox' name='update_MassMessagingAllMembersOverride' id='update_MassMessagingAllMembersOverride' value='true' <?php if($oldMassMessagingAllMembersOverride == 'true'){echo "checked";} ?> /> Yes</label>
							</fieldset>
						</td>
						</tr>
						<tr>
						<th scope="row">Allow mass messaging to blogs (requires Multisite)?</th>
						<td>
							<fieldset>
							<label title='Yes'><input type='radio' name='update_MassMessagingBlogs' value='true' <?php if($oldMassMessagingBlogs == 'true'){echo "checked";} ?> /> <span>Yes</span></label>
							<label title='No'><input type='radio' name='update_MassMessagingBlogs' value='false' <?php if($oldMassMessagingBlogs == 'false'){echo "checked";} ?> /> <span>No</span></label>
							</fieldset>
						</td>
						</tr>
						<tr>
						<th scope="row">Allow mass messaging to select all blogs (requires Multisite)?</th>
						<td>
							<fieldset>
							<label title='Yes'><input type='radio' name='update_MassMessagingAllBlogs' value='true' <?php if($oldMassMessagingAllBlogs == 'true'){echo "checked";} ?> /> <span>Yes</span></label>
							<label title='No'><input type='radio' name='update_MassMessagingAllBlogs' value='false' <?php if($oldMassMessagingAllBlogs == 'false'){echo "checked";} ?> /> <span>No</span></label>
							</fieldset>
						</td>
						</tr>
						<tr>
						<th scope="row">Show ALL blogs, not just those with membership?</th>
						<td>
							<fieldset>
							<label for='update_MassMessagingAllBlogsOverride'><input type='Checkbox' name='update_MassMessagingAllBlogsOverride' id='update_MassMessagingAllBlogsOverride' value='true' <?php if($oldMassMessagingAllBlogsOverride == 'true'){echo "checked";} ?> /> Yes</label>
							</fieldset>
						</td>
						</tr>
						<tr>
						<th scope="row"><label for="update_MassMessagingMinimumType">Minimum role for usage</label></th>
						<td><select name="update_MassMessagingMinimumType" id="update_MassMessagingMinimumType">
							<?php
							$items = array('Super Admin' => 'super admin', 'Administrator' => 'administrator', 'Editor' => 'editor', 'Author' => 'author', 'Contributor' => 'contributor', 'Subscriber' => 'subscriber');
							foreach($items as $item=>$value){
								$selected = ($oldMassMessagingMinimumType == $value ) ? 'selected="selected"' : '';
								echo "<option value='$value' $selected>$item</option>";
							}
							?>
						</select></td>
						</tr>
						</table>
						<table><tr><td><p class="submit"><input type="submit" name="update_MassMessagingOptions" id="submit" class="button-primary" value="Save Changes"  /></p></td>
						<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>"><td><p class="submit"><input type="submit" name="reset_MassMessagingOptions" id="submit" class="button-secondary" value="Reset to defaults"  /></p></td></form></tr>
						</table>
						</form></div>
						Still struggling? You can view the official help <a href="http://www.stormation.info/portfolio-item/mass-messaging-in-buddypress/">here...</a>
                			<?php
				}
			}
		}
		function setupToolbar($wp_admin_bar){
			global $bp;
			$user_domain = $bp->loggedin_user->domain;
			$link = trailingslashit( $user_domain . $bp->messages->slug );
			$wp_admin_nav = array(
				'parent' => 'my-account-' . $bp->messages->id,
				'title'  => 'Mass Messaging',
				'href'   => trailingslashit($link . 'mass-messaging')
			);
			$role = get_site_option('MassMessagingMinimumType');
			do_action('mass_messaging_in_buddypress_before_user_can_view');
			if(current_user_can($role)){
				$wp_admin_bar->add_node( $wp_admin_nav );
			}
			do_action('mass_messaging_in_buddypress_after_user_can_view');
		}
		function setupNavigation(){
			global $bp, $current_user, $wp_admin_bar;
			get_currentuserinfo();

			$role = get_site_option('MassMessagingMinimumType');
			do_action('mass_messaging_in_buddypress_before_user_can_view');
			if(current_user_can($role)){
				bp_core_new_subnav_item( array(
   					'name' => 'Mass Messaging',
   					'slug' => 'mass-messaging',
   					'parent_url' => $bp->loggedin_user->domain.$bp->messages->slug.'/',
   					'parent_slug' => $bp->messages->slug,
   					'screen_function' => 'mass_messaging_page_screen',
   					'default_subnav_slug' => 'inbox',
   					'item_css_id' => $bp->messages->id,
   					'position' => 100 ) );
   			}
   			do_action('mass_messaging_in_buddypress_after_user_can_view');

    		function mass_messaging_page_screen_title(){
        		echo 'Mass Messaging';
    		}
    		function mass_messaging_page_screen_content(){
    			global $wpdb, $bp, $blogs_template;
    			
    			do_action('mass_messaging_in_buddypress_before_post_settings');    			
    			$users = array();
    			
    			$subject = $_POST['subject'];
    			$content = $_POST['content'];
    			
				$thread = $_POST['thread'];
				$threaded = false;
				if(!empty($thread)){
					if($thread == 1){
						$threaded = true;
					}
				}
				do_action('mass_messaging_in_buddypress_after_post_settings');
				
				do_action('mass_messaging_in_buddypress_before_blogs_actions');
				$blogs = $_POST['blogs'];
    			if(!empty($blogs)){
    				$blogsCount = count($blogs);
    				for($i=0; $i < $blogsCount; $i++){
    					$blogMembers = get_users('blog_id='.$blogs[$i]);
						foreach($blogMembers as $blogMember){
							array_push($users, $blogMember->ID);
						}
    				}
    			}
    			do_action('mass_messaging_in_buddypress_after_blogs_actions');
    			
    			do_action('mass_messaging_in_buddypress_before_groups_actions');
    			$groups = $_POST['groups'];
  				if(!empty($groups)){
    				$groupCount = count($groups);
    				for($i=0; $i < $groupCount; $i++){
						if(is_numeric($groups[$i])){
    						$groupMembers = $wpdb->get_col("SELECT user_id FROM {$bp->groups->table_name_members} WHERE group_id = ".$groups[$i]." AND is_confirmed = 1 AND is_banned = 0");
						}
  						$groupMembersCount = count($groupMembers);
    					for($i=0; $i < $groupMembersCount; $i++){
      						array_push($users, $groupMembers[$i]);
      					}
      				}
 				}
 				do_action('mass_messaging_in_buddypress_after_groups_actions');
 				
    			do_action('mass_messaging_in_buddypress_before_members_actions');
 				$members = $_POST['members'];
  				if(!empty($members)){
    					$membersCount = count($members);
    					for($i=0; $i < $membersCount; $i++){
      						array_push($users, $members[$i]);
      					}
 				}
 				do_action('mass_messaging_in_buddypress_after_members_actions');
 				$usersFinal = array_unique($users, SORT_NUMERIC);
 				$sent = 0;
 				$sender = $bp->loggedin_user->id;
 				
				if($threaded == false){
 					foreach ($usersFinal as $value) {
 						if($value != $sender){
							if( messages_new_message( array('sender_id' => $sender, 'subject' => $subject, 'content' => $content, 	'recipients' => $value ) ) ){
								$sent++;
							}
						}
						if ( $sent % 50 == 0 )
							sleep(5);
					}
					if($sent > 0){
						echo "<div id=\"message\" class=\"updated\"><p>";
						if($sent != 1){
							echo "Messages sent to ".$sent." people";
						}else{
							echo "Message sent to 1 person";
						}
						echo "</p></div>";
					}
				}else{
					foreach ($usersFinal as $key => $value) {
 						if($value == $sender){
							unset($usersFinal[$key]);
						}
					}
					messages_new_message( array('sender_id' => $sender, 'subject' => $subject, 'content' => $content, 	'recipients' => $usersFinal ) );
					$sent = count($usersFinal);
					if($sent > 0){
						echo "<div id=\"message\" class=\"updated\"><p>";
						if($sent != 1){
							echo "Messages sent to ".$sent." people";
						}else{
							echo "Message sent to 1 person";
						}
						echo "</p></div>";
					}
				}
 				
				$MassMessagingGroups = get_site_option('MassMessagingGroups');
				$MassMessagingAllGroups = get_site_option('MassMessagingAllGroups');
				$MassMessagingAllGroupsOverride = get_site_option('MassMessagingAllGroupsOverride');
				$MassMessagingMembers = get_site_option('MassMessagingMembers');
				$MassMessagingAllMembers = get_site_option('MassMessagingAllMembers');
				$MassMessagingAllMembersOverride = get_site_option('MassMessagingAllMembersOverride');
				$MassMessagingBlogs = get_site_option('MassMessagingBlogs');
				$MassMessagingAllBlogs = get_site_option('MassMessagingAllBlogs');
				$MassMessagingAllBlogsOverride = get_site_option('MassMessagingAllBlogsOverride');
				$MassMessagingMinimumType = get_site_option('MassMessagingMinimumType');
    			?>
    			<form action="" method="post" id="send_message_form" class="standard-form">
				
				<label for="subject" class="subject">Subject</label>
				<input type="text" name="subject" id="subject" value="" />

				<label for="content" class="content">Message</label>
				<textarea name="content" id="message_content" rows="15" cols="50"></textarea>
				
				<?php if($MassMessagingAllBlogs == 'true'){ ?>
				
				<h3 style="width:100%;">All Blogs</h3>
				<input type=checkbox style="width:12px;" onClick="toggle(this, 'blogs[]')" >All Blogs<br />
				
				<?php } ?>
				<?php if($MassMessagingBlogs == 'true'){ ?>
				
					<h3 style="width:100%;">Blogs</h3>
				
					<?php if($MassMessagingAllBlogsOverride == 'true'){ 
			
						if(MassMessagingBlogsOverrideShowOnlyPublic == 'true'){
						
							$blogsAll = $wpdb->get_results("SELECT * FROM {$wpdb->blogs} AND spam = '0' AND deleted = '0' AND archived = '0' AND public='1'");
							
						}else{
							$blogsAll = $wpdb->get_results("SELECT * FROM {$wpdb->blogs} AND spam = '0' AND deleted = '0' AND archived = '0'");
						}
						foreach ( $blogsAll as $blogAll) {
							?>
							<input type=checkbox style="width:12px;" name="blogs[]" value="<?php echo $blogAll->id; ?>"><?php echo $blogAll->name; ?>
							<br />
							<?php
						}
				
					}else{
					
						if ( bp_has_blogs( 'user_id='.$user_id ) ) : ?>
							<?php while ( bp_blogs() ) : bp_the_blog(); ?>
								<input type=checkbox style="width:12px;" name="blogs[]" value="<?php echo $blogs_template->blog->blog_id; ?>"><?php bp_blog_name(); ?>
								<br />
							<?php endwhile; ?>
						<?php endif; ?>
					
					<?php } ?>
				
				<?php } ?>
				
				<?php if($MassMessagingAllGroups == 'true'){ ?>
				
				<h3 style="width:100%;">All Groups</h3>
				<input type=checkbox style="width:12px;" onClick="toggle(this, 'groups[]')" >All Groups<br />
				
				<?php } ?>
				<?php if($MassMessagingGroups == 'true'){ ?>
				
					<h3 style="width:100%;">Groups</h3><br>
				
					<?php if($MassMessagingAllGroupsOverride == 'true'){ 
					
						if(MassMessagingGroupsOverrideShowOnlyPublic == 'true'){
				
							$groupsAll = $wpdb->get_results("SELECT * FROM {$bp->groups->table_name} WHERE status = 'public'");
							
						}else{
						
							$groupsAll = $wpdb->get_results("SELECT * FROM {$bp->groups->table_name}");
							
						}
						
						foreach ( $groupsAll as $groupAll) {
							?>
							<input type=checkbox style="width:12px;" name="groups[]" value="<?php echo $groupAll->id; ?>"><?php echo $groupAll->name; ?>
							<br />
							<?php
						}
				
					}else{ ?>

					<?php if ( bp_has_groups('type=newest&per_page=99999&user_id='.$bp->loggedin_user->id) ) : ?>
						
						<?php while ( bp_groups() ) : bp_the_group(); ?>
							<input type=checkbox style="width:12px;" name="groups[]" value="<?php bp_group_id(); ?>"><?php bp_group_name(); ?>
							<br />
						<?php endwhile; ?>
					<?php endif; ?>
				
					<?php } ?>
				<?php } ?>

				<?php if($MassMessagingAllMembers == 'true'){ ?>
				
				<h3 style="width:100%;">All Members</h3>
				<input type=checkbox style="width:12px;" onClick="toggle(this, 'members[]')" >All Members<br />
				
				<?php } ?>
				<?php if($MassMessagingMembers == 'true'){ ?>
			
					<h3 style="width:100%;">Members</h3>
				
					<?php if($MassMessagingAllMembersOverride == 'true'){
					
						$membersAll = $wpdb->get_results("SELECT * from $wpdb->users");
						
						foreach ( $membersAll as $memberAll ) {
							?>
							<input type=checkbox style="width:12px;" name="members[]" value="<?php echo $memberAll->ID; ?>"><?php echo $memberAll->display_name; ?>
							<br />
							<?php
						}
					
					}else{ ?>
				
						<?php if(bp_has_members('per_page=99999&type=newest')): ?>
							<?php while ( bp_members() ) : bp_the_member(); ?>
								<input type=checkbox style="width:12px;" name="members[]" value="<?php bp_member_user_id(); ?>"><?php bp_member_name(); ?><br />
							<?php endwhile; ?>
						<?php endif; ?>
					<?php } ?>
				
				<?php } ?>
				<br />
				<input type=checkbox style="width:12px;" name="thread" class="thread" value="1">Send as 1 message?<br />
				<input type="submit" value="Send Message &rarr;" name="send" id="send" />
				</form>
        	<?php
    		}
   		}
	}
}

function mass_messaging_page_screen(){
	add_action('bp_template_title', 'mass_messaging_page_screen_title');
	add_action('bp_template_content', 'mass_messaging_page_screen_content');
	bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
}

if(class_exists("StormationMassMessagingPlugin")){
	$S_MassMessaging = new StormationMassMessagingPlugin();
}

if(isset($S_MassMessaging)){
	add_action('wp_head', array(&$S_MassMessaging, 'addHeaderCode'), 1);
	add_action('bp_setup_nav', array(&$S_MassMessaging, 'setupNavigation'), 1);
	add_action('admin_menu', array(&$S_MassMessaging,'addAdminMenus'), 1);
	add_action('admin_bar_menu', array(&$S_MassMessaging,'setupToolbar'), 999 );
}

function install_massmessaging(){
	add_site_option('MassMessagingGroups', 'true');
	add_site_option('MassMessagingAllGroups', 'true');
	add_site_option('MassMessagingAllGroupsOverride', 'false');
	add_site_option('MassMessagingMembers', 'true');
	add_site_option('MassMessagingAllMembers', 'true');
	add_site_option('MassMessagingAllMembersOverride', 'false');
	add_site_option('MassMessagingBlogs', 'true');
	add_site_option('MassMessagingAllBlogs', 'false');
	add_site_option('MassMessagingAllBlogsOverride', 'false');
	add_site_option('MassMessagingMinimumType', 'administrator');
}

if(!function_exists('menuExists')) {
	function menuExists($handle, $sub = false) {
		global $menu, $submenu;
		if(!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
			return false;
		}	
		$check_menu = $sub ? $submenu : $menu;
		if(empty($check_menu)) {
			return false;
		}
		foreach($check_menu as $k => $item) {
			if($sub) {
				foreach($item as $sm) {
					if($handle == $sm[2]) {
						return true;
					}
				}
			} else {
				if($handle == $item[2]) {
					return true;
				}
			}
		}
		return false;
	}
}
?>