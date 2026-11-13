# Changelog

## 3.0.0 (2026-11-13)

### Major Changes

* **Refactored architecture**: Abstracted logic into separate classes (Plugin, Options, Meta_Box, Frontend)
* **PSR-4 autoloading**: Added Composer autoloading for all PHP classes
* **Security improvements**: Added input sanitization for all settings, nonce verification for meta box saves
* **JavaScript modernization**: Removed `eval()`, replaced with Function constructor; modernized to ES6+
* **Fixed hardcoded paths**: Raptorize assets now use configurable plugin URL

### Testing

* Added PHPUnit test suite (40+ test cases)
* Added Jest JavaScript unit tests
* Added Cypress e2e tests for user workflows
* Added GitHub Actions CI/CD pipeline

### Requirements

* WordPress: 5.0+ (was 3.8)
* PHP: 7.4+ (was 5.2)

## 2.0.6

* Update keycode checker to be more specific and avoid fuzzy matches

## 2.0.5

* Allow backspace in keycode helper

## 2.0.4

* Cleanup
* Fix inclusive/exclusive filter

## 2.0.3

* Update filter description

## 2.0.2

* Add filter to allow on more than on post type (default: post and page)

## 2.0.1

* Cleanup admin JS

## 2.0.0

* Cleanup JS and filter logic
* Remove console logs

## 1.0.7

* Fix JS error in media library on settings page
* Add version when enqueueing scripts

## 1.0.6

* Text domain fix

## 1.0.5

* Cleanup

## 1.0.4

* Fix raptorize.js

## 1.0.3

* Restructure plugin and code cleanup

## 1.0.2

* Remove image width/height in JS (allow images to be shown in the size uploaded)

## 1.0.1

* Cleanup

## 1.0.0

* Add JS keyCode helper for custom codes

## 0.9.0

* Fix logic in filter

## 0.8.0

* Fix css for image scrolling across user's screen

## 0.7.0

* Add new options for moving image across a user's screen

## 0.6.0

* Cleanup filtering option

## 0.5.0

* Add option to turn filter off

## 0.4.0

* Adding inclusive/exclusive filter options for posts

## 0.3.0

* Add option for raptorize JS

## 0.2.0

* Add support for media library on settings page
* Add option to move image across screen
