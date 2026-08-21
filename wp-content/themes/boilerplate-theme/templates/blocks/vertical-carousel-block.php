<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$vertical_carousel_subheading = get_sub_field('vertical_carousel_subheading');
$vertical_carousel_heading = get_sub_field('vertical_carousel_heading');
$vertical_carousel_heading_content = get_sub_field('vertical_carousel_heading_content');
$vertical_carousel_image = get_sub_field('vertical_carousel_image');

$vertical_carousel_items = get_sub_field('vertical_carousel_items');
?>

<section class="vertical-carousel-section">
  <div class="container">
    <!-- Header Content -->
    <div class="vertical-carousel-header">
      <?php if ($vertical_carousel_subheading): ?>
        <p class="subheading"><?php echo esc_html($vertical_carousel_subheading); ?></p>
        <h2><?php echo esc_html($vertical_carousel_heading); ?></h2>
      <?php endif; ?>

      <?php if ($vertical_carousel_heading_content): ?>
        <div class="vertical-carousel-heading-content">
          <?php echo wp_kses_post($vertical_carousel_heading_content); ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- 50/50 Split: Image and Carousel -->
    <div class="vertical-carousel-content">
      <div class="vertical-carousel-split">
        <!-- Image Column -->
        <div class="vertical-carousel-image-column">
          <?php if ($vertical_carousel_image): ?>
            <img loading="lazy" src="<?php echo esc_url($vertical_carousel_image['url']); ?>"
              alt="<?php echo esc_attr($vertical_carousel_image['alt']); ?>"
              class="vertical-carousel-image">
          <?php endif; ?>
        </div>

        <!-- Carousel Column -->
        <div class="vertical-carousel-items-column">
          <?php if ($vertical_carousel_items): ?>
            <div class="vertical-carousel-wrapper">
              <?php foreach ($vertical_carousel_items as $item): ?>
                <div class="vertical-carousel-item">
                  <?php if ($item['vertical_carousel_item_heading']): ?>
                    <h4 class="carousel-item-title"><?php echo esc_html($item['vertical_carousel_item_heading']); ?></h4>
                  <?php endif; ?>

                  <?php if ($item['vertical_carousel_item_content']): ?>
                    <div class="carousel-item-content">
                      <?php echo wp_kses_post($item['vertical_carousel_item_content']); ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
            </div>
            <div class="slick-dots-container">
              <!-- Dots will be appended here by Slick -->

            </div>
        </div>
      </div>
    </div>
</section>
<script>
  jQuery(document).ready(function($) {
    $('.vertical-carousel-wrapper').slick({
      vertical: true,
      verticalSwiping: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      dots: true,
      appendDots: $('.slick-dots-container'),

      infinite: true,
      autoplay: true,
      autoplaySpeed: 5000,
      speed: 1000,
      adaptiveHeight: false, // Prevent height adaptation
      variableWidth: false,
      responsive: [{
          breakpoint: 980,
          settings: {
            vertical: false, // Switch to horizontal on mobile
            verticalSwiping: false,
            slidesToShow: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            vertical: false,
            verticalSwiping: false,
            slidesToShow: 1
          }
        }
      ]
    });
  });
</script>