# aicmo.uk → WordPress Integration Guide

**Last updated:** 2026-04-23
**Applies to repos:** `torlyAI` + `torly-blog` (identical copy at `torly-blog/docs/integrations/AICMO-UK-INTEGRATION.md`)
**Audience:** anyone configuring or debugging the aicmo.uk auto-publisher that pushes blog posts into the Torly.AI WordPress site.

---

## 1. TL;DR

**aicmo.uk is an upstream content-scheduling tool that publishes blog posts into the Torly.AI WordPress site via the WordPress REST API.**

The integration sits at:

```
 ┌───────────────┐   POST /wp-json/wp/v2/posts    ┌─────────────────────────┐
 │   aicmo.uk    │ ───────────────────────────►   │  origin.torly.ai        │
 │  (scheduler)  │    Basic auth:                  │  (WordPress, Oracle VM) │
 └───────────────┘    Application Password         └─────────────────────────┘
                                                              │
                                                              ▼
                                                   MySQL + WP post created
                                                              │
                                                              ▼
                                              Reader sees post at
                                              https://torly.ai/blog/<slug>/
                                              (via Vercel rewrite proxy)
```

**Critical detail:** aicmo.uk must POST to `origin.torly.ai` directly, **NOT** to `torly.ai` or `www.torly.ai`. The user-facing domain routes through Vercel, which blocks `/wp-json/*` at its platform firewall (`x-vercel-mitigated: deny`). Server-to-server API traffic bypasses Vercel entirely.

---

## 2. Why `origin.torly.ai` (not `www.torly.ai`)

| URL | Status | Why |
|---|---|---|
| `https://www.torly.ai/wp-json/wp/v2` | **403 forbidden** | Vercel's platform firewall blocks `/wp-json/*` at the edge. Vercel treats WP REST endpoints on Next.js projects as an attack signature. Unblocking requires a Vercel Dashboard firewall exemption, which also exposes the API to every bot on the internet. |
| `https://torly.ai/wp-json/wp/v2` | **403 forbidden** | Same — Vercel firewall fires on the apex too. |
| `https://origin.torly.ai/wp-json/wp/v2` | ✅ **200 OK** | Direct hit on Apache + WordPress on the Oracle VM. No Vercel layer. Already the correct path for server-to-server integrations. |

See `docs/architecture/BLOG-PROXY-ARCHITECTURE.md` §14 for the full history of why API access goes direct-to-origin.

---

## 3. Prerequisites

Before configuring aicmo.uk, make sure these are in place on the WP side:

1. **A dedicated WP user account** for aicmo (recommended: a new user, separate from `admin`). Role `Author` or `Editor`; never `Administrator` for an integration account unless it genuinely needs to install plugins.
   - Currently the integration runs as **`maggie`** (id=2, role: Administrator, email: `startup@topy.ai` — **note the typo in the email**, it should be `startup@torly.ai`. Worth fixing; see §10.).
   - Future hardening: create `aicmo-publisher` with role `Editor` (can publish/edit any post, cannot install plugins or manage users).
2. **Application Password** generated for that user.
   - Format: 24 characters, space-separated into 4-char groups (e.g. `0Lkg KWmT txjc ryMt hjLx e3vR`). Spaces are optional — WP strips them.
   - WP docs: https://make.wordpress.org/core/2020-11-05/application-passwords-integration-guide/
3. **`origin.torly.ai` reachable from the aicmo.uk server**. Test from aicmo:
   ```bash
   curl -sI https://origin.torly.ai/wp-json/wp/v2/
   # expected: HTTP/1.1 200 OK  + Content-Type: application/json
   ```
4. **REST API enabled** — default for all modern WP; confirm at `https://origin.torly.ai/wp-json/` (should return a JSON schema, not a 404).

---

## 4. Create an Application Password

### In the WordPress admin UI

1. Log in at `https://origin.torly.ai/wp-login.php` (the admin URL — NOT `torly.ai/wp-admin`, which Vercel blocks).
2. **Users → All Users → <the user that aicmo will post as>** → Edit.
3. Scroll to the **Application Passwords** section at the bottom of the profile page.
4. Enter a name that identifies the integration: `aicmo-blog-publisher-<YYYY-MM-DD>` (datestamp helps with rotation).
5. Click **Add New Application Password**.
6. **WordPress shows the 24-character password ONCE on the next screen.** Copy it to a secure vault immediately — you can never retrieve it again; you can only revoke and re-create.

### Via WP-CLI (SSH to VM, if UI is unavailable)

```bash
# SSH to the VM
ssh -i <key> ubuntu@141.147.89.179
cd /var/www/html

# Create (replace USERNAME with admin / maggie / aicmo-publisher / etc.)
sudo -u www-data HTTP_HOST=torly.ai wp --path=/var/www/html \
    user application-password create USERNAME 'aicmo-blog-publisher-2026-04-23' \
    --porcelain
# The 24-char password prints to stdout. Save immediately.
```

### Save it to a secret store

The convention on this project is macOS Keychain with a `torlyai-` prefix:

```bash
security add-generic-password -U \
    -a "maggie" \
    -s "torlyai-wp-app-password-aicmo" \
    -w '<24-char-app-password>'
```

---

## 5. Configure aicmo.uk — Blog Integration panel

Open the aicmo.uk dashboard → **Blog Integration Settings** panel. Select **"Publish via Your WordPress Site"** and fill in:

| Field | Value | Notes |
|---|---|---|
| **Endpoint** | `https://origin.torly.ai/wp-json/wp/v2` | **Critical:** use `origin.torly.ai`, not `www.torly.ai`. See §2. |
| **Email / Username** | `maggie` (or the WP user login you created) | aicmo's field is labeled "Email" but WP's Basic-auth flow accepts either username OR email. Either works; username is less likely to change. |
| **Application Password** | the 24-char string from §4 | Spaces optional. Treat as a secret — never commit to git, never paste into a public URL. |
| **Publishing Schedule** | e.g. "96 posts per day" | Respects WP's rate-limiting + server capacity. The Oracle VM is a free-tier instance — if you push more than ~100 posts/day, watch CPU usage on the VM. |
| **Enable/Disable Auto Publish** | ON when ready | Leave OFF while testing; flip ON after `Test Connection` succeeds. |

Click **Test Connection**. Success should return HTTP 200 with the user profile JSON. See §8 for what that looks like.

---

## 6. Post-body schema — what aicmo should send

aicmo.uk constructs each post as a `POST /wp-json/wp/v2/posts` request with a JSON body. Minimum fields:

```json
{
  "title":   "My blog post title",
  "content": "<p>Post body HTML here.</p>",
  "status":  "publish",
  "slug":    "my-blog-post-title",
  "excerpt": "Short summary for RSS + OG tags.",
  "categories": [3],                // WP taxonomy term IDs
  "tags":       [12, 19],
  "featured_media": 4473,           // attachment ID (upload via /wp/v2/media first)
  "meta": {
    "_yoast_wpseo_metadesc": "SEO description ≤155 chars",
    "_yoast_wpseo_focuskw":  "primary keyword"
  }
}
```

**Status values:**
- `publish` — live immediately
- `draft` — saved for editorial review
- `pending` — awaiting moderator (if the user role can't publish directly)
- `future` — scheduled publication via `date` field

**Response on success:** HTTP 201 Created + full post object including `id`, `link` (the public URL: `https://torly.ai/blog/my-blog-post-title/` — rewritten from origin by the WP host filter), and `guid`.

**Content safety:** aicmo should HTML-escape user-generated portions of content. WP will sanitize as well (stripping `<script>` etc.) but defense-in-depth.

---

## 7. Authentication — HTTP Basic with Application Password

Every request:

```http
POST /wp-json/wp/v2/posts HTTP/1.1
Host: origin.torly.ai
Authorization: Basic <base64(username:app_password)>
Content-Type: application/json
User-Agent: aicmo-blog-publisher/1.0

{ ... post JSON ... }
```

Where `<base64(username:app_password)>` is generated as:

```bash
echo -n "maggie:0Lkg KWmT txjc ryMt hjLx e3vR" | base64
# → bWFnZ2llOjBMa2cgS1dtVCB0eGpjIHJ5TXQgaGpMeCBlM3ZS
```

**Never log or echo the password.** Treat the base64 blob as equivalently sensitive to the password itself (it's trivially reversible).

---

## 8. Test — smoke check via curl

### From the aicmo.uk server (or any dev machine):

```bash
APP_PW='0Lkg KWmT txjc ryMt hjLx e3vR'   # retrieve from secret store

# 1. Anonymous discovery — should return WP's REST schema
curl -sI https://origin.torly.ai/wp-json/wp/v2/
# Expected: HTTP/1.1 200, Content-Type: application/json

# 2. Authenticated GET — profile of the integration user
curl -s -u "maggie:$APP_PW" \
    https://origin.torly.ai/wp-json/wp/v2/users/me
# Expected: JSON with id, name, email, roles, capabilities

# 3. Create a test draft post
curl -s -u "maggie:$APP_PW" \
    -H "Content-Type: application/json" \
    -X POST https://origin.torly.ai/wp-json/wp/v2/posts \
    -d '{"title":"aicmo test post","content":"Hello from aicmo","status":"draft"}'
# Expected: HTTP/1.1 201 Created + JSON with id, link, status=draft

# 4. Clean up — delete the test post
POST_ID=<id from step 3>
curl -s -u "maggie:$APP_PW" \
    -X DELETE https://origin.torly.ai/wp-json/wp/v2/posts/$POST_ID?force=true
# Expected: HTTP/1.1 200 + "deleted":true
```

If all four steps return their expected responses, the integration is wired up correctly.

---

## 9. Troubleshooting

| Symptom | Diagnosis | Fix |
|---|---|---|
| `403 x-vercel-mitigated: deny` | You're hitting `torly.ai` or `www.torly.ai`. Vercel firewall blocks WP paths on those hosts. | Change Endpoint to `https://origin.torly.ai/wp-json/wp/v2`. |
| `401 rest_cannot_create` | User account lacks permission to create posts OR App Password wrong. | Verify user has role `Author`, `Editor`, or `Administrator`. Verify App Password matches the one saved on WP (see §9b). |
| `401 rest_forbidden_cannot_manage_options` | User trying to do something outside their role. | Either escalate role or scope down the request. |
| `404` on `/wp-json/wp/v2/*` | URL typo OR WP REST routes not rewritten (rare). | `curl /wp-json/` to confirm root; check `.htaccess` on VM hasn't been deleted. |
| `500` with PHP warning mentioning a plugin | A WP plugin (e.g. WP-Mail-SMTP during post-notification) is erroring. | Check `/var/log/apache2/error.log` on VM. Temporarily deactivate the offending plugin. |
| Posts create with 201 but don't appear at `torly.ai/blog/` | Vercel edge cache for `/blog/` is serving stale content (1-hour TTL) | Wait up to 1 hour OR trigger revalidation by touching any post (WP purges its own caches; Vercel SWR refreshes in background). |
| "Test Connection" fails with no explicit error | aicmo.uk's test runner might be hitting the site with a different User-Agent or URL. | Log the request on the WP side temporarily (`sudo tail -f /var/log/apache2/access.log | grep wp-json`) and watch what aicmo actually sends. |
| Application Password stops working silently | User revoked it, password rotated, or user was disabled. | Recreate the App Password (§4) and update aicmo's config. |

### 9b. Verify an App Password matches the DB (via SSH)

```bash
# On the VM — use the temp-file pattern (sudo strips env)
TMP=$(mktemp)
printf '%s' '<app-password>' | sudo tee "$TMP" > /dev/null
sudo chown www-data:www-data "$TMP"

sudo -u www-data HTTP_HOST=torly.ai wp --path=/var/www/html eval \
    "\$p = trim(file_get_contents('$TMP')); \$u = get_user_by('login','maggie'); \
     foreach (WP_Application_Passwords::get_user_application_passwords(\$u->ID) as \$ap) { \
       if (WP_Application_Passwords::chunk_password(\$p) && \
           wp_check_password(WP_Application_Passwords::chunk_password(\$p), \$ap['password'], \$u->ID)) { \
         echo 'MATCH: '.\$ap['name'].PHP_EOL; exit; \
       } \
     } \
     echo 'NO MATCH';"

sudo rm -f "$TMP"
```

---

## 10. Security + operations

### Rotation

- **Rotate the Application Password at least every 90 days**, or immediately if:
  - The password appeared in a chat transcript, screenshot, log file, or email.
  - Someone leaves the team.
  - aicmo.uk changes hands or deploys to a new environment.
- Rotation flow: WP admin → Users → Profile → Application Passwords → **Revoke** the old one → **Add New** → paste the new password into aicmo.uk → update Keychain.

### Least privilege

The current integration runs as `maggie` (Administrator role). That gives aicmo the ability to install plugins, add users, export the database, etc. — far more than "create blog posts" needs.

**Recommended hardening**: create a dedicated `aicmo-publisher` user with role `Editor`. In WP-CLI:

```bash
sudo -u www-data HTTP_HOST=torly.ai wp --path=/var/www/html user create \
    aicmo-publisher integrations@innovatorly.ai \
    --role=editor --display_name='aicmo Publisher' --send-email=false
```

Then migrate aicmo.uk's Endpoint config to use that user, and `wp user application-password delete` the old `maggie` App Password once migration is verified.

### Monitoring

- **Vercel Usage dashboard** — watch Fluid Active CPU. High posting rate from aicmo could cascade into more /blog/ cache refreshes. Target: <30 min/day on blog-related invocations.
- **Apache access log on VM** — `sudo tail -f /var/log/apache2/access.log | grep wp-json` to watch auth attempts. Repeated 401s = wrong password or someone brute-forcing.
- **WP user meta `session_tokens`** — should NOT have tokens created from aicmo's App Password (App Passwords don't create sessions). If you see new sessions from the aicmo IP, something's sending a full login instead of Basic auth.

### Fix the typo on `maggie`'s email

`maggie`'s WP email is currently `startup@topy.ai` (should be `startup@torly.ai`). This breaks password reset via the "Lost your password?" flow for that account. Fix via SSH:

```bash
sudo -u www-data HTTP_HOST=torly.ai wp --path=/var/www/html \
    user update maggie --user_email='startup@torly.ai'
```

---

## 11. Rate limiting + back-pressure

aicmo is configured for 96 posts/day = ~4 per hour. For context:

- Each POST to `/wp-json/wp/v2/posts` on the VM takes ~300–700 ms (MySQL insert + term associations + hooks).
- The Oracle VM is a free-tier ARM instance with modest CPU — 96 posts/day is comfortable.
- Vercel edge cache for the public blog index (`torly.ai/blog/`) has a 1-hour TTL. So a surge of 10 new posts won't show up on the index for up to an hour, regardless of how fast aicmo creates them. Single-post URLs (`torly.ai/blog/<slug>/`) are fresh on first visitor after creation.
- If aicmo ever needs to push >500 posts/day, re-evaluate the VM tier (move to a paid Oracle flex shape with more OCPU + RAM).

---

## 12. Related docs

- `docs/architecture/BLOG-PROXY-ARCHITECTURE.md` — full architecture for the two-repo, two-host setup. Explains why WP lives on Oracle and API traffic goes direct-to-origin.
- `torlyAI/CLAUDE.md` → "Credentials" — how Keychain is used for torly.ai secrets + naming convention.
- `torly-blog/docs/ORACLE_CLOUD_INFRASTRUCTURE.md` — VM-level configuration.
- `docs/architecture/BLOG-PROXY-ARCHITECTURE.md` §14 — why admin URLs (including `/wp-json`) are exempted from the host-rewrite filter. Important if you ever see REST responses with unexpected hostnames.

---

## 13. Quick-reference card

```
Endpoint:                 https://origin.torly.ai/wp-json/wp/v2
Admin UI:                 https://origin.torly.ai/wp-login.php
Auth:                     HTTP Basic with Application Password
User:                     maggie (id=2) — recommend migrating to aicmo-publisher editor
App Password:             Keychain entry "torlyai-wp-app-password-aicmo"
Rate budget:              ~100 posts/day on current VM
SSH to manage:            ssh ubuntu@141.147.89.179 using TorlyAI-SSH-PrivateKey
                          (from macOS Keychain; base64-decode before use)
Public URL of a post:     https://torly.ai/blog/<slug>/ (Vercel proxy + 1h cache)
```

---

*If the integration breaks, try §9 before changing code. Document any new failure mode you encounter in that section so the next engineer has a shorter path to a fix.*
