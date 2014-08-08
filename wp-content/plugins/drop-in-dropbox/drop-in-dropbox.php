<?php
/*
Plugin Name: Drop in Dropbox
Plugin URI: http://steamingkettle.net/web-design/wordpress-plugins/
Description: Upload single files or entire directories with subdirectories to your Dropbox account.
Version: 0.2.7
Author: Denis Buka
Author URI: http://steamingkettle.net

Copyright (C) 2011 steamingkettle.net

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public Licensealong with this program. If not, see <http://www.gnu.org/licenses/>.
*/



require_once( dirname(__FILE__) . '/functions.php' );
require_once( dirname(__FILE__) . '/DropboxUploader.php' );
require_once( dirname(__FILE__) . '/a.charset.php' );

register_activation_hook(__FILE__, 'drop_drop_add_defaults');
register_uninstall_hook(__FILE__, 'drop_drop_delete_plugin_options');
add_action('admin_init', 'drop_drop_init' );
add_action('admin_menu', 'drop_drop_add_options_page');

function drop_drop_delete_plugin_options() {
	delete_option('drop_drop_options');
}

function drop_drop_add_defaults() {
	$tmp = get_option('drop_drop_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('drop_drop_options'); 
		$arr = array(	
						"drop_drop_email" => "",
						"drop_drop_pwd" => "",
						"drop_drop_rem_dir" => "",
						"drop_drop_loc_dir" => ABSPATH,
						"drop_drop_time_hr" => "",
						"drop_drop_time_min" => "",
						"drop_drop_freq" => ""
		);
		update_option('drop_drop_options', $arr);
	}
}

function drop_drop_init(){
	register_setting( 'drop_drop_plugin_options', 'drop_drop_options', 'drop_drop_validate_options' );
}

function drop_drop_add_options_page() {
	add_options_page('Drop in Dropbox Options', 'Drop in Dropbox', 'manage_options', __FILE__, 'drop_drop_render_form');
}

function drop_drop_render_form() {
	?>
	<div class="wrap">
		
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Drop in Dropbox Settings</h2>
		
		<h3><a target="_blank" href="http://db.tt/Og2TFSR4">Sign up for Dropbox &raquo;</a></h3>

		<form method="post" action="options.php">
			<?php settings_fields('drop_drop_plugin_options'); ?>
			<?php $options = get_option('drop_drop_options'); ?>


			<table class="form-table">
				
				<tr>
					<th scope="row" style="width:270px;"><strong>Dropbox account details:</strong><br /></th>
					<td>
						<label>E-mail: <input type="text" size="30" name="drop_drop_options[drop_drop_email]" value="<?php echo $options['drop_drop_email']; ?>" /></label>
						&nbsp;&nbsp;&nbsp;
						<label>Password: <input type="password" size="30" name="drop_drop_options[drop_drop_pwd]" value="" /></label>
						<?php if( trim( $options['drop_drop_pwd'] ) != '' ) { ?>
						&nbsp;&nbsp;<em style="color:gray;">(password saved)</em>
						<?php } ?>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><strong>Directory or file to upload:</strong>
					<br /><em>(could be anything within your WordPress installation)</em>
					</th>
					<td>
						<?php if( !is_dir( charset_x_win( $options['drop_drop_loc_dir'] ) ) && !file_exists( $options['drop_drop_loc_dir'] ) ) { 
								$error = 1; ?>
							<span style="color:red;">Please specify correct directory path:</span><br />
						<?php } ?>
						<label><input style="text-align:left;" type="text" size="88" name="drop_drop_options[drop_drop_loc_dir]" value="<?php echo $options['drop_drop_loc_dir']; ?>" /></label>
						<br /><em>(full directory path)</em>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><strong>WordPress installation path:</strong>
					</th>
					<td>
						<code><?php echo ABSPATH; ?></code>
						<br /><em>(you can use this path to back up your entire WordPress installation)</em>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><strong>Remote Dropbox folder:</strong></th>
					<td>
						<label><input style="text-align:left;" type="text" size="88" name="drop_drop_options[drop_drop_rem_dir]" value="<?php echo $options['drop_drop_rem_dir']; ?>" /></label>
						<br /><em>(if the folder doesn't exist it will be created)</em>
					</td>
				</tr>
				
			</table>
			<?php 	
				$options['drop_drop_final_dir'] = $options['drop_drop_dir'];
				update_option( 'drop_drop_options', $options ); 
				
				$runflag = dirname(__FILE__) . '/tmp/drop_running';
				if( file_exists( $runflag ) ) {  // delete flagfile if it's too old
					$filetime = filemtime( $runflag );
					$timeout = time()-1200; 
					if ($filetime <= $timeout) {
						unlink( $runflag );
					} 
				}

			?>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
		<?php if( $error != 1 ) { ?>
			<form method="post" class="submit">
			<?php 
			if( ( isset( $_POST['drop_drop_now'] ) || file_exists( $runflag ) ) && !isset( $_POST['drop_drop_abort'] ) ) { 
				if( file_exists( $runflag ) ) {
					echo 	'	<p style="color: green"><strong>Running...</strong></p>
								<p><strong style="color: green">Currently uploading: </strong></span><code>' . file_get_contents( $runflag ) . '</code></p>
								<p><input type="submit" name="drop_drop_abort" value="Abort" />&nbsp;&nbsp;&nbsp;<input type="submit" name="refresh" value="Refresh" /></p>
							';
				} else {
					echo 	'	<p style="color: green"><strong>Starting upload...</strong></p>';
					try { initDrop(); } catch(Exception $e) { echo '<p style="color:red"><strong>Error message: ' . $e->getMessage() . '</strong></p>'; }
					echo 	'	<p><input type="submit" name="drop_drop_abort" value="Abort" />&nbsp;&nbsp;&nbsp;<input type="submit" name="refresh" value="Refresh" /></p>
					';
				}
			} else {
				if( isset( $_POST['drop_drop_abort'] ) ) {
					cleanTmp();
					echo '<p style="color:red"><strong>Uploading to Dropbox aborted.</strong></p>';
				}
				echo 	'<p>
							<input type="submit" name="drop_drop_now" value="Drop in Dropbox now!" />
							&nbsp;&nbsp;<em>(make sure you\'ve saved any recent changes)</em>
						</p>';
			}
			?>
			</form>			
		<?php } ?>
		<br />
		<hr />
		<h3>My other plugins:</h3>
		<ul>
			<li><a href="http://wordpress.org/extend/plugins/intuitive-navigation/">Intuitive Navigation</a></li>   
			<li><a href="http://wordpress.org/extend/plugins/generate-cache/">Generate Cache</a></li>   
		</ul>
	</div>
	<?php	
}

function drop_drop_validate_options($input) {
	if( trim( $input['drop_drop_pwd'] ) == '' ) {
		$options = get_option('drop_drop_options');
		$input['drop_drop_pwd'] = $options['drop_drop_pwd'];
	}
	return $input;
}

add_filter( 'plugin_action_links', 'drop_drop_plugin_action_links', 10, 2 );
function drop_drop_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$drop_drop_links = '<a href="'.get_admin_url().'options-general.php?page=drop-in-dropbox/drop-in-dropbox.php">'.__('Settings').'</a>';
		array_unshift( $links, $drop_drop_links );
	}

	return $links;
}

?>
