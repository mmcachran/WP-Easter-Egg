(function( window, document, $, undefined ) {
	'use strict';

	console.log( 'WPEE Config: ', wpee_config );
	
	var rotateScreen = function() {
		setInterval(function() {
			var theta = Math.PI * Math.random();
			$( 'body' ).css({ transform: 'rotate(' + theta + 'rad)' });
		}, 1000);
	};
	
	var cornify = function() {
		$.getScript('http://www.cornify.com/js/cornify.js',function(){
			cornify_add();
			$( document ).keydown( cornify_add );
		});
	};
	
	var raptorize = function() {
		$( 'body' ).raptorize({
				'delayTime' : 1000,
				'enterOn' : 'do-it'
		});
	};
	
	var moveImageAcrossBottom = function() {
		if( wpee_config.image && wpee_config.image !== '' ) {
			var width = "+=" + $(document).width();
			var window_height = Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );

			var wpee_image = $( '<img />' )
				.attr( 'src', wpee_config.image )
				.css({
					position: 'absolute',
					width: '250px',
					height: '250px',
					bottom: '5px'
				})
				.appendTo( 'body' )
				.animate({
					left: width
				}, 5000, function() {
					// remove image when animation is complete
					wpee_image.remove();
		  });
		}
	};

	var moveImageAcrossMiddle = function() {
		console.log( wpee_config.image );
		if( wpee_config.image && wpee_config.image !== '' ) {
			var width = "+=" + $(document).width();

			var window_width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );
			var window_height = Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );

			var wpee_image = $( '<img />' )
				.attr( 'src', wpee_config.image )
				.css({
					position: 'absolute',
					width: '250px',
					height: '250px',
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
		}
	};

	var moveImageAcrossTop = function() {
		console.log( wpee_config.image );
		if( wpee_config.image && wpee_config.image !== '' ) {
			var width = "+=" + $(document).width();

			var window_width = Math.max( document.documentElement.clientWidth, window.innerWidth || 0 );
			var window_height = Math.max( document.documentElement.clientHeight, window.innerHeight || 0 );

			var wpee_image = $( '<img />' )
				.attr( 'src', wpee_config.image )
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
		}
	};		
	// check to make sure that the browser can handle window.addEventListener
	if( window.addEventListener ) {
		// create the keys array and default to konami code unless custom code is given
		var keys = [],
			code = ( wpee_config.type == 'custom' ) ? wpee_config.custom_code : '38,38,40,40,37,39,37,39,66,65';
	
		// bind the keydown event to the code function
		window.addEventListener( 'keydown', function( e ){
			// push the keycode to the 'keys' array
			keys.push( e.keyCode );
	
			// check to see if the user has entered the code
			if( keys.toString().indexOf( code ) >= 0 ) {
				switch( wpee_config.action ) {
					case 'rotate_screen':
						rotateScreen();
						break;
						
					case 'cornify':
						cornify();
						break;
						
					case 'raptorize':
						raptorize();
						break;
					
					case 'move_image_across_bottom':
						moveImageAcrossBottom();
						break;
					
					case 'move_image_across_middle':
						moveImageAcrossMiddle();
						break;

					case 'move_image_across_top':
						moveImageAcrossTop();
						break;
																	
					case 'custom_js':
						eval(wpee_config.custom_js);
						break;
				}
	     		// finally clean up the keys array
		 		keys = [];
			}
		}, true);
	};
})(window, document, jQuery);
