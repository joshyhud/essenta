<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$accordion_carousel_subheading = get_sub_field('accordion_carousel_subheading');
$accordion_carousel_heading = get_sub_field('accordion_carousel_heading');
$carousel_cta = get_sub_field('carousel_cta');
$carousel_items = get_sub_field('carousel_items');


$carousel_item_total = count($carousel_items);
$carousel_item_count = 0;


?>

<section class="carousel-accordion-block">
  <div class="container">
    <div class="section-header text-center">
      <p class="eyebrow"><?php echo esc_html($accordion_carousel_subheading); ?></p>
      <h2 class="heading"><?php echo esc_html($accordion_carousel_heading); ?></h2>
    </div>

    <div class="carousel-accordion-content desktop-only">
      <?php if ($carousel_items) : ?>
        <div class="carousel-accordion-wrapper grid">
          <div class="carousel-accordion-items">
            <?php foreach ($carousel_items as $index => $item) :
              $item_title = $item['item_heading'];
              $item_content = $item['item_content'];
              $item_image = $item['item_image'];
            ?>
              <div class="carousel-accordion-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                <div class="item-text">
                  <?php if ($item_title) : ?>
                    <div class="item-heading">
                      <div class="carousel-item-number">
                        <div class="current"><?php echo sprintf('%03d', $carousel_item_count + 1); ?> </div> / <div class="total"><?php echo sprintf('%03d', $carousel_item_total); ?></div>
                      </div>
                      <p class="item-title subheading">

                        <?php echo $item_title; ?>
                      </p>
                    </div>
                  <?php endif; ?>
                  <?php if ($item_content) : ?>
                    <div class="item-content">
                      <?php echo wp_kses_post($item_content); ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php $carousel_item_count++; ?>
            <?php endforeach; ?>

            <?php if ($carousel_cta) : ?>
              <div class="carousel-accordion-cta text-center">
                <a href="<?php echo esc_url($carousel_cta['url']); ?>" class="btn primary">
                  <?php echo esc_html($carousel_cta['title']); ?>
                </a>
              </div>
            <?php endif; ?>
          </div>

          <div class="carousel-image-container">
            <?php foreach ($carousel_items as $index => $item) :
              $item_image = $item['item_image'];
              if ($item_image) :
            ?>
                <div class="item-image <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                  <img loading="lazy" src="<?php echo esc_url($item_image['url']); ?>" alt="<?php echo esc_attr($item_image['alt']); ?>">
                </div>
            <?php endif;
            endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <div class="carousel-accordion-content mobile-only">
      <?php if ($carousel_items) : ?>
        <div class="carousel-mobile-wrapper">
          <div class="carousel-mobile-items">
            <?php foreach ($carousel_items as $index => $item) :
              $item_title = $item['item_heading'];
              $item_content = $item['item_content'];
              $item_image = $item['item_image'];
            ?>
              <div class="carousel-mobile-item">
                <div class="mobile-item-header">
                  <div class="carousel-item-number">
                    <div class="current"><?php echo sprintf('%03d', $index + 1); ?></div>
                    <div class="total"> / <?php echo sprintf('%03d', $carousel_item_total); ?></div>
                  </div>
                  <?php if ($item_title) : ?>
                    <p class="item-title subheading"><?php echo $item_title; ?></p>
                  <?php endif; ?>
                </div>

                <?php if ($item_image) : ?>
                  <div class="mobile-item-image">
                    <img loading="lazy" src="<?php echo esc_url($item_image['url']); ?>" alt="<?php echo esc_attr($item_image['alt']); ?>">
                  </div>
                <?php endif; ?>

                <?php if ($item_content) : ?>
                  <div class="item-content">
                    <?php echo wp_kses_post($item_content); ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>

          <?php if ($carousel_cta) : ?>
            <div class="carousel-accordion-cta text-center">
              <a href="<?php echo esc_url($carousel_cta['url']); ?>" class="btn primary">
                <?php echo esc_html($carousel_cta['title']); ?>
              </a>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.carousel-accordion-item');
    const images = document.querySelectorAll('.item-image');
    let currentIndex = 0;
    let autoRotateInterval;

    function setActiveItem(index) {
      // Remove active class from all items and images
      items.forEach(i => i.classList.remove('active'));
      images.forEach(img => img.classList.remove('active'));

      // Add active class to target item and corresponding image
      items[index].classList.add('active');
      const targetImage = document.querySelector(`.item-image[data-index="${index}"]`);
      if (targetImage) {
        targetImage.classList.add('active');
      }
    }

    function autoRotate() {
      const carouselItems = document.querySelector('.carousel-accordion-items');
      if (!carouselItems.matches(':hover')) {
        currentIndex = (currentIndex + 1) % items.length;
        setActiveItem(currentIndex);
      }
    }

    // Start auto rotation
    autoRotateInterval = setInterval(autoRotate, 8000);

    // Handle manual clicks
    items.forEach(item => {
      item.addEventListener('click', function() {
        const index = parseInt(this.dataset.index);
        currentIndex = index;
        setActiveItem(index);

        // Reset auto rotation timer
        clearInterval(autoRotateInterval);
        autoRotateInterval = setInterval(autoRotate, 8000);
      });
    });
  });
</script>