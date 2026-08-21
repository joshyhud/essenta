<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$products_gallery_items = get_sub_field('product_gallery_items');

?>

<section class="products-gallery-block">
  <div class="container">
    <div class="products-gallery-grid">
      <?php
      if ($products_gallery_items) {
        $product_count = count($products_gallery_items);
        $index = 0;

        foreach ($products_gallery_items as $product) {
          $product_link = get_permalink($product->ID);
          $product_image = get_the_post_thumbnail_url($product->ID, 'full');
          $product_title = get_the_title($product->ID);

          // Determine position in pattern (3, 1, 2, 2 repeating)
          $position_in_pattern = $index % 6;
          $grid_class = '';

          if ($position_in_pattern < 3) {
            // First row: 3 items
            $grid_class = 'col-4';
          } elseif ($position_in_pattern == 3) {
            // Second row: 1 item
            $grid_class = 'col-12';
          } else {
            // Third row: 2 items
            $grid_class = 'col-6';
          }
      ?>
          <div class="product-item <?php echo $grid_class; ?>">
            <a href="<?php echo esc_url($product_link); ?>">
              <?php if ($product_image) : ?>
                <img loading="lazy" src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>">
              <?php endif; ?>
              <h3 class="product-title"><?php echo esc_html($product_title); ?></h3>
            </a>
          </div>
      <?php
          $index++;
        }
      }
      ?>
    </div>
  </div>
</section>
<style>
  .products-gallery-block {
    padding: 60px 0;
  }

  .container {
    padding: 0 20px;
  }

  .products-gallery-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 20px;
  }

  .product-item {
    display: flex;
    flex-direction: column;
  }

  .product-item.col-4 {
    grid-column: span 4;
  }

  .product-item.col-6 {
    grid-column: span 6;
  }

  .product-item.col-12 {
    grid-column: span 12;
  }

  .product-item a {
    text-decoration: none;
    color: inherit;
    display: block;
  }

  .product-item img {
    width: 100%;
    height: 530px;
    object-fit: cover;
  }

  .product-item.col-12 img {
    height: 530px;
  }

  .product-title {
    font-size: 12px;
    font-style: normal;
    font-weight: 300;
    line-height: 18px;
    /* 150% */
    letter-spacing: 1.2px;
    text-transform: uppercase;
  }

  @media (max-width: 768px) {
    .products-gallery-grid {
      grid-template-columns: 1fr;
      gap: 15px;
    }

    .product-item.col-4,
    .product-item.col-6,
    .product-item.col-12 {
      grid-column: span 1;
    }

    .product-item img {
      height: 358px;
    }

    .product-item.col-12 img {
      height: 400px;
    }
  }
</style>