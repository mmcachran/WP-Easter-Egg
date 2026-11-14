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
	app.bind_keydown = function() {
		var keys = [];
		var code = app.l10n.type === 'custom' ? app.l10n.custom_code : '38,38,40,40,37,39,37,39,66,65';

		code = app.normalize_code( code );

		window.addEventListener(
			'keydown',
			function( event ) {
				keys.push( event.keyCode );

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

			case 'custom_js':
				app.execute_custom_js();
				break;

			default:
				break;
		}
	};

	/**
	 * Execute custom JavaScript from settings.
	 *
	 * Uses a Function constructor instead of eval() for better security.
	 */
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

	/**
	 * Rotate the screen randomly.
	 */
	app.rotate_screen = function() {
		setInterval(
			function() {
				var theta = Math.PI * Math.random();
				$( 'body' ).css( { transform: 'rotate(' + theta + 'rad)' } );
			},
			500
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
