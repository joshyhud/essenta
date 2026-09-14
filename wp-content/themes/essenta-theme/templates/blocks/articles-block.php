<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$articles_subheading = get_sub_field('articles_subheading');
$articles_heading = get_sub_field('articles_heading');
$latest_or_selected = get_sub_field('latest_or_selected');
$selected_articles = get_sub_field('selected_posts');

?>

<section class="articles-block">
  <div class="container">
    <div class="section-header">
      <p class="eyebrow"><?php echo esc_html($articles_subheading); ?></p>
      <h2 class="heading"><?php echo esc_html($articles_heading); ?></h2>
    </div>

    <div class="articles-content">
      <?php
      if ($latest_or_selected === 'latest') {
        $args = array(
          'post_type' => 'post',
          'posts_per_page' => 6,
        );
        $articles_query = new WP_Query($args);
      } elseif ($latest_or_selected === 'selected_posts' && $selected_articles) {
        $args = array(
          'post_type' => 'post',
          'post__in' => $selected_articles,
          'posts_per_page' => -1,
          'orderby' => 'post__in',
        );
        $articles_query = new WP_Query($args);
      } else {
        $articles_query = null;
      }

      if ($articles_query && $articles_query->have_posts()) : ?>
        <div class="articles-wrapper">
          <div class="articles-list">
            <?php $index = 0;
            while ($articles_query->have_posts()) : $articles_query->the_post(); ?>
              <div class="article-item" data-index="<?php echo $index; ?>">
                <a href="<?php the_permalink(); ?>" class="btn">
                  <div class="article-date"><?php echo get_the_date('d-m-Y'); ?></div>
                  <div class="article-category pill">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                      echo esc_html($categories[0]->name);
                    }
                    ?>
                  </div>
                  <h3 class="article-title"><?php the_title(); ?></h3>
                  <p class="btn primary">Read Article</p>
                </a>
              </div>
              <?php $index++; ?>
            <?php endwhile; ?>
            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="articles-cta btn primary">View All Articles</a>
          </div>

          <div class="articles-image">
            <?php
            // Reset query to show first post image by default
            $articles_query->rewind_posts();
            $image_index = 0;
            while ($articles_query->have_posts()) : $articles_query->the_post();
            ?>
              <div class="article-image-item <?php echo $image_index === 0 ? 'active' : ''; ?>" data-index="<?php echo $image_index; ?>">
                <?php
                $thumbnail_id = get_post_thumbnail_id();
                $thumb_url = wp_get_attachment_image_src($thumbnail_id, 'full');
                if (!$thumb_url) : ?>
                  <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
                <?php else : ?>
                  <?php the_post_thumbnail('full', ['loading' => 'lazy']); ?>
                <?php endif; ?>
              </div>
              <?php $image_index++; ?>
            <?php endwhile; ?>
          </div>
        </div>

        <!-- Mobile Slider Wrapper -->
        <div class="articles-mobile-slider">
          <div class="articles-slider">
            <?php
            // Reset query for mobile slider
            $articles_query->rewind_posts();
            while ($articles_query->have_posts()) : $articles_query->the_post();
            ?>
              <div class="article-slide">
                <div class="slide-header">
                  <div class="article-date"><?php echo get_the_date('d-m-Y'); ?></div>
                  <h3 class="article-title"><?php the_title(); ?></h3>
                </div>

                <div class="slide-image">
                  <?php
                  $thumbnail_id = get_post_thumbnail_id();
                  $thumb_url = wp_get_attachment_image_src($thumbnail_id, 'full');
                  if ($thumb_url) : ?>
                    <?php the_post_thumbnail('full', array('loading' => 'lazy')); ?>
                  <?php else : ?>
                    <img loading="lazy" src="<?php echo esc_url(get_template_directory_uri() . '/src/images/product-placeholder.png'); ?>" alt="Placeholder Image">
                  <?php endif; ?>
                </div>

                <div class="slide-footer">
                  <div class="article-category pill">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                      echo esc_html($categories[0]->name);
                    }
                    ?>
                  </div>
                  <a href="<?php the_permalink(); ?>" class="btn primary">Read Article</a>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
          <div class="slider-nav articles">
            <div class="slider-dots"></div>
            <div class="slider-arrows">
              <button class="slick-prev"></button>
              <button class="slick-next"></button>
            </div>
          </div>
        </div>

        <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p><?php esc_html_e('No articles found.', 'boilerplate-theme'); ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>