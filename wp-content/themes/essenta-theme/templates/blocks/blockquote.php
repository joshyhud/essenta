<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$central_or_left_aligned = get_sub_field('central_or_left_aligned');
$left_heading = get_sub_field('left_aligned_heading');

$quotation = get_sub_field('quotation');

?>

<section class="blockquote-block ">
  <div class="container 
  <?php
  if ($central_or_left_aligned === 'left') {
    echo 'left-aligned-blockquote';
  } else {
    echo 'centered-blockquote';
  }
  ?>">
    <?php
    if ($central_or_left_aligned === 'left' && $left_heading) {
      echo '<h2 class="left-aligned-heading">' . esc_html($left_heading) . '</h2>';
    }
    ?>
    <blockquote class="blockquote-content">
      <?php echo $quotation->post_content; ?>
    </blockquote>
    <cite class="blockquote-citation">
      <?php if (get_field('star_rating', $quotation->ID)) : ?>
        <div class="rating">
          <?php
          $rating = get_field('star_rating', $quotation->ID);
          for ($i = 0; $i < 5; $i++) {
            if ($i < $rating) {
              echo '<span class="star filled">&#9733;</span>'; // filled star
            } else {
              echo '<span class="star">&#9734;</span>'; // empty star
            }
          }
          ?>
        </div>
      <?php endif; ?>
      <?php echo esc_html($quotation->post_title); ?>
    </cite>
  </div>
</section>