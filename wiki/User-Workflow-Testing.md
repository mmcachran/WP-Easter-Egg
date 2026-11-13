# User Workflow Testing Documentation

This document describes the end-to-end user workflows tested for WP Easter Egg, including test scenarios, expected outcomes, and verification steps.

## Overview

User workflow testing validates that real users can successfully:
1. Install and configure the plugin
2. Trigger Easter Eggs on the frontend
3. Use the filter system to control Easter Egg visibility

## Test Environment

* WordPress 6.5+
* PHP 7.4+
* MySQL 5.7+
* Cypress test runner

## Workflow 1: Admin Configuration

**File**: `tests/cypress/e2e/admin-workflow.cy.js`

### Test Scenarios

| # | Scenario | Steps | Expected Result |
|---|----------|-------|-----------------|
| 1 | Navigate to settings | Login → Settings → WP Easter Egg | Settings page loads with all fields |
| 2 | Save settings | Configure all options → Save | Settings saved, success message shown |
| 3 | Toggle custom code field | Select "custom" type | Custom code input appears |
| 4 | Toggle custom JS field | Select "custom_js" action | Custom JS textarea appears |
| 5 | Toggle image uploader | Select "move_image_across_*" action | Image uploader appears |
| 6 | Save with image | Enter image URL → Save | Image URL persisted in form |

### Verification

* All settings fields are present and functional
* Conditional fields show/hide correctly based on selections
* Settings persist after saving
* Success message appears after save

## Workflow 2: Frontend Easter Egg Activation

**File**: `tests/cypress/e2e/frontend-workflow.cy.js`

### Test Scenarios

| # | Scenario | Steps | Expected Result |
|---|----------|-------|-----------------|
| 1 | Konami code triggers egg | Configure Konami → Visit site → Enter Konami | Screen rotates |
| 2 | Custom key triggers egg | Configure custom code → Visit site → Enter code | Action triggers |
| 3 | Wrong keys don't trigger | Configure Konami → Visit site → Press wrong keys | No action |
| 4 | Exclusive filter (not added) | Configure exclusive → Visit post → Enter code | Egg triggers |
| 5 | Exclusive filter (added) | Add post to filter → Visit post → Enter code | No action |
| 6 | Inclusive filter (not added) | Configure inclusive → Visit post → Enter code | No action |
| 7 | Inclusive filter (added) | Add post to filter → Visit post → Enter code | Egg triggers |

### Verification

* Konami code (Up, Up, Down, Down, Left, Right, Left, Right, B, A) triggers the configured action
* Custom key sequences trigger the configured action
* Wrong key sequences do not trigger any action
* Filter system correctly controls Easter Egg visibility per post

## Workflow 3: Filter System

### Exclusive Mode

In exclusive mode, the Easter Egg appears on all posts **except** those marked "Add to filter?":

1. Configure filter as "exclusive"
2. Create a post without checking "Add to filter?" → Easter Egg works
3. Create a post with "Add to filter?" checked → Easter Egg does NOT work

### Inclusive Mode

In inclusive mode, the Easter Egg appears **only** on posts marked "Add to filter?":

1. Configure filter as "inclusive"
2. Create a post without checking "Add to filter?" → Easter Egg does NOT work
3. Create a post with "Add to filter?" checked → Easter Egg works

## Running the Tests

### Prerequisites

```bash
# Install Node.js dependencies
npm install --save-dev cypress

# Start WordPress test site
# (requires WordPress installation with plugin activated)
```

### Execute Tests

```bash
# Open Cypress interactive runner
npx cypress open

# Run headless (CI mode)
npx cypress run
```

### Test Configuration

Edit `cypress.config.js` to set your WordPress admin credentials:

```javascript
env: {
    wp_admin_url: 'http://your-site.com/wp-admin',
    wp_admin_user: 'your-username',
    wp_admin_password: 'your-password',
}
```

## Custom Commands

| Command | Description |
|---------|-------------|
| `cy.wpLogin()` | Log in to WordPress admin |
| `cy.navigateToWpeeSettings()` | Navigate to WP Easter Egg settings |
| `cy.configureWpee(settings)` | Configure plugin settings |
| `cy.simulateKonamiCode()` | Enter the Konami code sequence |
| `cy.simulateKeySequence(codes)` | Enter a custom key sequence |

## Test Results

All tests should pass with:
* ✅ Settings save and persist correctly
* ✅ Conditional fields show/hide as expected
* ✅ Konami code triggers Easter Egg actions
* ✅ Custom key sequences work correctly
* ✅ Filter system controls visibility per post
* ✅ No JavaScript errors during workflows

## Troubleshooting

| Issue | Solution |
|-------|----------|
| Login fails | Verify admin credentials in cypress.config.js |
| Settings page not found | Ensure plugin is activated |
| Easter Egg doesn't trigger | Verify key sequence timing and focus |
| Filter not working | Check post meta `_wpee_added_to_filter` |
