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


// Team member CPT
function create_team_member_cpt()
{
  $labels = array(
    'name' => _x('Team Members', 'Post Type General Name', 'textdomain'),
    'singular_name' => _x('Team Member', 'Post Type Singular Name', 'textdomain'),
    'menu_name' => _x('Team Members', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Team Member', 'Add New on Toolbar', 'textdomain'),
    'archives' => __('Team Member Archives', 'textdomain'),
    'attributes' => __('Team Member Attributes', 'textdomain'),
    'parent_item_colon' => __('Parent Team Member:', 'textdomain'),
    'all_items' => __('All Team Members', 'textdomain'),
    'add_new_item' => __('Add New Team Member', 'textdomain'),
    'add_new' => __('Add New', 'textdomain'),
    'new_item' => __('New Team Member', 'textdomain'),
    'edit_item' => __('Edit Team Member', 'textdomain'),
    'update_item' => __('Update Team Member', 'textdomain'),
    'view_item' => __('View Team Member', 'textdomain'),
    'view_items' => __('View Team Members', 'textdomain'),
    'search_items' => __('Search Team Member', 'textdomain'),
    'not_found' => __('Not found', 'textdomain'),
    'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
    'featured_image' => __('Featured Image', 'textdomain'),
    'set_featured_image' => __('Set featured image', 'textdomain'),
    'remove_featured_image' => __('Remove featured image', 'textdomain'),
    'use_featured_image' => __('Use as featured image', 'textdomain'),
    'insert_into_item' => __('Insert into team member', 'textdomain'),
    'uploaded_to_this_item' => __('Uploaded to this team member', 'textdomain'),
    'items_list' => __('Team Members list', 'textdomain'),
    'items_list_navigation' => __('Team Members list navigation', 'textdomain'),
    'filter_items_list' => __('Filter team members list', 'textdomain'),
  );
  $args = array(
    'label' => __('Team Member', 'textdomain'),
    'description' => __('Post Type Description', 'textdomain'),
    'labels' => $labels,
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'taxonomies' => array('department'),
    'hierarchical' => false,
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 6,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => false,
    'can_export' => true,
    'has_archive' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'capability_type' => 'post',
  );
  register_post_type('team_member', $args);
}
add_action('init', 'create_team_member_cpt', 0);

// Register Department taxonomy for Team Members
function create_department_taxonomy()
{
  $labels = array(
    'name' => _x('Departments', 'taxonomy general name', 'textdomain'),
    'singular_name' => _x('Department', 'taxonomy singular name', 'textdomain'),
    'search_items' => __('Search Departments', 'textdomain'),
    'all_items' => __('All Departments', 'textdomain'),
    'parent_item' => __('Parent Department', 'textdomain'),
    'parent_item_colon' => __('Parent Department:', 'textdomain'),
    'edit_item' => __('Edit Department', 'textdomain'),
    'update_item' => __('Update Department', 'textdomain'),
    'add_new_item' => __('Add New Department', 'textdomain'),
    'new_item_name' => __('New Department Name', 'textdomain'),
    'menu_name' => __('Departments', 'textdomain'),
  );

  $args = array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'department'),
  );

  register_taxonomy('department', array('team_member'), $args);
}
add_action('init', 'create_department_taxonomy', 0);
