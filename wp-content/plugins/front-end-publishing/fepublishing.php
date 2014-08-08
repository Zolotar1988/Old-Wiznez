<?php
/*
Plugin Name: Front-End Publishing
Plugin URI: http://wpgurus.net/
Description: Accept guest posts without giving your authors access to the back-end area.
Version: 2.2
Author: Hassan Akhtar
Author URI: http://wpgurus.net/
License: GPL2
*/

/**
* Starts output buffer so that auth_redirect() can work in shortcodes
*/

function fep_start_output_buffers(){
	ob_start();
}
add_action('init', 'fep_start_output_buffers');

/**
* Initializes plugin options on first run
*/

function fep_initialize_options(){
	$activation_flag = get_option('fep_misc');
	
	if( $activation_flag )
		return;
	
	$fep_restrictions = array(
		'min_words_title' 	=> 2,
		'max_words_title' 	=> 12,
		'min_words_content' => 250,
		'max_words_content' => 2000,
		'min_words_bio' 	=> 50,
		'max_words_bio' 	=> 100,
		'min_tags' 			=> 1,
		'max_tags' 			=> 5,
		'max_links' 		=> 2,
		'max_links_bio' 	=> 2,
		'thumbnail_required'=> 0
	);
	
	$fep_roles = array(
		'no_check' 			=> false,
		'instantly_publish' => false,
		'enable_media' 		=> false
	);
	
	$fep_misc = array(
		'before_author_bio' 	=> '',
		'disable_author_bio' 	=> false,
		'remove_bios' 			=> false,
		'nofollow_body_links' 	=> false,
		'nofollow_bio_links' 	=> false,
		'posts_per_page' 		=> 10
	);
	
	update_option('fep_post_restrictions', $fep_restrictions);
	update_option('fep_role_settings', $fep_roles);
	update_option('fep_misc', $fep_misc);
}
register_activation_hook(__FILE__, 'fep_initialize_options');

/**
* Removes plugin data before uninstalling
*/

function fep_rollback(){
	wp_deregister_style( 'fep-style' );
	wp_deregister_script( 'fep-script' );
	delete_option('fep_post_restrictions');
	delete_option('fep_role_settings');
	delete_option('fep_misc');
}
register_uninstall_hook(__FILE__, 'fep_rollback');

/**
* Enqueue scripts and stylesheets
*
* @param array $posts WordPress posts to check for the shortcode
* @return array $posts Checked WordPress posts
*
*/

function fep_enqueue_files($posts) {
    if ( empty($posts) )
        return $posts;
 
    $found = false;
 	foreach ($posts as $post) {
        if ( has_shortcode($post->post_content, 'fep_article_list') ||  has_shortcode($post->post_content, 'fep_submission_form') ){
        	$found = true;
            break;
        }
    }
 
    if ($found){
        wp_enqueue_style( 'fep-style', plugins_url('static/css/style.css', __FILE__), array(), '1.0', 'all' );
        wp_enqueue_script( "fep-script", plugins_url( 'static/js/scripts.js', __FILE__ ), array('jquery') );
        wp_localize_script( 'fep-script', 'fepajaxhandler', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
        $fep_rules = get_option('fep_post_restrictions');
		$fep_roles = get_option('fep_role_settings');
		$fep_rules['check_required'] = (isset($fep_roles['no_check']) && $fep_roles['no_check'] && current_user_can( $fep_roles['no_check']))?0:1;
		wp_localize_script( 'fep-script', 'fep_rules', $fep_rules);
		$enable_media = (isset($fep_roles['enable_media']) && $fep_roles['enable_media'])?current_user_can($fep_roles['enable_media']):1;
		if($enable_media)
			wp_enqueue_media();
    }
    return $posts;
}
add_action('the_posts', 'fep_enqueue_files');

/**
* Append post meta (author bio) to post content
*
* @param string $content post content to append the bio to
* @return array $posts modified post content
*
*/

function fep_add_author_bio( $content ){
	$fep_misc = get_option('fep_misc');
	global $post;
	$ID = $post->ID;
	$author_bio = get_post_meta($ID, 'about_the_author', true);
	if(!$author_bio || $fep_misc['remove_bios']) return $content;
	$before_bio = $fep_misc['before_author_bio'];
	ob_start();
	?>
		<?=$content?><?=$before_bio?>
		<div class="fep-author-bio"><?=$author_bio?></div>
	<?php
	return ob_get_clean();
}
add_filter( 'the_content', 'fep_add_author_bio', 100 );

/**
* Scans content for shortcode.
*
* @param string $content post content to scan
* @param string $tag shortcode text
* @return bool: whether or not the shortcode exists in $content
*
*/

if(!function_exists('has_shortcode')){
	function has_shortcode( $content, $tag ) {
		if(stripos($content, '['.$tag.']') !== false)
			return true;
		return false;
	}
}

/**********************************************
*
* Inlcuding modules
*
***********************************************/

include('inc/ajax.php');

include('inc/shortcodes.php');

include('inc/options-panel.php');

//Kses for content and bio | featured image | custom fields | excerpts | custom post types