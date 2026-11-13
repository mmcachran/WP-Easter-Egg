<?php
/**
 * Bootstrap file for WP Easter Egg PHPUnit tests.
 *
 * Sets up autoloading and mocks WordPress functions for testing
 * outside of a full WordPress installation.
 */

// Register the PSR-4 autoloader from Composer if available.
$vendor_autoload = __DIR__ . '/../../vendor/autoload.php';
if ( file_exists( $vendor_autoload ) ) {
	require_once $vendor_autoload;
} else {
	// Fallback: simple PSR-4 autoloader for the WP_Easter_Egg namespace.
	spl_autoload_register( function ( $class ) {
		$prefix = 'WP_Easter_Egg\\';
		$base_dir = __DIR__ . '/../../lib/';
		$len = strlen( $prefix );

		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class, $len );
		$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	} );
}

/**
 * Mock WordPress functions for testing outside of WordPress.
 */
if ( ! function_exists( 'add_action' ) ) {
	function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		global $wp_actions;
		$wp_actions[ $hook ][] = array( 'callback' => $callback, 'priority' => $priority, 'args' => $accepted_args );
		return true;
	}
}

if ( ! function_exists( 'apply_filters' ) ) {
	function apply_filters( $hook, $value ) {
		global $wp_filters;
		if ( isset( $wp_filters[ $hook ] ) ) {
			foreach ( $wp_filters[ $hook ] as $filter ) {
				$value = call_user_func( $filter['callback'], $value );
			}
		}
		return $value;
	}
}

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $option, $default = false ) {
		global $wp_options;
		return isset( $wp_options[ $option ] ) ? $wp_options[ $option ] : $default;
	}
}

if ( ! function_exists( 'update_option' ) ) {
	function update_option( $option, $value ) {
		global $wp_options;
		$wp_options[ $option ] = $value;
		return true;
	}
}

if ( ! function_exists( 'get_post_meta' ) ) {
	function get_post_meta( $post_id, $key = '', $single = false ) {
		global $wp_post_meta;
		if ( ! isset( $wp_post_meta[ $post_id ] ) ) {
			return $single ? '' : array();
		}
		if ( '' === $key ) {
			return $wp_post_meta[ $post_id ];
		}
		if ( $single ) {
			return isset( $wp_post_meta[ $post_id ][ $key ] ) ? $wp_post_meta[ $post_id ][ $key ] : '';
		}
		return isset( $wp_post_meta[ $post_id ][ $key ] ) ? $wp_post_meta[ $post_id ][ $key ] : array();
	}
}

if ( ! function_exists( 'update_post_meta' ) ) {
	function update_post_meta( $post_id, $meta_key, $meta_value ) {
		global $wp_post_meta;
		$wp_post_meta[ $post_id ][ $meta_key ] = $meta_value;
		return true;
	}
}

if ( ! function_exists( 'current_user_can' ) ) {
	function current_user_can( $capability ) {
		return true;
	}
}

if ( ! function_exists( 'wp_verify_nonce' ) ) {
	function wp_verify_nonce( $nonce, $action ) {
		return true;
	}
}

if ( ! function_exists( 'wp_nonce_field' ) ) {
	function wp_nonce_field( $action = -1, $name = '_wpnonce', $echo = true ) {
		return '';
	}
}

if ( ! function_exists( 'checked' ) ) {
	function checked( $current, $value = true, $echo = true ) {
		return $current === $value ? ' checked="checked"' : '';
	}
}

if ( ! function_exists( 'selected' ) ) {
	function selected( $current, $value = true, $echo = true ) {
		return $current === $value ? ' selected="selected"' : '';
	}
}

if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $url ) {
		return $url;
	}
}

if ( ! function_exists( 'esc_url_raw' ) ) {
	function esc_url_raw( $url ) {
		return $url;
	}
}

if ( ! function_exists( 'esc_textarea' ) ) {
	function esc_textarea( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'wp_kses_post' ) ) {
	function wp_kses_post( $data ) {
		return $data;
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		return trim( $str );
	}
}

if ( ! function_exists( 'trailingslashit' ) ) {
	function trailingslashit( $string ) {
		return rtrim( $string, '/' ) . '/';
	}
}

if ( ! function_exists( 'plugin_dir_url' ) ) {
	function plugin_dir_url( $file ) {
		return 'http://example.com/wp-content/plugins/' . basename( dirname( $file ) ) . '/';
	}
}

if ( ! function_exists( 'plugin_dir_path' ) ) {
	function plugin_dir_path( $file ) {
		return dirname( $file ) . '/';
	}
}

if ( ! function_exists( 'dirname' ) ) {
	// dirname is a built-in PHP function, this is just a safety check
}

if ( ! function_exists( 'load_plugin_textdomain' ) ) {
	function load_plugin_textdomain( $domain, $deprecated = false, $plugin_rel_path = false ) {
		return true;
	}
}

if ( ! function_exists( 'load_textdomain' ) ) {
	function load_textdomain( $domain, $mofile ) {
		return true;
	}
}

if ( ! function_exists( 'plugins_url' ) ) {
	function plugins_url( $path = '', $plugin = '' ) {
		return 'http://example.com/wp-content/plugins/' . ( $plugin ? basename( dirname( $plugin ) ) . '/' : '' ) . $path;
	}
}

if ( ! function_exists( 'wp_enqueue_script' ) ) {
	function wp_enqueue_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = false ) {
		global $wp_scripts;
		$wp_scripts[ $handle ] = array( 'src' => $src, 'deps' => $deps, 'ver' => $ver, 'in_footer' => $in_footer );
	}
}

if ( ! function_exists( 'wp_localize_script' ) ) {
	function wp_localize_script( $handle, $object_name, $data ) {
		global $wp_localized_scripts;
		$wp_localized_scripts[ $handle ] = array( 'name' => $object_name, 'data' => $data );
	}
}

if ( ! function_exists( 'wp_enqueue_media' ) ) {
	function wp_enqueue_media() {
		return true;
	}
}

if ( ! function_exists( 'is_admin' ) ) {
	function is_admin() {
		return false;
	}
}

if ( ! function_exists( 'add_options_page' ) ) {
	function add_options_page( $page_title, $menu_title, $capability, $menu_slug, $callback = '' ) {
		return true;
	}
}

if ( ! function_exists( 'register_setting' ) ) {
	function register_setting( $option_group, $option_name, $args = array() ) {
		return true;
	}
}

if ( ! function_exists( 'add_settings_section' ) ) {
	function add_settings_section( $id, $title, $callback, $page ) {
		return true;
	}
}

if ( ! function_exists( 'add_settings_field' ) ) {
	function add_settings_field( $id, $title, $callback, $page, $section = '', $args = array() ) {
		return true;
	}
}

if ( ! function_exists( 'do_settings_sections' ) ) {
	function do_settings_sections( $page ) {
		return true;
	}
}

if ( ! function_exists( 'settings_fields' ) ) {
	function settings_fields( $option_group ) {
		return true;
	}
}

if ( ! function_exists( 'submit_button' ) ) {
	function submit_button( $text = '', $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = '' ) {
		return '';
	}
}

if ( ! function_exists( 'add_meta_box' ) ) {
	function add_meta_box( $id, $title, $callback, $screen, $context = 'advanced', $priority = 'default', $callback_args = null ) {
		return true;
	}
}

if ( ! function_exists( 'get_post_type_object' ) ) {
	function get_post_type_object( $post_type ) {
		return (object) array( 'labels' => (object) array( 'singular_name' => 'Post' ) );
	}
}

if ( ! function_exists( 'get_post_type' ) ) {
	function get_post_type( $post_id = null ) {
		return 'post';
	}
}

if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( '_e' ) ) {
	function _e( $text, $domain = 'default' ) {
		echo $text;
	}
}

if ( ! defined( 'WPINC' ) ) {
	define( 'WPINC', 'wp-includes' );
}

if ( ! defined( 'WP_LANG_DIR' ) ) {
	define( 'WP_LANG_DIR', '/wp-content/languages' );
}

if ( ! defined( 'DOING_AUTOSAVE' ) ) {
	define( 'DOING_AUTOSAVE', false );
}

// Global mock storage.
$GLOBALS['wp_actions'] = array();
$GLOBALS['wp_filters'] = array();
$GLOBALS['wp_options'] = array();
$GLOBALS['wp_post_meta'] = array();
$GLOBALS['wp_scripts'] = array();
$GLOBALS['wp_localized_scripts'] = array();
