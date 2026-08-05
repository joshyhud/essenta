<?php

$full_cta_subtitle = get_sub_field('full_cta_subtitle');
$full_cta_header  = get_sub_field('full_cta_header');
$full_cta_content = get_sub_field('full_cta_content');
$full_cta         = get_sub_field('full_cta_link');

// Collect all repeater images into an array
$images = [];
if (have_rows('full_cta_images')) {
  while (have_rows('full_cta_images')) {
    the_row();
    $image = get_sub_field('full_cta_image');
    if ($image) {
      $images[] = $image;
    }
  }
}

$image_count = count($images);

// Split images: left gets ceil, right gets the rest
$left_count  = (int) ceil($image_count / 2);
$left_images  = array_slice($images, 0, $left_count);
$right_images = array_slice($images, $left_count);

// Layout class based on image count
$layout_class = '';
if ($image_count === 2) {
  $layout_class = 'full-cta--two';
} elseif ($image_count === 3) {
  $layout_class = 'full-cta--three';
} elseif ($image_count === 4) {
  $layout_class = 'full-cta--four';
}

?>
<section class="full-cta <?php echo esc_attr($layout_class); ?>">

  <div class="full-cta-subheader container">
    <div class="sub-header-wrapper dark">
      <div class="sub-header"><?php echo esc_html($full_cta_subtitle); ?></div>
      <div class="header-divider"></div>
      <i class="header-icon"></i>
    </div>
  </div>

  <div class="full-cta-inner">

    <div class="images full-cta__left">
      <?php foreach ($left_images as $image) : ?>
        <img loading="lazy" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
      <?php endforeach; ?>
    </div>

    <div class="full-cta__content">
      <h2><?php echo esc_html($full_cta_header); ?></h2>
      <div class="full-cta__text">
        <?php echo wp_kses_post($full_cta_content); ?>
      </div>
      <svg class="svg-arrow" width="8" height="81" viewBox="0 0 8 81" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M3.32845 80.3536C3.52372 80.5488 3.8403 80.5488 4.03556 80.3536L7.21754 77.1716C7.4128 76.9763 7.4128 76.6597 7.21754 76.4645C7.02228 76.2692 6.7057 76.2692 6.51043 76.4645L3.68201 79.2929L0.85358 76.4645C0.658318 76.2692 0.341735 76.2692 0.146473 76.4645C-0.0487891 76.6597 -0.0487891 76.9763 0.146473 77.1716L3.32845 80.3536ZM3.68201 80L4.18201 80L4.18201 2.18557e-08L3.68201 0L3.18201 -2.18557e-08L3.18201 80L3.68201 80Z" fill="#1D1C1A" />
      </svg>
      <?php if ($full_cta) : ?>
        <a href="<?php echo esc_url($full_cta['url']); ?>" class="btn ticket purple" target="<?php echo esc_attr($full_cta['target'] ?: '_self'); ?>">
          <span class="ticket__label"><?php echo esc_html($full_cta['title']); ?></span>
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
      <?php endif; ?>
    </div>

    <?php if (!empty($right_images)) : ?>
      <div class=" images full-cta__right">
        <?php foreach ($right_images as $image) : ?>
          <img loading="lazy" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</section>