<?php
/*
Template Name: Case Study Hub
Template Post Type: page
*/

get_header();

$paged = max(1, get_query_var('paged'), get_query_var('page'));
// Prefixed params: "expertise" is already a query var for the expertise post type.
$selected_region = isset($_GET['cs_region']) ? sanitize_title(wp_unslash($_GET['cs_region'])) : '';
$selected_expertise = isset($_GET['cs_expertise']) ? sanitize_title(wp_unslash($_GET['cs_expertise'])) : '';

$tax_query = array();

if ($selected_region) {
    $tax_query[] = array(
        'taxonomy' => 'case_study_region',
        'field' => 'slug',
        'terms' => $selected_region,
    );
}

if ($selected_expertise) {
    $tax_query[] = array(
        'taxonomy' => 'case_study_service',
        'field' => 'slug',
        'terms' => $selected_expertise,
    );
}

$case_studies = new WP_Query(array(
    'post_type' => 'case_study',
    'posts_per_page' => 6,
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'DESC',
    'tax_query' => $tax_query,
));

$regions = get_terms(array('taxonomy' => 'case_study_region', 'hide_empty' => true));
$expertise_terms = get_terms(array('taxonomy' => 'case_study_service', 'hide_empty' => true));

$get_first_term = static function ($post_id, $taxonomy) {
    $terms = get_the_terms($post_id, $taxonomy);
    return $terms && !is_wp_error($terms) ? $terms[0] : null;
};

// Stats live in the case_study_header layout of the header builder.
$get_first_stat = static function ($post_id) {
    $header_rows = get_field('header_pagebuilder', $post_id);

    if (!is_array($header_rows)) {
        return null;
    }

    foreach ($header_rows as $row) {
        if (($row['acf_fc_layout'] ?? '') === 'case_study_header' && !empty($row['case_study_statistics'][0])) {
            return $row['case_study_statistics'][0];
        }
    }

    return null;
};
?>

<main class="content casestudy-hub">
    <?php get_template_part('header-page-builder'); ?>

    <section class="casestudy-hub__directory">
        <div class="casestudy-hub__container">
            <form class="casestudy-hub__filters" method="get" action="<?php echo esc_url(get_permalink()); ?>">
                <div class="casestudy-hub__filter">
                    <label class="screen-reader-text" for="casestudy-region-filter"><?php esc_html_e('Filter by region', 'essenta-theme'); ?></label>
                    <select id="casestudy-region-filter" name="cs_region" onchange="this.form.submit()">
                        <option value=""><?php esc_html_e('All Regions', 'essenta-theme'); ?></option>
                        <?php if (!is_wp_error($regions)) : ?>
                            <?php foreach ($regions as $region) : ?>
                                <option value="<?php echo esc_attr($region->slug); ?>" <?php selected($selected_region, $region->slug); ?>><?php echo esc_html($region->name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="casestudy-hub__filter">
                    <label class="screen-reader-text" for="casestudy-expertise-filter"><?php esc_html_e('Filter by expertise', 'essenta-theme'); ?></label>
                    <select id="casestudy-expertise-filter" name="cs_expertise" onchange="this.form.submit()">
                        <option value=""><?php esc_html_e('All Expertise', 'essenta-theme'); ?></option>
                        <?php if (!is_wp_error($expertise_terms)) : ?>
                            <?php foreach ($expertise_terms as $expertise) : ?>
                                <option value="<?php echo esc_attr($expertise->slug); ?>" <?php selected($selected_expertise, $expertise->slug); ?>><?php echo esc_html($expertise->name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <noscript><button class="btn primary" type="submit"><?php esc_html_e('Filter', 'essenta-theme'); ?></button></noscript>

                <p class="casestudy-hub__results" aria-live="polite">
                    <?php esc_html_e('Showing', 'essenta-theme'); ?>
                    <span><?php echo esc_html($case_studies->post_count); ?></span>
                    <?php esc_html_e('of', 'essenta-theme'); ?>
                    <span><?php echo esc_html($case_studies->found_posts); ?></span>
                    <?php esc_html_e('case studies', 'essenta-theme'); ?>
                </p>
            </form>

            <?php if ($case_studies->have_posts()) : ?>
                <div class="casestudy-hub__grid">
                    <?php while ($case_studies->have_posts()) : $case_studies->the_post(); ?>
                        <?php
                        $case_study_id = get_the_ID();
                        $service = $get_first_term($case_study_id, 'case_study_service');
                        $sector = $get_first_term($case_study_id, 'case_study_sector');
                        $client = $get_first_term($case_study_id, 'case_study_client');
                        $client_logo = $client ? get_field('client_logo', $client) : null;
                        $stat = $get_first_stat($case_study_id);
                        ?>
                        <article class="casestudy-card">
                            <a class="casestudy-card__link" href="<?php the_permalink(); ?>">
                                <div class="casestudy-card__image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                                    <?php else : ?>
                                        <img loading="lazy" src="<?php echo esc_url(get_template_directory_uri() . '/src/images/product-placeholder.png'); ?>" alt="">
                                    <?php endif; ?>

                                    <?php if ($service || $sector) : ?>
                                        <div class="casestudy-card__tags">
                                            <?php if ($service) : ?>
                                                <span class="casestudy-card__tag"><?php echo esc_html($service->name); ?></span>
                                            <?php endif; ?>
                                            <?php if ($sector) : ?>
                                                <span class="casestudy-card__tag casestudy-card__tag--teal"><?php echo esc_html($sector->name); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="casestudy-card__body">
                                    <?php if (!empty($client_logo['url'])) : ?>
                                        <img class="casestudy-card__logo" loading="lazy" src="<?php echo esc_url($client_logo['url']); ?>" alt="<?php echo esc_attr($client->name); ?>">
                                    <?php endif; ?>

                                    <h2 class="casestudy-card__title"><?php the_title(); ?></h2>
                                    <p class="casestudy-card__excerpt"><?php echo get_the_excerpt(); ?></p>

                                    <div class="casestudy-card__footer">
                                        <span class="btn cta-link"><?php esc_html_e('Read story', 'essenta-theme'); ?></span>
                                    </div>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p class="casestudy-hub__empty"><?php esc_html_e('No case studies match these filters.', 'essenta-theme'); ?></p>
            <?php endif; ?>

            <?php
            $pagination = paginate_links(array(
                'total' => $case_studies->max_num_pages,
                'current' => $paged,
                'prev_text' => '<span class="screen-reader-text">' . esc_html__('Previous page', 'essenta-theme') . '</span>',
                'next_text' => '<span class="screen-reader-text">' . esc_html__('Next page', 'essenta-theme') . '</span>',
                'prev_next' => true,
                'type' => 'array',
            ));
            ?>
            <?php if ($pagination) : ?>
                <nav class="casestudy-hub__pagination" aria-label="<?php esc_attr_e('Case studies pagination', 'essenta-theme'); ?>">
                    <?php if ($paged <= 1) : ?>
                        <span class="page-numbers prev is-disabled" aria-hidden="true"></span>
                    <?php endif; ?>
                    <?php echo implode('', $pagination); ?>
                    <?php if ($paged >= $case_studies->max_num_pages) : ?>
                        <span class="page-numbers next is-disabled" aria-hidden="true"></span>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </div>
    </section>

    <?php wp_reset_postdata(); ?>
    <?php get_template_part('page-builder'); ?>
</main>

<?php get_footer(); ?>