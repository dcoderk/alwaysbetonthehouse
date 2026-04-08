# Changelog

## 2026-04-08

### Header

- Removed social icon borders in the header
- Matched social icon hover behavior to the nav underline effect
- Applied the same cleanup to mobile social icons

### Search

- Confirmed the header search uses standard WordPress search behavior
- Added mobile inline search reveal inside the responsive menu
- Kept desktop search panel behavior unchanged

### Homepage Slider

- Replaced the starter homepage hero with a custom slider section
- Added autoplay rotation, arrows, and thumbnail navigation
- Added responsive slider styling for tablet and mobile
- Added overlay logo support
- Copied `assets/images/logo.png` into the theme
- Added a `Hero Slider` pattern for insertion from the editor on landing-page content
- Added a `Hero Slider` custom block with Media Library image selection
- Registered the custom block in theme setup and loaded slider styles in the editor
- Updated frontend slider logic to support block-based slider instances
- Removed the small slider thumbnail images
- Added a show/hide text option for the slider block

### Host Section

- Added a `Host Section` custom block
- Added editable Media Library host photo support
- Added editable title, eyebrow, host name, and description fields
- Added a show/hide title option for the host section block
- Added host section frontend/editor styling

### Documentation

- Replaced starter notes with project-specific documentation in `README.txt`
- Added `README.md`
- Added `CHANGELOG.md`
- Added labels to custom CSS sections in `assets/css/main.css`
