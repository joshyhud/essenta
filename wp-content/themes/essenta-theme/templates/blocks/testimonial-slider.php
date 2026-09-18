<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$testimonial_type = get_sub_field('testimonial_type');
$testimonial_selection = get_sub_field('select_your_testimonials');

$testimonials_to_display = array();

if ($testimonial_type === 'man' && $testimonial_selection) {
  foreach ($testimonial_selection as $selected_testimonial) {
    if (is_object($selected_testimonial)) {
      $testimonials_to_display[] = array(
        'testimonial_id'      => $selected_testimonial->ID,
        'testimonial_text'    => apply_filters('the_content', get_post_field('post_content', $selected_testimonial->ID)),
        'testimonial_author'  => get_the_title($selected_testimonial->ID),
      );
    }
  }
} else {
  $auto_testimonials = new WP_Query(array(
    'post_type'      => 'testimonial',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
  ));

  while ($auto_testimonials->have_posts()) {
    $auto_testimonials->the_post();

    $testimonials_to_display[] = array(
      'testimonial_id'     => get_the_ID(),
      'testimonial_text'   => apply_filters('the_content', get_the_content()),
      'testimonial_author' => get_the_title(),
    );
  }

  wp_reset_postdata();
}

$testimonial_total = count($testimonials_to_display);

if ($testimonial_total) : ?>
  <section class="testimonial-slider-block">
    <div class="container">
      <div class="testimonials-wrapper">
        <div class="testimonials-block-slider">
          <?php foreach ($testimonials_to_display as $testimonial_index => $testimonial) : ?>
            <div class="testimonial">
              <div class="testimonial-text"><?php echo wp_kses_post($testimonial['testimonial_text']); ?></div>
              <span class="testimonial-author eyebrow"><?php echo esc_html($testimonial['testimonial_author']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
        <?php if ($testimonial_total > 1) : ?>
          <div class="testimonials-block-slider-nav testimonials">
            <div class="slider-dots"></div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('.testimonials-block-slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      infinite: false,
      dots: true,
      arrows: false,
      appendDots: $('.testimonials-block-slider-nav.testimonials .slider-dots'),
      adaptiveHeight: false
    });
  });
</script>