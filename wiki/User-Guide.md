# User Guide

This guide helps site owners and content editors configure and use WP Easter Egg.

## Configuration

### Accessing Settings

1. Log in to your WordPress admin dashboard
2. Navigate to **Settings > WP Easter Egg**
3. Configure your Easter Egg options

### Settings Explained

| Setting | Description |
|---------|-------------|
| **Easter Egg Filter** | Control which posts show the Easter Egg (Off, Exclusive, Inclusive) |
| **Easter Egg Type** | Use the Konami code or a custom key sequence |
| **Easter Egg Custom Code** | Enter custom key codes (comma-separated) |
| **Easter Egg Action** | What happens when the code is entered |
| **Custom JS** | Custom JavaScript to execute (only for Custom JS action) |
| **Image** | Image to use for move image actions |

### Available Actions

| Action | What Happens |
|--------|--------------|
| Rotate Screen | Page rotates at random angles continuously |
| Cornify | Unicorns and rainbows appear (via cornify.com) |
| Raptorize | A raptor appears and moves across the screen |
| Move Image Across Bottom | Your image moves across the bottom of the screen |
| Move Image Across Middle | Your image moves across the middle of the screen |
| Move Image Across Top | Your image moves across the top of the screen |
| Custom JS | Your custom JavaScript executes |

## Using the Filter System

The filter system lets you control which posts display the Easter Egg:

### Off (Default)
The Easter Egg appears on all posts and pages.

### Exclusive
The Easter Egg appears everywhere **except** posts where you check "Add to filter?" in the post editor.

### Inclusive
The Easter Egg appears **only** on posts where you check "Add to filter?" in the post editor.

## Adding Filter to Posts

1. Edit a post in WordPress admin
2. Look for the "WP Easter Egg" meta box on the side
3. Check or uncheck "Add to filter?"
4. Update the post

## Key Codes Reference

Common JavaScript key codes:

| Key | Code |
|-----|------|
| Up Arrow | 38 |
| Down Arrow | 40 |
| Left Arrow | 37 |
| Right Arrow | 39 |
| B | 66 |
| A | 65 |
| Enter | 13 |
| Space | 32 |
| Escape | 27 |

The Konami code is: 38,38,40,40,37,39,37,39,66,65

## Screenshots and Videos

### Screenshots

| # | Description | Location |
|---|-------------|----------|
| 1 | WP Easter Egg settings page | `screenshots/settings-page.png` |
| 2 | Meta box on post edit screen | `screenshots/meta-box.png` |
| 3 | Easter Egg in action on frontend | `screenshots/frontend-action.png` |

### Demo Videos

| # | Description | Location |
|---|-------------|----------|
| 1 | Admin configuration walkthrough | `videos/admin-config.mp4` |
| 2 | Konami code Easter Egg trigger | `videos/konami-trigger.mp4` |
| 3 | Filter system demo | `videos/filter-system.mp4` |

> **Note:** Media files are not included in the repository. Capture them from a running WordPress installation with the plugin activated.

## Tips

* Use the "Activate keycode helper" checkbox to automatically fill key codes as you press keys
* Test your Easter Egg on a staging site before deploying to production
* Custom JS can be powerful but use with caution - invalid JavaScript can break your site
