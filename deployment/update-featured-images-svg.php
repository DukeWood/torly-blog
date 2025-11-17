<?php
/**
 * Update Featured Images to Custom SVGs
 *
 * Run via WP-CLI: wp eval-file update-featured-images-svg.php --path=/var/www/html
 */

$image_mappings = array(
    'UK Innovator Visa 2026: Complete Guide for Entrepreneurs' => 'innovator-visa-guide.svg',
    'How to Prepare a Winning Business Plan for UK Innovator Visa' => 'business-plan-guide.svg',
    'Top 5 UK Endorsing Bodies for Innovator Visa in 2026' => 'endorsing-bodies.svg',
    'UK Innovator Visa vs Scale-up Visa: Which is Right for You?' => 'visa-comparison.svg',
    'Success Story: From Startup Idea to UK Permanent Residence' => 'success-story.svg',
);

echo "Starting to update featured images with custom SVGs...\n\n";

$theme_dir = get_template_directory();
$svg_dir = $theme_dir . '/assets/blog-covers/';

foreach ($image_mappings as $post_title => $svg_filename) {
    // Find post by title
    $post = get_page_by_title($post_title, OBJECT, 'post');

    if (!$post) {
        echo "❌ Post not found: {$post_title}\n";
        continue;
    }

    $post_id = $post->ID;
    echo "Found post: {$post_title} (ID: {$post_id})\n";

    // Remove old featured image if exists
    if (has_post_thumbnail($post_id)) {
        $old_thumbnail_id = get_post_thumbnail_id($post_id);
        echo "  Removing old featured image (ID: {$old_thumbnail_id})...\n";
        delete_post_thumbnail($post_id);
        // Optionally delete the old attachment
        // wp_delete_attachment($old_thumbnail_id, true);
    }

    // Read SVG file
    $svg_path = $svg_dir . $svg_filename;

    if (!file_exists($svg_path)) {
        echo "  ❌ SVG file not found: {$svg_path}\n\n";
        continue;
    }

    echo "  Reading SVG: {$svg_filename}\n";
    $svg_data = file_get_contents($svg_path);

    if ($svg_data === false) {
        echo "  ❌ Failed to read SVG file\n\n";
        continue;
    }

    // Create unique filename
    $upload_filename = sanitize_title($post_title) . '-cover.svg';

    // Upload to WordPress media library
    $upload = wp_upload_bits($upload_filename, null, $svg_data);

    if ($upload['error']) {
        echo "  ❌ Upload error: {$upload['error']}\n\n";
        continue;
    }

    echo "  ✓ SVG uploaded: {$upload['file']}\n";

    // Create attachment
    $attachment = array(
        'post_mime_type' => 'image/svg+xml',
        'post_title' => $post_title . ' - Cover Image',
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);

    if (is_wp_error($attach_id)) {
        echo "  ❌ Failed to create attachment\n\n";
        continue;
    }

    echo "  ✓ Attachment created (ID: {$attach_id})\n";

    // Set as featured image
    $result = set_post_thumbnail($post_id, $attach_id);

    if ($result) {
        echo "  ✅ SVG featured image set successfully!\n\n";
    } else {
        echo "  ❌ Failed to set featured image\n\n";
    }
}

echo "✅ All done! Custom SVG cover images have been set.\n";
