=== Mass Messaging in Buddypress ===
Contributors: ElbowRobo
Donate link: http://www.stormation.info/portfolio-item/mass-messaging-in-buddypress/
Tags: mass, messaging, in, buddypress
Requires at least: 3.0.0
Tested up to: 3.9
Stable tag: 1.2.0

Ever wanted to send a message to many people at once? Now you can, introducing - Mass Messaging.

== Description ==

This plugin is for BuddyPress, it adds a dashboard menu and a tab in the messages section. Once you navigate into the messages section and click the "Mass Messaging" tab you have access to all the options which you chose in the dashboard.

Including mass messaging to:

* Members
* Members of Groups
* Members of Blogs (Sites)

And:

* Select all buttons to allow mass messaging to all members easily.

In this page you also see 'subject' and 'description' just like on the Buddypress compose page.

== Installation ==

1. Upload the folder `Mass Messaging in Buddypress` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Change the settings in the dashboard
1. Navigate through to the messages section in the frontend to find the mass messaging page

== Frequently Asked Questions ==

= It's not loading, what's wrong? =

Firstly, this is only for Wordpress (Network too!) running Buddypress. Check you have these installed correctly. Then, check you have activated the plugin.

The best way to get issues resolved is to head to the [plugin page](http://www.stormation.info/portfolio-item/mass-messaging-in-buddypress/ "Stormation.info official page for Mass Messaging in Buddypress"), and comment!

= All groups (override) is only showing public groups, can I change this? =

Yes, as of 1.1.4 you can change line 8 to read `define('MassMessagingGroupsOverrideShowOnlyPublic', 'false');` which will in turn show ALL groups not just those that are public.

= All blogs (override) is only showing public blogs, can I change this? =

Yes, as of 1.1.4 you can change line 9 to read `define('MassMessagingBlogsOverrideShowOnlyPublic', 'false');` which will in turn show ALL blogs not just those that are public.

== Screenshots ==

1. The tabbed location of the mass messaging front end.
2. The tabs and display once in the mass messaging page.
3. The admin settings area.

== Changelog ==

= 1.2.0 =
* Support for WP 3.6.1 and BP 1.8.1
* Fix for some rare link issues

= 1.1.5 =
* Support for WP 3.6.1 and BP 1.8.1
* Security fix for external images

= 1.1.4 =
* Added override options for selecting all regardless of friends or membership

= 1.1.3 =
* Whitespace error / headers bug fix

= 1.1.2 =
* Image definition changes

= 1.1.1 =
* Function definition changes

= 1.1.0 =
* Backend design changes

= 1.0.7 =
* Bug fix for select all boxes
* Readme restructure

= 1.0.6 =
* Bug fix for loading issues

= 1.0.5 =
* Bug fix for translation issues

= 1.0.4 =
* Added support for wordpress toolbar
* Bug fix for form syntax issues

= 1.0.3 =
* Added support for minimum role type required to send mass messages

= 1.0.2 =
* Bug fix for url references

= 1.0.1 =
* Bug fix for user login and name references
* Bug fix for incorrect template location

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.2.0 =
Added support for WordPress 3.9.0 and BuddyPress 2.0.0

= 1.1.5 =
Added support for WordPress 3.6.1 and BuddyPress 1.8.1

= 1.1.4 =
Just further developments in showing all members / groups / blogs.

= 1.1.3 =
Highly recommended, error bug fix.

= 1.1.2 =
Image definition changes for improved usability.

= 1.1.1 =
Function definition changes for improved compatibility.

= 1.1.0 =
Just backend design changes.

= 1.0.7 =
A bug fix for select all boxes.

= 1.0.6 =
Support added to more BuddyPress systems.

= 1.0.5 =
A bug fix for a translation issue.

= 1.0.4 =
Support for the wordpress toolbar added and fixed a bug with the form syntax.

= 1.0.3 =
A new option is added into the settings requiring a minimum role to access the page.

= 1.0.2 =
A slight change has been made with url references, it will work on more systems.

= 1.0.1 =
A slight change is made to the references of login names and template locations.

= 1.0 =
Initial Release

== Donations ==

If you want to support the plugin head over to: [Stormation](http://www.stormation.info/portfolio-item/mass-messaging-in-buddypress/ "Stormation.info official page for Mass Messaging in Buddypress") where you can comment about the plugin.