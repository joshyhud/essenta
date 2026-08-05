<?php

$logo_carousel_header = get_sub_field('logo_carousel_header');
$logo_carousel_images = get_sub_field('logo_carousel_images');


?>

<section class="logo-carousel-section">
  <div class="container">
    <div class="logo-carousel-inner">
      <?php if (!empty($logo_carousel_header)) : ?>
        <h2 class="logo-carousel__header"><?php echo esc_html($logo_carousel_header); ?></h2>
      <?php endif; ?>
      <div class="logo-carousel">
        <?php if (!empty($logo_carousel_images)) : ?>
          <?php foreach ($logo_carousel_images as $image) : ?>
            <div class="logo-carousel--item">
              <img loading="lazy" src="<?php echo esc_url($image['logo_carousel_image']['url']); ?>" alt="<?php echo esc_attr($image['logo_carousel_image']['alt']); ?>" />
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>