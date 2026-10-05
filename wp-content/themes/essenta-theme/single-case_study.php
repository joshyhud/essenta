<?php get_header(); ?>


<main class="content">
    <?php
    $header_part = get_template_part('header-page-builder');
    ?>
    <?php
    $page_part = get_template_part('page-builder');
    if (!$page_part) {
        // Fallback if page builder doesn't exist
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                the_content();
            }
        }
    }
    ?>
</main>

<?php get_footer(); ?>