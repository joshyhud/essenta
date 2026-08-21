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
<div class="homepage-hero" style="background-image: url('<?php echo esc_url($hero_image['url']); ?>');">
  <div class="overlay"></div>
  <div class="container homepage-hero-content">
    <div class="homepage-hero-inner">
      <h1><?php echo $hero_title; ?></h1>
      <h5><?php echo $hero_subtitle; ?></h5>
      <?php echo $hero_content; ?>
      <?php if ($hero_cta_link): ?>
        <a class="btn secondary" href="<?php echo $hero_cta_link['url']; ?>">
          <?php echo $hero_cta_link['title']; ?>
        </a>
      <?php endif; ?>

    </div>
  </div>

</div>