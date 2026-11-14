<?php
/**
 * @package   WP Easter Egg
 * @author    mmcachran
 * @license   MIT
 *
 * Plugin Name:       WP Easter Egg
 * Plugin URI:
 * Description:       Add an Easter Egg to your WordPress site using the Konami code or a custom key sequence.
 * Version:           3.2.0
 * Author:            mmcachran
 * Text Domain:       wp_easter_egg
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Requires PHP:      7.4
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define the version.
define( 'WP_EASTER_EGG_VERSION', '3.2.0' );

// Are we in DEV mode?
if ( ! defined( 'WP_EASTER_EGG' ) ) {
	define( 'WP_EASTER_EGG', true );
}

// Helper function.
function wp_easter_egg() {
	return \WP_Easter_Egg\Plugin::get_instance();
}

// Load the plugin.
require_once( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );

add_action( 'plugins_loaded', 'wp_easter_egg' );
