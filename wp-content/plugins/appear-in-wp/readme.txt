=== appear.in WP ===

Contributors: UaMV
Donate link: http://vandercar.net/wp
Tags: appear, in, video, chat, conference, webrtc, teleconference
Requires at least: 3.1
Tested up to: 3.8
Stable tag: 1.6
License: GPLv2 or later

Adds appear.in rooms to your site via shortcode

== Description ==

Harness the power of [appear.in](http://appear.in "appear.in") by embedding secure peer-to-peer video chat rooms on a self-hosted WordPress site via the [appear_in] shortcode.

= Shortcode =

> **[appear_in]**<br /><br />
> **[appear_in room="_custom-public-room-name_"]**<br />
> **[appear_in type="_public,private,post_"]**<br />
> **[appear_in public_invites="_0-7_" private_invites="_0-7_" post_invites="_0-7_" ]**<br />
> **[appear_in public_room_button="" private_room_button="" post_room_button=""]**<br />
> **[appear_in public_invite_button="" private_invite_button="" post_invite_button=""]**

= Settings =

Custom settings for appear.in Wordpress are found on the Settings > Media admin page.

* **Public Room Name:** define a public room name for default use in shortcode
* **Enable/Disable email invitations upon entering room:** 0-7 invitations allowed

The settings page includes the following basic usage stats per room type:

* Number of rooms triggered
* Number of invites sent
* Number & percentage of invites accepted
* Average number of participants per room

If a public room name has not been explicitly defined in settings or shortcode, then the default public room expires daily.

The 'post' room type will generate a public room with name of the current post.

= Documentation =

Documentation and sample implementation can also be found [here](http://vandercar.net/wp "appear.in WordPress Documentation").

Learn more about [appear.in](http://appear.in "appear.in") - a product of [Telenor Digital AS](http://www.telenor.com/ "Telenor Digital") built with WebRTC technologies.

_Note: As of 2.6.2014, the appear.in API is still in beta. You may encounter minor bugs with your rooms._

= Functions =

The following function can be used to include rooms:

`aiwp_include( $args );`

Default arguments:

`$args = array(
	'room'                  => '',
	'type'                  => 'public',
	'public_room_button'    => 'Public Room',
	'public_invite_button'  => 'Send Invitations & Enter Public Room',
	'public_invites'        => NULL,
	'private_room_button'   => 'Private Room',
	'private_invite_button' => 'Send Invitations & Enter Private Room',
	'private_invites'       => NULL,
	'post_room_button'      => 'Post Room',
	'post_invite_button'    => 'Send Invitations & Enter Post Room',
	'post_invites'          => NULL,
);`

= Filters =

`aiwp_unsupported_browser_message
aiwp_room_button
aiwp_invite_button
aiwp_invitation_subject
aiwp_invitation_message`

Each of the last four filters accept a second variable of room type _(public, private, post)_.


== Installation ==

1. Upload the `appear-in-wordpress` directory to `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

Silence is golden.

== Screenshots ==

1. appear.in WordPress Settings

== Changelog ==

= 1.6 =
* Fixed various PHP notices

= 1.5 =
* Added display of local invitation URL
* Updated side notice class
* Readme edits

= 1.4 =
* Modified & Refined filters
* Added shortcode parameters for button text

= 1.3 =
* Refined filters
* Added room type parameter to shortcode (defaults to public)
* Removed allowed types from options
* Added number of allowable invites parameter to shortcode
* Added room type 'post'
* Added 'aiwp_include' function for use in themes
* Fixed call to included files

= 1.2 =
* Minor readme edit to show correct version
* Removed function used during development

= 1.1 =
* Adding repository images

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.6 =
Fixed various PHP notices

= 1.5 =
Added display of local invitation URL + Updated side notice class

= 1.4 =
Modified & Refined filters + Added shortcode parameters for button text

= 1.3 =
* Refined filters
* Added room type parameter to shortcode (defaults to public)
* Removed allowed types from options
* Added number of allowable invites parameter to shortcode
* Added room type 'post'
* Added 'aiwp_include' function for use in themes
* Fixed call to included files

= 1.2 =
* Minor readme edit to show correct version
* Removed function used during development

= 1.0 =
* Initial Release