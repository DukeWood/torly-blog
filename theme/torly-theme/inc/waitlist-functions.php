<?php
/**
 * TorlyAI Waitlist Functions
 * Backend handlers for waitlist modal
 *
 * @package TorlyAI
 * @version 2.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// ============================================
// DATABASE SETUP
// ============================================

/**
 * Create waitlist database table
 */
function torlyai_create_waitlist_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'torlyai_waitlist';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        location varchar(100) DEFAULT NULL,
        stage varchar(50) DEFAULT NULL,
        timeline varchar(50) DEFAULT NULL,
        ip_address varchar(45) DEFAULT NULL,
        user_agent text DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        UNIQUE KEY email (email),
        KEY created_at (created_at)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Create table on theme activation
add_action('after_switch_theme', 'torlyai_create_waitlist_table');

// ============================================
// AJAX HANDLERS
// ============================================

/**
 * Handle waitlist email signup via AJAX
 */
function torlyai_ajax_waitlist_signup() {
    // Verify nonce
    check_ajax_referer('torlyai_waitlist_nonce', 'nonce');

    // Get email
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

    // Validate email
    if (empty($email) || !is_email($email)) {
        error_log('TorlyAI Waitlist: Invalid email submitted: ' . $email);
        wp_send_json_error(['message' => 'Invalid email address']);
        return;
    }

    // Save to database
    global $wpdb;
    $table_name = $wpdb->prefix . 'torlyai_waitlist';

    // Check if email already exists
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE email = %s",
        $email
    ));

    if ($existing) {
        // Email already exists - still return success
        error_log('TorlyAI Waitlist: Duplicate signup attempt for existing email: ' . $email);
        wp_send_json_success([
            'message' => 'You are already on the waitlist!',
            'existing' => true
        ]);
        return;
    }

    // Insert new entry
    $result = $wpdb->insert(
        $table_name,
        [
            'email' => $email,
            'ip_address' => torlyai_get_client_ip(),
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 255) : ''
        ],
        ['%s', '%s', '%s']
    );

    if ($result === false) {
        error_log('TorlyAI Waitlist: Database insert failed for email: ' . $email . ' | Error: ' . $wpdb->last_error);
        wp_send_json_error(['message' => 'Database error. Please try again.']);
        return;
    } else {
        error_log('TorlyAI Waitlist: Successfully added email: ' . $email . ' | ID: ' . $wpdb->insert_id);
    }

    // Send confirmation email
    torlyai_send_waitlist_confirmation_email($email);

    // Send notification to admin
    torlyai_send_admin_waitlist_notification($email);

    wp_send_json_success([
        'message' => 'Successfully added to waitlist!',
        'id' => $wpdb->insert_id
    ]);
}

add_action('wp_ajax_torlyai_waitlist_signup', 'torlyai_ajax_waitlist_signup');
add_action('wp_ajax_nopriv_torlyai_waitlist_signup', 'torlyai_ajax_waitlist_signup');

/**
 * Handle waitlist survey submission via AJAX
 */
function torlyai_ajax_waitlist_survey() {
    // Verify nonce
    check_ajax_referer('torlyai_waitlist_nonce', 'nonce');

    // Get survey data
    $data_json = isset($_POST['data']) ? $_POST['data'] : '';
    $data = json_decode(stripslashes($data_json), true);

    if (empty($data) || empty($data['email'])) {
        wp_send_json_error(['message' => 'Invalid data']);
        return;
    }

    // Sanitize data
    $email = sanitize_email($data['email']);
    $location = isset($data['location']) ? sanitize_text_field($data['location']) : '';
    $stage = isset($data['stage']) ? sanitize_text_field($data['stage']) : '';
    $timeline = isset($data['timeline']) ? sanitize_text_field($data['timeline']) : '';

    // Update database
    global $wpdb;
    $table_name = $wpdb->prefix . 'torlyai_waitlist';

    $result = $wpdb->update(
        $table_name,
        [
            'location' => $location,
            'stage' => $stage,
            'timeline' => $timeline
        ],
        ['email' => $email],
        ['%s', '%s', '%s'],
        ['%s']
    );

    if ($result === false) {
        wp_send_json_error(['message' => 'Database error. Please try again.']);
        return;
    }

    wp_send_json_success(['message' => 'Survey data saved successfully!']);
}

add_action('wp_ajax_torlyai_waitlist_survey', 'torlyai_ajax_waitlist_survey');
add_action('wp_ajax_nopriv_torlyai_waitlist_survey', 'torlyai_ajax_waitlist_survey');

// ============================================
// REST API ENDPOINTS (Alternative to AJAX)
// ============================================

/**
 * Register REST API routes
 */
function torlyai_register_waitlist_routes() {
    register_rest_route('torlyai/v1', '/waitlist', [
        'methods' => 'POST',
        'callback' => 'torlyai_rest_waitlist_signup',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('torlyai/v1', '/waitlist/survey', [
        'methods' => 'POST',
        'callback' => 'torlyai_rest_waitlist_survey',
        'permission_callback' => '__return_true'
    ]);
}

add_action('rest_api_init', 'torlyai_register_waitlist_routes');

/**
 * REST API handler for email signup
 */
function torlyai_rest_waitlist_signup(WP_REST_Request $request) {
    $email = sanitize_email($request->get_param('email'));

    if (empty($email) || !is_email($email)) {
        return new WP_Error('invalid_email', 'Invalid email address', ['status' => 400]);
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'torlyai_waitlist';

    // Check if exists
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE email = %s",
        $email
    ));

    if ($existing) {
        return new WP_REST_Response([
            'success' => true,
            'message' => 'You are already on the waitlist!',
            'existing' => true
        ], 200);
    }

    // Insert
    $result = $wpdb->insert(
        $table_name,
        [
            'email' => $email,
            'ip_address' => torlyai_get_client_ip(),
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 255) : ''
        ],
        ['%s', '%s', '%s']
    );

    if ($result === false) {
        return new WP_Error('database_error', 'Database error', ['status' => 500]);
    }

    // Send emails
    torlyai_send_waitlist_confirmation_email($email);
    torlyai_send_admin_waitlist_notification($email);

    return new WP_REST_Response([
        'success' => true,
        'message' => 'Successfully added to waitlist!',
        'id' => $wpdb->insert_id
    ], 201);
}

/**
 * REST API handler for survey submission
 */
function torlyai_rest_waitlist_survey(WP_REST_Request $request) {
    $email = sanitize_email($request->get_param('email'));
    $location = sanitize_text_field($request->get_param('location'));
    $stage = sanitize_text_field($request->get_param('stage'));
    $timeline = sanitize_text_field($request->get_param('timeline'));

    if (empty($email) || !is_email($email)) {
        return new WP_Error('invalid_email', 'Invalid email address', ['status' => 400]);
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'torlyai_waitlist';

    $result = $wpdb->update(
        $table_name,
        [
            'location' => $location,
            'stage' => $stage,
            'timeline' => $timeline
        ],
        ['email' => $email],
        ['%s', '%s', '%s'],
        ['%s']
    );

    if ($result === false) {
        return new WP_Error('database_error', 'Database error', ['status' => 500]);
    }

    return new WP_REST_Response([
        'success' => true,
        'message' => 'Survey data saved!'
    ], 200);
}

// ============================================
// EMAIL FUNCTIONS
// ============================================

/**
 * Send confirmation email to user
 */
function torlyai_send_waitlist_confirmation_email($email) {
    $subject = 'Welcome to the TorlyAI Waitlist!';

    $message = '
        <html>
        <body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; line-height: 1.6; color: #000000;">
            <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
                <h1 style="font-size: 28px; font-weight: 700; color: #000000; margin-bottom: 20px;">
                    You\'re on the list! 🎉
                </h1>

                <p style="font-size: 16px; color: rgba(0, 0, 0, 0.7); margin-bottom: 20px;">
                    Thank you for joining the TorlyAI waitlist. You\'ll be among the first to know when we launch, and you\'ll get exclusive early access pricing!
                </p>

                <div style="background: #f9fafb; border-radius: 12px; padding: 24px; margin: 30px 0;">
                    <h2 style="font-size: 18px; font-weight: 600; color: #000000; margin-bottom: 12px;">
                        What happens next?
                    </h2>
                    <ul style="margin: 0; padding-left: 20px; color: rgba(0, 0, 0, 0.7);">
                        <li style="margin-bottom: 8px;">We\'ll keep you updated on our launch progress</li>
                        <li style="margin-bottom: 8px;">You\'ll get early access before the general public</li>
                        <li style="margin-bottom: 8px;">Special launch pricing just for waitlist members</li>
                    </ul>
                </div>

                <p style="font-size: 16px; color: rgba(0, 0, 0, 0.7); margin-bottom: 20px;">
                    In the meantime, check out our <a href="https://torly.ai/blog/" style="color: #10b981; text-decoration: none;">free resources</a> on UK Innovator Visa applications.
                </p>

                <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <p style="font-size: 14px; color: rgba(0, 0, 0, 0.5);">
                        Best regards,<br>
                        The TorlyAI Team
                    </p>
                </div>
            </div>
        </body>
        </html>
    ';

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: TorlyAI <noreply@innovatorly.ai>'
    ];

    wp_mail($email, $subject, $message, $headers);
}

/**
 * Send notification email to admin
 */
function torlyai_send_admin_waitlist_notification($email) {
    $admin_email = get_option('admin_email');
    $subject = 'New TorlyAI Waitlist Signup';

    $message = sprintf(
        'New waitlist signup: %s\n\nView all signups in WordPress admin.',
        $email
    );

    wp_mail($admin_email, $subject, $message);
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

/**
 * Get client IP address
 */
function torlyai_get_client_ip() {
    $ip_keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];

    foreach ($ip_keys as $key) {
        if (isset($_SERVER[$key]) && filter_var($_SERVER[$key], FILTER_VALIDATE_IP)) {
            return $_SERVER[$key];
        }
    }

    return '0.0.0.0';
}

/**
 * Get waitlist count
 */
function torlyai_get_waitlist_count() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'torlyai_waitlist';

    return $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
}

/**
 * Export waitlist to CSV
 */
function torlyai_export_waitlist_csv() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'torlyai_waitlist';

    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A);

    if (empty($results)) {
        return false;
    }

    $filename = 'torlyai-waitlist-' . date('Y-m-d') . '.csv';

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    // Header row
    fputcsv($output, array_keys($results[0]));

    // Data rows
    foreach ($results as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
