<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$header_layout = get_sub_field('header_layout_select');

$media_image = get_sub_field('header_image');

$media_sub_header = get_sub_field('header_subheading');
$media_heading = get_sub_field('header_heading');
$media_heading_intro = get_sub_field('header_intro');

$media_heading_intro_text = get_sub_field('header_intro_text');

$media_cta = get_sub_field('header_primary_cta');
$media_cta_secondary = get_sub_field('header_secondary_cta');


?>

<section class="media-text-header">
    <div class="container">
        <?php if ($header_layout === 'media'): ?>
            <div class="media-text-left">
                <p class="eyebrow"><?php echo esc_html($media_sub_header); ?></p>
                <h1><?php echo esc_html($media_heading); ?></h1>
                <?php if (!empty($media_heading_intro)) : ?>
                    <p><?php echo wp_kses_post($media_heading_intro); ?></p>
                <?php endif; ?>
                <div class="media-header-ctas">
                    <?php if ($media_cta) { ?>
                        <a href="<?php echo esc_url($media_cta['url']); ?>" class="btn primary"><?php echo esc_html($media_cta['title']); ?></a>
                    <?php } ?>
                    <?php if ($media_cta_secondary) { ?>
                        <a href="<?php echo esc_url($media_cta_secondary['url']); ?>" class="btn secondary"><?php echo esc_html($media_cta_secondary['title']); ?></a>
                    <?php } ?>
                </div>
            </div>
            <div class="media-text-right image">
                <?php if (!empty($media_image)): ?>
                    <img loading="lazy" src="<?php echo esc_url($media_image['url']); ?>" alt="<?php echo esc_attr($media_image['alt']); ?>" />
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="media-text-left">
                <p class="eyebrow"><?php echo esc_html($media_sub_header); ?></p>
                <h1><?php echo esc_html($media_heading); ?></h1>
            </div>
            <div class="media-text-right content">
                <?php if ($media_heading_intro_text) : ?>
                    <?php echo wp_kses_post($media_heading_intro_text); ?>
                <?php endif; ?>
                <div class="media-header-ctas">
                    <?php if ($media_cta) { ?>
                        <a href="<?php echo esc_url($media_cta['url']); ?>" class="btn primary"><?php echo esc_html($media_cta['title']); ?></a>
                    <?php } ?>
                    <?php if ($media_cta_secondary) { ?>
                        <a href="<?php echo esc_url($media_cta_secondary['url']); ?>" class="btn secondary"><?php echo esc_html($media_cta_secondary['title']); ?></a>
                    <?php } ?>
                </div>

            </div>
        <?php endif; ?>
</section>