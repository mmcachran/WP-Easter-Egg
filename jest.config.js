module.exports = {
	rootDir: '.',
	testMatch: [ '**/tests/js/**/*.test.js' ],
	testEnvironment: 'jsdom',
	collectCoverageFrom: [
		'lib/wp-easter-egg.js',
		'lib/wp-easter-egg-admin.js',
		'!lib/raptorize/**',
	],
	coverageDirectory: 'build/coverage/js',
	coverageReporters: [ 'text', 'lcov', 'html' ],
	verbose: true,
};
