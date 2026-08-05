<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();

// check if the flexible content field has rows of data
if ($post_id && have_rows('header_pagebuilder', $post_id)) {
    // loop through the rows of data
    while (have_rows('header_pagebuilder', $post_id)) {
        the_row();
        $layout = get_row_layout();
        $template_path = 'templates/headers/' . str_replace('_', '-', $layout);
        get_template_part($template_path);
    }
} else {
    // no layouts found
}
