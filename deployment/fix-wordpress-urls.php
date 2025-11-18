<?php
/**
 * Fix WordPress Site URLs
 *
 * This script fixes broken WordPress site URLs
 *
 * Usage: wp eval-file fix-wordpress-urls.php --path=/var/www/html
 */

echo "=== Fixing WordPress URLs ===\n\n";

// Get current URLs
$current_siteurl = get_option('siteurl');
$current_home = get_option('home');

echo "Current URLs:\n";
echo "- siteurl: " . $current_siteurl . "\n";
echo "- home: " . $current_home . "\n\n";

// Correct URLs
$correct_url = 'https://torly.ai';

// Update URLs using update_option with autoload
echo "Updating URLs to: " . $correct_url . "\n";

$siteurl_updated = update_option('siteurl', $correct_url, 'yes');
$home_updated = update_option('home', $correct_url, 'yes');

if ($siteurl_updated) {
    echo "✓ siteurl updated successfully\n";
} else {
    echo "✗ siteurl update failed (may already be correct)\n";
}

if ($home_updated) {
    echo "✓ home updated successfully\n";
} else {
    echo "✗ home update failed (may already be correct)\n";
}

// Verify the changes
echo "\nVerifying changes:\n";
$new_siteurl = get_option('siteurl');
$new_home = get_option('home');
echo "- siteurl: " . $new_siteurl . "\n";
echo "- home: " . $new_home . "\n";

// Flush rewrite rules
flush_rewrite_rules(true);
echo "\n✓ Rewrite rules flushed\n";

echo "\n=== Fix Complete! ===\n";
