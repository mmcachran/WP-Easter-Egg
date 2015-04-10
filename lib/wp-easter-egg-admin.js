(function( window, document, $, undefined ) {
	'use strict';
	
	var EasterEggAdmin = {
		events: {
			'change' : {
				'select[name="wp_easter_egg_settings[action]"]' : function( evt ) {
					EasterEggAdmin.ShowOrHideCustomJS();
					EasterEggAdmin.ShowOrHideUploader();
				},
				'select[name="wp_easter_egg_settings[type]"]' : function( evt ) {
					EasterEggAdmin.ShowOrHideCustomCode();
				},
				'input[name="wp_easter_egg_settings[image]"]' : function( evt ) {
					EasterEggAdmin.ShowOrHideImagePreview();
				}
			},
			'keydown' : {
				'input[name="wp_easter_egg_settings[custom_code]"]' : function ( evt ) {
					// return early if helper isn't active
					if ( ! $('#keycode-helper').is(":checked") ) {
						return;
					}

					var custom_code_input = jQuery('input[name="wp_easter_egg_settings[custom_code]"]');
					var keycode = evt.keyCode;

					// append comma if not the beginning of our key sequence
					if( "" !== custom_code_input.val() ) {
						keycode = ',' + keycode;
					}
					
					// append JS keycode to input
					custom_code_input.val( custom_code_input.val() + keycode );
					return false;

				}
			}
		},
		
		initialize: function() {
			var me = EasterEggAdmin;
			me.render();
		},
		
		render: function() {
			var me = EasterEggAdmin;
			
			me.ShowOrHideCustomJS();
			me.ShowOrHideCustomCode();
			me.ShowOrHideImagePreview();
			me.ShowOrHideUploader();
			me.BindMediaLibrary();
			
			// bind events
			$.each( me.events, function( event, details ) {
				$.each( details, function( selector, method ) {
					$( selector ).on( event, method );
				});
			});
		},
		
		ShowOrHideCustomJS: function( evt ) {
			if( $('select[name="wp_easter_egg_settings[action]"]').val() == 'custom_js' ) {
				$('textarea[name="wp_easter_egg_settings[custom_js]"]').parent().parent().show();
			} else {
				$('textarea[name="wp_easter_egg_settings[custom_js]"]').parent().parent().hide();
			}	
		},
		
		ShowOrHideCustomCode: function( evt ) {
			if( $('select[name="wp_easter_egg_settings[type]"]').val() == 'custom' ) {
				$('input[name="wp_easter_egg_settings[custom_code]"]').parent().parent().show();
			} else {
				$('input[name="wp_easter_egg_settings[custom_code]"]').parent().parent().hide();
			}				
		},
		
		ShowOrHideImagePreview: function( evt ) {
			var image_src = $( 'input#_wp_easter_egg_image' ).val();
			if( image_src ) {
				$( 'div.uploader #image_preview' ).attr( 'src', image_src ).show();
			} else {
				$( 'div.uploader #image_preview' ).attr( 'src', '' ).hide();
			}
		},
		
		ShowOrHideUploader: function(evt) {
			var action = $( 'select[name="wp_easter_egg_settings[action]"]' ).val();
			if( action.indexOf( 'move_image_across_' ) >= 0 ) {
				$('div.uploader').parent().parent().show();
			} else {
				$('div.uploader').parent().parent().hide();
			}	
		},
		
		BindMediaLibrary: function() {
			// image uploader
			var _custom_media = true,
				_orig_send_attachment = wp.media.editor.send.attachment;
				
			$( '#_wp_easter_egg_image_button' ).click(function( evt ) {
				var send_attachment_bkp = wp.media.editor.send.attachment;
				var button = $( this );
				var id = button.attr( 'id' ).replace( '_button', '' );
				_custom_media = true;
				wp.media.editor.send.attachment = function( props, attachment ) {
					if( _custom_media ) {
						$( '#' + id ).val( attachment.url );
						EasterEggAdmin.ShowOrHideImagePreview();
					} else {
						return _orig_send_attachment.apply( this, [props, attachment] );
					};
				}
			 
				wp.media.editor.open( button );
				return false;
			});
			 
			$( '.add_media' ).on( 'click', function() {
				_custom_media = false;
			});			
		}
		
	};
	
	$( document ).ready( EasterEggAdmin.initialize );
	
})(window, document, jQuery);