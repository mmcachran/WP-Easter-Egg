<?php
namespace WP_Easter_Egg\Tests;

use WP_Easter_Egg\Frontend;
use WP_Easter_Egg\Plugin;
use PHPUnit\Framework\TestCase;

class TestWPEE_Frontend extends TestCase {

	/**
	 * Test that compile_js_data returns all expected keys.
	 */
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

		// Use reflection to access private method.
		$reflection = new \ReflectionMethod( $frontend, 'compile_js_data' );
		$reflection->setAccessible( true );
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

	/**
	 * Test that is_allowed_on_post returns true when filter is off.
	 */
	public function test_is_allowed_on_post_filter_off() {
		global $wp_options, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'off' );
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$reflection->setAccessible( true );
		$result = $reflection->invoke( $frontend );

		$this->assertTrue( $result );
	}

	/**
	 * Test that is_allowed_on_post returns true for exclusive filter when post not added.
	 */
	public function test_is_allowed_on_post_exclusive_not_added() {
		global $wp_options, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'exclusive' );
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$reflection->setAccessible( true );
		$result = $reflection->invoke( $frontend );

		$this->assertTrue( $result );
	}

	/**
	 * Test that is_allowed_on_post returns false for exclusive filter when post is added.
	 */
	public function test_is_allowed_on_post_exclusive_added() {
		global $wp_options, $wp_post_meta, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'exclusive' );
		$wp_post_meta[1] = array( '_wpee_added_to_filter' => 'on' );
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$reflection->setAccessible( true );
		$result = $reflection->invoke( $frontend );

		$this->assertFalse( $result );
	}

	/**
	 * Test that is_allowed_on_post returns true for inclusive filter when post is added.
	 */
	public function test_is_allowed_on_post_inclusive_added() {
		global $wp_options, $wp_post_meta, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'inclusive' );
		$wp_post_meta[1] = array( '_wpee_added_to_filter' => 'on' );
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$reflection->setAccessible( true );
		$result = $reflection->invoke( $frontend );

		$this->assertTrue( $result );
	}

	/**
	 * Test that is_allowed_on_post returns false for inclusive filter when post not added.
	 */
	public function test_is_allowed_on_post_inclusive_not_added() {
		global $wp_options, $post;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'inclusive' );
		$post = new \stdClass();
		$post->ID = 1;

		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();

		$reflection = new \ReflectionMethod( $frontend, 'is_allowed_on_post' );
		$reflection->setAccessible( true );
		$result = $reflection->invoke( $frontend );

		$this->assertFalse( $result );
	}
}
