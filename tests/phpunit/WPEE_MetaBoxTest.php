<?php
namespace WP_Easter_Egg\Tests;

use WP_Easter_Egg\Meta_Box;
use WP_Easter_Egg\Plugin;
use PHPUnit\Framework\TestCase;

class WPEE_MetaBoxTest extends TestCase {

	public function test_get_filter_label_inclusive() {
		global $wp_options, $_GET;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'inclusive' );
		$_GET['post_type'] = 'post';

		$plugin = Plugin::get_instance();
		$meta_box = $plugin->get_meta_box();

		$reflection = new \ReflectionMethod( $meta_box, 'get_filter_label' );
		$label = $reflection->invoke( $meta_box );

		$this->assertStringContainsString( 'Allow Easter Egg', $label );
	}

	public function test_get_filter_label_exclusive() {
		global $wp_options, $_GET;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'exclusive' );
		$_GET['post_type'] = 'post';

		$plugin = Plugin::get_instance();
		$meta_box = $plugin->get_meta_box();

		$reflection = new \ReflectionMethod( $meta_box, 'get_filter_label' );
		$label = $reflection->invoke( $meta_box );

		$this->assertStringContainsString( 'Do NOT allow Easter Egg', $label );
	}

	public function test_get_filter_label_off() {
		global $wp_options, $_GET;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'off' );
		$_GET['post_type'] = 'post';

		$plugin = Plugin::get_instance();
		$meta_box = $plugin->get_meta_box();

		$reflection = new \ReflectionMethod( $meta_box, 'get_filter_label' );
		$label = $reflection->invoke( $meta_box );

		$this->assertStringContainsString( 'Do NOT allow Easter Egg', $label );
	}

	public function test_meta_box_save_bails_on_autosave() {
		if ( ! defined( 'DOING_AUTOSAVE' ) ) {
			define( 'DOING_AUTOSAVE', true );
		} else {
			// Can't redefine, so we test with the existing value
			$this->assertTrue( true );
			return;
		}

		$plugin = Plugin::get_instance();
		$meta_box = $plugin->get_meta_box();

		$reflection = new \ReflectionMethod( $meta_box, 'meta_box_save' );
		$result = $reflection->invoke( $meta_box, 1 );

		$this->assertNull( $result );
	}

	public function test_meta_box_save_bails_without_nonce() {
		global $_POST;
		$_POST = array();

		$plugin = Plugin::get_instance();
		$meta_box = $plugin->get_meta_box();

		$reflection = new \ReflectionMethod( $meta_box, 'meta_box_save' );
		$result = $reflection->invoke( $meta_box, 1 );

		$this->assertNull( $result );
	}

	public function test_meta_box_save_saves_value() {
		global $wp_post_meta, $_POST;
		$_POST = array(
			'meta_box_nonce' => 'test-nonce',
			'_wpee_added_to_filter' => 'on',
		);

		$plugin = Plugin::get_instance();
		$meta_box = $plugin->get_meta_box();

		$reflection = new \ReflectionMethod( $meta_box, 'meta_box_save' );
		$reflection->invoke( $meta_box, 1 );

		$this->assertEquals( 'on', $wp_post_meta[1]['_wpee_added_to_filter'] );
	}
}
