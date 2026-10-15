<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$partner_logos_heading = get_sub_field('partner_logos_heading');
$partner_logos_one = get_field('partner_logos_1', 'option');
$partner_logos_two = get_field('partner_logos_2', 'option');

?>

<section class="partner-logos">
    <div class="container">
        <?php if ($partner_logos_heading) : ?>
            <h2><?php echo $partner_logos_heading; ?></h2>
        <?php endif; ?>
    </div>
    <div class="partner-logos-wrapper">
        <?php if ($partner_logos_one) : ?>
            <div class="partner-logos-slider partner-logos-slider--forward">
                <?php foreach ($partner_logos_one as $partner_logo) : ?>
                    <div class="partner-logo">
                        <img src="<?php echo esc_url($partner_logo['url']); ?>" alt="<?php echo esc_attr($partner_logo['alt']); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($partner_logos_two) : ?>
            <div class="partner-logos-slider partner-logos-slider--reverse">
                <?php foreach ($partner_logos_two as $partner_logo_two) : ?>
                    <div class="partner-logo">
                        <img src="<?php echo esc_url($partner_logo_two['url']); ?>" alt="<?php echo esc_attr($partner_logo_two['alt']); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
