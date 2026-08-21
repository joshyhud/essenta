<?php
defined('ABSPATH') || exit;

get_header();

$user_id = get_current_user_id();

// Get YITH wishlist products
$product_ids = [];

if (class_exists('YITH_WCWL_Wishlist_Factory')) {

  $wishlist = null;

  // If logged in, try default wishlist first
  if (is_user_logged_in()) {
    $wishlist = YITH_WCWL_Wishlist_Factory::get_default_wishlist(get_current_user_id());
  }

  // Fallback: current wishlist (covers guests / cookie-based)
  if (!$wishlist) {
    $wishlist = YITH_WCWL_Wishlist_Factory::get_current_wishlist();
  }

  if ($wishlist && method_exists($wishlist, 'get_items')) {
    $items = $wishlist->get_items();

    foreach ($items as $item) {
      // Item method names can differ slightly by version, so we guard them
      if (is_object($item)) {
        if (method_exists($item, 'get_product_id')) {
          $pid = (int) $item->get_product_id();
        } elseif (method_exists($item, 'get_prod_id')) {
          $pid = (int) $item->get_prod_id();
        } else {
          $pid = 0;
        }

        if ($pid) $product_ids[] = $pid;
      }
    }
  }
}

$product_ids = array_values(array_unique(array_filter($product_ids)));

?>

<div class="archive-header">
  <div class="archive-header-inner">
    <h1 class="archive-title subheading"><?php the_title(); ?></h1>
    <h2 class="archive-heading heading">Your Wishlist</h2>
  </div>
</div>

<?php if (!empty($product_ids)) : ?>
  <section class="archive-products">
    <div class="container">
      <div class="archive-filtering">
        <div class="filter-bar">
          <div class="filter-bar-left">
            <button class="remove-all-wishlist btn secondary" id="removeAllWishlist" type="button">
              <span>Remove All</span>
              <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                <path d="M1 1L16 16M16 1L1 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
          <div class="filter-bar-right">
            <div class="results-count">
              <span><?php echo count($product_ids); ?></span> Results
            </div>
            <div class="sort-dropdown">
              <label for="wishlistSort">Sort By:</label>
              <select id="wishlistSort">
                <option value="default">Default</option>
                <option value="name-asc">Name A&ndash;Z</option>
                <option value="name-desc">Name Z&ndash;A</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
                <option value="date-desc">Newest</option>
                <option value="date-asc">Oldest</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="products-grid grid">

        <?php
        // Query products in the wishlist
        $q = new WP_Query([
          'post_type'      => 'product',
          'post_status'    => 'publish',
          'posts_per_page' => 24,
          'post__in'       => $product_ids,
          'orderby'        => 'post__in',
        ]);
        ?>

        <?php if ($q->have_posts()) : ?>
          <?php while ($q->have_posts()) : $q->the_post(); ?>
            <?php
            $product = wc_get_product(get_the_ID());
            if (!$product) continue;
            ?>

            <div class="product-card"
              data-product-name="<?php echo esc_attr(get_the_title()); ?>"
              data-product-price="<?php echo esc_attr($product->get_price()); ?>"
              data-product-date="<?php echo esc_attr(get_the_date('Y-m-d H:i:s')); ?>"
              data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
              <a href="<?php the_permalink(); ?>">
                <div class="product-card-image">
                  <?php
                  $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                  $second_image_url = '';

                  // Get second image if available
                  $attachment_ids = $product->get_gallery_image_ids();
                  if (!empty($attachment_ids)) {
                    $second_image_url = wp_get_attachment_image_url($attachment_ids[0], 'full');
                  }

                  if ($thumb_url) : ?>
                    <div class="product-image-wrapper<?php echo $second_image_url ? '' : ' single-image'; ?>">
                      <img loading="lazy" src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>" class="product-image primary">
                      <?php if ($second_image_url) : ?>
                        <img loading="lazy" src="<?php echo esc_url($second_image_url); ?>" alt="<?php the_title_attribute(); ?>" class="product-image secondary">
                      <?php endif; ?>
                    </div>
                  <?php else : ?>
                    <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
                  <?php endif; ?>

                  <?php
                  $pills = [];

                  if (!$product->is_in_stock()) {
                    $pills[] = 'Made-to-Order';
                  }

                  $post_date = get_the_date('U');
                  $three_weeks_ago = strtotime('-3 weeks');
                  if ($post_date > $three_weeks_ago) {
                    $pills[] = 'New';
                  }

                  if ($product->is_on_sale()) {
                    $pills[] = 'Sale';
                  }

                  if (!empty($pills)) :
                  ?>
                    <div class="product-pills">
                      <?php foreach ($pills as $pill_text) : ?>
                        <div class="pill <?php echo strtolower(str_replace('-', '', $pill_text)); ?>"><?php echo esc_html($pill_text); ?></div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </a>

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
                        <?php if ($product && $product->is_in_stock()) : ?>
                          <a href="?add-to-cart=<?php echo get_the_ID(); ?>"
                            data-quantity="1"
                            class="button add_to_cart_button ajax_add_to_cart"
                            data-product_id="<?php echo get_the_ID(); ?>"
                            rel="nofollow"> <i class="basket-icon"></i></a>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
        <?php endif; ?>

      </div>
    </div>
  </section>

<?php else : ?>
  <div class="products-grid grid">
    <p><?php echo esc_html__('Your wishlist is empty.', 'your-textdomain'); ?></p>
  </div>
<?php endif; ?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var sortSelect = document.getElementById('wishlistSort');
    var grid = document.querySelector('.products-grid.grid');
    var removeBtn = document.getElementById('removeAllWishlist');

    // Sort
    if (sortSelect && grid) {
      sortSelect.addEventListener('change', function() {
        var cards = Array.prototype.slice.call(grid.querySelectorAll('.product-card'));
        var val = this.value;

        cards.sort(function(a, b) {
          switch (val) {
            case 'name-asc':
              return a.dataset.productName.localeCompare(b.dataset.productName);
            case 'name-desc':
              return b.dataset.productName.localeCompare(a.dataset.productName);
            case 'price-asc':
              return (parseFloat(a.dataset.productPrice) || 0) - (parseFloat(b.dataset.productPrice) || 0);
            case 'price-desc':
              return (parseFloat(b.dataset.productPrice) || 0) - (parseFloat(a.dataset.productPrice) || 0);
            case 'date-desc':
              return new Date(b.dataset.productDate) - new Date(a.dataset.productDate);
            case 'date-asc':
              return new Date(a.dataset.productDate) - new Date(b.dataset.productDate);
            default:
              return 0;
          }
        });

        cards.forEach(function(card) {
          grid.appendChild(card);
        });
      });
    }

    // Remove All
    if (removeBtn) {
      removeBtn.addEventListener('click', function() {
        if (!confirm('Remove all items from your wishlist?')) return;

        removeBtn.disabled = true;
        removeBtn.querySelector('span').textContent = 'Removing…';

        var formData = new FormData();
        formData.append('action', 'theme_clear_wishlist');
        formData.append('nonce', '<?php echo wp_create_nonce('theme_clear_wishlist'); ?>');

        fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
          })
          .then(function(res) {
            return res.json();
          })
          .then(function(data) {
            if (data.success) {
              window.location.reload();
            } else {
              alert('Could not clear wishlist. Please try again.');
              removeBtn.disabled = false;
              removeBtn.querySelector('span').textContent = 'Remove All';
            }
          })
          .catch(function() {
            alert('Could not clear wishlist. Please try again.');
            removeBtn.disabled = false;
            removeBtn.querySelector('span').textContent = 'Remove All';
          });
      });
    }
  });
</script>

<?php
get_template_part('page-builder');
get_footer();
