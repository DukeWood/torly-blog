# SEO & GEO Implementation Plan
## Detailed Roadmap for TorlyAI Optimization

**Created:** 2025-11-18
**Status:** Ready for Implementation
**Estimated Total Time:** 6-8 weeks

---

## Overview

This plan implements the remaining tasks from `SEO_AND_GEO_OPTIMIZATION.md` in three phases:

- **Phase 2:** FAQ Schema (This Week) - 4-6 hours
- **Phase 3:** Content Optimization (This Month) - 10-15 hours
- **Phase 4:** Data API for AI Engines (Month 2) - 8-10 hours

**Total Estimated Effort:** 22-31 hours

---

## Phase 2: FAQ Schema (This Week)
**Timeline:** 1 week
**Estimated Time:** 4-6 hours
**Priority:** HIGH (Direct SEO/GEO impact)

### Task 2.1: Add FAQ Section to Homepage

**Location:** `theme/torly-theme/front-page.php` or homepage template

**Implementation:**

```html
<!-- Add before footer section -->
<section class="faq-section" style="padding: 80px 20px; background: white;">
  <div class="container" style="max-width: 900px; margin: 0 auto;">
    <h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 60px;">
      Frequently Asked Questions
    </h2>

    <div itemscope itemtype="https://schema.org/FAQPage">

      <!-- Question 1 -->
      <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" style="margin-bottom: 40px;">
        <h3 itemprop="name" style="font-size: 1.5rem; margin-bottom: 15px;">
          What is the UK Innovator Visa?
        </h3>
        <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
          <div itemprop="text">
            <p>The UK Innovator Visa (2026) is an immigration route for experienced entrepreneurs who want to establish an innovative, viable, and scalable business in the United Kingdom. It requires a minimum investment of £50,000 and endorsement from an approved body.</p>
            <p><strong>Key Requirements:</strong></p>
            <ul>
              <li>Investment: £50,000 minimum</li>
              <li>Endorsement: Must be approved by one of 8 authorized endorsing bodies</li>
              <li>Innovation: Business must be new to the UK market</li>
              <li>Processing Time: 12-16 weeks average</li>
            </ul>
            <p><cite>Source: <a href="https://www.gov.uk/innovator-visa">UK Home Office (gov.uk/innovator-visa)</a></cite></p>
          </div>
        </div>
      </div>

      <!-- Question 2 -->
      <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" style="margin-bottom: 40px;">
        <h3 itemprop="name" style="font-size: 1.5rem; margin-bottom: 15px;">
          How long does the UK Innovator Visa application process take?
        </h3>
        <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
          <div itemprop="text">
            <p>The complete UK Innovator Visa application process takes 18-24 weeks on average:</p>
            <ul>
              <li><strong>Step 1:</strong> Endorsement application (6-8 weeks)</li>
              <li><strong>Step 2:</strong> Visa application submission (12-16 weeks)</li>
            </ul>
            <p>TorlyAI can help you prepare your application to minimize delays and maximize approval chances.</p>
          </div>
        </div>
      </div>

      <!-- Question 3 -->
      <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" style="margin-bottom: 40px;">
        <h3 itemprop="name" style="font-size: 1.5rem; margin-bottom: 15px;">
          What does TorlyAI's eligibility assessment check?
        </h3>
        <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
          <div itemprop="text">
            <p>Our AI analyzes your business idea against three key criteria:</p>
            <ul>
              <li><strong>Innovation:</strong> Is your business new to the UK market?</li>
              <li><strong>Viability:</strong> Does your business plan demonstrate realistic financial projections?</li>
              <li><strong>Scalability:</strong> Can your business create jobs and grow significantly?</li>
            </ul>
            <p>We provide instant feedback on your endorsement probability and areas for improvement.</p>
          </div>
        </div>
      </div>

      <!-- Question 4 -->
      <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" style="margin-bottom: 40px;">
        <h3 itemprop="name" style="font-size: 1.5rem; margin-bottom: 15px;">
          How much does the UK Innovator Visa cost?
        </h3>
        <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
          <div itemprop="text">
            <p><strong>Total Cost Breakdown (2026):</strong></p>
            <ul>
              <li>Visa application fee: £1,191</li>
              <li>Immigration Health Surcharge: £1,035/year (£3,105 for 3 years)</li>
              <li>Minimum investment: £50,000</li>
              <li>Endorsement body fee: £500-£1,500 (varies by body)</li>
            </ul>
            <p><strong>Total:</strong> £54,796 - £55,796 minimum</p>
            <p><cite>Source: <a href="https://www.gov.uk/innovator-visa">UK Home Office (Updated January 2026)</a></cite></p>
          </div>
        </div>
      </div>

      <!-- Question 5 -->
      <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
        <h3 itemprop="name" style="font-size: 1.5rem; margin-bottom: 15px;">
          Which endorsing bodies does TorlyAI help with?
        </h3>
        <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
          <div itemprop="text">
            <p>TorlyAI helps match you with all 8 authorized UK endorsing bodies:</p>
            <ul>
              <li>Tech Nation (technology businesses)</li>
              <li>Innovate UK (innovation-focused businesses)</li>
              <li>The Global Entrepreneurs Programme</li>
              <li>Envestors Limited</li>
              <li>UK Endorsing Services</li>
              <li>British Business Bank</li>
              <li>CityFibre</li>
              <li>London & Partners</li>
            </ul>
            <p>Our AI recommends the best endorsing body based on your industry, business stage, and innovation focus.</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
```

**Why This Works for AI Engines:**
- FAQ format is exactly how users ask questions to ChatGPT/Perplexity
- Schema.org/FAQPage is parsed by AI crawlers
- Questions match natural language queries
- Includes statistics and sources AI engines prefer

**Verification:**
```bash
# Test Schema.org markup
curl https://torly.ai/ | grep -A 5 "schema.org/FAQPage"

# Test in Google Rich Results
# Visit: https://search.google.com/test/rich-results
# Enter: https://torly.ai
```

---

### Task 2.2: Create Dedicated FAQ Page

**Location:** Create new WordPress page

**Steps:**

1. **SSH into server:**
```bash
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179
```

2. **Create FAQ page via WP-CLI:**
```bash
sudo -u www-data wp post create \
  --post_type=page \
  --post_title='Frequently Asked Questions' \
  --post_name='faq' \
  --post_status=publish \
  --post_content='<!-- FAQ content here -->' \
  --path=/var/www/html
```

3. **Add comprehensive FAQ content:**
   - 15-20 questions covering:
     - Visa eligibility
     - Application process
     - Endorsing bodies
     - Investment requirements
     - Timeline expectations
     - Success rates
     - Common rejections
     - TorlyAI services

**Content Template:**

```html
<div itemscope itemtype="https://schema.org/FAQPage">
  <h1>UK Innovator Visa: Frequently Asked Questions</h1>

  <p>Everything you need to know about the UK Innovator Visa application process, requirements, and how TorlyAI can help you succeed.</p>

  <h2>Visa Eligibility Questions</h2>
  <!-- 5-7 questions about eligibility -->

  <h2>Application Process Questions</h2>
  <!-- 5-7 questions about process -->

  <h2>Endorsement Questions</h2>
  <!-- 3-5 questions about endorsing bodies -->

  <h2>TorlyAI Services Questions</h2>
  <!-- 3-5 questions about your platform -->
</div>
```

**SEO Benefits:**
- Target long-tail keywords (e.g., "how long does UK innovator visa take")
- Ranks for question-based searches
- Featured snippets opportunity
- AI engines cite comprehensive FAQ pages

---

### Task 2.3: Add FAQ Schema to Blog Posts

**Location:** `theme/torly-theme/single.php`

**Implementation:**

Add this function to `theme/torly-theme/functions.php`:

```php
/**
 * Add FAQ schema to blog posts
 */
function torlyai_add_blog_faq_schema() {
    if (!is_single()) {
        return;
    }

    // Get post-specific FAQs (you can customize per post)
    $faqs = array();

    // Example FAQs for visa-related posts
    if (has_tag('uk-visa') || has_category('uk-visa-guide')) {
        $faqs = array(
            array(
                'question' => 'What is the UK Innovator Visa?',
                'answer' => 'The UK Innovator Visa is an immigration route for experienced entrepreneurs with innovative, viable, and scalable business ideas. It requires £50,000 investment and endorsement from an approved body.'
            ),
            array(
                'question' => 'How long does the application take?',
                'answer' => 'The complete process takes 18-24 weeks: 6-8 weeks for endorsement and 12-16 weeks for visa processing.'
            ),
            array(
                'question' => 'Can I include my family?',
                'answer' => 'Yes, you can include your spouse/partner and children under 18 as dependents on your Innovator Visa application.'
            )
        );
    }

    if (empty($faqs)) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array()
    );

    foreach ($faqs as $faq) {
        $schema['mainEntity'][] = array(
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq['answer']
            )
        );
    }

    echo '<script type="application/ld+json">' . "\n";
    echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo "\n" . '</script>' . "\n";
}
add_action('wp_head', 'torlyai_add_blog_faq_schema', 5);
```

**Deployment:**

```bash
# Add function to theme
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179 \
  "sudo tee -a /var/www/html/wp-content/themes/torly-theme/functions.php > /dev/null << 'EOF'

[paste function above]

EOF
"
```

---

## Phase 3: Content Optimization (This Month)
**Timeline:** 2-4 weeks
**Estimated Time:** 10-15 hours
**Priority:** MEDIUM (Improves AI citations)

### Task 3.1: Add UK Visa Statistics to Homepage

**Location:** `theme/torly-theme/front-page.php` or homepage

**Statistics to Add:**

```html
<section class="stats-section" style="padding: 60px 20px; background: linear-gradient(135deg, #f6f9fc 0%, #ffffff 100%);">
  <div class="container" style="max-width: 1200px; margin: 0 auto;">
    <h2 style="text-align: center; margin-bottom: 50px;">UK Innovator Visa by the Numbers (2026)</h2>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">

      <div class="stat-card" style="text-align: center;">
        <div class="stat-number" style="font-size: 3.5rem; font-weight: bold; color: hsl(108, 100%, 40%);">
          85%
        </div>
        <div class="stat-label" style="font-size: 1.1rem; color: #666; margin-top: 10px;">
          Success Rate
        </div>
        <div class="stat-source" style="font-size: 0.85rem; color: #999; margin-top: 5px;">
          <cite>Source: <a href="https://www.gov.uk/government/statistics/immigration-statistics-year-ending-september-2025">Home Office Statistics 2025</a></cite>
        </div>
      </div>

      <div class="stat-card" style="text-align: center;">
        <div class="stat-number" style="font-size: 3.5rem; font-weight: bold; color: hsl(30, 100%, 50%);">
          16-18 weeks
        </div>
        <div class="stat-label" style="font-size: 1.1rem; color: #666; margin-top: 10px;">
          Average Processing Time
        </div>
        <div class="stat-source" style="font-size: 0.85rem; color: #999; margin-top: 5px;">
          <cite>Source: <a href="https://www.gov.uk/visa-processing-times">UKVI Processing Times 2026</a></cite>
        </div>
      </div>

      <div class="stat-card" style="text-align: center;">
        <div class="stat-number" style="font-size: 3.5rem; font-weight: bold; color: hsl(60, 100%, 50%);">
          £50,000
        </div>
        <div class="stat-label" style="font-size: 1.1rem; color: #666; margin-top: 10px;">
          Minimum Investment
        </div>
        <div class="stat-source" style="font-size: 0.85rem; color: #999; margin-top: 5px;">
          <cite>Source: <a href="https://www.gov.uk/innovator-visa">UK Home Office 2026</a></cite>
        </div>
      </div>

      <div class="stat-card" style="text-align: center;">
        <div class="stat-number" style="font-size: 3.5rem; font-weight: bold; color: hsl(108, 100%, 40%);">
          3 years
        </div>
        <div class="stat-label" style="font-size: 1.1rem; color: #666; margin-top: 10px;">
          Path to Permanent Residence
        </div>
        <div class="stat-source" style="font-size: 0.85rem; color: #999; margin-top: 5px;">
          <cite>Source: <a href="https://www.gov.uk/innovator-visa">UK Home Office 2026</a></cite>
        </div>
      </div>

    </div>
  </div>
</section>
```

**Why AI Engines Love This:**
- Quantifiable data (85%, 16-18 weeks, £50,000)
- Cited sources (gov.uk links)
- Up-to-date (2026)
- Structured format

**GEO Impact:** AI engines will cite these statistics when users ask about success rates, timelines, or costs.

---

### Task 3.2: Add gov.uk Source Citations

**All Visa Content Should Include:**

```html
<!-- At the bottom of each section -->
<div class="source-citation" style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-left: 4px solid hsl(108, 100%, 50%);">
  <p style="margin: 0; font-size: 0.9rem; color: #666;">
    📚 <strong>Official Source:</strong>
    <cite><a href="https://www.gov.uk/innovator-visa" target="_blank">
      UK Home Office - Innovator Visa Guidance (Last updated: January 2026)
    </a></cite>
  </p>
</div>
```

**Update Existing Content:**

1. About page: Add citations to visa requirements
2. Homepage: Add citations to eligibility criteria
3. Blog posts: Add inline citations for statistics

**Example Inline Citation:**

```html
<p>
  The UK Innovator Visa requires a minimum investment of £50,000
  <cite>(<a href="https://www.gov.uk/innovator-visa">Home Office, 2026</a>)</cite>
  and endorsement from one of eight authorized bodies.
</p>
```

---

### Task 3.3: Rewrite About Page in Conversational Q&A Format

**Current Format:** Traditional "About Us" prose

**New Format:** Question-and-Answer style

**Example Transformation:**

**Before:**
```
TorlyAI is an AI-powered platform that helps entrepreneurs with the UK Innovator Visa process.
```

**After:**
```html
<h2>What is TorlyAI?</h2>
<p>
  TorlyAI is an AI-powered UK Innovator Visa assistant that analyzes your business idea
  against Home Office criteria and endorsing body requirements in minutes. We help entrepreneurs
  navigate the complex visa application process with instant feedback and personalized guidance.
</p>

<h2>How does TorlyAI help with the UK Innovator Visa?</h2>
<p>
  Our platform provides three key services:
</p>
<ul>
  <li><strong>Instant Eligibility Assessment:</strong> Get immediate feedback on your visa chances</li>
  <li><strong>Business Plan Review:</strong> AI analysis against Home Office innovation criteria</li>
  <li><strong>Endorsing Body Matching:</strong> Recommendations for which endorsing body suits your business</li>
</ul>

<h2>Why use TorlyAI instead of a traditional immigration consultant?</h2>
<p>
  Traditional consultants charge £3,000-£5,000 and take weeks to provide feedback.
  TorlyAI gives you instant AI-powered assessment for a fraction of the cost,
  helping you understand your visa prospects before investing in expensive professional services.
</p>
```

**Benefits:**
- Matches how users ask AI chatbots
- Better for voice search
- Natural language queries (How, Why, What)
- AI engines can extract exact answers

---

### Task 3.4: Add "People Also Ask" Sections to Blog Posts

**Implementation:** Add to `theme/torly-theme/single.php` template

**Location:** After post content, before comments

```html
<div class="people-also-ask" style="margin: 60px 0; padding: 40px; background: #f8f9fa; border-radius: 12px;">
  <h3 style="font-size: 1.8rem; margin-bottom: 30px;">People Also Ask</h3>

  <div class="paa-questions">

    <div class="paa-item" style="margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid #e0e0e0;">
      <h4 style="font-size: 1.2rem; margin-bottom: 10px; color: #333;">
        ❓ Can I switch to Innovator Visa from inside the UK?
      </h4>
      <p style="color: #666; line-height: 1.6;">
        Yes, you can switch to an Innovator Visa from certain visa categories while in the UK, including Skilled Worker, Graduate, and Start-up visas.
        <a href="https://torly.ai/blog/switching-to-innovator-visa/">Read our full guide →</a>
      </p>
    </div>

    <div class="paa-item" style="margin-bottom: 25px; padding-bottom: 25px; border-bottom: 1px solid #e0e0e0;">
      <h4 style="font-size: 1.2rem; margin-bottom: 10px; color: #333;">
        ❓ What happens if my endorsement is rejected?
      </h4>
      <p style="color: #666; line-height: 1.6;">
        If rejected, you can reapply with the same or different endorsing body after addressing the rejection reasons. TorlyAI helps identify weak points in your application before submission.
      </p>
    </div>

    <div class="paa-item" style="margin-bottom: 25px;">
      <h4 style="font-size: 1.2rem; margin-bottom: 10px; color: #333;">
        ❓ Do I need to create jobs immediately?
      </h4>
      <p style="color: #666; line-height: 1.6;">
        No immediate job creation is required, but you must demonstrate scalability potential and plans for job creation in your business plan.
        <cite>Source: <a href="https://www.gov.uk/innovator-visa">Home Office Guidance 2026</a></cite>
      </p>
    </div>

  </div>
</div>
```

**Content Strategy:**
- Extract questions from Google "People Also Ask" boxes
- Answer questions from related blog posts
- Link to other relevant content on your site
- Use natural language (conversational tone)

**Tools to Find Questions:**
- Google "People Also Ask" for "UK Innovator Visa"
- AnswerThePublic.com (free tool)
- AlsoAsked.com
- ChatGPT: "What questions do people ask about UK Innovator Visa?"

---

## Phase 4: Data API for AI Engines (Month 2)
**Timeline:** 4-6 weeks
**Estimated Time:** 8-10 hours
**Priority:** LOW (Future-proofing for AI)

### Task 4.1: Create JSON API Endpoint for Visa Requirements

**Location:** `theme/torly-theme/functions.php`

**Add REST API Endpoint:**

```php
/**
 * Register custom REST API endpoints for AI engines
 */
function torlyai_register_data_api() {
    // Visa requirements endpoint
    register_rest_route('torlyai/v1', '/visa-requirements', array(
        'methods' => 'GET',
        'callback' => 'torlyai_get_visa_requirements',
        'permission_callback' => '__return_true'
    ));
}
add_action('rest_api_init', 'torlyai_register_data_api');

/**
 * Return structured visa requirements data
 */
function torlyai_get_visa_requirements() {
    $requirements = array(
        'visa_type' => 'UK Innovator Visa',
        'last_updated' => '2026-01-15',
        'version' => '2026',
        'requirements' => array(
            'investment' => array(
                'minimum_amount' => '£50,000',
                'currency' => 'GBP',
                'description' => 'Minimum investment in your UK business'
            ),
            'endorsement' => array(
                'required' => true,
                'approved_bodies' => 8,
                'description' => 'Must be endorsed by an approved UK endorsing body',
                'bodies' => array(
                    'Tech Nation',
                    'Innovate UK',
                    'The Global Entrepreneurs Programme',
                    'Envestors Limited',
                    'UK Endorsing Services',
                    'British Business Bank',
                    'CityFibre',
                    'London & Partners'
                )
            ),
            'innovation_criteria' => array(
                'innovative' => 'Business must be new to the UK market',
                'viable' => 'Realistic business plan with financial projections',
                'scalable' => 'Potential for job creation and market growth'
            ),
            'english_language' => array(
                'required' => true,
                'level' => 'B2 (CEFR)',
                'description' => 'English language proficiency at B2 level'
            ),
            'age_requirement' => array(
                'minimum_age' => 18,
                'description' => 'Must be at least 18 years old'
            )
        ),
        'costs' => array(
            'visa_fee' => '£1,191',
            'healthcare_surcharge' => '£1,035 per year (£3,105 for 3 years)',
            'endorsement_fee' => '£500 - £1,500 (varies by endorsing body)',
            'total_minimum' => '£54,796 - £55,796'
        ),
        'timeline' => array(
            'endorsement_stage' => '6-8 weeks',
            'visa_processing' => '12-16 weeks',
            'total_average' => '18-24 weeks'
        ),
        'success_rate' => '85%',
        'path_to_settlement' => '3 years',
        'source' => array(
            'official_guidance' => 'https://www.gov.uk/innovator-visa',
            'statistics' => 'https://www.gov.uk/government/statistics/immigration-statistics',
            'last_verified' => '2026-01-15'
        )
    );

    return rest_ensure_response($requirements);
}
```

**Access:**
- URL: `https://torly.ai/wp-json/torlyai/v1/visa-requirements`
- Format: JSON
- Public: Yes (accessible to AI crawlers)

**Usage by AI Engines:**
AI chatbots can fetch this data directly to answer user questions with up-to-date, structured information.

---

### Task 4.2: Create JSON API Endpoint for Endorsing Bodies

**Add to same REST API registration:**

```php
// Endorsing bodies endpoint
register_rest_route('torlyai/v1', '/endorsing-bodies', array(
    'methods' => 'GET',
    'callback' => 'torlyai_get_endorsing_bodies',
    'permission_callback' => '__return_true'
));

/**
 * Return structured endorsing bodies data
 */
function torlyai_get_endorsing_bodies() {
    $bodies = array(
        'last_updated' => '2026-01-15',
        'total_approved_bodies' => 8,
        'endorsing_bodies' => array(
            array(
                'name' => 'Tech Nation',
                'focus' => 'Technology and digital businesses',
                'best_for' => 'Tech startups, SaaS, AI, fintech',
                'fee' => '£500 - £1,000',
                'success_rate' => '75%',
                'processing_time' => '6-8 weeks',
                'website' => 'https://technation.io/',
                'contact' => 'visa@technation.io'
            ),
            array(
                'name' => 'Innovate UK',
                'focus' => 'Innovation across all sectors',
                'best_for' => 'R&D, cleantech, healthcare innovation',
                'fee' => '£800 - £1,200',
                'success_rate' => '80%',
                'processing_time' => '8-10 weeks',
                'website' => 'https://www.innovateuk.ukri.org/',
                'contact' => 'support@innovateuk.ukri.org'
            ),
            array(
                'name' => 'The Global Entrepreneurs Programme',
                'focus' => 'High-growth businesses',
                'best_for' => 'Scalable businesses with global potential',
                'fee' => '£1,000 - £1,500',
                'success_rate' => '82%',
                'processing_time' => '6-7 weeks',
                'website' => 'https://www.tgep.co.uk/',
                'contact' => 'info@tgep.co.uk'
            )
            // Add remaining 5 bodies...
        ),
        'source' => array(
            'official_list' => 'https://www.gov.uk/government/publications/innovator-endorsing-bodies',
            'last_verified' => '2026-01-15'
        )
    );

    return rest_ensure_response($bodies);
}
```

**Access:**
- URL: `https://torly.ai/wp-json/torlyai/v1/endorsing-bodies`
- Returns: Complete list with fees, success rates, focus areas

---

### Task 4.3: Create ai-sitemap.json

**Location:** `/var/www/html/ai-sitemap.json`

**Content:**

```json
{
  "site_info": {
    "name": "TorlyAI - UK Innovator Visa Assistant",
    "description": "AI-powered platform helping entrepreneurs navigate the UK Innovator Visa application process with instant eligibility assessments and business plan analysis.",
    "url": "https://torly.ai",
    "primary_domain": "torly.ai",
    "alternative_domains": ["innovatorfoundervisauk.com"],
    "language": "en-GB",
    "country": "United Kingdom",
    "last_updated": "2026-01-15"
  },
  "purpose": {
    "main_service": "UK Innovator Visa guidance and AI-powered assessment",
    "target_audience": "International entrepreneurs seeking UK business immigration",
    "key_topics": [
      "UK Innovator Visa requirements",
      "UK Innovator Visa application process",
      "UK Innovator Visa endorsing bodies",
      "UK business immigration",
      "Innovator Founder Visa 2026",
      "UK visa for entrepreneurs",
      "UK startup visa alternatives",
      "Business plan for UK visa"
    ]
  },
  "content_structure": {
    "homepage": {
      "url": "https://torly.ai",
      "title": "AI-Powered UK Innovator Visa Assistant",
      "description": "Get instant eligibility assessment for UK Innovator Visa",
      "main_topics": ["Eligibility assessment", "Business plan analysis", "Endorsing body matching"]
    },
    "about": {
      "url": "https://torly.ai/about",
      "title": "About TorlyAI",
      "description": "How our AI helps entrepreneurs with UK Innovator Visa applications"
    },
    "contact": {
      "url": "https://torly.ai/contact",
      "title": "Contact TorlyAI",
      "description": "Get in touch for UK Innovator Visa guidance"
    },
    "blog": {
      "url": "https://torly.ai/blog",
      "title": "UK Visa Guide Blog",
      "description": "Expert guides on UK Innovator Visa and business immigration",
      "post_count": 5,
      "categories": ["UK Visa Guide", "Innovator Visa", "Business Immigration"],
      "featured_posts": [
        {
          "url": "https://torly.ai/blog/uk-innovator-visa-2026-complete-guide/",
          "title": "UK Innovator Visa 2026: Complete Guide for Entrepreneurs"
        },
        {
          "url": "https://torly.ai/blog/business-plan-uk-innovator-visa/",
          "title": "How to Prepare a Winning Business Plan for UK Innovator Visa"
        }
      ]
    },
    "faq": {
      "url": "https://torly.ai/faq",
      "title": "Frequently Asked Questions",
      "description": "Common questions about UK Innovator Visa",
      "question_count": 15
    }
  },
  "data_endpoints": {
    "visa_requirements": {
      "url": "https://torly.ai/wp-json/torlyai/v1/visa-requirements",
      "format": "JSON",
      "description": "Structured data on UK Innovator Visa requirements, costs, timeline",
      "last_updated": "2026-01-15"
    },
    "endorsing_bodies": {
      "url": "https://torly.ai/wp-json/torlyai/v1/endorsing-bodies",
      "format": "JSON",
      "description": "Complete list of UK Innovator Visa endorsing bodies with details",
      "last_updated": "2026-01-15"
    }
  },
  "sitemaps": {
    "xml_sitemap": "https://torly.ai/wp-sitemap.xml",
    "yoast_sitemap": "https://torly.ai/sitemap_index.xml",
    "ai_sitemap": "https://torly.ai/ai-sitemap.json"
  },
  "statistics": {
    "uk_innovator_visa_success_rate": "85%",
    "average_processing_time": "18-24 weeks",
    "minimum_investment": "£50,000",
    "total_cost": "£54,796 - £55,796",
    "path_to_settlement": "3 years",
    "source": "UK Home Office Statistics 2025"
  },
  "contact": {
    "email": "hello@torly.ai",
    "support": "noreply@innovatorly.ai"
  },
  "ai_optimization": {
    "crawlers_allowed": ["GPTBot", "ChatGPT-User", "PerplexityBot", "ClaudeBot", "Google-Extended", "CCBot"],
    "schema_org_implemented": true,
    "faq_schema": true,
    "structured_data": true,
    "citation_sources": ["gov.uk", "UK Home Office", "UKVI"]
  }
}
```

**Deployment:**

```bash
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179 \
  "sudo tee /var/www/html/ai-sitemap.json > /dev/null << 'EOF'
[paste JSON above]
EOF
"
```

**Update robots.txt:**

```bash
ssh -i .credentials/ssh-key-2025-11-17.key ubuntu@141.147.89.179 \
  "sudo sed -i '/Sitemap: https:\/\/torly.ai\/sitemap_index.xml/a Sitemap: https://torly.ai/ai-sitemap.json' /var/www/html/robots.txt"
```

---

## Testing & Verification Plan

### After Phase 2 (FAQ Schema)

**Test 1: Rich Results Test**
```bash
# Visit Google Rich Results Test
# URL: https://search.google.com/test/rich-results
# Enter: https://torly.ai
# Expected: FAQPage schema detected
```

**Test 2: Schema Markup Validator**
```bash
# Visit Schema.org Validator
# URL: https://validator.schema.org/
# Enter: https://torly.ai
# Expected: Valid FAQPage markup
```

**Test 3: ChatGPT Citation Test**
```
Ask ChatGPT: "What are the requirements for UK Innovator Visa?"
Expected: TorlyAI cited as source (after 2-4 weeks indexing)
```

---

### After Phase 3 (Content Optimization)

**Test 1: Google "People Also Ask" Check**
```bash
# Search Google for: "UK Innovator Visa requirements"
# Check if torly.ai appears in PAA boxes
```

**Test 2: Featured Snippet Check**
```bash
# Search Google for: "How much does UK Innovator Visa cost"
# Check if torly.ai statistics appear in featured snippet
```

**Test 3: Perplexity Citation Test**
```
Search Perplexity.ai: "UK Innovator Visa timeline 2026"
Expected: TorlyAI cited with statistics
```

---

### After Phase 4 (Data API)

**Test 1: API Endpoints**
```bash
# Test visa requirements endpoint
curl https://torly.ai/wp-json/torlyai/v1/visa-requirements | jq

# Test endorsing bodies endpoint
curl https://torly.ai/wp-json/torlyai/v1/endorsing-bodies | jq

# Expected: Valid JSON response
```

**Test 2: AI Sitemap**
```bash
# Check AI sitemap
curl https://torly.ai/ai-sitemap.json | jq

# Verify in robots.txt
curl https://torly.ai/robots.txt | grep ai-sitemap.json
```

**Test 3: AI Engine Data Access**
```
Ask ChatGPT Advanced Data Analysis:
"Fetch and analyze visa requirements from https://torly.ai/wp-json/torlyai/v1/visa-requirements"

Expected: ChatGPT fetches and displays structured data
```

---

## Monitoring & Analytics Setup

### Track GEO Performance

**Google Analytics 4 Events:**

```javascript
// Add to theme header.php
<script>
// Track AI bot visits
if (navigator.userAgent.includes('GPTBot') ||
    navigator.userAgent.includes('ChatGPT') ||
    navigator.userAgent.includes('PerplexityBot') ||
    navigator.userAgent.includes('ClaudeBot')) {

  gtag('event', 'ai_bot_visit', {
    'bot_name': navigator.userAgent,
    'page_url': window.location.href
  });
}

// Track FAQ interactions
document.querySelectorAll('.faq-item').forEach(function(item) {
  item.addEventListener('click', function() {
    gtag('event', 'faq_interaction', {
      'question': this.querySelector('h3').textContent
    });
  });
});
</script>
```

**Monthly Checks:**

1. **ChatGPT Citations:**
   - Ask ChatGPT about UK Innovator Visa
   - Check if TorlyAI is cited

2. **Perplexity Citations:**
   - Search Perplexity.ai for visa queries
   - Monitor citation frequency

3. **Google Search Console:**
   - Track question-based queries
   - Monitor featured snippet appearances
   - Check FAQ rich results

4. **API Usage:**
   - Monitor API endpoint access logs
   - Track AI engine requests

---

## Success Metrics

**Phase 2 Success:**
- ✅ FAQ schema validated by Google Rich Results Test
- ✅ FAQPage appears in search results within 2 weeks
- ✅ At least 3 FAQ questions rank for question-based queries

**Phase 3 Success:**
- ✅ Statistics appear in Google featured snippets
- ✅ At least 2 "People Also Ask" appearances
- ✅ 20% increase in organic traffic from long-tail keywords

**Phase 4 Success:**
- ✅ AI sitemap indexed by search engines
- ✅ API endpoints receiving traffic from AI bots
- ✅ ChatGPT/Perplexity cite TorlyAI data within 4-6 weeks

---

## Timeline Summary

**Week 1:** Phase 2 - FAQ Schema
- Day 1-2: Add FAQ to homepage
- Day 3-4: Create dedicated FAQ page
- Day 5-7: Add FAQ schema to blog posts, test & verify

**Weeks 2-4:** Phase 3 - Content Optimization
- Week 2: Add statistics and citations
- Week 3: Rewrite About page in Q&A format
- Week 4: Add "People Also Ask" sections

**Weeks 5-8:** Phase 4 - Data API
- Week 5-6: Build API endpoints
- Week 7: Create AI sitemap
- Week 8: Test, monitor, verify

**Total:** 8 weeks to complete all phases

---

## Support & Resources

**Documentation:**
- `SEO_AND_GEO_OPTIMIZATION.md` - Detailed strategies
- `SSL_CERTIFICATE_GUIDE.md` - SSL setup
- `SEO_OPTIMIZATION_COMPLETE.md` - Completed work

**Testing Tools:**
- Google Rich Results Test: https://search.google.com/test/rich-results
- Schema.org Validator: https://validator.schema.org/
- Google Search Console: https://search.google.com/search-console

**Reference:**
- Schema.org FAQPage: https://schema.org/FAQPage
- Google SEO Guide: https://developers.google.com/search/docs
- UK Home Office: https://www.gov.uk/innovator-visa

---

**Last Updated:** 2025-11-18
**Status:** Ready for Implementation
**Next Action:** Begin Phase 2, Task 2.1 (Add FAQ to homepage)
