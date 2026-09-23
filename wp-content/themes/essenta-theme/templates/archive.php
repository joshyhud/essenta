<?php
$posts_page_id = (int) get_option('page_for_posts');
$posts_page = $posts_page_id ? get_post($posts_page_id) : null;

if ($posts_page) {
  global $post;
  $post = $posts_page;
  setup_postdata($post);
}

$recent_insights = new WP_Query(array(
  'post_type' => 'post',
  'posts_per_page' => 2,
  'orderby' => 'date',
  'order' => 'DESC',
  'ignore_sticky_posts' => true,
));

$archive_query = $GLOBALS['wp_query'];
$archive_categories = get_categories(array(
  'taxonomy' => 'category',
  'hide_empty' => false,
  'orderby' => 'name',
  'order' => 'ASC',
));
?>

<main class="archive-blogs-page">
  <?php if ($posts_page && have_rows('header_pagebuilder')) : ?>
    <?php get_template_part('header-page-builder'); ?>
  <?php endif; ?>

  <?php wp_reset_postdata(); ?>

  <?php if ($recent_insights->have_posts()) : ?>
    <section class="archive-recent-insights">
      <div class="container">
        <div class="archive-section-heading">
          <p class="eyebrow">Recent insights</p>
          <h2>Latest thinking</h2>
        </div>

        <div class="archive-recent-insights-grid">
          <?php while ($recent_insights->have_posts()) : $recent_insights->the_post(); ?>
            <article class="archive-insight-card">
              <a href="<?php the_permalink(); ?>">
                <div class="archive-insight-card__image">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                  <?php endif; ?>
                </div>
                <div class="archive-insight-card__content">
                  <p class="eyebrow"><?php echo esc_html(get_the_date('d.m.Y')); ?></p>
                  <h3><?php the_title(); ?></h3>
                  <span class="btn cta-link">Read more</span>
                </div>
              </a>
            </article>
          <?php endwhile; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>

  <div class="container">
    <div class="archive-filtering">
      <div class="filter-bar">
        <div class="archive-category-filters" aria-label="Filter insights by category">
          <button class="archive-category-filter is-active" type="button" data-category="" aria-pressed="true">All</button>
          <?php foreach ($archive_categories as $category) : ?>
            <button
              class="archive-category-filter<?php echo $category->count ? '' : ' is-disabled'; ?>"
              type="button"
              data-category="<?php echo esc_attr($category->slug); ?>"
              aria-pressed="false"
              <?php echo $category->count ? '' : ' disabled'; ?>><?php echo esc_html($category->name); ?></button>
          <?php endforeach; ?>
        </div>

        <div class="filter-bar-right">
          <div class="results-count">
            <?php
            echo '<span>' . esc_html($archive_query->post_count) . '</span> Results';
            ?>
          </div>

        </div>
      </div>

    </div>

    <div class="all-posts-grid facetwp-template">
      <div class="blog-grid">
        <?php if ($archive_query->have_posts()) : ?>
          <?php while ($archive_query->have_posts()) : $archive_query->the_post(); ?>
            <div class="blog-card">
              <a href="<?php the_permalink(); ?>">
                <div class="blog-card-image">
                  <?php
                  $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                  if ($thumb_url) : ?>
                    <?php the_post_thumbnail('full', array('loading' => 'lazy')); ?>
                  <?php else : ?>
                    <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
                  <?php endif; ?>
                </div>

                <div class="blog-card-content">
                  <div class="blog-card-categories">
                    <?php foreach (get_the_category() as $category) : ?>
                      <span class="pill outline"><?php echo esc_html($category->name); ?></span>
                    <?php endforeach; ?>
                  </div>
                  <p class="blog-card-title subheading"><?php the_title(); ?></p>
                  <p class="blog-card-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                  <span class="blog-card-readmore btn cta-link">Read more</span>
                </div>
              </a>
            </div>
          <?php endwhile; ?>
        <?php else : ?>
          <p><?php echo esc_html__('No posts found.', 'essenta-theme'); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="pagination-wrapper">
      <?php
      if ($archive_query->max_num_pages > 1) :
        $pagination = paginate_links(array(
          'total' => $archive_query->max_num_pages,
          'current' => max(1, get_query_var('paged')),
          'prev_text' => 'Previous',
          'next_text' => 'Next',
          'type' => 'array',
        ));

        if ($pagination) {
          echo '<div class="pagination">';
          // Prev link
          echo '<div class="pagination-arrow pagination-prev">';
          foreach ($pagination as $link) {
            if (strpos($link, 'prev') !== false) {
              echo $link;
            }
          }
          echo '</div>';
          // Number links
          echo '<div class="pagination-numbers">';
          foreach ($pagination as $link) {
            if (strpos($link, 'prev') === false && strpos($link, 'next') === false) {
              echo str_replace('page-numbers', '', $link);
            }
          }
          echo '</div>';
          // Next link
          echo '<div class="pagination-arrow pagination-next">';
          foreach ($pagination as $link) {
            if (strpos($link, 'next') !== false) {
              echo $link;
            }
          }
          echo '</div>';
          echo '</div>';
        }
      endif;
      ?>
    </div>
  </div>

  <?php if ($posts_page) : ?>
    <?php
    global $post;
    $post = $posts_page;
    setup_postdata($post);
    ?>
    <?php if (have_rows('page_builder')) : ?>
      <?php get_template_part('page-builder'); ?>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

</main>

<?php wp_reset_postdata(); ?>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.archive-category-filter:not([disabled])');

    buttons.forEach(function(button) {
      button.addEventListener('click', function() {
        if (typeof FWP === 'undefined') {
          return;
        }

        FWP.facets.blog_categories = button.dataset.category ? [button.dataset.category] : [];
        FWP.refresh();
      });
    });

    document.addEventListener('facetwp-loaded', function() {
      const selectedCategory = typeof FWP !== 'undefined' && FWP.facets.blog_categories ?
        FWP.facets.blog_categories[0] || '' :
        '';

      buttons.forEach(function(button) {
        const isActive = button.dataset.category === selectedCategory;
        button.classList.toggle('is-active', isActive);
        button.setAttribute('aria-pressed', String(isActive));
      });
    });
  });
</script>