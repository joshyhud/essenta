<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$two_col_image = get_sub_field('two_column_image');
$two_col_heading = get_sub_field('two_column_header');
$two_col_content = get_sub_field('two_column_text');
?>

<section class="two-col-media-text">
  <div class="container">
    <div class="media-half">
      <div class="media-half-inner">
        <?php if ($two_col_image): ?>
          <img loading="lazy" src="<?php echo esc_url($two_col_image['url']); ?>" alt="<?php echo esc_attr($two_col_image['alt']); ?>" />
        <?php endif; ?>
      </div>
    </div>
    <div class="text-half">
      <div class="text-half-header">
        <h2><?php echo esc_html($two_col_heading); ?></h2>
      </div>
      <div class="text-cols">
        <?php echo wp_kses_post($two_col_content); ?>
      </div>
    </div>
  </div>
  </div>
</section>