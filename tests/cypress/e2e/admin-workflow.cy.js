/**
 * WP Easter Egg - Admin Workflow E2E Tests
 *
 * Tests the complete admin workflow:
 * 1. Install and activate plugin
 * 2. Navigate to settings
 * 3. Configure Easter Egg options
 * 4. Save settings
 * 5. Verify settings are persisted
 */

describe( 'WP Easter Egg Admin Workflow', () => {

	beforeEach( () => {
		cy.wpLogin();
	} );

	it( 'should navigate to WP Easter Egg settings page', () => {
		cy.navigateToWpeeSettings();
		cy.get( 'h1' ).should( 'contain', 'WP Easter Egg Plugin' );
		cy.get( 'select[name="wp_easter_egg_settings[filter]"]' ).should( 'exist' );
		cy.get( 'select[name="wp_easter_egg_settings[type]"]' ).should( 'exist' );
		cy.get( 'select[name="wp_easter_egg_settings[action]"]' ).should( 'exist' );
	} );

	it( 'should save and persist Easter Egg settings', () => {
		cy.navigateToWpeeSettings();
		cy.configureWpee( {
			filter: 'exclusive',
			type: 'custom',
			custom_code: '38,38,40,40,37,39,37,39,66,65',
			action: 'cornify',
		} );

		// Verify settings are reflected in the form
		cy.get( 'select[name="wp_easter_egg_settings[filter]"]' ).should( 'have.value', 'exclusive' );
		cy.get( 'select[name="wp_easter_egg_settings[type]"]' ).should( 'have.value', 'custom' );
		cy.get( 'input[name="wp_easter_egg_settings[custom_code]"]' ).should( 'have.value', '38,38,40,40,37,39,37,39,66,65' );
		cy.get( 'select[name="wp_easter_egg_settings[action]"]' ).should( 'have.value', 'cornify' );
	} );

	it( 'should show/hide custom code field based on type selection', () => {
		cy.navigateToWpeeSettings();

		// Default: konami type, custom code hidden
		cy.get( 'input[name="wp_easter_egg_settings[custom_code]"]' ).closest( 'tr' ).should( 'not.be.visible' );

		// Switch to custom type
		cy.get( 'select[name="wp_easter_egg_settings[type]"]' ).select( 'custom' );
		cy.get( 'input[name="wp_easter_egg_settings[custom_code]"]' ).closest( 'tr' ).should( 'be.visible' );
	} );

	it( 'should show/hide custom JS field based on action selection', () => {
		cy.navigateToWpeeSettings();

		// Default: rotate_screen, custom JS hidden
		cy.get( 'textarea[name="wp_easter_egg_settings[custom_js]"]' ).closest( 'tr' ).should( 'not.be.visible' );

		// Switch to custom_js action
		cy.get( 'select[name="wp_easter_egg_settings[action]"]' ).select( 'custom_js' );
		cy.get( 'textarea[name="wp_easter_egg_settings[custom_js]"]' ).closest( 'tr' ).should( 'be.visible' );
	} );

	it( 'should show/hide image uploader based on action selection', () => {
		cy.navigateToWpeeSettings();

		// Default: rotate_screen, image uploader hidden
		cy.get( 'div.uploader' ).closest( 'tr' ).should( 'not.be.visible' );

		// Switch to move_image_across_bottom
		cy.get( 'select[name="wp_easter_egg_settings[action]"]' ).select( 'move_image_across_bottom' );
		cy.get( 'div.uploader' ).closest( 'tr' ).should( 'be.visible' );
	} );

	it( 'should save settings with image URL', () => {
		cy.navigateToWpeeSettings();
		cy.configureWpee( {
			action: 'move_image_across_bottom',
			image: 'https://example.com/test-image.png',
		} );

		cy.get( 'input[name="wp_easter_egg_settings[image]"]' ).should( 'have.value', 'https://example.com/test-image.png' );
	} );

} );
