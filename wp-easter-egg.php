<?php
/**
 * Plugin Name: WP Easter Egg
 * Description: Add an Easter Egg to your site
 * Version:     1.0.2
 * Author:      mmcachran
 * License:     GPLv2+
 * Text Domain: wp_easter_egg
 * Domain Path: /languages
 */

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if( ! class_exists( 'WP_Easter_Egg' ) ):
class WP_Easter_Egg {

	const VERSION = '1.0.2';
	public static $url  = '';
	public static $path = '';
	public static $name = '';
	
	private static $options = array();

	/**
	 * Sets up our plugin
	 * @since  0.1.0
	 */
	public function __construct() {
		// Useful variables
		self::$url  = trailingslashit( plugin_dir_url( __FILE__ ) );
		self::$path = trailingslashit( dirname( __FILE__ ) );
		self::$name = __( 'WP Easter Egg', 'wp_easter_egg' );
		
		// Set the options
		self::$options = get_option( 'wp_easter_egg_settings' );
	}

	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );

		// Add JS to head
		add_action( 'wp_head', array( $this, 'do_easter_egg' ), 1 );
		
		// Options
		add_action( 'admin_menu', array( $this->settings(), 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this->settings(), 'settings_init' ) );
		
		// create meta box for posts
		if ( 'off' !==  self::fetch_option( 'filter' )  && false != self::fetch_option( 'filter' ) ) {
			add_action( 'add_meta_boxes', array( $this->meta_box(), 'meta_box_add' ) );
			add_action( 'save_post', array( $this->meta_box(), 'meta_box_save' ) );
		}
	}
	
	public function meta_box() {
		if ( isset( $this->meta_box ) ) {
			return $this->meta_box;
		}
		
		require_once( 'lib/meta-box.php' );
		$this->meta_box = new WPEE_Meta_Box( $this );
		return $this->meta_box;
	}
	
	public function settings() {
		if ( isset( $this->settings ) ) {
			return $this->settings;
		}
		require_once( 'lib/options.php' );
		$this->settings = new WP_Easter_Egg_Options( $this );
		return $this->settings;
	}

	/**
	 * Init hooks
	 * @since  0.1.0
	 * @return null
	 */
	public function init() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wp_easter_egg' );
		load_textdomain( 'wp_easter_egg', WP_LANG_DIR . '/wp-easter-egg/wp-easter-egg-' . $locale . '.mo' );
		load_plugin_textdomain( 'wp_easter_egg', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Add JS to head
	 */
	public function do_easter_egg() {
		if ( $this->is_allowed_on_post() ) {
			if( 'raptorize' === self::fetch_option( 'action' ) ) {
				wp_enqueue_script( 'raptorize', plugins_url( '/lib/raptorize/jquery.raptorize.1.0.js', __FILE__ ), array( 'jquery' ) );
			}
			
			wp_enqueue_script( 'wp-easter-egg', plugins_url( '/wp-easter-egg.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( 'wp-easter-egg', 'wpee_config', $this->compile_js_data() );
		}
	}
	
	private function is_allowed_on_post() {
		global $post;
		
		if ( ! self::fetch_option( 'filter' ) || 'off' === self::fetch_option( 'filter' ) ) {
			return true;
		}
		
		$is_added = 'on' === get_post_meta( $post->ID, '_wpee_added_to_filter', true );
		$filter = self::fetch_option( 'filter' );
		
		// fallback if null
		if ( ! $filter ) {
			$filter = 'exclusive';
		}
		
		// Is WPEE allowed on this post?
		if ( 
			( 'exclusive' === $filter && ! $is_added )
			|| ( 'inclusive' === $filter && $is_added )
		) {
			return true;
		} else {
			return false;
		}
	}
	
	private function compile_js_data() {
		$js_data = array(
			'type' => self::fetch_option( 'type' ),
			'custom_code' => self::fetch_option( 'custom_code' ),
			'action' => self::fetch_option( 'action' ),
			'custom_js' => self::fetch_option( 'custom_js' ),
			'image' => self::fetch_option( 'image' ),
		);
		
		return $js_data;
	}
	
	/**
	* Get options from the wp_easter_egg_settings option array.
	*
	* @since 1.0.0
	* @access public
	*
	* @param  string $key Key to get from the wp_easter_egg_settings option array.
	* @return string Returns the value of the key or false on failure.
	*/
	public static function fetch_option( $key ) {
		// Are options already set?
		if( empty( self::$options ) ) {
			self::$options = get_option( 'wp_easter_egg_settings' );
		}
		
		// Does the key exist?
		if( isset( self::$options[$key] ) ) {
			return self::$options[$key];
		}
		
		// If nothing has been returned yet, return false
		return false;
	}
	
}

// init our class
$wp_easter_egg = new WP_Easter_Egg();
$wp_easter_egg->hooks();

endif;