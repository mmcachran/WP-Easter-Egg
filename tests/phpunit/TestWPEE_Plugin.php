<?php
namespace WP_Easter_Egg\Tests;

use WP_Easter_Egg\Plugin;
use PHPUnit\Framework\TestCase;

class TestWPEE_Plugin extends TestCase {

	/**
	 * Test that get_instance returns a Plugin instance.
	 */
	public function test_get_instance_returns_plugin() {
		$instance = Plugin::get_instance();
		$this->assertInstanceOf( Plugin::class, $instance );
	}

	/**
	 * Test that get_instance returns the same instance (singleton).
	 */
	public function test_get_instance_is_singleton() {
		$instance1 = Plugin::get_instance();
		$instance2 = Plugin::get_instance();
		$this->assertSame( $instance1, $instance2 );
	}

	/**
	 * Test that the version constant is defined.
	 */
	public function test_version_constant() {
		$this->assertEquals( '3.0.0', Plugin::VERSION );
	}

	/**
	 * Test that get_version returns the correct version.
	 */
	public function test_get_version() {
		$plugin = Plugin::get_instance();
		$this->assertEquals( '3.0.0', $plugin->get_version() );
	}

	/**
	 * Test that get_name returns the plugin name.
	 */
	public function test_get_name() {
		$plugin = Plugin::get_instance();
		$this->assertNotEmpty( $plugin->get_name() );
	}

	/**
	 * Test that get_url returns a non-empty string.
	 */
	public function test_get_url() {
		$plugin = Plugin::get_instance();
		$this->assertIsString( $plugin->get_url() );
		$this->assertNotEmpty( $plugin->get_url() );
	}

	/**
	 * Test that get_path returns a non-empty string.
	 */
	public function test_get_path() {
		$plugin = Plugin::get_instance();
		$this->assertIsString( $plugin->get_path() );
		$this->assertNotEmpty( $plugin->get_path() );
	}

	/**
	 * Test that is_filter_enabled returns false when filter is off.
	 */
	public function test_is_filter_enabled_when_off() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'off' );

		$plugin = Plugin::get_instance();
		$this->assertFalse( $plugin->is_filter_enabled() );
	}

	/**
	 * Test that is_filter_enabled returns true when filter is exclusive.
	 */
	public function test_is_filter_enabled_when_exclusive() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'exclusive' );

		$plugin = Plugin::get_instance();
		$this->assertTrue( $plugin->is_filter_enabled() );
	}

	/**
	 * Test that is_filter_enabled returns true when filter is inclusive.
	 */
	public function test_is_filter_enabled_when_inclusive() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'inclusive' );

		$plugin = Plugin::get_instance();
		$this->assertTrue( $plugin->is_filter_enabled() );
	}

	/**
	 * Test that is_filter_enabled returns false when filter is not set.
	 */
	public function test_is_filter_enabled_when_not_set() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array();

		$plugin = Plugin::get_instance();
		$this->assertFalse( $plugin->is_filter_enabled() );
	}

	/**
	 * Test that fetch_option returns false for non-existent key.
	 */
	public function test_fetch_option_returns_false_for_missing_key() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'type' => 'konami' );

		$plugin = Plugin::get_instance();
		$this->assertFalse( $plugin->fetch_option( 'nonexistent' ) );
	}

	/**
	 * Test that fetch_option returns the correct value for existing key.
	 */
	public function test_fetch_option_returns_value() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'type' => 'custom' );

		$plugin = Plugin::get_instance();
		$this->assertEquals( 'custom', $plugin->fetch_option( 'type' ) );
	}

	/**
	 * Test that get_options returns an Options instance.
	 */
	public function test_get_options() {
		$plugin = Plugin::get_instance();
		$options = $plugin->get_options();
		$this->assertInstanceOf( \WP_Easter_Egg\Options::class, $options );
	}

	/**
	 * Test that get_meta_box returns a Meta_Box instance.
	 */
	public function test_get_meta_box() {
		$plugin = Plugin::get_instance();
		$meta_box = $plugin->get_meta_box();
		$this->assertInstanceOf( \WP_Easter_Egg\Meta_Box::class, $meta_box );
	}

	/**
	 * Test that get_frontend returns a Frontend instance.
	 */
	public function test_get_frontend() {
		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();
		$this->assertInstanceOf( \WP_Easter_Egg\Frontend::class, $frontend );
	}
}
