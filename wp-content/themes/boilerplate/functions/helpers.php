<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Change excerpt length
function my_excerpt_length($length)
{
  return 15;
}
add_filter('excerpt_length', 'my_excerpt_length');

// Change excerpt after text
function new_excerpt_more($more)
{
  global $post;
  return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');
