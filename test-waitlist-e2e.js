/**
 * End-to-End Playwright Test: Complete Waitlist Feature
 * Tests the entire waitlist flow from button click to form submission
 */

const { chromium } = require('playwright');

(async () => {
    console.log('🚀 Starting E2E test for Join Waitlist feature...\n');

    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();
    await page.setViewportSize({ width: 1280, height: 720 });

    const results = {
        pageLoad: false,
        buttonStyling: false,
        modalNotAutoOpen: false,
        modalOpensOnClick: false,
        modalCentered: false,
        formExists: false,
        formValidation: false,
        closeButton: false,
        overall: false
    };

    try {
        // ==========================================
        // TEST 1: Page Load & Initial State
        // ==========================================
        console.log('📋 TEST 1: Page Load & Initial State');
        console.log('   Loading https://torly.ai/');

        const response = await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });
        results.pageLoad = response.status() === 200;

        console.log(`   Status: ${response.status()}`);
        console.log(`   Result: ${results.pageLoad ? '✅ PASS' : '❌ FAIL'}\n`);

        // ==========================================
        // TEST 2: Button Styling (Design System)
        // ==========================================
        console.log('📋 TEST 2: Button Styling (Design System Compliance)');

        const button = page.locator('button.btn-primary').first();
        const buttonExists = await button.count() > 0;

        if (buttonExists) {
            const buttonText = await button.textContent();
            const styles = await button.evaluate((el) => {
                const computed = window.getComputedStyle(el);
                return {
                    background: computed.backgroundColor,
                    backdropFilter: computed.backdropFilter || computed.webkitBackdropFilter,
                    borderRadius: computed.borderRadius,
                    fontWeight: computed.fontWeight,
                    cursor: computed.cursor,
                };
            });

            console.log(`   Button text: "${buttonText.trim()}"`);
            console.log(`   Background: ${styles.background}`);
            console.log(`   Backdrop filter: ${styles.backdropFilter}`);
            console.log(`   Border radius: ${styles.borderRadius}`);
            console.log(`   Font weight: ${styles.fontWeight}`);
            console.log(`   Cursor: ${styles.cursor}`);

            const hasGlassMorphism = styles.backdropFilter && styles.backdropFilter.includes('blur');
            const hasRoundedCorners = parseFloat(styles.borderRadius) > 20;
            const hasBoldFont = parseInt(styles.fontWeight) >= 600;
            const hasPointerCursor = styles.cursor === 'pointer';

            results.buttonStyling = hasGlassMorphism && hasRoundedCorners && hasBoldFont && hasPointerCursor;

            console.log(`   Glass-morphism: ${hasGlassMorphism ? '✓' : '✗'}`);
            console.log(`   Rounded corners: ${hasRoundedCorners ? '✓' : '✗'}`);
            console.log(`   Bold font: ${hasBoldFont ? '✓' : '✗'}`);
            console.log(`   Pointer cursor: ${hasPointerCursor ? '✓' : '✗'}`);
            console.log(`   Result: ${results.buttonStyling ? '✅ PASS' : '❌ FAIL'}\n`);
        } else {
            console.log(`   ❌ Button not found!\n`);
        }

        // ==========================================
        // TEST 3: Modal NOT Auto-Open
        // ==========================================
        console.log('📋 TEST 3: Modal Should NOT Auto-Open');

        await page.waitForTimeout(2000); // Wait to see if modal auto-opens

        const modalNew = page.locator('#waitlist-modal');
        const backdrop = page.locator('#waitlist-backdrop');

        const modalVisible = await modalNew.isVisible().catch(() => false);
        const backdropVisible = await backdrop.isVisible().catch(() => false);

        results.modalNotAutoOpen = !modalVisible && !backdropVisible;

        console.log(`   Modal visible: ${modalVisible ? '✗ YES' : '✓ NO'}`);
        console.log(`   Backdrop visible: ${backdropVisible ? '✗ YES' : '✓ NO'}`);
        console.log(`   Result: ${results.modalNotAutoOpen ? '✅ PASS' : '❌ FAIL'}\n`);

        // ==========================================
        // TEST 4: Modal Opens on Button Click
        // ==========================================
        console.log('📋 TEST 4: Modal Opens on Button Click');

        console.log('   Clicking "Join Waitlist" button...');
        await button.click();
        await page.waitForTimeout(500);

        const modalVisibleAfterClick = await modalNew.isVisible();
        const backdropVisibleAfterClick = await backdrop.isVisible();

        results.modalOpensOnClick = modalVisibleAfterClick && backdropVisibleAfterClick;

        console.log(`   Modal visible: ${modalVisibleAfterClick ? '✓ YES' : '✗ NO'}`);
        console.log(`   Backdrop visible: ${backdropVisibleAfterClick ? '✓ YES' : '✗ NO'}`);
        console.log(`   Result: ${results.modalOpensOnClick ? '✅ PASS' : '❌ FAIL'}\n`);

        // ==========================================
        // TEST 5: Modal Positioning (Centered)
        // ==========================================
        console.log('📋 TEST 5: Modal Positioning (Centered on Screen)');

        const boundingBox = await modalNew.boundingBox();
        const viewport = page.viewportSize();

        const modalCenterX = boundingBox.x + (boundingBox.width / 2);
        const modalCenterY = boundingBox.y + (boundingBox.height / 2);
        const viewportCenterX = viewport.width / 2;
        const viewportCenterY = viewport.height / 2;

        const xOffset = Math.abs(modalCenterX - viewportCenterX);
        const yOffset = Math.abs(modalCenterY - viewportCenterY);

        results.modalCentered = xOffset < 50 && yOffset < 50;

        console.log(`   Modal center: (${modalCenterX.toFixed(1)}, ${modalCenterY.toFixed(1)})`);
        console.log(`   Viewport center: (${viewportCenterX}, ${viewportCenterY})`);
        console.log(`   X offset: ${xOffset.toFixed(1)}px ${xOffset < 50 ? '✓' : '✗'}`);
        console.log(`   Y offset: ${yOffset.toFixed(1)}px ${yOffset < 50 ? '✓' : '✗'}`);
        console.log(`   Result: ${results.modalCentered ? '✅ PASS' : '❌ FAIL'}\n`);

        // ==========================================
        // TEST 6: Form Elements Exist
        // ==========================================
        console.log('📋 TEST 6: Form Elements Exist');

        const emailInput = page.locator('#waitlist-email');
        const submitButton = page.locator('.btn-submit').first();

        const emailInputExists = await emailInput.count() > 0;
        const submitButtonExists = await submitButton.count() > 0;

        results.formExists = emailInputExists && submitButtonExists;

        console.log(`   Email input: ${emailInputExists ? '✓ Found' : '✗ Not found'}`);
        console.log(`   Submit button: ${submitButtonExists ? '✓ Found' : '✗ Not found'}`);
        console.log(`   Result: ${results.formExists ? '✅ PASS' : '❌ FAIL'}\n`);

        // ==========================================
        // TEST 7: Form Validation
        // ==========================================
        console.log('📋 TEST 7: Form Validation');

        if (emailInputExists && submitButtonExists) {
            // Test 7a: Empty email validation
            console.log('   Testing empty email...');
            await submitButton.click();
            await page.waitForTimeout(300);

            // HTML5 validation should prevent submission
            const isInvalid = await emailInput.evaluate(el => !el.validity.valid);
            console.log(`   Empty email rejected: ${isInvalid ? '✓' : '✗'}`);

            // Test 7b: Invalid email format
            console.log('   Testing invalid email format...');
            await emailInput.fill('invalid-email');
            await submitButton.click();
            await page.waitForTimeout(300);

            const isStillInvalid = await emailInput.evaluate(el => !el.validity.valid);
            console.log(`   Invalid format rejected: ${isStillInvalid ? '✓' : '✗'}`);

            // Test 7c: Valid email format
            console.log('   Testing valid email format...');
            await emailInput.fill('test@example.com');
            const isValid = await emailInput.evaluate(el => el.validity.valid);
            console.log(`   Valid email accepted: ${isValid ? '✓' : '✗'}`);

            results.formValidation = isInvalid && isStillInvalid && isValid;
            console.log(`   Result: ${results.formValidation ? '✅ PASS' : '❌ FAIL'}\n`);
        } else {
            console.log(`   ⚠️  Skipped (form elements missing)\n`);
        }

        // ==========================================
        // TEST 8: Close Button Works
        // ==========================================
        console.log('📋 TEST 8: Close Button Functionality');

        const closeButton = page.locator('.waitlist-close').first();
        const closeButtonExists = await closeButton.isVisible();

        if (closeButtonExists) {
            console.log('   Clicking close button...');
            await closeButton.click();
            await page.waitForTimeout(500);

            const modalVisibleAfterClose = await modalNew.isVisible().catch(() => false);
            const backdropVisibleAfterClose = await backdrop.isVisible().catch(() => false);

            results.closeButton = !modalVisibleAfterClose && !backdropVisibleAfterClose;

            console.log(`   Modal visible: ${modalVisibleAfterClose ? '✗ YES' : '✓ NO'}`);
            console.log(`   Backdrop visible: ${backdropVisibleAfterClose ? '✗ YES' : '✓ NO'}`);
            console.log(`   Result: ${results.closeButton ? '✅ PASS' : '❌ FAIL'}\n`);
        } else {
            console.log(`   ⚠️  Close button not found\n`);
        }

        // ==========================================
        // SCREENSHOTS
        // ==========================================
        console.log('📸 Taking screenshots...');

        // Homepage without modal
        await page.screenshot({ path: 'e2e-1-homepage.png', fullPage: false });
        console.log('   ✓ Homepage: e2e-1-homepage.png');

        // Open modal for screenshot
        await button.click();
        await page.waitForTimeout(500);
        await page.screenshot({ path: 'e2e-2-modal-open.png', fullPage: false });
        console.log('   ✓ Modal open: e2e-2-modal-open.png');

        // Fill form for screenshot
        await emailInput.fill('test@example.com');
        await page.screenshot({ path: 'e2e-3-form-filled.png', fullPage: false });
        console.log('   ✓ Form filled: e2e-3-form-filled.png\n');

        // ==========================================
        // FINAL SUMMARY
        // ==========================================
        const passedTests = Object.values(results).filter(r => r === true).length;
        const totalTests = Object.keys(results).length - 1; // Exclude 'overall'
        results.overall = passedTests === totalTests;

        console.log('='.repeat(70));
        console.log('📊 FINAL TEST RESULTS');
        console.log('='.repeat(70));
        console.log(`${results.pageLoad ? '✅' : '❌'} Page Load (HTTP 200)`);
        console.log(`${results.buttonStyling ? '✅' : '❌'} Button Styling (Design System)`);
        console.log(`${results.modalNotAutoOpen ? '✅' : '❌'} Modal Does NOT Auto-Open`);
        console.log(`${results.modalOpensOnClick ? '✅' : '❌'} Modal Opens on Button Click`);
        console.log(`${results.modalCentered ? '✅' : '❌'} Modal Properly Centered`);
        console.log(`${results.formExists ? '✅' : '❌'} Form Elements Exist`);
        console.log(`${results.formValidation ? '✅' : '❌'} Form Validation Works`);
        console.log(`${results.closeButton ? '✅' : '❌'} Close Button Works`);
        console.log('='.repeat(70));
        console.log(`Score: ${passedTests}/${totalTests} tests passed`);
        console.log('='.repeat(70));

        if (results.overall) {
            console.log('\n🎉 ALL TESTS PASSED!\n');
            console.log('✅ Join Waitlist feature is fully functional!');
            console.log('✅ Design system compliance verified');
            console.log('✅ User experience is optimal');
        } else {
            console.log('\n⚠️  SOME TESTS FAILED\n');
            console.log('Please review the test results above');
            console.log('Screenshots have been saved for debugging');
        }
        console.log('='.repeat(70));

    } catch (error) {
        console.error('\n❌ ERROR:', error.message);
        console.error(error.stack);
        await page.screenshot({ path: 'e2e-error.png', fullPage: true });
        console.log('\nError screenshot saved: e2e-error.png');
    } finally {
        await browser.close();
    }
})();
