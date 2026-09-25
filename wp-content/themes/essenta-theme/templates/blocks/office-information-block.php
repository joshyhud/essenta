<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$office_information_heading = get_sub_field('office_info_heading');
$office_information_subheading = get_sub_field('office_info_subheading');

$essenta_offices = new WP_Query(array(
    'post_type' => 'office',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'office_or_delivery_partner',
            'value' => 'office',
        ),
    ),
));

?>

<section class="office-information-block">
    <div class="container">
        <div class="office-information-block__intro">
            <?php if ($office_information_subheading) : ?>
                <p class="office-information-block__subheading eyebrow"><?php echo esc_html($office_information_subheading); ?></p>
            <?php endif; ?>
            <?php if ($office_information_heading) : ?>
                <h2 class="office-information-block__heading"><?php echo esc_html($office_information_heading); ?></h2>
            <?php endif; ?>
        </div>

        <?php if ($essenta_offices->have_posts()) : ?>
            <div class="office-information-block__offices">
                <?php while ($essenta_offices->have_posts()) : $essenta_offices->the_post();
                    $office_id = get_the_ID();
                    $office_name = get_the_title();
                    $office_content = get_field('office_content');
                    $office_location = get_field('office_location');
                    $office_address = is_array($office_location) ? ($office_location['address'] ?? '') : '';
                    $office_phone = get_field('office_phone');
                    $office_email = get_field('office_email');
                    $contact_page_url = get_permalink(get_page_by_path('contact'));
                ?>
                    <article class="office-information-block__office">
                        <?php if (has_post_thumbnail($office_id)) : ?>
                            <div class="office-information-block__office-image">
                                <?php echo get_the_post_thumbnail($office_id, 'large', array('loading' => 'lazy')); ?>
                            </div>
                        <?php endif; ?>
                        <div class="office-information-block__office-details">
                            <p class="office-information-block__office-label eyebrow">Office details</p>
                            <?php if ($office_name) : ?>
                                <h3 class="office-information-block__office-name"><?php echo esc_html($office_name); ?></h3>
                            <?php endif; ?>
                            <?php if ($office_content) : ?>
                                <div class="office-information-block__office-content">
                                    <?php echo wp_kses_post($office_content); ?>
                                </div>
                            <?php endif; ?>
                            <div class="office-information-block__office-contact">
                                <?php if ($office_address) : ?>
                                    <address>
                                        <a class="office-information-block__office-address" href="<?php echo esc_url('https://www.google.com/maps/search/?api=1&query=' . rawurlencode($office_address)); ?>" target="_blank" rel="noopener noreferrer">
                                            <span class="office-information-block__contact-icon" aria-hidden="true"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/pin.svg'); ?>" alt=""></span>
                                            <span><?php echo esc_html($office_address); ?></span>
                                        </a>
                                    </address>
                                <?php endif; ?>
                                <?php if ($office_phone) : ?>
                                    <a class="office-information-block__office-phone" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $office_phone)); ?>">
                                        <span class="office-information-block__contact-icon" aria-hidden="true"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/phone-dark.svg'); ?>" alt=""></span>
                                        <span><?php echo esc_html($office_phone); ?></span>
                                    </a>
                                <?php endif; ?>
                                <?php if ($office_email) : ?>
                                    <a class="office-information-block__office-email" href="mailto:<?php echo esc_attr(antispambot($office_email)); ?>">
                                        <span class="office-information-block__contact-icon" aria-hidden="true"><img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/dist/images/mail-dark.svg'); ?>" alt=""></span>
                                        <span><?php echo esc_html(antispambot($office_email)); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php if ($contact_page_url) : ?>
                                <a href="<?php echo esc_url($contact_page_url); ?>" class="office-information-block__cta btn primary">Talk to an expert</a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>