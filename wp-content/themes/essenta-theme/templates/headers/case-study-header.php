<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$case_study_right_subheading = get_sub_field('case_study_right_subheading');
$case_study_right_content = get_sub_field('case_study_content');
$case_study_stats = get_sub_field('case_study_statistics');
$case_study_stat_count = is_array($case_study_stats) ? count($case_study_stats) : 0;

?>

<section class="case-study-header">
    <div class="container">
        <div class="case-study-header-left">

            <p class="eyebrow">
                <?php
                $service_categories = get_the_terms(get_the_ID(), 'case_study_service');

                if ($service_categories && !is_wp_error($service_categories)) {
                    echo esc_html(implode(', ', wp_list_pluck($service_categories, 'name')));
                }
                ?>
            </p>

            <h1>
                <?php the_title(); ?>
            </h1>

            <div class="case-study-meta">
                <?php
                $case_study_meta = array(
                    'Client'   => 'case_study_client',
                    'Sector'   => 'case_study_sector',
                    'Service'  => 'case_study_service',
                    'Role'     => 'case_study_role',
                    'Regions'  => 'case_study_region',
                );

                foreach ($case_study_meta as $label => $taxonomy) {
                    $terms = get_the_terms(get_the_ID(), $taxonomy);

                    if (!$terms || is_wp_error($terms)) {
                        continue;
                    }
                ?>
                    <div class="case-study-meta-item">
                        <span class="case-study-meta-label"><?php echo esc_html($label); ?></span>
                        <span class="case-study-meta-value"><?php echo esc_html(implode(', ', wp_list_pluck($terms, 'name'))); ?></span>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="case-study-header-right">
            <?php if ($case_study_right_subheading) : ?>
                <p class="case-study-header-subheading eyebrow">
                    <?php echo esc_html($case_study_right_subheading); ?>
                </p>
            <?php endif; ?>
            <?php if ($case_study_right_content) : ?>
                <div class="case-study-header-content">
                    <?php echo wp_kses_post($case_study_right_content); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($case_study_stats)) : ?>
                <div class="case-study-stats<?php echo $case_study_stat_count > 1 ? ' case-study-stats--slider' : ''; ?>">
                    <?php foreach ($case_study_stats as $stat) : ?>
                        <div class="case-study-stat">
                            <span class="case-study-stat-label"><?php echo esc_html($stat['case_study_statistic']); ?></span>
                            <span class="case-study-stat-value"><?php echo esc_html($stat['case_study_statistic_content']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($case_study_stat_count > 1) : ?>
    <script>
        jQuery(document).ready(function($) {
            var $stats = $('.case-study-stats--slider');

            if ($stats.length && $.fn.slick && !$stats.hasClass('slick-initialized')) {
                $stats.slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: false,
                    infinite: false,
                    adaptiveHeight: false
                });
            }
        });
    </script>
<?php endif; ?>