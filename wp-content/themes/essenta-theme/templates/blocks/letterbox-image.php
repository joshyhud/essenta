<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$letterbox_image = get_sub_field('letterbox_image');
$letterbox_src = $letterbox_image['url'] ?? '';
$letterbox_srcset = '';
$letterbox_image_id = absint($letterbox_image['ID'] ?? 0);

if ($letterbox_image_id) {
    $webp_sources = essenta_generate_letterbox_webp_sources($letterbox_image_id);

    if ($webp_sources) {
        $letterbox_src = $webp_sources['desktop']['url'];
        $letterbox_srcset = sprintf(
            '%s %dw, %s %dw',
            $webp_sources['mobile']['url'],
            $webp_sources['mobile']['width'],
            $webp_sources['desktop']['url'],
            $webp_sources['desktop']['width']
        );
    } else {
        $letterbox_srcset = wp_get_attachment_image_srcset($letterbox_image_id, 'full') ?: '';
    }
}

if (!$letterbox_srcset && !empty($letterbox_image['width'])) {
    $letterbox_srcset = esc_url($letterbox_src) . ' ' . absint($letterbox_image['width']) . 'w';
}

?>

<section class="letterbox-image">
    <?php if ($letterbox_image && $letterbox_src): ?>
        <div class="letterbox-image-wrapper">
            <img
                src="<?php echo esc_url($letterbox_src); ?>"
                <?php if ($letterbox_srcset) : ?>srcset="<?php echo esc_attr($letterbox_srcset); ?>"<?php endif; ?>
                sizes="100vw"
                width="1920"
                height="470"
                alt="<?php echo esc_attr($letterbox_image['alt'] ?? ''); ?>"
                fetchpriority="high"
                decoding="async">
        </div>
    <?php endif; ?>
</section>