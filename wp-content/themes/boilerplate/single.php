<?php get_header(); ?>

<?php
get_template_part('templates/headers/blog-header', 'single');
?>

<div class="container contained">
    <?php
    $page_part = get_template_part('templates/page-builder');
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
</div>

<section class="latest-news-block">
    <div class="container more-from-blog">
        <h2 class="heading">More from our Blog</h2>

        <div class="latest-news-grid">
            <?php
            $args = array(
                'posts_per_page' => 3,
                'post__not_in' => array(get_the_ID())
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="blog-card" style="background-image: url('<?php echo the_post_thumbnail_url('medium'); ?>');">
                        <a href="<?php the_permalink(); ?>">
                            <div class="card-content">
                                <div class="blog-date"><?php echo get_the_date(); ?></div>
                                <div class="card-content--inner">
                                    <h5 class="card-title"><?php the_title(); ?></h5>
                                    <div class="author">
                                        <div class="author-avatar">
                                            <?php echo get_avatar(get_the_author_meta('ID'), 32); ?>
                                            <span class="author-name"><?php the_author(); ?></span>
                                        </div>
                                        <i class="card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php esc_html_e('No posts found.', 'text-domain'); ?></p>
            <?php endif; ?>
        </div>
        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn primary">
            <?php esc_html_e('View All News', 'text-domain'); ?>
        </a>
    </div>
</section>


<?php get_footer(); ?>