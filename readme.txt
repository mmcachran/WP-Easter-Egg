=== WP Easter Egg ===
Contributors: mmcachran
Tags: easteregg, konami, cornify, raptorize, 
Requires at least: 3.8
Tested up to: 4.1
Stable tag: trunk

Easily add Easter Eggs to your WordPress site using the Konami code or a custom key sequence.

== Description ==

Currently includes options for:

* Cornify (cornify.com)
* Raptorize (zurb.com/playground/jquery-raptorize)
* Rotate the user's screen
* Move an image across the user's screen (bottom, middle, or top)
* Fire user defined JS

The filter settings can be configured in the plugin's settings and will either be inclusive or exclusive.  If the filter is exclusive, the easter egg will not available on posts where the "Add to filter?" meta checkbox is checked.  If the filter is inclusive, the easter egg will not available on posts where the "Add to filter?" meta checkbox is not checked.

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Usage ==

After activation, you should see a new option in your "Settings" menu for "WP Easter Egg" where you can configure this plugin's settings.

== Changelog ==

= 0.7.0 =
* Add new options for moving image across a user's screen

= 0.6.0 =
* Cleanup filtering option

= 0.5.0 =
* Add option to turn filter off

= 0.4.0 =
* Adding inclusive/exclusive filter options for posts

= 0.3.0 =
* Add option for raptorize JS

= 0.2.0 =
* Add support for media library on settings page
* Add option to move image across screen