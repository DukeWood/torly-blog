# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

TorlyAI WordPress Setup is a custom WordPress configuration for a UK Innovator Visa AI Assistant service hosted at torly.ai. The project includes:

- Custom WordPress theme with visa assessment functionality
- Model Context Protocol (MCP) server for WordPress and GoDaddy API integration
- Automated deployment scripts for full server setup
- Custom REST API endpoints for visa assessments and contact forms

---

## 🎨 DESIGN SYSTEM - MANDATORY

**CRITICAL:** All UI/UX work MUST follow the TorlyAI Design System documented in `TORLYAI_DESIGN_SYSTEM.md`.

### Design System Rules

1. **ALWAYS consult `TORLYAI_DESIGN_SYSTEM.md` before:**
   - Creating new pages
   - Adding UI components
   - Writing CSS
   - Building forms
   - Implementing layouts

2. **REQUIRED Design Elements:**
   - ✅ Use exact colors from Section 1 (Color Palette)
   - ✅ Use typography scale from Section 2 (no custom font sizes)
   - ✅ Use spacing system from Section 3 (multiples of 0.25rem)
   - ✅ Use component patterns from Section 4
   - ✅ Apply gradient formulas from Section 10 (Granola.ai style)
   - ✅ Implement glass-morphism for primary CTAs (Section 11)
   - ✅ Follow responsive breakpoints from Section 8
   - ✅ Ensure WCAG 2.1 AA accessibility (Section 9)

3. **Color Usage:**
   ```css
   /* ALWAYS use these exact values */
   --color-yellow: hsl(60, 100%, 50%);
   --color-green: hsl(108, 100%, 50%);
   --color-orange: hsl(30, 100%, 50%);
   --color-chat-green: #10b981;
   --text-primary: #000000;
   --bg-primary: #ffffff;
   ```

4. **Typography Scale (NEVER use custom sizes):**
   - Hero (H1): `clamp(2.25rem, 5vw, 4.5rem)` / font-weight: 800
   - Section (H2): `clamp(1.875rem, 4vw, 3rem)` / font-weight: 700
   - Card (H3): `clamp(1.25rem, 2.5vw, 1.5rem)` / font-weight: 600
   - Body: `clamp(1rem, 1.5vw, 1.125rem)` / font-weight: 400

5. **Component Checklist:**
   - [ ] Buttons use glass-morphism (`.btn-primary`)
   - [ ] Cards have hover effects (`.feature-card:hover`)
   - [ ] Gradients use radial-gradient with HSL colors
   - [ ] Icons have gradient backgrounds (`.feature-icon`)
   - [ ] Forms follow validation patterns (Section 7)
   - [ ] Animations use Intersection Observer (Section 6)

6. **DO NOT:**
   - ❌ Create custom colors outside the palette
   - ❌ Use blue colors (old theme)
   - ❌ Use arbitrary font sizes
   - ❌ Skip hover states on interactive elements
   - ❌ Ignore responsive breakpoints
   - ❌ Forget focus states for accessibility

### Quick Reference Commands

```bash
# View design system
cat TORLYAI_DESIGN_SYSTEM.md

# Copy component code (example: button)
grep -A 20 "btn-primary" TORLYAI_DESIGN_SYSTEM.md

# Check color palette
grep -A 10 "Color Palette" TORLYAI_DESIGN_SYSTEM.md
```

### Example Implementation

When creating a new page:
1. Start with Section 12 (Usage Examples) template
2. Use components from Section 4
3. Apply gradients from Section 10
4. Test responsive design per Section 8
5. Verify accessibility per Section 9

---

## Key Commands

### MCP Server

```bash
# Install MCP dependencies (from mcp-integration/)
cd mcp-integration && npm install

# Start MCP server
npm start

# Development mode with auto-reload
npm run dev
```

**Important**: Before running the MCP server, copy `.env.example` to `.env` and configure:
- `WP_SITE_URL`: WordPress site URL
- `WP_USERNAME` and `WP_APP_PASSWORD`: WordPress application password (create in WP Admin → Users → Application Passwords)
- `GODADDY_API_KEY` and `GODADDY_API_SECRET`: GoDaddy API credentials

### WordPress Deployment

```bash
# Full deployment (requires root access on target server)
sudo bash deployment/deploy-script.sh
```

This script performs complete WordPress installation including:
- System dependencies (Apache, MySQL, PHP)
- WP-CLI installation
- Database creation and configuration
- Theme installation and activation
- SSL certificate via Let's Encrypt
- Essential plugins installation
- Backup system configuration

### WordPress Development

```bash
# Install WP-CLI (if not present)
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp

# Common WP-CLI commands
wp theme activate torly-theme --path=/var/www/html
wp plugin list --path=/var/www/html
wp rewrite flush --path=/var/www/html
wp post list --post_type=page --fields=ID,post_title --path=/var/www/html
```

## Architecture

### MCP Server Integration (mcp-integration/wordpress-mcp-server.js)

The MCP server provides 13 custom tools for WordPress and GoDaddy automation:

**WordPress Content Tools:**
- `wp_create_post`: Create blog posts with categories, tags, featured images
- `wp_update_post`: Update existing posts
- `wp_get_posts`: Retrieve and search posts
- `wp_delete_post`: Delete posts (with force option)
- `wp_create_page`: Create WordPress pages with templates
- `wp_upload_media`: Upload media files from URLs

**WordPress Administration Tools:**
- `wp_cli_command`: Execute any WP-CLI command
- `wp_create_menu`: Create navigation menus programmatically
- `wp_install_plugin`: Install and activate plugins from WordPress.org
- `wp_configure_multisite`: Configure WordPress Multisite for blog.torly.ai subdomain

**GoDaddy DNS Tools:**
- `godaddy_update_dns`: Update DNS records for torly.ai domain
- `godaddy_get_dns`: Retrieve current DNS configuration

**TorlyAI Custom Tool:**
- `torly_visa_assessment`: Submit visa assessment data to custom API endpoint

The server uses:
- WordPress REST API authentication via application passwords
- GoDaddy API authentication via sso-key
- WP-CLI for administrative tasks
- Axios for HTTP requests

### WordPress Theme (theme/torly-theme/)

**Custom Post Types:**
- `services`: Service offerings display
- `visa_resources`: Visa-related resources and documentation

**Custom REST API Endpoints (namespace: torlyai/v1):**
- `POST /visa-assessment`: Process visa application assessments
  - Calculates score based on innovation factors, business plan, growth potential
  - Returns recommendations and assessment score
  - Stores results in custom database table
- `POST /contact-form`: Handle contact form submissions via email
- `GET /blog-stats`: Retrieve blog statistics (post count, categories, recent posts)

**Database Schema:**
- Custom table: `wp_visa_assessments` (email, assessment_data JSON, created_at)
- Created on theme activation via `torlyai_create_tables()`

**Key Functions:**
- `calculate_visa_score($data)`: Visa assessment scoring algorithm (theme/torly-theme/functions.php:180)
- `get_visa_recommendations($data)`: Generate personalized recommendations (theme/torly-theme/functions.php:201)
- `TorlyAI_GoDaddy_Integration` class: GoDaddy API wrapper for domain management (theme/torly-theme/functions.php:249)

**Admin Interface:**
- Custom settings page at WP Admin → TorlyAI
- Configure GoDaddy API credentials
- Toggle MCP integration

### Deployment Architecture

The deployment script (deployment/deploy-script.sh) implements a complete LAMP stack setup:

1. **System Setup**: Apache2, MySQL, PHP 7.4+ with required extensions
2. **WordPress Core**: Downloaded via WP-CLI, configured with security headers
3. **Database**: Auto-generated secure passwords, proper privilege management
4. **Multisite Configuration**: Prepares for blog.torly.ai subdomain
5. **Security**: SSL via Certbot, secure .htaccess, protected wp-config.php
6. **Automation**: Cron jobs for wp-cron.php and daily backups
7. **Apache Virtual Hosts**: Separate configs for torly.ai and blog.torly.ai
8. **Backup System**: Daily automated backups to /var/backups/wordpress (30-day retention)

### Domain Configuration

The project is designed for:
- **Primary domain**: torly.ai (main site)
- **Subdomain**: blog.torly.ai (WordPress Multisite blog)
- **DNS Management**: Automated via GoDaddy API through MCP server

## Development Workflow

### When modifying the theme:

1. Theme files are in `theme/torly-theme/`
2. After changes, copy to WordPress installation: `cp -r theme/torly-theme /var/www/html/wp-content/themes/`
3. Flush rewrite rules: `wp rewrite flush --allow-root`

### When adding new MCP tools:

1. Add tool definition to `tools` array in wordpress-mcp-server.js:72
2. Implement handler function
3. Add case to `handleToolCall()` switch statement at wordpress-mcp-server.js:282
4. Test with `npm run dev`

### When modifying API endpoints:

1. Add route in `torlyai_register_api_routes()` at theme/torly-theme/functions.php:100
2. Implement callback function
3. Test endpoint: `curl -X POST https://torly.ai/wp-json/torlyai/v1/your-endpoint`
4. Update MCP server if endpoint should be accessible via MCP

## Important Notes

- **WordPress Path**: Default is `/var/www/html` (configurable in deploy-script.sh)
- **WP-CLI Path**: Default is `/usr/local/bin/wp` (set via WP_CLI_PATH env var)
- **Application Passwords**: Required for MCP authentication - generate in WordPress Admin
- **Database Prefix**: Uses `wp_` prefix (configurable in deploy-script.sh:25)
- **Memory Limits**: WordPress configured with 256M limit, 512M max (wp-config.php)
- **Cron Jobs**: WordPress cron runs every 5 minutes, backups daily at 2 AM

## Security Considerations

- Application passwords stored in .env (never commit)
- GoDaddy API credentials in environment variables
- wp-config.php protected via .htaccess
- JWT authentication configured for API access
- Force SSL for admin and login
- Security headers in .htaccess (X-Frame-Options, X-XSS-Protection, etc.)

## Automated Deployment Workflow

### Complete Zero-Budget Deployment (Oracle Cloud Free Tier)

**ONE-COMMAND DEPLOYMENT:**

```bash
# Install dependencies
cd mcp-integration && npm install && cd ..

# Run complete automated deployment
node automation/deploy-all.js
```

This single command orchestrates:
1. Oracle Cloud VM setup (semi-automated with prompts)
2. GoDaddy DNS configuration (automated via API)
3. WordPress installation (fully automated)
4. Free SSL certificate setup via Let's Encrypt (automated)
5. WordPress Multisite for blog.torly.ai (automated)
6. Blog structure creation (automated)
7. Sample content publication (automated)

**Total time:** ~30-40 minutes (mostly automated, ~15 min manual for Oracle account setup)

### Automation Scripts

#### 1. oracle-cloud-setup.js
**Purpose:** Semi-automate Oracle Cloud VM creation

**Usage:**
```bash
node automation/oracle-cloud-setup.js
```

**What it does:**
- Generates SSH key pair automatically
- Guides you through Oracle Cloud account creation
- Guides you through VM instance setup
- Saves credentials to `.credentials/oracle_credentials.json`

**Manual steps required:**
- Create Oracle Cloud account (5 min)
- Create VM instance via web UI (5 min)
- Provide VM IP and OCID

#### 2. ssl-setup.js
**Purpose:** Verify DNS propagation and SSL certificate status

**Usage:**
```bash
node automation/ssl-setup.js --domain torly.ai --ip <VM_IP>
```

**What it does:**
- Checks DNS propagation for all domains (torly.ai, www.torly.ai, blog.torly.ai)
- Waits up to 15 minutes for DNS to propagate
- Verifies SSL certificate installation and validity
- Tests SSL configuration for A+ rating
- Reports certificate expiry dates

#### 3. content-publisher.js
**Purpose:** Bulk publish blog posts from JSON

**Usage:**
```bash
# Dry run (test without publishing)
WP_SITE_URL=https://torly.ai WP_USERNAME=admin WP_APP_PASSWORD=xxx node automation/content-publisher.js --dry-run

# Actual publication
WP_SITE_URL=https://torly.ai WP_USERNAME=admin WP_APP_PASSWORD=xxx node automation/content-publisher.js
```

**What it does:**
- Reads blog posts from `content/blog-posts.json`
- Creates WordPress categories and tags automatically
- Uploads featured images
- Publishes posts with full SEO metadata
- Skips posts that already exist

**Blog posts included:**
1. UK Innovator Visa 2025: Complete Guide for Entrepreneurs
2. How to Prepare a Winning Business Plan for UK Innovator Visa
3. Top 5 UK Endorsing Bodies for Innovator Visa in 2025
4. UK Innovator Visa vs Scale-up Visa: Which is Right for You?
5. Success Story: From Startup Idea to UK Permanent Residence

#### 4. deploy-all.js
**Purpose:** Master orchestration script for complete deployment

**Usage:**
```bash
# Full deployment
node automation/deploy-all.js

# Skip Oracle setup (if already done)
node automation/deploy-all.js --skip-oracle

# Skip content publication
node automation/deploy-all.js --skip-content
```

**Workflow steps:**
1. **Oracle Cloud VM Setup** - Guides through VM creation
2. **DNS Configuration** - Updates GoDaddy DNS via API
3. **WordPress Deployment** - SSH deployment of LAMP stack
4. **SSL Setup** - Let's Encrypt certificates with A+ rating
5. **WordPress Configuration** - Multisite for blog subdomain
6. **Content Publication** - Publishes sample blog posts
7. **Final Verification** - Checks all endpoints

### Environment Variables

Create a `.env` file or set these in your shell:

```bash
# GoDaddy API (for DNS automation)
GODADDY_API_KEY=your_api_key
GODADDY_API_SECRET=your_api_secret

# WordPress (for content publishing and MCP server)
WP_SITE_URL=https://torly.ai
WP_USERNAME=admin
WP_APP_PASSWORD=your_application_password

# Optional: WP-CLI path
WP_CLI_PATH=/usr/local/bin/wp
```

### Manual Steps Summary

**Total manual time: ~20 minutes (one-time)**

1. **Oracle Cloud Account** (5 min)
   - Visit: https://cloud.oracle.com/free
   - Create account, verify email

2. **Oracle Cloud VM** (5 min)
   - Create VM instance (Always Free tier)
   - Use Ubuntu 22.04 image
   - Paste SSH public key (provided by script)

3. **GoDaddy API Keys** (2 min)
   - Visit: https://developer.godaddy.com/keys
   - Create production API key

4. **WordPress App Password** (2 min - after deployment)
   - Log into https://torly.ai/wp-admin
   - Create application password

Everything else is automated!

### Deployment Script Enhancements

The `deployment/deploy-script.sh` now includes:

- **DNS Propagation Check:** Waits up to 5 minutes before SSL request
- **SSL Verification:** Confirms certificate installation
- **A+ Rating Configuration:**
  - TLS 1.2+ only (no SSLv2/SSLv3/TLS 1.0/1.1)
  - Strong cipher suites (ECDHE, GCM, CHACHA20)
  - HSTS headers with preload
  - OCSP Stapling
- **Auto-renewal:** Cron job runs weekly to renew certificates
- **HTTPS Enforcement:** Automatic redirect from HTTP to HTTPS

### MCP Server Enhancements

The WordPress MCP server now has **17 tools** (up from 13):

**New Tools:**
1. `oracle_deploy_wordpress` - Deploy WordPress via SSH to Oracle Cloud
2. `wp_create_blog_structure` - Create blog categories and tags
3. `wp_bulk_create_posts` - Create multiple posts from JSON
4. `verify_ssl_status` - Check SSL certificate status and expiry

**Usage via MCP:**
```javascript
// Deploy WordPress to Oracle Cloud
mcp_call("oracle_deploy_wordpress", {
  vm_ip: "xxx.xxx.xxx.xxx",
  ssh_key_path: "/path/to/key",
  ssh_username: "ubuntu"
});

// Create blog structure
mcp_call("wp_create_blog_structure", {
  categories: ["UK Visa Guide", "Innovator Visa", "Business Immigration"],
  tags: ["UK Immigration", "Startup Visa", "Business Plan"]
});

// Bulk create posts
mcp_call("wp_bulk_create_posts", {
  posts: [...] // Array of post objects
});

// Verify SSL
mcp_call("verify_ssl_status", {
  domain: "torly.ai"
});
```

### Theme Enhancements

**HTTPS Enforcement (theme/torly-theme/functions.php):**
- Force HTTPS redirect on all pages
- Update site URLs to HTTPS automatically
- All generated URLs use HTTPS

**Default Blog Categories (auto-created on theme activation):**
- UK Visa Guide
- Innovator Visa
- Business Immigration
- Success Stories

**Default Tags:**
- UK Immigration
- Startup Visa
- Business Plan
- Endorsement
- Visa Application
- Entrepreneur
- Innovation
- Scale-up Visa

**SEO-Optimized Blog Template (theme/torly-theme/templates/blog-post-template.php):**
- Schema.org structured data
- Open Graph meta tags
- Social sharing buttons
- Related posts
- Author bio section
- Responsive design

### Content Management

**Blog Posts Location:** `content/blog-posts.json`

**Post Structure:**
```json
{
  "title": "Post Title",
  "excerpt": "SEO meta description",
  "content": "Full HTML content with headings, lists, etc.",
  "categories": ["Category 1", "Category 2"],
  "tags": ["Tag 1", "Tag 2"],
  "status": "publish",
  "featured_image_url": "https://images.unsplash.com/..."
}
```

To add more blog posts, edit `content/blog-posts.json` and run the content publisher.

### Troubleshooting

**DNS not propagating:**
```bash
# Check DNS status
node automation/ssl-setup.js --domain torly.ai --ip <VM_IP>

# Manual DNS check
nslookup torly.ai
dig torly.ai
```

**SSL certificate issues:**
```bash
# Verify certificate
openssl s_client -connect torly.ai:443 -servername torly.ai

# Check auto-renewal
ssh ubuntu@<VM_IP> "sudo certbot certificates"
```

**WordPress not accessible:**
```bash
# Check Apache status
ssh ubuntu@<VM_IP> "sudo systemctl status apache2"

# Check WordPress installation
ssh ubuntu@<VM_IP> "sudo wp --path=/var/www/html --allow-root core version"
```

## MCP Server Configuration in Claude Code

The MCP server is configured in `claude-code-config.json`. Update the paths and credentials:

```json
{
  "mcpServers": {
    "wordpress-torlyai": {
      "command": "node",
      "args": ["/path/to/torly-wordpress-setup/mcp-integration/wordpress-mcp-server.js"],
      "env": {
        "WP_SITE_URL": "https://torly.ai",
        "WP_USERNAME": "your_username",
        "WP_APP_PASSWORD": "your_app_password",
        "GODADDY_API_KEY": "your_api_key",
        "GODADDY_API_SECRET": "your_api_secret"
      }
    }
  }
}
```

## Cost Breakdown

**Total Cost: $0/month (Forever Free)**

- Oracle Cloud VM: $0 (Always Free tier)
- SSL Certificate (Let's Encrypt): $0
- Domain (torly.ai): Already owned
- GoDaddy DNS API: $0
- WordPress: $0 (open source)
- All automation scripts: $0

**No credit card charges. No hidden fees. Truly zero budget.**

## SMTP Configuration (Email Functionality)

### Lark Suite SMTP Setup

The WordPress installation is configured to send emails using Lark Suite (Feishu) SMTP service.

**Current Configuration:**
- **SMTP Host:** smtp.larksuite.com
- **Port:** 465 (SSL encryption)
- **From Email:** noreply@innovatorly.ai
- **Authentication:** noreply@innovatorly.ai
- **Plugin:** WP Mail SMTP v4.7.0

**Configuration Script:** `deployment/configure-smtp.sh`

**Key Features:**
- Cross-domain email: WordPress runs on torly.ai, emails sent from innovatorly.ai
- Separate configuration for both torly.ai and blog.torly.ai
- Secure SSL encryption (port 465)
- Automated test email functionality

**Manual Configuration:**

To update SMTP settings for both sites:

```bash
# For torly.ai main site
sudo -u www-data wp option update wp_mail_smtp '{
  "mail":{
    "from_email":"noreply@innovatorly.ai",
    "from_name":"Torly AI",
    "mailer":"smtp",
    "return_path":true
  },
  "smtp":{
    "host":"smtp.larksuite.com",
    "port":"465",
    "encryption":"ssl",
    "autotls":true,
    "auth":true,
    "user":"noreply@innovatorly.ai",
    "pass":"YOUR_SMTP_PASSWORD"
  }
}' --format=json --url=https://torly.ai --path=/var/www/html

# For blog.torly.ai site
sudo -u www-data wp option update wp_mail_smtp '{
  "mail":{
    "from_email":"noreply@innovatorly.ai",
    "from_name":"Torly AI Blog",
    "mailer":"smtp",
    "return_path":true
  },
  "smtp":{
    "host":"smtp.larksuite.com",
    "port":"465",
    "encryption":"ssl",
    "autotls":true,
    "auth":true,
    "user":"noreply@innovatorly.ai",
    "pass":"YOUR_SMTP_PASSWORD"
  }
}' --format=json --url=https://blog.torly.ai --path=/var/www/html
```

**Send Test Email:**

```bash
sudo -u www-data wp eval 'wp_mail("recipient@example.com", "Test Subject", "Test message body");' --url=https://torly.ai --path=/var/www/html
```

**Troubleshooting SMTP:**

1. **Authentication Errors:**
   - Verify SMTP credentials are correct
   - Check that the email account exists in Lark Suite
   - Ensure IMAP/SMTP is enabled for the mailbox

2. **Connection Errors:**
   - Test connectivity: `telnet smtp.larksuite.com 465`
   - Verify firewall allows outbound connections on port 465
   - Try alternate port 587 with STARTTLS encryption

3. **View Email Logs:**
   ```bash
   # Check WordPress debug log
   tail -f /var/www/html/wp-content/debug.log

   # Check Apache error log
   tail -f /var/log/apache2/error.log
   ```

**Lark Suite SMTP Settings Reference:**
- SSL Port: 465
- STARTTLS Port: 587
- SMTP Server: smtp.larksuite.com
- Authentication: Required (use mailbox email and IMAP/SMTP password)

**Security Notes:**
- SMTP credentials are stored in WordPress database (wp_options table)
- Never commit SMTP passwords to version control
- Use environment variables or secure credential storage for automation
- Regularly rotate SMTP passwords for security

## Recent Changes (November 17, 2025)

### Blog Site Merge & Multisite Removal

**Major architectural change:** WordPress converted from Multisite to single-site installation.

**Changes:**
1. **Blog Integration**
   - Blog moved from `blog.torly.ai` subdomain to `torly.ai/blog/` path
   - All 5 blog posts migrated successfully
   - Permalink structure updated to `/blog/%postname%/`
   - All posts accessible at `https://torly.ai/blog/article-slug/`

2. **Multisite Removal**
   - Disabled multisite constants in wp-config.php
   - Updated .htaccess from multisite rules to standard WordPress rules
   - Migrated files from `/wp-content/uploads/sites/2/` to `/wp-content/uploads/`
   - Updated all post GUIDs from blog.torly.ai to torly.ai/blog/

3. **Email Configuration**
   - Admin email: `noreply@innovatorly.ai` (changed from admin@torly.ai)
   - Reason: torly.ai has no email hosting/MX records, can only send via SMTP
   - All notifications now sent to valid receiving address

4. **Logo Updates**
   - Using PNG logo (`torlyai-logo.png`) instead of SVG for exact design match
   - File location: `/var/www/html/wp-content/themes/torly-theme/assets/torlyai-logo.png`
   - Header updated at line 68 to reference PNG file

5. **Blog Posts**
   - 5 posts successfully imported:
     - UK Innovator Visa 2026: Complete Guide for Entrepreneurs
     - How to Prepare a Winning Business Plan for UK Innovator Visa
     - Top 5 UK Endorsing Bodies for Innovator Visa in 2026
     - UK Innovator Visa vs Scale-up Visa: Which is Right for You?
     - Success Story: From Startup Idea to UK Permanent Residence
   - Each post has custom SVG cover image in `theme/torly-theme/assets/blog-covers/`

6. **Development Tools**
   - Created development journal: `dev_journal.md`
   - Created custom slash command: `/devjournal` (`.claude/commands/devjournal.md`)
   - Comprehensive documentation of all work performed

**Current Site Structure:**
- **Main Site:** https://torly.ai
- **Blog:** https://torly.ai/blog/
- **Mode:** Single-site WordPress (multisite disabled)
- **Logo:** PNG format (torlyai-logo.png)
- **Admin Email:** noreply@innovatorly.ai
- **SMTP:** Lark Suite (smtp.larksuite.com:465)

**Files Modified:**
- `/var/www/html/wp-config.php` - Multisite disabled
- `/var/www/html/.htaccess` - Standard WordPress rules
- `/var/www/html/wp-content/themes/torly-theme/header.php` - Logo and blog nav
- Database: wp_posts, wp_postmeta, wp_options

**Verification:**
- ✅ All blog posts accessible at /blog/ URLs
- ✅ Blog page loads at https://torly.ai/blog/
- ✅ Email notifications working without bounces
- ✅ Custom SVG covers displaying correctly
- ✅ WordPress in single-site mode
- ✅ Logo displaying exact PNG design
