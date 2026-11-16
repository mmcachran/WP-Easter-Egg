<?php
namespace WP_Easter_Egg\Tests;

use WP_Easter_Egg\Plugin;
use PHPUnit\Framework\TestCase;

class WPEE_PluginTest extends TestCase {

	public function test_get_instance_returns_plugin() {
		$instance = Plugin::get_instance();
		$this->assertInstanceOf( Plugin::class, $instance );
	}

	public function test_get_instance_is_singleton() {
		$instance1 = Plugin::get_instance();
		$instance2 = Plugin::get_instance();
		$this->assertSame( $instance1, $instance2 );
	}

	public function test_version_constant() {
		$this->assertEquals( '4.0.0', Plugin::VERSION );
	}

	public function test_get_version() {
		$plugin = Plugin::get_instance();
		$this->assertEquals( '4.0.0', $plugin->get_version() );
	}

	public function test_get_name() {
		$plugin = Plugin::get_instance();
		$this->assertNotEmpty( $plugin->get_name() );
	}

	public function test_get_url() {
		$plugin = Plugin::get_instance();
		$this->assertIsString( $plugin->get_url() );
		$this->assertNotEmpty( $plugin->get_url() );
	}

	public function test_get_path() {
		$plugin = Plugin::get_instance();
		$this->assertIsString( $plugin->get_path() );
		$this->assertNotEmpty( $plugin->get_path() );
	}

	public function test_is_filter_enabled_when_off() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'off' );

		$plugin = Plugin::get_instance();
		$this->assertFalse( $plugin->is_filter_enabled() );
	}

	public function test_is_filter_enabled_when_exclusive() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'exclusive' );

		$plugin = Plugin::get_instance();
		$this->assertTrue( $plugin->is_filter_enabled() );
	}

	public function test_is_filter_enabled_when_inclusive() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'filter' => 'inclusive' );

		$plugin = Plugin::get_instance();
		$this->assertTrue( $plugin->is_filter_enabled() );
	}

	public function test_is_filter_enabled_when_not_set() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array();

		$plugin = Plugin::get_instance();
		$this->assertFalse( $plugin->is_filter_enabled() );
	}

	public function test_fetch_option_returns_false_for_missing_key() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'type' => 'konami' );

		$plugin = Plugin::get_instance();
		$this->assertFalse( $plugin->fetch_option( 'nonexistent' ) );
	}

	public function test_fetch_option_returns_value() {
		global $wp_options;
		$wp_options['wp_easter_egg_settings'] = array( 'type' => 'custom' );

		$plugin = Plugin::get_instance();
		$this->assertEquals( 'custom', $plugin->fetch_option( 'type' ) );
	}

	public function test_get_options() {
		$plugin = Plugin::get_instance();
		$options = $plugin->get_options();
		$this->assertInstanceOf( \WP_Easter_Egg\Options::class, $options );
	}

	public function test_get_meta_box() {
		$plugin = Plugin::get_instance();
		$meta_box = $plugin->get_meta_box();
		$this->assertInstanceOf( \WP_Easter_Egg\Meta_Box::class, $meta_box );
	}

	public function test_get_frontend() {
		$plugin = Plugin::get_instance();
		$frontend = $plugin->get_frontend();
		$this->assertInstanceOf( \WP_Easter_Egg\Frontend::class, $frontend );
	}
}
