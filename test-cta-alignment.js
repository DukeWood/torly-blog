/**
 * Playwright Test: CTA Button Alignment Verification
 * Tests if the CTA section buttons are centered as expected
 */

const { chromium } = require('playwright');

async function testCTAAlignment() {
    console.log('🎭 Starting Playwright MCP test for CTA button alignment...\n');

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        viewport: { width: 1920, height: 1080 }
    });
    const page = await context.newPage();

    try {
        // Navigate to torly.ai
        console.log('📍 Navigating to https://torly.ai...');
        await page.goto('https://torly.ai', { waitUntil: 'networkidle' });
        console.log('✅ Page loaded successfully\n');

        // Find the CTA section
        console.log('🔍 Locating CTA section...');
        const ctaSection = page.locator('.cta-section');
        await ctaSection.waitFor({ state: 'visible' });
        console.log('✅ CTA section found\n');

        // Scroll to CTA section
        await ctaSection.scrollIntoViewIfNeeded();
        await page.waitForTimeout(1000); // Wait for animations

        // Get the .cta-buttons container (specifically in .cta-section)
        const ctaButtons = page.locator('.cta-section .cta-buttons');

        // Check computed CSS
        console.log('📊 Analyzing CSS properties...');
        const justifyContent = await ctaButtons.evaluate(el => {
            return window.getComputedStyle(el).justifyContent;
        });
        const display = await ctaButtons.evaluate(el => {
            return window.getComputedStyle(el).display;
        });
        const flexDirection = await ctaButtons.evaluate(el => {
            return window.getComputedStyle(el).flexDirection;
        });

        console.log(`  Display: ${display}`);
        console.log(`  Flex Direction: ${flexDirection}`);
        console.log(`  Justify Content: ${justifyContent}`);
        console.log('');

        // Get container and button positions
        const containerBox = await ctaButtons.boundingBox();
        const primaryButton = ctaButtons.locator('.btn-primary').first();
        const secondaryButton = ctaButtons.locator('.btn-secondary').first();

        const primaryBox = await primaryButton.boundingBox();
        const secondaryBox = await secondaryButton.boundingBox();

        console.log('📏 Measuring button positions...');
        console.log(`  Container width: ${containerBox.width}px`);
        console.log(`  Container x: ${containerBox.x}px`);
        console.log(`  Container center: ${containerBox.x + containerBox.width / 2}px\n`);

        console.log(`  Primary button x: ${primaryBox.x}px`);
        console.log(`  Primary button width: ${primaryBox.width}px`);
        console.log(`  Primary button center: ${primaryBox.x + primaryBox.width / 2}px\n`);

        console.log(`  Secondary button x: ${secondaryBox.x}px`);
        console.log(`  Secondary button width: ${secondaryBox.width}px`);
        console.log(`  Secondary button center: ${secondaryBox.x + secondaryBox.width / 2}px\n`);

        // Calculate alignment
        const containerCenter = containerBox.x + containerBox.width / 2;
        const buttonsGroupWidth = (primaryBox.x - containerBox.x) * 2 + primaryBox.width + secondaryBox.width;
        const buttonsGroupCenter = containerBox.x + buttonsGroupWidth / 2;

        const centerOffset = Math.abs(buttonsGroupCenter - containerCenter);
        const isCentered = centerOffset < 50; // Allow 50px tolerance

        console.log('✨ Alignment Analysis:');
        console.log(`  Center offset: ${centerOffset.toFixed(2)}px`);
        console.log(`  Is centered: ${isCentered ? '✅ YES' : '❌ NO'}`);
        console.log(`  Tolerance: 50px\n`);

        // Take screenshot of CTA section
        console.log('📸 Taking screenshot...');
        await ctaSection.screenshot({
            path: 'cta-section-screenshot.png',
            animations: 'disabled'
        });
        console.log('✅ Screenshot saved: cta-section-screenshot.png\n');

        // Full page screenshot for context
        await page.screenshot({
            path: 'homepage-full-screenshot.png',
            fullPage: true
        });
        console.log('✅ Full page screenshot saved: homepage-full-screenshot.png\n');

        // Final verdict
        console.log('═══════════════════════════════════════════');
        console.log('FINAL VERDICT:');
        console.log('═══════════════════════════════════════════');

        if (justifyContent === 'center') {
            console.log('✅ CSS Property: justify-content is "center" (CORRECT)');
        } else {
            console.log(`❌ CSS Property: justify-content is "${justifyContent}" (EXPECTED: center)`);
        }

        if (isCentered) {
            console.log('✅ Visual Alignment: Buttons are centered (CORRECT)');
        } else {
            console.log('❌ Visual Alignment: Buttons are NOT centered');
            console.log(`   Offset from center: ${centerOffset.toFixed(2)}px`);
        }

        console.log('═══════════════════════════════════════════\n');

        // Return results
        return {
            success: justifyContent === 'center' && isCentered,
            cssJustifyContent: justifyContent,
            visuallyCentered: isCentered,
            centerOffset: centerOffset,
            screenshots: [
                'cta-section-screenshot.png',
                'homepage-full-screenshot.png'
            ]
        };

    } catch (error) {
        console.error('❌ Test failed:', error.message);
        throw error;
    } finally {
        await browser.close();
    }
}

// Run the test
testCTAAlignment()
    .then(result => {
        if (result.success) {
            console.log('🎉 TEST PASSED: CTA buttons are properly centered!');
            process.exit(0);
        } else {
            console.log('⚠️  TEST FAILED: CTA buttons alignment issue detected');
            process.exit(1);
        }
    })
    .catch(error => {
        console.error('💥 Test execution failed:', error);
        process.exit(1);
    });
