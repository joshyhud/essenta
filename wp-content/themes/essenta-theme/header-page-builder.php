<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// check if the flexible content field has rows of data
if (have_rows('header_pagebuilder')) {
    // loop through the rows of data
    while (have_rows('header_pagebuilder')) {
        the_row();
        $layout = get_row_layout();
        $template_path = 'templates/headers/' . str_replace('_', '-', $layout);
        get_template_part($template_path);
    }
} else {
    // no layouts found
}
