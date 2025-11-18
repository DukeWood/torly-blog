/**
 * Playwright Test: CTA Button Column Layout Centering
 * Tests if buttons in column layout are individually centered
 */

const { chromium } = require('playwright');

async function testCTAColumnCentering() {
    console.log('🎭 Testing CTA button centering for column layout...\n');

    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        viewport: { width: 1920, height: 1080 }
    });
    const page = await context.newPage();

    try {
        console.log('📍 Navigating to https://torly.ai...');
        await page.goto('https://torly.ai', { waitUntil: 'networkidle' });
        console.log('✅ Page loaded\n');

        const ctaSection = page.locator('.cta-section');
        await ctaSection.scrollIntoViewIfNeeded();
        await page.waitForTimeout(1000);

        const ctaButtons = page.locator('.cta-section .cta-buttons');
        const containerBox = await ctaButtons.boundingBox();
        const containerCenter = containerBox.x + containerBox.width / 2;

        const primaryButton = ctaButtons.locator('.btn-primary').first();
        const secondaryButton = ctaButtons.locator('.btn-secondary').first();

        const primaryBox = await primaryButton.boundingBox();
        const secondaryBox = await secondaryButton.boundingBox();

        const primaryCenter = primaryBox.x + primaryBox.width / 2;
        const secondaryCenter = secondaryBox.x + secondaryBox.width / 2;

        const primaryOffset = Math.abs(primaryCenter - containerCenter);
        const secondaryOffset = Math.abs(secondaryCenter - containerCenter);

        // Check CSS properties
        const flexDirection = await ctaButtons.evaluate(el =>
            window.getComputedStyle(el).flexDirection
        );
        const alignItems = await ctaButtons.evaluate(el =>
            window.getComputedStyle(el).alignItems
        );

        console.log('📊 CSS Properties:');
        console.log(`  flex-direction: ${flexDirection}`);
        console.log(`  align-items: ${alignItems}\n`);

        console.log('📏 Measurements:');
        console.log(`  Container center: ${containerCenter.toFixed(2)}px\n`);

        console.log(`  Primary button:`);
        console.log(`    Center: ${primaryCenter.toFixed(2)}px`);
        console.log(`    Offset from container center: ${primaryOffset.toFixed(2)}px`);
        console.log(`    Status: ${primaryOffset < 1 ? '✅ CENTERED' : '❌ OFF-CENTER'}\n`);

        console.log(`  Secondary button:`);
        console.log(`    Center: ${secondaryCenter.toFixed(2)}px`);
        console.log(`    Offset from container center: ${secondaryOffset.toFixed(2)}px`);
        console.log(`    Status: ${secondaryOffset < 1 ? '✅ CENTERED' : '❌ OFF-CENTER'}\n`);

        // Take screenshots
        await ctaSection.screenshot({ path: 'cta-column-centering.png' });
        console.log('📸 Screenshot saved: cta-column-centering.png\n');

        // Final verdict
        const tolerance = 1; // 1px tolerance for rounding
        const primaryCentered = primaryOffset < tolerance;
        const secondaryCentered = secondaryOffset < tolerance;
        const allCentered = primaryCentered && secondaryCentered;
        const hasAlignItems = alignItems === 'center';

        console.log('═══════════════════════════════════════════');
        console.log('FINAL VERDICT:');
        console.log('═══════════════════════════════════════════');
        console.log(`CSS align-items: ${alignItems} ${hasAlignItems ? '✅' : '❌'}`);
        console.log(`Primary button centered: ${primaryCentered ? '✅ YES' : '❌ NO'}`);
        console.log(`Secondary button centered: ${secondaryCentered ? '✅ YES' : '❌ NO'}`);
        console.log(`\n${allCentered && hasAlignItems ? '🎉 TEST PASSED' : '⚠️  TEST FAILED'}`);
        console.log('═══════════════════════════════════════════\n');

        await browser.close();
        return allCentered && hasAlignItems;

    } catch (error) {
        console.error('❌ Test failed:', error.message);
        await browser.close();
        return false;
    }
}

testCTAColumnCentering()
    .then(success => process.exit(success ? 0 : 1))
    .catch(error => {
        console.error('💥 Test execution failed:', error);
        process.exit(1);
    });
