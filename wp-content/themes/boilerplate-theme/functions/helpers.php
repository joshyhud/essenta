<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Change excerpt length
function my_excerpt_length($length)
{
  return 15;
}
add_filter('excerpt_length', 'my_excerpt_length');

// Change excerpt after text
function new_excerpt_more($more)
{
  global $post;
  return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');


/**
 * FacetWP + YITH WooCommerce Wishlist compatibility
 */

// Force legacy PHP templates so the button can be re-init after FacetWP refresh
add_filter('yith_wcwl_rendering_method', function ($rendering) {
  return 'php-templates';
});

// Re-trigger YITH init after FacetWP updates results (pagination/filtering)
add_action('facetwp_scripts', function () {
?>
  <script>
    document.addEventListener('facetwp-loaded', function() {
      if (typeof FWP !== 'undefined' && FWP.loaded && typeof jQuery !== 'undefined') {
        jQuery(document).trigger('yith_wcwl_init');
      }
    });
  </script>
<?php
});

add_action('wp_ajax_yith_wcwl_update_wishlist_count', 'theme_yith_wcwl_update_wishlist_count');
add_action('wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'theme_yith_wcwl_update_wishlist_count');

function theme_yith_wcwl_update_wishlist_count()
{
  // yith_wcwl_count_all_products is widely used in YITH examples/support
  $count = function_exists('yith_wcwl_count_all_products') ? yith_wcwl_count_all_products() : 0;

  wp_send_json(['count' => (int) $count]);
}

add_action('wp_enqueue_scripts', 'theme_yith_wcwl_live_header_count', 20);

function theme_yith_wcwl_live_header_count()
{
  if (! defined('YITH_WCWL')) return;

  // Ensure YITH’s localized object exists; their script handle is commonly 'jquery-yith-wcwl'
  wp_add_inline_script(
    'jquery-yith-wcwl',
    "jQuery(function($){
      function refreshWishlistCount(){
        if (typeof yith_wcwl_l10n === 'undefined') return;

        $.get(yith_wcwl_l10n.ajax_url, { action: 'yith_wcwl_update_wishlist_count' }, function(data){
          var count = (data && typeof data.count !== 'undefined') ? parseInt(data.count, 10) : 0;
          var \$el = $('.wishlist-count');

          if (\$el.length) {
            \$el.text(count);

            // Optional: hide when zero
            // \$el.toggle(count > 0);
          }
        });
      }

      // Update count when YITH adds/removes items
      $(document).on('added_to_wishlist removed_from_wishlist', refreshWishlistCount);

      // Initial sync
      refreshWishlistCount();
    });"
  );
}


/**
 * AJAX: Clear all items from the YITH wishlist.
 */
add_action('wp_ajax_theme_clear_wishlist', 'theme_clear_wishlist');
add_action('wp_ajax_nopriv_theme_clear_wishlist', 'theme_clear_wishlist');

function theme_clear_wishlist()
{
  check_ajax_referer('theme_clear_wishlist', 'nonce');

  if (!class_exists('YITH_WCWL_Wishlist_Factory')) {
    wp_send_json_error();
  }

  $wishlist = null;

  if (is_user_logged_in()) {
    $wishlist = YITH_WCWL_Wishlist_Factory::get_default_wishlist(get_current_user_id());
  }

  if (!$wishlist) {
    $wishlist = YITH_WCWL_Wishlist_Factory::get_current_wishlist();
  }

  if (!$wishlist || !method_exists($wishlist, 'get_items')) {
    wp_send_json_error();
  }

  $items = $wishlist->get_items();

  foreach ($items as $item) {
    if (is_object($item) && method_exists($item, 'get_product_id')) {
      YITH_WCWL()->remove([
        'remove_from_wishlist' => $item->get_product_id(),
        'wishlist_id'          => $wishlist->get_id(),
      ]);
    }
  }

  wp_send_json_success();
}


// Team member block functions 

/**
 * Restrict relationship results by department per field.
 * Assumes taxonomy = department, terms = management/workshop/sales.
 */
add_filter('acf/fields/relationship/query/name=management_members', function ($args, $field, $post_id) {
  $args['tax_query'] = [[
    'taxonomy' => 'department',
    'field'    => 'slug',
    'terms'    => ['management-team'],
  ]];
  return $args;
}, 10, 3);

add_filter('acf/fields/relationship/query/name=workshop_members', function ($args, $field, $post_id) {
  $args['tax_query'] = [[
    'taxonomy' => 'department',
    'field'    => 'slug',
    'terms'    => ['workshop-team'],
  ]];
  return $args;
}, 10, 3);

add_filter('acf/fields/relationship/query/name=sales_members', function ($args, $field, $post_id) {
  $args['tax_query'] = [[
    'taxonomy' => 'department',
    'field'    => 'slug',
    'terms'    => ['sales-team'],
  ]];
  return $args;
}, 10, 3);


/// filter out sort and sort by text from sort dropdown 
add_filter('facetwp_facet_html', function ($output, $params) {
  if ('sort' === $params['facet']['type']) { // only target sort facets
    $output = str_replace('<option value="">Sort</option>', '', $output);
    $output = str_replace('<option value="">Sort by</option>', '', $output);
  }
  return $output;
}, 10, 2);


/**
 * Allow frontend search to match titles, content, and WooCommerce SKUs.
 * Uses OR logic between space-separated terms so "G71586 engagement" matches
 * products with SKU G71586 AND separately any page/product containing "engagement".
 */
add_filter('posts_search', 'bn_search_by_sku_and_keywords', 9999, 2);

function bn_search_by_sku_and_keywords($search, $wp_query)
{
  global $wpdb;

  if (is_admin() || !$wp_query->is_main_query() || !$wp_query->is_search()) {
    return $search;
  }

  $s = isset($wp_query->query_vars['s']) ? trim($wp_query->query_vars['s']) : '';

  if (empty($s)) {
    return $search;
  }

  $terms = preg_split('/\s+/', $s);
  $terms = array_values(array_filter(array_map('trim', $terms)));

  if (empty($terms)) {
    return $search;
  }

  // Build title/content OR conditions — one per term so each term is matched independently
  $term_conditions = [];
  foreach ($terms as $term) {
    $like = '%' . $wpdb->esc_like($term) . '%';
    $term_conditions[] = $wpdb->prepare(
      "({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_content LIKE %s)",
      $like,
      $like
    );
  }

  // Build SKU conditions — find products whose SKU matches any term
  $sku_conditions = [];
  foreach ($terms as $term) {
    $sku_conditions[] = $wpdb->prepare(
      "(pm.meta_key = '_sku' AND pm.meta_value LIKE %s)",
      '%' . $wpdb->esc_like($term) . '%'
    );
  }

  $sku_product_ids = $wpdb->get_col(
    "SELECT DISTINCT p.ID
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'product'
    AND p.post_status = 'publish'
    AND (" . implode(' OR ', $sku_conditions) . ")"
  );

  if (!empty($sku_product_ids)) {
    $ids_sql = implode(',', array_map('absint', $sku_product_ids));
    $term_conditions[] = "({$wpdb->posts}.ID IN ({$ids_sql}))";
  }

  // Replace the entire search clause with OR logic between all conditions
  $search = ' AND (' . implode(' OR ', $term_conditions) . ')';

  return $search;
}

add_filter('posts_distinct', 'bn_search_distinct_for_sku', 9999, 2);

function bn_search_distinct_for_sku($distinct, $wp_query)
{
  if (is_admin() || !$wp_query->is_main_query() || !$wp_query->is_search()) {
    return $distinct;
  }

  return 'DISTINCT';
}
