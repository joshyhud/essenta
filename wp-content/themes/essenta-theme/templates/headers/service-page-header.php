<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$heading_image = get_sub_field('header_image');
$heading_title = get_sub_field('header_heading');
$service_header_subheading = get_sub_field('service_header_subheading');
$heading_text = get_sub_field('header_text');
$heading_button_text = get_sub_field('header_cta_text');
$heading_button_link = get_sub_field('header_cta');

$page_link = get_permalink($heading_button_link->ID);
?>

<div class="cta-header">

  <div class="header-half" style="background-image: url('<?php echo esc_url($heading_image['url']); ?>');">

  </div>
  <div class="header-half">
    <div class="header-content">
      <h1 class="subheading"><?php echo $service_header_subheading; ?></h1>
      <p class="heading"><?php echo $heading_title; ?></p>
      <p><?php echo $heading_text; ?></p>
      <?php if ($page_link) : ?>
        <a href="<?php echo $page_link; ?>" class="btn primary"><?php echo $heading_button_text; ?></a>
      <?php endif; ?>
    </div>

  </div>
</div>