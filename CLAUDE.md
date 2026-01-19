# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

TorlyAI WordPress Setup is a custom WordPress configuration for a UK Innovator Visa AI Assistant service hosted at torly.ai. The project includes:

- Custom WordPress theme with visa assessment functionality
- Model Context Protocol (MCP) server for WordPress and GoDaddy API integration
- Automated deployment scripts for Oracle Cloud
- Custom REST API endpoints for visa assessments and contact forms

**Single-site installation** - Blog content at `/blog/` path (not subdomain).

---

## Production Server (Oracle Cloud Always Free)

| Property | Value |
|----------|-------|
| **VM IP** | 141.147.89.179 |
| **Region** | uk-london-1 |
| **Shape** | VM.Standard.E2.1.Micro (Always Free) |
| **OS** | Ubuntu 22.04 LTS |
| **Login** | Google Authenticator 2FA |
| **Docs** | `docs/ORACLE_CLOUD_INFRASTRUCTURE.md` |

### SSH Access

```bash
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179
```

### Deploy Theme Changes

```bash
# Upload and deploy theme files
scp -i .credentials/ssh-key-2025-11-17.key theme/torly-theme/*.php ubuntu@141.147.89.179:/tmp/

ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179 "\
  sudo cp /tmp/*.php /var/www/html/wp-content/themes/torly-theme/ && \
  sudo chown www-data:www-data /var/www/html/wp-content/themes/torly-theme/*.php && \
  sudo -u www-data wp cache flush --path=/var/www/html"
```

### Deploy CSS

```bash
scp -i .credentials/ssh-key-2025-11-17.key theme/torly-theme/style.css ubuntu@141.147.89.179:/tmp/

ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179 "\
  sudo cp /tmp/style.css /var/www/html/wp-content/themes/torly-theme/ && \
  sudo chown www-data:www-data /var/www/html/wp-content/themes/torly-theme/style.css && \
  sudo -u www-data wp cache flush --path=/var/www/html"
```

---

## Design System (MANDATORY)

**All UI/UX work MUST follow `TORLYAI_DESIGN_SYSTEM.md`.**

### Colors (use exact values)
```css
--color-yellow: hsl(60, 100%, 50%);
--color-green: hsl(108, 100%, 50%);
--color-orange: hsl(30, 100%, 50%);
--color-chat-green: #10b981;  /* For links and CTAs */
--text-primary: #000000;
--bg-primary: #ffffff;
```

### Key Rules
- Yellow: gradients/accents only, NOT for text or solid backgrounds
- Chat Green (#10b981): primary CTAs and links
- Gradient backgrounds: always low opacity (0.2-0.3)
- Primary buttons: glass-morphism with backdrop-filter
- Secondary buttons: transparent bg, 2px solid border

### Typography Scale
- Hero (H1): `clamp(2.25rem, 5vw, 4.5rem)` / weight: 800
- Section (H2): `clamp(1.875rem, 4vw, 3rem)` / weight: 700
- Card (H3): `clamp(1.25rem, 2.5vw, 1.5rem)` / weight: 600
- Body: `clamp(1rem, 1.5vw, 1.125rem)` / weight: 400

---

## Key Commands

### MCP Server

```bash
cd mcp-integration && npm install
npm start          # Start server
npm run dev        # Development with auto-reload
```

**Required `.env` configuration:**
- `WP_SITE_URL`, `WP_USERNAME`, `WP_APP_PASSWORD`
- `GODADDY_API_KEY`, `GODADDY_API_SECRET`

### Tests

```bash
# All tests
npx playwright test

# Single test file
npx playwright test tests/cursor-test.spec.js

# Single browser
npx playwright test --project=chromium

# With UI
npx playwright test --ui
```

### WordPress CLI (on server)

```bash
# Common commands (run via SSH)
wp theme activate torly-theme --path=/var/www/html
wp plugin list --path=/var/www/html
wp rewrite flush --path=/var/www/html
wp cache flush --path=/var/www/html
wp option get siteurl --path=/var/www/html
```

### Full Deployment

```bash
cd mcp-integration && npm install && cd ..
node automation/deploy-all.js
```

---

## Architecture

### Directory Structure

```
torly-blog/
├── theme/torly-theme/       # WordPress theme
│   ├── style.css            # Main styles (version in header)
│   ├── functions.php        # Theme functions, API endpoints
│   ├── front-page.php       # Homepage template
│   ├── footer.php           # Footer (loads on all pages)
│   └── assets/              # Images, fonts
├── mcp-integration/         # MCP server
│   └── wordpress-mcp-server.js
├── automation/              # Deployment scripts
├── deployment/              # Server setup scripts
├── tests/                   # Playwright tests
├── .credentials/            # SSH keys, API credentials (gitignored)
└── docs/                    # Documentation
```

### WordPress Theme

**Custom REST API Endpoints (namespace: `torlyai/v1`):**
- `POST /visa-assessment` - Process visa assessments, calculate scores
- `POST /contact-form` - Handle contact submissions
- `GET /blog-stats` - Blog statistics

**Key Functions in `functions.php`:**
- `torlyai_validate_and_fix_urls()` - Auto-fixes corrupted siteurl/home
- `calculate_visa_score($data)` - Visa assessment algorithm
- `get_visa_recommendations($data)` - Generate recommendations
- `torlyai_enqueue_scripts()` - Enqueue styles/scripts with dynamic versioning

**Database:**
- Custom table: `wp_visa_assessments`
- Database name: `torly_wordpress`

### MCP Server (17 tools)

**WordPress Tools:** `wp_create_post`, `wp_update_post`, `wp_get_posts`, `wp_delete_post`, `wp_create_page`, `wp_upload_media`, `wp_cli_command`, `wp_create_menu`, `wp_install_plugin`, `wp_create_blog_structure`, `wp_bulk_create_posts`

**GoDaddy Tools:** `godaddy_update_dns`, `godaddy_get_dns`

**Deployment Tools:** `oracle_deploy_wordpress`, `verify_ssl_status`

**Custom:** `torly_visa_assessment`

---

## Development Workflow

### Modifying Theme

1. Edit files in `theme/torly-theme/`
2. **Bump version** in `style.css` header (forces cache refresh)
3. Deploy using SSH commands above
4. Verify: `curl -s https://torly.ai | grep 'style.css?ver='`

### Adding MCP Tools

1. Add tool definition to `tools` array in `wordpress-mcp-server.js`
2. Implement handler function
3. Add case to `handleToolCall()` switch
4. Test with `npm run dev`

### Adding API Endpoints

1. Add route in `torlyai_register_api_routes()` in `functions.php`
2. Implement callback function
3. Test: `curl -X POST https://torly.ai/wp-json/torlyai/v1/your-endpoint`

---

## Important Notes

- **WordPress Path:** `/var/www/html`
- **Theme Version:** Update in `style.css` header to bust cache
- **URL Corruption:** If site breaks, check `siteurl` and `home` options in database
- **Blog URL:** `https://torly.ai/blog/` (NOT subdomain)
- **SMTP:** Lark Suite (`smtp.larksuite.com:465`) from `noreply@innovatorly.ai`
- **Credentials:** Never commit `.credentials/` or `.env` files

### Quick Health Check

```bash
# Check site status
curl -sI https://torly.ai | head -3

# Check VM services
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179 \
  "systemctl is-active apache2 mysql && wp core version --path=/var/www/html"
```

---

## Related Documentation

- `TORLYAI_DESIGN_SYSTEM.md` - Complete design system
- `docs/ORACLE_CLOUD_INFRASTRUCTURE.md` - Server details, Always Free tier
- `CHANGELOG.md` - Version history and changes
- `ORACLE_VM_INCIDENT_LOG.md` - Incident reports and resolutions
