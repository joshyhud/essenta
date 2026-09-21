<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$video = get_sub_field('video_select');

?>

<section class="video-module">
    <?php if ($video): ?>
        <div class="video-wrapper container">
            <video controls>
                <source src="<?php echo esc_url($video['url']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    <?php endif; ?>
</section>