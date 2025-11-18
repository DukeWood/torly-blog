#!/bin/bash
# TorlyAI Waitlist Analytics Report
# Shows behavioral tracking insights from Phase 2

SSH_KEY="/Users/Jason-uk/AI/AI_Coding/Repositories/torly-wordpress-setup/.credentials/ssh-key-2025-11-17.key"
SERVER="ubuntu@141.147.89.179"

echo "============================================"
echo "   TorlyAI Waitlist Analytics Report"
echo "============================================"
echo ""

# 1. Overall Statistics
echo "📊 OVERALL STATISTICS"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    COUNT(*) as total_signups,
    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today,
    COUNT(CASE WHEN DATE(created_at) >= CURDATE() - INTERVAL 7 DAY THEN 1 END) as this_week,
    AVG(time_on_page) as avg_time_on_page_sec,
    AVG(scroll_depth) as avg_scroll_depth_pct,
    AVG(page_load_to_signup) as avg_signup_time_sec
  FROM torly_wordpress.wp_waitlist
  WHERE time_on_page IS NOT NULL;\" -t"
echo ""

# 2. CTA Button Performance
echo "🎯 CTA BUTTON PERFORMANCE"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    cta_source as button,
    COUNT(*) as signups,
    ROUND(AVG(scroll_depth), 0) as avg_scroll_pct,
    ROUND(AVG(time_on_page), 0) as avg_time_sec
  FROM torly_wordpress.wp_waitlist
  WHERE cta_source IS NOT NULL
  GROUP BY cta_source
  ORDER BY signups DESC;\" -t"
echo ""

# 3. Device Breakdown
echo "📱 DEVICE BREAKDOWN"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    device_type,
    COUNT(*) as signups,
    ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 1) as percentage,
    ROUND(AVG(scroll_depth), 0) as avg_scroll
  FROM torly_wordpress.wp_waitlist
  WHERE device_type IS NOT NULL
  GROUP BY device_type
  ORDER BY signups DESC;\" -t"
echo ""

# 4. Engagement Levels
echo "⚡ ENGAGEMENT LEVELS"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    CASE
      WHEN scroll_depth >= 80 THEN 'High (80-100%)'
      WHEN scroll_depth >= 50 THEN 'Medium (50-79%)'
      WHEN scroll_depth >= 20 THEN 'Low (20-49%)'
      ELSE 'Very Low (0-19%)'
    END as engagement_level,
    COUNT(*) as signups,
    ROUND(AVG(time_on_page), 0) as avg_time_sec
  FROM torly_wordpress.wp_waitlist
  WHERE scroll_depth IS NOT NULL
  GROUP BY engagement_level
  ORDER BY
    CASE engagement_level
      WHEN 'High (80-100%)' THEN 1
      WHEN 'Medium (50-79%)' THEN 2
      WHEN 'Low (20-49%)' THEN 3
      WHEN 'Very Low (0-19%)' THEN 4
    END;\" -t"
echo ""

# 5. Top Sections Viewed
echo "👀 MOST VIEWED SECTIONS"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    sections_viewed,
    COUNT(*) as signups
  FROM torly_wordpress.wp_waitlist
  WHERE sections_viewed IS NOT NULL AND sections_viewed != ''
  GROUP BY sections_viewed
  ORDER BY signups DESC
  LIMIT 10;\" -t"
echo ""

# 6. Traffic Sources (UTM)
echo "🚀 TRAFFIC SOURCES"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    COALESCE(utm_source, 'Direct') as source,
    COALESCE(utm_medium, 'None') as medium,
    COUNT(*) as signups
  FROM torly_wordpress.wp_waitlist
  GROUP BY utm_source, utm_medium
  ORDER BY signups DESC
  LIMIT 10;\" -t"
echo ""

# 7. Country Distribution
echo "🌍 TOP COUNTRIES"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    COALESCE(country, 'Not specified') as country,
    COUNT(*) as signups,
    ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 1) as percentage
  FROM torly_wordpress.wp_waitlist
  GROUP BY country
  ORDER BY signups DESC
  LIMIT 10;\" -t"
echo ""

# 8. Business Stage Distribution
echo "🏢 BUSINESS STAGE"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    CASE business_stage
      WHEN 'idea' THEN 'Just an idea'
      WHEN 'mvp' THEN 'Building MVP'
      WHEN 'revenue' THEN 'Have revenue'
      WHEN 'scale' THEN 'Ready to scale'
      ELSE 'Not specified'
    END as stage,
    COUNT(*) as signups
  FROM torly_wordpress.wp_waitlist
  GROUP BY business_stage
  ORDER BY signups DESC;\" -t"
echo ""

# 9. Application Timeline
echo "⏰ APPLICATION TIMELINE"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    CASE application_timeline
      WHEN '0-3months' THEN 'Next 3 months'
      WHEN '3-6months' THEN '3-6 months'
      WHEN '6-12months' THEN '6-12 months'
      WHEN 'researching' THEN 'Just researching'
      ELSE 'Not specified'
    END as timeline,
    COUNT(*) as signups
  FROM torly_wordpress.wp_waitlist
  GROUP BY application_timeline
  ORDER BY signups DESC;\" -t"
echo ""

# 10. Quick Conversions (Fast signups)
echo "⚡ CONVERSION SPEED"
echo "──────────────────────"
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$SERVER" \
  "sudo mysql -e \"
  SELECT
    CASE
      WHEN page_load_to_signup < 30 THEN 'Very Fast (<30s)'
      WHEN page_load_to_signup < 60 THEN 'Fast (30-60s)'
      WHEN page_load_to_signup < 120 THEN 'Medium (1-2min)'
      WHEN page_load_to_signup < 300 THEN 'Slow (2-5min)'
      ELSE 'Very Slow (>5min)'
    END as speed,
    COUNT(*) as signups,
    ROUND(AVG(scroll_depth), 0) as avg_scroll
  FROM torly_wordpress.wp_waitlist
  WHERE page_load_to_signup IS NOT NULL
  GROUP BY speed
  ORDER BY
    CASE speed
      WHEN 'Very Fast (<30s)' THEN 1
      WHEN 'Fast (30-60s)' THEN 2
      WHEN 'Medium (1-2min)' THEN 3
      WHEN 'Slow (2-5min)' THEN 4
      WHEN 'Very Slow (>5min)' THEN 5
    END;\" -t"
echo ""

echo "============================================"
echo "Report generated: $(date)"
echo "============================================"
