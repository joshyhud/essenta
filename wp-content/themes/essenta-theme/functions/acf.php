<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * Filters for saving and loading ACF JSON files into specific subfolders.
 * See: https://www.advancedcustomfields.com/resources/local-json/
 */

// Save JSON for custom post types
function my_acf_save_post_type( $path ) {
    $path = get_stylesheet_directory() . '/includes/acf-json/post-types';
    return $path;
}
add_filter( 'acf/settings/save_json/type=acf-post-type', 'my_acf_save_post_type' );

// Save JSON for field groups
function my_acf_save_field_group( $path ) {
    $path = get_stylesheet_directory() . '/includes/acf-json/field-groups';
    return $path;
}
add_filter( 'acf/settings/save_json/type=acf-field-group', 'my_acf_save_field_group' );

// Save JSON for taxonomies
function my_acf_save_taxonomy( $path ) {
    $path = get_stylesheet_directory() . '/includes/acf-json/taxonomies';
    return $path;
}
add_filter( 'acf/settings/save_json/type=acf-taxonomy', 'my_acf_save_taxonomy' );

// Save JSON for options pages
function my_acf_save_options_pages( $path ) {
    $path = get_stylesheet_directory() . '/includes/acf-json/options-pages';
    return $path;
}
add_filter( 'acf/settings/save_json/type=acf-ui-options-page', 'my_acf_save_options_pages' );

/**
 * Filter for loading ACF JSON from multiple subfolders.
 */
function my_acf_json_load_point( $path ) {
	// Remove the original path (optional).
    unset($path[0]);
    $theme_dir = get_stylesheet_directory();

    // Add custom paths for ACF assets.
    $path[] = $theme_dir . '/includes/acf-json/field-groups';
    $path[] = $theme_dir . '/includes/acf-json/post-types';
    $path[] = $theme_dir . '/includes/acf-json/taxonomies';
    $path[] = $theme_dir . '/includes/acf-json/options-pages';

    return $path;
}
add_filter( 'acf/settings/load_json', 'my_acf_json_load_point' );

/**
 * Filter to prevent fields with 'admin_only' setting from displaying to non-admins.
 */
function admin_only_prepare_field( $field ) {
    // Bail early if no 'admin_only' setting or if set to false.
    if ( empty( $field['admin_only'] ) ) {
        return $field;
    }

    // Prevent field from displaying if current user is not an admin.
    if ( ! current_user_can( 'manage_options' ) ) {
        return false;
    }

    // Return the original field otherwise.
    return $field;
}
add_filter( 'acf/prepare_field', 'admin_only_prepare_field' );


/**
 * Filter to add 'Admin Only' setting to ACF field groups.
 */
add_filter( 'acf/field_group/additional_field_settings_tabs', function ( $tabs ) {
    $tabs['admin-settings'] = 'Admin Settings';

    return $tabs;
} );


/**
 * Filter to render 'Admin Only' setting in ACF field group settings.
 */
add_action( 'acf/field_group/render_field_settings_tab/admin-settings', function ( $field ) {
    acf_render_field_setting(
        $field,
        array(
            'label'        => 'Admin Only?',
            'instructions' => 'Visible to Admins only.',
            'name'         => 'admin_only',
            'type'         => 'true_false',
            'ui'           => 1,
        ),
        true
    );
} );

/**
 * B9 Custom ACF Fields
 */

// Register a custom field type for a visual separator.
if( function_exists('acf_register_field_type') ) :

    class acf_field_separator extends acf_field {

        /**
         * Constructor.
         */
        public function __construct() {
            // Field name (will be used in the code)
            $this->name = 'separator';
            // Field label (displayed in the admin)
            $this->label = __('Separator', 'acf');
            // Category in the field type selection (you can choose 'layout' or another category)
            $this->category = 'Custom';
            // Default settings (none are needed in this simple case)
            $this->defaults = array();
            // Call parent constructor
            parent::__construct();
        }

        /**
         * Render the field in the edit screen.
         * Since this field is only a visual separator, we only output an <hr>.
         *
         * @param array $field The field settings and values.
         */
        public function render_field( $field ) {
            echo '<hr />';
        }

        /**
         * (Optional) Render field settings in the field group editor.
         * For this simple separator field, we don't need any additional settings.
         *
         * @param array $field The field settings.
         */
        public function render_field_settings( $field ) {
            // No additional settings
        }

        /**
         * (Optional) Load value.
         * Not needed because this field doesn’t store any value.
         */
        public function load_value( $value, $post_id, $field ) {
            return $value;
        }

        /**
         * (Optional) Update value.
         * Not needed because this field doesn’t store any value.
         */
        public function update_value( $value, $post_id, $field ) {
            return $value;
        }
    }

    // Register the field after ACF has initialized its translations.
    add_action( 'acf/init', function () {
        acf_register_field_type( 'acf_field_separator' );
    } );

endif;