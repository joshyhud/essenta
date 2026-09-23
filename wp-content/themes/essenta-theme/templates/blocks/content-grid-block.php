<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Header section 
$block_subheading = get_sub_field('block_subheading');
$block_heading = get_sub_field('block_heading');
$block_content = get_sub_field('block_intro_content');

// Content grid 

$content_image = get_sub_field('block_image');
$content_grid_items = get_sub_field('content_grid_items');
$content_grid_item_count = is_array($content_grid_items) ? count($content_grid_items) : 0;

?>

<section class="content-grid-block">
    <div class="container">
        <div class="content-grid-header content-grid-header--<?php echo $content_grid_item_count >= 5 ? 'columns' : 'stacked'; ?>">
            <div class="content-grid-header-title">
                <?php if ($block_subheading) : ?>
                    <p class="eyebrow"><?php echo esc_html($block_subheading); ?></p>
                <?php endif; ?>
                <?php if ($block_heading) : ?>
                    <h2 class="content-grid-heading"><?php echo esc_html($block_heading); ?></h2>
                <?php endif; ?>
            </div>
            <?php if ($block_content) : ?>
                <p class="content-grid-intro"><?php echo esc_html($block_content); ?></p>
            <?php endif; ?>
        </div>

        <div class="content-grid-wrapper">
            <div class="content-grid-items <?php echo ($content_grid_items && count($content_grid_items) === 2) ? 'content-grid-items--stacked' : ''; ?>">
                <?php if ($content_grid_items) : ?>
                    <?php foreach ($content_grid_items as $item) : ?>
                        <div class="content-grid-item">
                            <?php if (! empty($item['grid_item_heading'])) : ?>
                                <h4 class="content-grid-item-title"><?php echo esc_html($item['grid_item_heading']); ?></h4>
                            <?php endif; ?>
                            <?php if (! empty($item['grid_item_content'])) : ?>
                                <p class="content-grid-item-content"><?php echo esc_html($item['grid_item_content']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="slider-nav content-grid-dots">
                <div class="slider-dots"></div>
            </div>
        </div>

        <div class="content-grid-image">
            <?php if ($content_image) : ?>
                <img src="<?php echo esc_url($content_image['url']); ?>" alt="<?php echo esc_attr($content_image['alt']); ?>">
            <?php endif; ?>
        </div>

    </div>
</section>

<script>
    jQuery(document).ready(function($) {
        var $items = $('.content-grid-block .content-grid-items');
        var $dots = $('.content-grid-block .content-grid-dots .slider-dots');
        var mobileQuery = window.matchMedia('(max-width: 980px)');

        function initSlider() {
            if (!$items.hasClass('slick-initialized')) {
                $items.slick({
                    slidesToShow: 1.1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: true,
                    infinite: false,
                    adaptiveHeight: false,
                    appendDots: $dots
                });
            }
        }

        function destroySlider() {
            if ($items.hasClass('slick-initialized')) {
                $items.slick('unslick');
            }
        }

        function handleSlider(e) {
            if (e.matches) {
                initSlider();
            } else {
                destroySlider();
            }
        }

        handleSlider(mobileQuery);
        mobileQuery.addEventListener('change', handleSlider);
    });
</script>