<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$featured_products_subtitle = get_sub_field('featured_products_subtitle');
$featured_products_heading = get_sub_field('featured_products_heading');

$latest_specific = get_sub_field('latest_or_specific');
$specific_products = get_sub_field('products');

?>

<section class="featured-products">
  <div class="container">
    <div class="featured-products-header">
      <?php if ($featured_products_subtitle) : ?>
        <p class="subheading"><?php echo esc_html($featured_products_subtitle); ?></p>
      <?php endif; ?>
      <?php if ($featured_products_heading) : ?>
        <h2 class="featured-products-heading"><?php echo esc_html($featured_products_heading); ?></h2>
      <?php endif; ?>
    </div>

    <div class="featured-products-grid grid">
      <?php if ($latest_specific === 'specific' && $specific_products) : ?>
        <?php foreach ($specific_products as $product) :

          $product_id = $product->ID;
          $product_image = wp_get_attachment_url(get_post_thumbnail_id($product_id), 'full');
          $product_title = $product->post_title;
          $product_link = get_permalink($product_id);

        ?>
          <div class="product-card col-3 "></div>
          <a href="<?php echo esc_url($product_link); ?>">
            <div class="product-card-image">
              <?php if ($product_image) : ?>
                <img loading="lazy" src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" style="width: 100%; height: auto; display: block;">
              <?php else : ?>
                <img loading="lazy" src="<?php echo esc_url(get_template_directory_uri() . '/src/images/product-placeholder.png'); ?>" alt="Placeholder Image" style="width: 100%; height: auto; display: block;">
              <?php endif; ?>

              <?php
              $wc_product = wc_get_product($product_id);
              if ($wc_product) {
                // Check for product status indicators
                $pills = [];

                // Check if out of stock
                if (!$wc_product->is_in_stock()) {
                  $pills[] = 'Made-to-Order';
                }

                // Check if newly added (last 3 weeks)
                $post_date = get_post_time('U', false, $product_id);
                $three_weeks_ago = strtotime('-3 weeks');
                if ($post_date > $three_weeks_ago) {
                  $pills[] = 'New';
                }

                // Check if on sale
                if ($wc_product->is_on_sale()) {
                  $pills[] = 'Sale';
                }

                // Display pills if any exist
                if (!empty($pills)) :
              ?>
                  <div class="product-pills">
                    <?php foreach ($pills as $pill_text) : ?>
                      <div class="pill <?php echo strtolower(str_replace('-', '', $pill_text)); ?>"><?php echo esc_html($pill_text); ?></div>
                    <?php endforeach; ?>
                  </div>
              <?php endif;
              } ?>
            </div>
            <div class="product-card-title">
              <h4><?php echo esc_html($product_title); ?></h4>
              <?php
              if ($wc_product && $wc_product->is_in_stock()) {
                echo '<div class="product-price">';
                if ($wc_product->is_on_sale()) {
                  echo '<span class="regular-price sale">' . wp_kses_post(wc_price($wc_product->get_regular_price())) . '</span>';
                  echo '<span class="sale-price">' . wp_kses_post(wc_price($wc_product->get_sale_price())) . '</span>';
                } else {
                  echo '<span class="price">' . wp_kses_post(wc_price($wc_product->get_price())) . '</span>';
                }
                echo '</div>';
              }
              ?>
            </div>
          </a>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <?php
        $args = [
          'post_type' => 'product',
          'posts_per_page' => 4,
          'orderby' => 'date',
          'order' => 'DESC',
        ];
        $latest_products = new WP_Query($args);
  ?>
  <?php if ($latest_products->have_posts()) : ?>
    <?php while ($latest_products->have_posts()) : $latest_products->the_post(); ?>
      <div class="product-card col-3 ">
        <a href="<?php the_permalink(); ?>">
          <div class="product-card-image">
            <?php
            $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $image_gallery = get_post_meta(get_the_ID(), '_product_image_gallery', true);
            $second_image_url = '';

            if ($image_gallery) :
              $gallery_ids = explode(',', $image_gallery);
              if (!empty($gallery_ids[0])) :
                $second_image_url = wp_get_attachment_image_url($gallery_ids[0], 'full');
              endif;
            endif;
            ?>

            <?php if ($thumb_url) : ?>
              <div class="product-image-wrapper<?php echo $second_image_url ? '' : ' single-image'; ?>">
                <?php the_post_thumbnail('full', ['loading' => 'lazy', 'class' => 'product-image primary']); ?>
                <?php if ($second_image_url) : ?>
                  <img loading="lazy" src="<?php echo esc_url($second_image_url); ?>" alt="<?php the_title_attribute(); ?>" class="product-image secondary">
                <?php endif; ?>
              </div>
            <?php else : ?>
              <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
            <?php endif; ?>

            <?php
            global $product;
            if (!$product) {
              $product = wc_get_product(get_the_ID());
            }

            if ($product) {
              // Check for product status indicators
              $pills = [];

              // Check if out of stock
              if (!$product->is_in_stock()) {
                $pills[] = 'Made-to-Order';
              }

              // Check if newly added (last 3 weeks)
              $post_date = get_the_date('U');
              $three_weeks_ago = strtotime('-3 weeks');
              if ($post_date > $three_weeks_ago) {
                $pills[] = 'New';
              }

              // Check if on sale
              if ($product->is_on_sale()) {
                $pills[] = 'Sale';
              }

              // Display pills if any exist
              if (!empty($pills)) :
            ?>
                <div class="product-pills">
                  <?php foreach ($pills as $pill_text) : ?>
                    <div class="pill <?php echo strtolower(str_replace('-', '', $pill_text)); ?>"><?php echo esc_html($pill_text); ?></div>
                  <?php endforeach; ?>
                </div>
            <?php endif;
            } ?>
          </div>

          <div class="product-card-title">
            <div class="title-price-wrapper">
              <h4><?php the_title(); ?></h4>

              <?php if ($product && $product->is_in_stock()) : ?>
                <div class="product-price">
                  <?php if ($product->is_on_sale()) : ?>
                    <span class="regular-price sale"><?php echo wp_kses_post(wc_price($product->get_regular_price())); ?></span>
                    <span class="sale-price"><?php echo wp_kses_post(wc_price($product->get_sale_price())); ?></span>
                  <?php else : ?>
                    <span class="price"><?php echo wp_kses_post(wc_price($product->get_price())); ?></span>
                  <?php endif; ?>
                  <div class="product-actions">
                    <?php if (function_exists('YITH_WCWL')) : ?>
                      <?php echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="' . get_the_ID() . '" icon="fa-heart-o" browse_wishlist_text="" already_in_wishslist_text="" product_added_text=""]'); ?>
                    <?php endif; ?>
                    <a href="?add-to-cart=<?php echo get_the_ID(); ?>"
                      data-quantity="1"
                      class="button add_to_cart_button ajax_add_to_cart"
                      data-product_id="<?php echo get_the_ID(); ?>"
                      rel="nofollow"> <i class="basket-icon"></i></a>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </a>
      </div>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>
<?php endif; ?>
  </div>
  </div>
</section>