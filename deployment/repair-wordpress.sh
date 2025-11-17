#!/bin/bash

set -e

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Configuration
DOMAIN="torly.ai"
DB_NAME="torly_wordpress"
DB_USER="torly_user"
DB_PASS=$(openssl rand -base64 24 | tr -d "=+/" | cut -c1-16)
DB_PREFIX="wp_"
WP_PATH="/var/www/html"
ADMIN_PASS=$(openssl rand -base64 24 | tr -d "=+/" | cut -c1-16)

print_status "Repairing WordPress deployment..."

# Clean up any partial WordPress installation
print_status "Cleaning up previous installation..."
rm -rf $WP_PATH/*

# Setup MySQL database with correct syntax
print_status "Setting up MySQL database..."
mysql -e "DROP DATABASE IF EXISTS ${DB_NAME};"
mysql -e "DROP USER IF EXISTS '${DB_USER}'@'localhost';"
mysql -e "CREATE DATABASE ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

print_status "Database setup complete"
print_status "  Database: ${DB_NAME}"
print_status "  User: ${DB_USER}"
print_status "  Password: ${DB_PASS}"

# Download WordPress
print_status "Downloading WordPress..."
cd $WP_PATH
curl -O https://wordpress.org/latest.tar.gz
tar -xzf latest.tar.gz
mv wordpress/* .
rm -rf wordpress latest.tar.gz

# Configure WordPress
print_status "Configuring WordPress..."
cat > wp-config.php << EOF
<?php
define('DB_NAME', '${DB_NAME}');
define('DB_USER', '${DB_USER}');
define('DB_PASSWORD', '${DB_PASS}');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

\$table_prefix = '${DB_PREFIX}';

define('AUTH_KEY',         '$(openssl rand -base64 64)');
define('SECURE_AUTH_KEY',  '$(openssl rand -base64 64)');
define('LOGGED_IN_KEY',    '$(openssl rand -base64 64)');
define('NONCE_KEY',        '$(openssl rand -base64 64)');
define('AUTH_SALT',        '$(openssl rand -base64 64)');
define('SECURE_AUTH_SALT', '$(openssl rand -base64 64)');
define('LOGGED_IN_SALT',   '$(openssl rand -base64 64)');
define('NONCE_SALT',       '$(openssl rand -base64 64)');

/* TorlyAI Custom Configurations */
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
define('WP_POST_REVISIONS', 5);
define('EMPTY_TRASH_DAYS', 30);
define('WP_ALLOW_MULTISITE', true);
define('FORCE_SSL_ADMIN', false);  // Will enable after SSL setup

define('WP_DEBUG', false);

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
EOF

# Install WordPress Core
print_status "Installing WordPress core..."
php -r "
define('WP_INSTALLING', true);
require_once('$WP_PATH/wp-load.php');
require_once('$WP_PATH/wp-admin/includes/upgrade.php');
wp_install('TorlyAI - UK Innovator Visa AI Assistant', 'admin', 'admin@$DOMAIN', true, '', '$ADMIN_PASS');
"

# Set correct permissions
print_status "Setting file permissions..."
chown -R www-data:www-data $WP_PATH
find $WP_PATH -type d -exec chmod 755 {} \;
find $WP_PATH -type f -exec chmod 644 {} \;

# Copy and activate theme
print_status "Installing TorlyAI theme..."
cp -r /tmp/theme/torly-theme $WP_PATH/wp-content/themes/
chown -R www-data:www-data $WP_PATH/wp-content/themes/torly-theme

# Activate theme via database
mysql ${DB_NAME} -e "UPDATE ${DB_PREFIX}options SET option_value = 'torly-theme' WHERE option_name = 'template' OR option_name = 'stylesheet';"

# Restart Apache
print_status "Restarting Apache..."
systemctl restart apache2

# Save credentials
print_status "Saving credentials..."
cat > /root/wordpress_credentials.txt << EOF
WordPress Installation Complete!
================================

Site URL: http://$DOMAIN
Admin URL: http://$DOMAIN/wp-admin

Database Credentials:
  Database Name: ${DB_NAME}
  Database User: ${DB_USER}
  Database Password: ${DB_PASS}

WordPress Admin Credentials:
  Username: admin
  Password: ${ADMIN_PASS}

IMPORTANT: Save these credentials securely!
EOF

chmod 600 /root/wordpress_credentials.txt

print_status "✅ WordPress installation complete!"
print_status "Credentials saved to /root/wordpress_credentials.txt"
print_status ""
print_status "Admin credentials:"
print_status "  Username: admin"
print_status "  Password: ${ADMIN_PASS}"
print_status ""
print_status "Next steps:"
print_status "  1. Setup SSL certificates"
print_status "  2. Configure WordPress Multisite"
print_status "  3. Publish blog content"
