/**
 * Demo Visual Test - Slow motion to watch browser
 */

const { test, expect } = require('@playwright/test');

test.describe('Visual Demo Test (Slow Motion)', () => {
  test('Navigate torly.ai homepage slowly', async ({ page }) => {
    console.log('🌐 Opening browser...');

    // Navigate to homepage
    await page.goto('https://torly.ai', { waitUntil: 'networkidle' });
    console.log('✓ Page loaded: https://torly.ai');

    // Wait 2 seconds
    await page.waitForTimeout(2000);

    // Check page title
    const title = await page.title();
    console.log('✓ Page title:', title);
    expect(title).toContain('Torly');

    // Wait 2 seconds
    await page.waitForTimeout(2000);

    // Find and highlight the main CTA button
    const mainCTA = page.locator('.cta-button, .btn-primary').first();
    await mainCTA.scrollIntoViewIfNeeded();

    // Highlight it by adding a red border
    await mainCTA.evaluate(el => {
      el.style.border = '5px solid red';
      el.style.boxShadow = '0 0 20px red';
    });
    console.log('✓ Main CTA button highlighted');

    // Wait 3 seconds so you can see it
    await page.waitForTimeout(3000);

    // Scroll to footer
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    console.log('✓ Scrolled to footer');

    // Wait 3 seconds
    await page.waitForTimeout(3000);

    // Highlight footer
    const footer = page.locator('footer');
    await footer.evaluate(el => {
      el.style.border = '5px solid blue';
    });
    console.log('✓ Footer highlighted');

    // Wait 3 seconds before closing
    await page.waitForTimeout(3000);

    console.log('✅ Demo complete!');
  });
});
