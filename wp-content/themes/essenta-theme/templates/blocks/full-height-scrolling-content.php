<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}


$sitcky_left_subheading = get_sub_field('sticky_left_subheading');
$sitcky_left_heading = get_sub_field('sticky_left_heading');
$sitcky_left_content = get_sub_field('sticky_left_content');
$sticky_left_cta = get_sub_field('sticky_left_cta');

$scrolling_contents = get_sub_field('scrolling_contents');
?>

<section class="full-height-scrolling-content">
  <div class="container">
    <div class="sticky-left">
      <div class="sticky-left-content">
        <?php if ($sitcky_left_subheading): ?>
          <p class="eyebrow"><?php echo esc_html($sitcky_left_subheading); ?></p>
        <?php endif; ?>

        <?php if ($sitcky_left_heading): ?>
          <h2 class="heading"><?php echo esc_html($sitcky_left_heading); ?></h2>
        <?php endif; ?>

        <?php if ($sitcky_left_content): ?>
          <div class="content-text">
            <?php echo wp_kses_post($sitcky_left_content); ?>
          </div>
        <?php endif; ?>

        <?php if ($sticky_left_cta): ?>
          <div class="sticky-left-cta">
            <a href="<?php echo esc_url($sticky_left_cta['url']); ?>" class="btn primary">
              <?php echo esc_html($sticky_left_cta['title']); ?>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="scrolling-contents-wrapper">
      <div class="scrolling-contents">
        <?php if ($scrolling_contents): ?>
          <?php foreach ($scrolling_contents as $content):
            $case_study = $content['case_study_link'];
            $case_study_id = $case_study ? $case_study->ID : 0;
            $case_study_image_url = $case_study_id ? get_the_post_thumbnail_url($case_study_id, 'full') : '';
          ?>

            <div class="scrolling-content-item" <?php if ($case_study_image_url): ?>style="background-image: url('<?php echo esc_url($case_study_image_url); ?>');" <?php endif; ?>>
              <div class="scrolling-content-card">
                <?php if ($case_study_id): ?>
                  <h3 class="scrolling-content-heading"><?php echo esc_html(get_the_title($case_study_id)); ?></h3>
                <?php endif; ?>
                <?php if ($case_study_id): ?>
                  <div class="scrolling-content-excerpt">
                    <?php echo esc_html(get_the_excerpt($case_study_id)); ?>
                  </div>

                  <?php if ($content['scrolling_content_text']): ?>
                    <div class="scrolling-content-text">
                      <?php echo apply_filters('the_content', $content['scrolling_content_text']); ?>
                    </div>
                  <?php endif; ?>

                  <a href="<?php echo esc_url(get_permalink($case_study_id)); ?>" class="btn cta-link">
                    <?php esc_html_e('Read More', 'essenta-theme'); ?>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php if ($scrolling_contents && count($scrolling_contents) > 1): ?>
        <div class="scrolling-contents-nav">
          <button type="button" class="scrolling-contents-prev" aria-label="<?php esc_attr_e('Previous', 'essenta-theme'); ?>"></button>
          <button type="button" class="scrolling-contents-next" aria-label="<?php esc_attr_e('Next', 'essenta-theme'); ?>"></button>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<script>
  jQuery(document).ready(function($) {
    $('.full-height-scrolling-content .scrolling-contents').slick({
      vertical: true,
      verticalSwiping: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      dots: false,
      infinite: true,
      centerMode: true,
      adaptiveHeight: false,
      prevArrow: $('.full-height-scrolling-content .scrolling-contents-prev'),
      nextArrow: $('.full-height-scrolling-content .scrolling-contents-next'),
      responsive: [{
        breakpoint: 980,
        settings: {
          vertical: false,
          verticalSwiping: false
        }
      }]
    });
  });
</script>