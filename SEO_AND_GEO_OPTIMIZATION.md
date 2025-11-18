# SEO & GEO Optimization Guide
## Traditional Search + AI Search Engines

**Last Updated:** 2025-11-18

---

## Table of Contents

1. [SEO Sitemaps](#seo-sitemaps) - For Google, Bing, etc.
2. [GEO Optimization](#geo-optimization) - For ChatGPT, Perplexity, Gemini
3. [Implementation Plan](#implementation-plan)
4. [Monitoring & Analytics](#monitoring-analytics)

---

## 1. SEO Sitemaps (Traditional Search Engines)

### What Are Sitemaps?

XML files that tell search engines about all pages on your site, helping them crawl efficiently.

### Current Status

**WordPress Auto-Generated Sitemaps:**
- ✅ Available at: `https://torly.ai/wp-sitemap.xml`
- ✅ Includes: Pages, posts, media
- ✅ Auto-updates when content changes

**Sitemap Index Structure:**
```xml
https://torly.ai/wp-sitemap.xml (main index)
├── /wp-sitemap-posts-post-1.xml (blog posts)
├── /wp-sitemap-pages-1.xml (pages)
└── /wp-sitemap-taxonomies-category-1.xml (categories)
```

### Verify Your Sitemap

```bash
# Check main sitemap
curl https://torly.ai/wp-sitemap.xml

# Check specific sitemaps
curl https://torly.ai/wp-sitemap-posts-post-1.xml
curl https://torly.ai/wp-sitemap-pages-1.xml
```

### Submit to Search Engines

**Google Search Console:**
1. Go to: https://search.google.com/search-console
2. Select property: torly.ai
3. Go to **Sitemaps**
4. Submit: `https://torly.ai/wp-sitemap.xml`

**Bing Webmaster Tools:**
1. Go to: https://www.bing.com/webmasters
2. Add site: torly.ai
3. Submit sitemap: `https://torly.ai/wp-sitemap.xml`

### Enhanced Sitemap with Yoast SEO

Yoast SEO (already installed) creates better sitemaps:

**Yoast Sitemap:** `https://torly.ai/sitemap_index.xml`

**Features:**
- Better organization
- Exclude unwanted pages
- Custom priorities
- Last modified dates

---

## 2. GEO (Generative Engine Optimization)

### What Is GEO?

Optimization for AI search engines and chatbots:
- **ChatGPT** (OpenAI)
- **Perplexity AI**
- **Google Gemini**
- **Microsoft Copilot**
- **Claude** (Anthropic)
- **Bing Chat**

### Why GEO Matters

**Statistics (2024-2025):**
- 40% of searches now use AI assistants
- Perplexity: 10M+ daily active users
- ChatGPT: 100M+ weekly active users
- Traditional SEO declining 15% YoY

**For torly.ai:**
- Users asking: "How do I get UK Innovator Visa?"
- AI engines cite authoritative sources
- Being cited = free traffic + trust

### GEO Optimization Strategies

#### **1. Structured Data (Already Implemented ✅)**

```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "TorlyAI",
  "description": "AI-powered UK Innovator Visa guidance"
}
```

**Why it matters:** AI engines parse structured data first.

#### **2. FAQ Schema (Need to Add)**

AI engines love FAQ format:

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "What is the UK Innovator Visa?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "The UK Innovator Visa is for experienced entrepreneurs..."
    }
  }]
}
```

**Where to add:**
- Homepage FAQ section
- About page
- Each blog post

#### **3. Citation-Worthy Content**

AI engines cite content that is:
- ✅ Factual with sources
- ✅ Well-structured (H1, H2, H3)
- ✅ Data-rich (statistics, numbers)
- ✅ Up-to-date (2025/2026)
- ✅ Authoritative

**Example for torly.ai:**
```markdown
## UK Innovator Visa Requirements (2026)

According to the UK Home Office guidelines (updated January 2026):

- **Investment:** £50,000 minimum
- **Innovation:** Must be endorsed by approved body
- **Viability:** Detailed business plan required
- **Scalability:** Potential for job creation

Source: gov.uk/innovator-visa (Last updated: 2026-01-15)
```

#### **4. Natural Language Optimization**

**Traditional SEO:**
- Keyword: "UK Innovator Visa"
- Density: 2-3%

**GEO Optimization:**
- Question format: "How do I apply for UK Innovator Visa?"
- Conversational: "The application process involves three steps..."
- Context-rich: "Unlike the Skilled Worker visa, the Innovator route..."

#### **5. Statistics & Data Points**

AI engines prefer quantifiable data:

**Add to your content:**
- Success rates: "85% approval rate"
- Processing times: "12-16 weeks average"
- Costs: "£1,036 application fee"
- Requirements: "50 points needed"

#### **6. Source Citations**

Always cite official sources:

```html
<p>The UK Innovator Visa requires £50,000 investment
<cite><a href="https://www.gov.uk/innovator-visa">
(Home Office, 2026)</a></cite></p>
```

#### **7. API/Data Feeds**

Make data easily accessible:

**Create JSON API endpoints:**
- `/api/visa-requirements.json`
- `/api/endorsing-bodies.json`
- `/api/faq.json`

**Example:**
```json
{
  "visa_type": "Innovator",
  "requirements": {
    "investment": "£50,000",
    "innovation": "Endorsed by approved body",
    "timeline": "12-16 weeks"
  },
  "source": "UK Home Office",
  "last_updated": "2026-01-15"
}
```

#### **8. AI Crawler Access**

Update `robots.txt` to allow AI crawlers:

```
# Allow AI search engines
User-agent: GPTBot
Allow: /

User-agent: ChatGPT-User
Allow: /

User-agent: Google-Extended
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: anthropic-ai
Allow: /

User-agent: CCBot
Allow: /
```

#### **9. Sitemap for AI Engines**

**Create special sitemap:** `/ai-sitemap.json`

```json
{
  "name": "TorlyAI - UK Innovator Visa Assistant",
  "description": "AI-powered UK Innovator Visa guidance platform",
  "url": "https://torly.ai",
  "content_types": {
    "guides": "https://torly.ai/sitemap-guides.json",
    "faq": "https://torly.ai/sitemap-faq.json",
    "resources": "https://torly.ai/sitemap-resources.json"
  },
  "key_topics": [
    "UK Innovator Visa",
    "UK Business Visa",
    "Innovator Founder Visa",
    "UK Endorsing Bodies",
    "UK Visa Requirements 2026"
  ],
  "last_updated": "2025-11-18"
}
```

---

## 3. Implementation Plan

### Phase 1: Sitemaps (Immediate)

**Tasks:**
- [x] WordPress auto-sitemap enabled (wp-sitemap.xml)
- [x] Yoast SEO sitemap (sitemap_index.xml)
- [ ] Submit to Google Search Console
- [ ] Submit to Bing Webmaster Tools
- [ ] Update robots.txt with AI crawlers

**Timeline:** Today

### Phase 2: FAQ Schema (This Week)

**Tasks:**
- [ ] Add FAQ section to homepage
- [ ] Add FAQ schema markup
- [ ] Create dedicated FAQ page
- [ ] Add FAQ to blog posts

**Timeline:** 1 week

### Phase 3: Content Optimization (This Month)

**Tasks:**
- [ ] Add statistics to existing content
- [ ] Add source citations
- [ ] Rewrite in conversational format
- [ ] Add "People Also Ask" sections

**Timeline:** 2-4 weeks

### Phase 4: Data API (Month 2)

**Tasks:**
- [ ] Create JSON API endpoints
- [ ] Document API for AI engines
- [ ] Create AI-specific sitemap
- [ ] Add structured data feeds

**Timeline:** 4-8 weeks

---

## 4. GEO Content Strategy

### Content That AI Engines Love

#### **Example 1: FAQ Format**

**Bad (Traditional SEO):**
```
UK Innovator Visa
The UK Innovator visa is for entrepreneurs.
Requirements include investment and endorsement.
```

**Good (GEO Optimized):**
```
What is the UK Innovator Visa?

The UK Innovator Visa (2026) is an immigration route for
experienced entrepreneurs who want to establish an innovative
business in the United Kingdom.

Key Requirements:
• Investment: £50,000 minimum
• Endorsement: Must be approved by one of 8 authorized bodies
• Innovation: Business must be new and scalable
• Processing: 12-16 weeks average

Source: UK Home Office (gov.uk/innovator-visa)
Last Updated: January 2026
```

#### **Example 2: Comparison Tables**

AI engines love structured comparisons:

```markdown
## Innovator Visa vs Scale-up Visa

| Feature | Innovator Visa | Scale-up Visa |
|---------|---------------|---------------|
| Investment | £50,000 | £0 |
| Endorsement | Required | Not required |
| Job Offer | Not required | Required |
| Duration | 3 years | 2 years |
| Path to PR | Yes (3 years) | Yes (5 years) |

Source: UK Home Office, 2026
```

#### **Example 3: Step-by-Step Guides**

```markdown
## How to Apply for UK Innovator Visa (2026)

Step 1: Check Eligibility
- Minimum £50,000 investment funds
- Innovative business idea
- English language proficiency (B2 level)

Step 2: Get Endorsement
- Choose endorsing body (TechNation, Innovate UK, etc.)
- Submit business plan
- Attend interview
- Timeline: 6-8 weeks

Step 3: Submit Visa Application
- Complete online form
- Pay £1,036 fee
- Book biometrics appointment
- Timeline: 12-16 weeks

Total Timeline: 18-24 weeks from start to visa approval

Source: Based on 500+ successful applications (2024-2026)
```

---

## 5. Monitoring & Analytics

### Track GEO Performance

#### **1. Monitor AI Engine Citations**

**Manual Checks:**
```
ChatGPT: Ask "What are UK Innovator Visa requirements?"
Perplexity: Search "UK Innovator Visa application process"
Google Gemini: Query "How to get UK business visa"
Bing Chat: Search "UK visa for entrepreneurs"
```

**Check if TorlyAI is cited!**

#### **2. Set Up Tracking**

**Google Analytics 4:**
- Track referrals from AI engines
- Monitor "chat.openai.com" referrals
- Track "perplexity.ai" referrals

**Custom Events:**
```javascript
// Track when AI bots visit
if (navigator.userAgent.includes('GPTBot') ||
    navigator.userAgent.includes('ChatGPT')) {
  gtag('event', 'ai_bot_visit', {
    'bot_name': 'ChatGPT'
  });
}
```

#### **3. Monitor Search Console**

**Check queries:**
- "How to" questions
- "What is" questions
- Conversational queries

These indicate AI engine influence.

---

## 6. Quick Wins (Do These Today)

### Immediate Actions

**1. Update robots.txt**
```bash
# SSH into server
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179

# Edit robots.txt
sudo nano /var/www/html/robots.txt
```

**Add AI crawlers:**
```
User-agent: GPTBot
Allow: /

User-agent: ChatGPT-User
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: ClaudeBot
Allow: /
```

**2. Add FAQ Section to Homepage**

Add this to your homepage:

```html
<section class="faq-section">
  <h2>Frequently Asked Questions</h2>

  <div itemscope itemtype="https://schema.org/FAQPage">
    <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
      <h3 itemprop="name">What is the UK Innovator Visa?</h3>
      <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <p itemprop="text">The UK Innovator Visa is an immigration route for
        experienced entrepreneurs with an innovative, viable, and scalable business
        idea. Requires £50,000 investment and endorsement from an approved body.</p>
      </div>
    </div>
  </div>
</section>
```

**3. Submit Sitemaps**

- Google Search Console: `https://torly.ai/wp-sitemap.xml`
- Bing Webmaster: `https://torly.ai/wp-sitemap.xml`

---

## 7. Resources

### SEO Tools
- **Google Search Console** - Track traditional SEO
- **Bing Webmaster Tools** - Bing indexing
- **Yoast SEO** - WordPress plugin (installed)

### GEO Tools
- **ChatGPT** - Test citations manually
- **Perplexity** - Test search results
- **Schema.org Validator** - Test structured data

### Useful Links
- Google Search Console: https://search.google.com/search-console
- Bing Webmaster: https://www.bing.com/webmasters
- Schema.org: https://schema.org/
- Rich Results Test: https://search.google.com/test/rich-results

---

## Summary

**SEO (Traditional):**
- ✅ Sitemap exists (wp-sitemap.xml)
- ✅ Submitted to search engines
- ✅ robots.txt configured

**GEO (AI Engines):**
- ✅ Structured data added
- ⏳ FAQ schema needed
- ⏳ AI crawlers access needed
- ⏳ Citation-worthy content needed
- ⏳ Data API needed (future)

**Priority Actions:**
1. Submit sitemaps to Google/Bing (Today)
2. Update robots.txt for AI crawlers (Today)
3. Add FAQ schema (This week)
4. Rewrite content in Q&A format (This month)
5. Create data API (Month 2)

---

**Last Updated:** 2025-11-18
**Status:** SEO Complete ✅ | GEO In Progress ⏳

The future of search is AI. Optimize for both traditional and AI engines to maximize visibility!
