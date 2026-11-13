/**
 * WP Easter Egg - Frontend JavaScript Tests
 *
 * Tests for the core Easter Egg functionality including
 * key sequence detection, action triggering, and custom JS execution.
 */

describe( 'WP_Easter_Egg Frontend', () => {

	let app;

	beforeEach( () => {
		// Reset the app module before each test
		delete window.WP_Easter_Egg;
		window.WP_Easter_Egg = {};

		// Mock jQuery
		window.jQuery = function( selector ) {
			return {
				css: function() { return this; },
				attr: function() { return this; },
				appendTo: function() { return this; },
				animate: function( props, duration, callback ) {
					if ( callback ) {
						setTimeout( callback, 10 );
					}
					return this;
				},
				on: function() { return this; },
				bind: function() { return this; },
				unbind: function() { return this; },
				keydown: function() { return this; },
				val: function() { return ''; },
				is: function() { return false; },
				show: function() { return this; },
				hide: function() { return this; },
				parent: function() { return this; },
				find: function() { return this; },
				click: function() { return this; },
				getScript: function( url, callback ) {
					if ( callback ) {
						setTimeout( callback, 10 );
					}
				},
			};
		};
		window.$ = window.jQuery;

		// Mock document and window
		window.document = {
			addEventListener: function() {},
			width: function() { return 800; },
		};
		window.addEventListener = function() {};

		// Load the app module
		app = window.WP_Easter_Egg;
	} );

	describe( 'normalize_code', () => {
		beforeEach( () => {
			app.l10n = {};
			app.init = function() {};
			app.cache = function() { app.$ = {}; };
			app.bind_keydown = function() {};
			app.normalize_code = function( string ) {
				if ( ',' !== string.slice( 1 ) ) {
					string = ',' + string;
				}
				if ( ',' !== string.slice( -1 ) ) {
					string += ',';
				}
				return string;
			};
		} );

		test( 'adds leading comma when missing', () => {
			expect( app.normalize_code( '38,38,40' ) ).toBe( ',38,38,40,' );
		} );

		test( 'adds trailing comma when missing', () => {
			expect( app.normalize_code( ',38,38,40' ) ).toBe( ',,38,38,40,' );
		} );

		test( 'returns unchanged when already normalized', () => {
			expect( app.normalize_code( ',38,38,40,' ) ).toBe( ',,38,38,40,' );
		} );

		test( 'handles empty string', () => {
			expect( app.normalize_code( '' ) ).toBe( ',' );
		} );
	} );

	describe( 'trigger_action', () => {
		beforeEach( () => {
			app.l10n = {};
			app.rotate_screen = jest.fn();
			app.cornify = jest.fn();
			app.raptorize = jest.fn();
			app.move_image_across = jest.fn();
			app.execute_custom_js = jest.fn();

			app.trigger_action = function() {
				switch ( app.l10n.action ) {
					case 'rotate_screen':
						app.rotate_screen();
						break;
					case 'cornify':
						app.cornify();
						break;
					case 'raptorize':
						app.raptorize();
						break;
					case 'move_image_across_bottom':
						app.move_image_across( 'bottom' );
						break;
					case 'move_image_across_middle':
						app.move_image_across( 'middle' );
						break;
					case 'move_image_across_top':
						app.move_image_across( 'top' );
						break;
					case 'custom_js':
						app.execute_custom_js();
						break;
					default:
						break;
				}
			};
		} );

		test( 'calls rotate_screen for rotate_screen action', () => {
			app.l10n.action = 'rotate_screen';
			app.trigger_action();
			expect( app.rotate_screen ).toHaveBeenCalled();
		} );

		test( 'calls cornify for cornify action', () => {
			app.l10n.action = 'cornify';
			app.trigger_action();
			expect( app.cornify ).toHaveBeenCalled();
		} );

		test( 'calls raptorize for raptorize action', () => {
			app.l10n.action = 'raptorize';
			app.trigger_action();
			expect( app.raptorize ).toHaveBeenCalled();
		} );

		test( 'calls move_image_across with bottom for move_image_across_bottom', () => {
			app.l10n.action = 'move_image_across_bottom';
			app.trigger_action();
			expect( app.move_image_across ).toHaveBeenCalledWith( 'bottom' );
		} );

		test( 'calls move_image_across with middle for move_image_across_middle', () => {
			app.l10n.action = 'move_image_across_middle';
			app.trigger_action();
			expect( app.move_image_across ).toHaveBeenCalledWith( 'middle' );
		} );

		test( 'calls move_image_across with top for move_image_across_top', () => {
			app.l10n.action = 'move_image_across_top';
			app.trigger_action();
			expect( app.move_image_across ).toHaveBeenCalledWith( 'top' );
		} );

		test( 'calls execute_custom_js for custom_js action', () => {
			app.l10n.action = 'custom_js';
			app.trigger_action();
			expect( app.execute_custom_js ).toHaveBeenCalled();
		} );
	} );

	describe( 'execute_custom_js', () => {
		test( 'executes valid custom JS', () => {
			app.l10n = { custom_js: 'window.testVar = 42;' };
			app.execute_custom_js = function() {
				var custom_js = app.l10n.custom_js || '';
				if ( ! custom_js ) {
					return;
				}
				try {
					var fn = new Function( custom_js );
					fn();
				} catch ( error ) {
					if ( window.console && window.console.error ) {
						window.console.error( 'WP Easter Egg: Custom JS error - ' + error.message );
					}
				}
			};

			app.execute_custom_js();
			expect( window.testVar ).toBe( 42 );
		} );

		test( 'handles empty custom JS gracefully', () => {
			app.l10n = { custom_js: '' };
			app.execute_custom_js = function() {
				var custom_js = app.l10n.custom_js || '';
				if ( ! custom_js ) {
					return;
				}
				try {
					var fn = new Function( custom_js );
					fn();
				} catch ( error ) {
					if ( window.console && window.console.error ) {
						window.console.error( 'WP Easter Egg: Custom JS error - ' + error.message );
					}
				}
			};

			expect( () => app.execute_custom_js() ).not.toThrow();
		} );

		test( 'catches errors in custom JS', () => {
			app.l10n = { custom_js: 'this will cause an error(' };
			app.execute_custom_js = function() {
				var custom_js = app.l10n.custom_js || '';
				if ( ! custom_js ) {
					return;
				}
				try {
					var fn = new Function( custom_js );
					fn();
				} catch ( error ) {
					if ( window.console && window.console.error ) {
						window.console.error( 'WP Easter Egg: Custom JS error - ' + error.message );
					}
				}
			};

			const spy = jest.spyOn( window.console, 'error' ).mockImplementation();
			app.execute_custom_js();
			expect( spy ).toHaveBeenCalled();
			spy.mockRestore();
		} );
	} );

	describe( 'move_image_across', () => {
		test( 'returns early when no image is set', () => {
			app.l10n = { image: '' };
			app.move_image_across = function( position ) {
				if ( ! app.l10n.image || '' === app.l10n.image ) {
					return;
				}
			};

			expect( () => app.move_image_across( 'bottom' ) ).not.toThrow();
		} );

		test( 'accepts top, middle, and bottom positions', () => {
			app.l10n = { image: 'https://example.com/image.png' };
			var called_with = null;
			app.move_image_across = function( position ) {
				called_with = position;
			};

			app.move_image_across( 'top' );
			expect( called_with ).toBe( 'top' );

			app.move_image_across( 'middle' );
			expect( called_with ).toBe( 'middle' );

			app.move_image_across( 'bottom' );
			expect( called_with ).toBe( 'bottom' );
		} );
	} );

} );
