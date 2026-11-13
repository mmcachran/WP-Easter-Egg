<?php
namespace WP_Easter_Egg;

/**
 * Class to handle the post meta box for the Easter Egg filter.
 *
 * @package WP_Easter_Egg
 * @since   3.0.0
 */
class Meta_Box {

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
	 * Add the meta box to supported post types.
	 *
	 * @since 3.0.0
	 */
	public function meta_box_add() {
		$allowed_on = apply_filters( 'wpee_allowed_on', array( 'post', 'page' ) );
		add_meta_box(
			'wpee-meta-box',
			__( 'WP Easter Egg', 'wp_easter_egg' ),
			array( $this, 'render_callback' ),
			$allowed_on,
			'side',
			'high'
		);
	}

	/**
	 * Render the meta box callback.
	 *
	 * @since 3.0.0
	 *
	 * @param object $post Current post object.
	 */
	public function render_callback( $post ) {
		$values = get_post_custom( $post->ID );
		$check  = isset( $values['_wpee_added_to_filter'][0] ) ? esc_attr( $values['_wpee_added_to_filter'][0] ) : '';

		wp_nonce_field( 'wpee_meta_box_nonce', 'meta_box_nonce' );
		?>
		<p>
			<input type="checkbox" id="_wpee_added_to_filter" name="_wpee_added_to_filter" <?php checked( $check, 'on' ); ?> />
			<label for="_wpee_added_to_filter"><?php _e( 'Add to filter?', 'wp_easter_egg' ); ?></label>
			<p><small><?php echo esc_html( $this->get_filter_label() ); ?></small></p>
		</p>
		<?php
	}

	/**
	 * Save the meta box data when a post is saved.
	 *
	 * @since 3.0.0
	 *
	 * @param int $post_id Post ID.
	 */
	public function meta_box_save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['meta_box_nonce'], 'wpee_meta_box_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$chk = isset( $_POST['_wpee_added_to_filter'] ) && $_POST['_wpee_added_to_filter'] ? 'on' : 'off';
		update_post_meta( $post_id, '_wpee_added_to_filter', $chk );
	}

	/**
	 * Get the filter label based on current filter mode.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_filter_label() {
		$post_type = 'post';

		if ( isset( $_GET['post'] ) ) {
			$post_type = get_post_type( $_GET['post'] );
		} elseif ( isset( $_GET['post_type'] ) ) {
			$post_type = $_GET['post_type'];
		}

		$pt_object = get_post_type_object( $post_type );
		$label     = $pt_object ? $pt_object->labels->singular_name : __( 'Post', 'wp_easter_egg' );

		switch ( $this->plugin->fetch_option( 'filter' ) ) {
			case 'inclusive':
				return sprintf( __( 'Allow Easter Egg on this %s', 'wp_easter_egg' ), $label );
			default:
				return sprintf( __( 'Do NOT allow Easter Egg on this %s', 'wp_easter_egg' ), $label );
		}
	}
}
