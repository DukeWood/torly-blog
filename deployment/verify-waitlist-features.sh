#!/bin/bash
# TorlyAI Waitlist Feature Verification
# Tests Phase 1 (Two-step flow + Patreon) and Phase 2 (Behavioral tracking)

echo "============================================"
echo "   TorlyAI Waitlist Feature Verification"
echo "============================================"
echo ""

# Test 1: Homepage loads correctly
echo "✓ TEST 1: Homepage Accessibility"
echo "─────────────────────────────────"
HOMEPAGE_STATUS=$(curl -o /dev/null -s -w "%{http_code}" https://torly.ai/)
if [ "$HOMEPAGE_STATUS" = "200" ]; then
    echo "✅ Homepage loads: HTTP $HOMEPAGE_STATUS"
else
    echo "❌ Homepage failed: HTTP $HOMEPAGE_STATUS"
fi
echo ""

# Test 2: Check for waitlist modal HTML elements
echo "✓ TEST 2: Waitlist Modal Elements"
echo "─────────────────────────────────"
HOMEPAGE_HTML=$(curl -s https://torly.ai/)

# Check for waitlist button
if echo "$HOMEPAGE_HTML" | grep -q "openWaitlistModal"; then
    echo "✅ Waitlist modal trigger found"
else
    echo "❌ Waitlist modal trigger missing"
fi

# Check for success screen
if echo "$HOMEPAGE_HTML" | grep -q "waitlistSuccess"; then
    echo "✅ Success screen HTML found"
else
    echo "❌ Success screen HTML missing"
fi

# Check for profile questions
if echo "$HOMEPAGE_HTML" | grep -q "profileCountry"; then
    echo "✅ Profile questions found"
else
    echo "❌ Profile questions missing"
fi

# Check for Patreon invitation
if echo "$HOMEPAGE_HTML" | grep -q "waitlistPatreon"; then
    echo "✅ Patreon invitation screen found"
else
    echo "❌ Patreon invitation screen missing"
fi

# Check for Patreon link
if echo "$HOMEPAGE_HTML" | grep -q "patreon.com/innovatorly"; then
    echo "✅ Patreon community link found"
else
    echo "❌ Patreon community link missing"
fi
echo ""

# Test 3: Check for behavioral tracking JavaScript
echo "✓ TEST 3: Behavioral Tracking Code"
echo "─────────────────────────────────"

if echo "$HOMEPAGE_HTML" | grep -q "behavioralTracking"; then
    echo "✅ Behavioral tracking object found"
else
    echo "❌ Behavioral tracking object missing"
fi

if echo "$HOMEPAGE_HTML" | grep -q "trackScrollDepth"; then
    echo "✅ Scroll depth tracking found"
else
    echo "❌ Scroll depth tracking missing"
fi

if echo "$HOMEPAGE_HTML" | grep -q "setupSectionTracking"; then
    echo "✅ Section tracking found"
else
    echo "❌ Section tracking missing"
fi

if echo "$HOMEPAGE_HTML" | grep -q "IntersectionObserver"; then
    echo "✅ Intersection Observer implemented"
else
    echo "❌ Intersection Observer missing"
fi

if echo "$HOMEPAGE_HTML" | grep -q "getBehavioralData"; then
    echo "✅ Behavioral data collection found"
else
    echo "❌ Behavioral data collection missing"
fi

if echo "$HOMEPAGE_HTML" | grep -q "getUTMParams"; then
    echo "✅ UTM parameter tracking found"
else
    echo "❌ UTM parameter tracking missing"
fi
echo ""

# Test 4: Check API endpoint
echo "✓ TEST 4: Waitlist API Endpoint"
echo "─────────────────────────────────"
API_STATUS=$(curl -o /dev/null -s -w "%{http_code}" -X POST https://torly.ai/wp-json/torlyai/v1/waitlist)
if [ "$API_STATUS" = "400" ] || [ "$API_STATUS" = "200" ]; then
    echo "✅ API endpoint accessible: HTTP $API_STATUS (400=validation working)"
else
    echo "❌ API endpoint failed: HTTP $API_STATUS"
fi
echo ""

# Test 5: Database schema check
echo "✓ TEST 5: Database Schema"
echo "─────────────────────────────────"
SSH_KEY="/Users/Jason-uk/AI/AI_Coding/Repositories/torly-wordpress-setup/.credentials/ssh-key-2025-11-17.key"
SERVER="ubuntu@141.147.89.179"

# Check for Phase 1 columns
SCHEMA=$(ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e 'DESCRIBE torly_wordpress.wp_waitlist;' -s" 2>/dev/null)

if echo "$SCHEMA" | grep -q "country"; then
    echo "✅ Phase 1: country column exists"
else
    echo "❌ Phase 1: country column missing"
fi

if echo "$SCHEMA" | grep -q "business_stage"; then
    echo "✅ Phase 1: business_stage column exists"
else
    echo "❌ Phase 1: business_stage column missing"
fi

if echo "$SCHEMA" | grep -q "application_timeline"; then
    echo "✅ Phase 1: application_timeline column exists"
else
    echo "❌ Phase 1: application_timeline column missing"
fi

# Check for Phase 2 columns
if echo "$SCHEMA" | grep -q "cta_source"; then
    echo "✅ Phase 2: cta_source column exists"
else
    echo "❌ Phase 2: cta_source column missing"
fi

if echo "$SCHEMA" | grep -q "time_on_page"; then
    echo "✅ Phase 2: time_on_page column exists"
else
    echo "❌ Phase 2: time_on_page column missing"
fi

if echo "$SCHEMA" | grep -q "scroll_depth"; then
    echo "✅ Phase 2: scroll_depth column exists"
else
    echo "❌ Phase 2: scroll_depth column missing"
fi

if echo "$SCHEMA" | grep -q "sections_viewed"; then
    echo "✅ Phase 2: sections_viewed column exists"
else
    echo "❌ Phase 2: sections_viewed column missing"
fi

if echo "$SCHEMA" | grep -q "page_load_to_signup"; then
    echo "✅ Phase 2: page_load_to_signup column exists"
else
    echo "❌ Phase 2: page_load_to_signup column missing"
fi

if echo "$SCHEMA" | grep -q "utm_source"; then
    echo "✅ Phase 2: utm_source column exists"
else
    echo "❌ Phase 2: utm_source column missing"
fi
echo ""

# Test 6: CTA buttons with source tracking
echo "✓ TEST 6: CTA Button Source Tracking"
echo "─────────────────────────────────"

if echo "$HOMEPAGE_HTML" | grep -q "openWaitlistModal('hero-primary')"; then
    echo "✅ Hero CTA tagged with 'hero-primary'"
else
    echo "❌ Hero CTA source tracking missing"
fi

if echo "$HOMEPAGE_HTML" | grep -q "openWaitlistModal('cta-bottom')"; then
    echo "✅ Bottom CTA tagged with 'cta-bottom'"
else
    echo "❌ Bottom CTA source tracking missing"
fi
echo ""

echo "============================================"
echo "Verification completed: $(date)"
echo "============================================"
echo ""
echo "Next steps:"
echo "1. Test waitlist signup on live site: https://torly.ai/"
echo "2. View analytics: bash deployment/analytics-report.sh"
echo "3. View signups: bash deployment/view-waitlist.sh"
