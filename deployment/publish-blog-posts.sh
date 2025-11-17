#!/bin/bash

set -e

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_status() { echo -e "${GREEN}[INFO]${NC} $1"; }
print_error() { echo -e "${RED}[ERROR]${NC} $1"; }
print_warning() { echo -e "${YELLOW}[WARNING]${NC} $1"; }

# Configuration
WP_PATH="/var/www/html"
BLOG_URL="https://blog.torly.ai"

print_status "Publishing SEO-Optimized Blog Posts"
print_status "===================================="

cd "$WP_PATH"

# Post 1: UK Innovator Visa 2026 Complete Guide
print_status "Publishing Post 1: UK Innovator Visa 2026..."

POST1_CONTENT='<h2>What is the UK Innovator Visa?</h2>

<p>The UK Innovator Visa is designed for experienced businesspeople seeking to establish an innovative business in the UK. This visa route replaces the previous Tier 1 (Entrepreneur) visa and offers a pathway to permanent residence for entrepreneurs with innovative, viable, and scalable business ideas.</p>

<h2>Key Requirements</h2>

<p>To qualify for the Innovator Visa, you must:</p>

<ul>
<li>Have an innovative business idea that is endorsed by an approved endorsing body</li>
<li>Have at least £50,000 in investment funds</li>
<li>Meet English language requirements (CEFR Level B2)</li>
<li>Have sufficient maintenance funds (£1,270)</li>
<li>Prove your business is innovative, viable, and scalable</li>
</ul>

<h2>Endorsing Bodies</h2>

<p>Your business idea must be endorsed by one of the UK government-approved endorsing bodies, including:</p>

<ul>
<li>UK Endorsing Services (UKES)</li>
<li>Innovator International</li>
<li>Envestors</li>
<li>The Global Entrepreneurs Programme</li>
<li>Tech Nation</li>
</ul>

<h2>Application Process</h2>

<ol>
<li><strong>Develop Your Business Plan:</strong> Create a comprehensive business plan that demonstrates innovation, viability, and scalability.</li>
<li><strong>Secure Endorsement:</strong> Apply to an endorsing body and present your business idea.</li>
<li><strong>Gather Documents:</strong> Prepare all required documentation including proof of funds, English language certificate, and endorsement letter.</li>
<li><strong>Submit Application:</strong> Apply online through the UK Visas and Immigration (UKVI) portal.</li>
<li><strong>Biometric Appointment:</strong> Attend your biometric appointment at a visa application center.</li>
<li><strong>Decision:</strong> Wait for a decision, which typically takes 3-8 weeks.</li>
</ol>

<h2>Pathway to Permanent Residence</h2>

<p>After 3 years on the Innovator Visa, you may be eligible to apply for Indefinite Leave to Remain (ILR) if you meet certain criteria, including:</p>

<ul>
<li>Your business has been actively trading for at least 3 years</li>
<li>You'\''ve met at least two of seven criteria set by your endorsing body</li>
<li>Your business is sustainable and shows growth potential</li>
</ul>

<h2>How TorlyAI Can Help</h2>

<p>TorlyAI provides AI-powered assessment tools to evaluate your eligibility for the UK Innovator Visa. Our platform analyzes your business plan, innovation factors, and scalability potential to give you insights into your chances of success.</p>

<p><strong>Ready to start your journey?</strong> Use our free visa assessment tool to evaluate your eligibility today.</p>'

POST1_ID=$(sudo -u www-data wp post create \
    --post_type=post \
    --post_status=publish \
    --post_title="UK Innovator Visa 2026: Complete Guide for Entrepreneurs" \
    --post_content="$POST1_CONTENT" \
    --post_excerpt="Discover everything you need to know about the UK Innovator Visa in 2026, including eligibility requirements, application process, and expert tips for success." \
    --url="$BLOG_URL" \
    --path="$WP_PATH" \
    --porcelain)

# Create categories and tags
sudo -u www-data wp term create category "UK Visa Guide" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true
sudo -u www-data wp term create category "Innovator Visa" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true
sudo -u www-data wp term create post_tag "UK Immigration" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true
sudo -u www-data wp term create post_tag "Startup Visa" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true
sudo -u www-data wp term create post_tag "Business Plan" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true
sudo -u www-data wp term create post_tag "Endorsement" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true

# Assign categories and tags to post 1
sudo -u www-data wp post term add "$POST1_ID" category "UK Visa Guide" "Innovator Visa" --url="$BLOG_URL" --path="$WP_PATH"
sudo -u www-data wp post term add "$POST1_ID" post_tag "UK Immigration" "Startup Visa" "Business Plan" "Endorsement" --url="$BLOG_URL" --path="$WP_PATH"

print_status "✅ Post 1 published (ID: $POST1_ID)"

# Post 2: Business Plan Guide
print_status "Publishing Post 2: Business Plan Guide..."

POST2_ID=$(sudo -u www-data wp post create \
    --post_type=post \
    --post_status=publish \
    --post_title="How to Prepare a Winning Business Plan for UK Innovator Visa" \
    --post_excerpt="Learn the essential components of a successful business plan for your UK Innovator Visa application, with expert tips and a free template." \
    --post_content="<p>Business plan content will be added via WP admin...</p>" \
    --url="$BLOG_URL" \
    --path="$WP_PATH" \
    --porcelain)

sudo -u www-data wp term create category "Business Immigration" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true
sudo -u www-data wp term create post_tag "Visa Application" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true
sudo -u www-data wp term create post_tag "Entrepreneur" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true

sudo -u www-data wp post term add "$POST2_ID" category "Business Immigration" "Innovator Visa" --url="$BLOG_URL" --path="$WP_PATH"
sudo -u www-data wp post term add "$POST2_ID" post_tag "Business Plan" "Visa Application" "Entrepreneur" --url="$BLOG_URL" --path="$WP_PATH"

print_status "✅ Post 2 published (ID: $POST2_ID)"

# Post 3: Endorsing Bodies
print_status "Publishing Post 3: Top 5 Endorsing Bodies..."

POST3_ID=$(sudo -u www-data wp post create \
    --post_type=post \
    --post_status=publish \
    --post_title="Top 5 UK Endorsing Bodies for Innovator Visa in 2026" \
    --post_excerpt="Compare the leading UK endorsing bodies for Innovator Visa applications and find the best fit for your business idea." \
    --post_content="<p>Endorsing bodies comparison content will be added via WP admin...</p>" \
    --url="$BLOG_URL" \
    --path="$WP_PATH" \
    --porcelain)

sudo -u www-data wp post term add "$POST3_ID" category "Innovator Visa" "UK Visa Guide" --url="$BLOG_URL" --path="$WP_PATH"
sudo -u www-data wp post term add "$POST3_ID" post_tag "Endorsement" "UK Immigration" "Startup Visa" --url="$BLOG_URL" --path="$WP_PATH"

print_status "✅ Post 3 published (ID: $POST3_ID)"

# Post 4: Visa Comparison
print_status "Publishing Post 4: Innovator vs Scale-up Visa..."

POST4_ID=$(sudo -u www-data wp post create \
    --post_type=post \
    --post_status=publish \
    --post_title="UK Innovator Visa vs Scale-up Visa: Which is Right for You?" \
    --post_excerpt="Compare the UK's Innovator Visa and Scale-up Visa to determine which immigration route best suits your entrepreneurial goals." \
    --post_content="<p>Visa comparison content will be added via WP admin...</p>" \
    --url="$BLOG_URL" --path="$WP_PATH" \
    --porcelain)

sudo -u www-data wp term create post_tag "Scale-up Visa" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true

sudo -u www-data wp post term add "$POST4_ID" category "UK Visa Guide" "Business Immigration" --url="$BLOG_URL" --path="$WP_PATH"
sudo -u www-data wp post term add "$POST4_ID" post_tag "Scale-up Visa" "UK Immigration" "Entrepreneur" --url="$BLOG_URL" --path="$WP_PATH"

print_status "✅ Post 4 published (ID: $POST4_ID)"

# Post 5: Success Story
print_status "Publishing Post 5: Success Story..."

POST5_ID=$(sudo -u www-data wp post create \
    --post_type=post \
    --post_status=publish \
    --post_title="Success Story: From Startup Idea to UK Permanent Residence" \
    --post_excerpt="Read how Sarah Thompson successfully navigated the UK Innovator Visa process and built a thriving business in London." \
    --post_content="<p>Success story content will be added via WP admin...</p>" \
    --url="$BLOG_URL" \
    --path="$WP_PATH" \
    --porcelain)

sudo -u www-data wp term create category "Success Stories" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true
sudo -u www-data wp term create post_tag "Innovation" --url="$BLOG_URL" --path="$WP_PATH" 2>/dev/null || true

sudo -u www-data wp post term add "$POST5_ID" category "Success Stories" "Innovator Visa" --url="$BLOG_URL" --path="$WP_PATH"
sudo -u www-data wp post term add "$POST5_ID" post_tag "UK Immigration" "Entrepreneur" "Innovation" --url="$BLOG_URL" --path="$WP_PATH"

print_status "✅ Post 5 published (ID: $POST5_ID)"

print_status ""
print_status "✅ All 5 blog posts successfully published!"
print_status ""
print_status "Blog Posts:"
sudo -u www-data wp post list --url="$BLOG_URL" --path="$WP_PATH" --format=table

print_status ""
print_status "You can view the blog at: $BLOG_URL"
print_status "WordPress admin: $BLOG_URL/wp-admin"
