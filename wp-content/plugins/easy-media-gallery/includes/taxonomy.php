<?php

/*-----------------------------------------------------------------------------------
/*	Register Taxonomy
/*---------------------------------------------------------------------------------*/
function easmedia_register_emg_tax() {
	$labels = array(
		'name' 					=> _x( 'Easy Media Gallery', 'taxonomy general name' ),
		'singular_name' 		=> _x( 'Easy Media Gallery', 'taxonomy singular name' ),
		'add_new' 				=> _x( 'Add New Category', 'easmedia'),
		'add_new_item' 			=> __( 'Add New Category' ),
		'edit_item' 			=> __( 'Edit Category' ),
		'new_item' 				=> __( 'New Category' ),
		'view_item' 			=> __( 'View Category' ),
		'search_items' 			=> __( 'Search Category' ),
		'not_found' 			=> __( 'No Category found' ),
		'not_found_in_trash' 	=> __( 'No Category found in Trash' ),
		'menu_name'				=> __( 'Categories' )
	);
	
	$pages = array( 'easymediagallery' );
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> __( 'Emediagallery', 'easmedia' ),
		'public' 			=> true,
		'show_ui' 			=> true,
		'query_var'			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> false,
		'show_in_nav_menus' => false,
		'rewrite' 			=> array( 'slug' => 'easy-media', 'with_front' => false ),
	 );
	register_taxonomy( 'emediagallery', $pages, $args );
}
add_action( 'init', 'easmedia_register_emg_tax' );


?>