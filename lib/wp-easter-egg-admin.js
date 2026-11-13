/**
 * WP Easter Egg - Admin JavaScript
 *
 * Handles admin UI interactions for the Easter Egg settings page.
 *
 * @package WP_Easter_Egg
 */

window.WP_Easter_Egg_Admin = window.WP_Easter_Egg_Admin || {};

( function( window, document, $, app, undefined ) {
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
					if ( ! $( '#keycode-helper' ).is( ':checked' ) ) {
						return;
					}

					if ( 8 === event.keyCode ) {
						return;
					}

					var custom_code_input = $( 'input[name="wp_easter_egg_settings[custom_code]"]' );
					var keycode = event.keyCode;

					if ( '' !== custom_code_input.val() ) {
						keycode = ',' + keycode;
					}

					custom_code_input.val( custom_code_input.val() + keycode );
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
		$.each( app.events, function( event_type, details ) {
			$.each( details, function( selector, method ) {
				$( document ).on( event_type, selector, method );
			} );
		} );
	};

	/**
	 * Show or hide the custom JS field based on action selection.
	 */
	app.show_or_hide_custom_js = function() {
		var action = $( 'select[name="wp_easter_egg_settings[action]"]' ).val();
		var $field = $( 'textarea[name="wp_easter_egg_settings[custom_js]"]' ).parent().parent();

		if ( 'custom_js' === action ) {
			$field.show();
		} else {
			$field.hide();
		}
	};

	/**
	 * Show or hide the custom code field based on type selection.
	 */
	app.show_or_hide_custom_code = function() {
		var type = $( 'select[name="wp_easter_egg_settings[type]"]' ).val();
		var $field = $( 'input[name="wp_easter_egg_settings[custom_code]"]' ).parent().parent();

		if ( 'custom' === type ) {
			$field.show();
		} else {
			$field.hide();
		}
	};

	/**
	 * Show or hide the image preview based on image URL.
	 */
	app.show_or_hide_image_preview = function() {
		var image_src = $( 'input#_wp_easter_egg_image' ).val();
		var $preview = $( 'div.uploader #image_preview' );

		if ( image_src ) {
			$preview.attr( 'src', image_src ).show();
		} else {
			$preview.attr( 'src', '' ).hide();
		}
	};

	/**
	 * Show or hide the image uploader based on action selection.
	 */
	app.show_or_hide_uploader = function() {
		var action = $( 'select[name="wp_easter_egg_settings[action]"]' ).val();
		var $uploader = $( 'div.uploader' ).parent().parent();

		if ( action && action.indexOf( 'move_image_across_' ) >= 0 ) {
			$uploader.show();
		} else {
			$uploader.hide();
		}
	};

	/**
	 * Bind the media library uploader button.
	 */
	app.bind_media_library = function() {
		$( '#_wp_easter_egg_image_button' ).on( 'click', app.media_image_button );

		$( '.add_media' ).on( 'click', function() {
			window._custom_media = false;
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
		var button = $( this );
		var id = button.attr( 'id' ).replace( '_button', '' );

		var send_attachment_bkp = wp.media.editor.send.attachment;

		wp.media.editor.send.attachment = function( props, attachment ) {
			if ( _custom_media ) {
				$( '#' + id ).val( attachment.url );
				app.show_or_hide_image_preview();
			} else {
				return send_attachment_bkp.apply( this, [ props, attachment ] );
			}
		};

		wp.media.editor.open( id );
		return false;
	};

	$( document ).ready( app.init );

	return app;

} )( window, document, jQuery, window.WP_Easter_Egg_Admin );
