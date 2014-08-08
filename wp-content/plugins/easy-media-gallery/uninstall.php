<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

// Remove plugin options from wordpress database.
function spg_clean_data() {
	
	    $easymedia_values = get_option( 'easy_media_opt' );
		if ( is_array( $easymedia_values ) && array_key_exists( $name, $easymedia_values ) ) $keepornot = $easymedia_values['easymedia_disen_databk'];
	
	if ( $keepornot != '1' ) {	
	
	delete_option( 'easy_media_opt' );

// Remove plugin-specific custom post type entries.	
		$posts = get_posts( array(
		'numberposts' => -1,
		'post_type' => 'easymediagallery',
		'post_status' => 'any' ) );

			foreach ( $posts as $post )
				wp_delete_post( $post->ID, true );
	
// Remove plugin-specific custom taxonomies and terms ( not work ).

		$tax = 'emediagallery' ;
			if( is_taxonomy( $tax ) ) {
				foreach ( $tax as $taxonomy ) {
					$terms = get_terms( $taxonomy, array( 'get ' => 'all' ) );
					foreach ( $terms as $term ) {
						wp_delete_term( $term->term_id, $taxonomy );}
						unset( $wp_taxonomies[$taxonomy] );
						}
			}
	}
	else {
		
	}			
}

spg_clean_data();

?>