#!/bin/bash

##############################################################################
# Setup Secondary Domain - innovatorfoundervisauk.com
#
# This script configures Apache to serve the same WordPress site on both:
# - torly.ai (primary domain)
# - innovatorfoundervisauk.com (secondary domain)
#
# Usage: sudo bash deployment/setup-secondary-domain.sh
##############################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
SECONDARY_DOMAIN="innovatorfoundervisauk.com"
PRIMARY_DOMAIN="torly.ai"
DOCUMENT_ROOT="/var/www/html"
APACHE_SITES="/etc/apache2/sites-available"
WP_CONFIG="/var/www/html/wp-config.php"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Setting up Secondary Domain${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Primary Domain: $PRIMARY_DOMAIN"
echo "Secondary Domain: $SECONDARY_DOMAIN"
echo "Document Root: $DOCUMENT_ROOT"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}ERROR: Please run as root (use sudo)${NC}"
    exit 1
fi

# Check if Apache is installed
if ! command -v apache2 &> /dev/null; then
    echo -e "${RED}ERROR: Apache is not installed${NC}"
    exit 1
fi

# Check if WordPress is installed
if [ ! -f "$WP_CONFIG" ]; then
    echo -e "${RED}ERROR: WordPress not found at $DOCUMENT_ROOT${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 1: Creating Apache virtual host configuration...${NC}"

# Create Apache virtual host for secondary domain
cat > "$APACHE_SITES/${SECONDARY_DOMAIN}.conf" <<EOF
<VirtualHost *:80>
    ServerName ${SECONDARY_DOMAIN}
    ServerAlias www.${SECONDARY_DOMAIN}

    DocumentRoot ${DOCUMENT_ROOT}

    <Directory ${DOCUMENT_ROOT}>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Logging
    ErrorLog \${APACHE_LOG_DIR}/${SECONDARY_DOMAIN}_error.log
    CustomLog \${APACHE_LOG_DIR}/${SECONDARY_DOMAIN}_access.log combined

    # Security Headers
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</VirtualHost>
EOF

echo -e "${GREEN}✓ Virtual host configuration created${NC}"

echo -e "${YELLOW}Step 2: Enabling the site...${NC}"

# Enable the site
a2ensite ${SECONDARY_DOMAIN}.conf

echo -e "${GREEN}✓ Site enabled${NC}"

echo -e "${YELLOW}Step 3: Updating WordPress configuration...${NC}"

# Backup wp-config.php
cp $WP_CONFIG ${WP_CONFIG}.backup.$(date +%Y%m%d_%H%M%S)

# Check if multi-domain config already exists
if grep -q "WP_SITEURL.*HTTP_HOST" $WP_CONFIG; then
    echo -e "${YELLOW}Multi-domain configuration already exists in wp-config.php${NC}"
else
    # Find the line "That's all, stop editing!"
    LINE_NUMBER=$(grep -n "That's all, stop editing" $WP_CONFIG | cut -d: -f1)

    if [ -z "$LINE_NUMBER" ]; then
        echo -e "${RED}WARNING: Could not find insertion point in wp-config.php${NC}"
        echo -e "${YELLOW}Please manually add the following to wp-config.php:${NC}"
        echo ""
        echo "define('WP_SITEURL', 'https://' . \$_SERVER['HTTP_HOST']);"
        echo "define('WP_HOME', 'https://' . \$_SERVER['HTTP_HOST']);"
        echo ""
    else
        # Insert multi-domain configuration
        sed -i "${LINE_NUMBER}i\\
\\
/* Multi-Domain Configuration */\\
define('WP_SITEURL', 'https://' . \$_SERVER['HTTP_HOST']);\\
define('WP_HOME', 'https://' . \$_SERVER['HTTP_HOST']);\\
" $WP_CONFIG

        echo -e "${GREEN}✓ WordPress multi-domain configuration added${NC}"
    fi
fi

echo -e "${YELLOW}Step 4: Testing Apache configuration...${NC}"

# Test Apache configuration
if apache2ctl configtest; then
    echo -e "${GREEN}✓ Apache configuration is valid${NC}"
else
    echo -e "${RED}ERROR: Apache configuration test failed${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 5: Reloading Apache...${NC}"

# Reload Apache
systemctl reload apache2

echo -e "${GREEN}✓ Apache reloaded${NC}"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Configuration Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}NEXT STEPS:${NC}"
echo ""
echo "1. Update DNS records at Dreamhost:"
echo "   - A record: @ → Your Oracle Cloud IP"
echo "   - A record: www → Your Oracle Cloud IP"
echo ""
echo "2. Wait for DNS propagation (5-60 minutes)"
echo "   Check with: nslookup ${SECONDARY_DOMAIN}"
echo ""
echo "3. Install SSL certificate (after DNS propagates):"
echo "   sudo certbot --apache -d ${SECONDARY_DOMAIN} -d www.${SECONDARY_DOMAIN}"
echo ""
echo "4. Test both domains:"
echo "   - https://${PRIMARY_DOMAIN}"
echo "   - https://${SECONDARY_DOMAIN}"
echo ""
echo -e "${GREEN}Both domains will now serve the same WordPress site!${NC}"
echo ""
echo "Files modified:"
echo "  - ${APACHE_SITES}/${SECONDARY_DOMAIN}.conf (created)"
echo "  - ${WP_CONFIG} (updated)"
echo "  - ${WP_CONFIG}.backup.* (backup created)"
echo ""
