# Blog Proxy Architecture — torly.ai + torly-blog

**Last updated:** 2026-04-22
**Applies to repos:** `torlyAI` (this repo) + `torly-blog` (sister repo, identical copy of this doc lives at `torly-blog/docs/architecture/BLOG-PROXY-ARCHITECTURE.md`)
**Audience:** anyone debugging blog rendering, SEO, or infrastructure issues on torly.ai — future-you included

---

## 1. TL;DR

The public site `torly.ai/*` is served by two independently deployed codebases:

- **`torlyAI`** — Next.js app on Vercel. Owns the marketing site, apps, `/blog/*` routing config (no article content).
- **`torly-blog`** — WordPress theme on an Oracle Cloud VM (`141.147.89.179`, hostname `origin.torly.ai`). Owns all article content + the WP theme.

When a visitor hits `torly.ai/blog/<slug>/`, Vercel's Next.js runtime **rewrites** the request to `origin.torly.ai/blog/<slug>/` — the response is streamed back to the browser with the URL bar staying on `torly.ai`. Vercel's CDN edge caches the response for **1 hour** so repeat traffic doesn't burn serverless CPU.

This doc records **why** the setup is this shape, the **contract** each layer must honor, and the **debugging playbook** for the three failure modes we've already hit.

---

## 2. Topology

```
 ┌─────────────────────────────────────────────────────────────────────────┐
 │                              USER / BROWSER                              │
 └──────────────────────────────┬───────────────────────────────────────────┘
                                │ https://torly.ai/*
                                ▼
 ┌─────────────────────────────────────────────────────────────────────────┐
 │  DNS (apex A record → Vercel anycast IPs)                                │
 │  torly.ai        → Vercel                                                │
 │  origin.torly.ai → 141.147.89.179  (direct A record, bypasses Vercel)    │
 └──────────────────────────────┬───────────────────────────────────────────┘
                                │
                                ▼
 ┌─────────────────────────────────────────────────────────────────────────┐
 │                           VERCEL EDGE (lhr1)                             │
 │                                                                          │
 │  ┌──────────────┐  ┌──────────────────┐  ┌───────────────────────────┐  │
 │  │ Static /     │  │ Marketing routes │  │ Blog proxy layer          │  │
 │  │ assets       │  │ (app/ pages)     │  │                           │  │
 │  │              │  │                  │  │  ┌─────────────────────┐  │  │
 │  │ og-card.png  │  │ /                │  │  │ Next.js serverless  │  │  │
 │  │ hero.webp    │  │ /pricing         │  │  │ function            │  │  │
 │  │ ...          │  │ /agents          │  │  │ (async rewrites)    │  │  │
 │  │              │  │ /download        │  │  └──────────┬──────────┘  │  │
 │  │              │  │ /insights/*      │  │             │ fetch       │  │
 │  │              │  │ ...              │  │             ▼             │  │
 │  └──────────────┘  └──────────────────┘  │    ┌─────────────────┐    │  │
 │                                          │    │ Vercel edge cache│    │  │
 │  ┌──────────────────────────────────────┐│    │  TTL = 3600s     │    │  │
 │  │ vercel.json rewrites (file-ext only):││    │  SWR = 86400s    │    │  │
 │  │   /wp-content/*  → origin            ││    └─────────────────┘    │  │
 │  │   /wp-includes/* → origin            │└────────────┬───────────────┘  │
 │  └──────────────────────────────────────┘             │                  │
 │                                                        │                  │
 │  vercel.json redirects (unchanged):                    │                  │
 │    /wp-admin, /wp-login, /wp-json, /feed,              │                  │
 │    /category, /tag  →  302 to origin                   │                  │
 └────────────────────────────────────────────────────────┼──────────────────┘
                                                          │
                                                          │ on cache MISS
                                                          ▼
 ┌─────────────────────────────────────────────────────────────────────────┐
 │              ORACLE CLOUD VM  (141.147.89.179, origin.torly.ai)          │
 │                                                                          │
 │   Apache 2.4.52 (Ubuntu)                                                 │
 │   └── /var/www/html/                                                     │
 │       ├── wp-config.php                                                  │
 │       ├── wp-content/themes/torly-theme/   ← synced from torly-blog repo │
 │       │   ├── functions.php (URL filter + Cache-Control send_headers)    │
 │       │   ├── header.php    (editorial nav)                              │
 │       │   ├── footer.php    (editorial footer)                           │
 │       │   ├── single.php    (editorial article template)                 │
 │       │   └── assets/css/torlyai-editorial.css                           │
 │       └── wp-content/uploads/  ← article images                          │
 │                                                                          │
 │   MySQL (local) — posts, pages, users, options                           │
 │                                                                          │
 │   PHP-FPM → WordPress bootstrap → theme → render HTML                    │
 │                                                                          │
 │   Response headers emitted:                                              │
 │     Cache-Control: public, s-maxage=3600, stale-while-revalidate=86400   │
 │     (anonymous public blog views only — enforced by send_headers action) │
 └─────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Request lifecycle — `/blog/<slug>/`

```mermaid
sequenceDiagram
    participant Browser
    participant VercelEdge as Vercel Edge (lhr1)
    participant VercelFn as Next.js serverless fn
    participant Oracle as Oracle VM Apache
    participant WP as WordPress PHP
    participant MySQL

    Browser->>VercelEdge: GET /blog/<slug>/
    alt cache HIT (within 1h of last MISS)
        VercelEdge->>Browser: 200 + cached HTML (x-vercel-cache: HIT)
        Note over VercelEdge: Zero serverless invocation
    else cache MISS
        VercelEdge->>VercelFn: invoke rewrite
        VercelFn->>Oracle: GET origin.torly.ai/blog/<slug>/
        Oracle->>WP: bootstrap + route
        WP->>MySQL: SELECT post, terms, meta
        MySQL-->>WP: rows
        WP->>WP: torlyai_rewrite_canonical_host filter (origin→torly.ai on every emitted URL)
        WP->>WP: torlyai_send_blog_cache_headers (Cache-Control: public s-maxage=3600 SWR=86400)
        WP-->>Oracle: rendered HTML
        Oracle-->>VercelFn: 200 + HTML + Cache-Control
        VercelFn-->>VercelEdge: pass-through + force CDN-Cache-Control via next.config.js headers()
        VercelEdge->>VercelEdge: cache for 3600s (keyed on path, not host)
        VercelEdge->>Browser: 200 + HTML (x-vercel-cache: MISS; next visitor sees HIT)
    end
    Note over Browser: URL bar stays on torly.ai/blog/<slug>/<br/>Canonical <link> says torly.ai/blog/<slug>/<br/>All asset URLs say torly.ai/wp-content/...
```

**Key invariant:** the browser URL bar shows `torly.ai` from the first hit and never flips to `origin.torly.ai`. That's the whole point of the rewrite layer.

---

## 4. Repo responsibilities

| Repo | Deploy target | Trigger | Owns | Does NOT own |
|---|---|---|---|---|
| **torlyAI** | Vercel | Push to `main` + commit message contains `[deploy]` | Marketing site, `/blog/*` rewrite config, CDN cache headers, app/pricing/agents/etc. | Article content, blog HTML, WordPress |
| **torly-blog** | Manual rsync to Oracle VM | SSH in + `git pull` + `rsync theme/` | WP theme (header/footer/single/archive/home), URL filter, WP cache-control headers | Routing, edge caching, marketing pages |
| **Oracle VM itself** | Not in git (the VM OS, Apache config, wp-config.php, MySQL) | Manual changes via SSH | The WordPress install, MySQL data, Apache config, PHP-FPM | Nothing in either repo |

**There is no CI/CD** from `torly-blog` → Oracle. Deploys are manual rsync (see §8).

---

## 5. The 2026-04-22 origin-URL incident

### Symptom
Google had indexed `https://torly.ai/blog/<slug>/` (correct). Users clicking those search results landed on `https://origin.torly.ai/blog/<slug>/` in their address bar. Link equity was splitting, and shared URLs leaked origin.

### Root cause (first pass)
`vercel.json` had the blog proxy configured as **302 redirects** to origin. Every `/blog/*` request returned `HTTP 302 Location: https://origin.torly.ai/...`, the browser followed, and the address bar flipped. This was intentional — it was the post-2026-04-17 fix to avoid a CPU budget blow-out (see §6).

### First fix attempt (commit `bd4bdf5`)
Flipped `/blog/*` from `redirects` to `rewrites` in `vercel.json`. With rewrites, Vercel fetches the origin server-side and streams the response back with the URL bar staying on torly.ai.

**Result:** `HTTP 200` was served, but all `/blog/*` URLs returned Next.js's 404 page, not the actual WordPress content.

### Root cause (second pass)
`pages/_error.tsx` exists as a Pages Router custom 404 handler. During `next build`, Next.js pre-renders it as a **static** 404 response for any path it can't match. At request time, Vercel's edge serves this static 404 BEFORE honoring `vercel.json`'s external rewrites — but **only for paths without a file extension**.

That's why `/wp-content/style.css` worked (file extension → Next.js skips routing → Vercel rewrite fires) but `/blog/foo/` didn't (no extension → Next.js routing intercepts → static 404 served).

### Second fix attempt (commit `3cf712f`)
Moved `/blog/*` rewrites from `vercel.json` into `next.config.js`'s `async rewrites()`. These run **inside** Next.js's routing pipeline, pre-empting the 404 handler for matched paths.

**Result:** HTTP 200 + WordPress content served through torly.ai — URL stops flipping. But `x-vercel-cache: MISS` on every request even after repeated fetches.

### Root cause (third pass)
Next.js `async rewrites()` to external URLs are NOT edge-cached by Vercel by default, even when the response has `Cache-Control: public, s-maxage=3600`. The `Cache-Control` header is interpreted as guidance for downstream HTTP clients (browsers, upstream CDNs), not for Vercel's own edge cache.

To force Vercel's edge to cache the response, the dedicated `CDN-Cache-Control` or `Vercel-CDN-Cache-Control` header must be set.

### Final fix (commit `12b8f0d`)
Added explicit CDN cache headers in `next.config.js` `async headers()`:

```javascript
{
  source: '/blog/:path*',
  headers: [
    { key: 'Cache-Control', value: 'public, s-maxage=3600, stale-while-revalidate=86400' },
    { key: 'CDN-Cache-Control', value: 'public, max-age=3600' },          // standards-based edge directive
    { key: 'Vercel-CDN-Cache-Control', value: 'public, max-age=3600' },   // Vercel-specific, highest priority
  ],
}
```

**Result:** first visitor sees `x-vercel-cache: MISS`, subsequent visitors within the hour see `HIT` + non-zero `age:` — the edge is now caching the rewrite response.

---

## 6. The 2026-04-17 CPU incident (why we can't just "always use rewrites")

Six days before the origin-URL incident, a previous `/blog/*` rewrite (without any cache headers) hit Vercel's Hobby plan Fluid Active CPU limit at **296% of budget**. The entire account — torlyai, aiskill-market, setupopenclaw, aicmo-canva-oauth — was paused for 2+ days.

Root cause: every bot/crawler hit to a blog URL burned a serverless invocation fetching the origin. No caching = multiplier of 1× visitors → 1× invocations.

The fix back then was to flip rewrites → redirects (free 302s, zero CPU). That worked for CPU but caused the URL-flip problem this doc covers.

**The right solution is both:** rewrites (for URL preservation) **and** mandatory cache headers (for CPU bounds). That's why the two caching directives in §7 are load-bearing — remove either and we regress one of the two incidents.

---

## 7. Load-bearing invariants

These are the contracts each layer must honor. Any one of them missing breaks the system in a specific way.

### 7.1 — WordPress must emit `Cache-Control: public` on anonymous blog views

**Owner:** `torly-blog/theme/torly-theme/functions.php`
**Function:** `torlyai_send_blog_cache_headers()`, hooked on `send_headers` action
**Contract:**
```
Cache-Control: public, s-maxage=3600, stale-while-revalidate=86400, max-age=0
Vary: Accept-Encoding
```
**Conditions that must skip:** logged-in users, wp-admin URIs, non-GET/HEAD, previews, customizer.

**What breaks if removed:** Vercel edge has nothing to cache against → every visitor triggers a serverless invocation → replay of the 2026-04-17 CPU incident.

### 7.2 — Next.js must set `CDN-Cache-Control` on `/blog/:path*`

**Owner:** `torlyAI/next.config.js` `async headers()`
**Contract:**
```javascript
{ key: 'CDN-Cache-Control',        value: 'public, max-age=3600' }
{ key: 'Vercel-CDN-Cache-Control', value: 'public, max-age=3600' }
```
**Why two:** `Vercel-CDN-Cache-Control` is vendor-specific and highest priority on Vercel's edge. `CDN-Cache-Control` is standards-based and portable (survives a hypothetical migration to another CDN).

**What breaks if removed:** Next.js external rewrites bypass Vercel edge cache → `x-vercel-cache: MISS` on every request → serverless cost scales with visitors, not with unique posts per hour.

### 7.3 — Blog rewrites MUST live in `next.config.js`, not `vercel.json`

**Owner:** `torlyAI/next.config.js` `async rewrites()`
**Why:** `pages/_error.tsx` causes Next.js to pre-render a static 404 at build time. That static 404 is served by Vercel's edge for any unmatched path — **shadowing** `vercel.json` external rewrites for extension-less paths.

By putting `/blog/*` rewrites in `next.config.js`, they run inside Next.js's routing layer and pre-empt the 404.

**Exception:** `/wp-content/*` and `/wp-includes/*` stay in `vercel.json` because file-extension paths bypass Next.js routing entirely (Vercel serves them directly).

**What breaks if violated:** `/blog/<slug>/` → static 404 instead of WP content.

### 7.4 — WordPress must rewrite every emitted URL from `origin.torly.ai` → `torly.ai`

**Owner:** `torly-blog/theme/torly-theme/functions.php`
**Function:** `torlyai_rewrite_canonical_host($url)` hooked on ~20 URL-emitting WP filters:
- `home_url`, `site_url`, `option_home`, `option_siteurl`
- `the_permalink`, `post_link`, `page_link`, `term_link`, `category_link`, `tag_link`, `author_link`, `day_link`, `month_link`, `year_link`, `post_type_link`, `get_the_guid`
- `content_url`, `stylesheet_directory_uri`, `template_directory_uri`, `stylesheet_uri`, `plugins_url`, `theme_root_uri`
- `wp_get_attachment_url`, `wp_get_attachment_image_src` (array wrapper), `upload_dir` (array wrapper)
- `feed_link`, `oembed_response_data`
- `the_content` (catches any absolute `origin.torly.ai` link pasted into post body)
- SEO plugin canonicals: `wpseo_canonical`, `rank_math_canonical`, `aioseop_canonical_url`

**What breaks if removed:** `<link rel="canonical">` points to origin → Google slowly reindexes origin.torly.ai as the authoritative host → link equity split → SEO erosion.

### 7.5 — The `[deploy]` commit-message tag on torlyAI

**Owner:** `torlyAI/vercel.json` → `ignoreCommand`
**Contract:** Vercel only builds commits whose message contains `[deploy]`. All other pushes to `main` are skipped.
**Why:** Hobby plan build-minute budget. See `torlyAI/CLAUDE.md` → Deployment Guidelines.

**What breaks if removed:** every push to `main` triggers a build, burning ~1 minute each. Docs-only commits waste budget.

### 7.6 — Vercel plan tier — Hobby has hard CPU caps

See `torlyAI/CLAUDE.md` → "Vercel Usage & Performance Rules" for the rule set. The short version:
- Fluid Active CPU: 4h/month account-wide
- Every project on the account shares that budget
- Exceeding pauses **all** projects for 24-48h

---

## 8. Deploy procedures

### 8.1 torlyAI → Vercel

1. Commit with `[deploy]` in the message.
2. `git push origin main`.
3. Vercel auto-builds the latest commit (because `ignoreCommand` matches). First run: ~2m.
4. Monitor: `vercel ls torlyai` — watch Status flip ● Building → ● Ready.
5. Verify: `curl -sI https://torly.ai/ | head -3` expects HTTP 200.

### 8.2 torly-blog → Oracle VM (MANUAL)

1. Commit + push to GitHub.
2. SSH to the VM: `ssh -i <key> ubuntu@141.147.89.179`.
3. On the VM, pull the repo mirror:
   ```bash
   cd /tmp/torly-blog-deploy
   git pull origin main
   ```
4. Rsync the changed files with ownership preservation:
   ```bash
   sudo rsync -av --chown=www-data:www-data \
     --exclude='.git/' --exclude='tests/' --exclude='playwright/' \
     /tmp/torly-blog-deploy/theme/torly-theme/ \
     /var/www/html/wp-content/themes/torly-theme/
   ```
5. Flush caches:
   ```bash
   cd /var/www/html
   sudo -u www-data HTTP_HOST=torly.ai wp cache flush
   sudo systemctl reload apache2     # clears PHP OPcache
   ```
6. Verify: `curl -sI https://origin.torly.ai/blog/ | grep -i cache-control` expects the `public s-maxage=3600` directive.

**Credentials are in macOS Keychain** (see `torlyAI/CLAUDE.md` → Credentials section). The SSH private key is base64-encoded in keychain — decode before use:
```bash
security find-generic-password -s "TorlyAI-SSH-PrivateKey" -a "ssh-key-2025-11-17" -w | base64 -d > /tmp/key && chmod 600 /tmp/key
```

**SSH user** is `ubuntu` (not `torly_user` — that keychain `acct` refers to the WP DB user, not the SSH login).

**Backup first:** `sudo cp -a /var/www/html/wp-content/themes/torly-theme{,-backup-$(date +%Y%m%d-%H%M%S)}`.

---

## 9. Debugging playbook

Follow in order. Stop at the first layer that looks wrong.

### 9.1 "URL flipped to origin.torly.ai in the address bar"

```bash
curl -sI https://torly.ai/blog/<slug>/ | head -5
```
- Returns **302 / 308** with `Location: origin.torly.ai/...` → rewrite rule missing.
  - Check `next.config.js` has `async rewrites()` with `/blog/:path*` destinations pointing at `origin.torly.ai`.
  - Check `vercel.json` does NOT also list `/blog/*` in `redirects` (competes with next.config.js).
- Returns **200** with `Location` absent → rewrite is firing. URL-bar flip must be coming from somewhere else:
  - WP-emitted redirects (check `wp_redirect_canonical` plugin setting).
  - JavaScript on the page doing `window.location = …`.

### 9.2 "`/blog/<slug>/` returns 404 through torly.ai"

```bash
curl -sI https://torly.ai/blog/<slug>/
curl -sI https://origin.torly.ai/blog/<slug>/
```
- Origin says **200**, torly.ai says **404** with `content-disposition: inline; filename="404"` → Next.js static 404 is shadowing the rewrite.
  - Ensure `/blog/*` is in `next.config.js` `async rewrites()`, NOT in `vercel.json` `rewrites`.
  - Trigger a fresh deploy (`[deploy]`-tagged empty commit) to invalidate stale edge cache of prior 404s.
- Origin says **404** → WP doesn't have the post (deleted? slug changed? trash state?).

### 9.3 "`x-vercel-cache: MISS` on every request"

```bash
curl -sI https://torly.ai/blog/<slug>/ | grep -iE "cache-control|cdn-cache|x-vercel-cache"
```
- No `cdn-cache-control` header → `next.config.js` `async headers()` is missing the two CDN directives. Add them per §7.2.
- `cdn-cache-control` present but still MISS → WP might be sending `Set-Cookie`, which Vercel interprets as "user-specific, don't cache." Check for session cookies on anonymous responses:
  ```bash
  curl -sI https://origin.torly.ai/blog/<slug>/ | grep -i set-cookie
  ```
  If present, investigate the WP plugin emitting it (analytics, cart, etc.) and exclude.

### 9.4 "Canonical tag points at origin.torly.ai"

```bash
curl -s https://origin.torly.ai/blog/<slug>/ | grep -oE '<link[^>]*rel="canonical"[^>]*>'
```
- Shows `href="https://origin.torly.ai/..."` → `torlyai_rewrite_canonical_host` filter is not firing.
  - Check the filter function is defined in `functions.php`.
  - Check it's registered on `home_url`, `site_url`, `the_permalink`, etc. at priority 99.
  - If using Yoast/RankMath, also hook their plugin-specific canonical filters.

### 9.5 "Vercel Usage dashboard shows Fluid CPU climbing"

Go to Vercel Dashboard → Overview → Usage. If CPU is approaching 4h/month:
- `curl -sI https://torly.ai/blog/<a-popular-post>/` — if `x-vercel-cache` shows MISS repeatedly, §9.3 applies.
- `curl -sI https://origin.torly.ai/blog/<a-popular-post>/` — if no `Cache-Control: public` header, §7.1 applies. WP isn't emitting the directive (maybe a plugin called `nocache_headers()` or the theme got reverted).
- Emergency revert: flip `/blog/*` in `next.config.js` from `rewrites` back to `redirects` (will re-introduce URL flip but stops CPU burn instantly).

---

## 10. Rollback procedures

### Fully revert the rewrite architecture (restore 302-redirect behavior)

1. In `torlyAI/next.config.js`: change `async rewrites()` blog rules → move them back into `async redirects()` with `permanent: false`.
2. In `torlyAI/vercel.json`: remove `/blog/*` entries from `rewrites` array (if still there).
3. Commit with `[deploy]`, push.
4. The URL bar will flip to origin.torly.ai again, but CPU usage drops to zero.

### Revert the WP-side URL filter (restore origin.torly.ai in canonicals)

1. In `torly-blog/theme/torly-theme/functions.php`: comment out or delete the `torlyai_rewrite_canonical_host` block and all the `add_filter` calls registering it.
2. Commit + push + rsync to VM + `wp cache flush` + `systemctl reload apache2`.
3. Canonical tags will emit origin.torly.ai again — expect Google to slowly reindex origin.

### Revert a specific commit

On torlyAI:
```bash
git revert <commit-sha>
# Add [deploy] to the revert message if you want it to ship
git push origin main
```

On torly-blog (same Git workflow, then manual rsync to VM):
```bash
git revert <commit-sha>
git push origin main
# SSH to VM and re-run §8.2 steps 3-5
```

---

## 11. Non-goals and deliberately-not-fixed items

- **Articles are not mirrored into the Vercel repo.** Content lives only on Oracle MySQL. A VM loss = article loss unless backed up separately (WordPress database backups are an operations-team responsibility, out of scope here).
- **No CI/CD for torly-blog → VM.** The rsync workflow is manual. Adding a GitHub Actions rsync deploy step is possible but requires VM SSH credentials in GH secrets — deferred.
- **`pages/_error.tsx`** is left in place even though it's the reason `vercel.json` rewrites can't shadow Next.js routing. Removing it would require migrating the custom error UI to `app/not-found.tsx` + `app/error.tsx` — not worth the regression risk. The `next.config.js` rewrite approach sidesteps the issue.
- **`origin.torly.ai` subdomain is still public.** We don't `noindex` it because Google uses the canonical directive to consolidate indexing on torly.ai; adding noindex risks deranking. If a page gets mistakenly indexed at origin, use GSC URL removal.
- **Admin paths stay as 302 redirects** (not rewrites). `/wp-admin`, `/wp-login`, `/wp-json`, `/feed`, `/category`, `/tag` all flip to origin — that's intentional. Admin sessions use origin-domain cookies; rewrites would break them.

---

## 12. Change log

| Date | Event | Commits |
|---|---|---|
| 2026-04-17 | **CPU incident:** `/blog/*` rewrites without caching burned Hobby plan Fluid CPU to 296%. Account paused 2+ days. Flipped rewrites → 302 redirects. | — |
| 2026-04-21 | **Editorial redesign shipped:** header/footer/single-post WP templates + canonical URL filter (torly-blog `398e40b`, `2cedb33`, `b27ddcc`). | torly-blog: 398e40b, 2cedb33, b27ddcc |
| 2026-04-22 | **Origin-URL incident resolved** across four commits (see §5). Final state: Next.js rewrites + CDN cache headers + WP cache-control + URL filter = URL stays on torly.ai with safe CPU. | torlyAI: bd4bdf5, 3cf712f, 12b8f0d; torly-blog: 820d1d7 |
| 2026-04-22 | **Admin-login incident resolved** (see §14). Three-layer nested bug in the host-rewrite filter silently broke `wp-admin` login. | torly-blog: ab5aa43, 2cbea5a + wp-config.php edit on VM |
| 2026-04-22 | **This doc created + extended with admin-login incident.** Saved identically to both repos. | — |

---

## 14. Admin-login incident (2026-04-22, evening)

### Symptom
Visiting `https://origin.torly.ai/wp-login.php`, typing correct admin credentials, and clicking **Log In** did nothing. The page silently re-rendered with empty fields — no error banner, no red notice. Classic WP "cookies blocked" silent-fail pattern. But cookies were NOT blocked at the browser level — the bug was deeper.

### Root cause (three nested layers)

**Layer 1 — Cross-origin form POST.** The login form's `action` attribute was `https://torly.ai/wp-login.php`, but the browser was at `https://origin.torly.ai`. Form POSTs to torly.ai → Vercel 302 redirects back to origin → browser converts POST→GET on redirect, stripping the form body → WP sees a bodyless GET and re-renders the empty login form.

**Layer 2 — The URL filter's admin-path exemption never matched.** Added an exemption regex for `/(wp-admin|wp-login\.php|wp-json|xmlrpc\.php)` in `torlyai_rewrite_canonical_host()` to keep admin URLs on origin. Live instrumentation (trace probe) showed the filter input was already `https://torly.ai/wp-login.php` — fully rewritten before our regex could examine it.

**Layer 3 — `option_siteurl` runs before WP concatenates the path.** The filter was hooked on four functions: `home_url`, `site_url`, `option_home`, `option_siteurl`. The `option_*` variants fire on the bare base URL (`https://origin.torly.ai`), BEFORE WP concatenates `/wp-login.php`. So the option filter rewrote origin→torly.ai on the base, WP then appended `/wp-login.php` → final URL `https://torly.ai/wp-login.php`. The `site_url` filter fired next but received the already-rewritten URL.

### Fix (paired with `COOKIE_DOMAIN = false`)

1. **`torly-blog@ab5aa43`** — add regex exemption for admin/login/REST paths:
   ```php
   if (preg_match('#/(wp-admin|wp-login\.php|wp-json|xmlrpc\.php)(/|$|\?)#', $url)) {
       return $url;
   }
   ```

2. **`torly-blog@2cbea5a`** — drop `option_home` / `option_siteurl` from the filter chain, keeping only `home_url` / `site_url` which receive the full URL post-concatenation. Now the exemption regex has access to the path and can correctly skip admin URLs.

3. **`wp-config.php` on VM (backup at `wp-config.php.bak-20260422-214159`)** — added `define('COOKIE_DOMAIN', false);`. Before this, auth cookies were being set with `Domain=torly.ai` (derived from option_siteurl), so browsers at origin.torly.ai silently rejected them. `false` tells WP to omit the Domain attribute, making cookies scope to the current request host.

### Verification (end-to-end via Playwright)
- Login form action is `https://origin.torly.ai/wp-login.php` (same-origin) ✓
- Admin login with correct credentials lands on `/wp-admin/` dashboard showing "Howdy, admin" ✓
- `<link rel="canonical">` on blog posts still emits `torly.ai/blog/...` — SEO preserved ✓
- `og:url`, `og:image`, `twitter:image`, RSS `<link>`, blog-index post URLs all still emit `torly.ai` ✓
- Only URLs now emitting `origin.torly.ai`: `/wp-admin/admin-ajax.php`, `/wp-json` self-links, `xmlrpc.php?rsd` — all intentionally exempt, none indexed by Google

### Load-bearing invariants (add to §7 rules in spirit)

- **URL-rewrite host filter must hook at the URL-output stage (`home_url` / `site_url`), NOT at the option stage (`option_home` / `option_siteurl`).** The option filters fire before path concatenation, so any path-based exemption logic won't work. If you ever see an admin URL being rewritten that shouldn't be, check if the filter list accidentally includes the option_* variants.
- **Admin paths (`/wp-admin`, `/wp-login.php`, `/wp-json`, `/xmlrpc.php`) must be exempted from host rewriting.** Cross-origin form POSTs + 302 redirects = lost POST bodies = silent login failures. Same rule applies to any future admin/API path we add.
- **`COOKIE_DOMAIN = false` in wp-config.php must stay.** Without it, WP derives cookie domain from siteurl, which the theme's auto-fix keeps forcing to `torly.ai`. Cookies then don't stick at origin.torly.ai, breaking admin login.
- **Admin UI URL is `https://origin.torly.ai/wp-admin/`, NOT through the Vercel proxy.** Vercel's firewall (`x-vercel-mitigated: deny`) blocks `/wp-admin/*` and `/wp-json/*` at the edge by default. This is a platform-level decision by Vercel (WP admin is treated as an attack signature on Next.js projects). Admin users bookmark the origin URL.

### Debugging playbook — "admin login silently fails"

If this happens again:

1. **Check the form action host** — `curl -s <login-url> | grep -oE '<form[^>]*action="[^"]*"'`. If action host ≠ current host, that's the bug. Cross-origin POSTs die on 302s.
2. **Check `site_url()` with and without path** — deploy a trace probe (see commit `2cbea5a` comments) that logs the filter input. If the input is already fully rewritten when your filter sees it, the rewrite is happening upstream (probably at `option_*url` stage).
3. **Check browser `Set-Cookie` headers** — they must NOT have `Domain=torly.ai` when the browser is on `origin.torly.ai`. If they do, `COOKIE_DOMAIN` config is missing or wrong.
4. **Don't trust "no error" as "no bug"** — WP's login handler fails silently when cookies are malformed. Use server-side session table (`wp user meta get admin session_tokens`) to see whether WP is creating sessions at all. If yes, browser is rejecting cookies; if no, POST isn't reaching the handler.

---

## 13. Related docs

- `torlyAI/CLAUDE.md` → "Vercel Usage & Performance Rules" (load-bearing CPU protection rules + blog exception)
- `torlyAI/docs/ARCHITECTURE.md` → overall app architecture
- `torly-blog/CLAUDE.md` → WP-specific operational notes
- `torly-blog/docs/ORACLE_CLOUD_INFRASTRUCTURE.md` → VM configuration

---

*If a future deploy fails with `/blog/*` errors or URL flipping, read §5 + §9 before making changes.*
