/**
 * Test: Execute JavaScript to Check State
 */

const { chromium } = require('playwright');

(async () => {
    const browser = await chromium.launch({ headless: false }); // Show browser
    const page = await browser.newPage();

    await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

    // Check if torlyaiWaitlist is defined
    const torlyaiDefined = await page.evaluate(() => {
        return typeof torlyaiWaitlist !== 'undefined';
    });

    console.log(`torlyaiWaitlist defined: ${torlyaiDefined}`);

    if (torlyaiDefined) {
        const torlyaiData = await page.evaluate(() => {
            return {
                ajaxUrl: torlyaiWaitlist.ajaxUrl,
                hasNonce: !!torlyaiWaitlist.nonce
            };
        });
        console.log(`AJAX URL: ${torlyaiData.ajaxUrl}`);
        console.log(`Has nonce: ${torlyaiData.hasNonce}`);
    }

    // Open modal
    await page.locator('button.btn-primary').first().click();
    await page.waitForTimeout(500);

    // Fill and submit
    const email = `test${Date.now()}@example.com`;
    console.log(`\nTesting with email: ${email}`);

    await page.locator('#waitlist-email').first().fill(email);

    console.log('Submitting form (watch the browser)...');
    await page.locator('.btn-submit').first().click();

    // Wait and check steps
    await page.waitForTimeout(3000);

    const steps = await page.evaluate(() => {
        const step1 = document.getElementById('waitlist-step-1');
        const step2 = document.getElementById('waitlist-step-2');
        return {
            step1Visible: step1 && step1.classList.contains('active'),
            step2Visible: step2 && step2.classList.contains('active'),
            step1Display: step1 ? window.getComputedStyle(step1).display : 'not found',
            step2Display: step2 ? window.getComputedStyle(step2).display : 'not found'
        };
    });

    console.log('\n=== STEP STATE ===');
    console.log(`Step 1 active: ${steps.step1Visible}`);
    console.log(`Step 2 active: ${steps.step2Visible}`);
    console.log(`Step 1 display: ${steps.step1Display}`);
    console.log(`Step 2 display: ${steps.step2Display}`);

    console.log('\nPress Ctrl+C to close browser...');
    await page.waitForTimeout(30000); // Wait 30 seconds

    await browser.close();
})();
