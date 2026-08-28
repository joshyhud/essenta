<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$image_carousel_subheading = get_sub_field('image_carousel_subheading');
$image_carousel_heading = get_sub_field('image_carousel_heading');
$image_carousel_content = get_sub_field('image_carousel_content');
$image_carousel_cta = get_sub_field('image_carousel_cta');
$image_carousel_images = get_sub_field('image_carousel_images');
?>

<section class="image-carousel-block">
  <div class="container">
    <div class="image-carousel-header">
      <?php if ($image_carousel_subheading): ?>
        <p class="eyebrow"><?php echo esc_html($image_carousel_subheading); ?></p>
      <?php endif; ?>
      <?php if ($image_carousel_heading): ?>
        <h2 class="heading"><?php echo esc_html($image_carousel_heading); ?></h2>
      <?php endif; ?>
      <?php if ($image_carousel_content): ?>
        <div class="content"><?php echo wp_kses_post($image_carousel_content); ?></div>
      <?php endif; ?>
      <?php if ($image_carousel_cta): ?>
        <div class="cta-button">
          <a href="<?php echo esc_url($image_carousel_cta['url']); ?>" class="btn primary">
            <?php echo esc_html($image_carousel_cta['title']); ?>
          </a>
        </div>
      <?php endif; ?>
    </div>
    <?php if ($image_carousel_images): ?>
      <div class="carousel-container">
        <div class="image-carousel-slider">
          <?php foreach ($image_carousel_images as $image): ?>
            <div class="carousel-slide">
              <img loading="lazy" src="<?php echo esc_url($image['carousel_image']['url']); ?>" alt="<?php echo esc_attr($image['carousel_image']['alt']); ?>" />
            </div>
          <?php endforeach; ?>
        </div>
        <div class="slider-nav image-carousel">
          <div class="slider-dots"></div>
          <div class="slider-arrows">
            <button class="slick-prev"></button>
            <button class="slick-next"></button>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<script>
  jQuery(document).ready(function($) {
    $('.image-carousel-block').each(function() {
      var $block = $(this);
      var $slider = $block.find('.image-carousel-slider');
      var $nav = $block.find('.slider-nav.image-carousel');
      var $dots = $nav.find('.slider-dots');
      var $prev = $nav.find('.slick-prev');
      var $next = $nav.find('.slick-next');
      $slider.slick({
        slidesToShow: 2.5,
        slidesToScroll: 1,
        autoplay: false,
        infinite: false,
        autoplaySpeed: 3000,
        dots: true,
        arrows: true,
        prevArrow: $prev,
        nextArrow: $next,
        appendDots: $dots,
        responsive: [{
          breakpoint: 980,
          settings: {
            slidesToShow: 1,
          }
        }]
      });
    });
  });
</script>