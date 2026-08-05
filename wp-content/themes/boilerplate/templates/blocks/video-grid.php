<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$videoGird = get_sub_field('video_grid');
?>

<section class="video-grid">
  <div class="container">
    <div class="video-grid-inner">
      <?php if ($videoGird): ?>
        <?php foreach ($videoGird as $video): ?>
          <div class="video-item">
            <div class="video-item-inner">
              <div class="video-item-image">
                <iframe src="<?php echo $video['video_link']['url']; ?>" frameborder="0" allowfullscreen></iframe>
              </div>
              <div class="video-item">
                <h5><?php echo $video['video_title']; ?></h5>
                <?php echo $video['video_text']; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>