# TorlyAI REST API Usage Guide

## Overview

The TorlyAI WordPress theme provides custom REST API endpoints for visa assessments, contact forms, and blog statistics. All endpoints now include **authentication** and **rate limiting** for security.

**Base URL:** `https://torly.ai/wp-json/torlyai/v1/`

---

## Authentication

### WordPress Nonce Authentication

All POST endpoints require WordPress nonce authentication. The nonce can be provided in two ways:

#### Option 1: HTTP Header (Recommended)
```javascript
fetch('https://torly.ai/wp-json/torlyai/v1/visa-assessment', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': wpApiSettings.nonce // Provided by WordPress
    },
    body: JSON.stringify(data)
});
```

#### Option 2: Request Parameter
```javascript
fetch('https://torly.ai/wp-json/torlyai/v1/visa-assessment?_wpnonce=abc123', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
});
```

### Getting the Nonce

The WordPress REST nonce is automatically available in JavaScript when the theme is loaded:

```php
// In functions.php (already implemented)
wp_localize_script('torlyai-script', 'torlyaiData', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('torlyai_nonce'),
    'api_endpoint' => home_url('/wp-json/torlyai/v1/')
));
```

Access it in JavaScript:
```javascript
const nonce = torlyaiData.nonce;
```

**For WordPress REST API, use the global `wp_rest` nonce:**
```javascript
const nonce = wpApiSettings.nonce; // WordPress global
```

Or generate a new one server-side:
```php
$nonce = wp_create_nonce('wp_rest');
```

---

## Rate Limiting

### POST Endpoints (visa-assessment, contact-form)
- **Limit:** 10 requests per minute per IP address per endpoint
- **Response:** 429 Too Many Requests
- **Reset:** Automatic after 60 seconds

### GET Endpoints (blog-stats)
- **Limit:** 30 requests per minute per IP address
- **Response:** 429 Too Many Requests
- **Reset:** Automatic after 60 seconds

### Rate Limit Error Response
```json
{
    "code": "rest_rate_limited",
    "message": "Too many requests. Please try again later.",
    "data": {
        "status": 429
    }
}
```

---

## API Endpoints

### 1. Visa Assessment

**Endpoint:** `POST /wp-json/torlyai/v1/visa-assessment`

**Authentication:** Required (WordPress nonce)

**Rate Limit:** 10 requests/minute

**Request Body:**
```json
{
    "email": "user@example.com",
    "business_name": "Tech Startup Ltd",
    "innovation_factors": [
        "Novel technology",
        "Scalable model",
        "Market disruption"
    ],
    "business_plan": true,
    "growth_potential": 85,
    "endorsing_body": "UKES"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "score": 85,
    "recommendations": [
        "Strong innovation profile",
        "Consider applying to UKES",
        "Prepare detailed market analysis"
    ],
    "assessment_id": 123
}
```

**JavaScript Example:**
```javascript
async function submitVisaAssessment(data) {
    try {
        const response = await fetch('https://torly.ai/wp-json/torlyai/v1/visa-assessment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': wpApiSettings.nonce
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message);
        }

        const result = await response.json();
        console.log('Assessment Score:', result.score);
        return result;
    } catch (error) {
        console.error('Assessment failed:', error);
        throw error;
    }
}
```

**PHP Example (Server-Side):**
```php
$assessment_data = array(
    'email' => 'user@example.com',
    'business_name' => 'Tech Startup Ltd',
    'innovation_factors' => array('Novel technology', 'Scalable model'),
    'business_plan' => true,
    'growth_potential' => 85,
    'endorsing_body' => 'UKES'
);

$response = wp_remote_post('https://torly.ai/wp-json/torlyai/v1/visa-assessment', array(
    'headers' => array(
        'Content-Type' => 'application/json',
        'X-WP-Nonce' => wp_create_nonce('wp_rest')
    ),
    'body' => json_encode($assessment_data)
));

if (is_wp_error($response)) {
    error_log('Assessment failed: ' . $response->get_error_message());
} else {
    $result = json_decode(wp_remote_retrieve_body($response), true);
    echo 'Score: ' . $result['score'];
}
```

---

### 2. Contact Form

**Endpoint:** `POST /wp-json/torlyai/v1/contact-form`

**Authentication:** Required (WordPress nonce)

**Rate Limit:** 10 requests/minute

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Inquiry about Innovator Visa",
    "message": "I would like to know more about your services..."
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Your message has been sent successfully"
}
```

**JavaScript Example:**
```javascript
async function submitContactForm(formData) {
    const response = await fetch('https://torly.ai/wp-json/torlyai/v1/contact-form', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': wpApiSettings.nonce
        },
        body: JSON.stringify(formData)
    });

    if (!response.ok) {
        throw new Error('Failed to submit contact form');
    }

    return await response.json();
}
```

---

### 3. Blog Statistics

**Endpoint:** `GET /wp-json/torlyai/v1/blog-stats`

**Authentication:** Not required (public endpoint)

**Rate Limit:** 30 requests/minute

**Response (200 OK):**
```json
{
    "total_posts": 42,
    "total_categories": 8,
    "recent_posts": [
        {
            "id": 123,
            "title": "UK Innovator Visa Guide 2025",
            "link": "https://torly.ai/blog/uk-innovator-visa-guide-2025",
            "date": "2025-11-17",
            "excerpt": "Complete guide to the UK Innovator Visa..."
        },
        {
            "id": 122,
            "title": "Top 5 Endorsing Bodies",
            "link": "https://torly.ai/blog/top-5-endorsing-bodies",
            "date": "2025-11-16",
            "excerpt": "Comparison of the best endorsing bodies..."
        }
    ]
}
```

**JavaScript Example:**
```javascript
async function getBlogStats() {
    const response = await fetch('https://torly.ai/wp-json/torlyai/v1/blog-stats');

    if (!response.ok) {
        throw new Error('Failed to fetch blog stats');
    }

    const stats = await response.json();
    console.log(`Total Posts: ${stats.total_posts}`);
    console.log(`Categories: ${stats.total_categories}`);
    return stats;
}
```

---

## Error Responses

### 401 Unauthorized (Missing Nonce)
```json
{
    "code": "rest_forbidden",
    "message": "Authentication required. Missing nonce.",
    "data": {
        "status": 401
    }
}
```

### 403 Forbidden (Invalid Nonce)
```json
{
    "code": "rest_forbidden",
    "message": "Invalid nonce. Authentication failed.",
    "data": {
        "status": 403
    }
}
```

### 429 Too Many Requests
```json
{
    "code": "rest_rate_limited",
    "message": "Too many requests. Please try again later.",
    "data": {
        "status": 429
    }
}
```

### 400 Bad Request (Invalid Data)
```json
{
    "code": "invalid_data",
    "message": "Invalid or missing required fields",
    "data": {
        "status": 400
    }
}
```

---

## Complete Form Example

Here's a complete example of a visa assessment form with authentication:

```html
<form id="visa-assessment-form">
    <input type="email" name="email" required placeholder="Your Email">
    <input type="text" name="business_name" required placeholder="Business Name">
    <button type="submit">Submit Assessment</button>
    <div id="result"></div>
</form>

<script>
document.getElementById('visa-assessment-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = {
        email: formData.get('email'),
        business_name: formData.get('business_name'),
        innovation_factors: ['Novel technology', 'Scalable model'],
        business_plan: true,
        growth_potential: 75,
        endorsing_body: 'UKES'
    };

    try {
        const response = await fetch('https://torly.ai/wp-json/torlyai/v1/visa-assessment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': wpApiSettings.nonce // WordPress provides this
            },
            body: JSON.stringify(data)
        });

        if (response.status === 429) {
            alert('Too many requests. Please wait a minute and try again.');
            return;
        }

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message);
        }

        const result = await response.json();
        document.getElementById('result').innerHTML = `
            <h3>Assessment Complete!</h3>
            <p>Your score: ${result.score}/100</p>
            <ul>
                ${result.recommendations.map(rec => `<li>${rec}</li>`).join('')}
            </ul>
        `;
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('result').innerHTML = `<p style="color: red;">Error: ${error.message}</p>`;
    }
});
</script>
```

---

## Testing with cURL

### Test Visa Assessment (Will Fail - No Nonce)
```bash
curl -X POST https://torly.ai/wp-json/torlyai/v1/visa-assessment \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "business_name": "Test Business",
    "innovation_factors": ["AI", "SaaS"],
    "business_plan": true,
    "growth_potential": 80,
    "endorsing_body": "UKES"
  }'
```

**Expected Response:**
```json
{
    "code": "rest_forbidden",
    "message": "Authentication required. Missing nonce.",
    "data": {"status": 401}
}
```

### Test with Nonce (Replace YOUR_NONCE)
```bash
curl -X POST https://torly.ai/wp-json/torlyai/v1/visa-assessment \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: YOUR_NONCE" \
  -d '{
    "email": "test@example.com",
    "business_name": "Test Business",
    "innovation_factors": ["AI", "SaaS"],
    "business_plan": true,
    "growth_potential": 80,
    "endorsing_body": "UKES"
  }'
```

### Test Blog Stats (Public - No Auth)
```bash
curl https://torly.ai/wp-json/torlyai/v1/blog-stats
```

### Test Rate Limiting
```bash
# Run this 11 times quickly
for i in {1..11}; do
  curl -X POST https://torly.ai/wp-json/torlyai/v1/visa-assessment \
    -H "Content-Type: application/json" \
    -H "X-WP-Nonce: YOUR_NONCE" \
    -d '{"email":"test@example.com","business_name":"Test"}';
  echo "\nRequest $i";
done

# 11th request should return 429
```

---

## Security Best Practices

1. **Always use HTTPS** - Never send nonces over HTTP
2. **Regenerate nonces** - Nonces expire after 24 hours by default
3. **Handle rate limits gracefully** - Show user-friendly messages
4. **Validate input** - Even though server validates, validate client-side too
5. **Never expose admin credentials** - Use application passwords for programmatic access
6. **Log suspicious activity** - Monitor for repeated rate limit violations

---

## Troubleshooting

### "Authentication required. Missing nonce"
- **Cause:** Nonce not included in request
- **Fix:** Add `X-WP-Nonce` header or `_wpnonce` parameter
- **Check:** Ensure `wpApiSettings` is available (user must be logged in or nonce must be generated)

### "Invalid nonce. Authentication failed"
- **Cause:** Nonce expired or incorrect
- **Fix:** Regenerate nonce (reload page or call `wp_create_nonce('wp_rest')`)
- **Note:** Nonces expire after 12-24 hours

### "Too many requests. Please try again later"
- **Cause:** Exceeded rate limit (10 or 30 requests/minute)
- **Fix:** Wait 60 seconds before retrying
- **Tip:** Implement client-side request queuing

### Nonce not available in JavaScript
- **Cause:** Theme not enqueuing scripts properly
- **Fix:** Check that `torlyai_enqueue_scripts()` is running
- **Debug:** `console.log(wpApiSettings)` to see if it exists

---

## Implementation Checklist

- [x] Nonce authentication on POST endpoints
- [x] Rate limiting (10 req/min for POST, 30 req/min for GET)
- [x] Permission callbacks implemented
- [x] Error handling with proper HTTP status codes
- [x] IP-based rate limiting
- [x] Transient-based rate limit storage
- [x] Public endpoint (blog-stats) with lenient limits
- [x] Security documentation

---

## Version History

- **v2.0.1** (2025-11-17): Added authentication and rate limiting
- **v2.0.0** (2025-11-17): Granola.ai-inspired redesign
- **v1.0.0** (Initial): Original API endpoints (no auth)

---

## Contact

For API support or questions:
- **Email:** jasonxu05@gmail.com
- **Documentation:** See SECURITY_FIXES.md for security improvements

---

**🔒 Security Status: All API endpoints now authenticated and rate-limited.**
