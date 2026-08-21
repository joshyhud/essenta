<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$bordered_subheading = get_sub_field('bordered_content_subheading');
$bordered_heading = get_sub_field('bordered_content_heading');
$bordered_subtitle = get_sub_field('bordered_content_subtitle');

$bordered_contents = get_sub_field('bordered_contents');
?>

<section class="bordered-content-section">
  <div class="container">
    <div class="section-header text-center">
      <?php if ($bordered_subheading): ?>
        <p class="subheading"><?php echo esc_html($bordered_subheading); ?></p>
      <?php endif; ?>

      <?php if ($bordered_heading): ?>
        <h2 class="heading"><?php echo esc_html($bordered_heading); ?></h2>
      <?php endif; ?>

      <?php if ($bordered_subtitle): ?>
        <p class="subtitle"><?php echo esc_html($bordered_subtitle); ?></p>
      <?php endif; ?>
    </div>

  </div>
  <div class="bordered-contents grid">
    <?php if ($bordered_contents): ?>
      <?php foreach ($bordered_contents as $content):
      ?>
        <div class="bordered-content-item bordered-card">
          <?php if ($content['bordered_content_item_heading']): ?>
            <h4 class="content-heading bordered"><?php echo esc_html($content['bordered_content_item_heading']); ?></h4>
          <?php endif; ?>

          <div class="bordered-content-image bordered">
            <img loading="lazy" src="<?php echo esc_url($content['bordered_content_item_image']['url']); ?>" alt="<?php echo esc_attr($content['bordered_content_item_image']['alt']); ?>">
          </div>


          <?php if ($content['bordered_content_item_text']): ?>
            <div class="content-text bordered">
              <?php echo wp_kses_post($content['bordered_content_item_text']); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>