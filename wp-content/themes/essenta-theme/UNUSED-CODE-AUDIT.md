# Essenta theme unused-code audit

## Scope and method

Updated 15 October 2026. Rechecked current theme source rather than carrying forward candidates from an earlier tree snapshot. The review covered PHP template routing, current ACF local JSON, named theme functions and WordPress/plugin hooks, all `src/scss` files, JavaScript selectors and callbacks, and Gulp build/enqueue wiring. `node_modules` and generated `dist` assets were not classified as source modules.

This is a static source audit, not a runtime/CMS database audit. Saved page-builder values, page content with custom HTML, external plugin/child-theme callers, and deployed environments were not available. Unreferenced source is therefore a cleanup candidate pending those checks, not proof that deletion is safe.

## Current unused-code candidates

| Candidate | Evidence | Confidence | Verify before cleanup |
|---|---|---:|---|
| `src/scss/blocks/card-grid.scss`, `src/scss/blocks/card-grid-links.scss`, `src/scss/blocks/text-bg-image.scss` | Their block-specific selectors (`.card-grid-section`, `.card-grid-links-section`, `.text-background-image`, `.text-bg-image-card`) have no matching references in current PHP templates, JavaScript, or ACF JSON. These files are nevertheless included by the broad SCSS input glob in [`gulpfile.js`](./gulpfile.js#L13). | 8/10 for no theme-template usage | Check saved/custom HTML and any external content that emits these classes before removing the styles. |
| `src/scss/blocks/hero-headers/full-width-header.scss`, `src/scss/blocks/hero-headers/minimal-header.scss`, `src/scss/blocks/hero-headers/contact-header.scss` | Their leading selectors (`.full-width-header`, `.minimal-header`, `.contact-hero`) have no matching PHP, JavaScript, or ACF references in the current theme. Other current hero styles do have matching template markup. | 8/10 for no theme-template usage | Check page content or external templates/plugins for these classes before removal. |
| `templates/headers/service-page-header.php` and `src/scss/blocks/hero-headers/service-header.scss` | The header renderer routes ACF layout names dynamically in [`header-page-builder.php`](./header-page-builder.php#L12-L13). Current `header_pagebuilder` layouts in [`group_6756b163dfe21.json`](./includes/acf-json/field-groups/group_6756b163dfe21.json#L22) include `homepage_header`, `case_study_header`, `case_study_hub_header`, and `media_text_header`, but not `service_page_header`. The stylesheet's `.cta-header` rules match only the service header template in the current source. | 8/10 for no route from current ACF configuration | Check saved header-builder data in WordPress and older exports; legacy rows can still route to this template. |
| Root `style.scss` and `stylesheet.php` | The active Gulp build reads `src/scss/**/*.scss` and `src/scss/admin/**/*.scss`, not root `style.scss`; that legacy file imports `assets/scss/...` paths rather than the current `src/scss` tree. `stylesheet.php` is a standalone styleguide HTML page, has no in-theme include/reference, and links to the old `b9-boilerplate` theme path. | 9/10 for not being part of the current theme build/render path | Verify the styleguide page is not visited directly by staff before deleting either file. |
| `get_component_prefix()` and `get_background()` in `functions/components.php` | They are defined in the theme but have no in-theme call sites. | 8/10 for no in-theme use | Check plugins, snippets, child themes, and other code that may call globally available theme functions. |
| Commented `.top-details` Slick block in `src/js/scripts.js` | The initialization block around line 463 is commented out and cannot execute. | 10/10 that this code is inert | Remove if it is no longer retained as a reference; removal does not change runtime behavior. |

## Function and JavaScript checks

- In the current [`functions/helpers.php`](./functions/helpers.php), `my_excerpt_length()` and `new_excerpt_more()` are registered as WordPress filters; `get_visitor_country_calling_code()` is called by `populate_gravity_forms_country_calling_code()`, which is registered with Gravity Forms. No named helper function in this file was found without a call site or hook.
- The other named functions in the active setup, ACF, CPT, and theme bootstrap code are wired to WordPress hooks, the admin tool, or callers in the theme. No additional unused PHP function was confirmed.
- The theme has one maintained JavaScript source file, [`src/js/scripts.js`](./src/js/scripts.js), included by the Gulp JS glob and enqueued as `dist/js/scripts.min.js`. Its `essentaInitOfficeMaps` callback is referenced by `templates/blocks/office-locations.php`; it is not orphaned. No additional unreferenced JavaScript file or active script function was confirmed. Some selectors serve plugin-generated WooCommerce markup and were not treated as unused based only on the absence of theme-owned product templates.
- The current SCSS pipeline concatenates all source SCSS files into one stylesheet, so file-level `@use`/`@import` references are not a reliable use test. The style candidates above are based on their selectors having no corresponding current theme markup/configuration, not on missing imports.

## ACF/template mismatch (missing implementation, not unused code)

- [`group_6793abe93bffe.json`](./includes/acf-json/field-groups/group_6793abe93bffe.json#L588) still defines a `blockquote` page-builder layout, but there is no `templates/blocks/blockquote.php`. The dynamic loader in [`page-builder.php`](./page-builder.php#L12-L13) will look for that file when the layout is rendered. Confirm whether the layout is still used and should have a renderer.
- Earlier report candidates `templates/blocks/page-post-links.php`, `templates/blocks/tabled-content.php`, and `templates/blocks/map-block.php` are not present in the current theme tree and have not been carried forward as current files.

## Build verification

- Gulp `compile`, `compileAdminSCSS`, and `minifyJS` completed successfully and regenerated the frontend CSS, admin CSS, and frontend JS outputs.
- Gulp `lintJS` did not pass: it reports 20 existing ESLint errors in `src/js/scripts.js` (undefined Google Maps global, indentation, and quote-style diagnostics). No JavaScript source changes were made as part of this documentation audit.
- No CMS/runtime tests were run.

## Suggested cleanup order

1. Check live and legacy CMS content for the unused-looking block and hero selectors, then remove confirmed-obsolete SCSS.
2. Check for saved `service_page_header` rows and direct use of the styleguide page before removing those files.
3. Check external callers before removing `get_component_prefix()` or `get_background()`.
4. Decide whether the configured `blockquote` layout needs its missing renderer.
5. Address the existing JavaScript lint diagnostics in a separate code-quality change.
