<?php get_header(); ?>


<main class="content">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('header-page-builder'); ?>

            <?php if (have_rows('page_builder')) : ?>
                <?php get_template_part('page-builder'); ?>
            <?php else : ?>
                <?php the_content(); ?>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>