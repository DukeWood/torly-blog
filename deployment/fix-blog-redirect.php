<?php
/**
 * Fix Blog Redirect Issue
 *
 * This script configures WordPress Reading Settings to properly display blog posts at /blog/
 *
 * Usage: wp eval-file fix-blog-redirect.php --path=/var/www/html
 */

echo "=== Fixing Blog Redirect Issue ===\n\n";

// Step 1: Check current Reading Settings
echo "Step 1: Checking current Reading Settings...\n";
$show_on_front = get_option('show_on_front');
$page_on_front = get_option('page_on_front');
$page_for_posts = get_option('page_for_posts');

echo "- show_on_front: " . $show_on_front . "\n";
echo "- page_on_front: " . $page_on_front . "\n";
echo "- page_for_posts: " . $page_for_posts . "\n\n";

// Step 2: Find or create "Blog" page
echo "Step 2: Finding or creating 'Blog' page...\n";
$blog_page = get_page_by_path('blog');

if (!$blog_page) {
    echo "- Blog page not found. Creating new page...\n";

    $blog_page_id = wp_insert_post(array(
        'post_title' => 'Blog',
        'post_name' => 'blog',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_content' => '<!-- Blog posts will be displayed here -->',
        'comment_status' => 'closed',
        'ping_status' => 'closed'
    ));

    if (is_wp_error($blog_page_id)) {
        echo "ERROR: Failed to create blog page - " . $blog_page_id->get_error_message() . "\n";
        exit(1);
    }

    echo "- Blog page created with ID: " . $blog_page_id . "\n";
} else {
    $blog_page_id = $blog_page->ID;
    echo "- Blog page found with ID: " . $blog_page_id . "\n";
}

// Step 3: Find or create homepage
echo "\nStep 3: Finding homepage...\n";
$homepage = get_page_by_path('home');

if (!$homepage) {
    // Check if there's a page with ID 1 or title "Home"
    $homepage = get_post(2); // Usually ID 2 is the first page
    if (!$homepage || $homepage->post_type !== 'page') {
        $homepage_id = 0; // Let WordPress use front-page.php template
        echo "- No specific homepage page found. Will use front-page.php template.\n";
    } else {
        $homepage_id = $homepage->ID;
        echo "- Homepage found with ID: " . $homepage_id . "\n";
    }
} else {
    $homepage_id = $homepage->ID;
    echo "- Homepage found with ID: " . $homepage_id . "\n";
}

// Step 4: Update Reading Settings
echo "\nStep 4: Updating WordPress Reading Settings...\n";

// Set to show a static page on front
update_option('show_on_front', 'page');
echo "- Set 'show_on_front' to 'page'\n";

// Set the Posts page (blog)
update_option('page_for_posts', $blog_page_id);
echo "- Set 'page_for_posts' to: " . $blog_page_id . "\n";

// Set homepage (if we have one)
if ($homepage_id > 0) {
    update_option('page_on_front', $homepage_id);
    echo "- Set 'page_on_front' to: " . $homepage_id . "\n";
}

// Step 5: Flush rewrite rules
echo "\nStep 5: Flushing rewrite rules...\n";
flush_rewrite_rules(true);
echo "- Rewrite rules flushed\n";

// Step 6: Verify configuration
echo "\nStep 6: Verifying configuration...\n";
$show_on_front = get_option('show_on_front');
$page_on_front = get_option('page_on_front');
$page_for_posts = get_option('page_for_posts');

echo "- show_on_front: " . $show_on_front . "\n";
echo "- page_on_front: " . $page_on_front . "\n";
echo "- page_for_posts: " . $page_for_posts . "\n";

// Verify the blog page URL
$blog_url = get_permalink($blog_page_id);
echo "- Blog page URL: " . $blog_url . "\n";

echo "\n=== Blog Redirect Fix Complete! ===\n";
echo "\nThe blog should now be accessible at: https://torly.ai/blog/\n";
echo "Please test the blog link in your browser.\n";
