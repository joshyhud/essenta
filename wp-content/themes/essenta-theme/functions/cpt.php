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
    'taxonomies' => array('department', 'location'),
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

// Register Location taxonomy for Team Members
function create_team_location_taxonomy()
{
  $labels = array(
    'name' => _x('Team Locations', 'taxonomy general name', 'textdomain'),
    'singular_name' => _x('Team Location', 'taxonomy singular name', 'textdomain'),
    'search_items' => __('Search Team Locations', 'textdomain'),
    'all_items' => __('All Team Locations', 'textdomain'),
    'parent_item' => __('Parent Team Location', 'textdomain'),
    'parent_item_colon' => __('Parent Team Location:', 'textdomain'),
    'edit_item' => __('Edit Team Location', 'textdomain'),
    'update_item' => __('Update Team Location', 'textdomain'),
    'add_new_item' => __('Add New Team Location', 'textdomain'),
    'new_item_name' => __('New Team Location Name', 'textdomain'),
    'menu_name' => __('Team Locations', 'textdomain'),
  );

  $args = array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'location'),
  );

  register_taxonomy('team_location', array('team_member'), $args);
}
add_action('init', 'create_team_location_taxonomy', 0);


// Office Location CPT

function create_office_cpt()
{
  $labels = array(
    'name' => _x('Offices', 'Post Type General Name', 'textdomain'),
    'singular_name' => _x('Office', 'Post Type Singular Name', 'textdomain'),
    'menu_name' => _x('Offices', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Office', 'Add New on Toolbar', 'textdomain'),
    'archives' => __('Office Archives', 'textdomain'),
    'attributes' => __('Office Attributes', 'textdomain'),
    'parent_item_colon' => __('Parent Office:', 'textdomain'),
    'all_items' => __('All Offices', 'textdomain'),
    'add_new_item' => __('Add New Office', 'textdomain'),
    'add_new' => __('Add New', 'textdomain'),
    'new_item' => __('New Office', 'textdomain'),
    'edit_item' => __('Edit Office', 'textdomain'),
    'update_item' => __('Update Office', 'textdomain'),
    'view_item' => __('View Office', 'textdomain'),
    'view_items' => __('View Offices', 'textdomain'),
    'search_items' => __('Search Office', 'textdomain'),
    'not_found' => __('Not found', 'textdomain'),
    'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
    'featured_image' => __('Featured Image', 'textdomain'),
    'set_featured_image' => __('Set featured image', 'textdomain'),
    'remove_featured_image' => __('Remove featured image', 'textdomain'),
    'use_featured_image' => __('Use as featured image', 'textdomain'),
    'insert_into_item' => __('Insert into office', 'textdomain'),
    'uploaded_to_this_item' => __('Uploaded to this office', 'textdomain'),
    'items_list' => __('Offices list', 'textdomain'),
    'items_list_navigation' => __('Offices list navigation', 'textdomain'),
    'filter_items_list' => __('Filter offices list', 'textdomain'),
  );
  $args = array(
    'label' => __('Office', 'textdomain'),
    'description' => __('Post Type Description', 'textdomain'),
    'labels' => $labels,
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'taxonomies' => array('department'),
    'hierarchical' => false,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 6,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'has_archive' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'capability_type' => 'post',
  );
  register_post_type('office', $args);
}
add_action('init', 'create_office_cpt', 0);


// Services CPT
function create_service_cpt()
{
  $labels = array(
    'name' => _x('Services', 'Post Type General Name', 'textdomain'),
    'singular_name' => _x('Service', 'Post Type Singular Name', 'textdomain'),
    'menu_name' => _x('Services', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Service', 'Add New on Toolbar', 'textdomain'),
    'all_items' => __('All Services', 'textdomain'),
    'add_new_item' => __('Add New Service', 'textdomain'),
    'add_new' => __('Add New', 'textdomain'),
    'new_item' => __('New Service', 'textdomain'),
    'edit_item' => __('Edit Service', 'textdomain'),
    'view_item' => __('View Service', 'textdomain'),
    'search_items' => __('Search Services', 'textdomain'),
    'not_found' => __('No services found', 'textdomain'),
    'not_found_in_trash' => __('No services found in Trash', 'textdomain'),
  );

  $args = array(
    'label' => __('Service', 'textdomain'),
    'labels' => $labels,
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'taxonomies' => array('service_type'),
    'hierarchical' => false,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 7,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'services'),
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'show_in_rest' => true,
    'capability_type' => 'post',
  );

  register_post_type('service', $args);
}
add_action('init', 'create_service_cpt', 0);

// Service Type taxonomy
function create_service_type_taxonomy()
{
  $labels = array(
    'name' => _x('Service Types', 'taxonomy general name', 'textdomain'),
    'singular_name' => _x('Service Type', 'taxonomy singular name', 'textdomain'),
    'search_items' => __('Search Service Types', 'textdomain'),
    'all_items' => __('All Service Types', 'textdomain'),
    'parent_item' => __('Parent Service Type', 'textdomain'),
    'parent_item_colon' => __('Parent Service Type:', 'textdomain'),
    'edit_item' => __('Edit Service Type', 'textdomain'),
    'update_item' => __('Update Service Type', 'textdomain'),
    'add_new_item' => __('Add New Service Type', 'textdomain'),
    'new_item_name' => __('New Service Type Name', 'textdomain'),
    'menu_name' => __('Service Types', 'textdomain'),
  );

  $args = array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_rest' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'service-type'),
  );

  register_taxonomy('service_type', array('service'), $args);
}
add_action('init', 'create_service_type_taxonomy', 0);


// Case Study CPT
function create_case_study_cpt()
{
  $labels = array(
    'name' => _x('Case Studies', 'Post Type General Name', 'textdomain'),
    'singular_name' => _x('Case Study', 'Post Type Singular Name', 'textdomain'),
    'menu_name' => _x('Case Studies', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Case Study', 'Add New on Toolbar', 'textdomain'),
    'all_items' => __('All Case Studies', 'textdomain'),
    'add_new_item' => __('Add New Case Study', 'textdomain'),
    'add_new' => __('Add New', 'textdomain'),
    'new_item' => __('New Case Study', 'textdomain'),
    'edit_item' => __('Edit Case Study', 'textdomain'),
    'view_item' => __('View Case Study', 'textdomain'),
    'search_items' => __('Search Case Studies', 'textdomain'),
    'not_found' => __('Not found', 'textdomain'),
    'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
  );

  $args = array(
    'label' => __('Case Study', 'textdomain'),
    'labels' => $labels,
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'taxonomies' => array(
      'case_study_client',
      'case_study_sector',
      'case_study_service',
      'case_study_role',
      'case_study_region',
    ),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 4,
    'show_in_nav_menus' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'case-studies'),
    'show_in_rest' => true,
  );

  register_post_type('case_study', $args);
}
add_action('init', 'create_case_study_cpt', 0);

// Case Study taxonomies
function create_case_study_taxonomies()
{
  $taxonomies = array(
    'case_study_client' => array('Client', 'Clients'),
    'case_study_sector' => array('Sector', 'Sectors'),
    'case_study_service' => array('Service', 'Services'),
    'case_study_role' => array('Role', 'Roles'),
    'case_study_region' => array('Region', 'Regions'),
  );

  foreach ($taxonomies as $taxonomy => $names) {
    register_taxonomy($taxonomy, array('case_study'), array(
      'labels' => array(
        'name' => __($names[1], 'textdomain'),
        'singular_name' => __($names[0], 'textdomain'),
        'search_items' => __('Search ' . $names[1], 'textdomain'),
        'all_items' => __('All ' . $names[1], 'textdomain'),
        'edit_item' => __('Edit ' . $names[0], 'textdomain'),
        'update_item' => __('Update ' . $names[0], 'textdomain'),
        'add_new_item' => __('Add New ' . $names[0], 'textdomain'),
        'new_item_name' => __('New ' . $names[0] . ' Name', 'textdomain'),
        'menu_name' => __($names[1], 'textdomain'),
      ),
      'hierarchical' => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'query_var' => true,
      'rewrite' => array('slug' => str_replace('_', '-', $taxonomy)),
    ));
  }
}
add_action('init', 'create_case_study_taxonomies', 0);
