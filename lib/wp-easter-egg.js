/**
 * WP Easter Egg - Frontend JavaScript
 *
 * Handles key sequence detection and Easter Egg actions.
 *
 * @package WP_Easter_Egg
 */

window.WP_Easter_Egg = window.WP_Easter_Egg || {};

( function( window, document, app, undefined ) {
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
			'CapsLock': 20
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
				document.body.style.transform = 'rotate(' + rotation + 'deg)';
			},
			100
		);
	};

	/**
	 * Trigger Cornify effect.
	 */
	app.cornify = function() {
		var script = document.createElement( 'script' );
		script.src = 'https://www.cornify.com/js/cornify.js';
		script.onload = function() {
			cornify_add();
			document.addEventListener( 'keydown', cornify_add );
		};
		document.head.appendChild( script );
	};

	/**
	 * Trigger Raptorize effect (vanilla JS replacement for jQuery plugin).
	 */
	app.raptorize = function() {
		var raptor_path = app.l10n.raptor_path || '';
		var audioSupported = false;

		// Check for audio support
		var audio = new Audio();
		audioSupported = audio.canPlayType && audio.canPlayType( 'audio/mpeg' ) !== '';

		// Raptor Vars
		var raptorImageMarkup = '<img id="elRaptor" style="display: none" src="' + raptor_path + 'raptor.png" />';
		var raptorAudioMarkup = '<audio id="elRaptorShriek" preload="auto"><source src="' + raptor_path + 'raptor-sound.mp3" /><source src="' + raptor_path + 'raptor-sound.ogg" /></audio>';
		var locked = false;

		// Append Raptor and Style
		var tempDiv = document.createElement( 'div' );
		tempDiv.innerHTML = raptorImageMarkup;
		if ( audioSupported ) {
			tempDiv.innerHTML += raptorAudioMarkup;
		}
		document.body.appendChild( tempDiv.firstChild );
		if ( audioSupported && tempDiv.children.length > 1 ) {
			document.body.appendChild( tempDiv.children[1] );
		}

		var raptor = document.getElementById( 'elRaptor' );
		raptor.style.position = 'fixed';
		raptor.style.bottom = '-700px';
		raptor.style.right = '0';
		raptor.style.display = 'block';

		// Animating Code
		function init() {
			locked = true;

			// Sound Hilarity
			if ( audioSupported ) {
				var shriek = document.getElementById( 'elRaptorShriek' );
				if ( shriek ) {
					shriek.play();
				}
			}

			// Movement Hilarity
			raptor.style.transition = 'bottom 0.5s ease';
			raptor.style.bottom = '0';

			setTimeout( function() {
				raptor.style.transition = 'bottom 0.1s ease';
				raptor.style.bottom = '-130px';

				setTimeout( function() {
					var offset = raptor.offsetLeft + 400;
					raptor.style.transition = 'right 2.2s ease';
					raptor.style.right = offset + 'px';

					setTimeout( function() {
						raptor.style.bottom = '-700px';
						raptor.style.right = '0';
						locked = false;
					}, 2200 );
				}, 100 );
			}, 500 );
		}

		// Determine Entrance - 'do-it' mode (triggered from frontend)
		init();
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

		var window_width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );
		var window_height = Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );
		var css = {
			position: 'absolute'
		};

		switch ( position ) {
			case 'bottom':
				css.bottom = '5px';
				css.left = '0';
				break;

			case 'middle':
				css.left = '50%';
				css.top = '50%';
				css.transform = 'translate(-50%, -50%)';
				break;

			case 'top':
				css.width = '250px';
				css.height = '250px';
				css.left = '50%';
				css.top = '50%';
				css.transform = 'translate(-50%, -50%)';
				break;
		}

		var wpee_image = document.createElement( 'img' );
		wpee_image.src = app.l10n.image;
		for ( var prop in css ) {
			if ( css.hasOwnProperty( prop ) ) {
				wpee_image.style[ prop ] = css[ prop ];
			}
		}
		document.body.appendChild( wpee_image );

		// Animate across screen
		var start = null;
		var duration = 5000;
		var distance = window_width + 250;

		function animate( timestamp ) {
			if ( !start ) start = timestamp;
			var progress = timestamp - start;
			var p = Math.min( progress / duration, 1 );
			wpee_image.style.left = ( p * distance ) + 'px';

			if ( p < 1 ) {
				requestAnimationFrame( animate );
			} else {
				wpee_image.remove();
			}
		}

		requestAnimationFrame( animate );
	};

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', app.init );
	} else {
		app.init();
	}

	return app;

} )( window, document, window.WP_Easter_Egg );
