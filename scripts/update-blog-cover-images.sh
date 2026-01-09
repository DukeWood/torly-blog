#!/bin/bash

# WordPress Blog Cover Image Update Script
# This script replaces repetitive passport images with diverse, professional imagery

set -e  # Exit on error

echo "═══════════════════════════════════════════════════════════"
echo "    WORDPRESS BLOG COVER IMAGE DIVERSITY UPDATE    "
echo "═══════════════════════════════════════════════════════════"
echo ""

# Configuration
WP_PATH="/var/www/html"
WP_USER="www-data"
IMAGES_DIR="/Users/Jason-uk/AI/AI_Coding/Repositories/torly-wordpress-setup/temp-blog-images"
VM_IP="132.226.239.78"
VM_USER="ubuntu"

echo "Step 1: Uploading images to Oracle VM..."
echo "----------------------------------------"

# Upload images to VM
scp -r "$IMAGES_DIR" "$VM_USER@$VM_IP:/tmp/new-blog-images"

echo "✓ Images uploaded to VM"
echo ""

echo "Step 2: Moving images to WordPress uploads directory..."
echo "--------------------------------------------------------"

# SSH commands to process images
ssh "$VM_USER@$VM_IP" << 'ENDSSH'
    # Move images to WordPress uploads
    sudo mkdir -p /var/www/html/wp-content/uploads/2025/11/diverse-covers
    sudo mv /tmp/new-blog-images/*.jpg /var/www/html/wp-content/uploads/2025/11/diverse-covers/
    sudo chown -R www-data:www-data /var/www/html/wp-content/uploads/2025/11/diverse-covers
    sudo chmod 644 /var/www/html/wp-content/uploads/2025/11/diverse-covers/*.jpg

    echo "✓ Images moved to WordPress uploads directory"
ENDSSH

echo ""
echo "Step 3: Identifying WordPress posts that need new images..."
echo "-----------------------------------------------------------"

# Get list of posts with their current featured images
ssh "$VM_USER@$VM_IP" "sudo -u www-data wp post list --post_type=post --fields=ID,post_title --path=$WP_PATH --format=json" > /tmp/wp-posts.json

echo "✓ Retrieved post list"
echo ""

echo "Step 4: Uploading new images to WordPress media library..."
echo "-----------------------------------------------------------"

# Upload images and get their IDs
declare -A IMAGE_IDS

# Upload business meeting image
BUSINESS_MEETING_ID=$(ssh "$VM_USER@$VM_IP" "sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse-covers/business-meeting.jpg --title='Professional Business Consultation Meeting' --alt='Business professionals in consultation meeting' --path=$WP_PATH --porcelain")
echo "✓ Uploaded: business-meeting.jpg (ID: $BUSINESS_MEETING_ID)"

# Upload AI technology image
AI_TECH_ID=$(ssh "$VM_USER@$VM_IP" "sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse-covers/ai-technology.jpg --title='AI Technology Dashboard Interface' --alt='Modern AI technology dashboard' --path=$WP_PATH --porcelain")
echo "✓ Uploaded: ai-technology.jpg (ID: $AI_TECH_ID)"

# Upload business strategy image
STRATEGY_ID=$(ssh "$VM_USER@$VM_IP" "sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse-covers/business-strategy.jpg --title='Business Planning and Strategy' --alt='Business strategy planning session' --path=$WP_PATH --porcelain")
echo "✓ Uploaded: business-strategy.jpg (ID: $STRATEGY_ID)"

# Upload data analytics image
ANALYTICS_ID=$(ssh "$VM_USER@$VM_IP" "sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse-covers/data-analytics.jpg --title='Data Analytics and Business Metrics' --alt='Data analytics dashboard with charts' --path=$WP_PATH --porcelain")
echo "✓ Uploaded: data-analytics.jpg (ID: $ANALYTICS_ID)"

# Upload London business image
LONDON_ID=$(ssh "$VM_USER@$VM_IP" "sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse-covers/london-business.jpg --title='London Business District' --alt='Modern London business district skyline' --path=$WP_PATH --porcelain")
echo "✓ Uploaded: london-business.jpg (ID: $LONDON_ID)"

# Upload startup team image
STARTUP_ID=$(ssh "$VM_USER@$VM_IP" "sudo -u www-data wp media import /var/www/html/wp-content/uploads/2025/11/diverse-covers/startup-team.jpg --title='Startup Team Collaboration' --alt='Startup team working together' --path=$WP_PATH --porcelain")
echo "✓ Uploaded: startup-team.jpg (ID: $STARTUP_ID)"

echo ""
echo "Step 5: Updating blog post featured images..."
echo "----------------------------------------------"

# Map posts to new images based on titles
# Note: You'll need to get the actual post IDs from your WordPress installation

# Find post by title and update featured image
update_post_image() {
    local search_term="$1"
    local new_image_id="$2"
    local post_id

    post_id=$(ssh "$VM_USER@$VM_IP" "sudo -u www-data wp post list --post_type=post --s='$search_term' --fields=ID --path=$WP_PATH --format=csv | tail -n 1")

    if [ -n "$post_id" ]; then
        ssh "$VM_USER@$VM_IP" "sudo -u www-data wp post meta update $post_id _thumbnail_id $new_image_id --path=$WP_PATH"
        echo "✓ Updated post: $search_term (ID: $post_id) with image ID: $new_image_id"
    else
        echo "⚠ Post not found: $search_term"
    fi
}

# Update posts (matching from screenshots)
update_post_image "AI-Powered UK Innovator Visa Office Hours" "$BUSINESS_MEETING_ID"
update_post_image "Comprehensive AI-Powered Guide to UK Innovator Visa" "$AI_TECH_ID"
update_post_image "Step-by-Step AI-Guided Innovator Visa 2025" "$STRATEGY_ID"
update_post_image "AI-Powered Innovator Founder Visa Outperforms Tier 1" "$ANALYTICS_ID"
update_post_image "UK Innovator Founder Visa 2025: AI-Driven Requirements" "$LONDON_ID"
update_post_image "AI-Assisted Business Setup" "$STARTUP_ID"

echo ""
echo "Step 6: Flushing WordPress cache..."
echo "------------------------------------"

ssh "$VM_USER@$VM_IP" "sudo -u www-data wp cache flush --path=$WP_PATH"
echo "✓ Cache flushed"

echo ""
echo "═══════════════════════════════════════════════════════════"
echo "                   UPDATE COMPLETE                    "
echo "═══════════════════════════════════════════════════════════"
echo ""
echo "Summary:"
echo "--------"
echo "✓ 6 diverse images uploaded to WordPress"
echo "✓ Blog post featured images updated"
echo "✓ Visual diversity improved"
echo ""
echo "Next step: Verify changes at https://torly.ai/blog/"
echo ""
