<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$testimonial_title = get_sub_field('testimonials_header');

$args = array(
  'post_type' => 'testimonial',
  'posts_per_page' => -1
);
$testimonials = new WP_Query($args);
if ($testimonials->have_posts()) : ?>
  <section class="testimonial-slider-section">
    <div class="container">
      <h2><?php echo esc_html($testimonial_title); ?></h2>
      <div class="testimonial-slider testimonial-slider-1">
        <?php while ($testimonials->have_posts()) : $testimonials->the_post();

          $post_id = get_the_ID();

          $rating = get_field('star_rating', $post_id);
          $testimonial_content = get_field('testimonial_content', $post_id);
          $testimonial_image = get_field('user_image', $post_id);
        ?>
          <div class="testimonial-card slide">
            <div class="testimonial-content">
              <?php if ($rating) : ?>
                <div class="testimonial-rating">
                  <div class="stars">
                    <?php for ($i = 0; $i < $rating; $i++) { ?>
                      <span class="star filled">★</span>
                    <?php } ?>
                  </div>
                  <i class="icon"></i>
                </div>
              <?php endif; ?>
              <?php echo wp_kses_post($testimonial_content); ?>
            </div>
            <div class="testimonial-author">
              <?php
              if ($testimonial_image) :
                echo wp_get_attachment_image($testimonial_image, 'thumbnail', false, array('class' => 'testimonial-avatar'));
              else :
                echo '<div class="testimonial-avatar placeholder-avatar"></div>';
              endif;
              ?>
              <p><?php the_title(); ?></p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      <div class="testimonial-slider testimonial-slider-2" dir="rtl">
        <?php while ($testimonials->have_posts()) : $testimonials->the_post();

          $post_id = get_the_ID();

          $rating = get_field('star_rating', $post_id);
          $testimonial_content = get_field('testimonial_content', $post_id);
          $testimonial_image = get_field('user_image', $post_id);
        ?>
          <div class="testimonial-card slide">
            <div class="testimonial-content">
              <?php if ($rating) : ?>
                <div class="testimonial-rating">
                  <div class="stars">
                    <?php for ($i = 0; $i < $rating; $i++) { ?>
                      <span class="star filled">★</span>
                    <?php } ?>
                  </div>
                  <i class="icon"></i>
                </div>
              <?php endif; ?>
              <?php echo wp_kses_post($testimonial_content); ?>
            </div>
            <div class="testimonial-author">
              <?php
              if ($testimonial_image) :
                echo wp_get_attachment_image($testimonial_image, 'thumbnail', false, array('class' => 'testimonial-avatar'));
              else :
                echo '<div class="testimonial-avatar placeholder-avatar"></div>';
              endif;
              ?>
              <p><?php the_title(); ?></p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </section>
<?php endif;
wp_reset_postdata(); ?>