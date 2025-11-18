/**
 * Mobile Menu Test for Torly AI
 * Tests hamburger menu functionality on mobile viewport
 */

const { chromium } = require('playwright');

async function testMobileMenu() {
    console.log('🧪 Starting mobile menu test...\n');

    const browser = await chromium.launch({ headless: false });
    const context = await browser.newContext({
        viewport: { width: 375, height: 667 }, // iPhone SE size
        userAgent: 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15'
    });

    const page = await context.newPage();

    try {
        // Navigate to homepage
        console.log('📱 Loading https://torly.ai/ in mobile viewport (375x667)...');
        await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });
        await page.waitForTimeout(2000);

        // Check if hamburger menu button is visible
        const menuToggle = page.locator('.menu-toggle');
        const isVisible = await menuToggle.isVisible();
        console.log(`✓ Hamburger button visible: ${isVisible}`);

        if (!isVisible) {
            console.error('❌ FAILED: Hamburger button not visible on mobile!');
            await browser.close();
            return;
        }

        // Check initial state - menu should be hidden
        const nav = page.locator('.main-navigation');
        const initiallyHidden = await nav.evaluate(el => {
            const styles = window.getComputedStyle(el);
            const right = styles.right;
            return right.includes('-') || right === 'auto';
        });
        console.log(`✓ Menu initially hidden: ${initiallyHidden}`);

        // Click hamburger to open menu
        console.log('\n🖱️  Clicking hamburger button...');
        await menuToggle.click();
        await page.waitForTimeout(500); // Wait for animation

        // Check if menu is now open
        const hasActiveClass = await nav.evaluate(el => el.classList.contains('active'));
        console.log(`✓ Menu has 'active' class: ${hasActiveClass}`);

        const isMenuOpen = await nav.evaluate(el => {
            const styles = window.getComputedStyle(el);
            return styles.right === '0px';
        });
        console.log(`✓ Menu slid to right: 0px: ${isMenuOpen}`);

        // Check if body has menu-open class
        const bodyHasMenuOpen = await page.evaluate(() => {
            return document.body.classList.contains('menu-open');
        });
        console.log(`✓ Body has 'menu-open' class: ${bodyHasMenuOpen}`);

        // Check if hamburger lines animated to X
        const hamburgerAnimated = await menuToggle.evaluate(el => {
            return el.classList.contains('active');
        });
        console.log(`✓ Hamburger animated to X: ${hamburgerAnimated}`);

        // Take screenshot of open menu
        await page.screenshot({ path: 'tests/screenshots/mobile-menu-open.png' });
        console.log('📸 Screenshot saved: tests/screenshots/mobile-menu-open.png');

        // Test closing menu by clicking outside
        console.log('\n🖱️  Clicking outside menu to close...');
        await page.click('body', { position: { x: 10, y: 100 } });
        await page.waitForTimeout(500);

        const menuClosedAfterOutsideClick = await nav.evaluate(el => {
            return !el.classList.contains('active');
        });
        console.log(`✓ Menu closed after outside click: ${menuClosedAfterOutsideClick}`);

        // Open menu again
        await menuToggle.click();
        await page.waitForTimeout(500);

        // Test closing with Escape key
        console.log('\n⌨️  Pressing Escape key...');
        await page.keyboard.press('Escape');
        await page.waitForTimeout(500);

        const menuClosedAfterEscape = await nav.evaluate(el => {
            return !el.classList.contains('active');
        });
        console.log(`✓ Menu closed after Escape: ${menuClosedAfterEscape}`);

        // Open menu and test navigation link
        await menuToggle.click();
        await page.waitForTimeout(500);

        console.log('\n🔗 Testing menu link click...');
        const blogLink = page.locator('.main-navigation a[href*="blog"]');
        const blogLinkExists = await blogLink.count() > 0;
        console.log(`✓ Blog link exists in menu: ${blogLinkExists}`);

        // Summary
        console.log('\n' + '='.repeat(50));
        console.log('📊 TEST SUMMARY');
        console.log('='.repeat(50));

        const allTestsPassed = isVisible && initiallyHidden && hasActiveClass &&
                               isMenuOpen && bodyHasMenuOpen && hamburgerAnimated &&
                               menuClosedAfterOutsideClick && menuClosedAfterEscape &&
                               blogLinkExists;

        if (allTestsPassed) {
            console.log('✅ ALL TESTS PASSED - Mobile menu is working correctly!');
        } else {
            console.log('❌ SOME TESTS FAILED - See details above');
        }

        console.log('\nClosing browser in 5 seconds...');
        await page.waitForTimeout(5000);

    } catch (error) {
        console.error('❌ Test error:', error.message);
    } finally {
        await browser.close();
        console.log('🏁 Test complete!');
    }
}

// Run test
testMobileMenu().catch(console.error);
