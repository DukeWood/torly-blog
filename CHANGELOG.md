# Changelog

All notable changes to the TorlyAI WordPress Setup project.

---

## [3.0.4] - 2026-01-09

### Removed
- Custom cursor system removed - now using standard browser cursor on all pages

### Fixed
- Cursor visibility issues on blog pages
- Duplicate cursor scripts on homepage
- CSS variable compatibility across pages

---

## [3.0.3] - 2026-01-09

### Fixed
- Cursor invisible on blog pages due to CSS variable `var(--black)` not defined
- Changed to hardcoded `#000000` for cross-page compatibility
- Removed duplicate cursor script from `front-page.php` (now only in `footer.php`)
- Dynamic version in `wp_enqueue_style()` using `wp_get_theme()->get('Version')`

---

## [3.0.2] - 2026-01-09

### Fixed
- Custom cursor refinements - starts hidden until mouse moves
- Changed cursor from gradient to solid black for visibility
- Fixed `border-radius` issue with `border-image`
- Added cursor JS implementation to `footer.php` for all pages

### Added
- MySQL OOM Kill incident #2 documentation (2025-11-20)

---

## [3.0.0] - 2025-11-20 - Premium Maximalism Transformation

### Added
- Premium Maximalism design aesthetic
- Typography upgrade: 'DM Serif Display' + 'Plus Jakarta Sans'
- Noise texture overlay for film-like grain
- Floating blur orbs in hero section
- Enhanced gradient meshes (5 layers)
- Premium button glow effects with pulse animation
- Custom cursor system with spring physics
- Gradient border system for cards

### Changed
- Image optimization: 3.0MB → 696KB (77% reduction via WebP)
- Application Journey section reordered and compacted
- UK Visa Statistics changed to 1×4 grid layout
- Removed "Simple 4-Step Process" section

### Fixed
- JavaScript bug: Undeclared `userWaitlistEmail` variable
- JavaScript bug: Null element access on `waitlistForm`
- All 19 product showcase images now WebP format

---

## [2.x] - 2025-11-18 - Design System Compliance

### Fixed
- CTA button centering with flexbox alignment
- Social media icons hover color (yellow → chat-green)
- Secondary button styling (transparent bg, 2px border)
- Step number gradient opacity (full → 25%)
- Button width on mobile (stretched → fit-content)

### Added
- `tests/design-system-compliance.spec.js` - Automated compliance tests
- `DESIGN_SYSTEM_AUDIT.md` - Complete audit documentation

---

## [2.x] - 2025-11-18 - WordPress URL Corruption Fix

### Fixed
- WordPress `siteurl` and `home` options corrupted (contained only `https:`)
- Blog navigation redirecting to homepage
- Added automatic URL validation in `functions.php`

### Added
- `deployment/fix-blog-redirect.php`
- `deployment/fix-wordpress-urls.php`
- `deployment/check-godaddy-dns.js`

---

## [2.x] - 2025-11-18 - Blog Navigation Fix

### Fixed
- Blog navigation button non-functional due to multisite references
- Updated `front-page.php`: Removed multisite domain check, fixed blog URL
- Updated `footer.php`: Fixed blog link URL
- Updated `home.php`: Comment update for `/blog/` path
- Database GUID entries updated

---

## [2.0.0] - 2025-11-17 - Multisite to Single-Site Conversion

### Changed
- **Major:** Converted from WordPress Multisite to single-site installation
- Blog moved from `blog.torly.ai` subdomain to `torly.ai/blog/` path
- Permalink structure: `/blog/%postname%/`
- Admin email: `admin@torly.ai` → `noreply@innovatorly.ai`
- Logo: SVG → PNG format (`torlyai-logo.png`)

### Removed
- Multisite constants in `wp-config.php`
- Multisite `.htaccess` rules
- All references to `blog.torly.ai` subdomain

### Migrated
- 5 blog posts to new URL structure
- Files from `/wp-content/uploads/sites/2/` to `/wp-content/uploads/`
- All post GUIDs updated

---

## [1.0.0] - 2025-11-17 - Initial Deployment

### Added
- Custom WordPress theme (`torly-theme`)
- MCP server with 17 tools for WordPress/GoDaddy automation
- Automated deployment scripts for Oracle Cloud
- SSL configuration via Let's Encrypt
- SMTP configuration via Lark Suite
- Visa assessment REST API endpoints
- Blog structure with 5 initial posts

### Infrastructure
- Oracle Cloud VM (Always Free tier)
- Ubuntu 22.04 LTS
- Apache 2.4 + MySQL 8.x + PHP 7.4+
- GoDaddy DNS management via API
