=== Plugin Name ===
Contributors: EkAndreas
Tags: agile, tools, whiteboard, project management, sticky notes, kanban, scrum
Requires at least: 3.4
Tested up to: 3.6
Stable tag: 1.6.1

FlowBoard makes it easier for web development to visualize the agile process.

== Description ==

Web development tool, agile whiteboard with sticky notes.

FlowBoard makes it easier for web development to visualize the agile process.
Eg. Use it with Scrum or Kanban projects!

Benefits: Make your development process more transparent!

To see the plugin in action visit the [Development and Demo Site](http://plugins.flowcom.se/flowboard)!

Every note is saved as a custom post type and editable in wp-admin. The FlowBoard data is stored under the note posts metadata (custom fields).


== Installation ==

This section describes how to install the plugin and get it working.

1. Download the plugin
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a new board in wp-admin/Flowboard/Boards (just title is enough)
4. Open a post or a page and click the "note"-button to add a board to your page!

In the admin settings menu you can change the board background image for your purposes.

== Frequently Asked Questions ==

= Does it fit into my theme? =
Yes, you can choose your own background in the admin settings. The whiteboard takes full width.

= Have ideas? =
Please post ideas at the plugin site http://plugins.flowcom.se/flowboard

== Screenshots ==

1. Example of a FlowBoard with notes.
2. Edit the board with configurable zones.

== Changelog ==

= 1.6.1 =
* Columns for Notes with board and status added
* Function to move, delete and detach notes/posts in one board as batch.

= 1.6 =
* Admin menu moved
* Flush rewrite rules made in plugin activate/deactivate
* Removed some error_log messages

= 1.5.5 =
* Flush rewrite rules after new registration of post types

= 1.5.4 =
* Added a readmore link in the note if selected

= 1.5.3 =
* Touch-Pounch added, support for ipad/iphone

= 1.5.2 =
* Post date fixed thanks to "innerbot".
* Post content editable in popup (no html), thanks to "vlood".
* Some removal of fonts in css

= 1.5.1 =
* New note didn't show until refresh, fixed.
* New note on top, fixed.

= 1.5 =
* Added zones to board

= 1.4 =
* Added Board as an entity / custom post type (preparing for properties, burndown, etc)
* Metabox added to Notes
* Cascade postition at new note

= 1.3.1 =
* Quick fix, estimate and timeleft switched in edit note
* Admin url fixed in edit post from edit note

= 1.3 =
* Button added in HTML-editor
* Thickbox instead of jQuery-UI dialog
* 100% content background image
* Custom post type flowboard_note for posts in boards
* Default note author name from profile in WP
* Changed name from HyperBoard to FlowBoard

= 1.2 =
Import posts by ID
Bugfixes

= 1.1 =
Public Access checkbox in the settings to override post edit access check.

= 1.0.1 =
Bugfixes

= 1.0 =
Settings menu added and cleaner view.

= 0.9 =
Updated ajax urls, absolute.

= 0.8.1 =
Bugfixes.

= 0.8 =
Hidden custom metakey and bugfixes.

= 0.7 =
Bugfix and colors

= 0.6 =
Notes as posts

= 0.5 =
Initial

== Upgrade Notice ==

1.3 to 1.4, You will have to recreate the board and reset all Note posts to the right board ID.

