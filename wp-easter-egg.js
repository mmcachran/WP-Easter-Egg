$ = $ || jQuery;

console.log('WPEE Config: ', wpee_config);

var rotateScreen = function() {
	setInterval(function() {
		var theta = Math.PI * Math.random();
		$('body').css({ transform: 'rotate(' + theta + 'rad)' });
	}, 1000);
};

// check to make sure that the browser can handle window.addEventListener
if(window.addEventListener) {
	// create the keys array and default to konami code unless custom code is given
	var keys = [],
		code = (wpee_config.type == 'custom') ? wpee_config.custom_code : "38,38,40,40,37,39,37,39,66,65";

	// bind the keydown event to the code function
	window.addEventListener("keydown", function(e){
		// push the keycode to the 'keys' array
		keys.push(e.keyCode);

		// check to see if the user has entered the code
		if(keys.toString().indexOf(code) >= 0) {
			switch(wpee_config.action) {
				case 'rotate_screen':
					rotateScreen();
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
