#!/bin/bash

set -e

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_status() { echo -e "${GREEN}[INFO]${NC} $1"; }
print_error() { echo -e "${RED}[ERROR]${NC} $1"; }
print_warning() { echo -e "${YELLOW}[WARNING]${NC} $1"; }

# Configuration
DOMAIN="torly.ai"
BLOG_DOMAIN="blog.torly.ai"
WP_PATH="/var/www/html"
DB_NAME="torly_wordpress"

print_status "Configuring WordPress Multisite"
print_status "================================"

# Step 1: Enable multisite in wp-config.php (if not already done)
print_status "Enabling multisite in wp-config.php..."

if ! grep -q "WP_ALLOW_MULTISITE" "$WP_PATH/wp-config.php"; then
    sed -i "/\/\* That's all, stop editing/i /* Enable Multisite */\\
define('WP_ALLOW_MULTISITE', true);\\
" "$WP_PATH/wp-config.php"
    print_status "✅ WP_ALLOW_MULTISITE enabled"
else
    print_warning "WP_ALLOW_MULTISITE already enabled"
fi

# Step 1b: Install WP-CLI if not present
if ! command -v wp &> /dev/null; then
    print_status "Installing WP-CLI..."
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
    mv wp-cli.phar /usr/local/bin/wp
fi

# Step 1c: Setup the network using WP-CLI
print_status "Setting up WordPress network..."

cd "$WP_PATH"

# Check if network is already installed
NETWORK_EXISTS=$(sudo -u www-data wp core is-installed --network --path="$WP_PATH" 2>/dev/null && echo "yes" || echo "no")

if [ "$NETWORK_EXISTS" = "no" ]; then
    print_status "Installing WordPress network..."
    sudo -u www-data wp core multisite-convert \
        --subdomains \
        --title="TorlyAI Network" \
        --path="$WP_PATH"

    print_status "✅ WordPress network installed"

    # The multisite-convert command adds the required constants to wp-config.php
    # But we need to ensure the domain is correct
    sed -i "s/define( 'DOMAIN_CURRENT_SITE'.*/define( 'DOMAIN_CURRENT_SITE', '$DOMAIN' );/" "$WP_PATH/wp-config.php"
else
    print_status "✅ WordPress network already installed"
fi

# Step 2: Update .htaccess for multisite
print_status "Updating .htaccess for multisite..."

cat > "$WP_PATH/.htaccess" << 'EOF'
# BEGIN WordPress Multisite
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]

# add a trailing slash to /wp-admin
RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]

RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]
RewriteRule . index.php [L]
# END WordPress Multisite
EOF

chown www-data:www-data "$WP_PATH/.htaccess"
chmod 644 "$WP_PATH/.htaccess"

print_status "✅ .htaccess updated"

# Step 3: Create blog subdomain site
print_status "Creating blog subdomain site..."

cd "$WP_PATH"

# Check if site already exists
SITE_EXISTS=$(sudo -u www-data wp site list --field=url --path="$WP_PATH" 2>/dev/null | grep -c "$BLOG_DOMAIN" || true)

if [ "$SITE_EXISTS" -gt 0 ]; then
    print_warning "Blog site already exists"
    BLOG_ID=$(sudo -u www-data wp site list --field=blog_id --url="$BLOG_DOMAIN" --path="$WP_PATH")
else
    # Create new site
    BLOG_ID=$(sudo -u www-data wp site create \
        --slug=blog \
        --title="TorlyAI Blog - UK Innovator Visa AI Assistant" \
        --email="admin@$DOMAIN" \
        --path="$WP_PATH" \
        --porcelain)

    print_status "✅ Blog site created with ID: $BLOG_ID"
fi

# Step 4: Configure Apache for subdomain handling
print_status "Updating Apache virtual host configuration..."

# The SSL virtual host should already exist from setup-ssl.sh
# We need to ensure it handles both main domain and blog subdomain correctly

# Update the main SSL virtual host to handle multisite
SSL_CONF="/etc/apache2/sites-available/000-default-le-ssl.conf"

if [ -f "$SSL_CONF" ]; then
    # Backup original
    cp "$SSL_CONF" "$SSL_CONF.backup"

    # Update ServerAlias to include blog subdomain
    if ! grep -q "ServerAlias.*$BLOG_DOMAIN" "$SSL_CONF"; then
        sed -i "s/ServerAlias.*/& $BLOG_DOMAIN/" "$SSL_CONF"
        print_status "✅ Added blog subdomain to ServerAlias"
    fi
else
    print_warning "SSL virtual host not found, it will be created when SSL is set up"
fi

# Step 5: Copy theme to blog site
print_status "Activating TorlyAI theme for blog..."

if [ -d "$WP_PATH/wp-content/themes/torly-theme" ]; then
    # Switch blog site to use torly-theme
    sudo -u www-data wp theme enable torly-theme --network --path="$WP_PATH" || true
    sudo -u www-data wp theme activate torly-theme --url="https://$BLOG_DOMAIN" --path="$WP_PATH" || true
    print_status "✅ Theme activated for blog"
else
    print_warning "TorlyAI theme not found, will be installed separately"
fi

# Step 6: Update site URLs to use HTTPS
print_status "Updating site URLs to HTTPS..."

mysql "$DB_NAME" -e "UPDATE wp_blogs SET domain = '$DOMAIN' WHERE blog_id = 1;"
mysql "$DB_NAME" -e "UPDATE wp_blogs SET domain = '$BLOG_DOMAIN' WHERE blog_id = 2;" || true

print_status "✅ Site URLs updated"

# Step 7: Restart Apache
print_status "Restarting Apache..."
systemctl restart apache2

print_status "✅ WordPress Multisite Configuration Complete!"
print_status ""
print_status "Network Details:"
print_status "  Main Site: https://$DOMAIN"
print_status "  Blog Site: https://$BLOG_DOMAIN"
print_status "  Network Admin: https://$DOMAIN/wp-admin/network/"
print_status ""
print_status "Site List:"
sudo -u www-data wp site list --path="$WP_PATH"
print_status ""
print_status "Next steps:"
print_status "  1. Login to network admin: https://$DOMAIN/wp-admin/network/"
print_status "  2. Configure blog site settings"
print_status "  3. Publish blog content"
print_status "  4. Verify both sites are accessible via HTTPS"
