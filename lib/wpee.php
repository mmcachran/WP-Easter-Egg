<?php
class WP_Easter_Egg {
	/**
	 * Helper vars
	 *
	 * @since    1.0.3
	 *
	 * @var      array
	 */
	protected static
		$url,
		$path,
		$name;

	/**
	 * Array to hold WPEE options
	 *
	 * @since    1.0.3
	 *
	 * @var      array
	 */
	private static $options = array();

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.3
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.3
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/**
	 * Sets up our plugin
	 * @since  0.1.0
	 */
	private function __construct() {
		// Useful variables
		self::$url  = trailingslashit( plugin_dir_url( __FILE__ ) );
		self::$path = trailingslashit( dirname( __FILE__ ) );
		self::$name = __( 'WP Easter Egg', 'wp_easter_egg' );

		add_action( 'init', array( $this, 'init' ) );

		// Add JS to head
		add_action( 'wp_head', array( $this, 'do_easter_egg' ), 1 );
		
		// Options
		add_action( 'admin_menu', array( $this->settings(), 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this->settings(), 'settings_init' ) );
		
		// create meta box for posts
		if ( self::fetch_option( 'filter' ) && ! ( 'off' ==  self::fetch_option( 'filter' ) ) ) {
			add_action( 'add_meta_boxes', array( $this->meta_box(), 'meta_box_add' ) );
			add_action( 'save_post', array( $this->meta_box(), 'meta_box_save' ) );
		}
	}
	
	public function meta_box() {
		if ( isset( $this->meta_box ) ) {
			return $this->meta_box;
		}
		
		require_once( self::$path . 'meta-box.php' );
		$this->meta_box = new WPEE_Meta_Box( $this );
		return $this->meta_box;
	}
	
	public function settings() {
		if ( isset( $this->settings ) ) {
			return $this->settings;
		}
		require_once( self::$path . 'options.php' );
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
		// bail early if not allowed on post
		if ( ! $this->is_allowed_on_post() ) {
			return;
		}
			
		if( 'raptorize' === self::fetch_option( 'action' ) ) {
			wp_enqueue_script( 'raptorize', self::$url .'raptorize/jquery.raptorize.1.0.js', array( 'jquery' ), '1.0', true );
		}
			
		wp_enqueue_script( 'wp-easter-egg', self::$url . 'wp-easter-egg.js', array( 'jquery' ), WP_EASTER_EGG_VERSION, true );
		wp_localize_script( 'wp-easter-egg', 'wpee_config', $this->compile_js_data() );
	}
	
	/**
	 * Determine if Easter Egg is allowed on the post
	 */
	private function is_allowed_on_post() {
		global $post;
		
		// bail early if filter is off
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
	
	/**
	 * Compile l10n config for front-end
	 * @return array configuration for front-end
	 */
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
	public function fetch_option( $key ) {
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