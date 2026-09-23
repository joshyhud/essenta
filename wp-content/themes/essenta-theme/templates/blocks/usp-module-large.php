<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$usp_module_subheading = get_sub_field('usp_module_subheading');
$usp_module_heading = get_sub_field('usp_module_heading');
$usp_module_text = get_sub_field('usp_module_heading_content');
$usp_module_items = get_sub_field('usp_module_items');

?>

<section class="usp-module-large">
    <div class="container">
        <?php if ($usp_module_subheading || $usp_module_heading || $usp_module_text): ?>
            <div class="usp-module-large__header">
                <div class="usp-module-large__header-inner">
                    <?php if ($usp_module_subheading): ?>
                        <p class="usp-module-large__subheading eyebrow"><?php echo esc_html($usp_module_subheading); ?></p>
                    <?php endif; ?>

                    <?php if ($usp_module_heading): ?>
                        <h2 class="usp-module-large__heading"><?php echo esc_html($usp_module_heading); ?></h2>
                    <?php endif; ?>
                </div> <!-- .usp-module-large__header-inner -->

                <?php if ($usp_module_text): ?>
                    <div class="usp-module-large__text"><?php echo wp_kses_post($usp_module_text); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($usp_module_items)): ?>
            <div class="usp-module-large__showcase">
                <div class="usp-module-large__feature">
                    <?php foreach ($usp_module_items as $item_index => $item): ?>
                        <?php
                        $heading = $item['usp_heading'] ?? $item['usp_module_item_heading'] ?? '';
                        $content = $item['usp_content'] ?? $item['usp_module_item_text'] ?? '';
                        $image = $item['usp_image'] ?? $item['usp_module_item_image'] ?? null;
                        $image_url = !empty($image) ? wp_get_attachment_image_url($image['ID'] ?? $image, 'large') : '';
                        ?>

                        <article class="usp-module-large__feature-item">
                            <?php if (!empty($image_url)): ?>
                                <div
                                    class="usp-module-large__feature-image"
                                    style="background-image: url('<?php echo esc_url($image_url); ?>');"
                                    role="img"
                                    aria-label="<?php echo esc_attr($heading); ?>"></div>
                            <?php endif; ?>

                            <div class="usp-module-large__feature-copy">
                                <?php if ($heading): ?>
                                    <h3 class="usp-module-large__feature-heading"><?php echo esc_html($heading); ?></h3>
                                <?php endif; ?>

                                <?php if ($content): ?>
                                    <div class="usp-module-large__feature-text"><?php echo wp_kses_post($content); ?></div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="usp-module-large__nav" aria-label="USP navigation">
                    <?php foreach ($usp_module_items as $item_index => $item): ?>
                        <?php
                        $nav_heading = $item['usp_heading'] ?? $item['usp_module_item_heading'] ?? '';
                        $nav_image = $item['usp_image'] ?? $item['usp_module_item_image'] ?? null;
                        $nav_image_url = !empty($nav_image) ? wp_get_attachment_image_url($nav_image['ID'] ?? $nav_image, 'medium') : '';
                        ?>

                        <div class="usp-module-large__nav-item" data-slide-index="<?php echo esc_attr($item_index); ?>">

                            <span class="usp-module-large__nav-copy">
                                <?php if ($nav_heading): ?>
                                    <span class="usp-module-large__nav-heading"><?php echo esc_html($nav_heading); ?></span>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    jQuery(document).ready(function($) {
        $('.usp-module-large__feature').each(function() {
            var $feature = $(this);
            var $nav = $feature.siblings('.usp-module-large__nav');

            if (!$nav.length) {
                return;
            }

            if (!$.fn.slick || $feature.hasClass('slick-initialized') || $nav.hasClass('slick-initialized')) {
                return;
            }

            var $allNavItems = $nav.children().detach();
            var navSettings = {
                slidesToShow: 3,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                vertical: true,
                verticalSwiping: true,
                infinite: false,
                responsive: [{
                    breakpoint: 980,
                    settings: {
                        vertical: false,
                        slidesToShow: 3,
                        centerMode: false
                    }
                }]
            };

            function renderAvailableNav(activeIndex) {
                if ($nav.hasClass('slick-initialized')) {
                    $nav.slick('unslick');
                }

                $nav.empty();
                $allNavItems.each(function() {
                    if (Number($(this).data('slide-index')) !== activeIndex) {
                        $nav.append(this);
                    }
                });
                $nav.slick(navSettings);
            }

            $feature.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                fade: true,
                speed: 400,
                infinite: false,
            });

            renderAvailableNav(0);

            $nav.on('click', '.usp-module-large__nav-item', function() {
                $feature.slick('slickGoTo', Number($(this).data('slide-index')));
            });

            $feature.on('afterChange', function(event, slick, currentSlide) {
                renderAvailableNav(currentSlide);
            });
        });
    });
</script>