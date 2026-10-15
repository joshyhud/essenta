<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$tex_carousel_heading = get_sub_field('text_carousel_heading');
$text_carousel_items = get_sub_field('text_carousel_items'); ?>

<section class="text-carousel-block">
  <div class="container text-carousel-content">
    <div class="text-carousel-header">
      <?php if ($tex_carousel_heading): ?>
        <h2 class="text-carousel-heading"><?php echo esc_html($tex_carousel_heading); ?></h2>
      <?php endif; ?>
      <div class="slider-nav text-carousel">
        <div class="slider-dots"></div>
        <div class="slider-arrows">
          <button class="slick-prev"></button>
          <button class="slick-next"></button>
        </div>
      </div>
    </div>
    <?php if ($text_carousel_items): ?>
      <div class="text-carousel-slider">
        <?php foreach ($text_carousel_items as $item):
          $item_content = $item['text_carousel_item']; ?>
          <div class="text-carousel-item">
            <?php if ($item_content): ?>
              <div class="item-content">
                <?php echo wp_kses_post($item_content); ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
