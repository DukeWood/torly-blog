const { test, expect } = require('@playwright/test');

test.describe('Waitlist Button Width Test', () => {
    test('Join Waitlist button should be 1/4 width (25%)', async ({ page }) => {
        // Navigate to homepage
        await page.goto('https://torly.ai/');

        // Wait for page to load
        await page.waitForLoadState('networkidle');

        // Find and click a "Join Waitlist" button to open the modal
        // Try multiple selectors in case there are different buttons
        const joinButtons = page.locator('text="Join Waitlist"').or(page.locator('text="Join waitlist"'));
        await joinButtons.first().click();

        // Wait for modal to appear
        await page.waitForSelector('#waitlist-modal', { state: 'visible', timeout: 5000 });

        // Get the modal container and submit button
        const modal = page.locator('#waitlist-modal');
        const submitButton = modal.locator('.btn-submit');

        // Wait for button to be visible
        await expect(submitButton).toBeVisible();

        // Get the bounding boxes
        const modalBox = await modal.boundingBox();
        const buttonBox = await submitButton.boundingBox();

        console.log('Modal width:', modalBox.width);
        console.log('Button width:', buttonBox.width);
        console.log('Button width percentage:', (buttonBox.width / modalBox.width * 100).toFixed(2) + '%');

        // Calculate the percentage
        const widthPercentage = (buttonBox.width / modalBox.width) * 100;

        // Check if button width is approximately 25% (allow 2% margin for padding/borders)
        expect(widthPercentage).toBeGreaterThan(23);
        expect(widthPercentage).toBeLessThan(27);

        // Verify button is centered (check left offset)
        const buttonLeft = buttonBox.x - modalBox.x;
        const expectedLeft = (modalBox.width - buttonBox.width) / 2;
        const leftDifference = Math.abs(buttonLeft - expectedLeft);

        console.log('Button left offset:', buttonLeft);
        console.log('Expected left offset (centered):', expectedLeft);
        console.log('Difference:', leftDifference);

        // Check centering (allow 5px tolerance)
        expect(leftDifference).toBeLessThan(5);

        // Take a screenshot
        await page.screenshot({
            path: 'tests/screenshots/waitlist-button-1-4-width.png',
            fullPage: false
        });

        console.log('✅ Test passed: Button is 1/4 width and centered');
    });

    test('Visual verification - capture modal with 1/4 width button', async ({ page }) => {
        // Navigate and open modal
        await page.goto('https://torly.ai/');
        await page.waitForLoadState('networkidle');

        const joinButtons = page.locator('text="Join Waitlist"').or(page.locator('text="Join waitlist"'));
        await joinButtons.first().click();

        // Wait for modal
        await page.waitForSelector('#waitlist-modal', { state: 'visible' });

        // Get modal element
        const modal = page.locator('#waitlist-modal');

        // Take screenshot of just the modal
        await modal.screenshot({
            path: 'tests/screenshots/waitlist-modal-button-detail.png'
        });

        console.log('✅ Screenshot saved to tests/screenshots/waitlist-modal-button-detail.png');
    });
});
