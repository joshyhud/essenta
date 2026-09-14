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
      <p class="eyebrow" ><?php echo $hero_subtitle; ?></p>
      <h1><?php echo $hero_title; ?></h1>
      <div class="homepage-hero-text">
        <?php echo $hero_content; ?>
      </div>
      <?php if ($hero_cta_link): ?>
        <a class="btn secondary" href="<?php echo $hero_cta_link['url']; ?>">
          <?php echo $hero_cta_link['title']; ?>
        </a>
      <?php endif; ?>

    </div>
    <div class="homepage-hero-image">
      <?php if ($hero_image): ?>

        <?php if ($hero_image['type'] === 'image'): ?>

          <img
            loading="lazy"
            src="<?php echo esc_url($hero_image['url']); ?>"
            alt="<?php echo esc_attr($hero_image['alt']); ?>"
          >

        <?php elseif ($hero_image['type'] === 'video'): ?>

          <video class="homepage-hero-video" controls>
            <source
              src="<?php echo esc_url($hero_image['url']); ?>"
              type="video/mp4"
            >
          </video>

          <button
            class="homepage-hero-play"
            type="button"
            aria-label="Play video"
          >
            <span>▶</span>
          </button>

        <?php endif; ?>

      <?php endif; ?>
    </div>
  </div>

</div>