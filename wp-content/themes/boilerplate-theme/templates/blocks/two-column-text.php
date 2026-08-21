<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$two_col_sub_heading = get_sub_field('two_col_sub_heading');
$two_col_heading = get_sub_field('two_col_heading');
$two_col_content = get_sub_field('two_col_content');
?>

<section class="two-column-text">
  <div class="content-half">
    <div class="content-half-inner">
      <p class="subheading"><?php echo esc_html($two_col_sub_heading); ?></p>
      <h2><?php echo esc_html($two_col_heading); ?></h2>
    </div>
  </div>
  <div class="content-half">
    <div class="content-half-inner">
      <p><?php echo $two_col_content; ?></p>
    </div>
  </div>
</section>