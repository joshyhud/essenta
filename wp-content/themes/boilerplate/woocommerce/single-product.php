<?php

/**
 * Single Product Template
 *
 * @package WooCommerce
 */

defined('ABSPATH') || exit;

get_header();


$post_id = get_the_ID();
$featured_image_url = get_the_post_thumbnail_url($post_id, 'full');
$featured_image_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true);

if (!$featured_image_alt) {
  $featured_image_alt = get_the_title($post_id);
}
?>

	<?php
  // get header part for product page
  echo get_template_part('templates/header-page-builder');



  // enter draw tickets or postal vote info
  echo get_template_part('templates//blocks/enter-draw');

  // get the main content from page builder
  echo get_template_part('templates/page-builder');



  get_footer();
