<?php
if( ! class_exists( 'WP_Easter_Egg_Options' ) ):
class WP_Easter_Egg_Options {

	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	public function add_admin_menu() {
		add_options_page( 'WP Easter Egg', 'WP Easter Egg', 'manage_options', 'wp_easter_egg_plugin', array( $this, 'wp_easter_egg_plugin_options_page' ) );
	}
	
	public function wp_easter_egg_settings_exist() { 
		if( false === get_option( 'wp_easter_egg_plugin_settings' ) ) { 
			add_option( 'wp_easter_egg_plugin_settings' );
		}
	}
	
	public function settings_init() { 
		register_setting( 'WP_Easter_Egg_settingsPage', 'wp_easter_egg_settings' );
		
		// only add the js on the WPEE admin page
		if( is_admin() && isset( $_GET['page'] ) && $_GET['page'] === 'wp_easter_egg_plugin' ) {
			wp_enqueue_media();
			wp_enqueue_script( 'wp-easter-egg-admin', plugins_url( '/wp-easter-egg-admin.js', __FILE__ ), array( 'jquery' ), WP_EASTER_EGG_VERSION );
		}
		
		add_settings_section(
			'wp_easter_egg_settingsPage_section', 
			__( '', 'wp_easter_egg' ), 
			array( $this, 'wp_easter_egg_settings_section_callback' ), 
			'WP_Easter_Egg_settingsPage'
		);
		
		add_settings_field( 
			'wp_easter_egg_filter_render', 
			__( 'Easter Egg Filter', 'wp_easter_egg' ), 
			array( $this, 'wp_easter_egg_filter_render' ), 
			'WP_Easter_Egg_settingsPage', 
			'wp_easter_egg_settingsPage_section' 
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
		
		add_settings_field( 
			'wp_easter_egg_image_render', 
			__( 'Image', 'wp_easter_egg' ), 
			array( $this, 'wp_easter_egg_image_render' ), 
			'WP_Easter_Egg_settingsPage', 
			'wp_easter_egg_settingsPage_section' 
		);
	}
	
	
	public function wp_easter_egg_type_render() {
		?>
		<select name='wp_easter_egg_settings[type]'>
			<option value='konami' <?php selected( $this->plugin->fetch_option( 'type' ), 'konami' ); ?>><?php _e( 'Konami', 'wp_easter_egg' ); ?></option>
			<option value='custom' <?php selected( $this->plugin->fetch_option( 'type' ), 'custom' ); ?>><?php _e( 'Custom', 'wp_easter_egg' ); ?></option>
		</select>
		<?php
	}
	
	public function wp_easter_egg_custom_code_render() {
		?>
		<input type='text' name='wp_easter_egg_settings[custom_code]' value='<?php echo $this->plugin->fetch_option( 'custom_code' ); ?>'> 
		&nbsp; &nbsp; <input type="checkbox" id="keycode-helper" />&nbsp; 
		<?php _e( 'Activate keycode helper', 'wp_easter_egg' ); ?>
		<p><label for="wp_easter_egg_settings[custom_code]">Example: 38,38,40,40,37,39,37,39,66,65</label></p>
		<?php
	}
	
	public function wp_easter_egg_action_render() {
		?>	
		<select name='wp_easter_egg_settings[action]'>
			<option value='rotate_screen' <?php selected( $this->plugin->fetch_option( 'action' ), 'rotate_screen' ); ?>><?php _e( 'Rotate Screen', 'wp_easter_egg' ); ?></option>
			<option value='cornify' <?php selected( $this->plugin->fetch_option( 'action' ), 'cornify' ); ?>><?php _e( 'Cornify', 'wp_easter_egg' ); ?></option>
			<option value='raptorize' <?php selected( $this->plugin->fetch_option( 'action' ), 'raptorize' ); ?>><?php _e( 'Raptorize', 'wp_easter_egg' ); ?></option>
			<option value='move_image_across_bottom' <?php selected( $this->plugin->fetch_option( 'action' ), 'move_image_across_bottom' ); ?>><?php _e( 'Move Image Across Bottom of Screen ', 'wp_easter_egg' ); ?></option>
			<option value='move_image_across_middle' <?php selected( $this->plugin->fetch_option( 'action' ), 'move_image_across_middle' ); ?>><?php _e( 'Move Image Across Middle of Screen' ,'wp_easter_egg' ); ?></option>
			<option value='move_image_across_top' <?php selected( $this->plugin->fetch_option( 'action' ), 'move_image_across_top' ); ?>><?php _e( 'Move Image Across Top of Screen', 'wp_easter_egg' ); ?></option>
			<option value='custom_js' <?php selected( $this->plugin->fetch_option( 'action' ), 'custom_js' ); ?>><?php _e( 'Custom JS', 'wp_easter_egg' ); ?></option>
		</select>
		<?php
	}
	
	public function wp_easter_egg_custom_js_render() { 
		?>
		<textarea cols='40' rows='5' name='wp_easter_egg_settings[custom_js]'><?php echo $this->plugin->fetch_option( 'custom_js' ); ?></textarea>
	 	<p><label for="wp_easter_egg_settings[custom_js]"><?php _e( 'WARNING: this could be dangerous to your site', 'wp_easter_egg' ); ?></label></p>
		<?php
	}
	
	public function wp_easter_egg_image_render() { 
		?>
		<div class="uploader">
			<input id="_wp_easter_egg_image" name="wp_easter_egg_settings[image]" type="text" value="<?php echo $this->plugin->fetch_option( 'image' ); ?>" />
			<input id="_wp_easter_egg_image_button" class="button" name="_wp_easter_egg_image_button" type="button" value="<?php _e( 'Upload', 'wp_easter_egg' ); ?>" />
			<p><img id="image_preview" src="" style="display: none; max-height: 250px; max-width: 250px;" /></p>
		</div>
		<?php
	}
	
	public function wp_easter_egg_filter_render() {
		?>
		<select name='wp_easter_egg_settings[filter]'>
			<option value='off' <?php selected( $this->plugin->fetch_option( 'filter' ), 'off' ); ?>><?php _e( 'Off', 'wp_easter_egg' ); ?></option>
			<option value='exclusive' <?php selected( $this->plugin->fetch_option( 'filter' ), 'exclusive' ); ?>><?php _e( 'Exclusive', 'wp_easter_egg' ); ?></option>
			<option value='inclusive' <?php selected( $this->plugin->fetch_option( 'filter' ), 'inclusive' ); ?>><?php _e( 'Inclusive', 'wp_easter_egg' ); ?></option>
		</select>
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