<?php
namespace WP_Easter_Egg;

/**
 * Class to handle frontend rendering of the Easter Egg.
 *
 * Manages script enqueuing, localization, and post visibility logic.
 *
 * @package WP_Easter_Egg
 * @since   3.0.0
 */
class Frontend {

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
	 * Add Easter Egg scripts to the page head.
	 *
	 * @since 3.0.0
	 */
	public function do_easter_egg() {
		if ( ! $this->is_allowed_on_post() ) {
			return;
		}

		if ( 'raptorize' === $this->plugin->fetch_option( 'action' ) ) {
			wp_enqueue_script(
				'raptorize',
				$this->plugin->get_url() . 'raptorize/jquery.raptorize.1.0.js',
				array( 'jquery' ),
				'1.0',
				true
			);
		}

		wp_enqueue_script(
			'wp-easter-egg',
			$this->plugin->get_url() . 'wp-easter-egg.js',
			array( 'jquery' ),
			WP_EASTER_EGG_VERSION,
			true
		);

		$js_data = $this->compile_js_data();
		$js_data['raptor_path'] = $this->plugin->get_url() . 'raptorize/';

		wp_localize_script( 'wp-easter-egg', 'wpee_config', $js_data );
	}

	/**
	 * Determine if the Easter Egg is allowed on the current post.
	 *
	 * @since 3.0.0
	 *
	 * @return bool
	 */
	private function is_allowed_on_post() {
		global $post;

		if ( ! $this->plugin->is_filter_enabled() ) {
			return true;
		}

		$is_added = 'on' === get_post_meta( $post->ID, '_wpee_added_to_filter', true );
		$filter   = $this->plugin->fetch_option( 'filter' );

		if ( ! $filter ) {
			$filter = 'exclusive';
		}

		if ( 'exclusive' === $filter && ! $is_added ) {
			return true;
		}

		if ( 'inclusive' === $filter && $is_added ) {
			return true;
		}

		return false;
	}

	/**
	 * Compile localization data for the frontend JavaScript.
	 *
	 * @since 3.0.0
	 *
	 * @return array Configuration data for the frontend.
	 */
	private function compile_js_data() {
		return array(
			'type'        => $this->plugin->fetch_option( 'type' ),
			'custom_code' => $this->plugin->fetch_option( 'custom_code' ),
			'action'      => $this->plugin->fetch_option( 'action' ),
			'custom_js'   => $this->plugin->fetch_option( 'custom_js' ),
			'image'       => $this->plugin->fetch_option( 'image' ),
		);
	}
}
