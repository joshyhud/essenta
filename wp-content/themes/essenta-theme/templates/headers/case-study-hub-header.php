<?php

if (!defined('ABSPATH')) {
    exit;
}

$sitcky_left_subheading = get_sub_field('sticky_left_subheading');
$sitcky_left_heading = get_sub_field('sticky_left_heading');
$sitcky_left_content = get_sub_field('sticky_left_content');
$sticky_left_cta = get_sub_field('sticky_left_cta');

/**
 * Get latest 3 case studies.
 */
$case_studies = new WP_Query([
    'post_type'      => 'case_study',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

?>

<section class="full-height-scrolling-content">
    <div class="container">

        <div class="sticky-left">
            <div class="sticky-left-content">

                <?php if ($sitcky_left_subheading): ?>
                    <p class="eyebrow">
                        <?php echo esc_html($sitcky_left_subheading); ?>
                    </p>
                <?php endif; ?>

                <?php if ($sitcky_left_heading): ?>
                    <h2 class="heading">
                        <?php echo esc_html($sitcky_left_heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($sitcky_left_content): ?>
                    <div class="content-text">
                        <?php echo wp_kses_post($sitcky_left_content); ?>
                    </div>
                <?php endif; ?>

                <?php if ($sticky_left_cta): ?>
                    <div class="sticky-left-cta">
                        <a
                            href="<?php echo esc_url($sticky_left_cta['url']); ?>"
                            class="btn primary">
                            <?php echo esc_html($sticky_left_cta['title']); ?>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="scrolling-contents-wrapper">

            <div class="scrolling-contents">

                <?php if ($case_studies->have_posts()): ?>

                    <?php while ($case_studies->have_posts()): $case_studies->the_post(); ?>

                        <?php
                        $case_study_id = get_the_ID();

                        /*
             * Featured image.
             */
                        $case_study_image_url = get_the_post_thumbnail_url(
                            $case_study_id,
                            'full'
                        );

                        /*
             * Get Case Study taxonomies.
             */
                        $client = get_the_terms(
                            $case_study_id,
                            'case_study_client'
                        );

                        $sector = get_the_terms(
                            $case_study_id,
                            'case_study_sector'
                        );

                        $service = get_the_terms(
                            $case_study_id,
                            'case_study_service'
                        );

                        /*
             * Get first terms.
             */
                        $client = ($client && !is_wp_error($client))
                            ? $client[0]
                            : null;

                        $sector = ($sector && !is_wp_error($sector))
                            ? $sector[0]
                            : null;

                        $service = ($service && !is_wp_error($service))
                            ? $service[0]
                            : null;

                        /*
             * Get client logo from the client taxonomy term.
             */
                        $client_logo = null;

                        if ($client) {
                            $client_logo = get_field(
                                'client_logo',
                                'term_' . $client->term_id
                            );
                        }
                        ?>

                        <div class="scrolling-content-item">

                            <!-- Media -->
                            <div
                                class="scrolling-content-media"
                                <?php if ($case_study_image_url): ?>
                                style="background-image: url('<?php echo esc_url($case_study_image_url); ?>');"
                                <?php endif; ?>>

                                <!-- Tags -->
                                <div class="casestudy-card__tags">

                                    <?php if ($service): ?>
                                        <span class="casestudy-card__tag">
                                            <?php echo esc_html($service->name); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if ($sector): ?>
                                        <span class="casestudy-card__tag casestudy-card__tag--teal">
                                            <?php echo esc_html($sector->name); ?>
                                        </span>
                                    <?php endif; ?>

                                </div>

                            </div>

                            <!-- Card -->
                            <div class="scrolling-content-card">

                                <?php if ($client_logo): ?>
                                    <div class="scrolling-content-client-logo">
                                        <img src="<?php echo esc_url($client_logo['url']); ?>" alt="<?php echo esc_attr($client_logo['alt']); ?>">
                                    </div>
                                <?php else: ?>
                                    <h3 class="scrolling-content-heading"><?php echo esc_html($client[0]->name); ?></h3>
                                <?php endif; ?>

                                <div class="scrolling-content-text">
                                    <?php echo get_the_title($case_study_id); ?>
                                </div>

                                <a class="scrolling-content-cta btn cta-link" href="<?php echo get_permalink($case_study_id); ?>">
                                    Read more
                                </a>

                            </div>

                        </div>

                    <?php endwhile; ?>

                    <?php wp_reset_postdata(); ?>

                <?php endif; ?>

            </div>

            <?php if ($case_studies->post_count > 1): ?>

                <div class="scrolling-contents-nav">

                    <button
                        type="button"
                        class="scrolling-contents-prev"
                        aria-label="<?php esc_attr_e('Previous', 'essenta-theme'); ?>"></button>

                    <button
                        type="button"
                        class="scrolling-contents-next"
                        aria-label="<?php esc_attr_e('Next', 'essenta-theme'); ?>"></button>

                </div>

            <?php endif; ?>

        </div>

    </div>
</section>

<script>
    jQuery(document).ready(function($) {

        var $slider = $('.full-height-scrolling-content .scrolling-contents');

        var mobileQuery = window.matchMedia('(max-width: 980px)');

        function initSlider(isMobile) {

            $slider.slick({
                vertical: !isMobile,
                verticalSwiping: !isMobile,
                slidesToShow: isMobile ? 1.1 : 1,
                slidesToScroll: 1,
                arrows: !isMobile,
                dots: false,
                infinite: !isMobile,
                centerMode: !isMobile,
                adaptiveHeight: false,
                prevArrow: $('.full-height-scrolling-content .scrolling-contents-prev'),
                nextArrow: $('.full-height-scrolling-content .scrolling-contents-next')
            });

        }

        function syncSliderOrientation(event) {

            if ($slider.hasClass('slick-initialized')) {
                $slider.slick('unslick');
            }

            initSlider(event.matches);

        }

        syncSliderOrientation(mobileQuery);

        mobileQuery.addEventListener(
            'change',
            syncSliderOrientation
        );

    });
</script>