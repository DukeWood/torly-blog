/**
 * Verify Button Improvements
 */

const { test, expect } = require('@playwright/test');

test.describe('Button Width & Contrast Improvements', () => {
  test('hero buttons should be compact, not full-width', async ({ page }) => {
    await page.goto('https://torly.ai/');
    await page.setViewportSize({ width: 1920, height: 1080 });

    const heroSection = page.locator('.hero-section');
    await heroSection.screenshot({
      path: 'playwright-report/hero-buttons-after.png'
    });

    // Check Join Waitlist button in hero
    const heroJoinButton = page.locator('.hero-buttons .btn-primary').first();
    const buttonBox = await heroJoinButton.boundingBox();

    // Button should be compact, not full-width (container is ~900px)
    // Button should be around 150-250px wide for "Join Waitlist" text
    expect(buttonBox.width).toBeLessThan(300);
    expect(buttonBox.width).toBeGreaterThan(120);

    console.log(`Hero "Join Waitlist" button width: ${buttonBox.width}px`);
  });

  test('CTA buttons should remain compact', async ({ page }) => {
    await page.goto('https://torly.ai/');

    const ctaSection = page.locator('.cta-section');
    await ctaSection.scrollIntoViewIfNeeded();

    await ctaSection.screenshot({
      path: 'playwright-report/cta-buttons-after.png'
    });

    // Check CTA Join Waitlist button
    const ctaJoinButton = page.locator('.cta-buttons .btn-primary').first();
    const buttonBox = await ctaJoinButton.boundingBox();

    expect(buttonBox.width).toBeLessThan(300);

    console.log(`CTA "Join Waitlist" button width: ${buttonBox.width}px`);
  });

  test('primary button should have good contrast', async ({ page }) => {
    await page.goto('https://torly.ai/');

    const primaryButton = page.locator('.btn-primary').first();

    const styles = await primaryButton.evaluate((el) => {
      const computed = window.getComputedStyle(el);
      return {
        background: computed.background,
        boxShadow: computed.boxShadow,
        border: computed.border
      };
    });

    // Should have rgba(255, 255, 255, 0.85) background
    expect(styles.background).toContain('rgba(255, 255, 255, 0.85)');

    // Should have shadow for contrast
    expect(styles.boxShadow).toContain('rgba(0, 0, 0');

    console.log('Primary button background:', styles.background.substring(0, 100));
    console.log('Primary button shadow:', styles.boxShadow);
  });

  test('buttons are consistent across sections', async ({ page }) => {
    await page.goto('https://torly.ai/');

    // Get all primary buttons
    const allPrimaryButtons = await page.locator('.btn-primary').all();

    const widths = [];

    for (const button of allPrimaryButtons.slice(0, 3)) {
      const box = await button.boundingBox();
      if (box) {
        widths.push(box.width);
      }
    }

    console.log('Button widths across sections:', widths);

    // All buttons should be reasonably compact (not full-width)
    for (const width of widths) {
      expect(width).toBeLessThan(350);
    }
  });

  test('button hover effect is smooth', async ({ page }) => {
    await page.goto('https://torly.ai/');

    const button = page.locator('.btn-primary').first();

    // Get initial state
    const initialStyles = await button.evaluate((el) => {
      return {
        transform: window.getComputedStyle(el).transform,
        background: window.getComputedStyle(el).background
      };
    });

    // Hover
    await button.hover();
    await page.waitForTimeout(200);

    // Get hover state
    const hoverStyles = await button.evaluate((el) => {
      return {
        transform: window.getComputedStyle(el).transform,
        background: window.getComputedStyle(el).background
      };
    });

    // Should have transform on hover
    expect(hoverStyles.transform).not.toBe(initialStyles.transform);

    // Background should be more opaque on hover (0.95)
    expect(hoverStyles.background).toContain('0.95');

    console.log('Hover transform:', hoverStyles.transform);
  });

  test('comprehensive button improvements summary', async ({ page }) => {
    await page.goto('https://torly.ai/');

    const checks = {
      heroButtonCompact: false,
      ctaButtonCompact: false,
      goodContrast: false,
      hoverEffect: false
    };

    // Hero button width
    const heroButton = page.locator('.hero-buttons .btn-primary').first();
    const heroBox = await heroButton.boundingBox();
    checks.heroButtonCompact = heroBox.width < 300;

    // CTA button width
    const ctaSection = page.locator('.cta-section');
    await ctaSection.scrollIntoViewIfNeeded();
    const ctaButton = page.locator('.cta-buttons .btn-primary').first();
    const ctaBox = await ctaButton.boundingBox();
    checks.ctaButtonCompact = ctaBox.width < 300;

    // Contrast check
    const bg = await heroButton.evaluate((el) => {
      return window.getComputedStyle(el).background;
    });
    checks.goodContrast = bg.includes('0.85');

    // Hover effect
    await heroButton.hover();
    await page.waitForTimeout(200);
    const hoverTransform = await heroButton.evaluate((el) => {
      return window.getComputedStyle(el).transform;
    });
    checks.hoverEffect = hoverTransform !== 'none';

    console.log('\n📋 BUTTON IMPROVEMENTS SUMMARY');
    console.log('═══════════════════════════════════════');
    console.log(`  Hero Button Compact     ${checks.heroButtonCompact ? '✅ PASS' : '❌ FAIL'} (${heroBox.width}px)`);
    console.log(`  CTA Button Compact      ${checks.ctaButtonCompact ? '✅ PASS' : '❌ FAIL'} (${ctaBox.width}px)`);
    console.log(`  Good Contrast (0.85)    ${checks.goodContrast ? '✅ PASS' : '❌ FAIL'}`);
    console.log(`  Hover Effect Working    ${checks.hoverEffect ? '✅ PASS' : '❌ FAIL'}`);
    console.log('═══════════════════════════════════════');

    const allPassed = Object.values(checks).every(v => v === true);
    expect(allPassed).toBe(true);
  });
});
