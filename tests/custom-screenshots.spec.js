/**
 * Custom Screenshot Test
 * Takes screenshots of different page elements and viewports
 * Saves to: screenshots/ directory
 */

const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

// Create screenshots directory if it doesn't exist
const screenshotsDir = path.join(__dirname, '../screenshots');
if (!fs.existsSync(screenshotsDir)) {
  fs.mkdirSync(screenshotsDir, { recursive: true });
}

test.describe('Custom Screenshot Capture', () => {

  test('Capture full homepage - Desktop', async ({ page }) => {
    console.log('📸 Capturing desktop homepage...');

    await page.setViewportSize({ width: 1920, height: 1080 });
    await page.goto('https://torly.ai', { waitUntil: 'networkidle' });

    // Full page screenshot (entire scrollable content)
    await page.screenshot({
      path: path.join(screenshotsDir, 'homepage-desktop-1920x1080.png'),
      fullPage: true
    });

    console.log('✅ Saved: screenshots/homepage-desktop-1920x1080.png');
  });

  test('Capture full homepage - Tablet', async ({ page }) => {
    console.log('📸 Capturing tablet homepage...');

    await page.setViewportSize({ width: 768, height: 1024 });
    await page.goto('https://torly.ai', { waitUntil: 'networkidle' });

    await page.screenshot({
      path: path.join(screenshotsDir, 'homepage-tablet-768x1024.png'),
      fullPage: true
    });

    console.log('✅ Saved: screenshots/homepage-tablet-768x1024.png');
  });

  test('Capture full homepage - Mobile', async ({ page }) => {
    console.log('📸 Capturing mobile homepage...');

    await page.setViewportSize({ width: 375, height: 812 });
    await page.goto('https://torly.ai', { waitUntil: 'networkidle' });

    await page.screenshot({
      path: path.join(screenshotsDir, 'homepage-mobile-375x812.png'),
      fullPage: true
    });

    console.log('✅ Saved: screenshots/homepage-mobile-375x812.png');
  });

  test('Capture specific page sections', async ({ page }) => {
    console.log('📸 Capturing individual sections...');

    await page.setViewportSize({ width: 1920, height: 1080 });
    await page.goto('https://torly.ai', { waitUntil: 'networkidle' });

    // Hero section
    const hero = page.locator('.hero-section, section').first();
    if (await hero.count() > 0) {
      await hero.screenshot({
        path: path.join(screenshotsDir, 'section-hero.png')
      });
      console.log('  ✓ Hero section');
    }

    // Features section
    const features = page.locator('.features-section, .feature-grid').first();
    if (await features.count() > 0) {
      await features.scrollIntoViewIfNeeded();
      await features.screenshot({
        path: path.join(screenshotsDir, 'section-features.png')
      });
      console.log('  ✓ Features section');
    }

    // CTA section
    const cta = page.locator('.cta-section').first();
    if (await cta.count() > 0) {
      await cta.scrollIntoViewIfNeeded();
      await cta.screenshot({
        path: path.join(screenshotsDir, 'section-cta.png')
      });
      console.log('  ✓ CTA section');
    }

    // Footer
    const footer = page.locator('footer');
    if (await footer.count() > 0) {
      await footer.scrollIntoViewIfNeeded();
      await footer.screenshot({
        path: path.join(screenshotsDir, 'section-footer.png')
      });
      console.log('  ✓ Footer');
    }

    console.log('✅ Saved: All sections to screenshots/');
  });

  test('Capture UI components', async ({ page }) => {
    console.log('📸 Capturing UI components...');

    await page.setViewportSize({ width: 1920, height: 1080 });
    await page.goto('https://torly.ai', { waitUntil: 'networkidle' });

    // Primary button
    const primaryBtn = page.locator('.btn-primary').first();
    if (await primaryBtn.count() > 0) {
      await primaryBtn.scrollIntoViewIfNeeded();
      await primaryBtn.screenshot({
        path: path.join(screenshotsDir, 'component-button-primary.png')
      });
      console.log('  ✓ Primary button');
    }

    // Secondary button
    const secondaryBtn = page.locator('.btn-secondary').first();
    if (await secondaryBtn.count() > 0) {
      await secondaryBtn.scrollIntoViewIfNeeded();
      await secondaryBtn.screenshot({
        path: path.join(screenshotsDir, 'component-button-secondary.png')
      });
      console.log('  ✓ Secondary button');
    }

    // Navigation header
    const header = page.locator('header, .header').first();
    if (await header.count() > 0) {
      await header.screenshot({
        path: path.join(screenshotsDir, 'component-header.png')
      });
      console.log('  ✓ Header navigation');
    }

    console.log('✅ Saved: All components to screenshots/');
  });

  test('Capture with timestamp', async ({ page }) => {
    console.log('📸 Capturing timestamped screenshot...');

    await page.goto('https://torly.ai', { waitUntil: 'networkidle' });

    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
    const filename = `homepage-${timestamp}.png`;

    await page.screenshot({
      path: path.join(screenshotsDir, filename),
      fullPage: true
    });

    console.log(`✅ Saved: screenshots/${filename}`);
  });

  test('Screenshot summary', async ({ page }) => {
    console.log('\n📋 SCREENSHOT SUMMARY');
    console.log('═══════════════════════════════════════');
    console.log('Location: screenshots/');
    console.log('');
    console.log('Files created:');

    const files = fs.readdirSync(screenshotsDir)
      .filter(f => f.endsWith('.png'))
      .map(f => {
        const stats = fs.statSync(path.join(screenshotsDir, f));
        const sizeMB = (stats.size / 1024 / 1024).toFixed(2);
        return `  ${f} (${sizeMB} MB)`;
      });

    files.forEach(f => console.log(f));
    console.log('');
    console.log(`Total: ${files.length} screenshots`);
    console.log('═══════════════════════════════════════');
  });
});
