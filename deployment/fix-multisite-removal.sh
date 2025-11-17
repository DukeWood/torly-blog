#!/bin/bash
set -e

WP_PATH="/var/www/html"
WP_URL="https://torly.ai"

echo "=== Fixing Multisite Removal Issues ==="
echo ""

# 1. Update permalink structure to include /blog/ prefix
echo "1. Updating permalink structure to /blog/%postname%/..."
sudo -u www-data wp option update permalink_structure '/blog/%postname%/' --url="$WP_URL" --path="$WP_PATH"

# 2. Update post GUIDs from blog.torly.ai to torly.ai
echo "2. Updating post GUIDs from blog.torly.ai to torly.ai..."
sudo -u www-data wp db query "UPDATE wp_posts SET guid = REPLACE(guid, 'https://blog.torly.ai/', 'https://torly.ai/blog/');" --url="$WP_URL" --path="$WP_PATH"

# 3. Update any references in post content
echo "3. Updating post content references..."
sudo -u www-data wp search-replace 'https://blog.torly.ai' 'https://torly.ai/blog' --precise --recurse-objects --all-tables --url="$WP_URL" --path="$WP_PATH"

# 4. Update site URL and home URL to ensure consistency
echo "4. Verifying site URLs..."
sudo -u www-data wp option update siteurl 'https://torly.ai' --url="$WP_URL" --path="$WP_PATH"
sudo -u www-data wp option update home 'https://torly.ai' --url="$WP_URL" --path="$WP_PATH"

# 5. Check and move files from multisite upload structure if needed
echo "5. Checking multisite upload directory..."
if [ -d "$WP_PATH/wp-content/uploads/sites/2" ]; then
    echo "   Found multisite uploads in sites/2, copying to main uploads..."
    # Copy files from sites/2 to main uploads (preserve existing)
    sudo cp -rn "$WP_PATH/wp-content/uploads/sites/2/"* "$WP_PATH/wp-content/uploads/" 2>/dev/null || true
    sudo chown -R www-data:www-data "$WP_PATH/wp-content/uploads/"
    echo "   ✓ Files copied"
fi

# 6. Update attachment URLs in database
echo "6. Updating attachment URLs in database..."
sudo -u www-data wp db query "UPDATE wp_posts SET guid = REPLACE(guid, '/sites/2/', '/') WHERE post_type = 'attachment';" --url="$WP_URL" --path="$WP_PATH"

# 7. Update post meta for attachment file paths
echo "7. Updating attachment file paths in post meta..."
sudo -u www-data wp db query "UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, '/sites/2/', '/') WHERE meta_key = '_wp_attached_file';" --url="$WP_URL" --path="$WP_PATH"

# 8. Flush rewrite rules
echo "8. Flushing rewrite rules..."
sudo -u www-data wp rewrite flush --hard --url="$WP_URL" --path="$WP_PATH"

# 9. Verify posts are accessible
echo ""
echo "9. Verifying post URLs..."
sudo -u www-data wp post list --post_type=post --post__in=12,13,14,15,16 --url="$WP_URL" --path="$WP_PATH" --format=table --fields=ID,post_title,url

echo ""
echo "=== Fix Complete! ==="
echo ""
echo "Next step: Disable multisite in wp-config.php"
echo "Note: WordPress is still in multisite mode. After verifying everything works,"
echo "you can disable it by removing/commenting out these lines in wp-config.php:"
echo "  - define( 'MULTISITE', true );"
echo "  - define( 'SUBDOMAIN_INSTALL', true );"
echo "  - define( 'DOMAIN_CURRENT_SITE', 'torly.ai' );"
echo "  - define( 'PATH_CURRENT_SITE', '/' );"
echo ""
