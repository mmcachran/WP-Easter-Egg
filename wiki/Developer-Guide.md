# Developer Guide

This guide is for developers who want to extend or contribute to WP Easter Egg.

## Architecture

WP Easter Egg follows a modular, component-based architecture:

```
wp-easter-egg.php          # Main plugin file (entry point)
lib/
  WPEE_Plugin.php          # Main Plugin class (singleton, coordinates components)
  WPEE_Options.php         # Options/Settings class (admin UI, sanitization)
  WPEE_Meta_Box.php        # Meta Box class (post filter UI)
  WPEE_Frontend.php        # Frontend class (script enqueuing, post visibility)
  wp-easter-egg.js         # Frontend JavaScript (key detection, actions)
  wp-easter-egg-admin.js   # Admin JavaScript (UI interactions)
  raptorize/               # Third-party raptorize library
```

## Class Reference

### WP_Easter_Egg\Plugin

The main plugin class. Implements the singleton pattern.

```php
$plugin = \WP_Easter_Egg\Plugin::get_instance();
$plugin->get_version();      // Returns '3.1.0'
$plugin->get_name();         // Returns plugin name
$plugin->get_url();          // Returns plugin URL
$plugin->get_path();         // Returns plugin path
$plugin->fetch_option( $key ); // Get a setting value
$plugin->is_filter_enabled();  // Check if filter is active
```

### WP_Easter_Egg\Options

Handles settings registration, sanitization, and admin UI.

```php
$options = $plugin->get_options();
$options->sanitize_settings( $input ); // Sanitize all settings
$options->fetch_option( $key );        // Get a setting value
```

### WP_Easter_Egg\Frontend

Handles frontend rendering and post visibility logic.

```php
$frontend = $plugin->get_frontend();
$frontend->do_easter_egg(); // Enqueue scripts and localize data
```

### WP_Easter_Egg\Meta_Box

Handles the post meta box for the filter system.

```php
$meta_box = $plugin->get_meta_box();
```

## Extending the Plugin

### Adding New Actions

1. Add the action to the `$allowed_actions` array in `WPEE_Options::sanitize_settings()`
2. Add the action to the select field in `WPEE_Options::render_action_field()`
3. Add a case to the switch statement in `wp-easter-egg.js` `app.trigger_action()`
4. Implement the action method in `wp-easter-egg.js`

### Adding New Post Types to Filter

Use the `wpee_allowed_on` filter:

```php
add_filter( 'wpee_allowed_on', function( $post_types ) {
    $post_types[] = 'product';
    return $post_types;
} );
```

## Testing

### Running PHPUnit Tests

```bash
composer install
vendor/bin/phpunit
```

### Running JavaScript Tests

```bash
npm install
npx jest
```

### Running E2E Tests

```bash
npm install
npx cypress open
```

## Code Standards

* PHP: WordPress Coding Standards (WPCS)
* JavaScript: ES6+, jQuery compatible
* All PHP classes use PSR-4 autoloading via Composer
* All user input is sanitized and escaped
* All output is escaped appropriately
