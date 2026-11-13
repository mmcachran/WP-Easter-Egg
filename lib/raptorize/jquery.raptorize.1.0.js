/*
 * jQuery Raptorize Plugin 1.0
 * www.ZURB.com/playground
 * Copyright 2010, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

/**
 * WP Easter Egg modification: Added support for wpee_raptor_path
 * global variable to set the correct plugin URL for assets.
 */

( function( $ ) {

	$.fn.raptorize = function( options ) {

		// Defaults
		var defaults = {
			enterOn: 'click', // timer, konami-code, click
			delayTime: 5000 // time before raptor attacks on timer mode
		};

		// Extend defaults with options
		var options = $.extend( defaults, options );

		// Determine plugin URL for assets
		var raptor_path = '';
		if ( typeof window.wpee_raptor_path !== 'undefined' ) {
			raptor_path = window.wpee_raptor_path;
		}

		return this.each( function() {

			var _this = $( this );
			var audioSupported = false;

			// Browser checking for audio support
			if ( $.browser.mozilla && $.browser.version.substr( 0, 5 ) >= "1.9.2" || $.browser.webkit ) {
				audioSupported = true;
			}

			// Raptor Vars
			var raptorImageMarkup = '<img id="elRaptor" style="display: none" src="' + raptor_path + 'raptor.png" />';
			var raptorAudioMarkup = '<audio id="elRaptorShriek" preload="auto"><source src="' + raptor_path + 'raptor-sound.mp3" /><source src="' + raptor_path + 'raptor-sound.ogg" /></audio>';
			var locked = false;

			// Append Raptor and Style
			$( 'body' ).append( raptorImageMarkup );
			if ( audioSupported ) {
				$( 'body' ).append( raptorAudioMarkup );
			}
			var raptor = $( '#elRaptor' ).css( {
				"position": "fixed",
				"bottom": "-700px",
				"right": "0",
				"display": "block"
			} );

			// Animating Code
			function init() {
				locked = true;

				// Sound Hilarity
				if ( audioSupported ) {
					function playSound() {
						document.getElementById( 'elRaptorShriek' ).play();
					}
					playSound();
				}

				// Movement Hilarity
				raptor.animate( {
					"bottom": "0"
				}, function() {
					$( this ).animate( {
						"bottom": "-130px"
					}, 100, function() {
						var offset = ( $( this ).position().left ) + 400;
						$( this ).delay( 300 ).animate( {
							"right": offset
						}, 2200, function() {
							raptor = $( '#elRaptor' ).css( {
								"bottom": "-700px",
								"right": "0"
							} );
							locked = false;
						} );
					} );
				} );
			}

			// Determine Entrance
			if ( options.enterOn === 'timer' ) {
				setTimeout( init, options.delayTime );
			} else if ( options.enterOn === 'click' ) {
				_this.bind( 'click', function( e ) {
					e.preventDefault();
					if ( ! locked ) {
						init();
					}
				} );
			} else if ( options.enterOn === 'konami-code' ) {
				var kkeys = [], konami = "38,38,40,40,37,39,37,39,66,65";
				$( window ).bind( "keydown.raptorz", function( e ) {
					kkeys.push( e.keyCode );
					if ( kkeys.toString().indexOf( konami ) >= 0 ) {
						init();
						$( window ).unbind( 'keydown.raptorz' );
					}
				}, true );
			} else if ( options.enterOn === 'do-it' ) {
				init();
			}

		} ); // each call
	}; // raptorize plugin call

} )( jQuery );
