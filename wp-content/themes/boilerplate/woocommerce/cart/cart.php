<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<div class="draw-cart-page">
  <h3 class="draw-cart-title"><?php esc_html_e('Your Basket', 'yourtheme'); ?></h3>

  <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <div class="draw-cart-items">
      <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
        $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

        if (! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
          continue;
        }

        $product_name     = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
        $product_subtotal = WC()->cart->get_product_subtotal($_product, $cart_item['quantity']);

        $entries_per_item = (int) get_post_meta($product_id, 'entry_count', true);
        $total_entries    = $entries_per_item ? ($entries_per_item * $cart_item['quantity']) : $cart_item['quantity'];

        $remove_url = wc_get_cart_remove_url($cart_item_key);

        // Optional product image / fallback icon
        $product_image = get_the_post_thumbnail_url($product_id, 'thumbnail');
      ?>
        <div class="draw-cart-card">
          <div class="draw-cart-card__main">
            <div class="draw-cart-card__brand">
              <?php if ($product_image) : ?>
                <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_name); ?>">
              <?php else : ?>
                <span class="draw-cart-card__brand-fallback">V</span>
              <?php endif; ?>
            </div>

            <div class="draw-cart-card__divider"></div>

            <div class="draw-cart-card__content">
              <div class="draw-cart-card__entries">
                <?php echo esc_html($total_entries); ?> <?php esc_html_e('Entries', 'yourtheme'); ?>
              </div>

              <div class="draw-cart-card__price">
                <?php echo wp_kses_post($product_subtotal); ?>
              </div>
            </div>

            <a
              class="draw-cart-card__remove"
              href="<?php echo esc_url($remove_url); ?>"
              aria-label="<?php echo esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))); ?>">
              <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M9 3h6l1 2h4v2H4V5h4l1-2zm1 6h2v8h-2V9zm4 0h2v8h-2V9zM7 9h2v8H7V9zm1 12c-1.1 0-2-.9-2-2V8h12v11c0 1.1-.9 2-2 2H8z" fill="currentColor" />
              </svg>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
  </form>

  <div class="draw-cart-totals">
    <div class="draw-cart-totals__row">
      <span><?php esc_html_e('Total:', 'yourtheme'); ?></span>
      <span><?php wc_cart_totals_order_total_html(); ?></span>
    </div>

    <div class="draw-cart-totals__checkout">
      <?php
      $checkout_url = wc_get_checkout_url();
      ?>
      <a href="<?php echo esc_url($checkout_url); ?>" class=" btn primary green">Checkout</a>
    </div>
  </div>
</div>

<?php do_action('woocommerce_after_cart'); ?>

<style>
  .draw-cart-page {
    max-width: 660px;
    margin: 0 auto;
    padding: 48px 28px 72px;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xl);
  }

  /* .draw-cart-title {
    margin: 0 0 26px;
    font-size: 52px;
    line-height: 1;
    font-weight: 700;
    color: #111;
  } */

  .draw-cart-items {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .draw-cart-card {
    border: 1px solid #7e766d;
    border-radius: 14px;
    background: transparent;
    padding: 0;
    overflow: hidden;
  }

  .draw-cart-card__main {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 16px 18px;
    position: relative;
    min-height: 100px;
  }

  .draw-cart-card__brand {
    width: 36px;
    min-width: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .draw-cart-card__brand img {
    max-width: 100%;
    height: auto;
    display: block;
  }

  .draw-cart-card__brand-fallback {
    font-size: 34px;
    font-weight: 700;
    line-height: 1;
    color: #111;
  }

  .draw-cart-card__divider {
    width: 1px;
    align-self: stretch;
    background: #7e766d;
  }

  .draw-cart-card__content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 6px;
    padding-right: 40px;
  }

  .draw-cart-card__entries {
    font-size: 18px;
    line-height: 1.2;
    font-weight: 700;
    color: #111;
  }

  .draw-cart-card__price {
    font-size: 18px;
    line-height: 1.2;
    font-weight: 400;
    color: #222;
  }

  .draw-cart-card__price .amount {
    color: #222;
    font-weight: 400;
  }

  .draw-cart-card__remove {
    position: absolute;
    top: 12px;
    right: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    color: #6c655d;
    text-decoration: none;
    font-size: 0;
  }

  .draw-cart-card__remove:hover {
    color: #111;
  }

  .draw-cart-totals {
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid #7e766d;
  }

  .draw-cart-totals__row {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 6px;
    margin-bottom: 18px;
    font-size: 20px;
    line-height: 1.2;
    font-weight: 700;
    color: #111;
  }

  .draw-cart-totals__row .amount {
    font-weight: 700;
    color: #111;
  }

  .draw-cart-totals__checkout .checkout-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px 24px;
    border-radius: 999px;
    background: #006b57;
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    font-weight: 600;
    line-height: 1;
    border: 0;
    box-shadow: none;
  }

  .draw-cart-totals__checkout .checkout-button:hover {
    background: #005847;
    color: #fff;
  }

  .draw-cart-totals__checkout .checkout-button::after {
    content: "›";
    font-size: 20px;
    line-height: 1;
    display: inline-block;
    transform: translateY(-1px);
  }

  @media (max-width: 640px) {
    .draw-cart-page {
      padding: 32px 18px 50px;
    }

    .draw-cart-title {
      font-size: 38px;
    }

    .draw-cart-card__main {
      padding: 14px 16px;
      gap: 14px;
    }

    .draw-cart-card__entries,
    .draw-cart-card__price {
      font-size: 16px;
    }
  }
</style>