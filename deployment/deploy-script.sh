#!/bin/bash

#################################################################
# TorlyAI WordPress Deployment Script
# 
# This script automates the deployment of WordPress with the
# TorlyAI custom theme and configurations
#################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration variables
DOMAIN="torly.ai"
BLOG_SUBDOMAIN="blog.torly.ai"
WP_PATH="/var/www/html"
THEME_NAME="torly-theme"
DB_NAME="torly_wordpress"
DB_USER="torly_user"
DB_PREFIX="wp_"

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   print_error "This script must be run as root"
   exit 1
fi

print_status "Starting TorlyAI WordPress deployment..."

# Step 1: Install dependencies
print_status "Installing system dependencies..."
apt-get update
apt-get install -y \
    apache2 \
    mysql-server \
    php \
    php-mysql \
    php-curl \
    php-gd \
    php-mbstring \
    php-xml \
    php-xmlrpc \
    php-soap \
    php-intl \
    php-zip \
    wget \
    unzip \
    curl \
    git

# Step 2: Install WP-CLI
if ! command -v wp &> /dev/null; then
    print_status "Installing WP-CLI..."
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
    mv wp-cli.phar /usr/local/bin/wp
else
    print_status "WP-CLI already installed"
fi

# Step 3: Create database
print_status "Setting up MySQL database..."

# Generate secure random password and save it
DB_PASSWORD=$(openssl rand -base64 32)
echo "$DB_PASSWORD" > /root/.torly_db_password
chmod 600 /root/.torly_db_password

mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';"
mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

print_status "Database password saved to /root/.torly_db_password"

# Step 4: Download and configure WordPress
print_status "Downloading WordPress..."
cd $WP_PATH
wp core download --allow-root

# Step 5: Configure WordPress
print_status "Configuring WordPress..."

# Read database password from saved file
DB_PASSWORD=$(cat /root/.torly_db_password)

wp config create \
    --dbname=$DB_NAME \
    --dbuser=$DB_USER \
    --dbpass="$DB_PASSWORD" \
    --dbprefix=$DB_PREFIX \
    --allow-root

# Add custom configurations to wp-config.php
cat >> wp-config.php << 'EOF'

/* TorlyAI Custom Configurations */
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
define('WP_POST_REVISIONS', 5);
define('EMPTY_TRASH_DAYS', 30);
define('WP_CRON_LOCK_TIMEOUT', 120);
define('AUTOSAVE_INTERVAL', 300);

/* Multisite Configuration for blog.torly.ai */
define('WP_ALLOW_MULTISITE', true);

/* Security Headers */
define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL_LOGIN', true);

/* API Settings */
define('JWT_AUTH_SECRET_KEY', '$(openssl rand -base64 64 | tr -d "\n")');
define('JWT_AUTH_CORS_ENABLE', true);
EOF

# Step 6: Install WordPress
print_status "Installing WordPress..."
wp core install \
    --url=$DOMAIN \
    --title="TorlyAI - UK Innovator Visa AI Assistant" \
    --admin_user=admin \
    --admin_password=$(openssl rand -base64 32) \
    --admin_email=admin@$DOMAIN \
    --allow-root

# Step 7: Install and activate theme
print_status "Installing TorlyAI theme..."
cp -r ../theme/$THEME_NAME $WP_PATH/wp-content/themes/
wp theme activate $THEME_NAME --allow-root

# Step 8: Configure Apache virtual hosts
print_status "Configuring Apache..."

# Main domain configuration
cat > /etc/apache2/sites-available/torly.ai.conf << EOF
<VirtualHost *:80>
    ServerName ${DOMAIN}
    ServerAlias www.${DOMAIN}
    DocumentRoot ${WP_PATH}
    
    <Directory ${WP_PATH}>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/torly-error.log
    CustomLog \${APACHE_LOG_DIR}/torly-access.log combined
</VirtualHost>
EOF

# Blog subdomain configuration
cat > /etc/apache2/sites-available/blog.torly.ai.conf << EOF
<VirtualHost *:80>
    ServerName ${BLOG_SUBDOMAIN}
    DocumentRoot ${WP_PATH}
    
    <Directory ${WP_PATH}>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/blog-torly-error.log
    CustomLog \${APACHE_LOG_DIR}/blog-torly-access.log combined
</VirtualHost>
EOF

# Enable sites and modules
a2ensite torly.ai.conf
a2ensite blog.torly.ai.conf
a2enmod rewrite
a2enmod headers
a2enmod ssl
systemctl restart apache2

# Step 9: Install essential plugins
print_status "Installing essential plugins..."
wp plugin install \
    wordpress-seo \
    wordfence \
    wp-super-cache \
    contact-form-7 \
    updraftplus \
    --activate \
    --allow-root

# Step 10: Create initial content
print_status "Creating initial pages..."

# Create essential pages
wp post create --post_type=page --post_title='Home' --post_status=publish --allow-root
wp post create --post_type=page --post_title='About' --post_status=publish --allow-root
wp post create --post_type=page --post_title='Services' --post_status=publish --allow-root
wp post create --post_type=page --post_title='Blog' --post_status=publish --allow-root
wp post create --post_type=page --post_title='Contact' --post_status=publish --allow-root
wp post create --post_type=page --post_title='Visa Assessment' --post_status=publish --allow-root
wp post create --post_type=page --post_title='Privacy Policy' --post_status=publish --allow-root
wp post create --post_type=page --post_title='Terms of Service' --post_status=publish --allow-root

# Set homepage
HOME_ID=$(wp post list --post_type=page --fields=ID,post_title --format=csv --allow-root | grep "Home" | cut -d',' -f1)
wp option update page_on_front $HOME_ID --allow-root
wp option update show_on_front page --allow-root

# Set blog page
BLOG_ID=$(wp post list --post_type=page --fields=ID,post_title --format=csv --allow-root | grep "Blog" | cut -d',' -f1)
wp option update page_for_posts $BLOG_ID --allow-root

# Step 11: Configure permalinks
print_status "Configuring permalinks..."
wp rewrite structure '/%postname%/' --allow-root
wp rewrite flush --allow-root

# Step 12: Set up cron job for WordPress
print_status "Setting up WordPress cron..."
(crontab -l 2>/dev/null; echo "*/5 * * * * cd ${WP_PATH}; php wp-cron.php") | crontab -

# Step 13: Set proper permissions
print_status "Setting file permissions..."
chown -R www-data:www-data $WP_PATH
find $WP_PATH -type d -exec chmod 755 {} \;
find $WP_PATH -type f -exec chmod 644 {} \;

# Step 14: Install SSL certificate (Let's Encrypt)
print_status "Installing SSL certificate..."
apt-get install -y certbot python3-certbot-apache

# Wait for DNS propagation before requesting SSL
print_status "Checking DNS propagation..."
MAX_WAIT=300  # 5 minutes
WAITED=0
while [ $WAITED -lt $MAX_WAIT ]; do
    if nslookup $DOMAIN | grep -q "Address:"; then
        print_status "DNS is propagated for $DOMAIN"
        break
    fi
    print_warning "Waiting for DNS propagation... ($WAITED/$MAX_WAIT seconds)"
    sleep 30
    WAITED=$((WAITED + 30))
done

# Request SSL certificate
certbot --apache -d $DOMAIN -d www.$DOMAIN -d $BLOG_SUBDOMAIN --non-interactive --agree-tos --email admin@$DOMAIN

# Verify SSL installation
print_status "Verifying SSL installation..."
if openssl s_client -connect $DOMAIN:443 -servername $DOMAIN </dev/null 2>/dev/null | grep -q "Verify return code: 0"; then
    print_status "SSL certificate verified successfully"
else
    print_warning "SSL verification may need more time for DNS propagation"
fi

# Configure SSL for A+ rating
print_status "Configuring SSL for A+ rating (HSTS, strong ciphers)..."
cat > /etc/apache2/conf-available/ssl-params.conf << 'EOF'
# SSL Configuration for A+ Rating
SSLProtocol all -SSLv2 -SSLv3 -TLSv1 -TLSv1.1
SSLCipherSuite ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384
SSLHonorCipherOrder on
SSLCompression off
SSLSessionTickets off

# HSTS (HTTP Strict Transport Security) - 1 year
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"

# OCSP Stapling
SSLUseStapling on
SSLStaplingCache "shmcb:logs/stapling-cache(150000)"
EOF

a2enconf ssl-params
systemctl reload apache2

# Setup SSL auto-renewal cron job
print_status "Setting up SSL auto-renewal..."
(crontab -l 2>/dev/null | grep -v certbot; echo "0 3 */7 * * certbot renew --quiet --post-hook 'systemctl reload apache2'") | crontab -

# Step 15: Create .htaccess file
print_status "Creating .htaccess file..."
cat > $WP_PATH/.htaccess << 'EOF'
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# BEGIN WordPress
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
# END WordPress

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Content-Security-Policy "upgrade-insecure-requests"
</IfModule>

# Protect wp-config.php
<Files wp-config.php>
    Order allow,deny
    Deny from all
</Files>

# Disable directory browsing
Options -Indexes

# Block access to hidden files
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

# Subdomain handling for blog.torly.ai
RewriteCond %{HTTP_HOST} ^blog\.torly\.ai$ [NC]
RewriteRule ^(.*)$ /blog/$1 [L]
EOF

# Step 16: Create backup script
print_status "Creating backup script..."
mkdir -p /var/backups/wordpress
cat > /usr/local/bin/backup-torlyai.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/var/backups/wordpress"
DATE=$(date +%Y%m%d_%H%M%S)
DB_BACKUP="$BACKUP_DIR/db_backup_$DATE.sql"
FILES_BACKUP="$BACKUP_DIR/files_backup_$DATE.tar.gz"

# Database backup
mysqldump torly_wordpress > $DB_BACKUP
gzip $DB_BACKUP

# Files backup
tar -czf $FILES_BACKUP /var/www/html/wp-content/

# Remove old backups (older than 30 days)
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup completed: $DATE"
EOF
chmod +x /usr/local/bin/backup-torlyai.sh

# Add backup to cron (daily at 2 AM)
(crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/backup-torlyai.sh") | crontab -

# Step 17: Display installation summary
print_status "========================================="
print_status "TorlyAI WordPress Installation Complete!"
print_status "========================================="
print_status ""
print_status "Main Site URL: https://${DOMAIN}"
print_status "Blog URL: https://${BLOG_SUBDOMAIN}"
print_status "Admin URL: https://${DOMAIN}/wp-admin"
print_status ""
print_status "Database Name: ${DB_NAME}"
print_status "Database User: ${DB_USER}"
print_status ""
print_warning "IMPORTANT: Save the admin credentials displayed above!"
print_warning "Update the DNS records in GoDaddy to point to this server's IP"
print_status ""
print_status "Next steps:"
print_status "1. Update DNS records in GoDaddy"
print_status "2. Configure the MCP server with your credentials"
print_status "3. Customize the theme and content"
print_status "4. Test the visa assessment functionality"
print_status ""
print_status "Deployment completed successfully!"