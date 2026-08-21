<?php $woocommerce_available = function_exists('wc_get_cart_url') && function_exists('WC') && WC()->cart; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>

    <!-- Larken AdobeFont -->
    <link rel="stylesheet" href="https://use.typekit.net/kxn1npi.css">

    <!-- Plus Jakarta Sans google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">


</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div class="site-wrapper">

        <?php 
        $announcement = get_field('announcement', 'option');
        
        if ($announcement) : ?>
            <div class="announcement-bar">
                    <p><?php echo esc_html($announcement); ?></p>
            </div>
        <?php endif; ?>

        <header class="site-header">
            <div class="site-header-inner">
                <div class="site-brand col-2">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <?php if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                            echo '<h1>' . get_bloginfo('name') . '</h1>';
                        } ?>
                    </a>
                </div>
                
               
                <!-- Mobile search form is commented out for now, but can be enabled if needed. --> 
                <!--
                <div class="header-search mobile">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="search-input-wrapper">
                            <i class="search-icon"></i>
                            <input type="search" class="search-field" placeholder="Search..." value="<?php echo get_search_query(); ?>" name="s" />

                        </div>
                    </form>
                </div>
                -->

            <div class="site-nav">
                <?php wp_nav_menu(array('theme_location' => 'main-menu')); ?>
            </div>


                <div class="header-ctas col-2">

                    <!-- Desktop search form -->
                    <!--
                    <div class="header-search">
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="search-input-wrapper">
                                <i class="search-icon"></i>
                                <input type="search" class="search-field" placeholder="Search..." value="<?php echo get_search_query(); ?>" name="s" />

                            </div>
                        </form>
                    </div>
                    -->
                    <div class="header-icons">

                        <a href="/contact-us/" class="btn primary">Contact Us</a>

                        <div class="site-nav mobile">
                            <button class="mm-toggle" aria-controls="mm-drawer" aria-expanded="false" type="button"></button>

                            <div class="mm-overlay" hidden></div>

                            <nav id="mm-drawer" class="mm-drawer" aria-hidden="true">
                                <div class="mm-topbar">
                                    <div class="mm-title">Menu</div>
                                    <button class="mm-close" type="button" aria-label="Close menu">✕</button>
                                </div>

                                <div class="mm-panels">
                                    <div class="mm-panel mm-panel--active" data-panel="root">
                                        <?php wp_nav_menu([
                                            'theme_location' => 'max_mega_menu_3',
                                            'container'      => false,
                                            'menu_class'     => 'mm-menu',
                                            'depth'          => 4,
                                            'fallback_cb'    => false,
                                        ]); ?>
                                    </div>
                                </div>

                                <div class="mm-footer">
                                    <div class="mm-contact-info">
                                        <div class="mm-email-phone">
                                            <a href="mailto:<?php echo esc_attr(get_field('email_enquiries', 'option')); ?>" class="mm-email">
                                                <?php echo esc_html(get_field('email_enquiries', 'option')); ?>
                                            </a>
                                            <a href="tel:<?php echo esc_attr(get_field('company_phone', 'option')); ?>" class="mm-phone">
                                                <?php echo esc_html(get_field('company_phone', 'option')); ?>
                                            </a>

                                        </div>
                                        <div class="mm-socials">
                                            <?php $social_links = get_field('social_media', 'option');
                                            if ($social_links) : ?>
                                                <?php foreach ($social_links as $link) : ?>
                                                    <a href="<?php echo esc_url($link['social_url']); ?>" target="_blank" rel="noopener noreferrer" class="mm-social-link">
                                                        <span class="screen-reader-text"><?php echo esc_html($link['social_name']); ?></span>
                                                        <i class="icon-<?php echo strtolower(esc_attr($link['social_name'])); ?>"></i>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>

                            </nav>
                        </div>
                    </div>
                </div>
            </div>

        </header>
        <?php if (!is_front_page()) : ?>
            <div class="breadcrumbs">
                <?php if (function_exists('yoast_breadcrumb')) {
                    yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
                } ?>
            </div>
        <?php endif; ?>