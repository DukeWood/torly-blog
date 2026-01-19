# Security Audit Report - TorlyAI WordPress Setup

**Date:** 2025-11-17
**Auditor:** Claude Code Security Review
**Codebase Version:** Main branch (commit b7545ae)

## Executive Summary

This security audit identified **15 security issues** across the TorlyAI WordPress setup project:
- **5 CRITICAL** vulnerabilities requiring immediate attention
- **4 HIGH** risk issues
- **4 MEDIUM** risk issues
- **2 LOW** risk items (informational)

The most critical issues are:
1. Hardcoded SMTP password in deployment script (EXPOSED CREDENTIAL)
2. Multiple command injection vulnerabilities in MCP server
3. Unauthenticated WordPress REST API endpoints
4. Hardcoded JWT secret key in deployment

---

## CRITICAL Severity Issues

### 1. 🔴 EXPOSED SMTP PASSWORD IN DEPLOYMENT SCRIPT

**File:** `deployment/configure-smtp.sh:16,21`
**Severity:** CRITICAL
**CVSS Score:** 9.8

**Description:**
SMTP password `Ll86VT3jPZCoh7yV` for `noreply@innovatorly.ai` is hardcoded in plain text in the deployment script.

**Impact:**
- Anyone with repository access can read the SMTP credentials
- Attacker can send emails from your domain
- Potential for phishing attacks using your infrastructure
- If this file is committed to version control, the password is permanently exposed

**Evidence:**
```bash
Line 16: "pass":"Ll86VT3jPZCoh7yV"
Line 21: "pass":"Ll86VT3jPZCoh7yV"
```

**Recommendation:**
```bash
# Immediate action required:
1. Change the SMTP password in Lark Suite immediately
2. Replace hardcoded password with environment variable:
   pass":"${SMTP_PASSWORD}"
3. Move password to .env file or .credentials/
4. Verify this file is NOT in git history with:
   git log --all --full-history -- deployment/configure-smtp.sh
```

---

### 2. 🔴 COMMAND INJECTION IN MCP SERVER

**File:** `mcp-integration/wordpress-mcp-server.js`
**Severity:** CRITICAL
**CVSS Score:** 9.8

**Description:**
Multiple functions execute user-supplied input directly in shell commands without sanitization.

**Vulnerable Functions:**

#### 2a. `executeWpCli()` - Line 522-540
```javascript
const { stdout, stderr } = await execAsync(
  `${WP_CLI_PATH} ${args.command} --path=${path}`
);
```
**Attack Vector:** `args.command` is directly concatenated without validation

#### 2b. `createMenu()` - Line 586-605
```javascript
await execAsync(
  `${WP_CLI_PATH} menu item add-custom ${args.name} "${item.title}" ${item.url}`
);
```
**Attack Vector:** `args.name`, `item.title`, and `item.url` can contain shell metacharacters

#### 2c. `installPlugin()` - Line 608-629
```javascript
const installCmd = `${WP_CLI_PATH} plugin install ${args.slug}`;
```
**Attack Vector:** `args.slug` not validated

#### 2d. `deployToOracleCloud()` - Line 676-704
```javascript
const scriptPath = deployment_script_path || '/opt/torly-blog/deployment/deploy-script.sh';
`scp -i ${ssh_key_path} ... ${scriptPath} ...`
```
**Attack Vector:** User-supplied paths can be exploited

**Impact:**
- Remote code execution on the server
- Full system compromise
- Data exfiltration
- Malware installation

**Example Exploit:**
```javascript
// Attacker sends this to wp_cli_command:
{
  "command": "plugin list; rm -rf /var/www/html; echo 'pwned'"
}

// Results in execution of:
wp plugin list; rm -rf /var/www/html; echo 'pwned' --path=/var/www/html
```

**Recommendation:**
```javascript
// Use parameterized execution or whitelist validation:
function executeWpCli(args) {
  // Validate command against whitelist
  const allowedCommands = ['plugin', 'theme', 'post', 'page', 'user'];
  const cmdParts = args.command.split(' ');

  if (!allowedCommands.includes(cmdParts[0])) {
    throw new Error('Command not allowed');
  }

  // Use array syntax to prevent injection
  const { stdout, stderr } = await execAsync(WP_CLI_PATH, [
    cmdParts[0],
    ...cmdParts.slice(1),
    `--path=${path}`
  ]);
}
```

---

### 3. 🔴 UNAUTHENTICATED REST API ENDPOINTS

**File:** `theme/torly-theme/functions.php:116-136`
**Severity:** CRITICAL
**CVSS Score:** 8.6

**Description:**
All custom WordPress REST API endpoints use `'permission_callback' => '__return_true'`, allowing anyone to access them without authentication.

**Vulnerable Endpoints:**

#### 3a. `/wp-json/torlyai/v1/visa-assessment` (POST)
- **Line:** 118-122
- **Risk:** Anyone can submit visa assessments, causing database spam
- **Data exposure:** Email addresses stored without user consent

#### 3b. `/wp-json/torlyai/v1/contact-form` (POST)
- **Line:** 124-128
- **Risk:** Spam emails to admin, potential email bombing attack

#### 3c. `/wp-json/torlyai/v1/blog-stats` (GET)
- **Line:** 130-134
- **Risk:** Information disclosure about site structure

**Impact:**
- Spam attacks via contact form
- Database bloat from fake assessments
- Information disclosure
- No rate limiting or abuse prevention

**Recommendation:**
```php
// Add proper authentication and rate limiting:
register_rest_route('torlyai/v1', '/visa-assessment', array(
    'methods' => 'POST',
    'callback' => 'torlyai_visa_assessment_callback',
    'permission_callback' => function($request) {
        // Verify nonce for logged-in users
        if (is_user_logged_in()) {
            return current_user_can('read');
        }

        // For public access, implement rate limiting
        $ip = $_SERVER['REMOTE_ADDR'];
        if (get_transient('rate_limit_' . $ip) > 5) {
            return new WP_Error('rate_limit', 'Too many requests', array('status' => 429));
        }
        set_transient('rate_limit_' . $ip, get_transient('rate_limit_' . $ip) + 1, HOUR_IN_SECONDS);

        return true;
    },
));
```

---

### 4. 🔴 HARDCODED JWT SECRET KEY

**File:** `deployment/deploy-script.sh:119`
**Severity:** CRITICAL
**CVSS Score:** 8.1

**Description:**
JWT authentication secret is hardcoded as `'your-secret-key-here'` in the WordPress configuration.

**Evidence:**
```bash
Line 119: define('JWT_AUTH_SECRET_KEY', 'your-secret-key-here');
```

**Impact:**
- JWT tokens can be forged by anyone who sees this code
- Complete authentication bypass
- Unauthorized API access
- User impersonation

**Recommendation:**
```bash
# Generate secure random secret:
JWT_SECRET=$(openssl rand -base64 64)

# Update in wp-config.php:
cat >> wp-config.php << EOF
define('JWT_AUTH_SECRET_KEY', '${JWT_SECRET}');
EOF

# Save secret for reference:
echo "JWT_SECRET=${JWT_SECRET}" >> /root/.wp-credentials
```

---

### 5. 🔴 DATABASE PASSWORD GENERATION ISSUE

**File:** `deployment/deploy-script.sh:82-96`
**Severity:** CRITICAL
**CVSS Score:** 7.5

**Description:**
The script generates a random database password but never stores it for administrator reference. The password retrieval at line 96 will fail because MySQL's `PASSWORD()` function doesn't return the original password.

**Evidence:**
```bash
Line 82: mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '$(openssl rand -base64 32)';"
Line 96: --dbpass=$(mysql -e "SELECT PASSWORD('${DB_USER}');" | tail -n1)
```

**Impact:**
- Database becomes inaccessible after initial setup
- Cannot recover database password
- WordPress will fail to connect to database
- Requires manual database password reset

**Recommendation:**
```bash
# Store password in variable first:
DB_PASSWORD=$(openssl rand -base64 32)

# Create user with stored password:
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';"

# Use stored password in wp-config:
wp config create --dbpass="${DB_PASSWORD}" ...

# Save credentials securely:
cat > /root/.wp-credentials << EOF
DB_NAME=${DB_NAME}
DB_USER=${DB_USER}
DB_PASSWORD=${DB_PASSWORD}
EOF
chmod 600 /root/.wp-credentials
```

---

## HIGH Severity Issues

### 6. 🟠 SENSITIVE PDF FILE NOT GITIGNORED

**File:** `17-Nov-2025_Instances _ Oracle Cloud Infrastructure.pdf`
**Severity:** HIGH
**CVSS Score:** 6.5

**Description:**
PDF file containing Oracle Cloud Infrastructure screenshots is present in the repository root and not excluded by `.gitignore`.

**Evidence:**
```bash
$ ls -la | grep pdf
-rw-r--r--@ 1 Jason-uk staff 99142 Nov 17 14:48 17-Nov-2025_Instances _ Oracle Cloud Infrastructure.pdf
```

**Impact:**
- May contain sensitive information (VM IPs, OCIDs, account details)
- If committed to git, permanently exposed in repository history
- File is currently untracked but at risk of accidental commit

**Recommendation:**
```bash
# Immediate actions:
1. Review PDF contents for sensitive information
2. Delete file from repository:
   rm "17-Nov-2025_Instances _ Oracle Cloud Infrastructure.pdf"

3. Add to .gitignore:
   echo "*.pdf" >> .gitignore

4. Verify not in git history:
   git log --all --full-history -- "*.pdf"
```

---

### 7. 🟠 WORDPRESS ADMIN PASSWORD NOT SAVED

**File:** `deployment/deploy-script.sh:129`
**Severity:** HIGH
**CVSS Score:** 6.0

**Description:**
WordPress admin password is generated randomly but never displayed or saved for the administrator.

**Evidence:**
```bash
Line 129: --admin_password=$(openssl rand -base64 32)
```

**Impact:**
- Administrator cannot log into WordPress
- Requires password reset via wp-cli or database manipulation
- Delays deployment completion

**Recommendation:**
```bash
# Store admin credentials:
ADMIN_PASSWORD=$(openssl rand -base64 32)

wp core install \
    --admin_password="${ADMIN_PASSWORD}" \
    ...

# Display and save credentials:
cat > /root/.wp-credentials << EOF
ADMIN_USERNAME=admin
ADMIN_PASSWORD=${ADMIN_PASSWORD}
ADMIN_EMAIL=admin@${DOMAIN}
WP_ADMIN_URL=https://${DOMAIN}/wp-admin
EOF

chmod 600 /root/.wp-credentials

echo ""
echo "IMPORTANT - SAVE THESE CREDENTIALS:"
echo "Username: admin"
echo "Password: ${ADMIN_PASSWORD}"
echo "Login: https://${DOMAIN}/wp-admin"
echo "Credentials saved to: /root/.wp-credentials"
```

---

### 8. 🟠 INPUT VALIDATION MISSING IN API ENDPOINTS

**File:** `theme/torly-theme/functions.php:139-183`
**Severity:** HIGH
**CVSS Score:** 6.8

**Description:**
API callback functions do not properly validate input data types, lengths, or formats.

**Vulnerable Functions:**

#### 8a. `torlyai_visa_assessment_callback()` - Line 139-156
```php
$params = $request->get_json_params();
// No validation before use:
$assessment_result['score'] = calculate_visa_score($params);
```

#### 8b. `torlyai_contact_form_callback()` - Line 158-182
```php
// Minimal validation:
if (empty($params['name']) || empty($params['email']) || empty($params['message'])) {
    return new WP_REST_Response(array('error' => 'Missing required fields'), 400);
}
// No email format validation
// No message length limits
// No sanitization before database storage
```

**Impact:**
- Cross-Site Scripting (XSS) via stored data
- SQL injection (mitigated by WordPress sanitization functions)
- Database bloat from large payloads
- Email header injection in contact form

**Recommendation:**
```php
function torlyai_visa_assessment_callback($request) {
    $params = $request->get_json_params();

    // Validate required fields
    if (!isset($params['email']) || !isset($params['business_name'])) {
        return new WP_REST_Response(
            array('error' => 'Missing required fields'),
            400
        );
    }

    // Validate email format
    if (!is_email($params['email'])) {
        return new WP_REST_Response(
            array('error' => 'Invalid email format'),
            400
        );
    }

    // Validate data types and lengths
    $params['business_name'] = sanitize_text_field($params['business_name']);
    if (strlen($params['business_name']) > 255) {
        return new WP_REST_Response(
            array('error' => 'Business name too long'),
            400
        );
    }

    // Validate growth_potential is numeric
    if (isset($params['growth_potential']) && !is_numeric($params['growth_potential'])) {
        return new WP_REST_Response(
            array('error' => 'Invalid growth potential value'),
            400
        );
    }

    // Continue with processing...
}
```

---

### 9. 🟠 API CREDENTIALS EXPOSED IN ADMIN PANEL

**File:** `theme/torly-theme/functions.php:332-341`
**Severity:** HIGH
**CVSS Score:** 5.5

**Description:**
GoDaddy API credentials are displayed in plain text input fields in the WordPress admin panel.

**Evidence:**
```php
Line 334: <input type="text" name="torlyai_godaddy_api_key" ...>
Line 340: <input type="password" name="torlyai_godaddy_api_secret" ...>
```

**Issue:**
- API Key uses `type="text"` (visible on screen)
- Browser autofill may save credentials insecurely
- Shoulder surfing risk
- Browser history/cache may store values

**Impact:**
- Credentials visible to anyone looking at admin's screen
- Risk of credential theft via screen recording/screenshots
- Insecure browser storage

**Recommendation:**
```php
// Both fields should use password type:
<tr>
    <th scope="row">GoDaddy API Key</th>
    <td>
        <input type="password"
               name="torlyai_godaddy_api_key"
               value="<?php echo esc_attr(get_option('torlyai_godaddy_api_key')); ?>"
               autocomplete="off" />
        <p class="description">Enter your GoDaddy API Key (will be hidden)</p>
    </td>
</tr>
<tr>
    <th scope="row">GoDaddy API Secret</th>
    <td>
        <input type="password"
               name="torlyai_godaddy_api_secret"
               value="<?php echo esc_attr(get_option('torlyai_godaddy_api_secret')); ?>"
               autocomplete="new-password" />
        <p class="description">Enter your GoDaddy API Secret (will be hidden)</p>
    </td>
</tr>
```

---

## MEDIUM Severity Issues

### 10. 🟡 NO RATE LIMITING ON PUBLIC API ENDPOINTS

**Severity:** MEDIUM
**CVSS Score:** 5.3

**Description:**
Public API endpoints have no rate limiting, allowing abuse and DoS attacks.

**Impact:**
- Spam submissions via contact form
- Database bloat from automated submissions
- Email bombing attack vector
- Resource exhaustion

**Recommendation:**
Implement rate limiting using WordPress transients (shown in Issue #3 recommendation).

---

### 11. 🟡 MISSING HTTPS ENFORCEMENT IN PHP

**File:** `theme/torly-theme/functions.php:36-43`
**Severity:** MEDIUM
**CVSS Score:** 4.8

**Description:**
While there's an HTTPS redirect function, it doesn't set secure cookie flags or HSTS headers from PHP level.

**Recommendation:**
```php
// Add secure session cookie settings:
function torlyai_secure_cookies() {
    @ini_set('session.cookie_secure', '1');
    @ini_set('session.cookie_httponly', '1');
    @ini_set('session.cookie_samesite', 'Strict');
}
add_action('init', 'torlyai_secure_cookies');

// Add HSTS header (redundant with Apache but adds defense in depth):
function torlyai_hsts_header() {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}
add_action('send_headers', 'torlyai_hsts_header');
```

---

### 12. 🟡 NO CSRF PROTECTION ON CUSTOM FORMS

**Severity:** MEDIUM
**CVSS Score:** 4.5

**Description:**
Custom JavaScript forms (`torlyai-script.js`) may not implement proper CSRF nonce verification.

**Recommendation:**
```php
// In functions.php (line 61):
wp_localize_script('torlyai-script', 'torlyai_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('torlyai_nonce'),
    'api_endpoint' => home_url('/wp-json/torlyai/v1/')
));

// In API callbacks, verify nonce:
function torlyai_visa_assessment_callback($request) {
    // Verify nonce if provided
    $nonce = $request->get_header('X-WP-Nonce');
    if ($nonce && !wp_verify_nonce($nonce, 'torlyai_nonce')) {
        return new WP_REST_Response(
            array('error' => 'Invalid security token'),
            403
        );
    }
    // ... continue processing
}
```

---

### 13. 🟡 DATABASE TABLE CREATION WITHOUT ERROR HANDLING

**File:** `theme/torly-theme/functions.php:406-423`
**Severity:** MEDIUM
**CVSS Score:** 3.5

**Description:**
`torlyai_create_tables()` function doesn't check if table creation succeeded or handle errors.

**Recommendation:**
```php
function torlyai_create_tables() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'visa_assessments';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        assessment_data text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX email_idx (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $result = dbDelta($sql);

    // Log result for debugging
    if (!empty($wpdb->last_error)) {
        error_log('TorlyAI table creation error: ' . $wpdb->last_error);
    } else {
        error_log('TorlyAI tables created successfully');
    }
}
```

---

## LOW Severity Issues (Informational)

### 14. ✅ FILE PERMISSIONS (SECURE)

**Severity:** LOW / Informational
**Status:** ✅ SECURE

**Findings:**
- `.credentials/` directory: `drwx------` (700) - Correct
- SSH keys: `-rw-------` (600) - Correct
- Credential files: `-rw-------` (600) - Correct

**Recommendation:** No action needed. File permissions are properly configured.

---

### 15. ✅ GITIGNORE CONFIGURATION (SECURE)

**Severity:** LOW / Informational
**Status:** ✅ SECURE

**Findings:**
- `.credentials/` properly excluded
- `.env` files properly excluded
- `*.key` and `*.pem` files excluded
- Password files excluded

**Recommendation:** Add `*.pdf` to gitignore to prevent future screenshot commits.

---

## Summary of Findings

| Severity | Count | Issues |
|----------|-------|--------|
| CRITICAL | 5 | SMTP password exposed, Command injection (4 locations), Unauthenticated APIs, Hardcoded JWT secret, DB password issue |
| HIGH | 4 | PDF file not gitignored, Admin password not saved, Missing input validation, API credentials in plain text |
| MEDIUM | 4 | No rate limiting, Missing HTTPS enforcement, No CSRF protection, DB error handling |
| LOW | 2 | File permissions (secure), Gitignore (secure) |
| **TOTAL** | **15** | |

---

## Remediation Priority

### Immediate Actions (Complete within 24 hours):

1. **Change SMTP password** - `Ll86VT3jPZCoh7yV` is now compromised
2. **Remove hardcoded password** from `configure-smtp.sh`
3. **Delete PDF file** containing Oracle Cloud screenshots
4. **Fix JWT secret** in deployment script
5. **Add authentication** to WordPress REST API endpoints

### Short-term Actions (Complete within 1 week):

6. Fix command injection vulnerabilities in MCP server
7. Implement input validation on all API endpoints
8. Add rate limiting to public endpoints
9. Fix database password storage in deployment script
10. Save admin credentials properly during deployment

### Medium-term Actions (Complete within 1 month):

11. Implement CSRF protection
12. Add comprehensive error handling
13. Set up security monitoring
14. Implement API request logging
15. Create security testing suite

---

## Compliance Impact

These vulnerabilities may affect compliance with:
- **GDPR:** Unauthenticated API allows unauthorized data processing
- **PCI DSS:** Command injection and credential exposure
- **OWASP Top 10:**
  - A01:2021 - Broken Access Control (Issue #3)
  - A03:2021 - Injection (Issue #2)
  - A05:2021 - Security Misconfiguration (Issues #1, #4)
  - A07:2021 - Identification and Authentication Failures (Issue #3)

---

## Testing Recommendations

1. **Penetration Testing:**
   - Test command injection vectors in MCP server
   - Attempt API abuse without authentication
   - Test rate limiting (once implemented)

2. **Code Security Scanning:**
   - Run Snyk or similar tool on Node.js dependencies
   - Use WPScan for WordPress vulnerabilities
   - Scan for secrets in git history

3. **Security Headers:**
   ```bash
   # Test security headers:
   curl -I https://torly.ai | grep -i "security\|transport\|frame\|xss"
   ```

---

## Contact

For questions about this security audit, contact:
- **Security Contact:** jasonxu05@gmail.com
- **Report Date:** 2025-11-17

---

**End of Security Audit Report**
