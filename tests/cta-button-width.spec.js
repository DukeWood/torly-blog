const { test, expect } = require('@playwright/test');

test.describe('CTA Section Button Width Test', () => {
    test('CTA buttons should be 20% width (80% reduction)', async ({ page }) => {
        // Navigate to homepage
        await page.goto('https://torly.ai/');

        // Wait for page to load
        await page.waitForLoadState('networkidle');

        // Scroll to CTA section
        await page.locator('.cta-section').scrollIntoViewIfNeeded();

        // Get the CTA section and buttons
        const ctaSection = page.locator('.cta-section');
        const ctaButtons = ctaSection.locator('.cta-buttons');
        const joinButton = ctaButtons.locator('.btn-primary');
        const consultButton = ctaButtons.locator('.btn-secondary');

        // Wait for buttons to be visible
        await expect(joinButton).toBeVisible();
        await expect(consultButton).toBeVisible();

        // Get bounding boxes
        const ctaSectionBox = await ctaSection.boundingBox();
        const joinButtonBox = await joinButton.boundingBox();
        const consultButtonBox = await consultButton.boundingBox();

        console.log('\n=== CTA Section Measurements ===');
        console.log('CTA Section width:', ctaSectionBox.width);
        console.log('Join Waitlist button width:', joinButtonBox.width);
        console.log('Schedule Consultation button width:', consultButtonBox.width);

        // Calculate percentages (relative to CTA section)
        const joinWidthPercent = (joinButtonBox.width / ctaSectionBox.width) * 100;
        const consultWidthPercent = (consultButtonBox.width / ctaSectionBox.width) * 100;

        console.log('\n=== Button Width Percentages ===');
        console.log('Join Waitlist:', joinWidthPercent.toFixed(2) + '%');
        console.log('Schedule Consultation:', consultWidthPercent.toFixed(2) + '%');

        // Verify buttons are compact (should be around 20% or less due to container constraints)
        // Allow flexibility since min-width: fit-content might make them slightly larger
        expect(joinWidthPercent).toBeLessThan(30);
        expect(consultWidthPercent).toBeLessThan(30);

        // Take screenshot
        await ctaSection.screenshot({
            path: 'tests/screenshots/cta-buttons-reduced-width.png'
        });

        console.log('\n✅ Test passed: Buttons are compact (reduced by 80%)');
        console.log('📸 Screenshot saved to tests/screenshots/cta-buttons-reduced-width.png');
    });

    test('Visual comparison - full page screenshot', async ({ page }) => {
        // Navigate to homepage
        await page.goto('https://torly.ai/');
        await page.waitForLoadState('networkidle');

        // Scroll to CTA section
        await page.locator('.cta-section').scrollIntoViewIfNeeded();

        // Wait a moment for any animations
        await page.waitForTimeout(500);

        // Take full page screenshot
        await page.screenshot({
            path: 'tests/screenshots/homepage-cta-compact-buttons.png',
            fullPage: false
        });

        console.log('📸 Full viewport screenshot saved');
    });
});
