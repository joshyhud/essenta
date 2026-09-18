<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$letterbox_image = get_sub_field('letterbox_image');

?>

<section class="letterbox-image">
    <?php if ($letterbox_image): ?>
        <div class="letterbox-image-wrapper" style="background-image: url('<?php echo esc_url($letterbox_image['url']); ?>');">
        </div>
    <?php endif; ?>
</section>