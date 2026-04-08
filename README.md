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
- Slider includes arrows, thumbnail selectors, autoplay, and logo overlay
- Slider now uses arrows only, with the small thumbnail images removed
- Slider is available as a custom block for editor insertion
- Slider supports Media Library image selection in block settings
- Slider text can be shown or hidden from the block settings

Main files:
- `templates/home.html`
- `blocks/hero-slider/block.json`
- `blocks/hero-slider/editor.js`
- `blocks/hero-slider/render.php`
- `patterns/hero-slider.php`
- `assets/js/main.js`
- `assets/css/main.css`
- `assets/images/logo.png`

## WordPress Editor Notes

- Your active homepage uses the `landing.html` template, which renders page content.
- The slider can now be inserted from the editor using the `Hero Slider` custom block.
- Add it from the page editor or Site Editor where the landing page content is managed.
- The block lets you edit the kicker, heading, subtitle, logo, and slide images.
- The block lets you show or hide the text overlay.
- Slide alt text is editable in the block inspector.

## How To Change Slider Images Right Now

If you upload an image using the WordPress Media Library, you can now assign it directly in the custom block:

1. Open the homepage in the block editor.
2. Insert the `Hero Slider` block if it is not already on the page.
3. Click the slider block.
4. Open the block settings sidebar.
5. Under `Logo`, choose or replace the logo image.
6. Under `Slides`, choose or replace each slide image from the Media Library.
7. Update the label and alt text for each slide as needed.

Important:
- The frontend thumbnail uses the same selected image as the main slide image.
- The block currently includes 3 editable slides.
- The older pattern file still exists, but the custom block is the recommended workflow now.

If you want true editor-based image selection later, the next upgrade should be one of these:

- Extend the custom block to support add/remove/reorder slides
- Build a richer React-based editing experience for slide content

## CSS Labels Added

Custom CSS sections now include labels for easier maintenance:

- `Custom: Homepage Hero Slider`
- `Custom: Header Social Icon Nav-Style Hover`
- `Custom: Mobile Search Panel Reveal`

## Known Limitations

- Slider content is still placeholder content
- Slider images are not yet editor-managed
- Search has not been customized by post type or taxonomy
- Browser verification was not performed from this terminal session
