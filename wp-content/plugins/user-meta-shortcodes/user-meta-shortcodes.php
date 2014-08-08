<?php
/**
Plugin Name: User meta shortcodes
Description: Use user meta-data as shortcodes in post content
Plugin URI:  http://vencu.ro/user-meta-shortcodes/
Author:      Richard Vencu
Author URI:  http://vencu.ro
Version:     0.4.1


USAGE:

use [userinfo field="fieldname"]some content[/userinfo] or [authorinfo field="fieldname"]some content[/authorinfo] shortcodes in your post content to show the "fieldname" meta value from the user-meta for the current logged-in user or for the post author without editing your theme files.


EXAMPLES:

[userinfo field="last_name"]{{empty}}[/userinfo]

returns the last name of the current logged-in user. If no user is logged in then the value is empty string

[userinfo field="user_login" if="admin"]You are the admin[/userinfo]

this shortcode tests the field against the value and if true it displays the included content

[userinfo field="user_login"] is your username and you are reading a post by [authorinfo field="user_login"]{{empty}}[/authorinfo].[/userinfo]

this will display the username followed with the processed content. Note: never use the nested shortcode same as the enclosing shortcode. This will produce unexpected results!

[userinfo nospan="true"] should eliminate the surrounding span tag so the output can be used inside URLs or similar applications

[userinfo field="avatar" size="50"] will display the logged-in user's avatar with the size of 50px. The display depends of theme's css class 'avatar'


List of some of the available meta field names: ID, user_login, user_pass, user_nicename, user_email, user_url, user_registered, display_name, first_name, last_name,nickname, description, user_level, admin_color (Theme of your admin page. Default is fresh.), closedpostboxes_page, nickname, primary_blog, rich_editing, source_domain

[authorinfo field="last_name"]

returns the last name of the current post/page author.


[authorinfo field="ID"]

returns the user ID of the current post/page author.

[authorinfo field="avatar" size="50"] will display the post author's avatar with the size of 50px. The display depends of theme's css class 'avatar'

[authorinfo field="posts"] will display the  author's posts link

List of some of the available meta field names: user_login, user_pass, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name,nickname, first_name, last_name, description, jabber, aim, yim, user_level, user_firstname, user_lastname, user_description, rich_editing, comment_shortcuts, admin_color,plugins_per_page, plugins_last_view, ID 

If you add custom user meta via additional plugins, then the meta should be available for the above shortcodes



*/
?>
<?php

  function otherUserInfoSc ( $attributes, $content=null ) {    

	extract(shortcode_atts(array(
		"field" => null, "if" => null, "nospan"=>false, "size"=>32, $default=>"", "uservar"=>"", "login"=>""
	), $attributes));
	
	//use {{empty}} instead of nothing inside the shortcode
	if('{{empty}}'==$content) $content="";
	
	if (isset($uservar) && $uservar != "") {
		$user_info = get_user_by("login",$_GET[$uservar]);
	}
	else
	{
		$user_info = get_user_by("login",$login);
	}
	
	//if we have a test, we print the content in case the test is true. otherwise we print the field value concatenated with the content of the shortcode
	if (isset($if) && $field != "avatar") {
		if ( $if == $user_info->$field ) return do_shortcode($content);
	}
	else {
		if( $field != "avatar" ) {
			if ($nospan)
				return $user_info->$field . do_shortcode($content);
			else
				return "<span class=\"userinfo\">" . $user_info->$field . do_shortcode($content) . "</span>";
		}
		else  {
			return get_avatar( $user_info->id, $size, $default, $user_info->first_name . " " . $user_info->last_name );
		}
	}

	//return empty string in case we arrive here
	return "";

}
  add_shortcode('otheruserinfo','otheruserInfoSc');

  function userInfoSc ( $attributes, $content=null ) {    

	extract(shortcode_atts(array(
		"field" => null, "if" => null, "nospan"=>false, "size"=>32, $default=>""
	), $attributes));
	
	//use {{empty}} instead of nothing inside the shortcode
	if('{{empty}}'==$content) $content="";

    global $user_info, $user_ID;
	
    get_currentuserinfo();

    $user_info = get_userdata($user_ID);
	
	//if we have a test, we print the content in case the test is true. otherwise we print the field value concatenated with the content of the shortcode
	if (isset($if) && $field != "avatar") {
		if ( $if == $user_info->$field ) return do_shortcode($content);
	}
	else {
		if ( is_user_logged_in() ) {
			if( $field != "avatar" ) {
				if ($nospan)
					return $user_info->$field . do_shortcode($content);
				else
					return "<span class=\"userinfo\">" . $user_info->$field . do_shortcode($content) . "</span>";
			}
			else  {
				return get_avatar( $user_info->id, $size, $default, $user_info->first_name . " " . $user_info->last_name );
			}
		}
	}

	//return empty string in case we arrive here
	return "";

}

  add_shortcode('userinfo','userInfoSc');

  function authorInfoSc ( $attributes, $content=null ) {

	extract(shortcode_atts(array(
		"field" => null, "if" => null, "nospan"=>false, "size"=>32, $default=>""
	), $attributes));
	
	//use {{empty}} instead of nothing inside the shortcode
	if('{{empty}}'==$content) $content="";

	//if we have a test, we print the content in case the test is true. otherwise we print the field value concatenated with the content of the shortcode
	if (isset($if)) {
		if ( $if == get_the_author_meta($field) ) return do_shortcode($content);
	}
	else {
		switch ($field) {
			case "avatar":
				return get_avatar( get_the_author_meta("id"), $size, $default, get_the_author_meta("first_name") . " " . get_the_author_meta(last_name) );
			case "posts":
				return get_author_posts_url( get_the_author_meta("id") );
			default:
				if ($nospan)
					return get_the_author_meta($field) . do_shortcode($content);
				else
					return "<span class=\"userinfo\">" . get_the_author_meta($field) . do_shortcode($content) . "</span>";
		}
	}

	//return empty string in case we arrive here
	return "";

  }

  add_shortcode('authorinfo','authorInfoSc');

?>