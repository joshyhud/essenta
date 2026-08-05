<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$media_text_subtitle = get_sub_field('media_text_subtitle');
$media_text_header = get_sub_field('media_text_header');

$text = get_sub_field('text_section');
$image = get_sub_field('image_half');
$smaller_image = get_sub_field('image_overlap');
$left_right = get_sub_field('media_left_or_right');
?>

<section class="media-text-block">

  <?php if (!empty($media_text_subtitle)) : ?>
    <div class="full-cta-subheader container">
      <div class="sub-header-wrapper dark">
        <div class="sub-header"><?php echo esc_html($media_text_subtitle); ?></div>
        <div class="header-divider"></div>
        <i class="header-icon"></i>
      </div>
    </div>
  <?php endif; ?>

  <div class="container">
    <div class="media-text-content grid <?php echo esc_attr($left_right); ?>">
      <div class="media-text-images">
        <?php if (!empty($image)): ?>
          <div class="media-text-images--main">
            <img loading="lazy" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
          </div>
        <?php endif; ?>
        <?php if (!empty($smaller_image)): ?>
          <div class="media-text-images--overlap">
            <img loading="lazy" src="<?php echo esc_url($smaller_image['url']); ?>" alt="<?php echo esc_attr($smaller_image['alt']); ?>" />
          </div>
        <?php endif; ?>
      </div>
      <div class="media-text-text">
        <?php if (!empty($media_text_header)): ?>
          <h2><?php echo esc_html($media_text_header); ?></h2>
        <?php endif; ?>
        <div class="media-text-content">
          <?php echo wp_kses_post($text); ?>
        </div>
        <?php if (get_sub_field('media_text_cta')):
          $cta = get_sub_field('media_text_cta'); ?>
          <div class="media-text-cta">
            <span class="cta-arrow">
              <svg width="319" height="8" viewBox="0 0 319 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M318.354 4.03568C318.549 3.84042 318.549 3.52384 318.354 3.32858L315.172 0.146595C314.976 -0.0486672 314.66 -0.0486672 314.464 0.146595C314.269 0.341857 314.269 0.65844 314.464 0.853702L317.293 3.68213L314.464 6.51056C314.269 6.70582 314.269 7.0224 314.464 7.21766C314.66 7.41293 314.976 7.41293 315.172 7.21766L318.354 4.03568ZM0 3.68213V4.18213H318V3.68213V3.18213H0V3.68213Z" fill="#1D1C1A" />
              </svg>

            </span>
            <a href="<?php echo esc_url($cta['url']); ?>" class="btn ticket purple" target="<?php echo esc_attr($cta['target'] ?: '_self'); ?>">
              <span class="ticket__label"><?php echo esc_html($cta['title']); ?></span>
              <span class="ticket__icon" aria-hidden="true">
                <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <mask id="path-1-inside-1_15032_2296" fill="white">
                    <path d="M20.7077 7.9798C19.6799 9.3507 19.7897 11.3043 21.0364 12.5511C22.2832 13.7979 24.2362 13.9069 25.6071 12.8791L28.2843 15.5563L15.5564 28.2843L12.8792 25.6071C13.9069 24.2362 13.7979 22.2832 12.5512 21.0364C11.3043 19.7896 9.35073 19.6799 7.97982 20.7077L5.65686 18.3848L18.3848 5.65684L20.7077 7.9798Z" />
                  </mask>
                  <path d="M20.7077 7.9798L21.5078 8.57967L22.028 7.88586L21.4148 7.27269L20.7077 7.9798ZM21.0364 12.5511L20.3293 13.2582L20.3293 13.2582L21.0364 12.5511ZM25.6071 12.8791L26.3142 12.172L25.701 11.5589L25.0072 12.079L25.6071 12.8791ZM28.2843 15.5563L28.9914 16.2634L29.6985 15.5563L28.9914 14.8492L28.2843 15.5563ZM15.5564 28.2843L14.8492 28.9914L15.5564 29.6985L16.2635 28.9914L15.5564 28.2843ZM12.8792 25.6071L12.079 25.0072L11.5589 25.701L12.172 26.3142L12.8792 25.6071ZM12.5512 21.0364L13.2583 20.3293L13.2583 20.3293L12.5512 21.0364ZM7.97982 20.7077L7.27271 21.4148L7.88588 22.028L8.57968 21.5078L7.97982 20.7077ZM5.65686 18.3848L4.94975 17.6777L4.24265 18.3848L4.94975 19.0919L5.65686 18.3848ZM18.3848 5.65684L19.0919 4.94974L18.3848 4.24263L17.6777 4.94974L18.3848 5.65684ZM20.7077 7.9798L19.9076 7.37993C18.5855 9.14338 18.7258 11.6547 20.3293 13.2582L21.0364 12.5511L21.7436 11.844C20.8535 10.954 20.7743 9.55801 21.5078 8.57967L20.7077 7.9798ZM21.0364 12.5511L20.3293 13.2582C21.9331 14.862 24.4438 15.001 26.2069 13.6793L25.6071 12.8791L25.0072 12.079C24.0286 12.8127 22.6333 12.7338 21.7435 11.844L21.0364 12.5511ZM25.6071 12.8791L24.9 13.5862L27.5772 16.2634L28.2843 15.5563L28.9914 14.8492L26.3142 12.172L25.6071 12.8791ZM28.2843 15.5563L27.5772 14.8492L14.8492 27.5772L15.5564 28.2843L16.2635 28.9914L28.9914 16.2634L28.2843 15.5563ZM15.5564 28.2843L16.2635 27.5772L13.5863 24.9L12.8792 25.6071L12.172 26.3142L14.8492 28.9914L15.5564 28.2843ZM12.8792 25.6071L13.6793 26.2069C15.0011 24.4438 14.862 21.9331 13.2583 20.3293L12.5512 21.0364L11.844 21.7435C12.7338 22.6333 12.8127 24.0286 12.079 25.0072L12.8792 25.6071ZM12.5512 21.0364L13.2583 20.3293C11.6547 18.7257 9.1434 18.5855 7.37995 19.9076L7.97982 20.7077L8.57968 21.5078C9.55806 20.7743 10.954 20.8535 11.844 21.7435L12.5512 21.0364ZM7.97982 20.7077L8.68692 20.0006L6.36397 17.6777L5.65686 18.3848L4.94975 19.0919L7.27271 21.4148L7.97982 20.7077ZM5.65686 18.3848L6.36397 19.0919L19.0919 6.36395L18.3848 5.65684L17.6777 4.94974L4.94975 17.6777L5.65686 18.3848ZM18.3848 5.65684L17.6777 6.36395L20.0006 8.68691L20.7077 7.9798L21.4148 7.27269L19.0919 4.94974L18.3848 5.65684Z" fill="#EFE1D1" mask="url(#path-1-inside-1_15032_2296)" />
                  <line x1="12.1976" y1="11.844" x2="22.0971" y2="21.7435" stroke="#EFE1D1" stroke-width="0.5" stroke-dasharray="1 1" />
                </svg>
              </span>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>