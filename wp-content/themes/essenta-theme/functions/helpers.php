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
 * Get the visitor's country calling code from their IP address.
 *
 * Example:
 * UK       => +44
 * USA      => +1
 * Portugal => +351
 *
 * @return string
 */
function get_visitor_country_calling_code()
{

  // Get the visitor's IP address.
  $ip = $_SERVER['REMOTE_ADDR'] ?? '';

  if (empty($ip) || ! filter_var($ip, FILTER_VALIDATE_IP)) {
    return '';
  }

  // Cache the result for this IP for 24 hours.
  $cache_key = 'visitor_calling_code_' . md5($ip);
  $cached    = get_transient($cache_key);

  if ($cached !== false) {
    return $cached;
  }

  // Look up the IP.
  $response = wp_remote_get(
    'https://ipapi.co/' . rawurlencode($ip) . '/country_calling_code/',
    [
      'timeout' => 2,
    ]
  );

  if (is_wp_error($response)) {
    return '';
  }

  if (wp_remote_retrieve_response_code($response) !== 200) {
    return '';
  }

  $calling_code = trim(wp_remote_retrieve_body($response));

  // Make sure we actually received something like +44.
  if (! preg_match('/^\+\d+(?:,\s*\+\d+)*$/', $calling_code)) {
    return '';
  }

  // Cache for 24 hours.
  set_transient($cache_key, $calling_code, DAY_IN_SECONDS);

  return $calling_code;
}


/**
 * Populate the Gravity Forms phone country calling code.
 */
add_filter(
  'gform_field_value_country_calling_code',
  'populate_gravity_forms_country_calling_code'
);

function populate_gravity_forms_country_calling_code($value)
{

  // Don't overwrite an existing value.
  if (! empty($value)) {
    return $value;
  }

  return get_visitor_country_calling_code();
}

add_filter('gform_submit_button', function ($button, $form) {
  $privacy_policy_url = get_privacy_policy_url();
  $privacy_policy_text = esc_html__('privacy policy', 'essenta-theme');

  if ($privacy_policy_url) {
    $privacy_policy_text = '<a href="' . esc_url($privacy_policy_url) . '">' . $privacy_policy_text . '</a>';
  }

  return $button . '<div class="gform_under_submit_text">' . sprintf(
    esc_html__("*By clicking submit I agree to Essenta's %s.", 'essenta-theme'),
    $privacy_policy_text
  ) . '</div>';
}, 10, 2);
