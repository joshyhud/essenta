<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
$partner_logos_heading = get_sub_field('partner_logos_heading');
$partner_logos_one = get_sub_field('partner_logos_carousel_one');
$partner_logos_two = get_sub_field('partner_logos_carousel_two');
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
                        <?php if ($partner_logo['partner_link']) : ?>
                            <a href="<?php echo esc_url($partner_logo['partner_link']); ?>">
                                <img src="<?php echo esc_url($partner_logo['partner_logo_image']['url']); ?>" alt="<?php echo esc_attr($partner_logo['partner_logo_image']['alt']); ?>">
                            </a>
                        <?php else : ?>
                            <img src="<?php echo esc_url($partner_logo['partner_logo_image']['url']); ?>" alt="<?php echo esc_attr($partner_logo['partner_logo_image']['alt']); ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($partner_logos_two) : ?>
            <div class="partner-logos-slider partner-logos-slider--reverse">
                <?php foreach ($partner_logos_two as $partner_logo_two) : ?>
                    <div class="partner-logo">
                        <?php if ($partner_logo_two['partner_link']) : ?>
                            <a href="<?php echo esc_url($partner_logo_two['partner_link']); ?>">
                                <img src="<?php echo esc_url($partner_logo_two['partner_logo_image']['url']); ?>" alt="<?php echo esc_attr($partner_logo_two['partner_logo_image']['alt']); ?>">
                            </a>
                        <?php else : ?>
                            <img src="<?php echo esc_url($partner_logo_two['partner_logo_image']['url']); ?>" alt="<?php echo esc_attr($partner_logo_two['partner_logo_image']['alt']); ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>


<script>
    jQuery(document).ready(function($) {
        $('.partner-logos .partner-logos-slider--forward').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 0,
            speed: 6000,
            cssEase: 'linear',
            pauseOnHover: false,
            pauseOnFocus: false,
            variableWidth: true,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 3
                }
            }]
        });

        // rtl + variableWidth breaks slick's track rendering, so mirror direction with CSS instead
        $('.partner-logos .partner-logos-slider--reverse').slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 0,
            speed: 6000,
            cssEase: 'linear',
            pauseOnHover: false,
            pauseOnFocus: false,
            variableWidth: true,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 3
                }
            }]
        });
    });
</script>