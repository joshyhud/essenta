<?php

/**
 * FAQ Accordion Block
 */

$faq_style = get_sub_field('faq_style');
$accordion_image = get_sub_field('faq_images');
$accordion_image_2 = get_sub_field('faq_images_2');
$accordion_heading = get_sub_field('faq_header');
$accordion_cta = get_sub_field('faq_page_link');

?>

<section class="accordion-block <?php echo esc_attr($faq_style); ?>">
  <div class="container">
    <div class="accordion-block__split grid">

      <div class="accordion-block__images">
        <!-- Left Side - Image -->
        <div class="accordion-block__image">
          <?php if ($accordion_image): ?>
            <img loading="lazy" src="<?php echo esc_url($accordion_image['url']); ?>"
              alt="<?php echo esc_attr($accordion_image['alt']); ?>">
          <?php endif; ?>
        </div>
        <div class="accordion-block__image">
          <?php if ($accordion_image_2): ?>
            <img loading="lazy" src="<?php echo esc_url($accordion_image_2['url']); ?>"
              alt="<?php echo esc_attr($accordion_image_2['alt']); ?>">
          <?php endif; ?>
        </div>
      </div>

      <!-- Right Side - Content -->
      <div class="accordion-block__content">
        <div class="accordion-block__content-inner">
          <?php if ($accordion_heading): ?>
            <h3 class="accordion-block__heading"><?php echo esc_html($accordion_heading); ?></h3>
          <?php endif; ?>

          <?php
          $faq_posts = get_posts([
            'post_type' => 'faq',
            'numberposts' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC'
          ]);

          if ($faq_posts):
          ?>
            <div class="accordion-block__items">
              <?php foreach ($faq_posts as $faq): ?>
                <details class="accordion-item">
                  <summary class="accordion-item__heading">
                    <?php echo esc_html(get_field('faq_question', $faq->ID)); ?>
                  </summary>
                  <div class="accordion-item__content">
                    <?php echo wp_kses_post(get_field('faq_answer', $faq->ID)); ?>
                  </div>
                </details>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if ($accordion_cta): ?>
            <a class="btn primary" href="<?php echo esc_url($accordion_cta['url']); ?>" class="accordion-block__cta">
              <?php echo esc_html($accordion_cta['title']); ?>
            </a>
          <?php endif; ?>
        </div>

      </div>
    </div>
</section>