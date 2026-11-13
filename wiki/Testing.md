# Testing

WP Easter Egg includes comprehensive testing at multiple levels:

## Test Suite Overview

| Test Type | Framework | Location | Description |
|-----------|-----------|----------|-------------|
| PHP Unit Tests | PHPUnit | `tests/phpunit/` | Tests PHP classes and logic |
| JavaScript Unit Tests | Jest | `tests/js/` | Tests JavaScript functions |
| E2E Tests | Cypress | `tests/cypress/e2e/` | Tests complete user workflows |

## Running Tests

### PHP Unit Tests

```bash
# Install dependencies
composer install

# Run all tests
vendor/bin/phpunit

# Run with coverage
vendor/bin/phpunit --coverage-html build/coverage
```

### JavaScript Unit Tests

```bash
# Install dependencies
npm install --save-dev jest jest-environment-jsdom

# Run all tests
npx jest

# Run with coverage
npx jest --coverage
```

### E2E Tests

```bash
# Install dependencies
npm install --save-dev cypress

# Open Cypress test runner
npx cypress open

# Run headless
npx cypress run
```

## Test Coverage

### PHP Tests

| Class | Test File | Coverage |
|-------|-----------|----------|
| Plugin | `TestWPEE_Plugin.php` | Singleton, version, options, filter |
| Options | `TestWPEE_Options.php` | Sanitization, defaults, validation |
| Frontend | `TestWPEE_Frontend.php` | JS data compilation, post visibility |
| Meta_Box | `TestWPEE_MetaBox.php` | Filter labels, save logic |

### JavaScript Tests

| Module | Test File | Coverage |
|--------|-----------|----------|
| Core | `wp-easter-egg.test.js` | Key normalization, action triggering, custom JS |

### E2E Tests

| Workflow | Test File | Coverage |
|----------|-----------|----------|
| Admin settings | `admin-workflow.cy.js` | Settings save, field visibility, image upload |
| Frontend activation | `frontend-workflow.cy.js` | Konami code, custom keys, filter behavior |

## Writing Tests

### PHP Test Example

```php
namespace WP_Easter_Egg\Tests;

use WP_Easter_Egg\Plugin;
use PHPUnit\Framework\TestCase;

class TestExample extends TestCase {
    public function test_something() {
        $plugin = Plugin::get_instance();
        $this->assertInstanceOf( Plugin::class, $plugin );
    }
}
```

### JavaScript Test Example

```javascript
describe( 'My Feature', () => {
    test( 'does something', () => {
        expect( true ).toBe( true );
    } );
} );
```

### Cypress E2E Test Example

```javascript
describe( 'User Workflow', () => {
    it( 'completes a task', () => {
        cy.wpLogin();
        cy.navigateToWpeeSettings();
        cy.configureWpee( { action: 'cornify' } );
    } );
} );
```

## Continuous Integration

All tests run automatically on every push and pull request via GitHub Actions. See `.github/workflows/ci.yml` for details.
