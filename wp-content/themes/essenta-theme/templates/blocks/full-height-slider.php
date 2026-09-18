<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$full_height_slides = get_sub_field('full_height_slides');

?>

<section class="full-height-slider-block">
    <?php if ($full_height_slides) : ?>
        <div class="full-height-slider">
            <?php foreach ($full_height_slides as $slide) : ?>
                <div class="full-height-slide" style="background-image: url('<?php echo esc_url($slide['slide_background_image']['url']); ?>');">
                    <?php if (!empty($slide['slide_content'])) : ?>
                        <div class="full-height-slide-content">
                            <p class="eyebrow"><?php echo $slide['slide_subheading']; ?></p>
                            <h2><?php echo $slide['slide_heading']; ?></h2>
                            <p class="slide-content"><?php echo wp_kses_post($slide['slide_content']); ?></p>
                            <?php if ($slide['slide_cta']) : ?>
                                <a href="<?php echo esc_url($slide['slide_cta']['url']); ?>" class="btn primary--light"><?php echo esc_html($slide['slide_cta']['title']); ?></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="slider-nav full-height-slider-nav">
            <?php foreach ($full_height_slides as $index => $slide) : ?>
                <?php if (!empty($slide['slide_type'])) : ?>
                    <button type="button" class="slide-type-btn<?php echo $index === 0 ? ' active' : ''; ?>" data-slide-index="<?php echo esc_attr($index); ?>">
                        <?php echo esc_html($slide['slide_type']); ?>
                    </button>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script>
    jQuery(document).ready(function($) {
        $('.full-height-slider-block').each(function() {
            var $block = $(this);
            var $slider = $block.find('.full-height-slider');
            var $navButtons = $block.find('.full-height-slider-nav .slide-type-btn');

            $slider.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                swipe: false,
                fade: true,
                arrows: false,
                dots: false,
                infinite: true,
                adaptiveHeight: false,
                speed: 800
            });

            $navButtons.on('click', function() {
                var slideIndex = $(this).data('slide-index');
                $slider.slick('slickGoTo', slideIndex);
            });

            $slider.on('afterChange', function(event, slick, currentSlide) {
                $navButtons.removeClass('active').eq(currentSlide).addClass('active');
            });
        });
    });
</script>