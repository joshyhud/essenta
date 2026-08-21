<?php

/**
 * FacetWP integration
 */

// Set 12 posts per page on the blog archive
add_action('pre_get_posts', function ($query) {
  if (!is_admin() && $query->is_main_query() && (is_home() || is_category() || is_tag())) {
    $query->set('posts_per_page', 12);
  }
});
