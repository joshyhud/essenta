<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$sectors_subheading = get_sub_field('sectors_subheading');
$sectors_heading = get_sub_field('sectors_heading');
$sectors_content = get_sub_field('sectors_content');

$sectors_select = get_sub_field('sectors_select');

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
                <div class="sectors-content"><?php echo wp_kses_post($sectors_content); ?></div>
            <?php endif; ?>
        </div>

        <?php
        $terms = $sectors_select;
        if ($terms && !is_array($terms)) {
            $terms = array($terms);
        }

        if ($terms) :
            $tax_query = array('relation' => 'OR');
            foreach ($terms as $term) {
                $term_obj = is_object($term) ? $term : get_term($term);
                if (!$term_obj || is_wp_error($term_obj)) {
                    continue;
                }
                $tax_query[] = array(
                    'taxonomy' => $term_obj->taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $term_obj->term_id,
                );
            }

            $sectors_query = new WP_Query(array(
                'post_type'              => 'expertise',
                'posts_per_page'         => -1,
                'ignore_sticky_posts'    => true,
                'no_found_rows'          => true,
                'tax_query'              => $tax_query,
            ));

            if ($sectors_query->have_posts()) :
        ?>
                <div class="sectors-selection">
                    <div class="sectors-grid">
                        <?php while ($sectors_query->have_posts()) : $sectors_query->the_post(); ?>
                            <article class="sectors-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a class="sectors-card__image" href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="sectors-card__body">
                                    <h3 class="sectors-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <div class="sectors-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></div>
                                    <a class="sectors-card__cta btn cta-link" href="<?php the_permalink(); ?>">Read more
                                    </a>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
        <?php
            endif;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</section>