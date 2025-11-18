/**
 * Test: Capture Full AJAX Response
 */

const { chromium } = require('playwright');

(async () => {
    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();

    let ajaxResponse = null;

    // Capture AJAX response
    page.on('response', async response => {
        if (response.url().includes('admin-ajax.php')) {
            try {
                const text = await response.text();
                ajaxResponse = {
                    status: response.status(),
                    statusText: response.statusText(),
                    body: text
                };
            } catch (error) {
                ajaxResponse = { error: error.message };
            }
        }
    });

    try {
        console.log('Loading homepage...');
        await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

        console.log('Opening modal...');
        await page.locator('button.btn-primary').first().click();
        await page.waitForTimeout(500);

        const email = `test${Date.now()}@example.com`;
        console.log(`Entering email: ${email}`);
        await page.locator('#waitlist-email').first().fill(email);

        console.log('Submitting form...\n');
        await page.locator('.btn-submit').first().click();

        // Wait for AJAX response
        await page.waitForTimeout(8000);

        console.log('=== AJAX RESPONSE ===');
        if (ajaxResponse) {
            console.log(`Status: ${ajaxResponse.status}`);
            console.log(`Body: ${ajaxResponse.body}`);
        } else {
            console.log('No AJAX response captured');
        }

        // Check UI state
        const step1 = await page.locator('#waitlist-step-1').isVisible();
        const step2 = await page.locator('#waitlist-step-2').isVisible();

        console.log('\n=== UI STATE ===');
        console.log(`Step 1 visible: ${step1}`);
        console.log(`Step 2 visible: ${step2 ? 'YES ✅' : 'NO ❌'}`);

        // Take screenshot
        await page.screenshot({ path: 'ajax-response-test.png' });
        console.log('\nScreenshot: ajax-response-test.png');

    } catch (error) {
        console.error('Error:', error.message);
    } finally {
        await browser.close();
    }
})();
