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
            <article class="blog-card">
              <a href="<?php the_permalink(); ?>">
                <div class="blog-card-image">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                  <?php endif; ?>
                  <div class="blog-card-categories">
                    <?php $category = get_the_category(); ?>
                    <?php if ($category) : ?>
                      <span class="pill outline"><?php echo esc_html($category[0]->name); ?></span>
                    <?php endif; ?>
                    <?php $expertise_terms = get_the_terms(get_the_ID(), 'post-expertise'); ?>
                    <?php if ($expertise_terms && !is_wp_error($expertise_terms)) : ?>
                      <span class="pill"><?php echo esc_html($expertise_terms[0]->name); ?></span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="blog-card-content">
                  <h4><?php the_title(); ?></h4>
                  <p class="blog-card-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
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

  <section class="archive-posts-grid">
    <div class="container">
      <div class="archive-filtering">
        <div class="filter-bar">
          <?php if (function_exists('facetwp_display')) : ?>
            <div class="archive-post-type-filters" aria-label="Filter insights by post type">
              <button class="archive-post-type-all is-active" type="button" aria-pressed="true">All</button>
              <?php echo facetwp_display('facet', 'post_types'); ?>
            </div>
          <?php endif; ?>

          <?php if (function_exists('facetwp_display')) : ?>
            <div class="archive-expertise-filter">
              <?php echo facetwp_display('facet', 'post_expertise'); ?>

              <div class="filter-bar-right">
                <div class="results-count">
                  <?php
                  echo 'Showing <span>' . esc_html($archive_query->post_count) . '</span> of <span>' . esc_html($archive_query->found_posts) . '</span> insights';
                  ?>
                </div>

              </div>

            </div>
          <?php endif; ?>
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
                    <div class="blog-card-categories">
                      <?php $category = get_the_category(); ?>
                      <?php if ($category) : ?>
                        <span class="pill outline"><?php echo esc_html($category[0]->name); ?></span>
                      <?php endif; ?>
                      <?php $expertise_terms = get_the_terms(get_the_ID(), 'post-expertise'); ?>
                      <?php if ($expertise_terms && !is_wp_error($expertise_terms)) : ?>
                        <span class="pill"><?php echo esc_html($expertise_terms[0]->name); ?></span>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="blog-card-content">
                    <h4 class="blog-card-title"><?php the_title(); ?></h4>
                    <?php $excerpt = get_the_excerpt(); ?>
                    <?php if ($excerpt) : ?>
                      <p class="blog-card-excerpt"><?php echo esc_html($excerpt); ?></p>
                    <?php endif; ?>
                    <div class="btn cta-link">Read More</div>
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
    </div> <!-- Close container -->
  </section>

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
    const allButton = document.querySelector('.archive-post-type-all');
    const recentInsights = jQuery('.archive-recent-insights-grid');
    const mobileQuery = window.matchMedia('(max-width: 768px)');

    function syncRecentInsightsSlider(event) {
      if (!recentInsights.length) {
        return;
      }

      if (event.matches && !recentInsights.hasClass('slick-initialized')) {
        recentInsights.slick({
          slidesToShow: 1.1,
          slidesToScroll: 1,
          arrows: false,
          dots: false,
          infinite: false,
          adaptiveHeight: true,
        });
      } else if (!event.matches && recentInsights.hasClass('slick-initialized')) {
        recentInsights.slick('unslick');
      }
    }

    syncRecentInsightsSlider(mobileQuery);
    mobileQuery.addEventListener('change', syncRecentInsightsSlider);

    function renderPostTypeButtons() {
      const facet = document.querySelector('.archive-post-type-filters .facetwp-facet');
      const dropdown = facet ? facet.querySelector('.facetwp-dropdown') : null;

      if (!facet || !dropdown || facet.querySelector('.archive-post-type-options')) {
        return;
      }

      const options = document.createElement('div');
      options.className = 'archive-post-type-options';

      Array.prototype.forEach.call(dropdown.options, function(option) {
        if (!option.value) {
          return;
        }

        const button = document.createElement('button');
        const isSelected = option.selected;

        button.className = 'archive-post-type-option';
        button.type = 'button';
        button.dataset.value = option.value;
        button.setAttribute('aria-pressed', String(isSelected));
        button.textContent = option.text.replace(/\s*\(\d+\)$/, '');

        if (isSelected) {
          button.classList.add('is-active');
        }

        button.addEventListener('click', function() {
          if (typeof FWP === 'undefined') {
            return;
          }

          FWP.facets.post_type = [button.dataset.value];
          FWP.is_reset = true;
          FWP.refresh();
        });

        options.appendChild(button);
      });

      dropdown.hidden = true;
      facet.appendChild(options);
    }

    if (allButton) {
      allButton.addEventListener('click', function() {
        if (typeof FWP !== 'undefined') {
          FWP.reset('post_type');
        }
      });
    }

    document.addEventListener('facetwp-loaded', function() {
      if (!allButton || typeof FWP === 'undefined') {
        return;
      }

      const isAllSelected = !(FWP.facets.post_type || []).length;
      allButton.classList.toggle('is-active', isAllSelected);
      allButton.setAttribute('aria-pressed', String(isAllSelected));
      renderPostTypeButtons();
    });

    renderPostTypeButtons();
  });
</script>