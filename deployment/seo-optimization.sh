#!/bin/bash

##############################################################################
# SEO Optimization Script for TorlyAI Multi-Domain Setup
#
# This script optimizes SEO for both torly.ai and innovatorfoundervisauk.com:
# 1. Adds canonical tags pointing to torly.ai (primary domain)
# 2. Improves meta descriptions
# 3. Adds structured data (Schema.org)
# 4. Configures robots.txt
#
# Usage: sudo bash deployment/seo-optimization.sh
##############################################################################

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

THEME_DIR="/var/www/html/wp-content/themes/torly-theme"
WP_ROOT="/var/www/html"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}SEO Optimization for TorlyAI${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}ERROR: Please run as root (use sudo)${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 1: Adding canonical tags to theme...${NC}"

# Backup header.php
cp $THEME_DIR/header.php $THEME_DIR/header.php.seo-backup

# Create SEO functions in functions.php
cat >> $THEME_DIR/functions.php <<'EOF'

/**
 * SEO Optimization Functions
 */

// Add canonical URL for all pages (always point to torly.ai)
function torlyai_add_canonical_tag() {
    if (is_404()) {
        return;
    }

    $canonical_url = '';

    if (is_front_page() || is_home()) {
        $canonical_url = 'https://torly.ai/';
    } elseif (is_singular()) {
        $canonical_url = 'https://torly.ai' . $_SERVER['REQUEST_URI'];
    } else {
        $canonical_url = 'https://torly.ai' . $_SERVER['REQUEST_URI'];
    }

    // Remove any query parameters for cleaner URLs
    $canonical_url = strtok($canonical_url, '?');

    echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
}
add_action('wp_head', 'torlyai_add_canonical_tag', 1);

// Improve meta descriptions
function torlyai_add_meta_description() {
    if (is_front_page() || is_home()) {
        echo '<meta name="description" content="AI-powered UK Innovator Visa guidance. Get instant eligibility assessment, business plan help, and endorsing body matching. 24/7 support for entrepreneurs.">' . "\n";
    } elseif (is_page('about')) {
        echo '<meta name="description" content="TorlyAI simplifies UK Innovator Visa applications with AI-powered guidance. Join 1,000+ entrepreneurs who trust our platform for visa success.">' . "\n";
    } elseif (is_page('contact')) {
        echo '<meta name="description" content="Contact TorlyAI for UK Innovator Visa guidance. Get answers within 24 hours. Free initial assessment available.">' . "\n";
    } elseif (is_singular('post')) {
        // Already handled in header.php
        return;
    }
}
add_action('wp_head', 'torlyai_add_meta_description', 2);

// Add Schema.org structured data
function torlyai_add_schema_org() {
    if (!is_front_page()) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'TorlyAI',
        'url' => 'https://torly.ai',
        'logo' => 'https://torly.ai/wp-content/themes/torly-theme/assets/torlyai-logo.png',
        'description' => 'AI-powered UK Innovator Visa guidance platform helping entrepreneurs navigate the visa application process.',
        'address' => array(
            '@type' => 'PostalAddress',
            'addressCountry' => 'GB',
            'addressLocality' => 'London'
        ),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'email' => 'hello@torly.ai',
            'contactType' => 'Customer Service',
            'availableLanguage' => 'English'
        ),
        'sameAs' => array(
            'https://torly.ai',
            'https://innovatorfoundervisauk.com'
        )
    );

    echo '<script type="application/ld+json">' . "\n";
    echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo "\n" . '</script>' . "\n";
}
add_action('wp_head', 'torlyai_add_schema_org', 3);

// Add breadcrumb schema for blog posts
function torlyai_add_breadcrumb_schema() {
    if (!is_singular('post')) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(
            array(
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => 'https://torly.ai'
            ),
            array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Blog',
                'item' => 'https://torly.ai/blog/'
            ),
            array(
                '@type' => 'ListItem',
                'position' => 3,
                'name' => get_the_title(),
                'item' => 'https://torly.ai' . $_SERVER['REQUEST_URI']
            )
        )
    );

    echo '<script type="application/ld+json">' . "\n";
    echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo "\n" . '</script>' . "\n";
}
add_action('wp_head', 'torlyai_add_breadcrumb_schema', 4);
EOF

echo -e "${GREEN}✓ SEO functions added to theme${NC}"

echo -e "${YELLOW}Step 2: Creating robots.txt...${NC}"

# Create robots.txt
cat > $WP_ROOT/robots.txt <<'EOF'
User-agent: *
Allow: /

# Sitemaps
Sitemap: https://torly.ai/wp-sitemap.xml
Sitemap: https://torly.ai/sitemap.xml

# Disallow admin and wp-includes
Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /wp-content/plugins/
Disallow: /wp-content/themes/

# Allow crawling of CSS and JS
Allow: /wp-content/themes/*.css
Allow: /wp-content/themes/*.js
Allow: /wp-content/plugins/*.css
Allow: /wp-content/plugins/*.js

# Disallow search and filter pages
Disallow: /?s=
Disallow: /search/
Disallow: /*?
EOF

chown www-data:www-data $WP_ROOT/robots.txt

echo -e "${GREEN}✓ robots.txt created${NC}"

echo -e "${YELLOW}Step 3: Installing Yoast SEO plugin (recommended)...${NC}"

# Install Yoast SEO for better SEO control
cd $WP_ROOT
sudo -u www-data wp plugin install wordpress-seo --activate --quiet 2>/dev/null || echo "Yoast SEO already installed or error occurred"

echo -e "${GREEN}✓ SEO plugin check complete${NC}"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}SEO Optimization Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}What was done:${NC}"
echo "✓ Canonical tags added (all pages point to torly.ai)"
echo "✓ Meta descriptions optimized"
echo "✓ Schema.org structured data added"
echo "✓ robots.txt created"
echo "✓ Yoast SEO plugin installed (optional)"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo "1. Submit sitemap to Google Search Console:"
echo "   https://torly.ai/wp-sitemap.xml"
echo ""
echo "2. Test structured data:"
echo "   https://search.google.com/test/rich-results"
echo ""
echo "3. Check robots.txt:"
echo "   https://torly.ai/robots.txt"
echo ""
echo "4. Google Search Console setup:"
echo "   - Add torly.ai as primary property"
echo "   - Add innovatorfoundervisauk.com as additional property"
echo "   - Set torly.ai as preferred domain"
echo ""
