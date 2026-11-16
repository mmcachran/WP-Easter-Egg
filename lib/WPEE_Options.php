<?php
namespace WP_Easter_Egg;

/**
 * Class to handle options for the Easter Egg.
 *
 * Manages settings registration, sanitization, and the admin
 * options page for WP Easter Egg.
 *
 * @package WP_Easter_Egg
 * @since   3.0.0
 */
class Options {

	/**
	 * Option name used for storing settings.
	 *
	 * @since 3.0.0
	 * @var   string
	 */
	const OPTION_NAME = 'wp_easter_egg_settings';

	/**
	 * Plugin instance.
	 *
	 * @since 3.0.0
	 * @var   Plugin
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since 3.0.0
	 *
	 * @param Plugin $plugin Plugin instance.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Add the admin menu page.
	 *
	 * @since 3.0.0
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'WP Easter Egg', 'wp_easter_egg' ),
			__( 'WP Easter Egg', 'wp_easter_egg' ),
			'manage_options',
			'wp_easter_egg_plugin',
			array( $this, 'render_options_page' )
		);
	}

	/**
	 * Initialize settings and register fields.
	 *
	 * @since 3.0.0
	 */
	public function settings_init() {
		register_setting(
			'wp_easter_egg_settings_group',
			self::OPTION_NAME,
			array(
				'type'         => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'show_in_rest' => false,
			)
		);

		// Only enqueue admin assets on the WPEE settings page.
		if ( is_admin() && isset( $_GET['page'] ) && 'wp_easter_egg_plugin' === $_GET['page'] ) {
			wp_enqueue_media();
			wp_enqueue_script(
				'wp-easter-egg-admin',
				plugins_url( '/wp-easter-egg-admin.js', __FILE__ ),
				array(),
				WP_EASTER_EGG_VERSION,
				true
			);
		}

		add_settings_section(
			'wp_easter_egg_settings_section',
			__( '', 'wp_easter_egg' ),
			array( $this, 'render_section_intro' ),
			'wp_easter_egg_settings_group'
		);

		add_settings_field(
			'wp_easter_egg_filter',
			__( 'Easter Egg Filter', 'wp_easter_egg' ),
			array( $this, 'render_filter_field' ),
			'wp_easter_egg_settings_group',
			'wp_easter_egg_settings_section'
		);

		add_settings_field(
			'wp_easter_egg_type',
			__( 'Easter Egg Type', 'wp_easter_egg' ),
			array( $this, 'render_type_field' ),
			'wp_easter_egg_settings_group',
			'wp_easter_egg_settings_section'
		);

		add_settings_field(
			'wp_easter_egg_custom_code',
			__( 'Easter Egg Custom Code', 'wp_easter_egg' ),
			array( $this, 'render_custom_code_field' ),
			'wp_easter_egg_settings_group',
			'wp_easter_egg_settings_section'
		);

		add_settings_field(
			'wp_easter_egg_action',
			__( 'Easter Egg Action', 'wp_easter_egg' ),
			array( $this, 'render_action_field' ),
			'wp_easter_egg_settings_group',
			'wp_easter_egg_settings_section'
		);

		add_settings_field(
			'wp_easter_egg_custom_js',
			__( 'Custom JS', 'wp_easter_egg' ),
			array( $this, 'render_custom_js_field' ),
			'wp_easter_egg_settings_group',
			'wp_easter_egg_settings_section'
		);

		add_settings_field(
			'wp_easter_egg_image',
			__( 'Image', 'wp_easter_egg' ),
			array( $this, 'render_image_field' ),
			'wp_easter_egg_settings_group',
			'wp_easter_egg_settings_section'
		);
	}

	/**
	 * Sanitize all settings before saving.
	 *
	 * @since 3.0.0
	 *
	 * @param array $input Raw input values.
	 * @return array Sanitized values.
	 */
	public function sanitize_settings( $input ) {
		$output = array();

		$allowed_filters   = array( 'off', 'exclusive', 'inclusive' );
		$allowed_types     = array( 'konami', 'custom' );
		$allowed_actions   = array(
			'rotate_screen',
			'cornify',
			'raptorize',
			'move_image_across_bottom',
			'move_image_across_middle',
			'move_image_across_top',
			'custom_js',
		);

		if ( isset( $input['filter'] ) && in_array( $input['filter'], $allowed_filters, true ) ) {
			$output['filter'] = sanitize_text_field( $input['filter'] );
		} else {
			$output['filter'] = 'off';
		}

		if ( isset( $input['type'] ) && in_array( $input['type'], $allowed_types, true ) ) {
			$output['type'] = sanitize_text_field( $input['type'] );
		} else {
			$output['type'] = 'konami';
		}

		if ( isset( $input['custom_code'] ) ) {
			$output['custom_code'] = $this->sanitize_keycode( $input['custom_code'] );
		} else {
			$output['custom_code'] = '';
		}

		if ( isset( $input['action'] ) && in_array( $input['action'], $allowed_actions, true ) ) {
			$output['action'] = sanitize_text_field( $input['action'] );
		} else {
			$output['action'] = 'rotate_screen';
		}

		if ( isset( $input['custom_js'] ) ) {
			$output['custom_js'] = wp_kses_post( $input['custom_js'] );
		} else {
			$output['custom_js'] = '';
		}

		if ( isset( $input['image'] ) ) {
			$output['image'] = esc_url_raw( $input['image'] );
		} else {
			$output['image'] = '';
		}

		return $output;
	}

	/**
	 * Sanitize a comma-separated keycode string.
	 *
	 * @since 3.0.0
	 *
	 * @param string $code Raw keycode string.
	 * @return string Sanitized keycode string.
	 */
	protected function sanitize_keycode( $code ) {
		$code = trim( $code );
		$parts = explode( ',', $code );
		$parts = array_filter( $parts, function( $part ) {
			return is_numeric( trim( $part ) ) && ( int ) trim( $part ) >= 0 && ( int ) trim( $part ) <= 255;
		} );
		$parts = array_map( function( $part ) {
			return ( int ) trim( $part );
		}, $parts );

		return implode( ',', $parts );
	}

	/**
	 * Fetch a plugin option by key.
	 *
	 * @since 3.0.0
	 *
	 * @param string $key Option key.
	 * @return mixed Option value or false if not set.
	 */
	public function fetch_option( $key ) {
		$options = get_option( self::OPTION_NAME, array() );

		if ( isset( $options[ $key ] ) ) {
			return $options[ $key ];
		}

		return false;
	}

	/**
	 * Render the settings section intro.
	 *
	 * @since 3.0.0
	 */
	public function render_section_intro() {
		echo wp_kses_post( __( '', 'wp_easter_egg' ) );
	}

	/**
	 * Render the filter select field.
	 *
	 * @since 3.0.0
	 */
	public function render_filter_field() {
		$filter = $this->plugin->fetch_option( 'filter' );
		?>
		<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[filter]">
			<option value="off"   <?php selected( $filter, 'off' );   ?>><?php _e( 'Off', 'wp_easter_egg' ); ?></option>
			<option value="exclusive" <?php selected( $filter, 'exclusive' ); ?>><?php _e( 'Exclusive', 'wp_easter_egg' ); ?></option>
			<option value="inclusive" <?php selected( $filter, 'inclusive' ); ?>><?php _e( 'Inclusive', 'wp_easter_egg' ); ?></option>
		</select>
		<p class="description"><?php _e( 'Control whether the Easter Egg appears on specific posts.', 'wp_easter_egg' ); ?></p>
		<?php
	}

	/**
	 * Render the type select field.
	 *
	 * @since 3.0.0
	 */
	public function render_type_field() {
		$type = $this->plugin->fetch_option( 'type' );
		?>
		<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[type]">
			<option value="konami"  <?php selected( $type, 'konami' );  ?>><?php _e( 'Konami Code', 'wp_easter_egg' ); ?></option>
			<option value="custom"  <?php selected( $type, 'custom' );  ?>><?php _e( 'Custom Code', 'wp_easter_egg' ); ?></option>
		</select>
		<?php
	}

	/**
	 * Render the custom code input field.
	 *
	 * @since 3.0.0
	 */
	public function render_custom_code_field() {
		$custom_code = $this->plugin->fetch_option( 'custom_code' );
		?>
		<input type="text" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[custom_code]" value="<?php echo esc_attr( $custom_code ); ?>" />
		&nbsp;&nbsp;
		<input type="checkbox" id="keycode-helper" />
		<label for="keycode-helper"><?php _e( 'Activate keycode helper', 'wp_easter_egg' ); ?></label>
		<p><label for="wp_easter_egg_settings[custom_code]"><?php _e( 'Example: 38,38,40,40,37,39,37,39,66,65', 'wp_easter_egg' ); ?></label></p>
		<?php
	}

	/**
	 * Render the action select field.
	 *
	 * @since 3.0.0
	 */
	public function render_action_field() {
		$action = $this->plugin->fetch_option( 'action' );
		$actions = array(
			'rotate_screen'             => __( 'Rotate Screen', 'wp_easter_egg' ),
			'cornify'                   => __( 'Cornify', 'wp_easter_egg' ),
			'raptorize'                 => __( 'Raptorize', 'wp_easter_egg' ),
			'move_image_across_bottom'  => __( 'Move Image Across Bottom of Screen', 'wp_easter_egg' ),
			'move_image_across_middle'  => __( 'Move Image Across Middle of Screen', 'wp_easter_egg' ),
			'move_image_across_top'     => __( 'Move Image Across Top of Screen', 'wp_easter_egg' ),
			'custom_js'                 => __( 'Custom JS', 'wp_easter_egg' ),
		);
		?>
		<select name="<?php echo esc_attr( self::OPTION_NAME ); ?>[action]">
			<?php foreach ( $actions as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $action, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	/**
	 * Render the custom JS textarea field.
	 *
	 * @since 3.0.0
	 */
	public function render_custom_js_field() {
		$custom_js = $this->plugin->fetch_option( 'custom_js' );
		?>
		<textarea cols="40" rows="5" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[custom_js]"><?php echo esc_textarea( $custom_js ); ?></textarea>
		<p><label for="wp_easter_egg_settings[custom_js]"><?php _e( 'WARNING: this could be dangerous to your site', 'wp_easter_egg' ); ?></label></p>
		<?php
	}

	/**
	 * Render the image upload field.
	 *
	 * @since 3.0.0
	 */
	public function render_image_field() {
		$image = $this->plugin->fetch_option( 'image' );
		?>
		<div class="uploader">
			<input id="_wp_easter_egg_image" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[image]" type="text" value="<?php echo esc_url( $image ); ?>" />
			<input id="_wp_easter_egg_image_button" class="button" name="_wp_easter_egg_image_button" type="button" value="<?php _e( 'Upload', 'wp_easter_egg' ); ?>" />
			<p><img id="image_preview" src="<?php echo esc_url( $image ); ?>" style="display: none; max-height: 250px; max-width: 250px;" /></p>
		</div>
		<?php
	}

	/**
	 * Render the options page.
	 *
	 * @since 3.0.0
	 */
	public function render_options_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( $this->plugin->get_name() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'wp_easter_egg_settings_group' );
				do_settings_sections( 'wp_easter_egg_settings_group' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
