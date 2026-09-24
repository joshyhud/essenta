<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$scrolling_text_subheading = get_sub_field('scrolling_text_subheading');
$scrolling_text_heading = get_sub_field('scrolling_text_heading');
$scrolling_text_content = get_sub_field('scrolling_text_content');

?>
<section class="scrolling-text">
    <div class="container">
        <div class="scrolling-text-header">
            <?php if ($scrolling_text_subheading) : ?>
                <p class="scrolling-text-subheading eyebrow"><?php echo esc_html($scrolling_text_subheading); ?></p>
            <?php endif; ?>
            <?php if ($scrolling_text_heading) : ?>
                <h2 class="scrolling-text-heading"><?php echo esc_html($scrolling_text_heading); ?></h2>
            <?php endif; ?>
        </div>
        <?php if ($scrolling_text_content) : ?>
            <div class="scrolling-text-content"><?php echo wp_kses_post($scrolling_text_content); ?></div>
        <?php endif; ?>
    </div>
</section>