<?php
get_template_part('templates/components/archive-header', 'single');

?>

<main class="archive-blogs-page">

  <section class="blog-posts-header">
    <div class="container">
      <div class="archive-blog-header">
        <h1 class="subheading">Our Blog</h1>
        <h2>Jewellery guides, news & articles.</h2>
      </div>
    </div>
  </section>

  <?php global $wp_query; ?>

  <div class="container">
    <div class="archive-filtering">
      <div class="filter-bar">
        <div class="filter-bar-left">
          <button class="filter-toggle btn secondary" id="filterToggle" type="button" aria-controls="filterDropdown">
            <span>Select Filters</span>
            <svg class="filter-icon" width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
              <path d="M14.5972 0.375V5.625M14.5972 5.625C13.6154 5.625 12.8194 6.4085 12.8194 7.375C12.8194 8.3415 13.6154 9.125 14.5972 9.125M14.5972 5.625C15.5791 5.625 16.375 6.4085 16.375 7.375C16.375 8.3415 15.5791 9.125 14.5972 9.125M14.5972 9.125V14.375M8.375 0.375V10.875M8.375 10.875C7.39316 10.875 6.59722 11.6585 6.59722 12.625C6.59722 13.5915 7.39316 14.375 8.375 14.375C9.35684 14.375 10.1528 13.5915 10.1528 12.625C10.1528 11.6585 9.35684 10.875 8.375 10.875ZM2.15278 3.875V14.375M2.15278 3.875C3.13462 3.875 3.93056 3.0915 3.93056 2.125C3.93056 1.1585 3.13462 0.375 2.15278 0.375C1.17094 0.375 0.375 1.1585 0.375 2.125C0.375 3.0915 1.17094 3.875 2.15278 3.875Z" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <svg class="close-icon" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
              <path d="M1 1L16 16M16 1L1 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
          <div class="selected-filters" aria-live="polite">
            <?php echo facetwp_display('selections'); ?>
          </div>
        </div>

        <div class="filter-bar-right">
          <div class="results-count">
            <?php
            echo '<span>' . esc_html($wp_query->post_count) . '</span> Results';
            ?>
          </div>

          <div class="sort-dropdown">
            <p>Sort By: </p>
            <?php
            if (function_exists('facetwp_display')) {
              echo facetwp_display('facet', 'blog_sort');
            }
            ?>
          </div>
        </div>
      </div>

      <div class="filter-dropdown" id="filterDropdown" hidden>
        <div class="filter-facets">
          <?php if (function_exists('facetwp_display')) : ?>

            <details class="facet-group facet-category">
              <summary>Category</summary>
              <?php echo facetwp_display('facet', 'blog_categories'); ?>
            </details>

          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="all-posts-grid facetwp-template">
      <div class="blog-grid">
        <?php if (have_posts()) : ?>
          <?php while (have_posts()) : the_post(); ?>
            <div class="blog-card">
              <a href="<?php the_permalink(); ?>">
                <div class="blog-card-content">
                  <p class="blog-card-title subheading">
                    <?php the_title(); ?>
                  </p>
                </div>

                <div class="blog-card-image">
                  <?php
                  $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                  if ($thumb_url) : ?>
                    <?php the_post_thumbnail('full', array('loading' => 'lazy')); ?>
                  <?php else : ?>
                    <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
                  <?php endif; ?>
                </div>
              </a>
            </div>
          <?php endwhile; ?>
        <?php else : ?>
          <p><?php echo esc_html__('No posts found.', 'boilerplate-theme'); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="pagination-wrapper">
      <?php
      if ($wp_query->max_num_pages > 1) :
        $pagination = paginate_links(array(
          'total' => $wp_query->max_num_pages,
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

</main>


<script>
  document.addEventListener('DOMContentLoaded', function() {

    const toggle = document.getElementById('filterToggle');
    const dropdown = document.getElementById('filterDropdown');
    const selected = document.getElementById('selectedFilters');

    if (toggle && dropdown) {
      toggle.addEventListener('click', function() {
        const isHidden = dropdown.hidden;
        dropdown.hidden = !isHidden;
        toggle.setAttribute('aria-expanded', String(isHidden));
      });
    }


  });
</script>