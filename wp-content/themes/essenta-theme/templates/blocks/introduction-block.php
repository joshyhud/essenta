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
                <?php echo wp_kses_post($intro_text); ?>
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
        $('.statistics-slider--slick').slick({
            slidesToShow: 2.75,
            slidesToScroll: 1,
            infinite: false,
            dots: false,
            arrows: false,
            adaptiveHeight: false,
            responsive: [{
                    breakpoint: 980,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    }

                }
            ]
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