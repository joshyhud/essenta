<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$statistics_subheading = get_sub_field('statistics_module_subheading');
$statistics_heading = get_sub_field('statistics_module_heading');
$statistics_text = get_sub_field('statistics_module_content');
$statistics_cards = get_sub_field('statistics_cards');

?>

<section class="statistics-module">
    <div class="container">
        <div class="statistics-module__content">
            <?php if ($statistics_subheading): ?>
                <p class="eyebrow"><?php echo esc_html($statistics_subheading); ?></p>
            <?php endif; ?>
            <?php if ($statistics_heading): ?>
                <h2 class="heading"><?php echo esc_html($statistics_heading); ?></h2>
            <?php endif; ?>

            <?php if ($statistics_text): ?>
                <div class="content-text">
                    <?php echo wp_kses_post($statistics_text); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($statistics_cards): ?>
            <div class="statistics-module__grid">
                <?php foreach ($statistics_cards as $card): ?>
                    <div class="statistics-item">
                        <?php if (!empty($card['statistic_value'])): ?>
                            <p class="statistic"><?php echo esc_html($card['statistic_value']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($card['statistic_content'])): ?>
                            <p class="label"><?php echo esc_html($card['statistic_content']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Your JavaScript code here

        $(window).resize(function() {

            if ($(window).width() <= 980) {
                $('.statistics-module__grid').slick({
                    slidesToShow: 2.2,
                    slidesToScroll: 1,
                    infinite: false,
                    dots: false,
                    arrows: false,
                    adaptiveHeight: true,
                    responsive: [{
                        breakpoint: 420,
                        settings: {
                            slidesToShow: 1.2,
                        }

                    }]
                });
            } else {
                if ($('.statistics-module__grid').hasClass('slick-initialized')) {
                    $('.statistics-module__grid').slick('unslick');
                }
            }
        });
        $(window).trigger('resize');
    });
</script>