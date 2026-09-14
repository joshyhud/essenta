<?php
// Blog Header
?>

<section class="blog-header">
  <div class="container">
    <div class="blog-header-content">
      <?php if (has_post_thumbnail()) : ?>
        <div class="blog-header-image">
          <?php the_post_thumbnail('large'); ?>
        </div>
      <?php endif; ?>

      <div class="blog-header-title-content">
        <div class="blog-header-meta">
          <span class="blog-header-date"><?php echo get_the_date('m.y'); ?></span>
          <?php
          $reading_time = get_post_meta(get_the_ID(), '_yoast_wpseo_estimated-reading-time-minutes', true);
          if ($reading_time) {
            echo ' • <span class="blog-header-read-time">' . $reading_time . ' min read</span>';
          }
          ?>
        </div>

        <h1 class="blog-header-title"><?php the_title(); ?></h1>
      </div>
    </div>
  </div>
</section>