# Development Journal - Torly AI WordPress Setup

## November 17, 2025

### Summary
Successfully merged blog.torly.ai into main torly.ai site, completed multisite removal, and fixed all related configuration issues. All 5 blog posts with custom SVG covers are now accessible at `torly.ai/blog/article-slug/` URLs.

---

## Tasks Completed

### 1. Blog Site Merge & Content Migration ✅

**Objective**: Merge blog.torly.ai subsite into main torly.ai site

**Actions Taken**:
- Exported all posts from blog.torly.ai using WP-CLI export command
  - Export file: `/tmp/blog-export/torlyblogs.wordpress.2025-11-17.000.xml`
- Installed wordpress-importer plugin on torly.ai
- Imported all content with `--authors=create` flag
- Successfully imported 5 blog posts (IDs: 12-16)
  - "UK Innovator Visa 2026: Complete Guide for Entrepreneurs"
  - "How to Prepare a Winning Business Plan for UK Innovator Visa"
  - "Top 5 UK Endorsing Bodies for Innovator Visa in 2026"
  - "UK Innovator Visa vs Scale-up Visa: Which is Right for You?"
  - "Success Story: From Startup Idea to UK Permanent Residence"
- Imported 10 attachments (5 old Unsplash images + 5 custom SVG covers)
- All categories, tags, and metadata preserved
- Deleted blog.torly.ai subsite (site ID 2) from multisite network

**Files Modified**:
- Database: wp_posts, wp_postmeta, wp_terms, wp_term_taxonomy
- Uploads: Files copied to main uploads directory

---

### 2. Blog Navigation Fix ✅

**Issue**: Blog navigation link pointed to deleted `blog.torly.ai` subdomain, causing "Registration has been disabled" error

**Actions Taken**:
- Created new "Blog" page (ID: 27) at `torly.ai/blog/`
- Set page as WordPress Posts Page using `wp option update page_for_posts 27`
- Updated navigation in `theme/torly-theme/header.php` line 74
  - Changed from: `https://blog.torly.ai/`
  - Changed to: `https://torly.ai/blog/`
- Uploaded updated header.php to server

**Files Modified**:
- `/var/www/html/wp-content/themes/torly-theme/header.php`
- Database: wp_options (page_for_posts)

---

### 3. Multisite Removal - Complete Fix ✅

**Critical Issues Found**:
1. ❌ WordPress still in multisite mode (MULTISITE=true in wp-config.php)
2. ❌ Posts at wrong URLs (torly.ai/post-slug/ instead of torly.ai/blog/post-slug/)
3. ❌ Post GUIDs referenced deleted blog.torly.ai domain
4. ❌ Upload files in multisite directory structure (sites/2/)
5. ❌ .htaccess had multisite rules and dangerous redirect to blog.torly.ai
6. ❌ Admin email set to non-existent admin@torly.ai

**Solution Script Created**: `deployment/fix-multisite-removal.sh`

**Actions Taken**:

#### 3.1 Permalink Structure Update
```bash
wp option update permalink_structure '/blog/%postname%/'
```
- Changed from: `/%postname%/`
- Changed to: `/blog/%postname%/`
- Result: All posts now at `torly.ai/blog/article-slug/`

#### 3.2 Post GUID Updates
```sql
UPDATE wp_posts SET guid = REPLACE(guid, 'https://blog.torly.ai/', 'https://torly.ai/blog/');
```
- Updated 6 posts (5 imported + 1 existing)
- Fixed broken references to deleted subdomain

#### 3.3 Search-Replace Across Database
```bash
wp search-replace 'https://blog.torly.ai' 'https://torly.ai/blog' --all-tables
```
- Checked all tables for references
- Made 0 replacements (GUIDs already updated)

#### 3.4 Multisite Upload Files Migration
- Copied files from `/wp-content/uploads/sites/2/` to `/wp-content/uploads/`
- Updated attachment URLs in database
- Updated `_wp_attached_file` post meta
- Set correct ownership (www-data:www-data)

#### 3.5 Multisite Configuration Disabled
**wp-config.php changes**:
```php
// Commented out:
// define( 'WP_ALLOW_MULTISITE', true );
// define( 'MULTISITE', true );
// define( 'SUBDOMAIN_INSTALL', true );
// define( 'DOMAIN_CURRENT_SITE', 'torly.ai' );
// define( 'PATH_CURRENT_SITE', '/');
// define( 'SITE_ID_CURRENT_SITE', 1 );
// define( 'BLOG_ID_CURRENT_SITE', 1 );
```
- Created backup: `/var/www/html/wp-config.php.bak`
- WordPress now running in standard single-site mode

#### 3.6 .htaccess Rewrite Rules Fixed
**Before** (BROKEN):
```apache
# BEGIN WordPress Multisite
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]
RewriteRule . index.php [L]
# END WordPress Multisite
# Redirect /blog to subdomain
RedirectMatch 301 ^/blog/?$ https://blog.torly.ai/
```

**After** (FIXED):
```apache
# BEGIN WordPress
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
# END WordPress
```

**Critical fix**: Removed dangerous `RedirectMatch 301 ^/blog/?$ https://blog.torly.ai/` that would have broken the blog!

**Files Modified**:
- `/var/www/html/wp-config.php` - Multisite disabled
- `/var/www/html/.htaccess` - Standard WordPress rules
- Database: wp_options (permalink_structure, siteurl, home)
- Database: wp_posts (guid updates)
- Database: wp_postmeta (_wp_attached_file)

---

### 4. Email Configuration Fix ✅

**Issue**: WordPress admin email set to `admin@torly.ai` which cannot receive emails, causing bounce-back notifications

**Root Cause**:
- torly.ai domain has no email hosting/MX records
- Can only SEND emails via Lark Suite SMTP
- Cannot RECEIVE emails at torly.ai addresses

**Actions Taken**:
1. Updated admin email from `admin@torly.ai` to `noreply@innovatorly.ai`
   ```bash
   wp option update admin_email 'noreply@innovatorly.ai'
   wp option update new_admin_email 'noreply@innovatorly.ai'
   ```

2. Tested email notifications:
   - Created test user with `wp user create`
   - Sent manual test email with `wp_mail()`
   - Verified emails sent successfully (no bounces)
   - Cleaned up test user

**Result**:
- ✅ Admin notifications now go to valid email address
- ✅ No more bounce-back emails
- ✅ New user registrations will send notifications successfully

**Files Modified**:
- Database: wp_options (admin_email, new_admin_email)

---

## Final Verification

### Post URLs ✅
All 5 blog posts accessible at correct URLs:
```
https://torly.ai/blog/uk-innovator-visa-2026-complete-guide-for-entrepreneurs/
https://torly.ai/blog/how-to-prepare-a-winning-business-plan-for-uk-innovator-visa/
https://torly.ai/blog/top-5-uk-endorsing-bodies-for-innovator-visa-in-2026/
https://torly.ai/blog/uk-innovator-visa-vs-scale-up-visa-which-is-right-for-you/
https://torly.ai/blog/success-story-from-startup-idea-to-uk-permanent-residence/
```

### Blog Page ✅
- URL: `https://torly.ai/blog/`
- Status: HTTP 200 OK
- Template: `home.php` (blog posts grid)
- Displays all posts with custom SVG covers

### Navigation ✅
- Blog link in header points to `https://torly.ai/blog/`
- No redirect loops
- No 404 errors

### WordPress Configuration ✅
- Mode: Standard single-site (multisite disabled)
- Permalink structure: `/blog/%postname%/`
- Admin email: `noreply@innovatorly.ai`
- SMTP: Lark Suite (smtp.larksuite.com:465)

### Custom SVG Covers ✅
All 5 posts have custom SVG featured images:
- innovator-visa-guide.svg (3.2KB)
- business-plan-guide.svg (3.4KB)
- endorsing-bodies.svg (4.4KB)
- visa-comparison.svg (4.4KB)
- success-story.svg (4.8KB)

---

## Documentation Created

1. **MULTISITE-REMOVAL-COMPLETE.md**
   - Comprehensive report of all issues found and fixed
   - Before/after comparisons
   - Files modified list
   - Verification results

2. **deployment/fix-multisite-removal.sh**
   - Automated script for multisite removal fixes
   - Permalink structure update
   - GUID updates
   - File migration
   - Rewrite rule flush

---

## Key Learnings

1. **Multisite to Single-Site Migration**: Requires updates to:
   - wp-config.php (disable multisite constants)
   - .htaccess (replace multisite rules)
   - Database (update GUIDs, site URLs)
   - Upload directory structure (move from sites/N/ to main)

2. **Email Domain Configuration**:
   - WordPress can send FROM one domain (innovatorly.ai via SMTP)
   - But admin email must be on a domain that can RECEIVE emails
   - Cross-domain setup requires valid receiving address

3. **WordPress Permalink Structure**:
   - Setting page_for_posts doesn't automatically add /blog/ prefix
   - Must explicitly set permalink_structure to `/blog/%postname%/`
   - Requires rewrite flush after changes

4. **.htaccess Redirect Dangers**:
   - Old redirects can break new URL structures
   - Always check for conflicting redirects when restructuring URLs
   - Test both blog index and individual posts after changes

---

## Statistics

- **Blog Posts Migrated**: 5
- **Attachments Migrated**: 10 (5 images + 5 SVG covers)
- **Categories Preserved**: 4 (UK Visa Guide, Innovator Visa, Business Immigration, Success Stories)
- **Tags Preserved**: 8
- **Database Queries Run**: ~15
- **Files Modified**: 3 (wp-config.php, .htaccess, header.php)
- **WP-CLI Commands Used**: ~25
- **Time Spent**: ~3 hours

---

## Next Steps (Optional)

1. **DNS Redirect**: Set up redirect from blog.torly.ai to torly.ai/blog/ for SEO
2. **Database Cleanup**: Remove old multisite tables (wp_blogs, wp_site, wp_sitemeta)
3. **Remove Sites Directory**: Clean up `/wp-content/uploads/sites/` after verifying all images work
4. **Analytics Setup**: Install Google Analytics for blog traffic monitoring
5. **SEO Verification**: Submit new blog URLs to Google Search Console

---

## Commands Reference

### Useful WP-CLI Commands Used Today

```bash
# Export posts
wp export --path=/var/www/html --url=https://blog.torly.ai

# Import posts
wp import file.xml --authors=create --url=https://torly.ai --path=/var/www/html

# Delete site from multisite
wp site delete 2 --yes --path=/var/www/html

# Update permalink structure
wp option update permalink_structure '/blog/%postname%/' --url=https://torly.ai

# Update admin email
wp option update admin_email 'noreply@innovatorly.ai' --url=https://torly.ai

# Search and replace in database
wp search-replace 'old-url' 'new-url' --all-tables --url=https://torly.ai

# Flush rewrite rules
wp rewrite flush --hard --url=https://torly.ai

# List posts
wp post list --post_type=post --format=table --url=https://torly.ai

# Create test user
wp user create testuser test@example.com --role=subscriber --send-email

# Database query
wp db query "SELECT * FROM wp_options WHERE option_name = 'admin_email';"
```

---

## Files & Directories Modified

```
torly-wordpress-setup/
├── deployment/
│   ├── fix-multisite-removal.sh (CREATED)
│   └── update-featured-images-svg.php (USED)
├── theme/torly-theme/
│   ├── header.php (MODIFIED - nav link updated)
│   ├── assets/blog-covers/ (USED)
│   │   ├── innovator-visa-guide.svg
│   │   ├── business-plan-guide.svg
│   │   ├── endorsing-bodies.svg
│   │   ├── visa-comparison.svg
│   │   └── success-story.svg
│   ├── home.php (EXISTING - blog template)
│   └── single.php (EXISTING - post template)
├── .claude/commands/
│   └── devjournal.md (CREATED - custom slash command)
├── MULTISITE-REMOVAL-COMPLETE.md (CREATED)
└── dev_journal.md (CREATED)

Server: /var/www/html/
├── wp-config.php (MODIFIED - multisite disabled)
├── .htaccess (MODIFIED - standard rules)
├── wp-content/
│   ├── themes/torly-theme/header.php (MODIFIED)
│   └── uploads/ (FILES COPIED from sites/2/)
└── Database:
    ├── wp_posts (MODIFIED - GUIDs updated)
    ├── wp_postmeta (MODIFIED - attachment paths)
    ├── wp_options (MODIFIED - permalinks, emails, pages)
    └── wp_blogs (1 site remaining, was 2)
```

---

## 5. Documentation & Developer Tools ✅

### 5.1 Custom Slash Command Created

**Objective**: Create `/devjournal` command for easy access to development journal

**Actions Taken**:
- Created `.claude/commands/` directory structure
- Implemented `/devjournal.md` slash command with comprehensive features:
  - Shows all important tasks completed (categorized)
  - Tracks git commits with `git log --oneline -10`
  - Lists all files modified
  - Shows key commands executed
  - Displays current project status
  - Interactive options for viewing, searching, and adding entries
  - Provides template for new entries
  - Maintains consistent markdown formatting

**Usage**:
```bash
# In Claude Code, simply type:
/devjournal
```

**Features**:
1. Latest entry date and summary
2. All important tasks (grouped by category)
3. Git commit history integration
4. Complete file modification list
5. Key commands reference
6. Interactive menu for:
   - Viewing full journal
   - Adding new entries
   - Searching topics/dates
   - Creating git commits

**Files Created**:
- `.claude/commands/devjournal.md` - Custom slash command definition

### 5.2 Development Journal Maintained

**Comprehensive Documentation Created**:
- Complete record of all work performed on November 17, 2025
- Detailed before/after comparisons for all changes
- Full command history with explanations
- Key learnings and best practices documented
- Statistics: 5 posts migrated, 10 attachments, ~25 WP-CLI commands
- Next steps identified for future improvements

**Files Created/Updated**:
- `dev_journal.md` - Main development journal (377 lines)
- `MULTISITE-REMOVAL-COMPLETE.md` - Detailed fix report
- `deployment/fix-multisite-removal.sh` - Automation script
- `.claude/commands/devjournal.md` - Slash command

---

## Status: ✅ Complete

All tasks completed successfully. WordPress is now running as a single-site installation with all blog posts accessible at `torly.ai/blog/` URLs. Email notifications are working properly. No errors or warnings.

**Site Status**: Production Ready ✅
**Email System**: Fully Functional ✅
**Blog Migration**: Complete ✅
**Multisite Removal**: Complete ✅

---

*Last Updated: November 17, 2025, 23:00 GMT*
