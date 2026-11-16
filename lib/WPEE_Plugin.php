<?php
namespace WP_Easter_Egg;

/**
 * Main plugin class for WP Easter Egg.
 *
 * Handles plugin initialization, hooks, and coordinates
 * between options, meta box, and frontend components.
 *
 * @package WP_Easter_Egg
 * @since   3.0.0
 */
class Plugin {

	/**
	 * Plugin version.
	 *
	 * @since 3.0.0
	 * @var   string
	 */
	const VERSION = '3.4.1';

	/**
	 * Option name used for storing settings.
	 *
	 * @since 3.0.0
	 * @var   string
	 */
	const OPTION_NAME = 'wp_easter_egg_settings';

	/**
	 * The single instance of the class.
	 *
	 * @since 3.0.0
	 * @var   Plugin|null
	 */
	protected static $instance = null;

	/**
	 * URL to the plugin directory.
	 *
	 * @since 3.0.0
	 * @var   string
	 */
	protected $url = '';

	/**
	 * Path to the plugin directory.
	 *
	 * @since 3.0.0
	 * @var   string
	 */
	protected $path = '';

	/**
	 * Plugin name.
	 *
	 * @since 3.0.0
	 * @var   string
	 */
	protected $name = '';

	/**
	 * Options component instance.
	 *
	 * @since 3.0.0
	 * @var   Options|null
	 */
	protected $options = null;

	/**
	 * Meta box component instance.
	 *
	 * @since 3.0.0
	 * @var   Meta_Box|null
	 */
	protected $meta_box = null;

	/**
	 * Frontend component instance.
	 *
	 * @since 3.0.0
	 * @var   Frontend|null
	 */
	protected $frontend = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since 3.0.0
	 *
	 * @return Plugin A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Private constructor to enforce singleton pattern.
	 *
	 * @since 3.0.0
	 */
	private function __construct() {
		$this->url  = trailingslashit( plugin_dir_url( __FILE__ ) );
		$this->path = trailingslashit( dirname( __FILE__ ) );
		$this->name = __( 'WP Easter Egg', 'wp_easter_egg' );

		$this->init_hooks();
	}

	/**
	 * Set up all plugin hooks.
	 *
	 * @since 3.0.0
	 */
	protected function init_hooks() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_head', array( $this->get_frontend(), 'do_easter_egg' ), 1 );
		add_action( 'admin_menu', array( $this->get_options(), 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this->get_options(), 'settings_init' ) );
	}

	/**
	 * Initialize plugin on init hook.
	 *
	 * Loads text domain and conditionally registers meta box.
	 *
	 * @since 3.0.0
	 */
	public function init() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'wp_easter_egg' );
		load_textdomain( 'wp_easter_egg', WP_LANG_DIR . '/wp-easter-egg/wp-easter-egg-' . $locale . '.mo' );
		load_plugin_textdomain( 'wp_easter_egg', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		if ( $this->is_filter_enabled() ) {
			add_action( 'add_meta_boxes', array( $this->get_meta_box(), 'meta_box_add' ) );
			add_action( 'save_post', array( $this->get_meta_box(), 'meta_box_save' ) );
		}
	}

	/**
	 * Get the options component instance.
	 *
	 * @since 3.0.0
	 *
	 * @return Options
	 */
	public function get_options() {
		if ( null === $this->options ) {
			$this->options = new Options( $this );
		}

		return $this->options;
	}

	/**
	 * Get the meta box component instance.
	 *
	 * @since 3.0.0
	 *
	 * @return Meta_Box
	 */
	public function get_meta_box() {
		if ( null === $this->meta_box ) {
			$this->meta_box = new Meta_Box( $this );
		}

		return $this->meta_box;
	}

	/**
	 * Get the frontend component instance.
	 *
	 * @since 3.0.0
	 *
	 * @return Frontend
	 */
	public function get_frontend() {
		if ( null === $this->frontend ) {
			$this->frontend = new Frontend( $this );
		}

		return $this->frontend;
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
		return $this->get_options()->fetch_option( $key );
	}

	/**
	 * Check if the filter feature is enabled.
	 *
	 * @since 3.0.0
	 *
	 * @return bool
	 */
	public function is_filter_enabled() {
		$filter = $this->fetch_option( 'filter' );

		return ! empty( $filter ) && 'off' !== $filter;
	}

	/**
	 * Get the plugin URL.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * Get the plugin path.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Get the plugin name.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get the plugin version.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_version() {
		return self::VERSION;
	}
}
