<?php
/*
Plugin Name: Easy Media Gallery
Plugin URI: http://www.ghozylab.com/plugins/
Description: Easy Media Gallery (Lite) - Displaying your images, videos (MP4, Youtube, Vimeo) and audio mp3 in elegant and fancy lightbox with very easy. Allows you to customize all media to get it looking exactly what you want. <a href="http://ghozylab.com/plugins/easy-media-gallery-pro/pricing/" target="_blank"><strong> Upgrade to Pro Version Now</strong></a> and get a tons of awesome features.
Author: GhozyLab, Inc.
Version: 1.2.49
Author URI: http://www.ghozylab.com/plugins/
*/

if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly!');
}

/*
|--------------------------------------------------------------------------
| Requires Wordpress Version
|--------------------------------------------------------------------------
*/
function req_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );

	if ( version_compare( $wp_version, "3.3", "<" ) ) {
		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
			wp_die( "Easy Media Gallery Lite requires WordPress 3.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>" );
		}
	}
}
add_action( 'admin_init', 'req_wordpress_version' );


/*
|--------------------------------------------------------------------------
| Requires PHP Version (min version PHP 5.2)
|--------------------------------------------------------------------------
*/
if ( version_compare(PHP_VERSION, '5.2', '<') ) {
	if ( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX) ) {
		require_once ABSPATH.'/wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
	    wp_die( "Easy Media Gallery Lite requires PHP 5.2 or higher. The plugin has now disabled itself. Please ask your hosting provider for this issue.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>" );
	} else {
		return;
	}
}


/*
|--------------------------------------------------------------------------
| Requires GD Library
|--------------------------------------------------------------------------
*/
if ( is_admin() ) {
	if ( !extension_loaded('gd') && !function_exists('gd_info') ) {
		require_once ABSPATH.'/wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
	    wp_die( "<strong>GD Library</strong> for PHP is not installed on your server. Easy Media Gallery requires it to function properly. The plugin has now disabled itself. Please ask your hosting provider for this issue.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>" );
		}
}

// Learn more here http://www.webassist.com/tutorials/Enabling-the-GD-library-setting


/*
|--------------------------------------------------------------------------
| Defines
|--------------------------------------------------------------------------
*/
if ( !defined( 'EASYMEDG_PLUGIN_BASENAME' ) )
    define( 'EASYMEDG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( !defined( 'EASYMEDG_PLUGIN_NAME' ) )
    define( 'EASYMEDG_PLUGIN_NAME', trim( dirname( EASYMEDG_PLUGIN_BASENAME ), '/') );

if ( !defined( 'EASYMEDG_PLUGIN_DIR' ) )
    define( 'EASYMEDG_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . EASYMEDG_PLUGIN_NAME . '/' );

if ( !defined( 'EASYMEDG_PLUGIN_URL' )) {
	if (is_ssl()) {
    define( 'EASYMEDG_PLUGIN_URL', str_replace('http', 'https', WP_PLUGIN_URL) . '/' . EASYMEDG_PLUGIN_NAME . '/' );
	} else {
		define( 'EASYMEDG_PLUGIN_URL', WP_PLUGIN_URL . '/' . EASYMEDG_PLUGIN_NAME . '/' );
	}
}	
	
	
$wp_plugin_dir = substr(plugin_dir_path(__FILE__), 0, -1);
define( 'EMG_DIR', $wp_plugin_dir );

global $wp_version;			
if ( version_compare($wp_version, "3.5", "<" ) ) {
	define( 'EMG_WP_VER', "l35" );	
	}
	else {
		define( 'EMG_WP_VER', "g35" );		
	}

require_once( EASYMEDG_PLUGIN_DIR . 'includes/class/easymedia_resizer.php' ); 	

// Plugin Version
if ( !defined( 'EASYMEDIA_VERSION' ) ) {
	define( 'EASYMEDIA_VERSION', '1.2.49' );
}

// Pro Price
if ( !defined( 'EASYMEDIA_PRO_PRICE' ) ) {
	define( 'EASYMEDIA_PRO_PRICE', '24' );
}

// Pro+
if ( !defined( 'EASYMEDIA_PRICE' ) ) {
	define( 'EASYMEDIA_PRICE', '29' );
}

// Pro++ Price
if ( !defined( 'EASYMEDIA_PLUS_PRICE' ) ) {
	define( 'EASYMEDIA_PLUS_PRICE', '35' );
}

// Dev Price
if ( !defined( 'EASYMEDIA_DEV_PRICE' ) ) {
	define( 'EASYMEDIA_DEV_PRICE', '99' );
}


/*
|--------------------------------------------------------------------------
| I18N - LOCALIZATION
|--------------------------------------------------------------------------
*/
load_plugin_textdomain( 'easmedia', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/*
|--------------------------------------------------------------------------
| Load WP jQuery library.
|--------------------------------------------------------------------------
*/
function easmedia_enqueue_scripts() {
	if( !is_admin() )
		{
			wp_enqueue_script( 'jquery' );
			}
}

if ( !is_admin() )
{
  add_action( 'init', 'easmedia_enqueue_scripts' );
}


/*
|--------------------------------------------------------------------------
| SETTINGS LINK
|--------------------------------------------------------------------------
*/
function easmedia_settings_link( $link, $file ) {
	static $this_plugin;
	
	if ( !$this_plugin )
		$this_plugin = plugin_basename( __FILE__ );

	if ( $file == $this_plugin ) {
		$settings_link = '<a href="' . admin_url( 'edit.php?post_type=easymediagallery&page=emg_settings' ) . '">' . __( 'Settings', 'easmedia' ) . '</a>';
		array_unshift( $link, $settings_link );
	}
	
	return $link;
}
add_filter( 'plugin_action_links', 'easmedia_settings_link', 10, 2 );


/*
|--------------------------------------------------------------------------
| Registers custom post type
|--------------------------------------------------------------------------
*/
function easmedia_post_type() {
	$labels = array(
		'name' 				=> _x( 'Easy Media Gallery', 'post type general name' ),
		'singular_name'		=> _x( 'Easy Media Gallery', 'post type singular name' ),
		'add_new' 			=> __( 'Add New Media', 'easmedia' ),
		'add_new_item' 		=> __( 'Easy Media Item', 'easmedia' ),
		'edit_item' 		=> __( 'Edit Media', 'easmedia' ),
		'new_item' 			=> __( 'New Media', 'easmedia' ),
		'view_item' 		=> __( 'View Media', 'easmedia' ),
		'search_items' 		=> __( 'Search Media', 'easmedia' ),
		'not_found' 		=> __( 'No Media Found', 'easmedia' ),
		'not_found_in_trash'=> __( 'No Media Found In Trash', 'easmedia' ),
		'parent_item_colon' => __( 'Parent Media', 'easmedia' ),
		'menu_name'			=> __( 'Easy Media', 'easmedia' )
	);

	$taxonomies = array();
	$supports = array( 'title', 'thumbnail' );
	
	$post_type_args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> __( 'Easy Media', 'easmedia' ),
		'public' 			=> false,
		'show_ui' 			=> true,
		'publicly_queryable'=> true,
		'query_var'			=> true,
		'capability_type' 	=> 'post',
		'has_archive' 		=> false,
		'hierarchical' 		=> false,
		'rewrite' 			=> array( 'slug' => 'easymedia', 'with_front' => false ),
		'supports' 			=> $supports,
		'menu_position' 	=> 20,
		'menu_icon' =>  plugins_url( 'includes/images/easymedia-cp-icon.png' , __FILE__ ),		
		'taxonomies'		=> $taxonomies
	);

	 register_post_type( 'easymediagallery', $post_type_args );
}
add_action( 'init', 'easmedia_post_type' );


/*-------------------------------------------------------------------------------*/
/* Put css file and add Custom Icon for Easy Media Gallery
/*-------------------------------------------------------------------------------*/
function add_my_admin_stylesheet() {
	wp_enqueue_style( 'easmedia_admin_styles', plugins_url('includes/css/admin.css' , __FILE__ ) );
	}
	
add_action( 'admin_print_styles', 'add_my_admin_stylesheet' );


function easmedia_easymediagallery_icons() { ?>
    <style type="text/css" media="screen">
	
	/* Easy Media Gallery */
	/*
        #menu-posts-easymediagallery .wp-menu-image {
            background: url(<?php //echo plugins_url( 'includes/images/easymedia-icon.png' , __FILE__ )?>) no-repeat 7px 6px !important;
        }
		#menu-posts-easymediagallery:hover .wp-menu-image, 
		#menu-posts-easymediagallery.wp-has-current-submenu .wp-menu-image {
            background-position:7px -17px !important;
        }*/
		#icon-edit.icon32-posts-easymediagallery {
		    background: url(<?php echo plugins_url( 'includes/images/easymedia-32x32.png' , __FILE__ )?>) no-repeat top left transparent !important;
		}
		
		#icon-edit.icon32-posts-easymedia {
		    background: url(<?php echo plugins_url( 'includes/images/easymedia-32x32.png' , __FILE__ )?>) no-repeat top left transparent !important;
		}		
		
    </style>
<?php }

add_action( 'admin_head', 'easmedia_easymediagallery_icons' );


/*--------------------------------------------------------------------------------*/
/*  Add Custom Columns for Portfolios 
/*--------------------------------------------------------------------------------*/
add_filter( 'manage_edit-easymediagallery_columns', 'easmedia_edit_columns_easymedia' );
function easmedia_edit_columns_easymedia( $easymedia_columns ){  
	$easymedia_columns = array(  
		'cb' => '<input type="checkbox" />',  
		'title' => _x( 'Title', 'column name', 'easmedia' ),
		'psg_thumbnail' => __( 'Thumbnails', 'easmedia'),
		'psg_type' => __( 'Type', 'easmedia'),		
		'psg_cat' => __( 'Categories', 'easmedia'),
		'psg_id' => __( 'ID', 'easmedia')		
			
	);  
	unset( $columns['Date'] );
	return $easymedia_columns;  
}  

function easmedia_custom_columns_easymedia( $easymedia_columns, $post_id ){  

	switch ( $easymedia_columns ) {
	    case 'psg_thumbnail':
	        	$mediatype = get_post_meta( $post_id, 'easmedia_metabox_media_type', true );
						switch	( $mediatype ) {
								case 'Single Image':
										$thumbmedia = get_post_meta( $post_id, 'easmedia_metabox_img', true );
	       								
										 if ( isset( $thumbmedia ) ) {
											 $globalimgsize = wp_get_attachment_image_src( emg_get_attachment_id_from_src( $thumbmedia ), 'full' );
											 $timthumbimg = easymedia_resizer( $thumbmedia, $globalimgsize[1], $globalimgsize[2], 70, 70, true );
											 echo '<img class="imgthumblist" width="70" height="70" alt="Thumbnail" src="' . $timthumbimg . '"></img>';
											 } 
											 else {
												 echo __( 'None', 'easmedia' );
												 }
												 break;												 
											

								case 'Video':
											 echo '<img class="imgthumblist" width="70" height="70" alt="Thumbnail" src="' . plugins_url( 'images/video.png' , __FILE__ ) . '"></img>';
												 break;			
			
								case 'Audio':
											 echo '<img class="imgthumblist" width="70" height="70" alt="Thumbnail" src="' . plugins_url( 'images/audio.png' , __FILE__ ) . '"></img>';
												 break;		
		
			}
			
	        break;
	    case 'psg_id':
		echo $post_id;

	        break;
			
						
	    case 'psg_type':

 $mediatype = get_post_meta( $post_id, 'easmedia_metabox_media_type', true );

	        if ( isset( $mediatype ) && $mediatype !='Select' ) {
	            echo $mediatype;
	        } else {
	            echo __( 'None', 'easmedia' );
	        }

			break;
								
	    case 'psg_cat':
			$cats = get_the_terms( $post_id, 'emediagallery' );
            if ( is_array( $cats ) ) {
				$item_cats = array();
				foreach ( $cats as $cat ) { $item_cats[] = $cat->name;}
				echo implode( ', ', $item_cats );
			}
			else {echo 'Uncategorized';}
			break;		
	        
		default:
			break;
	}  
}  

add_filter( 'manage_posts_custom_column',  'easmedia_custom_columns_easymedia', 10, 2 );  

// jQuery Auto Save Media Order
function easmedia_save_easymedia_sorted_order() {
    global $wpdb;
    
    $order = explode(',', $_POST['order']);
    $counter = 0;
    
    foreach ( $order as $easymedia_id ) {
        $wpdb->update( $wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $easymedia_id ) );
        $counter++;
    }
    die(1);
}


add_action( 'wp_ajax_easymedia_sort', 'easmedia_save_easymedia_sorted_order' );

function easmedia_print_sort_scripts() {
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'easmedia_easymedia_sort', plugins_url('includes/js/func/easymedia_sort.js' , __FILE__ ) );
}

function easmedia_print_sort_styles() {
    wp_enqueue_style( 'nav-menu' );
}


/*-------------------------------------------------------------------------------*/
/*  Add The Custom Columns ( Thanks & Credit to Captain Slider Plugin ) 
/*-------------------------------------------------------------------------------*/
function easmedia_add_new_easymediagallery_column( $easmedia_col ) {
	$easmedia_col['emg_menu_order'] = "Order";
	return $easmedia_col;
}
add_action( 'manage_edit-easymediagallery_columns', 'easmedia_add_new_easymediagallery_column' );


// Show Custom Order Values
function easmedia_show_order_column( $name ) {
	global $post;

	switch ( $name ) {
		case 'emg_menu_order':
			$order = $post->menu_order;
			echo $order;
		break;
	 default:
		break;
	 }
}
add_action( 'manage_easymediagallery_posts_custom_column','easmedia_show_order_column' );


// Make It Sortable
function easmedia_order_column_register_sortable( $columns ) {
	$columns['emg_menu_order'] = 'menu_order';
	return $columns;
}
add_filter( 'manage_edit-easymediagallery_sortable_columns','easmedia_order_column_register_sortable' );


// Presets Media Order to be menu_order
function easmedia_set_custom_post_types_admin_order( $wp_query ) {
	if ( is_admin() ) {
		// Get the post type from the query
		$post_type = $wp_query->query['post_type'];
		// if it's one of our custom ones
		if ( $post_type == 'easymediagallery' ) {
			$wp_query->set( 'orderby', 'menu_order' );
			$wp_query->set( 'order', 'ASC' );
		}
	}
}
add_filter( 'pre_get_posts', 'easmedia_set_custom_post_types_admin_order' );


/*-------------------------------------------------------------------------------*/
/*   Hide View, Quick Edit and Preview Button
/*-------------------------------------------------------------------------------*/
function emg_hide_post_view() {
    global $post_type;
    $post_types = array(
                        'easymediagallery'
                  );
    if(in_array($post_type, $post_types))
    echo '<style type="text/css">#post-preview, #view-post-btn{display: none;}</style>';
}

add_action( 'admin_head-post-new.php', 'emg_hide_post_view' );
add_action( 'admin_head-post.php', 'emg_hide_post_view' );
add_filter( 'post_row_actions', 'emg_remove_row_actions', 10, 1 ); // <--- comment this to show post quick edit.

function emg_remove_row_actions( $actions )
{
    if( get_post_type() === 'easymediagallery' )
        unset( $actions['view'] );
    return $actions;
}


/*-------------------------------------------------------------------------------*/
/*   Executing shortcode inside the_excerpt() and sidebar/widget
/*-------------------------------------------------------------------------------*/
add_filter('widget_text', 'do_shortcode', 11); // <--- comment this to disable media in widget.
add_filter( 'the_excerpt', 'shortcode_unautop');
add_filter( 'the_excerpt', 'do_shortcode');  


/*
|--------------------------------------------------------------------------
| RENAME SUBMENU
|--------------------------------------------------------------------------
*/
function emg_rename_submenu() {  
    global $submenu;     
	$submenu['edit.php?post_type=easymediagallery'][5][0] = __( 'Overview', 'easmedia' );  
}  
add_action( 'admin_menu', 'emg_rename_submenu' );  


/*-------------------------------------------------------------------------------*/
/*   Load Plugin Functions
/*-------------------------------------------------------------------------------*/
include_once( EASYMEDG_PLUGIN_DIR . 'includes/functions/functions.php' );


/*
|--------------------------------------------------------------------------
| CHECK PLUGIN DEFAULT SETTINGS
|--------------------------------------------------------------------------
*/

function emg_plugin_activate() {

  add_option( 'Activated_Emg_Plugin', 'emg-activate' );

}
register_activation_hook( __FILE__, 'emg_plugin_activate' );

function emg_load_plugin() {

    if ( is_admin() && get_option( 'Activated_Emg_Plugin' ) == 'emg-activate' ) {
		
		$emg_optval = get_option( 'easy_media_opt' );
		
		if ( !is_array( $emg_optval ) ) update_option( 'easy_media_opt', array() );		
		
		$tmp = get_option( 'easy_media_opt' );
		if ( isset( $tmp['easymedia_deff_init'] ) != '1' ) {
			easymedia_1st_config();
			}

        delete_option( 'Activated_Emg_Plugin' );
		wp_redirect("edit.php?post_type=easymediagallery&page=comparison");
    }
}
add_action( 'admin_init', 'emg_load_plugin' );



?>