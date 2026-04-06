ClientStarter WordPress Block Theme
===================================

A reusable Full Site Editing starter theme for client projects.

Installation
------------
1. Upload the `clientstarter` folder to `/wp-content/themes/`
   or upload `clientstarter.zip` in Appearance > Themes > Add New > Upload Theme.
2. Activate the theme.
3. Open Appearance > Editor to customize templates, template parts, and styles.
4. Set a static homepage if needed in Settings > Reading.

Recommended workflow
--------------------
- Keep design/layout inside the theme.
- Put functionality like custom post types, meta fields, forms, API logic, and custom blocks in a companion plugin.
- Duplicate this theme for each client and rename:
  - folder name
  - Theme Name in style.css
  - Text Domain
  - function prefixes
  - pattern slugs

Included files
--------------
- theme.json with design tokens and editor settings
- core templates for posts, pages, archives, search, and 404
- header and footer template parts
- reusable hero, services, and CTA patterns
- lightweight frontend and editor CSS

Notes
-----
- This is an FSE/block theme, not a classic PHP template theme.
- The `landing` custom template can be selected on pages.
- For production use, replace placeholder text, colors, and links.
