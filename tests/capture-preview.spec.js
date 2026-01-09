const { test } = require('@playwright/test');
const path = require('path');

test('capture footer preview', async ({ page }) => {
  const previewPath = path.resolve(__dirname, 'footer-preview.html');
  await page.goto(`file://${previewPath}`);

  await page.waitForTimeout(1000);

  // Capture full preview
  await page.screenshot({
    path: 'tests/screenshots/footer-preview-with-logo.png',
    fullPage: true
  });

  console.log('✅ Preview screenshot saved: tests/screenshots/footer-preview-with-logo.png');

  // Also capture just the footer section
  const footer = page.locator('.site-footer');
  await footer.screenshot({
    path: 'tests/screenshots/footer-new-design.png'
  });

  console.log('✅ Footer only screenshot saved: tests/screenshots/footer-new-design.png');
});
