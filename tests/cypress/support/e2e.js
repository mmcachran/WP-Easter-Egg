// Cypress e2e support file for WP Easter Egg tests.
// Contains custom commands and setup for user workflow testing.

import './commands';

// Custom command: Log in to WordPress admin
Cypress.Commands.add( 'wpLogin', ( user = Cypress.env( 'wp_admin_user' ), pass = Cypress.env( 'wp_admin_password' ) ) => {
	cy.visit( Cypress.env( 'wp_admin_url' ) );
	cy.get( '#user_login' ).type( user );
	cy.get( '#user_pass' ).type( pass );
	cy.get( '#wp-submit' ).click();
	cy.url().should( 'include', '/wp-admin/' );
} );

// Custom command: Navigate to WP Easter Egg settings
Cypress.Commands.add( 'navigateToWpeeSettings', () => {
	cy.get( '.wp-menu-name' ).contains( 'Settings' ).click();
	cy.get( '.wp-submenu-wrap' ).find( 'a' ).contains( 'WP Easter Egg' ).click();
	cy.url().should( 'include', 'page=wp_easter_egg_plugin' );
} );

// Custom command: Configure Easter Egg settings
Cypress.Commands.add( 'configureWpee', ( settings = {} ) => {
	const defaults = {
		filter: 'off',
		type: 'konami',
		action: 'rotate_screen',
		custom_code: '',
		custom_js: '',
		image: '',
	};
	const config = { ...defaults, ...settings };

	cy.get( 'select[name="wp_easter_egg_settings[filter]"]' ).select( config.filter );
	cy.get( 'select[name="wp_easter_egg_settings[type]"]' ).select( config.type );

	if ( config.type === 'custom' && config.custom_code ) {
		cy.get( 'input[name="wp_easter_egg_settings[custom_code]"]' ).clear().type( config.custom_code );
	}

	cy.get( 'select[name="wp_easter_egg_settings[action]"]' ).select( config.action );

	if ( config.action === 'custom_js' && config.custom_js ) {
		cy.get( 'textarea[name="wp_easter_egg_settings[custom_js]"]' ).clear().type( config.custom_js );
	}

	if ( config.image ) {
		cy.get( 'input[name="wp_easter_egg_settings[image]"]' ).clear().type( config.image );
	}

	cy.get( '#submit' ).click();
	cy.get( '.updated' ).should( 'exist' );
} );

// Custom command: Simulate Konami code key sequence
Cypress.Commands.add( 'simulateKonamiCode', () => {
	const konami = [ 38, 38, 40, 40, 37, 39, 37, 39, 66, 65 ];
	konami.forEach( ( keycode ) => {
		cy.get( 'body' ).type( '{' + keycode + '}' );
	} );
} );

// Custom command: Simulate custom key sequence
Cypress.Commands.add( 'simulateKeySequence', ( keycodes ) => {
	keycodes.forEach( ( keycode ) => {
		cy.get( 'body' ).type( '{' + keycode + '}' );
	} );
} );
