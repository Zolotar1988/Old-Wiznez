=== JSON-API-for-BuddyPress ===

Tags: json, api, buddypress
Stable Tag: 1.0.2
Contributors: tweichart, andrewk
Requires at least: 3.0.1
Tested up to: 3.8
Donate link: http://tweichart.github.com/JSON-API-for-BuddyPress/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extends the JSON API to be used with Buddypress


==Description==

JSON API for BuddyPress is a plugin, that supports the JSON API Plugin with a new Controller to get information from BuddyPress.

For further information refer to the [GitHub Project Page](http://tweichart.github.com/JSON-API-for-BuddyPress)

==Installation==
First you have to install the [JSON API for WordPress Plugin](http://wordpress.org/extend/plugins/json-api/installation/).

To install JSON API for BuddyPress just follow these steps:

* upload the folder "json-api-for-buddypress" to your WordPress plugin folder (/wp-content/plugins)
* activate the plugin through the 'Plugins' menu in WordPress or by using the link provided by the plugin installer
* activate the controller through the JSON API menu found in the WordPress admin center (Settings -> JSON API)



==Changelog==

=1.0.2=

* added possibility to get avatars and user meta data from profile_get_profile

=1.0.1=

* added support for de_DE
* added support for es_ES (all credits to the spanish translation to Andrew Kurtis from [webhostinghub.com](http://www.webhostinghub.com))
* changed folder structure

=1.0=

* code review
* added documentation

=0.9=

* now checking for permissions before retrieving data (security)
* need for authentication pushed to 2.0

=0.8=

* extended functionality for settings

=0.7=

* extended functionality for forums

=0.6=

* extended functionality for groups

=0.5=

* extended functionality for friends

=0.4=

* extended functionality for messages / notifications
* reworked the framework

=0.3=

* extended functionality for profile
* new parameter 'limit' for get_activity
* including error handler function

=0.2=

* extended functionality for activity

=0.1=

* initial commit

==Frequently Asked Questions==
For a full code documentation go to the [GitHub code documentation](http://tweichart.github.com/JSON-API-for-BuddyPress/doc/index.html)