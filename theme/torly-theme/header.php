<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/torlyai-logo.png">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/assets/torlyai-logo.png">

    <?php if (is_single()) : ?>
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
    <meta name="keywords" content="<?php echo esc_attr(implode(', ', wp_get_post_tags(get_the_ID(), array('fields' => 'names')))); ?>, UK Innovator Visa, UK Immigration, Business Visa UK">
    <meta name="author" content="TorlyAI">
    <meta name="geo.region" content="GB">
    <meta name="geo.placename" content="United Kingdom">
    <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
    <meta property="og:description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
    <meta property="og:site_name" content="TorlyAI - UK Innovator Visa Assistant">
    <meta property="og:locale" content="en_GB">
    <?php if (has_post_thumbnail()) : ?>
    <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <?php endif; ?>
    <meta property="article:published_time" content="<?php echo get_the_date('c'); ?>">
    <meta property="article:modified_time" content="<?php echo get_the_modified_date('c'); ?>">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr(get_the_title()); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
    <?php if (has_post_thumbnail()) : ?>
    <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>">
    <?php endif; ?>
    <?php endif; ?>
    <style>
        .site-logo .logo-image {
            height: 50px;
            width: auto;
            display: block;
            transition: opacity 0.3s ease;
        }
        .site-logo:hover .logo-image {
            opacity: 0.8;
        }
        @media (max-width: 768px) {
            .site-logo .logo-image {
                height: 40px;
            }
        }
    </style>

    <?php
    // Google Analytics 4 Tracking
    $ga_measurement_id = get_option('torlyai_ga_measurement_id', '');
    if (!empty($ga_measurement_id)) :
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_measurement_id); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo esc_js($ga_measurement_id); ?>', {
            'anonymize_ip': true,
            'cookie_flags': 'SameSite=None;Secure'
        });

        // Custom event tracking for conversions
        document.addEventListener('DOMContentLoaded', function() {
            // Track CTA clicks
            const ctaButtons = document.querySelectorAll('.cta-button, .btn-primary, a[href*="get-started"]');
            ctaButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    gtag('event', 'cta_click', {
                        'event_category': 'engagement',
                        'event_label': this.textContent.trim(),
                        'value': 1
                    });
                });
            });

            // Track form submissions
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    gtag('event', 'form_submit', {
                        'event_category': 'conversion',
                        'event_label': this.id || 'unknown_form',
                        'value': 5
                    });
                });
            });

            // Track scroll depth
            let scrollDepth = 0;
            window.addEventListener('scroll', function() {
                const winHeight = window.innerHeight;
                const docHeight = document.documentElement.scrollHeight;
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const trackPercentage = Math.floor((scrollTop + winHeight) / docHeight * 100);

                if (trackPercentage > scrollDepth && trackPercentage % 25 === 0) {
                    scrollDepth = trackPercentage;
                    gtag('event', 'scroll_depth', {
                        'event_category': 'engagement',
                        'event_label': scrollDepth + '%',
                        'value': scrollDepth
                    });
                }
            });

            // Track outbound links
            const outboundLinks = document.querySelectorAll('a[href^="http"]');
            outboundLinks.forEach(link => {
                if (!link.href.includes(window.location.hostname)) {
                    link.addEventListener('click', function(e) {
                        gtag('event', 'outbound_link', {
                            'event_category': 'engagement',
                            'event_label': this.href,
                            'transport_type': 'beacon'
                        });
                    });
                }
            });
        });
    </script>
    <?php endif; ?>

    <!-- Preload Premium Fonts for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container">
        <div class="header-content">
            <a href="https://torly.ai/" class="site-logo">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai-logo.png" alt="Torly AI" class="logo-image" />
            </a>

            <!-- Mobile Menu Toggle -->
            <button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>

            <nav class="main-navigation">
                <ul>
                    <li><a href="https://torly.ai/">Home</a></li>
                    <li><a href="https://torly.ai/blog/">Blog</a></li>
                    <li><a href="https://torly.ai/about">About</a></li>
                    <li><a href="https://torly.ai/contact">Contact</a></li>
                    <li><a href="https://torly.ai/get-started" class="cta-button">Get Started</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
