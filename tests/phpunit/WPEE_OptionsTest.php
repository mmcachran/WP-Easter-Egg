<?php
namespace WP_Easter_Egg\Tests;

use WP_Easter_Egg\Options;
use WP_Easter_Egg\Plugin;
use PHPUnit\Framework\TestCase;

class WPEE_OptionsTest extends TestCase {

	public function test_sanitize_settings_defaults() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array();

		$plugin = Plugin::get_instance();
		$options = $plugin->get_options();

		$input = array();
		$result = $options->sanitize_settings( $input );

		$this->assertEquals( 'off', $result['filter'] );
		$this->assertEquals( 'konami', $result['type'] );
		$this->assertEquals( '', $result['custom_code'] );
		$this->assertEquals( 'rotate_screen', $result['action'] );
		$this->assertEquals( '', $result['custom_js'] );
		$this->assertEquals( '', $result['image'] );
	}

	public function test_sanitize_settings_filter() {
		$options = new Options( null );

		$result = $options->sanitize_settings( array( 'filter' => 'exclusive' ) );
		$this->assertEquals( 'exclusive', $result['filter'] );

		$result = $options->sanitize_settings( array( 'filter' => 'invalid' ) );
		$this->assertEquals( 'off', $result['filter'] );
	}

	public function test_sanitize_settings_type() {
		$options = new Options( null );

		$result = $options->sanitize_settings( array( 'type' => 'custom' ) );
		$this->assertEquals( 'custom', $result['type'] );

		$result = $options->sanitize_settings( array( 'type' => 'invalid' ) );
		$this->assertEquals( 'konami', $result['type'] );
	}

	public function test_sanitize_settings_action() {
		$options = new Options( null );

		$result = $options->sanitize_settings( array( 'action' => 'cornify' ) );
		$this->assertEquals( 'cornify', $result['action'] );

		$result = $options->sanitize_settings( array( 'action' => 'invalid_action' ) );
		$this->assertEquals( 'rotate_screen', $result['action'] );
	}

	public function test_sanitize_settings_custom_code() {
		$options = new Options( null );

		$result = $options->sanitize_settings( array( 'custom_code' => '38,38,40,40,37,39,37,39,66,65' ) );
		$this->assertEquals( '38,38,40,40,37,39,37,39,66,65', $result['custom_code'] );

		$result = $options->sanitize_settings( array( 'custom_code' => '38,abc,40' ) );
		$this->assertEquals( '38,40', $result['custom_code'] );
	}

	public function test_sanitize_settings_image() {
		$options = new Options( null );

		$result = $options->sanitize_settings( array( 'image' => 'https://example.com/image.png' ) );
		$this->assertEquals( 'https://example.com/image.png', $result['image'] );
	}

	public function test_fetch_option_missing_key() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'type' => 'konami' );

		$plugin = Plugin::get_instance();
		$options = $plugin->get_options();

		$this->assertFalse( $options->fetch_option( 'nonexistent' ) );
	}

	public function test_fetch_option_existing_key() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'type' => 'custom' );

		$plugin = Plugin::get_instance();
		$options = $plugin->get_options();

		$this->assertEquals( 'custom', $options->fetch_option( 'type' ) );
	}
}
