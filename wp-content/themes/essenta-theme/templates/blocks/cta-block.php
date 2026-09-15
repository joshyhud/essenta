<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$cta_block_heading = get_sub_field('cta_block_heading');
$cta_block_content = get_sub_field('cta_block_content');
$cta_block_primary_button = get_sub_field('cta_block_primary_link');
$cta_block_secondary_button = get_sub_field('cta_block_secondary_link');
?>

<section class="cta-block">
    <div class="container">
        <div class="cta-block-content">
            <h2><?php echo $cta_block_heading; ?></h2>
            <p><?php echo $cta_block_content; ?></p>
        </div>
        <div class="cta-block-buttons">
            <?php if ($cta_block_primary_button) : ?>
                <a href="<?php echo $cta_block_primary_button['url']; ?>" class="btn primary--light"><?php echo $cta_block_primary_button['title']; ?></a>
            <?php endif; ?>
            <?php if ($cta_block_secondary_button) : ?>
                <a href="<?php echo $cta_block_secondary_button['url']; ?>" class="btn secondary--light"><?php echo $cta_block_secondary_button['title']; ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>