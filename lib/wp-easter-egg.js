window.WP_Easter_Egg = window.WP_Easter_Egg || {};

(function( window, document, $, app, undefined ) {
	'use strict';

	app.l10n = window.wpee_config || {};

	app.cache = function() {
		app.$ = {};
	};

	app.init = function() {
		app.cache();

		// check to make sure that the browser can handle window.addEventListener
		if( window.addEventListener ) {
			app.bind_keydown();
		}
	};

	app.bind_keydown = function() {
		// create the keys array and default to konami code unless custom code is given
		var keys = [],
			code = ( app.l10n.type == 'custom' ) ? app.l10n.custom_code : '38,38,40,40,37,39,37,39,66,65';

		code = app.modify_string_code( code );
	
		// bind the keydown event to the code function
		window.addEventListener( 'keydown', function( e ){
			// push the keycode to the 'keys' array
			keys.push( e.keyCode );

			var keys_string = app.modify_string_code( keys.toString() );
	
			// check to see if the user has entered the code
			if( keys_string.indexOf( code ) >= 0 ) {
				switch( app.l10n.action ) {
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
						app.move_image_across_bottom();
						break;
					
					case 'move_image_across_middle':
						app.move_image_across_middle();
						break;

					case 'move_image_across_top':
						app.move_image_across_top();
						break;
																	
					case 'custom_js':
						eval(app.l10n.custom_js);
						break;
				}
	     		// finally clean up the keys array
		 		keys = [];
			}
		}, true);
	};

	app.modify_string_code = function( string ) {
		if ( ',' !== string.slice( 1 ) ) {
			string = ',' + string;
		}

		if ( ',' !== string.slice( -1 ) ) {
			string += ',';
		}

		return string;
	};

	app.rotate_screen = function() {
		setInterval(function() {
			var theta = Math.PI * Math.random();
			$( 'body' ).css({ transform: 'rotate(' + theta + 'rad)' });
		}, 500 );
	};

	app.cornify = function() {
		$.getScript( 'http://www.cornify.com/js/cornify.js',function(){
			cornify_add();
			$( document ).keydown( cornify_add );
		});
	};

	app.raptorize = function() {
		$( 'body' ).raptorize({
			'delayTime' : 1000,
			'enterOn' : 'do-it'
		});
	};

	app.move_image_across_bottom = function() {
		// bail early if no image
		if ( ! app.l10n.image || '' == app.l10n.image ) {
			return;
		}

		var width = "+=" + $(document).width();
		var window_height = Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );

		var wpee_image = $( '<img />' )
			.attr( 'src', app.l10n.image )
			.css({
				position: 'absolute',
				bottom: '5px',
			})
			.appendTo( 'body' )
			.animate({
				left: width
			}, 5000, function() {
				// remove image when animation is complete
				wpee_image.remove();
		 	});
	};

	app.move_image_across_middle = function() {
		// bail early if no image
		if ( ! app.l10n.image || '' == app.l10n.image ) {
			return;
		}
	
		var width = "+=" + $(document).width();

		var window_width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );
		var window_height = Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );

		var wpee_image = $( '<img />' )
			.attr( 'src', app.l10n.image )
			.css({
				position: 'absolute',
				//width: '250px',
				//height: '250px',
				left: '50%',
				top: '50%'
			})
			.appendTo( 'body' )
			.animate({
				left: width
			}, 5000, function() {
				// remove image when animation is complete
				wpee_image.remove();
		 });
	};

	app.move_image_across_top = function() {
		// bail early if no image
		if ( ! app.l10n.image || '' == app.l10n.image ) {
			return;
		}
		
		var width = "+=" + $(document).width();

		var window_width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );
		var window_height = Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );

		var wpee_image = $( '<img />' )
			.attr( 'src', app.l10n.image )
			.css({
				position: 'absolute',
				width: '250px',
				height: '250px',
				left: '50%',
				top: '50%',
				'margin-left': -( window_width / 2 ) + 'px',
				'margin-top': -( window_height / 2 ) + 'px',
			})
			.appendTo( 'body' )
			.animate({
				left: width
			}, 5000, function() {
				// remove image when animation is complete
				wpee_image.remove();
		 	});
	};		

	$( document ).ready( app.init );

	return app;

})( window, document, jQuery, window.WP_Easter_Egg );
