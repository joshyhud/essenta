<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$sectors_subheading = get_sub_field('sectors_subheading');
$sectors_heading = get_sub_field('sectors_heading');
$sectors_content = get_sub_field('sectors_content');

$sectors_select = get_sub_field('sectors_select');
$manual_sectors = get_sub_field('manual_sectors');
?>

<section class="sectors-module">
    <div class="container">
        <div class="sectors-inner">
            <?php if ($sectors_subheading) : ?>
                <p class="sectors-subheading eyebrow"><?php echo esc_html($sectors_subheading); ?></p>
            <?php endif; ?>

            <?php if ($sectors_heading) : ?>
                <h2 class="sectors-heading"><?php echo esc_html($sectors_heading); ?></h2>
            <?php endif; ?>

            <?php if ($sectors_content) : ?>
                <div class="sectors-content">
                    <?php echo wp_kses_post($sectors_content); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php
        // ACF taxonomy field returns the selected term ID.
        $term_ids = $sectors_select;

        if (!is_array($term_ids)) {
            $term_ids = array($term_ids);
        }


        // Make sure we only have valid IDs.
        $term_ids = array_filter(array_map('intval', $term_ids));

        if ($term_ids) :

            // Get the taxonomy from the selected term ID.
            $term = get_term($term_ids[0]);

            if ($term && !is_wp_error($term)) :

                $taxonomy = $term->taxonomy;

                $sectors_query = new WP_Query(array(
                    'post_type'      => 'expertise',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'post_parent__not_in' => array(0),
                    'tax_query'      => array(
                        array(
                            'taxonomy' => $taxonomy,
                            'field'    => 'term_id',
                            'terms'    => $term_ids,
                            'operator'  => 'IN',
                        ),
                    ),
                ));

                if ($sectors_query->have_posts()) : ?>
                    <div class="sectors-selection">
                        <div class="sectors-grid">

                            <?php while ($sectors_query->have_posts()) : $sectors_query->the_post(); ?>

                                <article class="sectors-card">

                                    <?php if (has_post_thumbnail()) : ?>
                                        <a class="sectors-card__image" href="<?php the_permalink(); ?>">
                                            <?php
                                            the_post_thumbnail(
                                                'medium_large',
                                                array(
                                                    'loading' => 'lazy',
                                                )
                                            );
                                            ?>
                                        </a>
                                    <?php endif; ?>

                                    <div class="sectors-card__body">
                                        <h3 class="sectors-card__title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>

                                        <div class="sectors-card__excerpt">
                                            <?php echo esc_html(get_the_excerpt()); ?>
                                        </div>

                                        <a
                                            class="sectors-card__cta btn cta-link"
                                            href="<?php the_permalink(); ?>">
                                            Read more
                                        </a>
                                    </div>

                                </article>

                            <?php endwhile; ?>

                            <?php if ($manual_sectors) : ?>

                                <?php foreach ($manual_sectors as $sector) :

                                    $sector_image = $sector['manual_sector_image'];
                                    $sector_title = $sector['manual_sector_title'];
                                    $sector_text = $sector['manual_sector_text'];

                                ?>

                                    <article class="sectors-card">

                                        <?php if ($sector_image) : ?>
                                            <div class="sectors-card__image">
                                                <img
                                                    src="<?php echo esc_url($sector_image['url']); ?>"
                                                    alt="<?php echo esc_attr($sector_image['alt']); ?>"
                                                    loading="lazy" />
                                            </div>
                                        <?php endif; ?>

                                        <div class="sectors-card__body">

                                            <h3 class="sectors-card__title">
                                                <?php echo esc_html($sector_title); ?>
                                            </h3>

                                            <div class="sectors-card__excerpt">
                                                <?php echo wp_kses_post($sector_text); ?>
                                            </div>

                                        </div>

                                    </article>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </div>
                    </div>

        <?php endif;

                wp_reset_postdata();

            endif; // End check for $term_ids
        endif; ?>

    </div>
</section>

<script>
    jQuery(document).ready(function($) {
        var $grid = $('.sectors-module .sectors-grid');
        var mobileQuery = window.matchMedia('(max-width: 760px)');

        function syncSectorsSlider(event) {
            if (!$grid.length) {
                return;
            }

            if (event.matches && !$grid.hasClass('slick-initialized')) {
                $grid.slick({
                    slidesToShow: 1.2,
                    slidesToScroll: 1,
                    arrows: false,
                    dots: false,
                    infinite: false,
                    adaptiveHeight: false
                });
            } else if (!event.matches && $grid.hasClass('slick-initialized')) {
                $grid.slick('unslick');
            }
        }

        syncSectorsSlider(mobileQuery);

        mobileQuery.addEventListener('change', syncSectorsSlider);
    });
</script>