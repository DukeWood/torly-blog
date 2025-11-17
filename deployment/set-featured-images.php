<?php
/**
 * Set Featured Images for Blog Posts
 *
 * This script downloads images from URLs and sets them as featured images for blog posts
 * Run via WP-CLI: wp eval-file set-featured-images.php --path=/var/www/html
 */

// Post ID to image URL mapping
$post_images = array(
    'UK Innovator Visa 2026: Complete Guide for Entrepreneurs' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200',
    'How to Prepare a Winning Business Plan for UK Innovator Visa' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200',
    'Top 5 UK Endorsing Bodies for Innovator Visa in 2026' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1200',
    'UK Innovator Visa vs Scale-up Visa: Which is Right for You?' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=1200',
    'Success Story: From Startup Idea to UK Permanent Residence' => 'https://images.unsplash.com/photo-1573164574511-73c773193279?w=1200',
);

echo "Starting to set featured images for blog posts...\n\n";

foreach ($post_images as $post_title => $image_url) {
    // Find post by title
    $post = get_page_by_title($post_title, OBJECT, 'post');

    if (!$post) {
        echo "❌ Post not found: {$post_title}\n";
        continue;
    }

    $post_id = $post->ID;
    echo "Found post: {$post_title} (ID: {$post_id})\n";

    // Check if post already has a featured image
    if (has_post_thumbnail($post_id)) {
        echo "  ✓ Post already has featured image, skipping...\n\n";
        continue;
    }

    // Download image
    echo "  Downloading image from: {$image_url}\n";

    // Get image data
    $image_data = file_get_contents($image_url);

    if ($image_data === false) {
        echo "  ❌ Failed to download image\n\n";
        continue;
    }

    // Generate filename
    $filename = sanitize_title($post_title) . '-' . time() . '.jpg';

    // Upload to WordPress
    $upload = wp_upload_bits($filename, null, $image_data);

    if ($upload['error']) {
        echo "  ❌ Upload error: {$upload['error']}\n\n";
        continue;
    }

    echo "  ✓ Image uploaded: {$upload['file']}\n";

    // Create attachment
    $attachment = array(
        'post_mime_type' => 'image/jpeg',
        'post_title' => $post_title,
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);

    if (is_wp_error($attach_id)) {
        echo "  ❌ Failed to create attachment\n\n";
        continue;
    }

    echo "  ✓ Attachment created (ID: {$attach_id})\n";

    // Generate attachment metadata
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);

    // Set as featured image
    $result = set_post_thumbnail($post_id, $attach_id);

    if ($result) {
        echo "  ✅ Featured image set successfully!\n\n";
    } else {
        echo "  ❌ Failed to set featured image\n\n";
    }
}

echo "✅ All done! Featured images have been set.\n";
