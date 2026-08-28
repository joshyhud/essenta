<?php

/**
 * Accordion Block Template
 */

$accordion_image = get_sub_field('accordion_block_image');
$accordion_heading = get_sub_field('accordion_heading');
$accordion_items = get_sub_field('accordion_items');
?>

<section class="accordion-block">
  <div class="accordion-block__container">
    <div class="accordion-block__split">
      <!-- Left Side - Image -->
      <div class="accordion-block__image">
        <?php if ($accordion_image): ?>
          <img loading="lazy" src="<?php echo esc_url($accordion_image['url']); ?>"
            alt="<?php echo esc_attr($accordion_image['alt']); ?>">
        <?php endif; ?>
      </div>

      <!-- Right Side - Content -->
      <div class="accordion-block__content">
        <?php if ($accordion_heading): ?>
          <h2 class="accordion-block__heading"><?php echo esc_html($accordion_heading); ?></h2>
        <?php endif; ?>

        <?php if ($accordion_items): ?>
          <div class="accordion-block__items">
            <?php foreach ($accordion_items as $index => $item): ?>
              <details class="accordion-item" name="accordion-item">
                <summary class="accordion-item__heading">
                  <?php echo esc_html($item['accordion_item_heading']); ?>
                </summary>
                <div class="accordion-item__content">
                  <?php echo wp_kses_post($item['accordion_item_content']); ?>
                </div>
              </details>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>