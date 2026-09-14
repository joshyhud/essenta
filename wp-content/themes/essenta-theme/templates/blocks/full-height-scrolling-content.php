<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}


$sitcky_left_subheading = get_sub_field('sticky_left_subheading');
$sitcky_left_heading = get_sub_field('sticky_left_heading');
$sitcky_left_content = get_sub_field('sticky_left_content');
$sticky_left_cta = get_sub_field('sticky_left_cta');

$scrolling_contents = get_sub_field('scrolling_contents');
?>

<section class="full-height-scrolling-content-section">
  <div class="container full-height-scrolling-content-container ">
    <div class="sticky-left">
      <div class="sticky-left-content">
        <?php if ($sitcky_left_subheading): ?>
          <p class="eyebrow"><?php echo esc_html($sitcky_left_subheading); ?></p>
        <?php endif; ?>

        <?php if ($sitcky_left_heading): ?>
          <h2 class="heading"><?php echo esc_html($sitcky_left_heading); ?></h2>
        <?php endif; ?>

        <?php if ($sitcky_left_content): ?>
          <div class="content-text">
            <?php echo wp_kses_post($sitcky_left_content); ?>
          </div>
        <?php endif; ?>

        <?php if ($sticky_left_cta): ?>
          <div class="sticky-left-cta">
            <a href="<?php echo esc_url($sticky_left_cta['url']); ?>" class="btn primary">
              <?php echo esc_html($sticky_left_cta['title']); ?>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="scrolling-contents">
      <?php if ($scrolling_contents): ?>
        <?php foreach ($scrolling_contents as $content): ?>

          <div class="scrolling-content-item">
            <?php if ($content['scrolling_content_image']): ?>
              <div class="scrolling-content-image">
                <img loading="lazy" src="<?php echo esc_url($content['scrolling_content_image']['url']); ?>" alt="<?php echo esc_attr($content['scrolling_content_image']['alt']); ?>">
              </div>
            <?php endif; ?>
            <?php if ($content['scrolling_content_heading']): ?>
              <h3 class="scrolling-content-heading"><?php echo esc_html($content['scrolling_content_heading']); ?></h3>
            <?php endif; ?>

            <?php if ($content['scrolling_content_text']): ?>
              <div class="scrolling-content-text">
                <?php echo wp_kses_post($content['scrolling_content_text']); ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>