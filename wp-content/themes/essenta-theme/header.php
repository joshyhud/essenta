<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>

    <link rel="preconnect" href="https://use.typekit.net" crossorigin>
    <link rel="preconnect" href="https://p.typekit.net" crossorigin>

    <link
        rel="preload"
        href="https://use.typekit.net/kxn1npi.css"
        as="style"
        onload="this.onload=null;this.rel='stylesheet'">

    <noscript>
        <link rel="stylesheet" href="https://use.typekit.net/kxn1npi.css">
    </noscript>

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PGXPHSFK');
    </script>
    <!-- End Google Tag Manager -->

    <?php if (is_front_page()) : ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Organization",
                "@id": "https://essentadevedev.wpenginepowered.com/#organization",
                "name": "Essenta",
                "legalName": "Essenta Partners",
                "url": "https://essentadevedev.wpenginepowered.com/",
                "logo": {
                    "@type": "ImageObject",
                    "@id": "https://essentadevedev.wpenginepowered.com/#logo",
                    "url": "https://essentadevedev.wpenginepowered.com/wp-content/uploads/2026/08/Primary_Blue-3.svg",
                    "caption": "Essenta"
                },
                "image": {
                    "@id": "https://essentadevedev.wpenginepowered.com/#logo"
                },
                "description": "Essenta is a senior-led executive search, interim leadership and leadership advisory firm partnering with investors, boards and leadership teams across the UK and US.",
                "sameAs": [
                    "https://www.linkedin.com/company/essenta-partners/"
                ],
                "email": "enquiries@essentapartners.com",
                "telephone": "+44 203 819 3680",
                "contactPoint": [{
                    "@type": "ContactPoint",
                    "contactType": "sales",
                    "email": "enquiries@essentapartners.com",
                    "telephone": "+44-203-819-3680",
                    "areaServed": ["GB", "US"],
                    "availableLanguage": ["en"]
                }],
                "address": [{
                        "@type": "PostalAddress",
                        "streetAddress": "77 Saint Martin's Lane",
                        "addressLocality": "London",
                        "postalCode": "WC2N 4AA",
                        "addressCountry": "GB"
                    },
                    {
                        "@type": "PostalAddress",
                        "streetAddress": "287 Park Ave S, Suite 333",
                        "addressLocality": "New York",
                        "addressRegion": "NY",
                        "postalCode": "10010",
                        "addressCountry": "US"
                    }
                ]
            }
        </script>
    <?php endif; ?>


</head>

<body <?php body_class(); ?>>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PGXPHSFK"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

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

                        <a href="/contact/" class="btn primary">Talk to an expert</a>

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