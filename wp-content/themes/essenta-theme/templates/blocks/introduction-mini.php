<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$intro_mini_subheading = get_sub_field('intro_mini_subheading');
$intro_mini_heading = get_sub_field('intro_mini_heading');
$intro_mini_text = get_sub_field('intro_mini_content');

?>
<section class="introduction-mini">
    <div class="container">
        <div class="introduction-mini__header">
            <?php if ($intro_mini_subheading) : ?>
                <p class="introduction-mini__subheading eyebrow"><?php echo esc_html($intro_mini_subheading); ?></p>
            <?php endif; ?>
            <?php if ($intro_mini_heading) : ?>
                <p class="introduction-mini__heading body-large "><?php echo esc_html($intro_mini_heading); ?></p>
            <?php endif; ?>
        </div>
        <div class="introduction-mini__content">
            <?php if ($intro_mini_text) : ?>
                <div class="introduction-mini__text"><?php echo wp_kses_post($intro_mini_text); ?></div>
            <?php endif; ?>
        </div>
    </div>
</section>