$ = $ || jQuery;

$(document).on('ready', function() {
	ShowOrHideCustomJS();
	ShowOrHideCustomCode();
	
	$('select[name="wp_easter_egg_settings[action]"]').on('change', function(e) {
		ShowOrHideCustomJS();
	});
	
	$('select[name="wp_easter_egg_settings[type]"]').on('change', function(e) {
		ShowOrHideCustomCode();
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