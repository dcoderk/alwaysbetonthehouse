Always Bet On The House Theme Notes
===================================

This file documents the current custom work completed in the
`property-listing` WordPress block theme for the
`always-bet-on-the-house` site.

Project summary
---------------
- Theme path:
  `C:\Users\Jay\Local Sites\always-bet-on-the-house\app\public\wp-content\themes\property-listing`
- Theme type:
  Full Site Editing / block theme
- Primary reference used for recent homepage work:
  `C:\JAYFILE\JAYFILE\Client\Mainstream\Wordpress\Rico\html\alwaysbetonthehouse\index.html`

Completed work
--------------
1. Header social icon styling
- Removed the circular border treatment from the header social icons.
- Updated hover behavior to match the navigation menu underline effect.
- Applied the same cleanup to the mobile menu social icons.

Files updated:
- `assets/css/main.css`

Relevant selectors:
- `.site-header .social-list .wp-social-link`
- `a.wp-block-social-link-anchor`
- `.site-header .mobile-menu-extras .wp-social-link`

2. Mobile search reveal
- Confirmed the search form already used standard WordPress search behavior.
- Added a mobile-specific search reveal inside the responsive menu.
- The mobile search button now toggles an inline search form and focuses the search input.
- Desktop search behavior remains separate and still uses the header search panel.

Files updated:
- `assets/js/main.js`
- `assets/css/main.css`

Relevant selectors and logic:
- `#searchToggle`
- `#searchPanel`
- `.mobile-menu-extras`
- `.mobile-search-panel`

3. Homepage hero slider
- Replaced the starter homepage hero with a custom slider section based on the static HTML reference.
- Added 3 slides with navigation arrows and thumbnail selectors.
- Added autoplay rotation logic with pause on hover.
- Added overlay logo support.
- Added responsive styles for tablet and mobile.

Files updated:
- `templates/home.html`
- `assets/css/main.css`
- `assets/js/main.js`
- `assets/images/logo.png`

Relevant selectors and logic:
- `.hero`
- `.hero-carousel-full`
- `.hero-slide`
- `.hero-thumb`
- `.hero-arrow`
- `#heroCarousel`

Current behavior
----------------
Header
- Desktop header shows social icons, search button, and navigation.
- Social icons now use an underline hover treatment consistent with nav links.

Search
- Desktop search button toggles the top search panel.
- Mobile search button inside the responsive menu reveals a stacked inline form.
- Search form submits using the standard WordPress query variable `s`.

Homepage slider
- The homepage template contains the slider directly in `templates/home.html`.
- Slides currently use placeholder Unsplash image URLs.
- The logo overlay uses `assets/images/logo.png`.
- Slide changes are handled in `assets/js/main.js`.

WordPress editor notes
----------------------
- The slider is currently hard-coded into the Home template.
- It is not yet a reusable block pattern or a custom editable block.
- You can see it in:
  Appearance > Editor > Templates > Home
- It is easier to maintain in code right now than through the visual editor.

If editor control is needed later, recommended options are:
- Convert the slider into a block pattern
- Build editable slide fields with ACF or custom blocks

Important file map
------------------
- `templates/home.html`
  Homepage template containing the custom hero slider markup.
- `parts/site-header.html`
  Header structure including social links and the search button.
- `assets/css/main.css`
  Main frontend styling for header, mobile menu, search, and hero slider.
- `assets/js/main.js`
  Frontend interactions for search toggle, mobile search reveal, and slider rotation.
- `assets/images/logo.png`
  Logo used in the slider overlay.

Known limitations
-----------------
- Slider images and labels are still placeholder content.
- Slider content is not yet editable from normal WordPress block controls.
- Browser verification was not performed inside this terminal session.
- Search behavior is standard WordPress search and has not been customized by post type or taxonomy.

Suggested next steps
--------------------
- Replace placeholder hero images with project-approved media.
- Convert the slider into a pattern if it should be insertable in the Site Editor.
- Convert slider content into editable fields if the client needs dashboard control.
- Continue porting additional sections from the static reference file if required.

Change log
----------
- Updated social icon hover effect to match nav links and removed icon borders.
- Added mobile inline search panel toggle.
- Ported homepage hero slider from static HTML reference.
- Copied `logo.png` into theme assets for slider branding.
