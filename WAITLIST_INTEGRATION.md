# TorlyAI Waitlist Modal - Integration Guide

Complete implementation of optimized waitlist modal with multi-step flow, following TorlyAI Design System.

## 📁 Files Created

```
theme/torly-theme/
├── templates/
│   └── waitlist-modal.html          # HTML structure (4 steps)
├── assets/
│   ├── css/
│   │   └── waitlist-modal.css       # Design system compliant styles
│   └── js/
│       └── waitlist-modal.js        # Multi-step flow controller
└── inc/
    └── waitlist-functions.php       # WordPress backend handlers
```

## 🚀 Quick Setup (5 Minutes)

### Step 1: Update functions.php

Add to `theme/torly-theme/functions.php`:

```php
// Include waitlist functions
require_once get_template_directory() . '/inc/waitlist-functions.php';

// Enqueue waitlist assets
function torlyai_enqueue_waitlist_assets() {
    // CSS
    wp_enqueue_style(
        'torlyai-waitlist-modal',
        get_template_directory_uri() . '/assets/css/waitlist-modal.css',
        [],
        '2.0.0'
    );

    // JavaScript
    wp_enqueue_script(
        'torlyai-waitlist-modal',
        get_template_directory_uri() . '/assets/js/waitlist-modal.js',
        [],
        '2.0.0',
        true
    );

    // Localize script for AJAX
    wp_localize_script('torlyai-waitlist-modal', 'torlyaiWaitlist', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('torlyai_waitlist_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'torlyai_enqueue_waitlist_assets');
```

### Step 2: Add Modal to Footer

Add to `theme/torly-theme/footer.php` (before `<?php wp_footer(); ?>`):

```php
<!-- Waitlist Modal -->
<?php get_template_part('templates/waitlist-modal'); ?>
```

### Step 3: Add Trigger Buttons

Add anywhere you want a "Join Waitlist" button:

```html
<!-- Primary CTA -->
<button class="btn-primary" onclick="openWaitlistModal()">
    Join Waitlist
</button>

<!-- Text Link -->
<a href="#" onclick="event.preventDefault(); openWaitlistModal();">
    Join the waitlist
</a>

<!-- Navigation Link -->
<li><a href="#waitlist" onclick="event.preventDefault(); openWaitlistModal();">Waitlist</a></li>
```

### Step 4: Deploy

```bash
# Copy files to server
cd theme/torly-theme
rsync -avz --rsync-path="sudo rsync" \
  -e "ssh -i ../../.credentials/ssh-key-2025-11-17.key -o StrictHostKeyChecking=no" \
  templates/waitlist-modal.html \
  assets/css/waitlist-modal.css \
  assets/js/waitlist-modal.js \
  inc/waitlist-functions.php \
  ubuntu@141.147.89.179:/var/www/html/wp-content/themes/torly-theme/

# Update functions.php and footer.php on server
# (Manual edit or deploy complete theme)
```

## ✨ Features

### Multi-Step Flow

**Step 1: Email Signup**
- Clean, focused email input
- Real-time validation
- Loading state during submission

**Step 2: Success + Optional Survey CTA**
- Success confirmation with checkmark animation
- Optional survey invitation
- Skip option (closes modal)

**Step 3: Survey Questions** (Optional)
- Location dropdown
- Business stage radio buttons
- Timeline radio buttons
- Back button to return to step 2
- Skip option at any time

**Step 4: Final Thank You**
- Thank you message
- CTA to visa assessment page
- Close button

### Design System Compliance

✅ **Typography**
- Fluid scaling with `clamp()`
- Font weights per design system (700 for headings, 600 for buttons)
- Line heights: 1.1 for titles, 1.6 for body

✅ **Colors**
- Success green: `#10b981` (design system chat-green)
- Text colors: black, rgba(0,0,0,0.7), rgba(0,0,0,0.5)
- Border color: `#e5e7eb`

✅ **Spacing**
- Uses design system scale (multiples of 0.25rem)
- Section padding: 2.5rem (40px)
- Form groups: 1.5rem (24px) margin

✅ **Components**
- Glass-morphism buttons with backdrop-filter
- 12px border-radius on form inputs
- 16px border-radius on modal
- Pill-shaped buttons (9999px radius)

✅ **Animations**
- Fade in: 0.3s cubic-bezier
- Slide in: 0.3s cubic-bezier
- Success icon pop: 0.5s cubic-bezier
- Hover effects on all interactive elements

✅ **Accessibility**
- Focus states with 2px outlines
- ARIA labels on buttons
- Keyboard navigation (Escape to close)
- Reduced motion support
- High contrast mode support

✅ **Mobile Optimization**
- Bottom sheet style on mobile
- Slide up animation
- Larger tap targets (52px height)
- 16px font size (prevents iOS zoom)
- 95vh max height with scrolling

## 🎨 Customization

### Change Modal Colors

Edit `waitlist-modal.css`:

```css
/* Change success icon color */
.success-icon {
    background: var(--color-chat-green, #10b981); /* Change this */
}

/* Change button style */
.btn-primary {
    background: rgba(255, 255, 255, 0.6); /* Adjust transparency */
}
```

### Change Email Content

Edit `inc/waitlist-functions.php` in `torlyai_send_waitlist_confirmation_email()` function.

### Add More Questions

Edit `templates/waitlist-modal.html` in Step 3 section:

```html
<div class="form-group">
    <label class="form-label">Your Question</label>
    <select id="new-question" name="new-question" class="form-select">
        <option value="">Select...</option>
        <option value="option1">Option 1</option>
    </select>
</div>
```

### Auto-Open Modal

Add to any page:

```html
<script>
// Auto-open after 5 seconds
window.autoOpenWaitlistModal(5000);
</script>
```

## 📊 Database

### Table Structure

```sql
wp_torlyai_waitlist
├── id (bigint, primary key)
├── email (varchar 255, unique)
├── location (varchar 100)
├── stage (varchar 50)
├── timeline (varchar 50)
├── ip_address (varchar 45)
├── user_agent (text)
├── created_at (datetime)
└── updated_at (datetime)
```

### Query Waitlist Data

```php
// Get all signups
global $wpdb;
$table_name = $wpdb->prefix . 'torlyai_waitlist';
$results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

// Get count
$count = torlyai_get_waitlist_count();

// Export to CSV
torlyai_export_waitlist_csv();
```

## 🔌 API Endpoints

### AJAX (Default)

**Email Signup:**
```javascript
POST /wp-admin/admin-ajax.php
action: torlyai_waitlist_signup
nonce: [nonce]
email: user@example.com
```

**Survey Submission:**
```javascript
POST /wp-admin/admin-ajax.php
action: torlyai_waitlist_survey
nonce: [nonce]
data: {"email": "user@example.com", "location": "uk", ...}
```

### REST API (Alternative)

**Email Signup:**
```bash
curl -X POST https://torly.ai/wp-json/torlyai/v1/waitlist \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com"}'
```

**Survey Submission:**
```bash
curl -X POST https://torly.ai/wp-json/torlyai/v1/waitlist/survey \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","location":"uk","stage":"mvp","timeline":"3-6"}'
```

## ✅ Testing Checklist

### Desktop (1280px+)
- [ ] Modal opens centered on screen
- [ ] Glass-morphism effect visible
- [ ] Email validation works
- [ ] Step transitions smooth
- [ ] All buttons clickable
- [ ] Escape key closes modal
- [ ] Backdrop click closes modal

### Tablet (768px)
- [ ] Modal responsive
- [ ] Form inputs readable
- [ ] Buttons appropriately sized
- [ ] Radio buttons easy to select

### Mobile (375px)
- [ ] Modal appears as bottom sheet
- [ ] Slide up animation works
- [ ] Email input doesn't trigger zoom
- [ ] Radio buttons have large tap targets
- [ ] Modal scrolls if content overflows
- [ ] Success icon displays correctly

### Functionality
- [ ] Email saves to database
- [ ] Duplicate emails handled gracefully
- [ ] Survey data updates existing record
- [ ] Confirmation email sends
- [ ] Admin notification email sends
- [ ] Loading states display
- [ ] Error messages show for invalid input

## 🐛 Troubleshooting

### Modal doesn't open
**Check:**
1. JavaScript file loaded: View source, search for `waitlist-modal.js`
2. Console errors: Open DevTools → Console
3. Function exists: Type `openWaitlistModal` in console

**Fix:**
```bash
# Clear WordPress cache
wp cache flush --path=/var/www/html

# Check file permissions
ls -la theme/torly-theme/assets/js/waitlist-modal.js
```

### Styles not applying
**Check:**
1. CSS file loaded: View source, search for `waitlist-modal.css`
2. CSS priority: Inspect element, check computed styles

**Fix:**
```bash
# Regenerate CSS cache
wp theme enable torly-theme --path=/var/www/html
```

### Form submission fails
**Check:**
1. Network tab: See actual error
2. PHP errors: Check `/var/log/apache2/error.log`
3. Database table exists:
```sql
SHOW TABLES LIKE '%torlyai_waitlist%';
```

**Fix:**
```bash
# Recreate database table
wp eval 'torlyai_create_waitlist_table();' --path=/var/www/html
```

### Emails not sending
**Check:**
1. SMTP configured: See `CLAUDE.md` SMTP section
2. Email logs: Check `debug.log`

**Fix:**
```bash
# Test email sending
wp eval 'wp_mail("test@example.com", "Test", "Test message");' --path=/var/www/html
```

## 📈 Analytics Integration

### Google Analytics 4

Track waitlist signups:

```javascript
// Add to waitlist-modal.js after successful signup
gtag('event', 'waitlist_signup', {
    'event_category': 'conversion',
    'event_label': 'waitlist_email',
    'value': 10
});
```

### Track Survey Completion

```javascript
// Add after survey submission
gtag('event', 'waitlist_survey_complete', {
    'event_category': 'engagement',
    'event_label': 'user_preferences',
    'value': 5
});
```

## 🔐 Security Notes

- ✅ Nonce verification on all AJAX requests
- ✅ Email sanitization and validation
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS prevention (escaped output)
- ✅ Rate limiting via IP tracking
- ✅ Input length limits enforced

## 📝 Admin Dashboard

### View Signups

Add to WordPress admin (optional):

```php
// Add admin menu page
function torlyai_waitlist_admin_menu() {
    add_menu_page(
        'Waitlist',
        'Waitlist',
        'manage_options',
        'torlyai-waitlist',
        'torlyai_waitlist_admin_page',
        'dashicons-email',
        30
    );
}
add_action('admin_menu', 'torlyai_waitlist_admin_menu');

function torlyai_waitlist_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'torlyai_waitlist';
    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 100");

    echo '<div class="wrap">';
    echo '<h1>Waitlist Signups (' . torlyai_get_waitlist_count() . ' total)</h1>';
    echo '<table class="wp-list-table widefat">';
    echo '<thead><tr><th>Email</th><th>Location</th><th>Stage</th><th>Timeline</th><th>Date</th></tr></thead>';
    echo '<tbody>';
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row->email) . '</td>';
        echo '<td>' . esc_html($row->location) . '</td>';
        echo '<td>' . esc_html($row->stage) . '</td>';
        echo '<td>' . esc_html($row->timeline) . '</td>';
        echo '<td>' . esc_html($row->created_at) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}
```

## 🎯 Next Steps

1. **Deploy to Production** - Follow deployment steps above
2. **Test All Flows** - Use checklist to verify functionality
3. **Set Up Email** - Configure SMTP if not already done
4. **Add Analytics** - Track conversions in GA4
5. **Monitor Signups** - Check database regularly
6. **A/B Test** - Try different copy/timing
7. **Export Data** - Use CSV export for email campaigns

---

**Questions?** Check the design system: `TORLYAI_DESIGN_SYSTEM.md`

**Issues?** See troubleshooting section above or check logs.
