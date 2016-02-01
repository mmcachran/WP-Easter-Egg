<?php
if( ! class_exists( 'WPEE_Meta_Box' ) ):
class WPEE_Meta_Box {
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}
	
	public function meta_box_add() {
		$allowed_on = apply_filters( 'wpee_allowed_on', array( 'post', 'page' ) );
		add_meta_box( 'wpee-meta-box', 'WP Easter Egg', array( $this, 'meta_box_cb' ), $allowed_on, 'side', 'high' );
	}
	
	public function meta_box_cb() {
	    global $post;
	    $values = get_post_custom( $post->ID );
	    $check = isset( $values['_wpee_added_to_filter'][0] ) ? esc_attr( $values['_wpee_added_to_filter'][0] ) : '';
	     
	    // We'll use this nonce field later on when saving.
	    wp_nonce_field( 'wpee_meta_box_nonce', 'meta_box_nonce' );
	    ?>     
	    <p>
	        <input type="checkbox" id="_wpee_added_to_filter" name="_wpee_added_to_filter" <?php checked( $check, 'on' ); ?> />
	        <label for="_wpee_added_to_filter"><?php _e( 'Add to filter?', 'wp_easter_egg' ); ?></label>
	        <p><small><?php echo $this->filter_label(); ?></small></p>
	    </p>
	    <?php  
	}
	
	public function meta_box_save( $post_id ) {
		// bail if we're autosaving
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		// bail if our nounce if not verified
		if( ! isset( $_POST['meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['meta_box_nonce'], 'wpee_meta_box_nonce' ) ) {
		    return;
		}

		// bail if our current user can't edit this post
		if( ! current_user_can( 'edit_post' ) ) {
			return;
		}
	         
		// save the post meta
		$chk = isset( $_POST['_wpee_added_to_filter'] ) && $_POST['_wpee_added_to_filter'] ? 'on' : 'off';
		update_post_meta( $post_id, '_wpee_added_to_filter', $chk );
	}

	public function filter_label() {
		if ( ! isset( $_GET['post'] ) && ! isset( $_GET['post_type'] ) ) {
			$label = __( 'Post' );
		} else {
			$pt = isset( $_GET['post_type'] ) ? $_GET['post_type'] : get_post_type( $_GET['post'] );
			$pt_object = get_post_type_object( $pt );
			$label = $pt_object->labels->singular_name;
		}

		switch ( wp_easter_egg()->fetch_option( 'filter' ) ) {
			case 'inclusive':
				$label = sprintf( __( 'Allow Easter Egg on this %s', 'wp_easter_egg' ), $label );
				break;
			default:
				$label = sprintf( __( 'Do NOT allow Easter Egg on this %s', 'wp_easter_egg' ), $label );
				break;
		}
		return $label;
	}
}
endif;