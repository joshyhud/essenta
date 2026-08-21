<?php

if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

$tabled_contents = get_sub_field('table_contents');
?>

<section class="tabled-content-block">
  <div class="container">
    <div class="tabled-content-wrapper grid no-space ">
      <?php
      if ($tabled_contents) {
        foreach ($tabled_contents as $index => $content) {
          $content_heading = $content['tabled_content_heading'];
          $content_body = $content['tabled_content_text'];
      ?>
          <div class="tabled-content-item" data-index="<?php echo $index; ?>">
            <h3 class="content-heading heading"><?php echo esc_html($content_heading); ?></h3>
            <div class="content-body">
              <?php echo wp_kses_post(wpautop($content_body)); ?>
            </div>
          </div>
      <?php
        }
      }
      ?>
    </div>
  </div>
</section>