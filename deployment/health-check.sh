#!/bin/bash
#
# WordPress Health Check Script
# Monitors critical WordPress configuration and auto-fixes issues
#
# Usage: ./health-check.sh
# Add to cron: 0 */6 * * * /path/to/health-check.sh >> /var/log/wordpress-health.log 2>&1
#

echo "==================================="
echo "WordPress Health Check"
echo "Time: $(date)"
echo "==================================="

WP_PATH="/var/www/html"
CORRECT_URL="https://torly.ai"
ERRORS=0

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to check and fix URLs
check_urls() {
    echo ""
    echo "1. Checking WordPress URLs..."

    SITEURL=$(sudo -u www-data wp option get siteurl --path=$WP_PATH 2>/dev/null | grep -v "Warning" | tail -1)
    HOME=$(sudo -u www-data wp option get home --path=$WP_PATH 2>/dev/null | grep -v "Warning" | tail -1)

    echo "   siteurl: $SITEURL"
    echo "   home: $HOME"

    if [ "$SITEURL" != "$CORRECT_URL" ] || [ "$HOME" != "$CORRECT_URL" ]; then
        echo -e "${RED}   ✗ FAIL: URLs are incorrect or corrupted${NC}"
        echo "   Attempting auto-fix..."

        sudo mysql -e "UPDATE torly_wordpress.wp_options SET option_value = '$CORRECT_URL' WHERE option_name IN ('siteurl', 'home');"

        if [ $? -eq 0 ]; then
            echo -e "${GREEN}   ✓ URLs fixed successfully${NC}"
            sudo -u www-data wp rewrite flush --path=$WP_PATH >/dev/null 2>&1
        else
            echo -e "${RED}   ✗ Failed to fix URLs${NC}"
            ERRORS=$((ERRORS + 1))
        fi
    else
        echo -e "${GREEN}   ✓ PASS: URLs are correct${NC}"
    fi
}

# Function to check blog page
check_blog_page() {
    echo ""
    echo "2. Checking blog page configuration..."

    PAGE_FOR_POSTS=$(sudo -u www-data wp option get page_for_posts --path=$WP_PATH 2>/dev/null | grep -v "Warning" | tail -1)

    if [ -z "$PAGE_FOR_POSTS" ] || [ "$PAGE_FOR_POSTS" = "0" ]; then
        echo -e "${RED}   ✗ FAIL: Blog page not configured${NC}"
        ERRORS=$((ERRORS + 1))
    else
        echo -e "${GREEN}   ✓ PASS: Blog page configured (ID: $PAGE_FOR_POSTS)${NC}"
    fi
}

# Function to check blog accessibility
check_blog_accessibility() {
    echo ""
    echo "3. Checking blog page accessibility..."

    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://torly.ai/blog/)

    if [ "$HTTP_CODE" = "200" ]; then
        echo -e "${GREEN}   ✓ PASS: Blog page accessible (HTTP $HTTP_CODE)${NC}"
    else
        echo -e "${RED}   ✗ FAIL: Blog page returned HTTP $HTTP_CODE${NC}"
        ERRORS=$((ERRORS + 1))
    fi
}

# Function to check rewrite rules
check_rewrite_rules() {
    echo ""
    echo "4. Checking rewrite rules..."

    REWRITE_RULES=$(sudo -u www-data wp rewrite list --path=$WP_PATH 2>/dev/null | wc -l)

    if [ "$REWRITE_RULES" -gt 10 ]; then
        echo -e "${GREEN}   ✓ PASS: Rewrite rules active ($REWRITE_RULES rules)${NC}"
    else
        echo -e "${YELLOW}   ⚠ WARNING: Few rewrite rules detected${NC}"
        echo "   Flushing rewrite rules..."
        sudo -u www-data wp rewrite flush --path=$WP_PATH >/dev/null 2>&1
    fi
}

# Function to check database connectivity
check_database() {
    echo ""
    echo "5. Checking database connectivity..."

    DB_CHECK=$(sudo mysql -e "SELECT COUNT(*) FROM torly_wordpress.wp_options;" 2>/dev/null | tail -1)

    if [ ! -z "$DB_CHECK" ] && [ "$DB_CHECK" -gt 0 ]; then
        echo -e "${GREEN}   ✓ PASS: Database accessible ($DB_CHECK options)${NC}"
    else
        echo -e "${RED}   ✗ FAIL: Database connection issue${NC}"
        ERRORS=$((ERRORS + 1))
    fi
}

# Function to check SSL certificate
check_ssl() {
    echo ""
    echo "6. Checking SSL certificate..."

    SSL_EXPIRY=$(echo | openssl s_client -servername torly.ai -connect torly.ai:443 2>/dev/null | openssl x509 -noout -enddate 2>/dev/null | cut -d= -f2)

    if [ ! -z "$SSL_EXPIRY" ]; then
        echo -e "${GREEN}   ✓ PASS: SSL certificate valid (expires: $SSL_EXPIRY)${NC}"
    else
        echo -e "${YELLOW}   ⚠ WARNING: Could not verify SSL certificate${NC}"
    fi
}

# Run all checks
check_urls
check_blog_page
check_blog_accessibility
check_rewrite_rules
check_database
check_ssl

# Summary
echo ""
echo "==================================="
if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}Health Check PASSED ✓${NC}"
    echo "All systems operational"
else
    echo -e "${RED}Health Check FAILED ✗${NC}"
    echo "Errors detected: $ERRORS"
    echo "Review output above for details"
fi
echo "==================================="
echo ""

exit $ERRORS
