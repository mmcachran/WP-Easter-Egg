$ = $ || jQuery;

$(document).ready(function() {	
	ShowOrHideCustomJS();
	ShowOrHideCustomCode();
	ShowOrHideImagePreview();
	
	$( 'select[name="wp_easter_egg_settings[action]"]' ).on( 'change', function( e ) {
		ShowOrHideCustomJS();
	});
	
	$( 'select[name="wp_easter_egg_settings[type]"]' ).on( 'change', function( e ) {
		ShowOrHideCustomCode();
	});
	
	$( 'input[name="wp_easter_egg_settings[image]"]' ).on( 'change', function( e ) {
		ShowOrHideImagePreview();
	});
	
		
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
				ShowOrHideImagePreview();
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
				ShowOrHideImagePreview();
			};
		}
	 
		wp.media.editor.open(button);
		return false;
	});
	 
	$( '.add_media' ).on('click', function(){
		_custom_media = false;
	});
});

function ShowOrHideCustomJS() {
	if( $('select[name="wp_easter_egg_settings[action]"]').val() == 'custom_js' ) {
		$('textarea[name="wp_easter_egg_settings[custom_js]"]').parent().parent().show();
	} else {
		$('textarea[name="wp_easter_egg_settings[custom_js]"]').parent().parent().hide();
	}	
}

function ShowOrHideCustomCode() {
	if( $('select[name="wp_easter_egg_settings[type]"]').val() == 'custom' ) {
		$('input[name="wp_easter_egg_settings[custom_code]"]').parent().parent().show();
	} else {
		$('input[name="wp_easter_egg_settings[custom_code]"]').parent().parent().hide();
	}		
}

function ShowOrHideImagePreview() {
	var image_src = $( 'input#_wp_easter_egg_image' ).val();
	if( image_src ) {
		$( 'div.uploader #image_preview' ).attr( 'src', image_src ).show();
	} else {
		$( 'div.uploader #image_preview' ).attr( 'src', '' ).hide();
	}
}