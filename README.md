# Always Bet On The House Theme

This theme is a custom WordPress block theme for the `always-bet-on-the-house` site.

## Project Paths

- Theme: `C:\Users\Jay\Local Sites\always-bet-on-the-house\app\public\wp-content\themes\property-listing`
- Static reference: `C:\JAYFILE\JAYFILE\Client\Mainstream\Wordpress\Rico\html\alwaysbetonthehouse\index.html`

## Current Custom Work

### Header

- Social icon borders removed
- Social icon hover updated to match nav underline behavior
- Mobile menu social icons cleaned up to match desktop styling

Main file:
- `assets/css/main.css`

### Search

- Desktop search button toggles the top search panel
- Mobile search button opens an inline search form inside the responsive menu
- Search still uses standard WordPress `?s=` behavior

Main files:
- `parts/site-header.html`
- `assets/js/main.js`
- `assets/css/main.css`

### Homepage Slider

- Starter hero replaced with a custom homepage slider
- Slider now uses arrows only, with the small thumbnail images removed
- Slider is available as a custom block for editor insertion
- Slider supports Media Library image selection in block settings
- Slider text can be shown or hidden from the block settings

Main files:
- `blocks/hero-slider/block.json`
- `blocks/hero-slider/editor.js`
- `blocks/hero-slider/render.php`
- `assets/js/main.js`
- `assets/css/main.css`
- `assets/images/logo.png`

### Host Section

- Added a custom `Host Section` block for the host or agent intro area
- Supports Media Library image selection
- Lets you edit the section title, eyebrow, host name, and description in the editor
- Lets you show or hide the top section title

Main files:
- `blocks/host-section/block.json`
- `blocks/host-section/editor.js`
- `blocks/host-section/render.php`
- `assets/css/main.css`
- `inc/setup.php`

## WordPress Editor Notes

- Your active homepage uses the `landing.html` template, which renders page content.
- The slider can be inserted from the editor using the `Hero Slider` custom block.
- The host intro can be inserted from the editor using the `Host Section` custom block.
- Add these blocks from the page editor or Site Editor where the landing page content is managed.
- The slider block lets you edit the kicker, heading, subtitle, logo, and slide images.
- The host block lets you edit the host photo, title, eyebrow, host name, and description.
- Both blocks support Media Library image selection.

## How To Use The Blocks

### Hero Slider

1. Open the homepage in the block editor.
2. Insert the `Hero Slider` block.
3. Click the block and open the settings sidebar.
4. Choose the logo and slide images from the Media Library.
5. Edit the slider text as needed.
6. Use the toggle to show or hide the slider text.

### Host Section

1. Open the homepage in the block editor.
2. Insert the `Host Section` block.
3. Click the block and open the settings sidebar.
4. Choose the host photo from the Media Library.
5. Edit the title, eyebrow, host name, and description directly in the block.
6. Use the toggle to show or hide the top section title.

## CSS Labels Added

Custom CSS sections now include labels for easier maintenance:

- `Custom: Homepage Hero Slider`
- `Custom: Host Or Agent Intro Section`
- `Custom: Header Social Icon Nav-Style Hover`
- `Custom: Mobile Search Panel Reveal`

## Known Limitations

- The slider block currently includes 3 editable slides
- The slider block does not yet support add/remove/reorder slides
- Search has not been customized by post type or taxonomy
- Browser verification was not performed from this terminal session
