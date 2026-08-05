<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Register Custom Post Type for Testimonials
function create_testimonial_cpt()
{

  $labels = array(
    'name' => _x('Testimonials', 'Post Type General Name', 'textdomain'),
    'singular_name' => _x('Testimonial', 'Post Type Singular Name', 'textdomain'),
    'menu_name' => _x('Testimonials', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Testimonial', 'Add New on Toolbar', 'textdomain'),
    'archives' => __('Testimonial Archives', 'textdomain'),
    'attributes' => __('Testimonial Attributes', 'textdomain'),
    'parent_item_colon' => __('Parent Testimonial:', 'textdomain'),
    'all_items' => __('All Testimonials', 'textdomain'),
    'add_new_item' => __('Add New Testimonial', 'textdomain'),
    'add_new' => __('Add New', 'textdomain'),
    'new_item' => __('New Testimonial', 'textdomain'),
    'edit_item' => __('Edit Testimonial', 'textdomain'),
    'update_item' => __('Update Testimonial', 'textdomain'),
    'view_item' => __('View Testimonial', 'textdomain'),
    'view_items' => __('View Testimonials', 'textdomain'),
    'search_items' => __('Search Testimonial', 'textdomain'),
    'not_found' => __('Not found', 'textdomain'),
    'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
    'featured_image' => __('Featured Image', 'textdomain'),
    'set_featured_image' => __('Set featured image', 'textdomain'),
    'remove_featured_image' => __('Remove featured image', 'textdomain'),
    'use_featured_image' => __('Use as featured image', 'textdomain'),
    'insert_into_item' => __('Insert into testimonial', 'textdomain'),
    'uploaded_to_this_item' => __('Uploaded to this testimonial', 'textdomain'),
    'items_list' => __('Testimonials list', 'textdomain'),
    'items_list_navigation' => __('Testimonials list navigation', 'textdomain'),
    'filter_items_list' => __('Filter testimonials list', 'textdomain'),
  );
  $args = array(
    'label' => __('Testimonial', 'textdomain'),
    'description' => __('Post Type Description', 'textdomain'),
    'labels' => $labels,
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'taxonomies' => array('category', 'post_tag'),
    'hierarchical' => false,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'has_archive' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'capability_type' => 'post',
  );
  register_post_type('testimonial', $args);
}
add_action('init', 'create_testimonial_cpt', 0);

// Register Custom Post Type for FAQs
function create_faq_cpt()
{
  $labels = array(
    'name' => _x('FAQs', 'Post Type General Name', 'textdomain'),
    'singular_name' => _x('FAQ', 'Post Type Singular Name', 'textdomain'),
    'menu_name' => _x('FAQs', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('FAQ', 'Add New on Toolbar', 'textdomain'),
    'archives' => __('FAQ Archives', 'textdomain'),
    'attributes' => __('FAQ Attributes', 'textdomain'),
    'parent_item_colon' => __('Parent FAQ:', 'textdomain'),
    'all_items' => __('All FAQs', 'textdomain'),
    'add_new_item' => __('Add New FAQ', 'textdomain'),
    'add_new' => __('Add New', 'textdomain'),
    'new_item' => __('New FAQ', 'textdomain'),
    'edit_item' => __('Edit FAQ', 'textdomain'),
    'update_item' => __('Update FAQ', 'textdomain'),
    'view_item' => __('View FAQ', 'textdomain'),
    'view_items' => __('View FAQs', 'textdomain'),
    'search_items' => __('Search FAQ', 'textdomain'),
    'not_found' => __('Not found', 'textdomain'),
    'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
    'featured_image' => __('Featured Image', 'textdomain'),
    'set_featured_image' => __('Set featured image', 'textdomain'),
    'remove_featured_image' => __('Remove featured image', 'textdomain'),
    'use_featured_image' => __('Use as featured image', 'textdomain'),
    'insert_into_item' => __('Insert into FAQ', 'textdomain'),
    'uploaded_to_this_item' => __('Uploaded to this FAQ', 'textdomain'),
    'items_list' => __('FAQs list', 'textdomain'),
    'items_list_navigation' => __('FAQs list navigation', 'textdomain'),
    'filter_items_list' => __('Filter FAQs list', 'textdomain'),
  );
  $args = array(
    'label' => __('FAQ', 'textdomain'),
    'description' => __('Frequently Asked Questions', 'textdomain'),
    'labels' => $labels,
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'taxonomies' => array('category', 'post_tag'),
    'hierarchical' => false,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'has_archive' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'capability_type' => 'post',
  );
  register_post_type('faq', $args);
}
add_action('init', 'create_faq_cpt', 0);
