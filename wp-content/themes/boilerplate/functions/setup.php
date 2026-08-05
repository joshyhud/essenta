<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

// Enqueue CSS and JS files into theme
function add_theme_scripts()
{
  // Get latest update of Stylesheet
  $style_ver = filemtime(get_stylesheet_directory() . '/dist/css/style.min.css');
  // Get latest update of Scripts
  $script_ver = filemtime(get_stylesheet_directory() . '/dist/js/scripts.min.js');
  // Enqueue style.css with version updating
  wp_enqueue_style('main_style', get_stylesheet_directory_uri() . '/dist/css/style.min.css', array(), $style_ver);
  // Enqueue latest version of jQuery
  wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-latest.min.js', array(), null, true);
  // Enqueue script.js with version updating and dependency on jQuery
  wp_enqueue_script('main-script', get_stylesheet_directory_uri() . '/dist/js/scripts.min.js', array('jquery'), $script_ver);
}
add_action('wp_enqueue_scripts', 'add_theme_scripts');

// Enqueue External Libraries
function add_cdn_libraries()
{
  // Enqueue a CSS file from a CDN
  wp_enqueue_style(
    'accessible-slick-cdn', // Handle name
    'https://cdn.jsdelivr.net/npm/@accessible360/accessible-slick@1.0.1/slick/slick.min.css', // CDN URL
    array(), // Dependencies
  );

  wp_enqueue_style(
    'accessible-slick-theme-cdn', // Handle name
    'https://cdn.jsdelivr.net/npm/@accessible360/accessible-slick@1.0.1/slick/accessible-slick-theme.min.css', // CDN URL
    array(), // Dependencies
  );

  // Enqueue a JavaScript file from a CDN
  wp_enqueue_script(
    'accessible-slick-cdn-js', // Handle name
    'https://cdn.jsdelivr.net/npm/@accessible360/accessible-slick@1.0.1/slick/slick.min.js', // CDN URL
  );
}
add_action('wp_enqueue_scripts', 'add_cdn_libraries');


// Remove WordPress's inline SVG's from the frontend
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

// Create menus, to enable menu support and create menu locations
function register_my_menus()
{
  register_nav_menus(
    array(
      'main-menu' => __('Main Menu', 'main_menu'),
      'footer-menu' => __('Footer Menu', 'footer_menu'),
      'legal-menu' => __('Legal Menu', 'legal_menu'),
      'about-menu' => __('About Menu', 'about_menu'),
      'contact-menu' => __('Contact Menu', 'contact_menu'),
    )
  );
}
add_action('init', 'register_my_menus');

/****************************************************************
Disable Gutenberg functions - 
https://metabox.io/disable-gutenberg-without-using-plugins/
 ****************************************************************/

// Disable Gutenberg for posts and pages
add_filter('use_block_editor_for_post', '__return_false');

// Disable Gutenberg for widgets.
add_filter('use_widgets_block_editor', '__return_false');

add_action('wp_enqueue_scripts', function () {
  // Remove CSS on the front end.
  wp_dequeue_style('wp-block-library');

  // Remove Gutenberg theme.
  wp_dequeue_style('wp-block-library-theme');

  // Remove inline global CSS on the front end.
  wp_dequeue_style('global-styles');

  // Remove classic-themes CSS for backwards compatibility for button blocks.
  wp_dequeue_style('classic-theme-styles');
}, 20);

/****************************************************************
End of disbale Gutenberg functions
 ****************************************************************/


// Add featured image support
add_theme_support('post-thumbnails');

// Add custom logo support
add_theme_support('custom-logo');

// Enable WooCommerce template overrides from the theme's /woocommerce/ folder
add_theme_support('woocommerce');

// Enable SVG uploading support
function add_file_types_to_uploads($file_types)
{
  $new_filetypes = array();
  $new_filetypes['svg'] = 'image/svg+xml';
  $file_types = array_merge($file_types, $new_filetypes);
  return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');

// Add image sizes when uploading
add_image_size('smallest', 200, 150, true);
add_image_size('small', 600, 450, true);
add_image_size('medium', 800, 600, true);
add_image_size('large', 1400, 1150, true);
add_image_size('largest', 1920, 1440, true);

// Enqueue admin styles
function enqueue_admin_styles()
{
  $admin_css_path = get_stylesheet_directory() . '/dist/css/admin.min.css';

  if (file_exists($admin_css_path)) {
    wp_enqueue_style(
      'theme-admin-styles',
      get_stylesheet_directory_uri() . '/dist/css/admin.min.css',
      array(),
      filemtime($admin_css_path)
    );
  }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_styles');
