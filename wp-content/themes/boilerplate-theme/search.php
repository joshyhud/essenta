<?php get_header(); ?>

<main class="search-results-page">

  <section class="search-results-header">
    <div class="container contained">
      <div class="search-header-content">
        <h1 class="subheading">Search Results</h1>
        <h2>
          <?php
          printf(
            'Results for: &ldquo;%s&rdquo;',
            esc_html(get_search_query())
          );
          ?>
        </h2>
        <p class="results-count">
          <?php
          global $wp_query;
          printf(
            '%d %s found',
            $wp_query->found_posts,
            $wp_query->found_posts === 1 ? 'result' : 'results'
          );
          ?>
        </p>
      </div>
      <form role="search" method="get" class="search-form search-results-form" action="<?php echo esc_url(home_url('/')); ?>">
        <div class="search-input-wrapper">
          <i class="search-icon"></i>
          <input type="search" class="search-field" placeholder="Search again..." value="<?php echo get_search_query(); ?>" name="s" />
          <button type="submit" class="search-submit btn">Search</button>
        </div>
      </form>
    </div>
  </section>

  <?php if (have_posts()) : ?>
    <div class="search-results-grid">
      <div class="container">
        <div class="featured-products-grid grid">
          <?php while (have_posts()) : the_post(); ?>

            <?php if (get_post_type() === 'product') :
              $product = wc_get_product(get_the_ID());
            ?>
              <div class="product-card col-3">
                <a href="<?php the_permalink(); ?>">
                  <div class="product-card-image">
                    <?php
                    $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    if ($thumb_url) : ?>
                      <?php the_post_thumbnail('full', array('loading' => 'lazy')); ?>
                    <?php else : ?>
                      <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
                    <?php endif; ?>

                    <?php if ($product) :
                      $pills = [];
                      if (!$product->is_in_stock()) {
                        $pills[] = 'Made-to-Order';
                      }
                      $post_date = get_the_date('U');
                      if ($post_date > strtotime('-3 weeks')) {
                        $pills[] = 'New';
                      }
                      if ($product->is_on_sale()) {
                        $pills[] = 'Sale';
                      }
                      if (!empty($pills)) : ?>
                        <div class="product-pills">
                          <?php foreach ($pills as $pill_text) : ?>
                            <div class="pill <?php echo esc_attr(strtolower(str_replace('-', '', $pill_text))); ?>"><?php echo esc_html($pill_text); ?></div>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                  <div class="product-card-title">
                    <h4><?php the_title(); ?></h4>
                    <?php if ($product && $product->is_in_stock()) : ?>
                      <div class="product-price">
                        <?php if ($product->is_on_sale()) : ?>
                          <span class="regular-price sale"><?php echo wp_kses_post(wc_price($product->get_regular_price())); ?></span>
                          <span class="sale-price"><?php echo wp_kses_post(wc_price($product->get_sale_price())); ?></span>
                        <?php else : ?>
                          <span class="price"><?php echo wp_kses_post(wc_price($product->get_price())); ?></span>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </a>
              </div>

            <?php else : ?>
              <!-- Posts & Pages: image + title only -->
              <div class="product-card col-3">
                <a href="<?php the_permalink(); ?>">
                  <div class="product-card-image">
                    <?php
                    $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                    if ($thumb_url) : ?>
                      <?php the_post_thumbnail('full', array('loading' => 'lazy')); ?>
                    <?php else : ?>
                      <img loading="lazy" src="<?php echo get_template_directory_uri() . '/src/images/product-placeholder.png'; ?>" alt="Placeholder Image">
                    <?php endif; ?>
                  </div>
                  <div class="product-card-title">
                    <h4><?php the_title(); ?></h4>
                  </div>
                </a>
              </div>
            <?php endif; ?>

          <?php endwhile; ?>
        </div>
      </div>
    </div>

    <div class="pagination-wrapper">
      <?php
      global $wp_query;
      if ($wp_query->max_num_pages > 1) {
        $pagination = paginate_links([
          'total' => $wp_query->max_num_pages,
          'current' => max(1, get_query_var('paged')),
          'prev_text' => 'Previous',
          'next_text' => 'Next',
          'type' => 'array',
        ]);

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
      }
      ?>
    </div>

  <?php else : ?>
    <section class="no-results">
      <div class="container">
        <div class="no-results-content">
          <h3>Nothing found</h3>
          <p>Sorry, no results were found for your search. Please try again with different keywords.</p>
        </div>
      </div>
    </section>
  <?php endif; ?>

</main>

<?php get_footer(); ?>