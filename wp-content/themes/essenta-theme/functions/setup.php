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
  $slick_ver = filemtime(get_stylesheet_directory() . '/dist/js/slick.min.js');
  $sliders_ver = filemtime(get_stylesheet_directory() . '/dist/js/sliders.min.js');

  // Enqueue style.css with version updating
  wp_enqueue_style('main_style', get_stylesheet_directory_uri() . '/dist/css/style.min.css', array(), $style_ver);
  // Enqueue latest version of jQuery
  wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-latest.min.js', array(), null);
  // Enqueue script.js with version updating and dependency on jQuery
  wp_enqueue_script('main-script', get_stylesheet_directory_uri() . '/dist/js/scripts.min.js', array('jquery'), $script_ver, true);
  wp_enqueue_script('main-slick', get_stylesheet_directory_uri() . '/dist/js/slick.min.js', array('jquery'), $slick_ver, true);
  wp_enqueue_script('main-sliders', get_stylesheet_directory_uri() . '/dist/js/sliders.min.js', array('jquery'), $sliders_ver, true);
}
add_action('wp_enqueue_scripts', 'add_theme_scripts');



/// Only enqueue gravity form resources for contact page

function restrict_gravity_forms_to_contact_page()
{
  // If we are in the WordPress admin dashboard, do nothing
  if (is_admin()) {
    return;
  }

  // CRITICAL CHECK: If it is NOT the 'contact' page, strip all assets
  // This checks for the URL slug 'contact'. You can also use the Page ID (e.g., 123)
  if (! is_page('contact')) {

    // 1. Tell Gravity Forms' internal engine not to load styles
    add_filter('gform_disable_css', '__return_true');

    // 2. Aggressively wipe the style handles from WordPress memory
    $styles_to_kill = array(
      'gform_theme_components',
      'gform_theme_framework',
      'gform_orbital_theme',
      'gform_basic',
      'gforms_reset_css',
      'gforms_formsmain_css'
    );

    foreach ($styles_to_kill as $handle) {
      wp_dequeue_style($handle);
      wp_deregister_style($handle);
    }

    // 3. Wipe the core Gravity Forms scripts from memory
    $scripts_to_kill = array(
      'gform_gravityforms',
      'gform_conditional_logic',
      'gform_json'
    );

    foreach ($scripts_to_kill as $script_handle) {
      wp_dequeue_script($script_handle);
      wp_deregister_script($script_handle);
    }
  }
}
// Run at template_redirect so WordPress has determined the current page identity
add_action('template_redirect', 'restrict_gravity_forms_to_contact_page', 1);

// Remove WordPress's inline SVG's from the frontend
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

// Create menus, to enable menu support and create menu locations
function register_my_menus()
{
  register_nav_menus(
    array(
      'main-menu' => __('Main Menu', 'main_menu'),
      'footer-menu' => __('Footer Menu', 'footer_menu')
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

  // Remove classic-themes CSS for backwards compatibility for button blocks.
  wp_dequeue_style('dashicons');
}, 20);

/****************************************************************
End of disbale Gutenberg functions
 ****************************************************************/


// Add featured image support
add_theme_support('post-thumbnails');

// Add custom logo support
add_theme_support('custom-logo');

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


function mytheme_add_woocommerce_support()
{
  add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

// Enable excerpts for posts and pages
add_post_type_support('post', 'excerpt');
add_post_type_support('page', 'excerpt');

// Prefix standard post URLs with /insights/
function essenta_insights_post_link($permalink, $post)
{
  if ($post->post_type !== 'post' || !get_option('permalink_structure') || in_array($post->post_status, array('draft', 'pending', 'auto-draft'), true)) {
    return $permalink;
  }

  return home_url(user_trailingslashit('insights/' . $post->post_name));
}
add_filter('post_link', 'essenta_insights_post_link', 10, 2);

function essenta_insights_rewrite_rules()
{
  add_rewrite_rule('^insights/([^/]+)/?$', 'index.php?name=$matches[1]', 'top');
}
add_action('init', 'essenta_insights_rewrite_rules');

// 301 old /post-slug/ URLs to /insights/post-slug/
function essenta_insights_redirect_old_urls()
{
  if (!is_singular('post') || is_preview()) {
    return;
  }

  $request_path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');

  if (strpos($request_path, 'insights/') !== 0) {
    wp_safe_redirect(get_permalink(), 301);
    exit;
  }
}
add_action('template_redirect', 'essenta_insights_redirect_old_urls');

// Enable classic editor for products
add_post_type_support('product', 'editor');
