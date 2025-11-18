/**
 * Test: Form Submission with Network Logging
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

    // Capture network requests
    const networkRequests = [];
    page.on('request', request => {
        if (request.url().includes('waitlist') || request.url().includes('admin-ajax.php')) {
            networkRequests.push({
                type: 'request',
                url: request.url(),
                method: request.method(),
                postData: request.postData()
            });
        }
    });

    page.on('response', async response => {
        if (response.url().includes('waitlist') || response.url().includes('admin-ajax.php')) {
            const text = await response.text().catch(() => 'Could not read response');
            networkRequests.push({
                type: 'response',
                url: response.url(),
                status: response.status(),
                body: text
            });
        }
    });

    try {
        await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

        // Click button
        const button = page.locator('button.btn-primary').first();
        await button.click();
        await page.waitForTimeout(500);

        // Fill email
        const email = `test${Date.now()}@example.com`;
        console.log(`Testing with email: ${email}\n`);
        await page.locator('#waitlist-email').first().fill(email);

        // Submit
        console.log('Submitting form...\n');
        await page.locator('.btn-submit').first().click();

        // Wait for API response
        await page.waitForTimeout(5000);

        // Check result
        const step1 = await page.locator('#waitlist-step-1').isVisible();
        const step2 = await page.locator('#waitlist-step-2').isVisible();

        console.log('=== NETWORK REQUESTS ===');
        networkRequests.forEach(req => {
            console.log(`\n${req.type.toUpperCase()}: ${req.url}`);
            if (req.method) console.log(`Method: ${req.method}`);
            if (req.postData) console.log(`Data: ${req.postData.substring(0, 200)}`);
            if (req.status) console.log(`Status: ${req.status}`);
            if (req.body) console.log(`Response: ${req.body.substring(0, 300)}`);
        });

        console.log('\n=== CONSOLE LOGS ===');
        consoleLogs.forEach(log => console.log(log));

        console.log('\n=== RESULT ===');
        console.log(`Step 1 visible: ${step1}`);
        console.log(`Step 2 visible: ${step2 ? 'YES ✅' : 'NO ❌'}`);

    } catch (error) {
        console.error('Error:', error.message);
    } finally {
        await browser.close();
    }
})();
