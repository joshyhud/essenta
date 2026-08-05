<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$carousel_cards_header = get_sub_field('carousel_cards_header');
$carousel_cards = get_sub_field('carousel_cards');

?>
<section class="carousel-cards-section">
  <div class="container">
    <?php if (!empty($carousel_cards_header)) : ?>
      <h2 class="carousel-cards-header"><?php echo esc_html($carousel_cards_header); ?></h2>
    <?php endif; ?>
    <div class="carousel-cards">
      <?php if (!empty($carousel_cards)) : ?>
        <?php foreach ($carousel_cards as $card) : ?>
          <div class="carousel-card">
            <div class="carousel-card-inner">
              <div class="carousel-card-image">
                <img loading="lazy" src="<?php echo esc_url($card['carousel_card_image']['url']); ?>" alt="<?php echo esc_attr($card['carousel_card_image']['alt']); ?>" />
              </div>

              <div class="carousel-card-content">
                <?php echo wp_kses_post($card['carousel_card_content']); ?>
                <?php if (!empty($card['carousel_card_cta'])) : ?>
                  <a class="btn primary" href="<?php echo esc_url($card['carousel_card_cta']['url']); ?>" target="<?php echo esc_attr($card['carousel_card_cta']['target']); ?>"><?php echo esc_html($card['carousel_card_cta']['title']); ?></a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="slick-nav slick-carousel">
      <button class="arrow slick-prev"></button>
      <button class="arrow slick-next"></button>
    </div>
  </div>
</section>