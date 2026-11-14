/**
 * WP Easter Egg - Frontend JavaScript
 *
 * Handles key sequence detection and Easter Egg actions.
 *
 * @package WP_Easter_Egg
 */

window.WP_Easter_Egg = window.WP_Easter_Egg || {};

( function( window, document, $, app, undefined ) {
	'use strict';

	app.l10n = window.wpee_config || {};

	/**
	 * Cache DOM references.
	 */
	app.cache = function() {
		app.$ = {};
	};

	/**
	 * Initialize the Easter Egg.
	 */
	app.init = function() {
		app.cache();

		if ( window.addEventListener ) {
			app.bind_keydown();
		}
	};

	/**
	 * Bind the keydown event listener for key sequence detection.
	 */
	/**
	 * Map modern event.key values to legacy keyCode numbers for compatibility.
	 *
	 * @param {string} key - The event.key value.
	 * @returns {number} The corresponding keyCode number.
	 */
	app.keyToKeyCode = function( key ) {
		var keyMap = {
			'ArrowUp': 38,
			'ArrowDown': 40,
			'ArrowLeft': 37,
			'ArrowRight': 39,
			'B': 66,
			'A': 65,
			'Enter': 13,
			' ': 32,
			'Escape': 27,
			'Tab': 9,
			'Shift': 16,
			'Control': 17,
			'Alt': 18,
			'CapsLock': 20,
		};
		return keyMap[ key ] || 0;
	};

	app.bind_keydown = function() {
		var keys = [];
		var code = app.l10n.type === 'custom' ? app.l10n.custom_code : '38,38,40,40,37,39,37,39,66,65';

		code = app.normalize_code( code );

		window.addEventListener(
			'keydown',
			function( event ) {
				var keyCode = app.keyToKeyCode( event.key );
				if ( 0 === keyCode ) {
					return;
				}
				keys.push( keyCode );

				var keys_string = app.normalize_code( keys.toString() );

				if ( keys_string.indexOf( code ) >= 0 ) {
					app.trigger_action();
					keys = [];
				}
			},
			true
		);
	};

	/**
	 * Normalize a keycode string by ensuring leading/trailing commas.
	 *
	 * @param {string} string - The keycode string to normalize.
	 * @returns {string} The normalized keycode string.
	 */
	app.normalize_code = function( string ) {
		if ( ',' !== string.charAt( 0 ) ) {
			string = ',' + string;
		}

		if ( ',' !== string.charAt( string.length - 1 ) ) {
			string += ',';
		}

		return string;
	};

	/**
	 * Trigger the configured Easter Egg action.
	 */
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

			default:
				break;
		}
	};

	/**
		* Rotate the screen randomly.
		*/
	app.rotate_screen = function() {
		var rotation = 0;
		var interval = setInterval(
			function() {
				rotation += 15;
				if ( rotation >= 360 ) {
					clearInterval( interval );
					return;
				}
				$( 'body' ).css( { transform: 'rotate(' + rotation + 'deg)' } );
			},
			100
		);
	};

	/**
	 * Trigger Cornify effect.
	 */
	app.cornify = function() {
		$.getScript(
			'https://www.cornify.com/js/cornify.js',
			function() {
				cornify_add();
				$( document ).keydown( cornify_add );
			}
		);
	};

	/**
	 * Trigger Raptorize effect.
	 */
	app.raptorize = function() {
		if ( $.fn.raptorize ) {
			window.wpee_raptor_path = app.l10n.raptor_path || '';
			$( 'body' ).raptorize( {
				delayTime : 1000,
				enterOn   : 'do-it'
			} );
		}
	};

	/**
	 * Move an image across the screen at a specified vertical position.
	 *
	 * @param {string} position - 'top', 'middle', or 'bottom'.
	 */
	app.move_image_across = function( position ) {
		if ( ! app.l10n.image || '' === app.l10n.image ) {
			return;
		}

		var width = '+=' + $( document ).width();
		var window_height = Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );
		var css = {
			position: 'absolute',
		};

		switch ( position ) {
			case 'bottom':
				css.bottom = '5px';
				break;

			case 'middle':
				css.left   = '50%';
				css.top    = '50%';
				break;

			case 'top':
				css.width         = '250px';
				css.height        = '250px';
				css.left          = '50%';
				css.top           = '50%';
				css[ 'margin-left' ] = '-' + ( window_width / 2 ) + 'px';
				css[ 'margin-top' ]  = '-' + ( window_height / 2 ) + 'px';
				break;
		}

		var window_width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );

		if ( 'top' === position ) {
			css[ 'margin-left' ] = '-' + ( window_width / 2 ) + 'px';
			css[ 'margin-top' ]  = '-' + ( window_height / 2 ) + 'px';
		}

		var wpee_image = $( '<img />' )
			.attr( 'src', app.l10n.image )
			.css( css )
			.appendTo( 'body' )
			.animate(
				{ left: width },
				5000,
				function() {
					wpee_image.remove();
				}
			);
	};

	$( document ).ready( app.init );

	return app;

} )( window, document, jQuery, window.WP_Easter_Egg );
