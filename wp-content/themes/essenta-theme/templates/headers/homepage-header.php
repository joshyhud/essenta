<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$hero_title = get_sub_field('homepage_heading');
$hero_subtitle = get_sub_field('homepage_sub_heading');
$hero_content = get_sub_field('homepage_text');
$hero_image = get_sub_field('homepage_header_image');

$hero_cta_link = get_sub_field('homepage_cta');

?>
<div class="homepage-hero">
  <div class="container homepage-hero-content">
    <div class="homepage-hero-inner">
      <p class="eyebrow"><?php echo $hero_subtitle; ?></p>
      <h1><?php echo $hero_title; ?></h1>
      <div class="homepage-hero-text expand">
        <?php echo wp_kses_post($hero_content); ?>
      </div>
      <?php if ($hero_cta_link): ?>
        <a class="btn secondary" href="<?php echo $hero_cta_link['url']; ?>">
          <?php echo $hero_cta_link['title']; ?>
        </a>
      <?php endif; ?>

    </div>
    <?php if ($hero_image && $hero_image['type'] === 'video'): ?>
      <div class="homepage-hero-image-wrapper has-scroll-video">
        <div class="homepage-hero-image">
          <video class="homepage-hero-video" playsinline muted controls>
            <source
              src="<?php echo esc_url($hero_image['url']); ?>"
              type="video/mp4">
          </video>

          <button
            class="homepage-hero-play"
            type="button"
            aria-label="Play video">
            <span>▶</span>
          </button>
        </div>
      </div>
    <?php else: ?>
      <div class="homepage-hero-image-wrapper">
        <div class="homepage-hero-image">
          <?php if ($hero_image && $hero_image['type'] === 'image'): ?>
            <img
              loading="lazy"
              src="<?php echo esc_url($hero_image['url']); ?>"
              alt="<?php echo esc_attr($hero_image['alt']); ?>">
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

</div>