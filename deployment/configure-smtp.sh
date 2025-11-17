#!/bin/bash

set -e

# Colors
GREEN='\033[0;32m'
NC='\033[0m'

print_status() { echo -e "${GREEN}[INFO]${NC} $1"; }

WP_PATH="/var/www/html"

print_status "Configuring WP Mail SMTP for torly.ai..."

# Configure for main site (torly.ai)
sudo -u www-data wp option update wp_mail_smtp '{"mail":{"from_email":"noreply@innovatorly.ai","from_name":"Torly AI","mailer":"smtp","return_path":true},"smtp":{"host":"smtp.larksuite.com","port":"465","encryption":"ssl","autotls":true,"auth":true,"user":"noreply@innovatorly.ai","pass":"Ll86VT3jPZCoh7yV"}}' --format=json --url=https://torly.ai --path="$WP_PATH"

print_status "Configuring WP Mail SMTP for blog.torly.ai..."

# Configure for blog site (blog.torly.ai)
sudo -u www-data wp option update wp_mail_smtp '{"mail":{"from_email":"noreply@innovatorly.ai","from_name":"Torly AI Blog","mailer":"smtp","return_path":true},"smtp":{"host":"smtp.larksuite.com","port":"465","encryption":"ssl","autotls":true,"auth":true,"user":"noreply@innovatorly.ai","pass":"Ll86VT3jPZCoh7yV"}}' --format=json --url=https://blog.torly.ai --path="$WP_PATH"

print_status "SMTP configuration completed for both sites!"

print_status "Sending test email..."
sudo -u www-data wp eval 'wp_mail("noreply@innovatorly.ai", "WordPress SMTP Test", "This is a test email from WordPress on torly.ai");' --url=https://torly.ai --path="$WP_PATH"

print_status "Test email sent! Please check noreply@innovatorly.ai inbox."
