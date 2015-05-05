<?php
/**
 * @package   WP Easter Egg
 * @author    mmcachran
 * @license   GPL-2.0+
 *
 * Plugin Name:       WP Easter Egg
 * Plugin URI:        
 * Description:       Add an Easter Egg to your site
 * Version:           1.0.7
 * Author:            mmcachran
 * Text Domain:       wp_easter_egg
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WP_EASTER_EGG_VERSION', '1.0.7' );

// Are we in DEV mode?
if ( ! defined( 'WP_EASTER_EGG' ) ) {
	define( 'WP_EASTER_EGG', true );
}

// load the plugin
require_once( plugin_dir_path( __FILE__ ) . 'lib/wpee.php' );	
add_action( 'plugins_loaded', array( 'WP_Easter_Egg', 'get_instance' ) );