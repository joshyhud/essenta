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
  </div>
  <?php if ($hero_image && $hero_image['type'] === 'video'): ?>
    <div class="homepage-hero-image-wrapper has-video">
      <div class="homepage-hero-image has-video" data-hero-video-modal="homepage-hero-video-modal">
        <video class="homepage-hero-video" muted playsinline preload="metadata" fetchpriority="high" poster="<?php echo esc_url(get_the_post_thumbnail_url($hero_image['ID'], 'full')); ?>">
          <source
            src="<?php echo esc_url($hero_image['url']); ?>"
            type="video/mp4">
        </video>

        <button
          class="homepage-hero-play"
          type="button"
          aria-label="<?php esc_attr_e('Play video', 'essenta-theme'); ?>">
          <span>▶</span>
        </button>
      </div>
    </div>
  <?php else: ?>
    <div class="homepage-hero-image-wrapper">
      <div class="homepage-hero-image">
        <?php if ($hero_image && $hero_image['type'] === 'image'): ?>
          <img fetchpriority="high"
            src="<?php echo esc_url($hero_image['url']); ?>"
            alt="<?php echo esc_attr($hero_image['alt']); ?>">
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

</div>
<?php if ($hero_image && $hero_image['type'] === 'video'): ?>
  <div class="homepage-hero-video-modal" id="homepage-hero-video-modal" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="homepage-hero-video-modal-overlay" data-hero-video-modal-close></div>
    <div class="homepage-hero-video-modal-inner">
      <button type="button" class="homepage-hero-video-modal-close" aria-label="<?php esc_attr_e('Close', 'essenta-theme'); ?>" data-hero-video-modal-close></button>
      <video class="homepage-hero-video-modal-video" src="<?php echo esc_url($hero_image['url']); ?>" controls playsinline preload="none"></video>
    </div>
  </div>
<?php endif; ?>