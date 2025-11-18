<?php
/**
 * TorlyAI WordPress Theme Functions
 * 
 * @package TorlyAI
 * @version 1.0.0
 */

// Theme Setup
function torlyai_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'torlyai'),
        'footer' => __('Footer Menu', 'torlyai'),
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'torlyai_theme_setup');

// Enable SVG Upload Support
function torlyai_enable_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'torlyai_enable_svg_upload');

// Fix SVG display in media library
function torlyai_fix_svg_display($response, $attachment, $meta) {
    if ($response['mime'] === 'image/svg+xml' && empty($response['sizes'])) {
        $svg_path = get_attached_file($attachment->ID);
        if (file_exists($svg_path)) {
            $svg_content = file_get_contents($svg_path);
            if (preg_match('/viewBox=["\']([0-9\s\.]+)["\']/i', $svg_content, $matches)) {
                $viewbox = explode(' ', $matches[1]);
                if (count($viewbox) == 4) {
                    $response['sizes'] = array(
                        'full' => array(
                            'url' => $response['url'],
                            'width' => intval($viewbox[2]),
                            'height' => intval($viewbox[3]),
                            'orientation' => intval($viewbox[2]) > intval($viewbox[3]) ? 'landscape' : 'portrait'
                        )
                    );
                }
            }
        }
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'torlyai_fix_svg_display', 10, 3);

// Force HTTPS in all URLs
function torlyai_force_https() {
    if (!is_ssl() && !is_admin()) {
        wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);
        exit();
    }
}
add_action('template_redirect', 'torlyai_force_https');

// Validate and fix WordPress site URLs (Prevention for URL corruption issue - Nov 2025)
function torlyai_validate_and_fix_urls() {
    $correct_url = 'https://torly.ai';
    $siteurl = get_option('siteurl');
    $home = get_option('home');

    $siteurl_needs_fix = false;
    $home_needs_fix = false;

    // Check if URLs are corrupted or incorrect
    if ($siteurl !== $correct_url) {
        // Check for common corruption patterns
        if (empty($siteurl) || $siteurl === 'https:' || $siteurl === 'http:' ||
            strpos($siteurl, 'torly.ai') === false) {
            $siteurl_needs_fix = true;
        }
        // Also fix http:// to https://
        elseif (strpos($siteurl, 'http://') === 0) {
            $siteurl_needs_fix = true;
        }
    }

    if ($home !== $correct_url) {
        if (empty($home) || $home === 'https:' || $home === 'http:' ||
            strpos($home, 'torly.ai') === false) {
            $home_needs_fix = true;
        }
        elseif (strpos($home, 'http://') === 0) {
            $home_needs_fix = true;
        }
    }

    // Apply fixes if needed
    if ($siteurl_needs_fix || $home_needs_fix) {
        // Use direct database update for reliability
        global $wpdb;

        if ($siteurl_needs_fix) {
            $wpdb->update(
                $wpdb->options,
                array('option_value' => $correct_url),
                array('option_name' => 'siteurl'),
                array('%s'),
                array('%s')
            );
            error_log('TorlyAI: Auto-fixed corrupted siteurl from "' . $siteurl . '" to "' . $correct_url . '"');
        }

        if ($home_needs_fix) {
            $wpdb->update(
                $wpdb->options,
                array('option_value' => $correct_url),
                array('option_name' => 'home'),
                array('%s'),
                array('%s')
            );
            error_log('TorlyAI: Auto-fixed corrupted home URL from "' . $home . '" to "' . $correct_url . '"');
        }

        // Flush rewrite rules after fixing
        flush_rewrite_rules(true);
    }
}
// Run on admin_init and init to catch corruption early
add_action('admin_init', 'torlyai_validate_and_fix_urls');
add_action('init', 'torlyai_validate_and_fix_urls');

// Enqueue Scripts and Styles
function torlyai_enqueue_scripts() {
    // Theme stylesheet (v2.1.0 - Added mobile hamburger menu)
    wp_enqueue_style('torlyai-style', get_stylesheet_uri(), array(), '2.1.0');

    // Custom JavaScript (v2.1.0 - Enhanced mobile menu with accessibility)
    wp_enqueue_script('torlyai-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '2.1.0', true);

    // Localize script for AJAX
    wp_localize_script('torlyai-script', 'torlyai_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('torlyai_nonce'),
        'api_endpoint' => home_url('/wp-json/torlyai/v1/')
    ));
}
add_action('wp_enqueue_scripts', 'torlyai_enqueue_scripts');

// Register Custom Post Types
function torlyai_register_post_types() {
    // Services Post Type
    register_post_type('services', array(
        'labels' => array(
            'name' => __('Services', 'torlyai'),
            'singular_name' => __('Service', 'torlyai'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => array('slug' => 'services'),
        'show_in_rest' => true,
    ));
    
    // Visa Resources Post Type
    register_post_type('visa_resources', array(
        'labels' => array(
            'name' => __('Visa Resources', 'torlyai'),
            'singular_name' => __('Visa Resource', 'torlyai'),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-media-document',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => array('slug' => 'visa-resources'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'torlyai_register_post_types');

// Register Sidebars
function torlyai_register_sidebars() {
    register_sidebar(array(
        'name' => __('Blog Sidebar', 'torlyai'),
        'id' => 'blog-sidebar',
        'description' => __('Sidebar for blog posts and archive pages', 'torlyai'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'torlyai_register_sidebars');

// API Permission Callbacks
function torlyai_verify_nonce_permission($request) {
    // Check for WordPress nonce in header or body
    $nonce = $request->get_header('X-WP-Nonce');

    if (empty($nonce)) {
        $nonce = $request->get_param('_wpnonce');
    }

    if (empty($nonce)) {
        return new WP_Error(
            'rest_forbidden',
            __('Authentication required. Missing nonce.', 'torlyai'),
            array('status' => 401)
        );
    }

    // Verify nonce
    if (!wp_verify_nonce($nonce, 'wp_rest')) {
        return new WP_Error(
            'rest_forbidden',
            __('Invalid nonce. Authentication failed.', 'torlyai'),
            array('status' => 403)
        );
    }

    // Rate limiting check
    $rate_limit_check = torlyai_check_rate_limit($request);
    if (is_wp_error($rate_limit_check)) {
        return $rate_limit_check;
    }

    return true;
}

// Rate limiting function
function torlyai_check_rate_limit($request) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $endpoint = $request->get_route();

    // Get rate limit data from transients
    $rate_limit_key = 'torlyai_rate_limit_' . md5($ip_address . $endpoint);
    $requests = get_transient($rate_limit_key);

    // Rate limits: 10 requests per minute for POST endpoints
    $max_requests = 10;
    $time_window = 60; // seconds

    if ($requests === false) {
        // First request
        set_transient($rate_limit_key, 1, $time_window);
        return true;
    }

    if ($requests >= $max_requests) {
        return new WP_Error(
            'rest_rate_limited',
            __('Too many requests. Please try again later.', 'torlyai'),
            array('status' => 429)
        );
    }

    // Increment request count
    set_transient($rate_limit_key, $requests + 1, $time_window);
    return true;
}

// Public permission callback (for blog-stats - read-only)
function torlyai_public_permission() {
    // Public endpoint, but still rate-limited
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'torlyai_public_rate_' . md5($ip_address);
    $requests = get_transient($rate_limit_key);

    // More lenient rate limit for public endpoint: 30 requests per minute
    if ($requests === false) {
        set_transient($rate_limit_key, 1, 60);
        return true;
    }

    if ($requests >= 30) {
        return new WP_Error(
            'rest_rate_limited',
            __('Too many requests. Please try again later.', 'torlyai'),
            array('status' => 429)
        );
    }

    set_transient($rate_limit_key, $requests + 1, 60);
    return true;
}

// Custom REST API Endpoints
function torlyai_register_api_routes() {
    // Register custom API namespace
    register_rest_route('torlyai/v1', '/visa-assessment', array(
        'methods' => 'POST',
        'callback' => 'torlyai_visa_assessment_callback',
        'permission_callback' => 'torlyai_verify_nonce_permission',
    ));

    register_rest_route('torlyai/v1', '/contact-form', array(
        'methods' => 'POST',
        'callback' => 'torlyai_contact_form_callback',
        'permission_callback' => 'torlyai_verify_nonce_permission',
    ));

    register_rest_route('torlyai/v1', '/blog-stats', array(
        'methods' => 'GET',
        'callback' => 'torlyai_blog_stats_callback',
        'permission_callback' => 'torlyai_public_permission',
    ));

    // GEO Optimization: Visa Requirements API (for AI engines)
    register_rest_route('torlyai/v1', '/visa-requirements', array(
        'methods' => 'GET',
        'callback' => 'torlyai_visa_requirements_callback',
        'permission_callback' => 'torlyai_public_permission',
    ));

    // GEO Optimization: Endorsing Bodies API (for AI engines)
    register_rest_route('torlyai/v1', '/endorsing-bodies', array(
        'methods' => 'GET',
        'callback' => 'torlyai_endorsing_bodies_callback',
        'permission_callback' => 'torlyai_public_permission',
    ));
}
add_action('rest_api_init', 'torlyai_register_api_routes');

// API Callback Functions
function torlyai_visa_assessment_callback($request) {
    $params = $request->get_json_params();
    
    // Process visa assessment data
    $assessment_result = array(
        'status' => 'success',
        'score' => calculate_visa_score($params),
        'recommendations' => get_visa_recommendations($params),
        'timestamp' => current_time('mysql'),
    );
    
    // Store in database if needed
    if (isset($params['email'])) {
        store_assessment_result($params['email'], $assessment_result);
    }
    
    return new WP_REST_Response($assessment_result, 200);
}

function torlyai_contact_form_callback($request) {
    $params = $request->get_json_params();
    
    // Validate required fields
    if (empty($params['name']) || empty($params['email']) || empty($params['message'])) {
        return new WP_REST_Response(array('error' => 'Missing required fields'), 400);
    }
    
    // Send email
    $to = get_option('admin_email');
    $subject = 'New Contact Form Submission from TorlyAI';
    $message = sprintf(
        "Name: %s\nEmail: %s\nMessage:\n%s",
        sanitize_text_field($params['name']),
        sanitize_email($params['email']),
        sanitize_textarea_field($params['message'])
    );
    
    $sent = wp_mail($to, $subject, $message);
    
    return new WP_REST_Response(array(
        'status' => $sent ? 'success' : 'error',
        'message' => $sent ? 'Message sent successfully' : 'Failed to send message'
    ), $sent ? 200 : 500);
}

function torlyai_blog_stats_callback() {
    $stats = array(
        'total_posts' => wp_count_posts('post')->publish,
        'total_categories' => wp_count_terms('category'),
        'total_comments' => wp_count_comments()->approved,
        'recent_posts' => get_recent_posts_data(5),
    );

    return new WP_REST_Response($stats, 200);
}

// GEO Optimization: Visa Requirements API Callback
function torlyai_visa_requirements_callback() {
    $requirements = array(
        'visa_name' => 'UK Innovator Founder Visa',
        'official_name' => 'Innovator Founder Visa',
        'previous_names' => array('Innovator Visa (pre-2023)'),
        'year' => '2026',
        'minimum_investment' => array(
            'amount' => 50000,
            'currency' => 'GBP',
            'description' => 'Minimum £50,000 investment in the business'
        ),
        'costs' => array(
            'visa_application_fee' => array('amount' => 1191, 'currency' => 'GBP'),
            'immigration_health_surcharge' => array('amount' => 3105, 'currency' => 'GBP', 'duration' => '3 years'),
            'endorsement_fee' => array('min' => 500, 'max' => 1500, 'currency' => 'GBP'),
            'english_test' => array('min' => 150, 'max' => 200, 'currency' => 'GBP'),
            'total_minimum' => array('amount' => 54796, 'currency' => 'GBP'),
            'total_maximum' => array('amount' => 55796, 'currency' => 'GBP')
        ),
        'timeline' => array(
            'total_weeks' => array('min' => 18, 'max' => 24),
            'endorsement_stage' => array('min' => 6, 'max' => 8, 'unit' => 'weeks'),
            'visa_application_stage' => array('min' => 12, 'max' => 16, 'unit' => 'weeks')
        ),
        'requirements' => array(
            'age' => array('minimum' => 18, 'description' => 'At least 18 years old'),
            'innovation' => 'Business must be innovative, new to UK market',
            'investment' => '£50,000 minimum',
            'endorsement' => 'Required from one of 8 authorized endorsing bodies',
            'english' => 'B2 level English language requirement',
            'maintenance' => array('amount' => 1270, 'currency' => 'GBP', 'description' => 'Minimum personal savings'),
            'experience' => 'Business experience or relevant skills'
        ),
        'home_office_criteria' => array(
            'innovation' => 'Is it new to the UK market?',
            'viability' => 'Will it work?',
            'scalability' => 'Can it grow?'
        ),
        'success_rate' => array(
            'overall' => 0.85,
            'description' => '85% approval rate for well-prepared applications',
            'year' => 2025
        ),
        'duration' => array(
            'initial' => array('years' => 3, 'description' => '3 years initially, renewable'),
            'settlement' => array('years' => 3, 'description' => 'Eligible for permanent residence after 3 years')
        ),
        'sources' => array(
            array(
                'title' => 'UK Home Office - Innovator Founder Visa',
                'url' => 'https://www.gov.uk/innovator-founder-visa'
            ),
            array(
                'title' => 'UK Home Office - Immigration Statistics 2025',
                'url' => 'https://www.gov.uk/government/statistics/immigration-statistics-year-ending-september-2025'
            )
        ),
        'last_updated' => '2026-01-18',
        'api_version' => '1.0'
    );

    return new WP_REST_Response($requirements, 200);
}

// GEO Optimization: Endorsing Bodies API Callback
function torlyai_endorsing_bodies_callback() {
    $endorsing_bodies = array(
        'total_count' => 8,
        'year' => 2026,
        'bodies' => array(
            array(
                'id' => 1,
                'name' => 'Tech Nation',
                'focus' => 'Technology and digital businesses',
                'success_rate' => array('min' => 0.75, 'max' => 0.80),
                'processing_time' => array('min' => 4, 'max' => 6, 'unit' => 'weeks'),
                'fee_range' => array('min' => 500, 'max' => 1000, 'currency' => 'GBP'),
                'website' => 'https://technation.io'
            ),
            array(
                'id' => 2,
                'name' => 'Innovate UK',
                'focus' => 'Innovation across all sectors',
                'success_rate' => array('min' => 0.80, 'max' => 0.85),
                'processing_time' => array('min' => 8, 'max' => 10, 'unit' => 'weeks'),
                'fee_range' => array('min' => 1000, 'max' => 1500, 'currency' => 'GBP'),
                'website' => 'https://www.ukri.org/councils/innovate-uk'
            ),
            array(
                'id' => 3,
                'name' => 'The Global Entrepreneurs Programme',
                'focus' => 'High-growth businesses',
                'success_rate' => array('min' => 0.80, 'max' => 0.85),
                'processing_time' => array('min' => 4, 'max' => 6, 'unit' => 'weeks'),
                'fee_range' => array('min' => 800, 'max' => 1200, 'currency' => 'GBP'),
                'website' => 'https://www.gep.london'
            ),
            array(
                'id' => 4,
                'name' => 'Envestors Limited',
                'focus' => 'Investment-ready businesses',
                'success_rate' => array('min' => 0.75, 'max' => 0.80),
                'processing_time' => array('min' => 6, 'max' => 8, 'unit' => 'weeks'),
                'fee_range' => array('min' => 1000, 'max' => 1500, 'currency' => 'GBP'),
                'website' => 'https://www.envestors.co.uk'
            ),
            array(
                'id' => 5,
                'name' => 'UK Endorsing Services (UKES)',
                'focus' => 'General businesses',
                'success_rate' => array('min' => 0.70, 'max' => 0.75),
                'processing_time' => array('min' => 6, 'max' => 8, 'unit' => 'weeks'),
                'fee_range' => array('min' => 500, 'max' => 800, 'currency' => 'GBP'),
                'website' => 'https://www.endorsingbodies.co.uk'
            ),
            array(
                'id' => 6,
                'name' => 'British Business Bank',
                'focus' => 'Scalable businesses',
                'success_rate' => array('min' => 0.75, 'max' => 0.80),
                'processing_time' => array('min' => 8, 'max' => 10, 'unit' => 'weeks'),
                'fee_range' => array('min' => 1000, 'max' => 1500, 'currency' => 'GBP'),
                'website' => 'https://www.british-business-bank.co.uk'
            ),
            array(
                'id' => 7,
                'name' => 'CityFibre',
                'focus' => 'Infrastructure and connectivity',
                'success_rate' => array('min' => 0.75, 'max' => 0.80),
                'processing_time' => array('min' => 6, 'max' => 8, 'unit' => 'weeks'),
                'fee_range' => array('min' => 800, 'max' => 1200, 'currency' => 'GBP'),
                'website' => 'https://www.cityfibre.com'
            ),
            array(
                'id' => 8,
                'name' => 'London & Partners',
                'focus' => 'London-based businesses',
                'success_rate' => array('min' => 0.75, 'max' => 0.80),
                'processing_time' => array('min' => 6, 'max' => 8, 'unit' => 'weeks'),
                'fee_range' => array('min' => 800, 'max' => 1200, 'currency' => 'GBP'),
                'website' => 'https://www.londonandpartners.com'
            )
        ),
        'sources' => array(
            array(
                'title' => 'UK Home Office - Endorsing Bodies List',
                'url' => 'https://www.gov.uk/government/publications/endorsing-bodies-innovator-founder'
            )
        ),
        'last_updated' => '2026-01-18',
        'api_version' => '1.0'
    );

    return new WP_REST_Response($endorsing_bodies, 200);
}

// Helper Functions
function calculate_visa_score($data) {
    $score = 0;
    
    // Innovation score calculation
    if (isset($data['innovation_factors'])) {
        $score += count($data['innovation_factors']) * 10;
    }
    
    // Viability score calculation
    if (isset($data['business_plan']) && $data['business_plan']) {
        $score += 20;
    }
    
    // Scalability score calculation
    if (isset($data['growth_potential']) && $data['growth_potential'] > 50) {
        $score += 30;
    }
    
    return min($score, 100); // Cap at 100
}

function get_visa_recommendations($data) {
    $recommendations = array();
    
    if (!isset($data['business_plan']) || !$data['business_plan']) {
        $recommendations[] = 'Develop a comprehensive business plan';
    }
    
    if (!isset($data['innovation_factors']) || count($data['innovation_factors']) < 3) {
        $recommendations[] = 'Strengthen innovation aspects of your business';
    }
    
    return $recommendations;
}

function store_assessment_result($email, $result) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'visa_assessments';
    
    $wpdb->insert(
        $table_name,
        array(
            'email' => $email,
            'assessment_data' => json_encode($result),
            'created_at' => current_time('mysql'),
        )
    );
}

function get_recent_posts_data($count = 5) {
    $recent_posts = wp_get_recent_posts(array(
        'numberposts' => $count,
        'post_status' => 'publish',
    ));
    
    $posts_data = array();
    foreach ($recent_posts as $post) {
        $posts_data[] = array(
            'id' => $post['ID'],
            'title' => $post['post_title'],
            'date' => $post['post_date'],
            'url' => get_permalink($post['ID']),
        );
    }
    
    return $posts_data;
}

// GoDaddy API Integration
class TorlyAI_GoDaddy_Integration {
    private $api_key;
    private $api_secret;
    private $api_url = 'https://api.godaddy.com/v1/';
    
    public function __construct() {
        $this->api_key = get_option('torlyai_godaddy_api_key');
        $this->api_secret = get_option('torlyai_godaddy_api_secret');
    }
    
    public function get_domain_info($domain) {
        $endpoint = $this->api_url . 'domains/' . $domain;
        
        $response = wp_remote_get($endpoint, array(
            'headers' => array(
                'Authorization' => 'sso-key ' . $this->api_key . ':' . $this->api_secret,
                'Content-Type' => 'application/json',
            ),
        ));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        return json_decode(wp_remote_retrieve_body($response), true);
    }
    
    public function update_dns_records($domain, $records) {
        $endpoint = $this->api_url . 'domains/' . $domain . '/records';
        
        $response = wp_remote_request($endpoint, array(
            'method' => 'PATCH',
            'headers' => array(
                'Authorization' => 'sso-key ' . $this->api_key . ':' . $this->api_secret,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($records),
        ));
        
        return !is_wp_error($response);
    }
}

// WordPress Admin Customizations
function torlyai_admin_menu() {
    add_menu_page(
        'TorlyAI Settings',
        'TorlyAI',
        'manage_options',
        'torlyai-settings',
        'torlyai_settings_page',
        'dashicons-admin-generic',
        30
    );
}
add_action('admin_menu', 'torlyai_admin_menu');

function torlyai_settings_page() {
    ?>
    <div class="wrap">
        <h1>TorlyAI Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('torlyai_settings'); ?>
            <?php do_settings_sections('torlyai_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">GoDaddy API Key</th>
                    <td>
                        <input type="text" name="torlyai_godaddy_api_key" value="<?php echo esc_attr(get_option('torlyai_godaddy_api_key')); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">GoDaddy API Secret</th>
                    <td>
                        <input type="password" name="torlyai_godaddy_api_secret" value="<?php echo esc_attr(get_option('torlyai_godaddy_api_secret')); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Enable MCP Integration</th>
                    <td>
                        <input type="checkbox" name="torlyai_mcp_enabled" value="1" <?php checked(1, get_option('torlyai_mcp_enabled'), true); ?> />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Google Analytics 4 Measurement ID</th>
                    <td>
                        <input type="text" name="torlyai_ga_measurement_id" value="<?php echo esc_attr(get_option('torlyai_ga_measurement_id')); ?>" placeholder="G-XXXXXXXXXX" class="regular-text" />
                        <p class="description">Enter your GA4 Measurement ID (format: G-XXXXXXXXXX). Tracks CTA clicks, form submissions, scroll depth, and outbound links.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
function torlyai_register_settings() {
    register_setting('torlyai_settings', 'torlyai_godaddy_api_key');
    register_setting('torlyai_settings', 'torlyai_godaddy_api_secret');
    register_setting('torlyai_settings', 'torlyai_mcp_enabled');
    register_setting('torlyai_settings', 'torlyai_ga_measurement_id', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => ''
    ));
}
add_action('admin_init', 'torlyai_register_settings');

// Create default blog categories and tags
function torlyai_create_default_taxonomy() {
    // Create default categories
    $categories = array(
        'UK Visa Guide' => 'Comprehensive guides for UK visa applications',
        'Innovator Visa' => 'Information and resources for Innovator Visa applicants',
        'Business Immigration' => 'Business immigration news and updates',
        'Success Stories' => 'Success stories from our clients',
    );

    foreach ($categories as $cat_name => $cat_description) {
        if (!term_exists($cat_name, 'category')) {
            wp_insert_term(
                $cat_name,
                'category',
                array('description' => $cat_description)
            );
        }
    }

    // Create default tags
    $tags = array(
        'UK Immigration',
        'Startup Visa',
        'Business Plan',
        'Endorsement',
        'Visa Application',
        'Entrepreneur',
        'Innovation',
        'Scale-up Visa',
    );

    foreach ($tags as $tag_name) {
        if (!term_exists($tag_name, 'post_tag')) {
            wp_insert_term($tag_name, 'post_tag');
        }
    }
}
add_action('after_switch_theme', 'torlyai_create_default_taxonomy');

// Create database tables on theme activation
function torlyai_create_tables() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'visa_assessments';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        assessment_data text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'torlyai_create_tables');

// Add FAQ Schema to Blog Posts (SEO/GEO Optimization)
function torlyai_add_faq_schema_to_posts($content) {
    // Only run on single post pages, not pages
    if (!is_single() || is_page()) {
        return $content;
    }

    global $post;

    // Check if post is in visa-related categories
    $categories = get_the_category($post->ID);
    $visa_categories = array('UK Visa Guide', 'Innovator Visa', 'Business Immigration', 'How To Guides', 'Success Stories');
    $is_visa_post = false;

    foreach ($categories as $category) {
        if (in_array($category->name, $visa_categories)) {
            $is_visa_post = true;
            break;
        }
    }

    // Only add FAQ to visa-related posts
    if (!$is_visa_post) {
        return $content;
    }

    // Common FAQ questions for visa posts
    $faq_data = array(
        array(
            'question' => 'What is the UK Innovator Founder Visa?',
            'answer' => 'The UK Innovator Founder Visa (2026) is an immigration route for experienced entrepreneurs who want to establish an innovative, viable, and scalable business in the United Kingdom. It requires a minimum investment of £50,000 and endorsement from an approved body. <em>(Previously called "Innovator Visa" before 2023 reform.)</em><br><br><cite>Source: <a href="https://www.gov.uk/innovator-founder-visa" target="_blank" rel="noopener">UK Home Office</a></cite>'
        ),
        array(
            'question' => 'How much does the UK Innovator Founder Visa cost?',
            'answer' => '<strong>Total costs (2026):</strong><ul><li>Visa application fee: £1,191</li><li>Immigration Health Surcharge: £3,105 (3 years)</li><li>Minimum business investment: £50,000</li><li>Endorsement body fee: £500 - £1,500</li><li>English language test: £150 - £200</li></ul><strong>Minimum Total: £54,796 - £55,796</strong><br><br><cite>Source: <a href="https://www.gov.uk/innovator-founder-visa" target="_blank" rel="noopener">UK Home Office</a></cite>'
        ),
        array(
            'question' => 'How long does the UK Innovator Founder Visa application take?',
            'answer' => '<strong>Total Timeline: 18-24 weeks</strong><ul><li>Stage 1 (Endorsement): 6-8 weeks</li><li>Stage 2 (Visa Application): 12-16 weeks</li></ul>TorlyAI helps you prepare endorsement documents in days, not weeks.'
        ),
        array(
            'question' => 'What are the key requirements for UK Innovator Founder Visa?',
            'answer' => '<strong>You must meet ALL of these criteria:</strong><ul><li>At least 18 years old</li><li>Innovative business idea new to UK market</li><li>£50,000 minimum investment</li><li>Endorsement from approved body</li><li>English language (B2 level)</li><li>Sufficient personal savings (£1,270+)</li><li>Business experience or relevant skills</li></ul>'
        ),
        array(
            'question' => 'Which endorsing bodies are authorized for UK Innovator Founder Visa?',
            'answer' => '<strong>8 authorized endorsing bodies (2026):</strong><ol><li>Tech Nation - Technology and digital businesses</li><li>Innovate UK - Innovation across all sectors</li><li>The Global Entrepreneurs Programme - High-growth businesses</li><li>Envestors Limited - Investment-ready businesses</li><li>UK Endorsing Services (UKES) - General businesses</li><li>British Business Bank - Scalable businesses</li><li>CityFibre - Infrastructure and connectivity</li><li>London & Partners - London-based businesses</li></ol>TorlyAI recommends the best fit based on your industry and business stage.'
        )
    );

    // Generate FAQ Schema.org markup
    $faq_html = '<div itemscope itemtype="https://schema.org/FAQPage" class="blog-post-faq" style="margin-top: 4rem; padding: 2rem; background: linear-gradient(135deg, #f6f9fc 0%, #ffffff 100%); border-radius: 12px;">';
    $faq_html .= '<h2 style="font-size: 1.75rem; margin-bottom: 2rem; text-align: center;">Frequently Asked Questions</h2>';

    foreach ($faq_data as $faq) {
        $faq_html .= '<div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem;">';
        $faq_html .= '<h3 itemprop="name" class="faq-question" style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 1rem;">' . esc_html($faq['question']) . '</h3>';
        $faq_html .= '<div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">';
        $faq_html .= '<div itemprop="text" class="faq-answer" style="color: #4b5563; line-height: 1.7;">' . $faq['answer'] . '</div>';
        $faq_html .= '</div></div>';
    }

    $faq_html .= '<div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">';
    $faq_html .= '<p style="font-size: 1.125rem; color: #6b7280; margin-bottom: 1rem;">Need personalized guidance for your UK Innovator Founder Visa application?</p>';
    $faq_html .= '<a href="/visa-assessment" style="display: inline-block; background: var(--color-chat-green); color: white; padding: 0.875rem 2rem; border-radius: 9999px; text-decoration: none; font-weight: 600;">Start Free Assessment</a>';
    $faq_html .= '</div></div>';

    // Append FAQ to post content
    return $content . $faq_html;
}
add_filter('the_content', 'torlyai_add_faq_schema_to_posts');

// Cleanup on theme deactivation
function torlyai_cleanup() {
    // Clean up transients and temporary data
    delete_transient('torlyai_cache');
}
add_action('switch_theme', 'torlyai_cleanup');