# API Reference

## PHP Classes

### WP_Easter_Egg\Plugin

Main plugin class implementing the singleton pattern.

#### Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `get_instance()` | Get the singleton instance | `Plugin` |
| `get_version()` | Get plugin version | `string` |
| `get_name()` | Get plugin name | `string` |
| `get_url()` | Get plugin URL | `string` |
| `get_path()` | Get plugin path | `string` |
| `fetch_option( $key )` | Get a setting value | `mixed` |
| `is_filter_enabled()` | Check if filter is active | `bool` |
| `get_options()` | Get Options component | `Options` |
| `get_meta_box()` | Get Meta_Box component | `Meta_Box` |
| `get_frontend()` | Get Frontend component | `Frontend` |

#### Constants

| Constant | Value | Description |
|----------|-------|-------------|
| `VERSION` | `'3.0.0'` | Plugin version |
| `OPTION_NAME` | `'wp_easter_egg_settings'` | Option name for settings |

### WP_Easter_Egg\Options

Handles settings registration, sanitization, and admin UI.

#### Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `sanitize_settings( $input )` | Sanitize all settings before saving | `array` |
| `fetch_option( $key )` | Get a setting value | `mixed` |

### WP_Easter_Egg\Frontend

Handles frontend rendering and post visibility logic.

#### Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `do_easter_egg()` | Enqueue scripts and localize data | `void` |

### WP_Easter_Egg\Meta_Box

Handles the post meta box for the filter system.

#### Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `get_filter_label()` | Get the filter label text | `string` |

## JavaScript API

### window.WP_Easter_Egg

The main frontend application object.

#### Properties

| Property | Type | Description |
|----------|------|-------------|
| `l10n` | `object` | Localization data from PHP |
| `$` | `object` | Cached jQuery references |

#### Methods

| Method | Description |
|--------|-------------|
| `init()` | Initialize the Easter Egg |
| `bind_keydown()` | Bind key sequence detection |
| `normalize_code( string )` | Normalize keycode string |
| `trigger_action()` | Trigger the configured action |
| `execute_custom_js()` | Execute custom JavaScript safely |
| `rotate_screen()` | Rotate the screen |
| `cornify()` | Trigger Cornify effect |
| `raptorize()` | Trigger Raptorize effect |
| `move_image_across( position )` | Move image across screen |

### window.wpee_config

Localization data passed from PHP to JavaScript.

| Property | Type | Description |
|----------|------|-------------|
| `type` | `string` | Key sequence type ('konami' or 'custom') |
| `custom_code` | `string` | Custom key code sequence |
| `action` | `string` | Easter Egg action |
| `custom_js` | `string` | Custom JavaScript code |
| `image` | `string` | Image URL |
| `raptor_path` | `string` | Path to raptorize assets |

## Filters

### wpee_allowed_on

Filter the post types that show the meta box.

```php
add_filter( 'wpee_allowed_on', function( $post_types ) {
    return array( 'post', 'page', 'product' );
} );
```

## Actions

No custom actions are currently defined.

## Constants

| Constant | Value | Description |
|----------|-------|-------------|
| `WP_EASTER_EGG_VERSION` | `'3.0.0'` | Plugin version (defined in main file) |
| `WP_EASTER_EGG` | `true` | Development mode flag |
