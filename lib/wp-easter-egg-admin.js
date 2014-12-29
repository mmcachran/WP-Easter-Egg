(function( $ ) {
	var EasterEgg = Backbone.Model.extend({
		el: $( 'body' ), // attach `this.el` to body element
		
		events: {
			'change select[name="wp_easter_egg_settings[action]"]': 'ShowOrHideCustomJS',
			'change select[name="wp_easter_egg_settings[type]"]': 'ShowOrHideCustomCode',
			'change input[name="wp_easter_egg_settings[image]"]': 'ShowOrHideImagePreview'
		},
		
		initialize: function() {
			_.bindAll( this, 'render' ); // fix loss of context for `this` within methods
			
			var me = this;
			
			//this.setElement( $( '#screen-meta' ) );
			
			$( document ).ready(function() {
				me.render();
			});
		},
		
		render: function() {
			var me = this;
			
			me.ShowOrHideCustomJS();
			me.ShowOrHideCustomCode();
			me.ShowOrHideImagePreview();
			me.BindMediaLibrary();
			
			/*
			 * @ToDo - these should be defined as backbone events
			 */
			$( 'select[name="wp_easter_egg_settings[action]"]' ).on( 'change', function( evt) {
				me.ShowOrHideCustomJS();
			});
			
			$( 'select[name="wp_easter_egg_settings[type]"]' ).on( 'change', function( evt ) {
				me.ShowOrHideCustomCode();
			});
			
			$( 'input[name="wp_easter_egg_settings[image]"]' ).on( 'change', function( evt ) {
				me.ShowOrHideImagePreview();
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
		
		BindMediaLibrary: function() {
			// image uploader
			var _custom_media = true,
				_orig_send_attachment = wp.media.editor.send.attachment;
				
			$( '#_wp_easter_egg_image_button' ).click(function( e ) {
				var send_attachment_bkp = wp.media.editor.send.attachment;
				var button = $( this );
				var id = button.attr( 'id' ).replace( '_button', '' );
				_custom_media = true;
				wp.media.editor.send.attachment = function( props, attachment ){
					if ( _custom_media ) {
						$( '#' + id ).val( attachment.url );
						self.ShowOrHideImagePreview();
					} else {
						return _orig_send_attachment.apply( this, [props, attachment] );
					};
				}
			 
				wp.media.editor.open(button);
				return false;
			});
			 
			$( '.add_media' ).on('click', function(){
				_custom_media = false;
			});			
		}
		
	});
	
	var EasterEgg = new EasterEgg();
	
})(jQuery);