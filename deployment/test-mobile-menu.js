#!/usr/bin/env node
/**
 * Test Mobile Hamburger Menu
 */

const { chromium, devices } = require('playwright');

(async () => {
  console.log('🔍 Testing Mobile Hamburger Menu...\n');

  const browser = await chromium.launch({ headless: true });
  const iPhone = devices['iPhone 12'];
  const context = await browser.newContext({
    ...iPhone,
  });
  const page = await context.newPage();

  try {
    // Navigate to homepage on mobile
    console.log('📱 Step 1: Loading site on mobile (iPhone 12)...');
    await page.goto('https://torly.ai/', { waitUntil: 'domcontentloaded', timeout: 15000 });
    await page.waitForTimeout(2000); // Wait for scripts to load
    console.log('   ✓ Page loaded\n');

    // Check if hamburger button is visible
    console.log('📱 Step 2: Checking hamburger menu button...');
    const menuToggle = page.locator('.menu-toggle');
    const isVisible = await menuToggle.isVisible();
    console.log(`   ${isVisible ? '✓' : '✗'} Hamburger button visible: ${isVisible}`);

    if (!isVisible) {
      console.log('   ⚠️  PROBLEM: Hamburger button is not visible!');
    }

    // Check if navigation is hidden initially
    const nav = page.locator('.main-navigation');
    const navVisible = await nav.isVisible();
    const navHasActive = await nav.evaluate(el => el.classList.contains('active'));
    console.log(`   Navigation visible: ${navVisible}`);
    console.log(`   Navigation has "active" class: ${navHasActive}\n`);

    // Try to click the hamburger menu
    console.log('📱 Step 3: Clicking hamburger menu...');
    await menuToggle.click();
    await page.waitForTimeout(500); // Wait for animation

    // Check if menu opened
    const navActiveAfterClick = await nav.evaluate(el => el.classList.contains('active'));
    const menuToggleActive = await menuToggle.evaluate(el => el.classList.contains('active'));

    console.log(`   Navigation "active" after click: ${navActiveAfterClick}`);
    console.log(`   Menu toggle "active" after click: ${menuToggleActive}`);
    console.log(`   ${navActiveAfterClick ? '✓' : '✗'} Menu ${navActiveAfterClick ? 'OPENED' : 'DID NOT OPEN'}\n`);

    // Check if menu items are clickable
    if (navActiveAfterClick) {
      console.log('📱 Step 4: Checking menu items...');
      const blogLink = nav.locator('a[href*="blog"]');
      const blogLinkVisible = await blogLink.isVisible();
      console.log(`   ✓ Blog link visible: ${blogLinkVisible}`);

      // Try clicking blog link
      console.log('   → Clicking blog link...');
      await blogLink.click();
      await page.waitForLoadState('networkidle');

      const finalUrl = page.url();
      console.log(`   ✓ Final URL: ${finalUrl}\n`);
    }

    // Summary
    console.log('📊 TEST RESULTS:');
    console.log('================');
    console.log(`Hamburger button visible: ${isVisible ? '✅ PASS' : '❌ FAIL'}`);
    console.log(`Menu opens on click: ${navActiveAfterClick ? '✅ PASS' : '❌ FAIL'}`);
    console.log(`Toggle button animates: ${menuToggleActive ? '✅ PASS' : '❌ FAIL'}`);

    if (!isVisible || !navActiveAfterClick || !menuToggleActive) {
      console.log('\n⚠️  MOBILE MENU HAS ISSUES!');

      // Take screenshot for debugging
      await page.screenshot({ path: '/tmp/mobile-menu-issue.png', fullPage: true });
      console.log('📸 Screenshot saved to: /tmp/mobile-menu-issue.png');
    } else {
      console.log('\n✅ MOBILE MENU WORKING CORRECTLY!');
    }

  } catch (error) {
    console.error('❌ Test failed:', error.message);
    console.error(error.stack);
  } finally {
    await browser.close();
  }
})();
