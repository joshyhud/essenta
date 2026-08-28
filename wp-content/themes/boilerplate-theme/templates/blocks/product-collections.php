<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$product_collection_subheading = get_sub_field('collections_block_subtitle');
$product_collection_heading = get_sub_field('collections_block_heading');

$collections = get_sub_field('collections');


?>

<section class="product-collections-block">
  <div class="container">
    <div class="product-collections-header">
      <?php if ($product_collection_subheading) : ?>
        <p class="eyebrow"><?php echo esc_html($product_collection_subheading); ?></p>
      <?php endif; ?>
      <?php if ($product_collection_heading) : ?>
        <h2 class="heading"><?php echo esc_html($product_collection_heading); ?></h2>
      <?php endif; ?>
    </div>
    <div class="collections-grid grid no-space">
      <?php if ($collections) : ?>
        <?php foreach ($collections as $collection) :
          $collection_id = $collection->term_id;
          $collection_title = $collection->name;
          $collection_meta = get_term_meta($collection_id);
          $collection_thumbnail_id = get_term_meta($collection_id, 'thumbnail_id', true);
          $collection_image = wp_get_attachment_url($collection_thumbnail_id);
          $collection_link = get_term_link($collection_id);

        ?>
          <div class="collection-card bordered">
            <a href="<?php echo esc_url($collection_link); ?>">
              <div class="collection-overlay"></div>
              <div class="collection-card-title">
                <p>Collection</p>
                <?php if ($collection_title) : ?>
                  <p><?php echo esc_html($collection_title); ?></p>
                <?php endif; ?>
              </div>
              <div class="collection-card-image">
                <?php if ($collection_image) : ?>
                  <img loading="lazy" src="<?php echo esc_url($collection_image); ?>">
                <?php else : ?>
                  <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
                <?php endif; ?>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php wp_reset_postdata(); ?>