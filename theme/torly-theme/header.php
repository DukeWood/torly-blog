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
    <?php
    $post_tags = wp_get_post_tags(get_the_ID(), array('fields' => 'names'));
    $post_tags = array_filter($post_tags, function($tag) { return strtolower($tag) !== 'hidden'; });
    $keywords = implode(', ', $post_tags);
    if ($keywords) $keywords .= ', ';
    $keywords .= 'UK Innovator Visa, UK Immigration, Business Visa UK';
    ?>
    <meta name="keywords" content="<?php echo esc_attr($keywords); ?>">
    <meta name="author" content="TorlyAI">
    <meta name="geo.region" content="GB">
    <meta name="geo.placename" content="United Kingdom">
    <link rel="canonical" href="<?php echo esc_url(get_permalink()); ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
    <meta property="og:description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
    <meta property="og:site_name" content="Torly AI">
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

    <?php if (is_home() || is_front_page()) : // Blog index ?>
    <meta name="description" content="Expert insights on UK Innovator Visa, business immigration strategies, and AI-powered visa preparation from TorlyAI.">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <link rel="canonical" href="<?php echo esc_url(home_url('/blog/')); ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="UK Innovator Visa Blog | TorlyAI">
    <meta property="og:description" content="Expert insights on UK Innovator Visa, business immigration strategies, and AI-powered visa preparation.">
    <meta property="og:url" content="<?php echo esc_url(home_url('/blog/')); ?>">
    <meta property="og:site_name" content="Torly AI">
    <?php elseif (is_category()) : ?>
    <meta name="description" content="<?php echo esc_attr(category_description()); ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo esc_url(get_category_link(get_queried_object_id())); ?>">
    <?php elseif (is_search() || is_author() || is_date() || is_tag()) : // thin/duplicate archives — keep out of the index, let link equity flow ?>
    <meta name="robots" content="noindex, follow">
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

        /* Theme Toggle Button - positioned between hamburger and nav */
        .theme-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all 0.2s ease;
            flex-shrink: 0;
            order: 2; /* between nav and hamburger on mobile */
        }

        .theme-toggle:hover {
            color: var(--text-primary);
            border-color: var(--text-tertiary);
        }

        /* Show sun in dark mode, moon in light mode */
        [data-theme="dark"] .theme-icon-moon { display: none; }
        [data-theme="dark"] .theme-icon-sun { display: block; }
        :root:not([data-theme="dark"]) .theme-icon-sun { display: none; }
        :root:not([data-theme="dark"]) .theme-icon-moon { display: block; }

        /* Dark mode toggle colors */
        [data-theme="dark"] .theme-toggle {
            border-color: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.6);
        }

        [data-theme="dark"] .theme-toggle:hover {
            color: rgba(255, 255, 255, 0.9);
            border-color: rgba(255, 255, 255, 0.25);
        }

        /* Mobile: theme toggle + hamburger side by side */
        @media (max-width: 768px) {
            .header-content {
                display: flex;
                align-items: center;
            }
            .theme-toggle {
                order: 2;
                margin-left: auto;
                margin-right: 0.5rem;
                width: 36px;
                height: 36px;
            }
            .menu-toggle {
                order: 3;
            }
            .site-logo {
                order: 1;
            }
            .main-navigation {
                order: 4;
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

    <?php
    // Editorial design system is light-only (matches torly.ai marketing side).
    // Dark-mode toggle removed from the header; legacy localStorage from the
    // previous theme is ignored here to force the editorial bone background.
    ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class('torly-editorial-active'); ?>>
<?php wp_body_open(); ?>

<div class="torly-editorial">
<header class="torly-editorial-header" id="torly-editorial-header">
    <div class="ed-inner">
        <!-- Brand: logo tile + stacked wordmark + slogan -->
        <a href="https://torly.ai/" class="ed-brand" aria-label="TorlyAI home">
            <span class="ed-logo-tile">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/torlyai-logo.png" alt="" />
            </span>
            <span class="ed-brand-stack">
                <span class="ed-wordmark">
                    <span>Torly</span><span class="ed-accent-dot">.</span><span class="ed-accent-ai">AI</span>
                </span>
                <span class="ed-slogan">Dream it · Build it · Scaled</span>
            </span>
        </a>

        <!-- Desktop nav -->
        <nav class="ed-nav" aria-label="Primary">
            <a href="https://torly.ai/#product">Product</a>
            <a href="https://torly.ai/agents">Agents</a>
            <a href="https://torly.ai/pricing">Pricing</a>
            <a href="https://torly.ai/blog/">Blog</a>
            <a href="https://torly.ai/guides/innovator-founder-visa/">Guides</a>
        </nav>

        <!-- Actions: CTA (desktop) + mobile menu toggle -->
        <div class="ed-actions">
            <a href="https://torly.ai/assess" class="ed-cta" style="display:none" id="ed-cta-desktop">
                Get your free assessment
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
            <button class="ed-mobile-toggle" id="ed-mobile-toggle" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="ed-mobile-drawer">
                <svg id="ed-icon-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="7" x2="20" y2="7"></line><line x1="4" y1="12" x2="20" y2="12"></line><line x1="4" y1="17" x2="20" y2="17"></line></svg>
                <svg id="ed-icon-close" style="display:none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
            </button>
        </div>
    </div>

    <!-- Mobile drawer -->
    <div class="ed-mobile-drawer" id="ed-mobile-drawer" role="dialog" aria-label="Mobile navigation">
        <ul>
            <li><a href="https://torly.ai/#product">Product</a></li>
            <li><a href="https://torly.ai/agents">Agents</a></li>
            <li><a href="https://torly.ai/pricing">Pricing</a></li>
            <li><a href="https://torly.ai/blog/">Blog</a></li>
            <li><a href="https://torly.ai/guides/innovator-founder-visa/">Guides</a></li>
            <li><a href="https://torly.ai/assess" class="ed-cta">Get your free assessment →</a></li>
        </ul>
    </div>
</header>
</div>

<script>
// Editorial header — scroll state + mobile drawer + CSS media query shim for desktop CTA
(function(){
    var header = document.getElementById('torly-editorial-header');
    var toggle = document.getElementById('ed-mobile-toggle');
    var drawer = document.getElementById('ed-mobile-drawer');
    var iconMenu = document.getElementById('ed-icon-menu');
    var iconClose = document.getElementById('ed-icon-close');
    var ctaDesktop = document.getElementById('ed-cta-desktop');
    if (!header) return;

    // Add .is-scrolled class when user scrolls past 6px (adds hairline border)
    function onScroll() {
        if (window.scrollY > 6) header.classList.add('is-scrolled');
        else header.classList.remove('is-scrolled');
    }
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    // Desktop CTA visibility at ≥1024px (inline style trumps a CSS rule, so
    // toggle the inline style based on matchMedia)
    if (ctaDesktop) {
        var mql = window.matchMedia('(min-width: 1024px)');
        function syncCta() { ctaDesktop.style.display = mql.matches ? 'inline-flex' : 'none'; }
        syncCta();
        if (mql.addEventListener) mql.addEventListener('change', syncCta);
        else if (mql.addListener) mql.addListener(syncCta);
    }

    // Mobile drawer toggle
    if (toggle && drawer) {
        toggle.addEventListener('click', function(){
            var isOpen = header.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', String(isOpen));
            if (iconMenu) iconMenu.style.display = isOpen ? 'none' : '';
            if (iconClose) iconClose.style.display = isOpen ? '' : 'none';
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });
    }
})();
</script>

<?php /* Legacy theme-toggle script removed — editorial design is light-only. */ ?>
