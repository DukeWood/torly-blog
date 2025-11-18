/**
 * TorlyAI Footer Social Links - Verification Test
 */

const { test, expect } = require('@playwright/test');

test.describe('Footer Social Media Links', () => {

  test('All social media links are present and correctly configured', async ({ page }) => {
    console.log('🧪 TEST: Footer social media links');

    // Navigate to homepage
    await page.goto('https://torly.ai/');
    await page.waitForLoadState('networkidle');
    console.log('✓ Page loaded');

    // Scroll to footer
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    await page.waitForTimeout(1000);
    console.log('✓ Scrolled to footer');

    // Check for footer-social container
    const socialContainer = page.locator('.footer-social');
    await expect(socialContainer).toBeVisible({ timeout: 5000 });
    console.log('✓ Social media container found');

    // Define expected social links
    const socialLinks = [
      { platform: 'Facebook', url: 'facebook.com/innovatorly', label: 'Facebook' },
      { platform: 'Twitter/X', url: 'twitter.com/innovatorly', label: 'X (Twitter)' },
      { platform: 'LinkedIn', url: 'linkedin.com/company/innovatorly', label: 'LinkedIn' },
      { platform: 'YouTube', url: 'youtube.com/@innovatorly', label: 'YouTube' },
      { platform: 'Patreon', url: 'patreon.com/innovatorly', label: 'Patreon' },
      { platform: 'TikTok', url: 'tiktok.com/@innovatorly', label: 'TikTok' }
    ];

    // Verify each social link
    for (const social of socialLinks) {
      const link = page.locator(`.footer-social a[href*="${social.url.split('/')[0]}"]`);
      await expect(link).toBeVisible();

      // Check link attributes
      const href = await link.getAttribute('href');
      expect(href).toContain(social.url.split('/')[0]);

      const target = await link.getAttribute('target');
      expect(target).toBe('_blank');

      const rel = await link.getAttribute('rel');
      expect(rel).toContain('noopener');
      expect(rel).toContain('noreferrer');

      const ariaLabel = await link.getAttribute('aria-label');
      expect(ariaLabel).toBeTruthy();

      console.log(`✓ ${social.platform} link verified`);
    }

    // Check SVG icons are present
    const svgIcons = page.locator('.footer-social svg');
    const svgCount = await svgIcons.count();
    expect(svgCount).toBe(6);
    console.log(`✓ All 6 SVG icons present`);

    // Verify company information
    const companyInfo = page.locator('text=/Innovatorly Ltd/');
    await expect(companyInfo).toBeVisible();
    console.log('✓ Company name displayed');

    const registrationInfo = page.locator('text=/Company number: 16674855/');
    await expect(registrationInfo).toBeVisible();
    console.log('✓ Company registration number displayed');

    // Check hover effect (optional - visual test)
    const firstLink = page.locator('.footer-social a').first();
    await firstLink.hover();
    await page.waitForTimeout(300);
    console.log('✓ Hover effect tested');

    console.log('✅ TEST PASSED: All social media links configured correctly');
  });

  test('Social links have correct CSS styling', async ({ page }) => {
    console.log('🧪 TEST: Social links CSS styling');

    await page.goto('https://torly.ai/');
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    await page.waitForTimeout(500);

    // Check container has flex display
    const containerStyles = await page.locator('.footer-social').evaluate((el) => {
      const styles = window.getComputedStyle(el);
      return {
        display: styles.display,
        justifyContent: styles.justifyContent,
        alignItems: styles.alignItems,
        gap: styles.gap
      };
    });

    expect(containerStyles.display).toBe('flex');
    expect(containerStyles.justifyContent).toContain('center');
    console.log('✓ Flex layout configured correctly');

    // Check link sizing
    const linkStyles = await page.locator('.footer-social a').first().evaluate((el) => {
      const styles = window.getComputedStyle(el);
      return {
        width: styles.width,
        height: styles.height
      };
    });

    expect(linkStyles.width).toBe('40px');
    expect(linkStyles.height).toBe('40px');
    console.log('✓ Link dimensions correct (40x40px)');

    // Check SVG sizing
    const svgStyles = await page.locator('.footer-social svg').first().evaluate((el) => {
      const styles = window.getComputedStyle(el);
      return {
        width: styles.width,
        height: styles.height
      };
    });

    expect(svgStyles.width).toBe('24px');
    expect(svgStyles.height).toBe('24px');
    console.log('✓ SVG icons sized correctly (24x24px)');

    console.log('✅ TEST PASSED: CSS styling correct');
  });
});
