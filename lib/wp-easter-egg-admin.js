window.WP_Easter_Egg_Admin = window.WP_Easter_Egg_Admin || {};

(function( window, document, $, app, undefined ) {
	'use strict';

	app.l10n = window.wpee_admin_config || {};

	app.cache = function() {
		app.$ = {};

		// admin events
		app.events = {
			'change' : {
				'select[name="wp_easter_egg_settings[action]"]' : function( evt ) {
					app.show_or_hide_custom_js();
					app.show_or_hide_uploader();
				},
				'select[name="wp_easter_egg_settings[type]"]' : function( evt ) {
					app.show_or_hide_custom_code();
				},
				'input[name="wp_easter_egg_settings[image]"]' : function( evt ) {
					app.show_or_hide_image_preview();
				}
			},
			'keydown' : {
				'input[name="wp_easter_egg_settings[custom_code]"]' : function ( evt ) {
					// return early if helper isn't active
					if ( ! $('#keycode-helper').is(":checked") ) {
						return;
					}

					// bail early if backspace

					var custom_code_input = jQuery('input[name="wp_easter_egg_settings[custom_code]"]');
					var keycode = evt.keyCode;

					// bail early if backspace
					if ( 8 === keycode ) {
						return;
					}

					// prepend comma if not the beginning of our key sequence
					if( '' !== custom_code_input.val() ) {
						keycode = ',' + keycode;
					}
					
					// append JS keycode to input
					custom_code_input.val( custom_code_input.val() + keycode );
					return false;
				}
			}
		};
	};

	app.init = function() {
		app.cache();

		app.show_or_hide_custom_js();
		app.show_or_hide_custom_code();
		app.show_or_hide_image_preview();
		app.show_or_hide_uploader();
		app.bind_media_library();

		// bind events
		app.bind_events();
	};

	app.bind_events = function() {
		// bind events
		$.each( app.events, function( event, details ) {
			$.each( details, function( selector, method ) {
				$( selector ).on( event, method );
			});
		});
	};

	app.show_or_hide_custom_js = function( evt ) {
		if( $('select[name="wp_easter_egg_settings[action]"]').val() == 'custom_js' ) {
			$('textarea[name="wp_easter_egg_settings[custom_js]"]').parent().parent().show();
		} else {
			$('textarea[name="wp_easter_egg_settings[custom_js]"]').parent().parent().hide();
		}	
	};
		
	app.show_or_hide_custom_code = function( evt ) {
		if( $('select[name="wp_easter_egg_settings[type]"]').val() == 'custom' ) {
			$('input[name="wp_easter_egg_settings[custom_code]"]').parent().parent().show();
		} else {
			$('input[name="wp_easter_egg_settings[custom_code]"]').parent().parent().hide();
		}				
	};
		
	app.show_or_hide_image_preview = function( evt ) {
		var image_src = $( 'input#_wp_easter_egg_image' ).val();
		
		if( image_src ) {
			$( 'div.uploader #image_preview' ).attr( 'src', image_src ).show();
		} else {
			$( 'div.uploader #image_preview' ).attr( 'src', '' ).hide();
		}
	};
		
	app.show_or_hide_uploader = function(evt) {
		var action = $( 'select[name="wp_easter_egg_settings[action]"]' ).val();
	
		if( action.indexOf( 'move_image_across_' ) >= 0 ) {
			$('div.uploader').parent().parent().show();
		} else {
			$('div.uploader').parent().parent().hide();
		}	
	};
		
	app.bind_media_library = function() {
		$( '#_wp_easter_egg_image_button' ).on( 'click', app.media_image_button );
			 
		$( '.add_media' ).on( 'click', function() {
			_custom_media = false;
		});			
	};

	app.media_image_button = function( evt ) {
		// image uploader
		var _custom_media = true,
			_orig_send_attachment = wp.media.editor.send.attachment;
		
		var send_attachment_bkp = wp.media.editor.send.attachment;
		var button = $( this );
		var id = button.attr( 'id' ).replace( '_button', '' );
		_custom_media = true;
				
		wp.media.editor.send.attachment = function( props, attachment ) {
			if( _custom_media ) {
				$( '#' + id ).val( attachment.url );
				app.show_or_hide_image_preview();
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}
			 
		wp.media.editor.open( id );
		return false;
	};

	$( document ).ready( app.init );

	return app;

})( window, document, jQuery, window.WP_Easter_Egg_Admin );
