<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$latest_new_header = get_sub_field('latest_news_header');

$args = array(
  'post_type' => 'post',
  'posts_per_page' => 3,
);

$latest_news_query = new WP_Query($args);


if (!$latest_news_query->have_posts()) {
  return;
}
?>

<section class="latest-news-block">
  <div class="container">
    <?php if (!empty($latest_new_header)) : ?>
      <h2 class="heading"><?php echo esc_html($latest_new_header); ?></h2>
    <?php endif; ?>

    <div class="latest-news-grid">
      <?php if ($latest_news_query->have_posts()) : ?>
        <?php while ($latest_news_query->have_posts()) : $latest_news_query->the_post(); ?>
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
        <p><?php esc_html_e('No news found.', 'text-domain'); ?></p>
      <?php endif; ?>
    </div>
    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn primary">
      <?php esc_html_e('View All News', 'text-domain'); ?>
    </a>
  </div>
</section>