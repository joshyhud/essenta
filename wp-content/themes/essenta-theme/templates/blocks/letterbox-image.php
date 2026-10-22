<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$letterbox_image = get_sub_field('letterbox_image');

?>

<section class="letterbox-image">
    <?php if ($letterbox_image): ?>
        <div class="letterbox-image-wrapper">
            <div class="letterbox-image-overlay"></div>
            <img loading="lazy" src="<?php echo esc_url($letterbox_image['url']); ?>" alt="<?php echo esc_attr($letterbox_image['alt']); ?>">
        </div>
    <?php endif; ?>
</section>