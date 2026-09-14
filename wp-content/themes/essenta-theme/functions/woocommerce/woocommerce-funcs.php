<?php

/**
 * Product category archives: in-stock first, out-of-stock last (initial load + works with FacetWP pager).
 */
add_action('pre_get_posts', function ($q) {

  // Front-end main query only
  if (is_admin() || ! $q->is_main_query()) return;

  // Search results: include products, pages, and posts
  if ($q->is_search()) {
    $q->set('post_type', ['product', 'page', 'post']);
    return;
  }

  // Product category archives only
  if (! $q->is_tax('product_cat')) return;

  // Ensure we're querying products (WC archives are, but safe)
  $q->set('post_type', 'product');

  // Add stock status meta so we can order by it
  $meta_query = (array) $q->get('meta_query');
  $meta_query[] = [
    'key'     => '_stock_status',
    'compare' => 'EXISTS',
  ];
  $q->set('meta_query', $meta_query);

  // Order by stock first, then whatever you want (example: menu_order + title)
  $q->set('orderby', [
    'meta_value' => 'ASC',  // instock, onbackorder, outofstock
    'menu_order' => 'ASC',
    'title'      => 'ASC',
  ]);
  $q->set('order', 'ASC');
}, 20);


// Stripe crdedit card icon fix
add_shortcode('accepted_payments_stripe_paypal', function () {
  $base = get_stylesheet_directory_uri() . '/dist/images/payments/';

  // Add/remove icons based on what you want to show
  $icons = [
    ['alt' => 'American Express', 'src' => $base . 'amex.svg'],
    ['alt' => 'Apple Pay',  'src' => $base . 'applepay.svg'],
    ['alt' => 'Diners', 'src' => $base . 'diners.svg'],
    ['alt' => 'Discover', 'src' => $base . 'discover.svg'],
    ['alt' => 'Google Pay', 'src' => $base . 'googlepay.svg'],
    ['alt' => 'Maestro',     'src' => $base . 'maestro.svg'],
    ['alt' => 'Mastercard', 'src' => $base . 'mastercard.svg'],
    ['alt' => 'PayPal',     'src' => $base . 'paypal.svg'],
    ['alt' => 'Shop Pay',    'src' => $base . 'shoppay.svg'],
    ['alt' => 'Visa',       'src' => $base . 'visa.svg'],
    ['alt' => 'Afterpay',    'src' => $base . 'afterpay.svg'],
  ];

  ob_start(); ?>
  <div class="accepted-payments" aria-label="Accepted payment methods">
    <?php foreach ($icons as $icon): ?>
      <img
        class="accepted-payments__icon"
        src="<?php echo esc_url($icon['src']); ?>"
        alt="<?php echo esc_attr($icon['alt']); ?>"
        loading="lazy"
        width="40"
        height="26" />
    <?php endforeach; ?>
  </div>
<?php
  return ob_get_clean();
});
