=== WP Easter Egg ===
Contributors: mmcachran, topta.co
Tags: easteregg, konami, cornify, raptorize, fun
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 3.4.1
Requires PHP: 7.4

## Description

Easily add Easter Eggs to your WordPress site using the Konami code or a custom key sequence.

Currently includes options for:

* Cornify (cornify.com)
* Raptorize (zurb.com/playground/jquery-raptorize)
* Rotate the user's screen
* Move an image across the user's screen (bottom, middle, or top)
* Fire custom JS

If you have the "Activate keycode helper" checkbox checked, the custom keycode will fill based on the JS keyCode.

The filter settings can be configured in the plugin's settings and will either be inclusive or exclusive. If the filter is exclusive, the Easter Egg will not be available on posts where the "Add to filter?" meta checkbox is checked. If the filter is inclusive, the Easter Egg will not be available on posts where the "Add to filter?" meta checkbox is not checked.

## Installation

1. Extract the zip file and drop the contents in the `wp-content/plugins/` directory of your WordPress installation.
2. Activate the plugin from the Plugins page in WordPress admin.
3. Navigate to Settings > WP Easter Egg to configure your Easter Egg options.

## Usage

After activation, you will see a new option in your "Settings" menu for "WP Easter Egg" where you can configure this plugin's settings.

## Available Actions

| Action | Description |
|--------|-------------|
| Rotate Screen | Continuously rotates the page at random angles |
| Cornify | Adds unicorns and rainbows via cornify.com |
| Raptorize | A raptor appears and screams across the screen |
| Move Image Across Bottom | Moves a custom image across the bottom of the screen |
| Move Image Across Middle | Moves a custom image across the middle of the screen |
| Move Image Across Top | Moves a custom image across the top of the screen |
| Custom JS | Executes custom JavaScript code (use with caution) |

## Key Sequences

* **Konami Code**: Up, Up, Down, Down, Left, Right, Left, Right, B, A
* **Custom Code**: Define your own key sequence using JavaScript keyCodes

## Filter System

The filter system allows you to control which posts display the Easter Egg:

* **Off**: Easter Egg appears on all posts and pages
* **Exclusive**: Easter Egg appears everywhere except posts marked "Add to filter?"
* **Inclusive**: Easter Egg appears only on posts marked "Add to filter?"

## Screenshots

| # | Description | Image |
|---|-------------|-------|
| 1 | WP Easter Egg settings page | `screenshots/settings-page.png` |
| 2 | Meta box on post edit screen | `screenshots/meta-box.png` |
| 3 | Easter Egg in action on frontend | `screenshots/frontend-action.png` |

## Demo Videos

| # | Description | Video |
|---|-------------|-------|
| 1 | Admin configuration walkthrough | `videos/admin-config.mp4` |
| 2 | Konami code Easter Egg trigger | `videos/konami-trigger.mp4` |
| 3 | Filter system demo | `videos/filter-system.mp4` |

> **Note:** Screenshot and video files are not included in the repository. Capture them from a running WordPress installation with the plugin activated and place them in the `screenshots/` and `videos/` directories respectively.

## Changelog

### 3.4.1
* Bump version to 3.4.1
* Fix PHP 8.5 deprecation warnings in test suite (removed deprecated setAccessible calls)
* Update test assertions for version 3.4.1

### 3.4.0
* Bump version to 3.4.0
* Add topta.co author byline
* Enhance Composer autoloader optimization (classmap-authoritative, prefer-dist)
* Add WordPress installer paths and wp-cli scripts
* Add roave/security-advisories for vulnerability scanning
* Update .gitignore to exclude vendor, build, and cache directories

### 3.1.0
* Bump minor version to 3.1.0
* Fix GitHub Actions CI/CD pipeline issues
* Update test assertions for new version

### 3.0.0
* Major refactor: abstracted logic into separate classes (Plugin, Options, Meta_Box, Frontend)
* Added PSR-4 autoloading via Composer
* Added input sanitization for all settings
* Added nonce verification for meta box saves
* Removed eval() from JavaScript, replaced with Function constructor
* Modernized JavaScript to ES6+ standards
* Fixed hardcoded paths in raptorize.js
* Added PHPUnit test suite with 40+ test cases
* Added Jest JavaScript unit tests
* Added Cypress e2e tests for user workflows
* Added GitHub Actions CI/CD pipeline
* Updated WordPress compatibility to 6.5
* Updated PHP requirement to 7.4+
* Added comprehensive documentation and wiki

### 2.0.6
* Update keycode checker to be more specific and avoid fuzzy matches

### 2.0.5
* Allow backspace in keycode helper

### 2.0.4
* Cleanup
* Fix inclusive/exclusive filter

### 2.0.3
* Update filter description

### 2.0.2
* Add filter to allow on more than on post type (default: post and page)

### 2.0.1
* Cleanup admin JS

### 2.0.0
* Cleanup JS and filter logic
* Remove console logs

### 1.0.7
* Fix JS error in media library on settings page
* Add version when enqueueing scripts

### 1.0.6
* Text domain fix

### 1.0.5
* Cleanup

### 1.0.4
* Fix raptorize.js

### 1.0.3
* Restructure plugin and code cleanup

### 1.0.2
* Remove image width/height in JS (allow images to be shown in the size uploaded)

### 1.0.1
* Cleanup

### 1.0.0
* Add JS keyCode helper for custom codes

### 0.9.0
* Fix logic in filter

### 0.8.0
* Fix css for image scrolling across user's screen

### 0.7.0
* Add new options for moving image across a user's screen

### 0.6.0
* Cleanup filtering option

### 0.5.0
* Add option to turn filter off

### 0.4.0
* Adding inclusive/exclusive filter options for posts

### 0.3.0
* Add option for raptorize JS

### 0.2.0
* Add support for media library on settings page
* Add option to move image across screen

## Upgrade Notice

### 3.0.0
This is a major release with significant refactoring. Please review the changelog and test on a staging site before deploying to production. The plugin now requires PHP 7.4+ and WordPress 5.0+.

## Frequently Asked Questions

### Can I use custom JavaScript?
Yes, select "Custom JS" as the action and enter your JavaScript code. Warning: this could be dangerous to your site if you enter malicious code.

### How does the filter work?
The filter controls which posts display the Easter Egg. In exclusive mode, the egg appears everywhere except filtered posts. In inclusive mode, the egg only appears on filtered posts.

### What key codes should I use for custom sequences?
Common key codes: 38=Up, 40=Down, 37=Left, 39=Right, 66=B, 65=A, 13=Enter, 32=Space. The plugin includes a keycode helper in the admin settings.

## Support

For support, please create an issue on the GitHub repository or check the wiki for documentation.

## License

This plugin is released under the MIT license.
