const { defineConfig } = require( 'cypress' );

module.exports = defineConfig( {
	e2e: {
		baseUrl: 'http://localhost:8080',
		specPattern: 'tests/cypress/e2e/**/*.cy.js',
		supportFile: 'tests/cypress/support/e2e.js',
		video: false,
		screenshotOnRunFailure: true,
		screenshotsFolder: 'build/cypress/screenshots',
		videosFolder: 'build/cypress/videos',
	},
	env: {
		wp_admin_url: 'http://localhost:8080/wp-admin',
		wp_admin_user: 'admin',
		wp_admin_password: 'password',
	},
} );
