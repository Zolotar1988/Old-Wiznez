<?php
/*
Plugin Name: Tabbed Sidebar Widgets
Description: Adds a magic sidebar which you can fill with widgets and a widget that displays the magic sidebar's contents as tabs. Sounds more complicated than it really is!
Author: Nevma
Version: 1.1.2
Author URI: http://www.nevma.gr
*/

function widget_tabs_init() {
    
    // This registers the magic sidebar, just the way it should be
	if ( function_exists('register_sidebar')) {
        register_sidebar(array(
            'name' => 'Magic Sidebar',
            'id' => 'nevma-magic-sidebar',
            'before_widget' => '<li id="%1$s" class="%2$s tab-content">',
            'after_widget' => '</li>',
            'before_title' => '<h2 class = "tab-title">',
            'after_title' => '</h2>',));
    }
    
	// Check for the required plugin functions. This will prevent fatal errors occurring when you deactivate the dynamic-sidebar plugin.
	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs the data.
	function widget_tabs($args) {
		
		// $args is an array of strings that help widgets to conform to the active theme: before_widget, before_title, after_widget and after_title are the array keys. Default tags: li and h2.
		extract($args);

		// We keep them option strings here (although none exist in this version of the plugin).
		$options = get_option('widget_tabs');
		
		// These lines generate our output.
		echo $before_widget;
				
		    echo $before_title . $title . $after_title;
		    
		    echo '<ul class = "tab-container">';
		    
		        dynamic_sidebar ('Magic Sidebar');
		    
		    echo '</ul>';
	    
	    echo $after_widget;

	}
	
	// This registers our widget so it appears with the other available widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Tabbed Sidebar Widgets', 'widgets'), 'widget_tabs');
	
	// Check if jQuery is queued and queue it if not
	if ( !wp_script_is( 'jquery', 'queue' ) ) wp_enqueue_script( 'jquery' );

    // This includes our JS files and loads them in the head section of each page. They manipulate the widget's HTML structure.
    wp_enqueue_script('nevma_sidebar_tabs_js', '/wp-content/plugins/tabbed-sidebar-widgets/nevma-sidebar-tabs.js', array ( 'jquery' ) );

    // This includes our CSS files and loads them in the head section of each page.
    // If a custom stylesheet for the widget exists in the active theme, it takes priority over the bundled one
    if ( file_exists ( get_stylesheet_directory() . '/tabbed-sidebar-widgets/widget.css' ) ) { // Child Theme (or just theme)
		
		wp_enqueue_style ( 'nevma_sidebar_tabs', get_stylesheet_directory_uri() . '/tabbed-sidebar-widgets/widget.css' );
	
	} elseif ( file_exists ( get_template_directory() . '/tabbed-sidebar-widgets/widget.css') ) { // Parent Theme (if parent exists)
	
		wp_enqueue_style ( 'nevma_sidebar_tabs', get_template_directory_uri() . '/tabbed-sidebar-widgets/widget.css' );
	
	} else { //Default file in plugin folder
	
        wp_enqueue_style ('nevma_sidebar_tabs', '/wp-content/plugins/tabbed-sidebar-widgets/nevma-sidebar-tabs.css');

	}

}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_tabs_init');

?>