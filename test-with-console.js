/**
 * Test: Form Submission with Console Logging
 */

const { chromium } = require('playwright');

(async () => {
    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();

    // Capture console logs
    const consoleLogs = [];
    page.on('console', msg => {
        consoleLogs.push(`[${msg.type()}] ${msg.text()}`);
    });

    // Capture errors
    page.on('pageerror', error => {
        consoleLogs.push(`[ERROR] ${error.message}`);
    });

    try {
        await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

        // Click button
        const button = page.locator('button.btn-primary').first();
        await button.click();
        await page.waitForTimeout(500);

        // Fill email
        const email = `test${Date.now()}@example.com`;
        await page.locator('#waitlist-email').fill(email);

        // Submit
        await page.locator('.btn-submit').first().click();

        // Wait for API response
        await page.waitForTimeout(5000);

        // Check what happened
        const step2 = await page.locator('#waitlist-step-2').isVisible();

        console.log('\n=== CONSOLE LOGS ===');
        consoleLogs.forEach(log => console.log(log));

        console.log('\n=== RESULT ===');
        console.log(`Step 2 visible: ${step2 ? 'YES ✅' : 'NO ❌'}`);

    } catch (error) {
        console.error('Error:', error);
    } finally {
        await browser.close();
    }
})();
