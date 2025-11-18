/**
 * Playwright Test: Join Waitlist Button Styling & Functionality
 * Tests the button appearance and modal popup
 */

const { chromium } = require('playwright');

(async () => {
    console.log('🚀 Starting Playwright test for Join Waitlist button...\n');

    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();

    try {
        // Navigate to the homepage
        console.log('📍 Navigating to https://torly.ai/');
        await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

        // Test 1: Check if button exists
        console.log('\n✅ Test 1: Button Existence');
        const button = await page.locator('button.btn-primary').first();
        const buttonExists = await button.count() > 0;
        console.log(`   Button found: ${buttonExists ? '✓ Yes' : '✗ No'}`);

        if (!buttonExists) {
            throw new Error('Button not found!');
        }

        // Test 2: Check button text
        console.log('\n✅ Test 2: Button Text');
        const buttonText = await button.textContent();
        console.log(`   Text: "${buttonText.trim()}"`);
        console.log(`   Correct: ${buttonText.includes('Join Waitlist') ? '✓ Yes' : '✗ No'}`);

        // Test 3: Check button styling (Design System compliance)
        console.log('\n✅ Test 3: Button Styling (Design System Compliance)');
        const styles = await button.evaluate((el) => {
            const computed = window.getComputedStyle(el);
            return {
                background: computed.backgroundColor,
                backdropFilter: computed.backdropFilter || computed.webkitBackdropFilter,
                borderRadius: computed.borderRadius,
                fontWeight: computed.fontWeight,
                fontSize: computed.fontSize,
                display: computed.display,
                cursor: computed.cursor,
                padding: computed.padding,
                height: computed.height
            };
        });

        console.log('   Styling Properties:');
        console.log(`   - Background: ${styles.background}`);
        console.log(`   - Backdrop Filter: ${styles.backdropFilter}`);
        console.log(`   - Border Radius: ${styles.borderRadius}`);
        console.log(`   - Font Weight: ${styles.fontWeight}`);
        console.log(`   - Font Size: ${styles.fontSize}`);
        console.log(`   - Display: ${styles.display}`);
        console.log(`   - Cursor: ${styles.cursor}`);
        console.log(`   - Height: ${styles.height}`);

        // Validate design system compliance
        const hasGlassMorphism = styles.backdropFilter && styles.backdropFilter.includes('blur');
        const hasRoundedCorners = parseFloat(styles.borderRadius) > 20;
        const hasBoldFont = parseInt(styles.fontWeight) >= 600;
        const hasPointerCursor = styles.cursor === 'pointer';

        console.log('\n   Design System Compliance:');
        console.log(`   - Glass-morphism (backdrop-filter): ${hasGlassMorphism ? '✓' : '✗'}`);
        console.log(`   - Fully rounded (border-radius > 20px): ${hasRoundedCorners ? '✓' : '✗'}`);
        console.log(`   - Bold font (weight >= 600): ${hasBoldFont ? '✓' : '✗'}`);
        console.log(`   - Pointer cursor: ${hasPointerCursor ? '✓' : '✗'}`);

        // Test 4: Check button visibility
        console.log('\n✅ Test 4: Button Visibility');
        const isVisible = await button.isVisible();
        console.log(`   Visible: ${isVisible ? '✓ Yes' : '✗ No'}`);

        // Test 5: Check if button is enabled
        console.log('\n✅ Test 5: Button Enabled State');
        const isEnabled = await button.isEnabled();
        console.log(`   Enabled: ${isEnabled ? '✓ Yes' : '✗ No'}`);

        // Test 6: Click button and check modal
        console.log('\n✅ Test 6: Modal Functionality');
        console.log('   Clicking button...');
        await button.click();

        // Wait a moment for modal to appear
        await page.waitForTimeout(500);

        // Check for modal with either ID (old or new)
        const modalOld = page.locator('#waitlistModal');
        const modalNew = page.locator('#waitlist-modal');
        const backdropNew = page.locator('#waitlist-backdrop');

        const modalOldExists = await modalOld.count() > 0;
        const modalNewExists = await modalNew.count() > 0;
        const backdropExists = await backdropNew.count() > 0;

        console.log(`   Modal (old ID: waitlistModal): ${modalOldExists ? 'Found' : 'Not found'}`);
        console.log(`   Modal (new ID: waitlist-modal): ${modalNewExists ? 'Found' : 'Not found'}`);
        console.log(`   Backdrop (waitlist-backdrop): ${backdropExists ? 'Found' : 'Not found'}`);

        // Check if any modal is visible
        let modalVisible = false;
        let modalType = '';

        if (modalOldExists) {
            modalVisible = await modalOld.isVisible();
            modalType = 'old';
        }
        if (modalNewExists) {
            const newVisible = await modalNew.isVisible();
            if (newVisible) {
                modalVisible = true;
                modalType = 'new';
            }
        }

        console.log(`   Modal visible: ${modalVisible ? `✓ Yes (${modalType})` : '✗ No'}`);

        // Take screenshot
        console.log('\n📸 Taking screenshot...');
        await page.screenshot({ path: 'waitlist-button-test.png', fullPage: true });
        console.log('   Screenshot saved: waitlist-button-test.png');

        // Summary
        console.log('\n' + '='.repeat(60));
        console.log('📊 TEST SUMMARY');
        console.log('='.repeat(60));
        console.log(`✓ Button exists: ${buttonExists}`);
        console.log(`✓ Button text correct: ${buttonText.includes('Join Waitlist')}`);
        console.log(`✓ Glass-morphism effect: ${hasGlassMorphism}`);
        console.log(`✓ Fully rounded corners: ${hasRoundedCorners}`);
        console.log(`✓ Bold font weight: ${hasBoldFont}`);
        console.log(`✓ Pointer cursor: ${hasPointerCursor}`);
        console.log(`✓ Button visible: ${isVisible}`);
        console.log(`✓ Button enabled: ${isEnabled}`);
        console.log(`${modalVisible ? '✓' : '✗'} Modal opens on click: ${modalVisible}`);

        const allTestsPassed = buttonExists && buttonText.includes('Join Waitlist') &&
                               hasGlassMorphism && hasRoundedCorners && hasBoldFont &&
                               hasPointerCursor && isVisible && isEnabled && modalVisible;

        console.log('\n' + '='.repeat(60));
        if (allTestsPassed) {
            console.log('🎉 ALL TESTS PASSED!');
        } else {
            console.log('⚠️  SOME TESTS FAILED - See details above');
        }
        console.log('='.repeat(60));

    } catch (error) {
        console.error('\n❌ ERROR:', error.message);
        await page.screenshot({ path: 'waitlist-button-error.png', fullPage: true });
        console.log('Error screenshot saved: waitlist-button-error.png');
    } finally {
        await browser.close();
    }
})();
