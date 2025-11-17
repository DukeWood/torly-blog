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
EMAIL="admin@torly.ai"

print_status "Starting SSL/TLS Setup with Let's Encrypt"
print_status "=========================================="

# Step 1: Install Certbot
print_status "Installing Certbot..."
apt-get update -qq
apt-get install -y certbot python3-certbot-apache

# Step 2: Configure Apache for SSL (before obtaining certificates)
print_status "Configuring Apache virtual hosts..."

# Main domain virtual host
cat > /etc/apache2/sites-available/torly.ai.conf << 'EOF'
<VirtualHost *:80>
    ServerName torly.ai
    ServerAlias www.torly.ai
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/torly_error.log
    CustomLog ${APACHE_LOG_DIR}/torly_access.log combined
</VirtualHost>
EOF

# Blog subdomain virtual host
cat > /etc/apache2/sites-available/blog.torly.ai.conf << 'EOF'
<VirtualHost *:80>
    ServerName blog.torly.ai
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/blog_error.log
    CustomLog ${APACHE_LOG_DIR}/blog_access.log combined
</VirtualHost>
EOF

# Enable sites and modules
a2ensite torly.ai.conf
a2ensite blog.torly.ai.conf
a2enmod rewrite
a2enmod ssl
a2enmod headers
systemctl reload apache2

# Step 3: Obtain SSL certificates
print_status "Obtaining SSL certificates from Let's Encrypt..."
print_warning "This may take a few minutes..."

# Wait for DNS propagation
print_status "Checking DNS propagation for $DOMAIN..."
for i in {1..30}; do
    if host $DOMAIN 8.8.8.8 | grep -q "141.147.89.179"; then
        print_status "DNS resolved correctly for $DOMAIN"
        break
    fi
    if [ $i -eq 30 ]; then
        print_error "DNS not propagated yet. Please wait and try again."
        exit 1
    fi
    sleep 2
done

# Obtain certificates
certbot --apache \
    --non-interactive \
    --agree-tos \
    --email $EMAIL \
    --domains $DOMAIN,www.$DOMAIN,$BLOG_DOMAIN \
    --redirect

# Step 4: Configure SSL for A+ rating
print_status "Configuring SSL for A+ rating..."

# Create SSL configuration file
cat > /etc/apache2/conf-available/ssl-params.conf << 'EOF'
# SSL/TLS Configuration for A+ Rating

# Disable SSLv2, SSLv3, TLS 1.0, TLS 1.1
SSLProtocol -all +TLSv1.2 +TLSv1.3

# Strong cipher suites (prioritize perfect forward secrecy)
SSLCipherSuite ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384
SSLHonorCipherOrder off
SSLSessionTickets off

# OCSP Stapling
SSLUseStapling on
SSLStaplingResponderTimeout 5
SSLStaplingReturnResponderErrors off
SSLStaplingCache shmcb:/var/run/ocsp(128000)

# HSTS (HTTP Strict Transport Security) - 1 year
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"

# Security Headers
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-Content-Type-Options "nosniff"
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"

# Remove server signature
ServerSignature Off
ServerTokens Prod
EOF

# Enable SSL configuration
a2enconf ssl-params

# Update SSL virtual hosts with security headers
for conf in /etc/apache2/sites-available/*-le-ssl.conf; do
    if [ -f "$conf" ]; then
        # Add security headers if not already present
        if ! grep -q "Strict-Transport-Security" "$conf"; then
            sed -i '/<\/VirtualHost>/i \    # Security Headers\n    Include /etc/apache2/conf-available/ssl-params.conf' "$conf"
        fi
    fi
done

# Step 5: Restart Apache
print_status "Restarting Apache..."
systemctl restart apache2

# Step 6: Setup auto-renewal
print_status "Setting up automatic certificate renewal..."
systemctl enable certbot.timer
systemctl start certbot.timer

# Test renewal
certbot renew --dry-run

# Step 7: Update WordPress site URL to HTTPS
print_status "Updating WordPress to use HTTPS..."
mysql torly_wordpress -e "UPDATE wp_options SET option_value = 'https://torly.ai' WHERE option_name IN ('siteurl', 'home');"

# Update wp-config.php to force SSL
sed -i "/WP_ALLOW_MULTISITE/a define('FORCE_SSL_ADMIN', true);\ndefine('FORCE_SSL_LOGIN', true);" /var/www/html/wp-config.php

print_status "✅ SSL/TLS Setup Complete!"
print_status ""
print_status "Certificate Details:"
certbot certificates
print_status ""
print_status "Next steps:"
print_status "  1. Test SSL: https://www.ssllabs.com/ssltest/analyze.html?d=torly.ai"
print_status "  2. Verify HTTPS redirect: curl -I http://torly.ai"
print_status "  3. Check WordPress admin: https://torly.ai/wp-admin"
print_status ""
print_status "SSL certificates will auto-renew via systemd timer"
