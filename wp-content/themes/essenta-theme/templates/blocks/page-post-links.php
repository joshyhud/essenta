<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$page_post_subheading = get_sub_field('page_post_subheading');
$page_post_heading = get_sub_field('page_post_heading');
$page_post_items = get_sub_field('pages_and_posts');

?>

<section class="page-post-links-block">
  <div class="container">
    <div class="section-header">
      <p class="eyebrow"><?php echo esc_html($page_post_subheading); ?></p>
      <h2 class="heading"><?php echo esc_html($page_post_heading); ?></h2>
    </div>
    <?php if ($page_post_items) : ?>
      <div class="page-post-links-wrapper grid">
        <?php foreach ($page_post_items as $item) :
          $post_object = $item['page_or_post'];
          if ($post_object) :
            $post_id = $post_object->ID;
            $post_title = get_the_title($post_id);
            $post_permalink = get_permalink($post_id);
            $post_image = get_the_post_thumbnail_url($post_id, 'medium');
        ?>
            <div class="page-post-link-item">
              <a href="<?php echo esc_url($post_permalink); ?>" class="page-post-link">
                <img loading="lazy" src="<?php echo esc_url($post_image); ?>" alt="<?php echo esc_attr($post_title); ?>" />
                <span class="link-text"><?php echo esc_html($post_title); ?></span>

              </a>
            </div>
        <?php
          endif;
        endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>