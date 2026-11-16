<?php
namespace WP_Easter_Egg\Tests;

use WP_Easter_Egg\Frontend;
use WP_Easter_Egg\Plugin;
use PHPUnit\Framework\TestCase;

class WPEE_FrontendTest extends TestCase {

	public function test_compile_js_data_returns_all_keys() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array(
			'type'        => 'custom',
			'custom_code' => '38,38,40,40',
			'action'      => 'cornify',
			'custom_js'   => 'alert("hello");',
			'image'       => 'https://example.com/image.png',
		);

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'compile_js_data' );
		$data = $reflection->invoke( $frontend );

		$this->assertArrayHasKey( 'type', $data );
		$this->assertArrayHasKey( 'custom_code', $data );
		$this->assertArrayHasKey( 'action', $data );
		$this->assertArrayHasKey( 'custom_js', $data );
		$this->assertArrayHasKey( 'image', $data );
		$this->assertArrayHasKey( 'raptor_path', $data );

		$this->assertEquals( 'custom', $data['type'] );
		$this->assertEquals( '38,38,40,40', $data['custom_code'] );
		$this->assertEquals( 'cornify', $data['action'] );
		$this->assertEquals( 'alert("hello");', $data['custom_js'] );
		$this->assertEquals( 'https://example.com/image.png', $data['image'] );
	}

	public function test_is_allowed_on_post_filter_off() {
		global $wp_options, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'off' );
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$result = $reflection->invoke( $frontend );

		$this->assertTrue( $result );
	}

	public function test_is_allowed_on_post_exclusive_not_added() {
		global $wp_options, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'exclusive' );
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$result = $reflection->invoke( $frontend );

		$this->assertTrue( $result );
	}

	public function test_is_allowed_on_post_exclusive_added() {
		global $wp_options, $wp_post_meta, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'exclusive' );
		$wp_post_meta[1] = array( '_wpee_added_to_filter' => 'on' );
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$result = $reflection->invoke( $frontend );

		$this->assertFalse( $result );
	}

	public function test_is_allowed_on_post_inclusive_added() {
		global $wp_options, $wp_post_meta, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'inclusive' );
		$wp_post_meta[1] = array( '_wpee_added_to_filter' => 'on' );
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$result = $reflection->invoke( $frontend );

		$this->assertTrue( $result );
	}

	public function test_is_allowed_on_post_inclusive_not_added() {
		global $wp_options, $wp_post_meta, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'inclusive' );
		$wp_post_meta = array();
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$result = $reflection->invoke( $frontend );

		$this->assertFalse( $result );
	}
}
