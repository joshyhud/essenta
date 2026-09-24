<?php
/*
Template Name: Team Archive
Template Post Type: page
*/

get_header();

$team_members = new WP_Query(array(
    'post_type' => 'team_member',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
));
$has_team_members = $team_members->have_posts();
$team_locations = get_terms(array(
    'taxonomy' => 'team_location',
    'hide_empty' => true,
));

if (!is_wp_error($team_locations)) {
    $team_locations = array_values(array_filter($team_locations, static function ($term) {
        return $term->parent > 0;
    }));
}

$team_departments = get_terms(array(
    'taxonomy' => 'department',
    'hide_empty' => true,
));
$get_filter_term_slugs = static function ($terms, $taxonomy) {
    if (!$terms || is_wp_error($terms)) {
        return array();
    }

    $term_ids = wp_list_pluck($terms, 'term_id');

    foreach ($terms as $term) {
        $term_ids = array_merge($term_ids, get_ancestors($term->term_id, $taxonomy, 'taxonomy'));
    }

    $term_ids = array_unique(array_map('intval', $term_ids));

    return array_values(array_filter(array_map(static function ($term_id) use ($taxonomy) {
        $term = get_term($term_id, $taxonomy);
        return $term && !is_wp_error($term) ? $term->slug : '';
    }, $term_ids)));
};
?>

<main class="content team-archive">
    <?php get_template_part('header-page-builder'); ?>

    <section class="team-archive__directory">
        <div class="container">
            <?php if ($has_team_members) : ?>
                <div class="team-archive__filters" data-team-filters>
                    <p class="team-archive__results" aria-live="polite">
                        <?php esc_html_e('Showing', 'essenta-theme'); ?>
                        <span data-team-visible-count><?php echo esc_html($team_members->post_count); ?></span>
                        <?php esc_html_e('of', 'essenta-theme'); ?>
                        <span><?php echo esc_html($team_members->post_count); ?></span>
                        <?php esc_html_e('experts', 'essenta-theme'); ?>
                    </p>

                    <div class="team-archive__filter">
                        <select id="team-location-filter" data-team-filter="location">
                            <option value=""><?php esc_html_e('All locations', 'essenta-theme'); ?></option>
                            <?php if (!is_wp_error($team_locations)) : ?>
                                <?php foreach ($team_locations as $team_location) : ?>
                                    <option value="<?php echo esc_attr($team_location->slug); ?>"><?php echo esc_html($team_location->name); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="team-archive__filter">
                        <select id="team-department-filter" data-team-filter="department">
                            <option value=""><?php esc_html_e('All expertise', 'essenta-theme'); ?></option>
                            <?php if (!is_wp_error($team_departments)) : ?>
                                <?php foreach ($team_departments as $team_department) : ?>
                                    <option value="<?php echo esc_attr($team_department->slug); ?>"><?php echo esc_html($team_department->name); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <p class="team-archive__empty" data-team-empty hidden><?php esc_html_e('No team members match these filters.', 'essenta-theme'); ?></p>

                <div class="team-archive__grid" data-team-grid>
                    <?php while ($team_members->have_posts()) : $team_members->the_post(); ?>
                        <?php
                        $member_id = get_the_ID();
                        $role = get_field('job_role', $member_id);
                        $departments = get_the_terms($member_id, 'department');
                        $locations = get_the_terms($member_id, 'team_location');
                        $department_filter_slugs = $get_filter_term_slugs($departments, 'department');
                        $location_filter_slugs = $locations && !is_wp_error($locations)
                            ? wp_list_pluck($locations, 'slug')
                            : array();
                        ?>
                        <article
                            class="team-archive__card"
                            data-team-location="<?php echo esc_attr(implode(' ', $location_filter_slugs)); ?>"
                            data-team-department="<?php echo esc_attr(implode(' ', $department_filter_slugs)); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="team-archive__image">
                                    <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                                </div>
                            <?php endif; ?>

                            <div class="team-archive__card-body">
                                <h2 class="team-archive__name"><?php the_title(); ?></h2>

                                <?php if ($role) : ?>
                                    <p class="team-archive__role"><?php echo esc_html($role); ?></p>
                                <?php endif; ?>

                                <div class="team-archive__meta">

                                    <?php if ($departments && !is_wp_error($departments)) : ?>
                                        <p class="team-archive__department"><?php echo esc_html(implode(', ', wp_list_pluck($departments, 'name'))); ?></p>
                                    <?php endif; ?>

                                    <?php if ($locations && !is_wp_error($locations)) : ?>
                                        <p class="team-archive__location"><?php echo esc_html(implode(', ', wp_list_pluck($locations, 'name'))); ?></p>
                                    <?php endif; ?>
                                </div>

                                <button
                                    class="team-archive__profile-trigger btn cta-link"
                                    type="button"
                                    aria-controls="team-profile-drawer"
                                    aria-expanded="false"
                                    data-team-profile="team-profile-<?php echo esc_attr($member_id); ?>">
                                    <?php esc_html_e('View Profile', 'essenta-theme'); ?>
                                </button>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p><?php esc_html_e('No team members found.', 'essenta-theme'); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($has_team_members) : ?>
        <div class="team-archive__drawer-shell" data-team-drawer hidden>
            <button class="team-archive__backdrop" type="button" aria-label="<?php esc_attr_e('Close profile', 'essenta-theme'); ?>" data-team-drawer-close></button>
            <aside
                class="team-archive__drawer"
                id="team-profile-drawer"
                role="dialog"
                aria-modal="true"
                aria-label="<?php esc_attr_e('Team member profile', 'essenta-theme'); ?>"
                tabindex="-1">
                <button class="team-archive__drawer-close" type="button" aria-label="<?php esc_attr_e('Close profile', 'essenta-theme'); ?>" data-team-drawer-close></button>
                <div data-team-drawer-content></div>
            </aside>
        </div>

        <?php $team_members->rewind_posts(); ?>
        <?php while ($team_members->have_posts()) : $team_members->the_post(); ?>
            <?php
            $member_id = get_the_ID();
            $role = get_field('job_role', $member_id);
            $departments = get_the_terms($member_id, 'department');
            $locations = get_the_terms($member_id, 'team_location');

            $profile_linkedin = get_field('team_member_linkedin', $member_id);
            $profile_email = get_field('team_member_email', $member_id);
            $profile_email_url = $profile_email['url'] ?? '';
            $profile_email_address = preg_replace('#^https?://#i', '', $profile_email_url);

            if (is_email($profile_email_address)) {
                $profile_email_url = 'mailto:' . $profile_email_address;
            }

            $profile_expertise = get_field('team_member_expertise', $member_id);

            $professional_overview = get_field('professional_overview', $member_id);
            $sector_and_functional_experience = get_field('sector_and_functional_experience', $member_id);
            $relevant_career_experience = get_field('relevant_career_experience', $member_id);
            $personal_perspective = get_field('personal_perspective', $member_id);
            $featured_quote = get_field('featured_quote', $member_id);

            ?>
            <template id="team-profile-<?php echo esc_attr($member_id); ?>">
                <article class="team-archive__profile">
                    <div class="team-archive__profile-header">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="team-archive__profile-image">
                                <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                            </div>
                        <?php endif; ?>

                        <div class="team-archive__profile-intro">
                            <h2><?php the_title(); ?></h2>

                            <?php if ($role) : ?>
                                <p class="team-archive__profile-role"><?php echo esc_html($role); ?></p>
                            <?php endif; ?>

                            <div class="team-archive__profile-meta">
                                <?php if ($locations && !is_wp_error($locations)) : ?>
                                    <span class="team-archive__profile-location">
                                        <img loading="lazy" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/pin.svg'); ?>" alt="">
                                        <?php echo esc_html(implode(', ', wp_list_pluck($locations, 'name'))); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($profile_linkedin) : ?>
                                    <a href="<?php echo esc_url($profile_linkedin['url']); ?>" target="<?php echo esc_attr($profile_linkedin['target'] ?: '_blank'); ?>" rel="noopener noreferrer">
                                        <img loading="lazy" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/linkedin.svg'); ?>" alt="">
                                        <?php echo esc_html($profile_linkedin['title']); ?>
                                    </a>
                                <?php endif; ?>
                                <?php if ($profile_email) : ?>
                                    <a href="<?php echo esc_url($profile_email_url); ?>">
                                        <img loading="lazy" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/mail.svg'); ?>" alt="">
                                        <?php echo esc_html($profile_email['title']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <?php if ($profile_expertise) : ?>
                                <div class="team-archive__profile-expertise" aria-label="<?php esc_attr_e('Areas of expertise', 'essenta-theme'); ?>">
                                    <p><?php esc_html_e('Areas of expertise', 'essenta-theme'); ?></p>
                                    <div>
                                        <?php foreach ($profile_expertise as $expertise) : ?>
                                            <?php $expertise_label = is_array($expertise) ? ($expertise['expertise'] ?? '') : $expertise; ?>
                                            <?php if ($expertise_label) : ?>
                                                <span><?php echo esc_html($expertise_label); ?></span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <a class="btn primary team-archive__profile-cta" href="/contact">
                                <?php esc_html_e('Talk to an expert', 'essenta-theme'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="team-archive__profile-content">
                        <div class="profile-content-overlay"></div>
                        <?php if ($professional_overview) : ?>
                            <section class="team-archive__profile-section">
                                <h3><?php esc_html_e('Professional overview', 'essenta-theme'); ?></h3>
                                <div>
                                    <?php echo wp_kses_post($professional_overview); ?>
                                </div>
                            </section>
                        <?php endif; ?>
                        <?php if ($sector_and_functional_experience) : ?>
                            <section class="team-archive__profile-section">
                                <h3><?php esc_html_e('Sector and functional experience', 'essenta-theme'); ?></h3>
                                <div>
                                    <?php echo wp_kses_post($sector_and_functional_experience); ?>
                                </div>
                            </section>
                        <?php endif; ?>
                        <?php if ($relevant_career_experience) : ?>
                            <section class="team-archive__profile-section">
                                <h3><?php esc_html_e('Relevant career experience', 'essenta-theme'); ?></h3>
                                <div>
                                    <?php echo wp_kses_post($relevant_career_experience); ?>
                                </div>
                            </section>
                        <?php endif; ?>
                        <?php if ($personal_perspective) : ?>
                            <section class="team-archive__profile-section">
                                <h3><?php esc_html_e('Personal perspective', 'essenta-theme'); ?></h3>
                                <div>
                                    <?php echo wp_kses_post($personal_perspective); ?>
                                </div>
                            </section>
                        <?php endif; ?>
                        <?php if ($featured_quote) : ?>
                            <blockquote class="team-archive__profile-quote"><?php echo esc_html($featured_quote); ?></blockquote>
                        <?php endif; ?>
                    </div>
                </article>
            </template>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
    <?php get_template_part('page-builder'); ?>
</main>

<?php get_footer(); ?>