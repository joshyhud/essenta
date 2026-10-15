<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$intro_subheading = get_sub_field('intro_subheading');
$intro_heading = get_sub_field('intro_heading');
$intro_text = get_sub_field('intro_text');
$intro_primary_cta = get_sub_field('intro_primary_cta');
$intro_secondary_cta = get_sub_field('intro_secondary_cta');

$statistics = get_sub_field('statistics');

$testimonial_select = get_sub_field('testimonial_select');

$selected_testimonials = get_sub_field('select_your_testimonials');

?>

<section class="introduction-block">
    <div class="container">

        <div class="introduction-block__content">
            <?php if ($intro_subheading) : ?>
                <p class="introduction-block__subheading eyebrow"><?php echo esc_html($intro_subheading); ?></p>
            <?php endif; ?>

            <?php if ($intro_heading) : ?>
                <h2 class="introduction-block__heading"><?php echo esc_html($intro_heading); ?></h2>
            <?php endif; ?>

            <?php if ($intro_text) : ?>
                <div class="introduction-block__text expand">
                    <?php echo wp_kses_post($intro_text); ?>
                </div>
            <?php endif; ?>

            <?php if ($intro_primary_cta) : ?>
                <a href="<?php echo esc_url($intro_primary_cta['url']); ?>" class="btn primary"><?php echo esc_html($intro_primary_cta['title']); ?></a>
            <?php endif; ?>

            <?php if ($intro_secondary_cta) : ?>
                <a href="<?php echo esc_url($intro_secondary_cta['url']); ?>" class="btn secondary"><?php echo esc_html($intro_secondary_cta['title']); ?></a>
            <?php endif; ?>
        </div>

        <div class="introduction-block__statistics">

            <?php if ($statistics) :
                $statistics_count = count($statistics);
                $statistics_slider_class = 'statistics-slider';
                if ($statistics_count === 1) {
                    $statistics_slider_class .= ' statistics-slider--single';
                } elseif ($statistics_count === 2) {
                    $statistics_slider_class .= ' statistics-slider--double';
                } else {
                    $statistics_slider_class .= ' statistics-slider--slick';
                }
            ?>
                <div class="<?php echo esc_attr($statistics_slider_class); ?>">
                    <?php foreach ($statistics as $statistic) : ?>
                        <div class="statistic">
                            <p class="statistic-label eyebrow"><?php echo esc_html($statistic['statistic_subheader']); ?></p>
                            <span class="statistic-value"><?php echo esc_html($statistic['statistic_entry']); ?></span>
                            <span class="statistic-label"><?php echo esc_html($statistic['statistic_subcontent']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php
            $testimonials_to_display = array();

            if ($testimonial_select === 'auto') {

                $auto_testimonials = new WP_Query(array(
                    'post_type'      => 'testimonial',
                    'posts_per_page' => 5,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ));

                while ($auto_testimonials->have_posts()) {
                    $auto_testimonials->the_post();

                    $testimonials_to_display[] = array(
                        'testimonial_text'   => get_the_content(),
                        'testimonial_author' => get_the_title(),
                    );
                }

                wp_reset_postdata();
            } elseif ($testimonial_select === 'man' && $selected_testimonials) {
                foreach ($selected_testimonials as $selected_testimonial) {
                    if (is_object($selected_testimonial)) {
                        $testimonials_to_display[] = array(
                            'testimonial_text'   => get_post_field('post_content', $selected_testimonial->ID),
                            'testimonial_author' => get_the_title($selected_testimonial->ID),
                        );
                    } elseif (is_array($selected_testimonial)) {
                        $testimonials_to_display[] = $selected_testimonial;
                    }
                }
            }
            ?>
            <div class="testimonials-slider">
                <?php foreach ($testimonials_to_display as $testimonial) : ?>
                    <div class="testimonial">
                        <p class="testimonial-text"><?php echo esc_html($testimonial['testimonial_text']); ?></p>
                        <span class="testimonial-author eyebrow"><?php echo esc_html($testimonial['testimonial_author']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($testimonials_to_display) > 1) : ?>
                <div class="slider-nav testimonials">
                    <div class="slider-dots"></div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var $statisticsSliders = $('.statistics-slider--slick').not('.slick-initialized');

        $statisticsSliders.slick({
            slidesToShow: 2.75,
            slidesToScroll: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 0,
            speed: 10000,
            cssEase: 'linear',
            pauseOnHover: false,
            pauseOnFocus: false,
            draggable: true,
            swipe: true,
            swipeToSlide: true,
            touchMove: true,
            waitForAnimate: false,
            dots: false,
            arrows: false,
            adaptiveHeight: false,
            responsive: [{
                    breakpoint: 980,
                    settings: {
                        slidesToShow: 2.2,
                    }
                },
                {
                    breakpoint: 420,
                    settings: {
                        slidesToShow: 2.2,
                    }

                }
            ]
        });

        $statisticsSliders.each(function() {
            var $slider = $(this);
            var slick = $slider.slick('getSlick');
            var isPaused = false;
            var animation;
            var completeAnimation;
            var touch;
            var touchInteraction = false;
            var isSettling = false;

            $slider.attr('tabindex', '0');

            // Keep Slick's slide lifecycle, but pause the animation itself, not just autoplay.
            slick.animateSlide = function(targetLeft, callback) {
                var track = slick.$slideTrack[0];
                var startTransform = window.getComputedStyle(track).transform;
                var startLeft = new DOMMatrixReadOnly(startTransform).m41;

                if (animation) {
                    animation.cancel();
                }

                slick.setCSS(targetLeft);
                animation = track.animate([{
                        transform: startTransform
                    },
                    {
                        transform: track.style.transform
                    }
                ], {
                    duration: isSettling ? 250 : Math.abs(targetLeft - startLeft) / slick.slideWidth * slick.options.speed,
                    easing: 'linear'
                });

                completeAnimation = function() {
                    animation.cancel();
                    animation = null;
                    completeAnimation = null;
                    isSettling = false;
                    callback();
                };
                animation.onfinish = completeAnimation;

                if (isPaused && !isSettling) {
                    animation.pause();
                }
            };

            function pauseSlider() {
                if (isPaused) {
                    return;
                }

                isPaused = true;
                $slider.slick('slickPause');

                if (animation) {
                    animation.pause();
                }
            }

            function resumeSlider(afterTouch) {
                if (touch || (afterTouch !== true &&
                    ((window.matchMedia('(hover: hover)').matches && $slider.is(':hover')) || $slider.is(':focus'))) ||
                    $slider.find(':focus').length) {
                    return;
                }

                if (!isPaused) {
                    return;
                }

                isPaused = false;
                if (animation) {
                    animation.play();
                }
                $slider.slick('slickPlay');
                if (animation) {
                    slick.autoPlayClear();
                }
            }

            function handleTouchStart(event) {
                event.stopPropagation();
                if (event.touches.length !== 1) {
                    return;
                }

                touchInteraction = true;
                pauseSlider();
                touch = {
                    x: event.touches[0].clientX,
                    y: event.touches[0].clientY,
                    left: new DOMMatrixReadOnly(window.getComputedStyle(slick.$slideTrack[0]).transform).m41,
                    dragging: false,
                    scrolling: false
                };
            }

            function handleTouchMove(event) {
                event.stopPropagation();
                if (!touch || event.touches.length !== 1 || touch.scrolling) {
                    return;
                }

                var deltaX = event.touches[0].clientX - touch.x;
                var deltaY = event.touches[0].clientY - touch.y;

                if (!touch.dragging) {
                    if (Math.max(Math.abs(deltaX), Math.abs(deltaY)) < 5) {
                        return;
                    }
                    if (Math.abs(deltaY) > Math.abs(deltaX)) {
                        touch.scrolling = true;
                        return;
                    }
                    touch.dragging = true;
                    if (animation) {
                        animation.cancel();
                        animation = null;
                        completeAnimation = null;
                    }
                    slick.animating = false;
                }

                event.preventDefault();
                slick.setCSS(Math.max(slick.getLeft(slick.slideCount),
                    Math.min(slick.getLeft(-1), touch.left + deltaX)));
            }

            function handleTouchEnd(event) {
                event.stopPropagation();
                if (!touch || event.touches.length) {
                    return;
                }

                var wasDragging = touch.dragging;
                touch = null;
                if (wasDragging) {
                    var left = new DOMMatrixReadOnly(window.getComputedStyle(slick.$slideTrack[0]).transform).m41;
                    var index = slick.currentSlide + Math.round((slick.getLeft(slick.currentSlide) - left) / slick.slideWidth);
                    slick.swipeLeft = left;
                    isSettling = true;
                    slick.slideHandler(index);
                }
                resumeSlider(true);
            }

            // Capture touch gestures before Slick's in-flight animation guard blocks dragging.
            $slider[0].addEventListener('touchstart', handleTouchStart, {
                capture: true,
                passive: true
            });
            $slider[0].addEventListener('touchmove', handleTouchMove, {
                capture: true,
                passive: false
            });
            $slider[0].addEventListener('touchend', handleTouchEnd, true);
            $slider[0].addEventListener('touchcancel', handleTouchEnd, true);

            $slider.on('setPosition', function() {
                if (completeAnimation) {
                    completeAnimation();
                }
            });
            $slider.on('destroy', function() {
                $slider[0].removeEventListener('touchstart', handleTouchStart, true);
                $slider[0].removeEventListener('touchmove', handleTouchMove, true);
                $slider[0].removeEventListener('touchend', handleTouchEnd, true);
                $slider[0].removeEventListener('touchcancel', handleTouchEnd, true);
                if (animation) {
                    animation.cancel();
                    animation = null;
                    completeAnimation = null;
                }
            });
            $slider.on('pointerenter', function(event) {
                if (event.originalEvent.pointerType === 'mouse') {
                    touchInteraction = false;
                    pauseSlider();
                }
            });
            $slider.on('focusin', function(event) {
                var capabilities = event.originalEvent && event.originalEvent.sourceCapabilities;
                if (event.target === $slider[0] && ((capabilities && capabilities.firesTouchEvents) ||
                    (touchInteraction && !event.target.matches(':focus-visible')))) {
                    return;
                }
                pauseSlider();
            });
            $slider.on('mouseleave', resumeSlider);
            $slider.on('focusout', function(event) {
                if (event.relatedTarget && $slider[0].contains(event.relatedTarget)) {
                    return;
                }

                window.setTimeout(resumeSlider, 0);
            });
        });

        $('.testimonials-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: false,
            dots: true,
            arrows: false,
            appendDots: $('.slider-nav.testimonials .slider-dots'),
            adaptiveHeight: false
        });
    });
</script>