<?php wp_footer();

$footer_locations = get_field('company_footer_locations', 'option');

?>

<footer>
    <section class="footer-newsletter">
        <div class="container">
            <div class="newsletter-header">
                <p class="eyebrow">Subscribe & save</p>
                <h2><?php echo get_field('subscription_text', 'option'); ?></h2>
            </div>
            <div class="newsletter-form">
                <?php echo do_shortcode('[contact-form-7 id="66f30db" title="Newsletter"]'); ?>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="footer-inner">

            <div class="footer-logos">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img loading="lazy" src="<?php echo wp_get_upload_dir()['baseurl']; ?>/2026/08/Primary_Blue-3-1.svg" alt="<?php bloginfo('name'); ?>">
                </a>
                <div class="footer-contact">
                    <?php echo get_field('footer_contact_form', 'option'); ?>
                </div>
            </div>
            <div class="footer-contact">
                <details open class="footer-details-wrapper">
                    <summary class="footer-header">
                        <p>Contact</p>
                    </summary>
                    <?php foreach ($footer_locations as $location) : ?>
                        <div class="footer-details">
                            <p class="location-title"><?php echo esc_html($location['location_title']); ?></p>
                            <?php if (!empty($location['location_phone'])) : ?>
                                <a class="location-tel  " href="tel:<?php echo esc_attr($location['location_phone']['url']); ?>">
                                    <?php echo esc_html($location['location_phone']['title']); ?>
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($location['location_address'])) : ?>
                                <?php echo wp_kses_post($location['location_address']); ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <div class="mobile-socials">
                        <?php
                        $socials = get_field('socials', 'option');
                        if ($socials) :
                            foreach ($socials as $social) :
                                if (!empty($social['social_url']) && !empty($social['social_icon'])) :
                                    echo '<a href="' . esc_url($social['social_url']) . '">' . wp_kses_post($social['social_icon']) . '</a>';
                                endif;
                            endforeach;
                        endif;
                        ?>
                    </div>
                </details>
            </div>
            <div class="footer-site">
                <details open class="footer-details-wrapper">
                    <summary class="footer-header">
                        <p>Site</p>
                    </summary>
                    <div class="footer-details">
                        <?php wp_nav_menu(array('menu' => 'footer-site')); ?>
                    </div>
                </details>
            </div>
            <div class="footer-socials">
                <details open class="social-wrapper footer-details-wrapper">
                    <summary class="footer-header">
                        <p>Social</p>
                    </summary>
                    <div class="footer-details">
                        <?php
                        $socials = get_field('socials', 'option');
                        if ($socials) :
                            foreach ($socials as $social) :
                                if (!empty($social['social_url']) && !empty($social['social_icon'])) :
                                    echo '<a href="' . esc_url($social['social_url']) . '">' . wp_kses_post($social['social_icon']) . '</a>';
                                endif;
                            endforeach;
                        endif;
                        ?>
                    </div>
                </details>
                <details open class="legal-wrapper footer-details-wrapper">
                    <summary class="footer-header">
                        <p>Legal</p>
                    </summary>
                    <div class="footer-details">
                        <?php wp_nav_menu(array('menu' => 'footer-legal')); ?>
                    </div>
                </details>
            </div>

        </div>

        <div class="footer-copyright">
            <p>&copy; <?php echo date("Y"); ?> <?php echo get_field('company_name', 'option'); ?>. All rights reserved.</p>
        </div>

    </div>

</footer>
</body>

</html>