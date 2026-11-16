/**
 * WP Easter Egg - Admin JavaScript
 *
 * Handles admin UI interactions for the Easter Egg settings page.
 *
 * @package WP_Easter_Egg
 */

window.WP_Easter_Egg_Admin = window.WP_Easter_Egg_Admin || {};

( function( window, document, app, undefined ) {
	'use strict';

	app.l10n = window.wpee_admin_config || {};

	/**
	 * Cache DOM references and event configurations.
	 */
	app.cache = function() {
		app.$ = {};

		app.events = {
			change: {
				'select[name="wp_easter_egg_settings[action]"]': function( event ) {
					app.show_or_hide_custom_js();
					app.show_or_hide_uploader();
				},
				'select[name="wp_easter_egg_settings[type]"]': function( event ) {
					app.show_or_hide_custom_code();
				},
				'input[name="wp_easter_egg_settings[image]"]': function( event ) {
					app.show_or_hide_image_preview();
				}
			},
			keydown: {
				'input[name="wp_easter_egg_settings[custom_code]"]': function( event ) {
					if ( ! document.querySelector( '#keycode-helper' ).checked ) {
						return;
					}

					if ( 8 === event.keyCode ) {
						return;
					}

					var custom_code_input = document.querySelector( 'input[name="wp_easter_egg_settings[custom_code]"]' );
					var keycode = event.keyCode;

					if ( '' !== custom_code_input.value ) {
						keycode = ',' + keycode;
					}

					custom_code_input.value = custom_code_input.value + keycode;
					return false;
				}
			}
		};
	};

	/**
	 * Initialize the admin interface.
	 */
	app.init = function() {
		app.cache();

		app.show_or_hide_custom_js();
		app.show_or_hide_custom_code();
		app.show_or_hide_image_preview();
		app.show_or_hide_uploader();
		app.bind_media_library();
		app.bind_events();
	};

	/**
	 * Bind all configured event handlers.
	 */
	app.bind_events = function() {
		Object.keys( app.events ).forEach( function( event_type ) {
			var details = app.events[ event_type ];
			Object.keys( details ).forEach( function( selector ) {
				var method = details[ selector ];
				document.addEventListener( event_type, function( event ) {
					var target = event.target;
					if ( target.matches && target.matches( selector ) ) {
						method.call( target, event );
					}
				} );
			} );
		} );
	};

	/**
	 * Show or hide the custom JS field based on action selection.
	 */
	app.show_or_hide_custom_js = function() {
		var action = document.querySelector( 'select[name="wp_easter_egg_settings[action]"]' ).value;
		var $field = document.querySelector( 'textarea[name="wp_easter_egg_settings[custom_js]"]' ).parentElement.parentElement;

		if ( 'custom_js' === action ) {
			$field.style.display = '';
		} else {
			$field.style.display = 'none';
		}
	};

	/**
	 * Show or hide the custom code field based on type selection.
	 */
	app.show_or_hide_custom_code = function() {
		var type = document.querySelector( 'select[name="wp_easter_egg_settings[type]"]' ).value;
		var $field = document.querySelector( 'input[name="wp_easter_egg_settings[custom_code]"]' ).parentElement.parentElement;

		if ( 'custom' === type ) {
			$field.style.display = '';
		} else {
			$field.style.display = 'none';
		}
	};

	/**
	 * Show or hide the image preview based on image URL.
	 */
	app.show_or_hide_image_preview = function() {
		var image_src = document.querySelector( 'input#_wp_easter_egg_image' ).value;
		var $preview = document.querySelector( 'div.uploader #image_preview' );

		if ( image_src ) {
			$preview.src = image_src;
			$preview.style.display = '';
		} else {
			$preview.src = '';
			$preview.style.display = 'none';
		}
	};

	/**
	 * Show or hide the image uploader based on action selection.
	 */
	app.show_or_hide_uploader = function() {
		var action = document.querySelector( 'select[name="wp_easter_egg_settings[action]"]' ).value;
		var $uploader = document.querySelector( 'div.uploader' ).parentElement.parentElement;

		if ( action && action.indexOf( 'move_image_across_' ) >= 0 ) {
			$uploader.style.display = '';
		} else {
			$uploader.style.display = 'none';
		}
	};

	/**
	 * Bind the media library uploader button.
	 */
	app.bind_media_library = function() {
		document.querySelector( '#_wp_easter_egg_image_button' ).addEventListener( 'click', app.media_image_button );

		document.querySelectorAll( '.add_media' ).forEach( function( el ) {
			el.addEventListener( 'click', function() {
				window._custom_media = false;
			} );
		} );
	};

	/**
	 * Handle media library image selection.
	 *
	 * @param {Event} event - Click event.
	 */
	app.media_image_button = function( event ) {
		event.preventDefault();

		var _custom_media = true;
		var button = this;
		var id = button.id.replace( '_button', '' );

		var send_attachment_bkp = wp.media.editor.send.attachment;

		wp.media.editor.send.attachment = function( props, attachment ) {
			if ( _custom_media ) {
				document.querySelector( '#' + id ).value = attachment.url;
				app.show_or_hide_image_preview();
			} else {
				return send_attachment_bkp.apply( this, [ props, attachment ] );
			}
		};

		wp.media.editor.open( id );
		return false;
	};

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', app.init );
	} else {
		app.init();
	}

	return app;

} )( window, document, window.WP_Easter_Egg_Admin );
