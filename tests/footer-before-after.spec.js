const { test, expect } = require('@playwright/test');

test.describe('Footer Before/After Comparison', () => {
  test('capture current footer (BEFORE changes)', async ({ page }) => {
    await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

    // Scroll to footer
    await page.evaluate(() => {
      const footer = document.querySelector('.site-footer');
      if (footer) footer.scrollIntoView({ behavior: 'smooth' });
    });

    await page.waitForTimeout(1000);

    // Take screenshot of current footer
    const footer = page.locator('.site-footer');
    await footer.screenshot({ path: 'tests/screenshots/footer-before.png' });

    console.log('✅ Screenshot saved: tests/screenshots/footer-before.png');

    // Analyze current footer structure
    const footerHTML = await footer.innerHTML();

    console.log('\n=== CURRENT FOOTER STRUCTURE ===');
    console.log('Has logo section:', footerHTML.includes('footer-logo'));
    console.log('Has footer-content:', footerHTML.includes('footer-content'));
    console.log('Number of columns:', await page.locator('.footer-column').count());
    console.log('Has social icons:', footerHTML.includes('footer-social'));

    // Check if logo exists
    const logoExists = await page.locator('.footer-logo img').count();
    console.log('Logo present:', logoExists > 0 ? 'YES' : 'NO');

    if (logoExists === 0) {
      console.log('\n❌ NO LOGO in current footer');
      console.log('Footer starts directly with 4-column grid');
    }
  });
});
