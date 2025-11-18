# TorlyAI WordPress Setup

A complete WordPress deployment solution for [torly.ai](https://torly.ai) - an AI-powered UK Innovator Visa Assistant service.

## Overview

This repository contains everything needed to deploy a production-ready WordPress website with custom theme, MCP (Model Context Protocol) integration, automated deployment scripts, and email functionality.

**Live Sites:**
- Main Site: https://torly.ai
- Blog: https://torly.ai/blog/

**Key Features:**
- Zero-cost deployment on Oracle Cloud Free Tier
- Automated setup with one-command deployment
- Custom WordPress theme with visa assessment functionality
- MCP server for WordPress automation
- GoDaddy DNS integration
- Free SSL certificates (Let's Encrypt)
- SMTP email configuration with Lark Suite
- Integrated blog at /blog/ with custom SVG covers (single-site setup)

## Quick Start

### One-Command Deployment

```bash
# Install dependencies
cd mcp-integration && npm install && cd ..

# Run complete automated deployment
node automation/deploy-all.js
```

**Total deployment time:** ~30-40 minutes (mostly automated)

See [Automated Deployment](#automated-deployment) section for details.

## Project Structure

```
torly-wordpress-setup/
├── README.md                   # This file
├── CLAUDE.md                   # Detailed technical documentation for AI assistants
├── .claude/                    # Claude Code configuration
│   └── settings.local.json
├── .credentials/               # SSH keys and credentials (gitignored)
│   ├── ssh-key-*.key
│   └── oracle_credentials.json
├── theme/                      # Custom WordPress theme
│   └── torly-theme/
│       ├── style.css
│       ├── functions.php       # Custom API endpoints, GoDaddy integration
│       ├── header.php
│       ├── footer.php
│       ├── front-page.php      # Homepage template
│       ├── home.php            # Blog listing template
│       ├── single.php          # Single post template
│       └── assets/             # SVG logos, images
├── mcp-integration/            # Model Context Protocol server
│   ├── wordpress-mcp-server.js # 17 WordPress & GoDaddy automation tools
│   ├── package.json
│   └── .env.example
├── deployment/                 # Deployment scripts
│   ├── deploy-script.sh        # Complete WordPress installation
│   ├── configure-smtp.sh       # SMTP configuration for email
│   └── setup-ssl.sh            # SSL certificate setup
├── automation/                 # Node.js automation scripts
│   ├── deploy-all.js           # Master orchestration script
│   ├── oracle-cloud-setup.js   # Oracle Cloud VM setup guide
│   ├── ssl-setup.js            # SSL verification and monitoring
│   ├── content-publisher.js    # Bulk blog post publishing
│   └── godaddy-dns-playwright.js # GoDaddy DNS automation
├── content/                    # Blog content
│   └── blog-posts.json         # Sample blog posts in JSON format
└── config/                     # Configuration templates
    ├── wp-config-custom.php
    ├── .htaccess
    └── nginx.conf
```

## Prerequisites

### Required
- **Domain Name:** torly.ai (configured on GoDaddy)
- **Node.js:** v14+ (for automation scripts and MCP server)
- **GoDaddy API Keys:** For DNS automation ([Get keys](https://developer.godaddy.com/keys))

### Optional (for full deployment)
- **Oracle Cloud Account:** Free tier ([Sign up](https://cloud.oracle.com/free))
- **Lark Suite Account:** For SMTP email service ([Sign up](https://www.larksuite.com/))

## Installation

### Method 1: Automated Deployment (Recommended)

```bash
# Clone repository
git clone https://github.com/yourusername/torly-wordpress-setup.git
cd torly-wordpress-setup

# Install dependencies
cd mcp-integration && npm install && cd ..

# Configure environment variables
cp mcp-integration/.env.example mcp-integration/.env
# Edit .env with your credentials

# Run deployment
node automation/deploy-all.js
```

The script will guide you through:
1. Oracle Cloud VM setup (or skip if using existing server)
2. GoDaddy DNS configuration
3. WordPress installation (single-site)
4. SSL certificate setup
5. ~~WordPress Multisite configuration~~ **DEPRECATED** - Single-site only
6. Content publication at `/blog/` path

### Method 2: Manual Deployment

See [CLAUDE.md](./CLAUDE.md) for detailed manual deployment instructions.

## Configuration

### Environment Variables

Create `mcp-integration/.env`:

```bash
# GoDaddy API (for DNS automation)
GODADDY_API_KEY=your_api_key_here
GODADDY_API_SECRET=your_api_secret_here

# WordPress (for MCP server and content publishing)
WP_SITE_URL=https://torly.ai
WP_USERNAME=admin
WP_APP_PASSWORD=your_wordpress_app_password

# Optional: Custom WP-CLI path
WP_CLI_PATH=/usr/local/bin/wp
```

### SMTP Email Configuration

The deployment includes SMTP configuration using Lark Suite:

**Current Setup:**
- SMTP Host: smtp.larksuite.com
- Port: 465 (SSL)
- From Email: noreply@innovatorly.ai
- Plugin: WP Mail SMTP v4.7.0

**Configure SMTP:**

```bash
# Run SMTP configuration script
ssh ubuntu@YOUR_VM_IP "sudo bash /var/www/html/deployment/configure-smtp.sh"
```

**Manual SMTP Configuration:**

See [CLAUDE.md - SMTP Configuration](./CLAUDE.md#smtp-configuration-email-functionality) for detailed manual setup instructions.

## Features

### Custom WordPress Theme

**TorlyAI Theme** (`theme/torly-theme/`)

Features:
- Responsive design with custom CSS
- Hero section with visa assessment CTA
- Feature showcase grid
- Blog integration with recent posts
- Custom navigation menu
- SVG logo and favicon
- Mobile-optimized

**Custom REST API Endpoints:**

- `POST /wp-json/torlyai/v1/visa-assessment` - Process visa assessments
- `POST /wp-json/torlyai/v1/contact-form` - Handle contact form submissions
- `GET /wp-json/torlyai/v1/blog-stats` - Retrieve blog statistics

**Custom Database Tables:**
- `wp_visa_assessments` - Store visa assessment data

### MCP Server Integration

**17 WordPress & GoDaddy Automation Tools:**

**Content Management:**
- `wp_create_post` - Create blog posts
- `wp_update_post` - Update posts
- `wp_get_posts` - Search and retrieve posts
- `wp_delete_post` - Delete posts
- `wp_create_page` - Create WordPress pages
- `wp_upload_media` - Upload images from URLs
- `wp_bulk_create_posts` - Create multiple posts from JSON

**WordPress Administration:**
- `wp_cli_command` - Execute any WP-CLI command
- `wp_create_menu` - Create navigation menus
- `wp_install_plugin` - Install plugins
- `wp_configure_multisite` - ~~Setup multisite~~ **DEPRECATED** - Single-site only
- `wp_create_blog_structure` - Create blog categories/tags

**DNS Management:**
- `godaddy_update_dns` - Update DNS records
- `godaddy_get_dns` - Retrieve DNS configuration

**Deployment:**
- `oracle_deploy_wordpress` - Deploy WordPress via SSH
- `verify_ssl_status` - Check SSL certificate status

**Custom Tools:**
- `torly_visa_assessment` - Submit visa assessments

**Start MCP Server:**

```bash
cd mcp-integration
npm start
```

### Automated Deployment Scripts

#### deploy-all.js - Master Orchestrator

Complete end-to-end deployment automation.

```bash
# Full deployment
node automation/deploy-all.js

# Skip Oracle setup (if VM already exists)
node automation/deploy-all.js --skip-oracle

# Skip content publication
node automation/deploy-all.js --skip-content
```

**Workflow:**
1. Oracle Cloud VM setup
2. DNS configuration (GoDaddy API)
3. WordPress installation (LAMP stack, single-site)
4. SSL certificates (Let's Encrypt)
5. ~~WordPress Multisite setup~~ **DEPRECATED** - Single-site configuration
6. Blog content publication at `/blog/` path
7. Final verification

#### content-publisher.js - Bulk Content Publishing

Publish blog posts from JSON to WordPress.

```bash
# Dry run (test without publishing)
WP_SITE_URL=https://torly.ai WP_USERNAME=admin WP_APP_PASSWORD=xxx \
  node automation/content-publisher.js --dry-run

# Publish posts
WP_SITE_URL=https://torly.ai WP_USERNAME=admin WP_APP_PASSWORD=xxx \
  node automation/content-publisher.js
```

**Features:**
- Automatically creates categories and tags
- Uploads featured images from URLs
- Skips duplicate posts
- Full SEO metadata support

#### ssl-setup.js - SSL Certificate Verification

Monitor SSL certificate status and DNS propagation.

```bash
node automation/ssl-setup.js --domain torly.ai --ip YOUR_VM_IP
```

**Checks:**
- DNS propagation for all domains
- SSL certificate validity
- Certificate expiry dates
- SSL configuration strength

### Security Features

- **SSL/TLS:** Let's Encrypt certificates with auto-renewal
- **HTTPS Enforcement:** Automatic HTTP to HTTPS redirect
- **Security Headers:** X-Frame-Options, X-XSS-Protection, HSTS
- **WordPress Security:**
  - Application passwords for API access
  - Protected wp-config.php
  - Secure database credentials
  - Regular automated backups
- **SMTP Security:** SSL encryption for email transmission

## Usage

### Common WP-CLI Commands

```bash
# SSH into server
ssh ubuntu@YOUR_VM_IP

# List all posts
wp post list --path=/var/www/html

# Activate theme
wp theme activate torly-theme --path=/var/www/html

# Install plugin
wp plugin install wordfence --activate --path=/var/www/html

# Flush rewrite rules
wp rewrite flush --path=/var/www/html

# Create a new page
wp post create --post_type=page --post_title='About Us' --post_status=publish --path=/var/www/html
```

### Content Management

**Add Blog Posts:**

1. Edit `content/blog-posts.json`
2. Add new post objects with title, content, categories, tags
3. Run content publisher:
   ```bash
   WP_SITE_URL=https://torly.ai WP_USERNAME=admin WP_APP_PASSWORD=xxx \
     node automation/content-publisher.js
   ```

**Post Structure:**

```json
{
  "title": "Your Post Title",
  "excerpt": "SEO meta description",
  "content": "<p>Full HTML content...</p>",
  "categories": ["UK Visa Guide", "Innovator Visa"],
  "tags": ["UK Immigration", "Business Plan"],
  "status": "publish",
  "featured_image_url": "https://images.unsplash.com/photo-..."
}
```

### Theme Customization

**Modify Theme Files:**

```bash
# Local development
cd theme/torly-theme/
# Edit files: functions.php, style.css, templates, etc.

# Deploy to server
scp -r theme/torly-theme ubuntu@YOUR_VM_IP:/tmp/
ssh ubuntu@YOUR_VM_IP "sudo cp -r /tmp/torly-theme /var/www/html/wp-content/themes/"
ssh ubuntu@YOUR_VM_IP "sudo chown -R www-data:www-data /var/www/html/wp-content/themes/torly-theme"

# Flush rewrite rules if modifying routes
ssh ubuntu@YOUR_VM_IP "sudo wp rewrite flush --path=/var/www/html --allow-root"
```

## Troubleshooting

### SMTP Email Issues

**Problem:** Emails not sending

**Solutions:**
1. Verify SMTP credentials in WordPress settings
2. Check SMTP connection:
   ```bash
   telnet smtp.larksuite.com 465
   ```
3. View WordPress debug log:
   ```bash
   ssh ubuntu@YOUR_VM_IP "tail -f /var/www/html/wp-content/debug.log"
   ```
4. Test email manually:
   ```bash
   ssh ubuntu@YOUR_VM_IP "sudo -u www-data wp eval 'wp_mail(\"test@example.com\", \"Test\", \"Message\");' --path=/var/www/html"
   ```

See [CLAUDE.md - Troubleshooting SMTP](./CLAUDE.md#troubleshooting-smtp) for detailed troubleshooting steps.

### DNS Propagation Issues

**Problem:** Domain not pointing to server

**Check DNS:**
```bash
# Check current DNS
nslookup torly.ai
dig torly.ai

# Monitor DNS propagation
node automation/ssl-setup.js --domain torly.ai --ip YOUR_VM_IP
```

### SSL Certificate Issues

**Problem:** SSL certificate not working

**Solutions:**
1. Verify DNS is propagated (wait up to 24 hours)
2. Check certificate status:
   ```bash
   ssh ubuntu@YOUR_VM_IP "sudo certbot certificates"
   ```
3. Manually renew certificate:
   ```bash
   ssh ubuntu@YOUR_VM_IP "sudo certbot renew --force-renewal"
   ```
4. Test SSL configuration:
   ```bash
   openssl s_client -connect torly.ai:443 -servername torly.ai
   ```

### WordPress Not Accessible

**Check Services:**
```bash
# Check Apache
ssh ubuntu@YOUR_VM_IP "sudo systemctl status apache2"

# Check MySQL
ssh ubuntu@YOUR_VM_IP "sudo systemctl status mysql"

# Restart services
ssh ubuntu@YOUR_VM_IP "sudo systemctl restart apache2 mysql"
```

## Cost Breakdown

**Total Cost: $0/month (Forever Free)**

| Service | Cost |
|---------|------|
| Oracle Cloud VM (Always Free tier) | $0 |
| SSL Certificate (Let's Encrypt) | $0 |
| Domain (torly.ai) | Already owned |
| GoDaddy DNS API | $0 |
| WordPress | $0 (open source) |
| Lark Suite (email) | $0 (free tier) |
| All automation scripts | $0 |

**Total:** $0/month - No credit card charges. No hidden fees. Truly zero budget.

## Development

### Local Development Setup

```bash
# Install XAMPP/WAMP/MAMP for local WordPress

# Clone repository
git clone https://github.com/yourusername/torly-wordpress-setup.git

# Copy theme to WordPress
cp -r torly-wordpress-setup/theme/torly-theme /path/to/wordpress/wp-content/themes/

# Activate theme
wp theme activate torly-theme --path=/path/to/wordpress

# Start MCP server
cd torly-wordpress-setup/mcp-integration
npm install
npm run dev
```

### Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Documentation

- **[CLAUDE.md](./CLAUDE.md)** - Comprehensive technical documentation for developers and AI assistants
- **[Theme Documentation](./theme/torly-theme/README.md)** - Theme-specific documentation (if available)
- **[MCP Server Documentation](./mcp-integration/README.md)** - MCP server API reference (if available)

## Resources

### WordPress
- [WordPress Codex](https://codex.wordpress.org/)
- [WP-CLI Documentation](https://wp-cli.org/)
- [WordPress REST API](https://developer.wordpress.org/rest-api/)

### Infrastructure
- [Oracle Cloud Free Tier](https://www.oracle.com/cloud/free/)
- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)
- [Apache Documentation](https://httpd.apache.org/docs/)

### APIs
- [GoDaddy API Documentation](https://developer.godaddy.com/)
- [Lark Suite API](https://open.larksuite.com/)
- [Model Context Protocol](https://modelcontextprotocol.io/)

### Plugins Used
- [WP Mail SMTP](https://wordpress.org/plugins/wp-mail-smtp/) - Email functionality
- [Contact Form 7](https://wordpress.org/plugins/contact-form-7/) - Contact forms
- [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/) - SEO optimization

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For issues, questions, or contributions:

- **Issues:** [GitHub Issues](https://github.com/yourusername/torly-wordpress-setup/issues)
- **Documentation:** [CLAUDE.md](./CLAUDE.md)
- **Website:** [torly.ai](https://torly.ai)
- **Blog:** [torly.ai/blog](https://torly.ai/blog/)

## Acknowledgments

- WordPress Community
- Oracle Cloud Free Tier
- Let's Encrypt
- GoDaddy Developer API
- Lark Suite
- Model Context Protocol (Anthropic)
- All open-source contributors

---

**Built with ❤️ for UK Innovator Visa applicants**

*Helping entrepreneurs navigate the UK visa process with AI-powered assistance*

---

## Recent Updates

### November 18, 2025 - Design System Compliance Audit

Comprehensive audit and fixes to achieve 100% compliance with TorlyAI Design System:

**Issues Fixed:**
- ✅ **Social Media Icons**: Changed hover color from yellow to chat-green (#10b981)
- ✅ **Secondary Buttons**: Complete rewrite - transparent bg, 2px border, correct hover states
- ✅ **Step Numbers**: Fixed gradient opacity from 100% to 25% for subtle appearance
- ✅ **Button Width**: Made buttons compact (fit-content) instead of stretched full-width

**Testing Framework:**
- Created `tests/design-system-compliance.spec.js` (6 automated Playwright tests)
- Created `DESIGN_SYSTEM_AUDIT.md` (comprehensive compliance documentation)
- All tests passing - 100% design system compliant

**Design System Rules Enforced:**
1. Yellow = gradients/accents only (NOT interactive elements)
2. Chat-green = links and CTAs
3. Gradient backgrounds = low opacity (0.2-0.3)
4. Secondary buttons = transparent, 2px solid border
5. Primary buttons = glass-morphism with backdrop-filter
6. Button sizing = compact and content-sized

**Run Tests:**
```bash
npx playwright test tests/design-system-compliance.spec.js
```

See [CLAUDE.md - Design System Compliance](./CLAUDE.md#design-system-compliance-audit--fixes-november-18-2025) for detailed documentation.

### November 18, 2025 - CTA Button Centering Fix

Fixed critical flexbox alignment issue causing CTA buttons to appear left-aligned instead of centered:

**Root Causes:**
- ❌ Missing `align-items: center` in `.cta-buttons` flexbox container
- ❌ Buttons constrained to `width: 20%` instead of `width: fit-content`

**Fixes Applied:**
- ✅ Added `align-items: center` for cross-axis centering (horizontal in column layout)
- ✅ Changed button width from `20%` to `fit-content` for natural sizing
- ✅ Created proper column-layout test (`test-cta-column-centering.js`)

**Results:**
- Button centering precision: 0.00-0.01px (mathematically perfect)
- Test results: All buttons centered with < 1px tolerance
- Visual appearance: Clean, compact buttons properly aligned

**Key Learning:**
Flexbox axes swap with `flex-direction` change. In column layout, `align-items` controls horizontal alignment, not `justify-content`. Always specify both alignment properties for responsive flexbox.

**Documentation:**
- Comprehensive lessons learned in `dev_journal.md` (300+ lines)
- Debugging process, prevention checklist, best practices
- Time investment: ~3 hours (now documented to save future time)

### November 18, 2025 - Blog Navigation Fix

Fixed blog navigation issues after multisite removal:
- **Fixed**: All navigation links updated from `blog.torly.ai` to `/blog/` path
- **Updated**: 3 theme files (front-page.php, footer.php, home.php)
- **Cleaned**: Removed all database references to old subdomain
- **Verified**: Blog button works correctly across all pages

### November 17, 2025 - Blog Site Merge & Architecture Change

The WordPress installation has been updated from Multisite to single-site configuration:

- **Blog Location**: Moved from `blog.torly.ai` subdomain to `torly.ai/blog/` path
- **Posts**: All 5 blog posts successfully migrated with custom SVG covers
- **Configuration**: Single-site WordPress (multisite disabled)
- **Logo**: PNG format for exact design match
- **Permalinks**: All posts at `https://torly.ai/blog/article-slug/`

See [CLAUDE.md](./CLAUDE.md) and `dev_journal.md` for detailed documentation of changes.
