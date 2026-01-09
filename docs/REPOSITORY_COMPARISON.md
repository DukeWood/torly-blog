# Torly Ecosystem - Repository Comparison

> Comprehensive documentation of the two-repository architecture for the Torly ecosystem

**Last Updated**: January 9, 2026

---

## Executive Summary

The Torly ecosystem consists of two separate repositories that work together:

| Repository | Purpose | Deployment Status | Tech Stack |
|------------|---------|-------------------|------------|
| **torlyAI** | SaaS Application | ❌ **NOT DEPLOYED** (local dev only) | Next.js 14, Supabase, Stripe |
| **torly-wordpress-setup** | WordPress Backend + Deployment | ✅ **LIVE** at torly.ai (Oracle VM) | WordPress, PHP, MySQL |

```
┌─────────────────────────────────────────────────────────────────┐
│                     TORLY ECOSYSTEM                              │
├─────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌──────────────────────┐      ┌──────────────────────────────┐ │
│  │     torlyAI          │      │  torly-wordpress-setup       │ │
│  │  (Next.js SaaS App)  │      │  (WordPress + Deployment)    │ │
│  │                      │      │                              │ │
│  │  - 31 AI Skills      │      │  - WordPress Theme           │ │
│  │  - Stripe Payments   │      │  - Blog/Content              │ │
│  │  - Supabase DB       │      │  - Waitlist Feature          │ │
│  │  - Autopilot Engine  │      │  - Deployment Scripts        │ │
│  │                      │      │                              │ │
│  │  Status: LOCAL ONLY  │      │  Hosted: Oracle Cloud VM     │ │
│  │  Port: 3000          │      │  Domain: torly.ai            │ │
│  └──────────────────────┘      └──────────────────────────────┘ │
│                                                                  │
└─────────────────────────────────────────────────────────────────┘
```

---

## Repository 1: torlyAI

### Purpose
AI-powered UK Innovator Visa application platform. A 3-tier freemium SaaS that funnels users from free DIY tools to premium subscriptions, and ultimately to InnovatorlyAI's bootcamp program.

### Location
```
/Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI/
```

### Technology Stack
- **Framework**: Next.js 14 (App Router)
- **Language**: TypeScript
- **Styling**: Tailwind CSS + Lucide React icons
- **Database**: Supabase (PostgreSQL)
- **Payments**: Stripe
- **AI Providers**: OpenAI, Anthropic, DeepSeek, Google Gemini
- **Testing**: Jest + Playwright

### Deployment Status
- **Status**: ❌ **NOT DEPLOYED** - Local development only
- **Dev Server**: `npm run dev` → http://localhost:3000
- **Backend API**: Supabase project (configured in `.env`)
- **Payments**: Stripe (configured in `.env`)

> **Note**: The domain `torly.ai` currently points to the WordPress site (torly-wordpress-setup), NOT this Next.js app.

### Business Tiers
| Tier | Price | Features |
|------|-------|----------|
| Free | $0 | BYOK chat, basic tools |
| Premium | $99/month | 31 AI Skills, Learning Hub, resources |
| Autopilot | Custom | Full 6-week visa package preparation |

### File Structure

```
torlyAI/
├── .claude/                    # Claude Code configuration
├── .env                        # Environment variables (Supabase, Stripe, etc.)
├── .env.example                # Environment template
├── app/                        # Next.js App Router
│   ├── api/                    # API routes
│   │   ├── chat/route.ts       # Multi-provider AI gateway
│   │   ├── stripe/             # Stripe webhooks & checkout
│   │   │   ├── checkout/route.ts
│   │   │   ├── portal/route.ts
│   │   │   └── webhook/route.ts
│   │   ├── autopilot/          # Autopilot package APIs
│   │   │   └── packages/[id]/
│   │   │       ├── drafting/
│   │   │       │   ├── business-plan/route.ts
│   │   │       │   └── financial-model/route.ts
│   │   │       └── review/
│   │   │           ├── approve/route.ts
│   │   │           ├── compliance/route.ts
│   │   │           ├── revise/route.ts
│   │   │           └── status/route.ts
│   │   └── resources/          # Resource download
│   ├── pricing/                # Pricing page
│   │   ├── page.tsx
│   │   └── PricingClient.tsx
│   ├── autopilot/page.tsx      # Autopilot dashboard
│   ├── layout.tsx              # Root layout
│   ├── page.tsx                # Landing page
│   ├── sitemap.ts              # SEO sitemap
│   └── HomeClient.tsx          # Client-side landing
├── components/                 # React components
│   ├── auth/                   # Authentication
│   │   ├── AdminLogin.tsx
│   │   ├── LoginModal.tsx
│   │   ├── SignupModal.tsx
│   │   └── UserMenu.tsx
│   ├── autopilot/              # Autopilot components
│   │   └── AutopilotDashboard.tsx
│   ├── chat/                   # Chat interface
│   │   ├── ChatHistorySidebar.tsx
│   │   └── MessageRenderer.tsx
│   ├── config/                 # Configuration modals
│   │   ├── ApiKeyInput.tsx
│   │   ├── ConfigurationModal.tsx
│   │   └── ModelSelector.tsx
│   ├── dashboard/              # Main dashboard
│   │   ├── ChatInterface.tsx
│   │   ├── Dashboard.tsx
│   │   ├── KanbanBoard.tsx
│   │   └── ModuleLibrary.tsx
│   ├── documents/              # Document management
│   │   ├── AdminAuthPanel.tsx
│   │   ├── DocumentList.tsx
│   │   ├── DocumentWorkspace.tsx
│   │   ├── ModuleUploadSection.tsx
│   │   └── UserDocumentSection.tsx
│   ├── learn/                  # Learning Hub
│   │   ├── BlogFeed.tsx
│   │   ├── CourseProgress.tsx
│   │   ├── LearningHub.tsx
│   │   ├── ModuleCard.tsx
│   │   ├── ModuleViewer.tsx
│   │   └── VideoLibrary.tsx
│   ├── marketing/              # Landing page sections
│   │   ├── FAQ.tsx
│   │   ├── Features.tsx
│   │   ├── Footer.tsx
│   │   ├── Header.tsx
│   │   ├── Hero.tsx
│   │   ├── HowItWorks.tsx
│   │   ├── Pricing.tsx
│   │   ├── Testimonials.tsx
│   │   └── TrustSignals.tsx
│   ├── pricing/                # Pricing components
│   │   ├── PricingModal.tsx
│   │   ├── TierComparison.tsx
│   │   └── UpgradePrompt.tsx
│   ├── skills/                 # Skills panel
│   │   ├── SkillBadges.tsx
│   │   └── SkillsPanel.tsx
│   ├── shared/                 # Shared components
│   │   ├── ResourceCard.tsx
│   │   ├── TierBadge.tsx
│   │   ├── TierTooltip.tsx
│   │   └── UpgradePromptMessage.tsx
│   └── ui/                     # Primitive UI
│       ├── CopyButton.tsx
│       ├── Logo.tsx
│       └── ThemeToggle.tsx
├── docs/                       # Documentation
│   ├── ARCHITECTURE_OVERVIEW.md
│   ├── AUTOPILOT_ARCHITECTURE.md
│   ├── planning/
│   │   ├── BUSINESS_MODEL.md
│   │   ├── NEXT_STEPS.md
│   │   ├── TODO.md
│   │   └── TORLYAI_STRATEGIC_PLAN.md
│   ├── setup/
│   │   ├── AUTHENTICATION_TESTING_GUIDE.md
│   │   ├── PHASE_1A_SETUP.md
│   │   └── SUPABASE_SETUP_GUIDE.md
│   └── testing/
│       ├── TEST_CASES.md
│       ├── TEST_EXECUTION_REPORT.md
│       ├── TEST_REPORT.md
│       └── TESTING_GUIDE.md
├── lib/                        # Core library code
│   ├── constants/              # App constants
│   │   ├── content-library.ts  # AI resource suggestions
│   │   ├── modules.ts          # Learning modules
│   │   └── tier-limits.ts      # Tier restrictions
│   ├── hooks/                  # React hooks
│   │   ├── useBusinessPlan.ts
│   │   ├── useChatHistory.ts
│   │   ├── useConversionTracking.ts
│   │   ├── useJourney.ts
│   │   ├── useSkillUsage.ts
│   │   ├── useSubscription.ts
│   │   └── useTheme.ts
│   ├── services/               # Business logic
│   │   ├── autopilot-document-generator.ts
│   │   ├── business-plan-indexer.ts
│   │   ├── check-in-detector.ts
│   │   ├── enhanced-skills.ts
│   │   ├── md-processor.ts
│   │   ├── module-router.ts
│   │   └── pdf-processor.ts
│   ├── skills/                 # 31 AI Skills
│   │   ├── index.ts            # Skill registry
│   │   ├── types.ts            # Skill interfaces
│   │   ├── utils.ts            # Skill utilities
│   │   ├── business-plan-skills.ts
│   │   ├── visa-framework-skills.ts       # Phase 1
│   │   └── visa-framework-skills-phase2-3.ts # Phase 2-3
│   ├── supabase/               # Database
│   │   ├── client.ts
│   │   ├── database.types.ts
│   │   └── server.ts
│   ├── types/
│   │   └── autopilot.ts        # Autopilot types
│   ├── utils/
│   │   └── ai-resource-suggester.ts
│   ├── crypto.ts               # API key encryption
│   └── db.ts                   # IndexedDB wrapper
├── public/                     # Static assets
├── scripts/                    # Build & setup scripts
├── supabase/                   # Supabase migrations
│   └── migrations/
├── tests/                      # Test files
│   └── auth/                   # Auth E2E tests
├── middleware.ts               # Route protection
├── package.json
├── tailwind.config.ts
├── tsconfig.json
├── CLAUDE.md                   # Claude Code instructions
├── README.md
└── WORKTREES.md                # Git worktree guide
```

### Credentials Location
| Type | Location | Purpose |
|------|----------|---------|
| Environment | `.env` | Supabase, Stripe keys |

> **Note**: All Oracle Cloud / SSH credentials are stored in `torly-wordpress-setup/.credentials/` only.

---

## Repository 2: torly-wordpress-setup

### Purpose
WordPress theme, blog content, waitlist feature, and deployment automation for the Oracle Cloud VM. This repo contains all server-side infrastructure and deployment scripts.

### Location
```
/Users/Jason-uk/AI/AI_Coding/Repositories/torly-wordpress-setup/
```

### Technology Stack
- **CMS**: WordPress
- **Language**: PHP 8.1
- **Database**: MySQL 8.0
- **Web Server**: Nginx + PHP-FPM
- **Hosting**: Oracle Cloud Free Tier
- **Testing**: Playwright

### Connected Domains
- **VM IP**: `141.147.89.179`
- **WordPress**: Running on Oracle VM
- **DNS**: Managed via GoDaddy

### File Structure

```
torly-wordpress-setup/
├── .claude/                    # Claude Code configuration
│   ├── settings.local.json
│   ├── commands/
│   │   └── devjournal.md
│   └── skills/
│       └── torlyai-design-system/
│           ├── SKILL.md
│           └── examples/
│               ├── button-examples.md
│               ├── card-examples.md
│               └── form-examples.md
├── .credentials/               # Server credentials (SENSITIVE)
│   ├── ssh-key-2025-11-17.key  # WORKING SSH private key
│   ├── ssh-key-2025-11-17.key.pub
│   ├── oracle_credentials.json # VM details
│   ├── wordpress_credentials.txt
│   ├── godaddy_credentials.json
│   ├── godaddy_login.json
│   ├── google-analytics-stream.json
│   ├── database-access.md
│   └── mcp-inspector-reference.md
├── automation/                 # Automation scripts
├── content/                    # Content management
│   └── blog-posts.json
├── deployment/                 # Deployment scripts
│   ├── deploy-script.sh        # Main deployment
│   ├── health-check.sh
│   ├── setup-ssl.sh
│   ├── configure-smtp.sh
│   ├── seo-optimization.sh
│   ├── publish-blog-posts.sh
│   ├── update-blog-content.sh
│   ├── view-waitlist.sh
│   ├── analytics-report.sh
│   ├── verify-waitlist-features.sh
│   ├── setup-multisite.sh
│   ├── fix-multisite-removal.sh
│   ├── setup-secondary-domain.sh
│   ├── repair-wordpress.sh
│   ├── fix-wordpress-urls.php
│   ├── fix-blog-redirect.php
│   ├── set-featured-images.php
│   ├── update-featured-images-svg.php
│   ├── check-godaddy-dns.js
│   ├── test-mobile-menu.js
│   └── mysql-commands.md
├── docs/                       # Documentation
├── mcp-integration/            # MCP server integration
├── scripts/                    # Utility scripts
│   └── update-blog-cover-images.sh
├── temp-blog-images/           # Temporary image storage
├── tests/                      # Playwright tests
│   ├── waitlist-flow.spec.js
│   ├── waitlist-functionality-test.spec.js
│   ├── waitlist-button-width.spec.js
│   ├── waitlist-step4-button.spec.js
│   ├── contact-form.spec.js
│   ├── homepage-visual-review.spec.js
│   ├── homepage-improvements-verification.spec.js
│   ├── button-improvements-verification.spec.js
│   ├── cta-button-centering.spec.js
│   ├── cta-button-width.spec.js
│   ├── design-system-compliance.spec.js
│   ├── verify-consistency.spec.js
│   ├── footer-social-links.spec.js
│   ├── footer-favicon-test.spec.js
│   ├── footer-before-after.spec.js
│   ├── verify-blog-nav.spec.js
│   ├── check-blog-cover-images.spec.js
│   ├── final-blog-verification.spec.js
│   ├── verify-visual-diversity.spec.js
│   ├── cross-browser-blog-test.spec.js
│   ├── fetch-unsplash-images.spec.js
│   ├── demo-visual-test.spec.js
│   ├── test-carousel-and-zoom.spec.js
│   ├── complete-showcase-test.spec.js
│   ├── capture-preview.spec.js
│   ├── custom-screenshots.spec.js
│   └── test-mobile-menu.js
├── theme/                      # WordPress theme
│   └── torly-theme/
│       ├── style.css           # Theme stylesheet
│       ├── functions.php       # Theme functions
│       ├── front-page.php      # Landing page template
│       ├── header.php
│       ├── footer.php
│       ├── index.php
│       ├── page.php
│       ├── single.php
│       ├── home.php
│       ├── archive.php
│       ├── inc/
│       │   └── waitlist-functions.php
│       ├── templates/
│       │   ├── blog-post-template.php
│       │   └── waitlist-modal.php
│       └── assets/
│           ├── css/
│           │   └── waitlist-modal.css
│           └── js/
│               ├── main.js
│               └── waitlist-modal.js
├── screenshots/                # Test screenshots
├── test-results/
├── playwright-report/
│
# Root-level documentation
├── CLAUDE.md                   # Claude Code instructions
├── README.md
├── ORACLE_VM_INCIDENT_LOG.md   # VM incident history
├── TORLYAI_DESIGN_SYSTEM.md    # Design system reference
├── PRD_LANDING_PAGE_REDESIGN.md
├── IMPLEMENTATION_SUMMARY.md
├── API_USAGE_GUIDE.md
├── SECURITY.md
├── SECURITY_AUDIT_REPORT.md
├── SECURITY_FIXES.md
├── SSL_CERTIFICATE_GUIDE.md
├── SEO_AND_GEO_OPTIMIZATION.md
├── SEO_GEO_IMPLEMENTATION_PLAN.md
├── SEO_GEO_IMPLEMENTATION_STATUS.md
├── SEO_GEO_FINAL_SUMMARY.md
├── SEO_OPTIMIZATION_COMPLETE.md
├── TERMINOLOGY_UPDATE.md
├── DESIGN_SYSTEM_AUDIT.md
├── IMAGE_REQUIREMENTS.md
├── BLOG_NAVIGATION_FIX.md
├── BLOG_COVER_IMAGE_UPDATE_GUIDE.md
├── COVER_IMAGE_DIVERSITY_PLAN.md
├── WAITLIST_FEATURE_SUMMARY.md
├── WAITLIST_INTEGRATION.md
├── SECONDARY_DOMAIN_SETUP.md
├── MULTISITE-REMOVAL-COMPLETE.md
├── PRODUCT_SHOWCASE_IMPLEMENTATION.md
├── dev_journal.md              # Development log
├── ai-sitemap.json             # AI-readable sitemap
├── curated-unsplash-images.json
├── playwright.config.js
├── package.json
│
# Test scripts (root level)
├── test-ajax-response.js
├── test-cta-alignment.js
├── test-cta-column-centering.js
├── test-evaluate-js.js
├── test-form-submission.js
├── test-modal-autoopen.js
├── test-waitlist-button.js
├── test-waitlist-e2e.js
├── test-waitlist-modal-position.js
├── test-with-console.js
├── test-with-network.js
└── test-mobile-menu.html
```

### Credentials Location
| Type | Location | Purpose |
|------|----------|---------|
| SSH Key | `.credentials/ssh-key-2025-11-17.key` | Oracle VM SSH access |
| Oracle VM | `.credentials/oracle_credentials.json` | VM IP, OCID, region |
| WordPress | `.credentials/wordpress_credentials.txt` | WP admin credentials |
| GoDaddy | `.credentials/godaddy_*.json` | DNS management |
| MySQL | `.credentials/database-access.md` | Database credentials |
| Analytics | `.credentials/google-analytics-stream.json` | GA4 configuration |

---

## Infrastructure & Deployment

### Oracle Cloud VM Details

```
Instance Name:  instance-20251117-1321
OCID:          ocid1.instance.oc1.uk-london-1.anwgiljtdz6cpeyciyko632s6r4fpdoxfydao7rl4chfja76ghnhm2e5rmrq
Public IP:     141.147.89.179
Region:        uk-london-1 (UK South - London)
Shape:         VM.Standard.E2.1.Micro (Free Tier)
OS:            Ubuntu 22.04 LTS
```

### SSH Connection

**Working SSH Command:**
```bash
ssh -i /Users/Jason-uk/AI/AI_Coding/Repositories/torly-wordpress-setup/.credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179
```

Or from torlyAI (keys replicated):
```bash
ssh -i /Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI/.ssh/ssh-key-2025-11-17.key ubuntu@141.147.89.179
```

### OCI CLI Connection

Configuration at `torlyAI/.oci/config`:
```ini
[DEFAULT]
user=ocid1.user.oc1..aaaaaaaawo2seatkedupdmpsihlevgnbd7mkqlchgfnz7dqg23gjoy2vzqrq
fingerprint=40:0d:2e:92:4a:ac:5c:0b:84:d2:f5:05:de:12:ad:e8
tenancy=ocid1.tenancy.oc1..aaaaaaaanzvkl4w3dhw6e4lktdivfy2gtwuy52vbzj5xvj3zmxmrhgjyw4fa
region=uk-london-1
key_file=/Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI/.oci/oci_api_key.pem
```

### Deployment Workflow

```
┌────────────────────────────────────────────────────────────────┐
│                     DEPLOYMENT WORKFLOW                         │
├────────────────────────────────────────────────────────────────┤
│                                                                 │
│  LOCAL (torly-wordpress-setup)                                  │
│  ┌─────────────────────────────┐                               │
│  │ 1. Edit theme files         │                               │
│  │    theme/torly-theme/       │                               │
│  │                             │                               │
│  │ 2. Run Playwright tests     │                               │
│  │    npm run test             │                               │
│  │                             │                               │
│  │ 3. Deploy via SSH           │                               │
│  │    deployment/deploy-       │                               │
│  │    script.sh                │                               │
│  └─────────────┬───────────────┘                               │
│                │                                                │
│                ▼ SSH + SCP                                      │
│  ORACLE VM (141.147.89.179)                                     │
│  ┌─────────────────────────────┐                               │
│  │ /var/www/html/              │                               │
│  │ └── wp-content/             │                               │
│  │     └── themes/             │                               │
│  │         └── torly-theme/    │                               │
│  │                             │                               │
│  │ Services:                   │                               │
│  │ - Nginx (web server)        │                               │
│  │ - PHP-FPM (PHP runtime)     │                               │
│  │ - MySQL 8.0 (database)      │                               │
│  │ - Certbot (SSL)             │                               │
│  └─────────────────────────────┘                               │
│                                                                 │
└────────────────────────────────────────────────────────────────┘
```

---

## Key Differences Summary

| Aspect | torlyAI | torly-wordpress-setup |
|--------|---------|----------------------|
| **Purpose** | SaaS Application | Backend + Deployment |
| **Tech Stack** | Next.js, TypeScript | WordPress, PHP |
| **Database** | Supabase (PostgreSQL) | MySQL 8.0 |
| **Hosting** | Vercel | Oracle Cloud VM |
| **Primary Users** | End users (visa applicants) | Developers (deployment) |
| **Content Type** | Dynamic AI features | Static blog/marketing |
| **Payment Processing** | Stripe integration | N/A |
| **AI Integration** | 31 skills, multi-provider | None |
| **Development Port** | 3000 | Via SSH to VM |

---

## Quick Reference Commands

### torlyAI Development
```bash
cd /Users/Jason-uk/AI/AI_Coding/Repositories/torlyAI

# Development
npm run dev              # Start on port 3000
npm run build            # Production build
npm test                 # Run Jest tests
npm run test:e2e         # Run Playwright tests

# Supabase
./scripts/setup-supabase.sh
npx tsx scripts/test-supabase-connection.ts
```

### torly-wordpress-setup Deployment
```bash
cd /Users/Jason-uk/AI/AI_Coding/Repositories/torly-wordpress-setup

# SSH to server
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179

# Deploy theme
./deployment/deploy-script.sh

# Run tests
npm test                 # Playwright tests

# Health check
./deployment/health-check.sh
```

---

## Development Workflow

### Making Changes to WordPress

1. **Edit theme files** in `torly-wordpress-setup/theme/torly-theme/`
2. **Test locally** with Playwright
3. **Deploy to Oracle VM** via SSH
4. **Verify changes** on live site

### Making Changes to SaaS App

1. **Edit files** in `torlyAI/`
2. **Test locally** with `npm run dev`
3. **Run tests** with Jest/Playwright
4. **Deploy to Vercel** via git push

---

## Security Notes

- **Never commit** `.credentials/`, `.oci/`, `.ssh/`, `.env` to git
- Both repos have `.gitignore` configured to exclude sensitive files
- SSH keys should have `chmod 600` permissions
- Rotate credentials periodically

---

## Document History

| Date | Change | Author |
|------|--------|--------|
| 2025-01-09 | Initial creation | Claude Code |

