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
        <?php $video_modals_html = ''; ?>
        <?php if ($scrolling_contents): ?>
          <?php foreach ($scrolling_contents as $index => $content):
            $case_study = $content['case_study_link'];
            $case_study_id = $case_study ? $case_study->ID : 0;
            $case_study_image_url = $case_study_id ? get_the_post_thumbnail_url($case_study_id, 'full') : '';

            $image_or_video = isset($content['image_or_video']) ? $content['image_or_video'] : null;
            $media_type = $image_or_video && !empty($image_or_video['type']) ? $image_or_video['type'] : '';


            $background_image_url = '';
            $video_url = '';

            if ($media_type === 'video' && !empty($image_or_video)) {
              $video_url = $image_or_video['url'];
            } elseif ($media_type === 'image' && !empty($image_or_video)) {
              $background_image_url = is_array($image_or_video['image']) ? $image_or_video['image']['url'] : $image_or_video['image'];
            } else {
              $background_image_url = $case_study_image_url;
            }

            $item_id = 'scrolling-content-item-' . $index;
          ?>

            <div id="<?php echo esc_attr($item_id); ?>" class="scrolling-content-item <?php echo $video_url ? 'has-video' : ''; ?>">
              <div class="scrolling-content-media" <?php if ($background_image_url && !$video_url): ?> style="background-image: url('<?php echo esc_url($background_image_url); ?>');" <?php endif; ?>>
                <?php if ($video_url): ?>
                  <video <?php if ($video_url): ?>data-video-modal-trigger="<?php echo esc_attr($item_id); ?>" <?php endif; ?>class="scrolling-content-video" src="<?php echo esc_url($video_url); ?>" muted playsinline loop preload="metadata" data-video-src="<?php echo esc_url($video_url); ?>"></video>
                <?php endif; ?>
              </div>
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

            <?php if ($video_url):
              ob_start(); ?>
              <div class="scrolling-content-video-modal" id="<?php echo esc_attr($item_id); ?>-modal" data-video-modal>
                <div class="scrolling-content-video-modal-overlay" data-video-modal-close></div>
                <div class="scrolling-content-video-modal-inner">
                  <button type="button" class="scrolling-content-video-modal-close" aria-label="<?php esc_attr_e('Close', 'essenta-theme'); ?>" data-video-modal-close></button>
                  <video class="scrolling-content-video-modal-video" src="<?php echo esc_url($video_url); ?>" controls playsinline></video>
                </div>
              </div>
            <?php $video_modals_html .= ob_get_clean();
            endif; ?>
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
  <?php echo $video_modals_html; ?>
</section>

<script>
  jQuery(document).ready(function($) {
    var $slider = $('.full-height-scrolling-content .scrolling-contents');
    var sectionEl = document.querySelector('.full-height-scrolling-content');
    var sectionInView = true;

    function pauseAllVideos() {
      $slider.find('.scrolling-content-video').each(function() {
        this.pause();
      });
    }

    function playActiveVideo() {
      pauseAllVideos();
      if (!sectionInView) return;
      $slider.find('.slick-current .scrolling-content-video').each(function() {
        this.play().catch(function() {});
      });
    }

    $slider.on('init afterChange', playActiveVideo);

    var mobileQuery = window.matchMedia('(max-width: 980px)');

    function initSlider(isMobile) {
      $slider.slick({
        vertical: !isMobile,
        verticalSwiping: !isMobile,
        slidesToShow: isMobile ? 1.1 : 1,
        slidesToScroll: 1,
        arrows: !isMobile,
        dots: false,
        infinite: !isMobile,
        centerMode: !isMobile,
        adaptiveHeight: false,
        prevArrow: $('.full-height-scrolling-content .scrolling-contents-prev'),
        nextArrow: $('.full-height-scrolling-content .scrolling-contents-next')
      });
    }

    function syncSliderOrientation(event) {
      if ($slider.hasClass('slick-initialized')) {
        $slider.slick('unslick');
      }

      initSlider(event.matches);
    }

    syncSliderOrientation(mobileQuery);
    mobileQuery.addEventListener('change', syncSliderOrientation);

    // Only autoplay the current slide's video while the section itself is on screen.
    if (sectionEl && 'IntersectionObserver' in window) {
      var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          sectionInView = entry.isIntersecting;
          if (entry.isIntersecting) {
            playActiveVideo();
          } else {
            pauseAllVideos();
          }
        });
      }, {
        threshold: 0.4
      });
      observer.observe(sectionEl);
    }

    var $openModal = null;

    function closeModal($modal) {
      $modal.removeClass('is-open').find('.scrolling-content-video-modal-video').each(function() {
        this.pause();
        this.currentTime = 0;
      });
      $openModal = null;
      playActiveVideo();
    }

    $(document).on('click', '[data-video-modal-trigger]', function() {
      var itemId = $(this).data('video-modal-trigger');
      var $modal = $('#' + itemId + '-modal');
      if (!$modal.length) return;

      pauseAllVideos();
      $openModal = $modal;
      $modal.addClass('is-open').find('.scrolling-content-video-modal-video').each(function() {
        this.currentTime = 0;
        this.play().catch(function() {});
      });
    });

    $(document).on('click', '[data-video-modal-close]', function() {
      closeModal($(this).closest('[data-video-modal]'));
    });

    $(document).on('keydown', function(event) {
      if (event.key === 'Escape' && $openModal) {
        closeModal($openModal);
      }
    });
  });
</script>