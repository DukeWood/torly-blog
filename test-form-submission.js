/**
 * Test: Verify Form Submission Success Message
 * Tests that the form shows success message after submission
 */

const { chromium } = require('playwright');

(async () => {
    console.log('🚀 Testing form submission success message...\n');

    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();
    await page.setViewportSize({ width: 1280, height: 720 });

    try {
        // Navigate to homepage
        console.log('📍 Loading https://torly.ai/');
        await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

        // Click Join Waitlist button
        console.log('🖱️  Clicking Join Waitlist button...');
        const button = page.locator('button.btn-primary').first();
        await button.click();
        await page.waitForTimeout(500);

        // Fill in email
        const testEmail = `test${Date.now()}@example.com`;
        console.log(`✍️  Entering email: ${testEmail}`);
        const emailInput = page.locator('#waitlist-email');
        await emailInput.fill(testEmail);

        // Submit form
        console.log('📤 Submitting form...');
        const submitButton = page.locator('.btn-submit').first();
        await submitButton.click();

        // Wait for response
        await page.waitForTimeout(3000);

        // Check for success message
        const step1 = page.locator('#waitlist-step-1');
        const step2 = page.locator('#waitlist-step-2');
        const errorMessage = page.locator('.waitlist-error');

        const step1Visible = await step1.isVisible();
        const step2Visible = await step2.isVisible();
        const errorVisible = await errorMessage.isVisible().catch(() => false);

        console.log('\n📊 RESULTS:');
        console.log(`   Step 1 (form) visible: ${step1Visible ? '✓ YES' : '✗ NO'}`);
        console.log(`   Step 2 (success) visible: ${step2Visible ? '✓ YES' : '✗ NO'}`);
        console.log(`   Error message visible: ${errorVisible ? '✗ YES (BAD)' : '✓ NO (GOOD)'}`);

        if (step2Visible && !errorVisible) {
            console.log('\n✅ SUCCESS! Form submission works correctly!');

            // Get success message text
            const successTitle = await page.locator('#waitlist-step-2 h2').textContent();
            console.log(`   Success message: "${successTitle}"`);
        } else if (errorVisible) {
            const errorText = await errorMessage.textContent();
            console.log(`\n❌ ERROR! Error message shown: "${errorText}"`);
        } else {
            console.log('\n⚠️  UNEXPECTED STATE! Neither success nor error shown.');
        }

        // Take screenshot
        await page.screenshot({ path: 'form-submission-test.png', fullPage: false });
        console.log('\n📸 Screenshot saved: form-submission-test.png');

    } catch (error) {
        console.error('\n❌ ERROR:', error.message);
        await page.screenshot({ path: 'form-submission-error.png', fullPage: true });
        console.log('Error screenshot saved: form-submission-error.png');
    } finally {
        await browser.close();
    }
})();
