# Essenta theme unused-code audit

## Scope and method

Static review of the maintained theme source, including PHP templates and hooks, ACF local JSON, JavaScript, SCSS, and the asset build/enqueue path. `node_modules` and generated `dist` files were excluded from dead-code classification. The review followed the theme's dynamic ACF template routing and searched for in-theme call sites before classifying candidates.

This is not a runtime/CMS database audit. No live page-builder values, external plugin callers, or production theme state were inspected. A missing source reference is therefore reported as a candidate, not proof that removal is safe.

## Candidates to verify before cleanup

| Candidate | Evidence | Confidence | Before removing |
|---|---|---:|---|
| `templates/blocks/page-post-links.php`, `templates/blocks/tabled-content.php`, and `templates/blocks/map-block.php` | [`page-builder.php`](./page-builder.php#L12-L13) maps each `page_builder` layout name to a `templates/blocks/<layout>.php` file. None of the three corresponding layout names is present in the current `page_builder` layouts in [`group_6793abe93bffe.json`](./includes/acf-json/field-groups/group_6793abe93bffe.json), and there are no direct in-theme calls to these templates. | 9/10 that the current theme has no route to them | Check saved page-builder rows in the WordPress database and any older environment/export before deleting; legacy layout data may remain even if it is absent from the current ACF JSON. |
| `src/scss/blocks/map-block.scss` and the map-specific implementation inside `templates/blocks/map-block.php` | The stylesheet selectors match only the map template. The template is not among the current configured page-builder layouts. Within the template, `$company_location` is assigned but never read; the markup contains a placeholder rather than an `.acf-map` element, while the embedded initializer searches only for `.acf-map`. | 8/10 for the map template/style being inactive; 10/10 for the unused local assignment | Treat these as part of the map-template decision above. Confirm there is no saved legacy map layout before removing the template and its stylesheet. |
| `get_component_prefix()` and `get_background()` in `functions/components.php` | Both functions are defined, but searches found no in-theme call sites. | 8/10 for no in-theme use | Check for calls from custom plugins, child themes, snippets, or other code outside this theme before removal. |
| `Custom_Nav_Walker` and `Mobile_Menu_Walker` in `functions/menu.php` | The classes have no in-theme instantiations. The theme's `wp_nav_menu()` calls in `header.php` and `footer.php` do not supply either walker. | 8/10 for no in-theme use | Check any external customization that may instantiate these globally defined classes. |
| Commented `.top-details` Slick initialization in `src/js/scripts.js` | The block around line 463 is commented out and cannot execute. | 10/10 that this snippet is inert | Remove if it is no longer being kept as a reference; no runtime behavior changes either way. |

## Related wiring mismatch (not an unused-code deletion candidate)

- The current ACF `page_builder` configuration includes a `blockquote` layout, but there is no `templates/blocks/blockquote.php`. The dynamic loader will look for that template when the layout is rendered, so confirm whether the layout is still intended and needs a template.

## Findings not classified as unused

- The source JavaScript is included by the Gulp build and the generated script path is enqueued by the theme. `essentaInitOfficeMaps` is passed as the Google Maps callback from `templates/blocks/office-locations.php`; it is not an orphan.
- SCSS files are included through the build's SCSS glob rather than imported individually. A lack of `@use`/`@import` references alone is not evidence that a stylesheet is unused.
- Other apparent dynamic entry points (WordPress hooks, ACF field definitions, and the registered post types) were retained as active unless a direct mismatch was found.

## Suggested cleanup order

1. Verify the three absent ACF layouts against saved content and deployed environments; remove the corresponding templates and the map stylesheet only if confirmed unused.
2. Confirm there are no external callers before removing the helper functions or navigation walker classes.
3. Remove the inert commented Slick snippet if it is not being retained for reference.
4. Decide whether the configured `blockquote` layout needs a rendering template.
