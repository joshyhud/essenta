<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Loads base theme setup and enqueues
require_once('functions/setup.php');

// Helpers and utility functions
require_once('functions/helpers.php');

// Handles ACF custom JSON paths and field definitions
require_once('functions/acf.php');

// Registers custom theme components
require_once('functions/components.php');

// Registers custom post types like Testimonials and FAQs
require_once('functions/cpt.php');

// Registers and manages theme navigation menus
require_once('functions/menu.php');

// FacetWP integration functions
if (class_exists('FacetWP')) {
  require_once('functions/facetwp.php');
}

// WooCommerce customizations and integrations
if (class_exists('WooCommerce')) {
  require_once('functions/woocommerce/woocommerce-funcs.php');
}

/**
 * Admin tool: Sync product tags -> product attributes (pa_*)
 * - Batch processes products
 * - Maps tag slugs/names to attribute term names
 * - Assigns terms and ensures _product_attributes contains the taxonomy attribute
 */

add_action('admin_menu', function () {
  add_management_page(
    'Sync Attributes from Tags',
    'Sync Attributes from Tags',
    'manage_woocommerce',
    'bn-sync-attributes-from-tags',
    'bn_render_sync_attributes_from_tags_page'
  );
});

/**
 * Configure your sync rules here.
 *
 * IMPORTANT:
 * - Keys are attribute taxonomies (must exist in WooCommerce): pa_metal-types etc.
 * - Each attribute has a map: 'tag to match' => 'Attribute Term Name to set'
 * - Matching is case-insensitive against tag NAMES (not slugs) by default.
 */
function bn_get_attribute_sync_rules(): array
{
  return [
    'pa_metal-types' => [
      'yellow gold' => 'Yellow Gold',
      'white gold'  => 'White Gold',
      'platinum'    => 'Platinum',
      'rose gold'   => 'Rose Gold',
      'silver'      => 'Silver',
      'palladium'   => 'Palladium',
    ],

    'pa_cuts' => [
      'round'     => 'Round',
      'square'    => 'Square',
      'cushion'   => 'Cushion',
      'asscher'   => 'Asscher',
      'emerald'   => 'Emerald',
      'radiant'   => 'Radiant',
      'oval'      => 'Oval',
      'marquise'  => 'Marquise',
    ],

    'pa_ring-type' => [
      'solitaire'       => 'Solitaire',
      'three stone'     => 'Three Stone',
      'shoulder set'    => 'Shoulder Set',
      'halo'            => 'Halo',
      'alternative'     => 'Alternative',
    ],

    'pa_stone-colour' => [
      'black' => 'Black',
      'blue' => 'Blue',
      'brown' => 'Brown',
      'green' => 'Green',
      'multi-colour' => 'Multi-Colour',
      'orange' => 'Orange',
      'pink' => 'Pink',
      'purple' => 'Purple',
      'red' => 'Red',
      'teal' => 'Teal',
      'white' => 'White',
      'yellow' => 'Yellow',
    ],

    'pa_stone-spread' => [
      // examples
      'part'         => 'Part',
      'full'         => 'Full',
    ],

    'pa_stone-types' => [
      'amethyst'      => 'Amethyst',
      'aquamarine'    => 'Aquamarine',
      'diamond'       => 'Diamond',
      'emerald'       => 'Emerald',
      'garnet'        => 'Garnet',
      'morganite'     => 'Morganite',
      'peridot'       => 'Peridot',
      'ruby'          => 'Ruby',
      'sapphire'      => 'Sapphire',
      'tanzanite'     => 'Tanzanite',
      'topaz'         => 'Topaz',
      'tourmaline'    => 'Tourmaline',
      'zircon'        => 'Zircon',
    ],

    'pa_styles' => [
      'claw'          => 'Claw',
      'grain'         => 'Grain',
      'channel'       => 'Channel',
      'rub over'      => 'Rub Over',
    ],
  ];
}

function bn_render_sync_attributes_from_tags_page()
{
  if (!current_user_can('manage_woocommerce')) return;

  $rules = bn_get_attribute_sync_rules();

  $selected_tax = isset($_GET['attr_tax']) ? sanitize_text_field($_GET['attr_tax']) : 'pa_metal-types';
  if (!isset($rules[$selected_tax])) {
    $selected_tax = array_key_first($rules);
  }

  $done        = isset($_GET['done']) ? intval($_GET['done']) : 0;
  $next_offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
  $limit       = 50;

  echo '<div class="wrap"><h1>Sync Attributes from Tags</h1>';

  if (!taxonomy_exists($selected_tax)) {
    echo '<div class="notice notice-error"><p><strong>Error:</strong> Taxonomy <code>' . esc_html($selected_tax) . '</code> does not exist. Create it in WooCommerce → Products → Attributes first.</p></div>';
  }

  // Run batch
  if (!empty($_POST['bn_sync_attributes_from_tags'])) {
    check_admin_referer('bn_sync_attributes_from_tags');

    $result = bn_sync_attribute_from_tags_batch($selected_tax, $rules[$selected_tax], $next_offset, $limit);

    $new_done   = $done + $result['processed'];
    $new_offset = $next_offset + $result['processed'];

    if ($result['processed'] === 0) {
      echo '<p><strong>Finished.</strong> Total processed: ' . esc_html($new_done) . '</p>';
      echo '<p>Products updated (had at least one match): <strong>' . esc_html($result['updated']) . '</strong></p>';
      echo '<p>Terms created: <strong>' . esc_html($result['terms_created']) . '</strong></p>';
    } else {
      $url = add_query_arg([
        'page'     => 'bn-sync-attributes-from-tags',
        'attr_tax' => $selected_tax,
        'offset'   => $new_offset,
        'done'     => $new_done,
      ], admin_url('tools.php'));

      echo '<p>Processed: <strong>' . esc_html($new_done) . '</strong> so far. Continuing…</p>';
      echo '<p>Updated this batch: <strong>' . esc_html($result['updated']) . '</strong> | Terms created this batch: <strong>' . esc_html($result['terms_created']) . '</strong></p>';

      echo '<form method="post" action="' . esc_url($url) . '">';
      wp_nonce_field('bn_sync_attributes_from_tags');
      echo '<p><button class="button button-primary" type="submit" name="bn_sync_attributes_from_tags" value="1">Run next batch</button></p>';
      echo '</form>';
      echo '</div>';
      return;
    }
  }

  echo '<p>This scans products and, when it finds matching <strong>product tags</strong>, assigns the corresponding term(s) to the selected attribute taxonomy.</p>';

  // Attribute selector
  echo '<form method="get" action="' . esc_url(admin_url('tools.php')) . '" style="margin-bottom:16px;">';
  echo '<input type="hidden" name="page" value="bn-sync-attributes-from-tags" />';
  echo '<label for="attr_tax"><strong>Attribute taxonomy:</strong></label> ';
  echo '<select name="attr_tax" id="attr_tax">';
  foreach ($rules as $tax => $_map) {
    echo '<option value="' . esc_attr($tax) . '" ' . selected($selected_tax, $tax, false) . '>' . esc_html($tax) . '</option>';
  }
  echo '</select> ';
  echo '<button class="button">Change</button>';
  echo '</form>';

  echo '<details style="margin: 12px 0;"><summary><strong>Current mapping for ' . esc_html($selected_tax) . '</strong></summary>';
  echo '<ul style="margin-left:18px;">';
  foreach ($rules[$selected_tax] as $tag_need => $term_name) {
    echo '<li><code>' . esc_html($tag_need) . '</code> → <code>' . esc_html($term_name) . '</code></li>';
  }
  echo '</ul></details>';

  echo '<form method="post">';
  wp_nonce_field('bn_sync_attributes_from_tags');
  echo '<input type="hidden" name="attr_tax" value="' . esc_attr($selected_tax) . '" />';
  echo '<p><button class="button button-primary" type="submit" name="bn_sync_attributes_from_tags" value="1">Start / Run</button></p>';
  echo '</form>';

  echo '</div>';
}

function bn_sync_attribute_from_tags_batch(string $attr_tax, array $map, int $offset = 0, int $limit = 50): array
{
  // Query product IDs in batches
  $q = new WP_Query([
    'post_type'      => 'product',
    'post_status'    => ['publish', 'private', 'draft'],
    'fields'         => 'ids',
    'posts_per_page' => $limit,
    'offset'         => $offset,
    'orderby'        => 'ID',
    'order'          => 'ASC',
    'no_found_rows'  => true,
  ]);

  $processed     = 0;
  $updated       = 0;
  $terms_created = 0;

  // Normalize map keys to lowercase for matching
  $map_lc = [];
  foreach ($map as $needle_tag => $term_name) {
    $map_lc[mb_strtolower(trim($needle_tag))] = $term_name;
  }

  foreach ($q->posts as $product_id) {
    $processed++;

    // Get tag NAMES and normalize to lowercase
    $tag_names = wp_get_post_terms($product_id, 'product_tag', ['fields' => 'names']);
    if (is_wp_error($tag_names) || empty($tag_names)) continue;

    $tag_lc = array_map(function ($t) {
      return mb_strtolower(trim($t));
    }, $tag_names);

    $terms_to_set = [];

    foreach ($map_lc as $needle_tag_lc => $term_name) {
      if (in_array($needle_tag_lc, $tag_lc, true)) {
        $terms_to_set[] = $term_name;
      }
    }

    $terms_to_set = array_values(array_unique(array_filter($terms_to_set)));
    if (empty($terms_to_set)) continue;

    // Ensure attribute terms exist
    foreach ($terms_to_set as $term_name) {
      if (!term_exists($term_name, $attr_tax)) {
        $inserted = wp_insert_term($term_name, $attr_tax);
        if (!is_wp_error($inserted)) $terms_created++;
      }
    }

    // Assign taxonomy terms (append = true)
    $set = wp_set_object_terms($product_id, $terms_to_set, $attr_tax, true);
    if (!is_wp_error($set)) {
      $updated++;
      bn_set_wc_product_attribute_taxonomy($product_id, $attr_tax);
    }
  }

  return [
    'processed'      => $processed,
    'updated'        => $updated,
    'terms_created'  => $terms_created,
  ];
}

/**
 * Ensures the taxonomy attribute is present in _product_attributes meta
 * so it shows up under Product data → Attributes.
 */
function bn_set_wc_product_attribute_taxonomy(int $product_id, string $attr_tax): void
{
  $attrs = get_post_meta($product_id, '_product_attributes', true);
  if (!is_array($attrs)) $attrs = [];

  if (!isset($attrs[$attr_tax])) {
    $attrs[$attr_tax] = [
      'name'         => $attr_tax,
      'value'        => '',
      'position'     => count($attrs),
      'is_visible'   => 1,
      'is_variation' => 0,
      'is_taxonomy'  => 1,
    ];
    update_post_meta($product_id, '_product_attributes', $attrs);
  }
}
