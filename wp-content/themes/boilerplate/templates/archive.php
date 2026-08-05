<main class="content">
  <?php
  $content_source_id = is_home() ? (int) get_option('page_for_posts') : get_queried_object_id();

  get_template_part('templates/header-page-builder', null, array(
    'post_id' => $content_source_id,
  ));

  if (is_home() && $content_source_id) {
    get_template_part('templates/page-builder', null, array(
      'post_id' => $content_source_id,
    ));
  }

  // Query for all posts
  $all_posts_query = new WP_Query(array(
    'posts_per_page' => -1,
    'post_status' => 'publish'
  ));

  if ($all_posts_query->have_posts()) : ?>
    <section class="container all-posts-grid">
      <div class="blog-grid">
        <?php while ($all_posts_query->have_posts()) : $all_posts_query->the_post(); ?>
          <div class="blog-card" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>');">
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
      </div>
    </section>
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>
</main>