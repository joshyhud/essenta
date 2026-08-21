<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$testimonial_slider_subheading = get_sub_field('testimonial_slider_subheading');
$testimonial_slider_heading = get_sub_field('testimonial_slider_heading');
$testimonial_slider_standout = get_sub_field('testimonial_slider_standout');

$testimonail_total = wp_count_posts('testimonial')->publish;
$testimonial_count = 0;

$args = array(
  'post_type' => 'testimonial',
  'posts_per_page' => -1
);
$testimonials = new WP_Query($args);

if ($testimonials->have_posts()) : ?>
  <section class="testimonial-slider-section">
    <div class="container">

      <div class="testimonial-slider-header">
        <?php if ($testimonial_slider_subheading) : ?>
          <p class="subheading"><?php echo esc_html($testimonial_slider_subheading); ?></p>
        <?php endif; ?>
        <?php if ($testimonial_slider_heading) : ?>
          <h2 class="heading"><?php echo esc_html($testimonial_slider_heading); ?></h2>
        <?php endif; ?>
      </div>

      <div class="testimonials-wrapper">
        <?php if ($testimonial_slider_standout) : ?>
          <div class="standout-text">
            <div class="standout-text--inner">
              <?php echo $testimonial_slider_standout; ?>
            </div>
          </div>
        <?php endif; ?>

        <div class="slider-half">
          <div class="testimonial-slider">
            <?php while ($testimonials->have_posts()) : $testimonials->the_post(); ?>
              <div class="testimonial-card slide">
                <div class="quote-rating">
                  <div class="testimonial-number">
                    <div class="current"><?php echo sprintf('%03d', $testimonial_count + 1); ?> </div> / <div class="total"><?php echo sprintf('%03d', $testimonail_total); ?></div>
                  </div>
                  <?php if (get_field('star_rating')) : ?>
                    <div class="rating">
                      <?php
                      $rating = get_field('star_rating');
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
                </div>
                <div class=" testimonial-content">
                  <?php the_content(); ?>
                </div>
                <div class="testimonial-author">
                  <?php the_title(); ?>
                </div>
              </div>
              <?php $testimonial_count++; ?>
            <?php endwhile; ?>
          </div>
          <div class="slider-nav testimonials">
            <div class="slider-dots"></div>
            <div class="slider-arrows">
              <button class="slick-prev"></button>
              <button class="slick-next"></button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
<?php endif;
wp_reset_postdata(); ?>

<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('.testimonial-slider').on('setPosition', function() {
      var slickTrack = $(this).find('.slick-track');
      var slickTrackHeight = $(slickTrack).height();
      $(this).find('.slide').css('height', slickTrackHeight + 'px');
    });

    $('.testimonial-slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      infinite: false,
      dots: true,
      arrows: true,
      prevArrow: $('.slider-nav.testimonials .slick-prev'),
      nextArrow: $('.slider-nav.testimonials .slick-next'),
      appendDots: $('.slider-nav.testimonials .slider-dots'),
      adaptiveHeight: true,
      responsive: [{
          breakpoint: 1440,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          }
        }
      ]
    });
  });
</script>


</style>