=== WP Easter Egg ===
Contributors: mmcachran
Tags: easteregg, konami, cornify, raptorize, 
Requires at least: 3.8
Tested up to: 4.5.3
Stable tag: trunk

Easily add Easter Eggs to your WordPress site using the Konami code or a custom key sequence.

== Description ==

Currently includes options for:

* Cornify (cornify.com)
* Raptorize (zurb.com/playground/jquery-raptorize)
* Rotate the user's screen
* Move an image across the user's screen (bottom, middle, or top)
* Fire custom JS

If you have the "Activate keycode helper" checkbox checked, the custom keycode will fill based on the JS keyCode.  

The filter settings can be configured in the plugin's settings and will either be inclusive or exclusive.  If the filter is exclusive, the easter egg will not available on posts where the "Add to filter?" meta checkbox is checked.  If the filter is inclusive, the easter egg will not available on posts where the "Add to filter?" meta checkbox is not checked.

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Usage ==

After activation, you should see a new option in your "Settings" menu for "WP Easter Egg" where you can configure this plugin's settings.

== Changelog ==

= 2.0.6 =
* Update keycode checker to be more specific and avoid fuzzy matches

= 2.0.5 =
* Allow backspace in keycode helper

= 2.0.4 =
* Cleanup
* Fix inclusive/exclusive filter

= 2.0.3 =
* Update filter description

= 2.0.2 =
* Add filter to allow on more than on post type (default: post and page)

= 2.0.1 =
* Cleanup admin JS

= 2.0.0 =
* Cleanup JS and filter logic
* Remove console logs

= 1.0.7 =
* Fix JS error in media library on settings page
* Add version when enqueueing scripts

= 1.0.6 =
* Text domain fix

= 1.0.5 =
* Cleanup

= 1.0.4 =
* Fix raptorize.js

= 1.0.3 =
* Restructure plugin and code cleanup

= 1.0.2 =
* Remove image width/height in JS (allow images to be shown in the size uploaded)

= 1.0.1 =
* Cleanup

= 1.0.0 =
* Add JS keyCode helper for custom codes

= 0.9.0 =
* Fix logic in filter

= 0.8.0 =
* Fix css for image scrolling across user's screen

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
