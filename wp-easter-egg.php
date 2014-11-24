<?php
/**
 * Plugin Name: WP Easter Egg
 * Description: Add an Easter Egg to your site
 * Version:     0.1.0
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

	const VERSION = '0.1.0';
	public static $url  = '';
	public static $path = '';
	public static $name = '';

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
		$this->options = get_option( 'wp_easter_egg_settings' );
	}

	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );

		// Add whatever is in the metabox field to <head>
		add_action( 'wp_head', array( $this, 'do_easter_egg' ), 1 );
		
		// Adds "Settings" link to the plugin action page
		add_action( 'admin_menu', array( $this, 'wp_easter_egg_add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'wp_easter_egg_settings_init' ) );
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
	 * Output content from metabox defined above
	 */
	public function do_easter_egg() {
		wp_enqueue_script( 'wp-easter-egg', plugins_url( '/wp-easter-egg.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'wp-easter-egg', 'wpee_config', $this->compile_js_data() );
	}
	
	private function compile_js_data() {
		$js_data = array(
			'type' => $this->fetch_option( 'type' ),
			'custom_code' => $this->fetch_option( 'custom_code' ),
			'action' => $this->fetch_option( 'action' ),
			'custom_js' => $this->fetch_option( 'custom_js' ),
		);
		
		return $js_data;
	}
	
	/**
	* Get options from the wp_easter_egg_settings option array.
	*
	* @since 1.0.0
	* @access private
	*
	* @param  string $key Key to get from the wp_easter_egg_settings option array.
	* @return string Returns the value of the key or false on failure.
	*/
	private function fetch_option( $key ) {
		// Are options already set?
		if( empty( $this->options ) ) {
			$this->options = get_option( 'wp_easter_egg_settings' );
		}
		
		// Does the key exist?
		if( isset( $this->options[$key] ) ) {
			return $this->options[$key];
		}
		
		// If nothing has been returned yet, return false
		return false;
	}
	
	/* Options */
	public function wp_easter_egg_add_admin_menu() {
		add_options_page( 'WP Easter Egg', 'WP Easter Egg', 'manage_options', 'wp_easter_egg_plugin', array( $this, 'wp_easter_egg_plugin_options_page' ) );
	}
	
	
	public function wp_easter_egg_settings_exist() { 
		if( false == get_option( 'wp_easter_egg_plugin_settings' ) ) { 
			add_option( 'wp_easter_egg_plugin_settings' );
		}
	}
	
	public function wp_easter_egg_settings_init() { 
		register_setting( 'WP_Easter_Egg_settingsPage', 'wp_easter_egg_settings' );
		
		// only add the js on the WPEE admin page
		if( is_admin() && isset( $_GET['page'] ) && $_GET['page'] === 'wp_easter_egg_plugin' ) {
			wp_enqueue_script( 'wp-easter-egg-admin', plugins_url( '/wp-easter-egg-admin.js', __FILE__ ), array( 'jquery' ) );
		}
		
		add_settings_section(
			'wp_easter_egg_settingsPage_section', 
			__( '', 'wp_easter_egg' ), 
			array( $this, 'wp_easter_egg_settings_section_callback' ), 
			'WP_Easter_Egg_settingsPage'
		);
		
		add_settings_field( 
			'wp_easter_egg_type_render', 
			__( 'Easter Egg Type', 'wp_easter_egg' ), 
			array( $this, 'wp_easter_egg_type_render' ), 
			'WP_Easter_Egg_settingsPage', 
			'wp_easter_egg_settingsPage_section' 
		);
		
		add_settings_field( 
			'wp_easter_egg_custom_code_render', 
			__( 'Easter Egg Custom Code', 'wp_easter_egg' ), 
			array( $this, 'wp_easter_egg_custom_code_render' ), 
			'WP_Easter_Egg_settingsPage', 
			'wp_easter_egg_settingsPage_section' 
		);
		
		add_settings_field( 
			'wp_easter_egg_action_render', 
			__( 'Easter Egg Action', 'wp_easter_egg' ), 
			array( $this, 'wp_easter_egg_action_render' ), 
			'WP_Easter_Egg_settingsPage', 
			'wp_easter_egg_settingsPage_section' 
		);
		
		add_settings_field( 
			'wp_easter_egg_custom_js_render', 
			__( 'Custom JS', 'wp_easter_egg' ), 
			array( $this, 'wp_easter_egg_custom_js_render' ), 
			'WP_Easter_Egg_settingsPage', 
			'wp_easter_egg_settingsPage_section' 
		);
	}
	
	
	public function wp_easter_egg_type_render() {
		?>
		<select name='wp_easter_egg_settings[type]'>
			<option value='konami' <?php selected( $this->fetch_option( 'type' ), 'konami' ); ?>>Konami</option>
			<option value='custom' <?php selected( $this->fetch_option( 'type' ), 'custom' ); ?>>Custom</option>
		</select>
		<?php
	}
	
	public function wp_easter_egg_custom_code_render() {
		?>
		<input type='text' name='wp_easter_egg_settings[custom_code]' value='<?php echo $this->fetch_option( 'custom_code' ); ?>'>
		<p><label for="wp_easter_egg_settings[custom_code]">Example: 38,38,40,40,37,39,37,39,66,65</label></p>
		<?php
	}
	
	public function wp_easter_egg_action_render() {
		?>	
		<select name='wp_easter_egg_settings[action]'>
			<option value='rotate_screen' <?php selected( $this->fetch_option( 'action' ), 'rotate_screen' ); ?>>Rotate Screen</option>
			<option value='custom_js' <?php selected( $this->fetch_option( 'action' ), 'custom_js' ); ?>>Custom JS</option>
		</select>
		<?php
	}
	
	public function wp_easter_egg_custom_js_render() { 
		?>
		<textarea cols='40' rows='5' name='wp_easter_egg_settings[custom_js]'><?php echo $this->fetch_option( 'custom_js' ); ?></textarea>
	 	<p><label for="wp_easter_egg_settings[custom_js]">WARNING: this could be dangerous to your site</label></p>
		<?php
	
	}

	public function wp_easter_egg_settings_section_callback() {
		echo __( '', 'wp_easter_egg' );
	}
	
	public function wp_easter_egg_plugin_options_page() {
		?>
		<form action='options.php' method='post'>
			<h2>WP Easter Egg Plugin</h2>
			<?php
			settings_fields( 'WP_Easter_Egg_settingsPage' );
			do_settings_sections( 'WP_Easter_Egg_settingsPage' );
			submit_button();
			?>	
		</form>
		<?php
	}
}
endif;

// init our class
$wp_easter_egg = new WP_Easter_Egg();
$wp_easter_egg->hooks();