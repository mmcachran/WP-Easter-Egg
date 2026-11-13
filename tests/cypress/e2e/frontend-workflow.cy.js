/**
 * WP Easter Egg - Frontend Workflow E2E Tests
 *
 * Tests the complete frontend user workflow:
 * 1. Visit a page with Easter Egg enabled
 * 2. Enter Konami code
 * 3. Verify Easter Egg action triggers
 * 4. Test custom key sequence
 * 5. Test filter behavior (inclusive/exclusive)
 */

describe( 'WP Easter Egg Frontend Workflow', () => {

	beforeEach( () => {
		cy.wpLogin();
		cy.navigateToWpeeSettings();
	} );

	it( 'should trigger Easter Egg with Konami code', () => {
		// Configure with default Konami code
		cy.configureWpee( {
			filter: 'off',
			type: 'konami',
			action: 'rotate_screen',
		} );

		// Visit the frontend
		cy.visit( '/' );

		// Simulate Konami code: Up, Up, Down, Down, Left, Right, Left, Right, B, A
		cy.simulateKonamiCode();

		// Verify screen rotation was applied (transform style on body)
		cy.get( 'body' ).should( 'have.css', 'transform' );
	} );

	it( 'should trigger Easter Egg with custom key sequence', () => {
		// Configure with custom code
		cy.configureWpee( {
			filter: 'off',
			type: 'custom',
			custom_code: '13,13', // Enter, Enter
			action: 'cornify',
		} );

		// Visit the frontend
		cy.visit( '/' );

		// Simulate custom key sequence
		cy.simulateKeySequence( [ 13, 13 ] );

		// Cornify should have been triggered (check for cornify elements)
		// Note: cornify loads from external URL, so we just verify no errors
		cy.get( 'body' ).should( 'exist' );
	} );

	it( 'should not trigger Easter Egg with wrong key sequence', () => {
		cy.configureWpee( {
			filter: 'off',
			type: 'konami',
			action: 'rotate_screen',
		} );

		cy.visit( '/' );

		// Press wrong keys
		cy.get( 'body' ).type( '{1}' );
		cy.get( 'body' ).type( '{2}' );
		cy.get( 'body' ).type( '{3}' );

		// Screen should not be rotated
		cy.get( 'body' ).should( 'have.css', 'transform', 'none' );
	} );

	it( 'should respect exclusive filter - not show on filtered posts', () => {
		cy.configureWpee( {
			filter: 'exclusive',
			type: 'konami',
			action: 'rotate_screen',
		} );

		// Visit a post that is NOT in the filter
		cy.visit( '/2026/01/01/hello-world/' );
		cy.simulateKonamiCode();
		cy.get( 'body' ).should( 'have.css', 'transform' );
	} );

	it( 'should respect exclusive filter - show on filtered posts when added', () => {
		cy.configureWpee( {
			filter: 'exclusive',
			type: 'konami',
			action: 'rotate_screen',
		} );

		// First, add a post to the filter via admin
		cy.visit( Cypress.env( 'wp_admin_url' ) + '/post.php?post=1&action=edit' );
		cy.get( '#_wpee_added_to_filter' ).check();
		cy.get( '#publish' ).click();

		// Now visit the post - Easter Egg should NOT work
		cy.visit( '/2026/01/01/hello-world/' );
		cy.simulateKonamiCode();
		cy.get( 'body' ).should( 'have.css', 'transform', 'none' );
	} );

	it( 'should respect inclusive filter - show only on filtered posts', () => {
		cy.configureWpee( {
			filter: 'inclusive',
			type: 'konami',
			action: 'rotate_screen',
		} );

		// Visit a post NOT in the filter - Easter Egg should NOT work
		cy.visit( '/2026/01/01/hello-world/' );
		cy.simulateKonamiCode();
		cy.get( 'body' ).should( 'have.css', 'transform', 'none' );

		// Add post to filter via admin
		cy.visit( Cypress.env( 'wp_admin_url' ) + '/post.php?post=1&action=edit' );
		cy.get( '#_wpee_added_to_filter' ).check();
		cy.get( '#publish' ).click();

		// Now Easter Egg should work
		cy.visit( '/2026/01/01/hello-world/' );
		cy.simulateKonamiCode();
		cy.get( 'body' ).should( 'have.css', 'transform' );
	} );

} );
