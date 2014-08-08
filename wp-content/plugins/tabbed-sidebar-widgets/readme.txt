=== Plugin Name ===
Contributors: nevma
Tags: sidebar, widget, tabs
Requires at least: 2.9.2
Tested up to: 3.4.2
Stable tag: trunk

Pack multiple sidebar widgets into one convenient tabbed container.

== Description ==

This plugin adds a magic sidebar which you can fill with widgets and a widget that displays the magic sidebar's contents as tabs. Sounds more complicated than it really is!

== Installation ==

1. Download the plugin and unzip.
2. Put the tabbed-sidebar-widgets folder in your wp-content/plugins directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Fill the Magic Sidebar with some widgets and drag the Tabbed Sidebar Widgets widget to your sidebar.

== Frequently Asked Questions ==

= What about styling? =

There's really only a minimum of styling included "out-of-the-box". You can style the tabs by adding your own stylesheet and images in your theme directory. Like so:

1. Create a directory called "tabbed-sidebar-widgets" in your theme's root folder. (eg: /wp-content/themes/mytheme/tabbed-sidebar-widgets)
2. Add a css file named "widget.css" to the above directory and style away!

There's no graphic style editor at this moment. 

== Screenshots ==

1. The plugin creates a Magic Sidebar and a widget. Use them as you wish.

== Changelog ==

= 1.1.2 =
Fixed another jQuery related bug that made tabs show as a continuous list.

= 1.1.1 =
Fixed all jQuery related issues and now load it if it has not been loaded by someone else.

= 1.1 =
Added the option to include a custom stylesheet in the current theme folder.

= 1.0.1 =
Fixed a bug where not all scripts would be enqueued properly.

= 1.0 =
First version to get things going.