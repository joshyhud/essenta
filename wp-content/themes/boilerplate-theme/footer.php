<?php wp_footer(); ?>

<footer>
    <section class="footer-newsletter">
        <div class="container">
            <div class="newsletter-header">
                <p class="subheading">Subscribe & save</p>
                <h2><?php echo get_field('subscription_text', 'option'); ?></h2>
            </div>
            <div class="newsletter-form">
                <?php echo do_shortcode('[contact-form-7 id="66f30db" title="Newsletter"]'); ?>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="footer-inner">

            <div class="footer-location">
                <details open class="footer-details-wrapper">
                    <summary class="footer-header">
                        <p>Our location</p>
                    </summary>
                    <div class="footer-details">
                        <?php echo get_field('company_address', 'option'); ?>
                    </div>
                </details>
            </div>
            <div class="footer-contact">
                <details open class="footer-details-wrapper">
                    <summary class="footer-header">
                        <p>Contact Us</p>
                    </summary>
                    <div class="footer-details">
                        <div class="footer-contact-detail">
                            <p>Telephone:</p>
                            <?php echo get_field('company_phone', 'option'); ?>
                        </div>
                        <div class="footer-contact-detail">
                            <p>Email:</p>
                            <?php echo get_field('email_enquiries', 'option'); ?>
                        </div>
                    </div>
                </details>
            </div>
            <div class="footer-privacy">
                <details open class="footer-details-wrapper">
                    <summary class="footer-header">
                        <p>Policies & Information</p>
                    </summary>
                    <div class="footer-details">
                        <?php wp_nav_menu(array('theme_location' => 'max_mega_menu_1')); ?>
                    </div>
                </details>
            </div>
            <div class="footer-contact">
                <details open class="footer-details-wrapper">
                    <summary class="footer-header">
                        <p>Key Links</p>
                    </summary>
                    <div class="footer-details">
                        <?php wp_nav_menu(array('theme_location' => 'max_mega_menu_2')); ?>
                    </div>
                </details>
            </div>

        </div>
        <div class="footer-logos">
            <div class="accreditation-logo">

                <?php $accreditation = get_field('accreditation_logo', 'option');
                $accreditationLink = get_field('footer_logo_link', 'option');
                ?>
                <a href="<?php echo esc_url($accreditationLink); ?>" target="_blank" rel="noopener noreferrer">
                    <img loading="lazy" src="<?php echo $accreditation['url']; ?>" alt="<?php echo $accreditation['alt']; ?>">
                </a>
            </div>
            <div class="accreditation-payments">

                <?php if (class_exists('WooCommerce')) : ?>
                    <div class="payment-methods">
                        <div class="payment-icons">
                            <?php echo do_shortcode('[accepted_payments_stripe_paypal]'); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <div class="footer-socials">
                <?php $social_links = get_field('social_media', 'option');
                if ($social_links) : ?>
                    <?php foreach ($social_links as $link) : ?>

                        <div class="social-link-item">
                            <a href="<?php echo esc_url($link['social_url']); ?>" target="_blank" rel="noopener noreferrer">
                                <span class="screen-reader-text"><?php echo esc_html($link['social_name']); ?></span>
                                <i class="icon-<?php echo strtolower(esc_attr($link['social_name'])); ?>"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <p>Copyright &copy; <?php echo date("Y"); ?> Your Company Name. All rights reserved.</p>
        <p>Site by: <a href="https://example.com" target="_blank">Your Company</a></p>
    </div>
</footer>
</body>

</html>