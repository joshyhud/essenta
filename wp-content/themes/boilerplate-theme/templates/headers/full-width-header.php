<?php
// Full Width Header Template
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$full_width_sutitle = get_sub_field('full_width_sub_title');
$full_width_heading_text = get_sub_field('full_width_heading_text');
$full_width_content = get_sub_field('full_header_content');

$full_width_image = get_sub_field('full_header_image');
?>

<section class="full-width-header">
  <div class="container">
    <div class="header-content-row">
      <div class="header-left">
        <h1 class="subheading"><?php echo esc_html($full_width_sutitle); ?></h1>
        <h2><?php echo esc_html($full_width_heading_text); ?></h2>
      </div>
      <div class="header-right">
        <div class="full-width-content">
          <?php echo $full_width_content; ?>
        </div>
      </div>
    </div>
    <div class="header-image-row">
      <img loading="lazy" src="<?php echo esc_url($full_width_image['url']); ?>" alt="<?php echo esc_attr($full_width_image['alt']); ?>" class="full-width-image">
    </div>
  </div>
</section>