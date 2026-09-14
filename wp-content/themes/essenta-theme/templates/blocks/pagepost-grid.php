<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$posts = get_sub_field('page_or_post_selection');

?>

<section class="pagepost-grid-block">
  <div class="container grid no-space">
    <?php if ($posts) : ?>
      <?php foreach ($posts as $post) : ?>
        <?php if ($post['post_or_category'] === 'category') :
          $category = $post['product_category'];
          $cat_id = $category->term_id;
          $cat_title = $category->name;
          $cat_description = $category->description;
          $cat_meta = get_term_meta($cat_id);
          $thumbnail_id = get_term_meta($cat_id, 'thumbnail_id', true);
          $cat_image = wp_get_attachment_url($thumbnail_id);
          $cat_link = get_category_link($cat_id);
        ?>
          <div class="bordered-card">
            <div class="bordered bordered-card-title">
              <h4><?php echo esc_html($cat_title); ?></h4>
            </div>
            <div class="bordered bordered-card-image">
              <?php if ($cat_image) : ?>
                <img loading="lazy" src="<?php echo esc_url($cat_image); ?>" alt="<?php echo esc_attr($cat_title); ?>">
              <?php else : ?>
                <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
              <?php endif; ?>
            </div>
            <div class="bordered bordered-card-content">
              <p><?php echo esc_html($cat_description); ?></p>
            </div>
            <div class="bordered bordered-card-cta">
              <a href="<?php echo esc_url($cat_link); ?>" class="btn primary">Discover</a>
            </div>
          </div>
        <?php else :
          $post_id = $post['post_or_page']->ID;
          $post_extract = get_the_excerpt($post_id);
        ?>
          <div class="bordered-card">
            <div class="bordered bordered-card-title">
              <h4><?php echo get_the_title($post_id); ?></h4>
            </div>
            <div class="bordered bordered-card-image">
              <?php if (has_post_thumbnail($post_id)) : ?>
                <?php echo get_the_post_thumbnail($post_id, 'medium_large', ['loading' => 'lazy']); ?>
              <?php else : ?>
                <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
              <?php endif; ?>
            </div>
            <?php if ($post_extract): ?>
              <div class="bordered bordered-card-content">
                <p><?php echo esc_html($post_extract); ?></p>
              </div>
            <?php endif; ?>
            <div class="bordered bordered-card-cta">
              <a href="<?php echo get_permalink($post_id); ?>" class="btn primary">Discover</a>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p><?php esc_html_e('No posts or pages found.', 'boilerplate-theme'); ?></p>
    <?php endif; ?>
  </div>
</section>