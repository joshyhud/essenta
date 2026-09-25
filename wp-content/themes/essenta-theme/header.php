<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>

    <!-- Adelle AdobeFont -->
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
                    <div class="site-nav">
                        <?php wp_nav_menu(array('theme_location' => 'main-menu')); ?>
                    </div>
                </div>

                <div class="header-ctas col-2">
                    <div class="header-icons">

                        <a href="/contact-us/" class="btn primary">Talk to an expert</a>

                        <div class="site-nav mobile">
                            <button class="mm-toggle" aria-controls="mm-drawer" aria-expanded="false" type="button"></button>

                            <div class="mm-overlay" hidden></div>

                            <nav id="mm-drawer" class="mm-drawer" aria-hidden="true">
                                <div class="mm-topbar">

                                    <?php if (has_custom_logo()) : ?>
                                        <?php the_custom_logo(); ?>
                                    <?php else : ?>
                                        <span><?php bloginfo('name'); ?></span>
                                    <?php endif; ?>

                                    <button class="mm-close" type="button" aria-label="Close menu">✕</button>
                                </div>

                                <div class="mm-menu-content">
                                    <?php wp_nav_menu([
                                        'theme_location' => 'main-menu',
                                        'container'      => false,
                                        'depth'          => 4,
                                        'fallback_cb'    => false,
                                    ]); ?>
                                </div>

                                <div class="mm-footer">
                                    <a href="/contact/" class="btn primary">Talk to an expert</a>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

        </header>
        <?php if (!is_front_page()) : ?>
            <!-- <div class="breadcrumbs">
                <?php if (function_exists('yoast_breadcrumb')) {
                    yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
                } ?>
            </div> -->
        <?php endif; ?>