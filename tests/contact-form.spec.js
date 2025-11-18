/**
 * TorlyAI Contact Form - Quick Test
 */

const { test, expect } = require('@playwright/test');

test.describe('Contact Form', () => {

  test('Contact form submits successfully', async ({ page }) => {
    console.log('🧪 TEST: Contact form submission');

    // Navigate to homepage (or contact page if separate)
    await page.goto('https://torly.ai/');
    await page.waitForLoadState('networkidle');
    console.log('✓ Page loaded');

    // Find contact form section (scroll to it if needed)
    // This test assumes contact form exists on the page
    // Adjust selectors based on actual HTML structure

    // Test API endpoint directly
    const response = await page.request.post('https://torly.ai/wp-json/torlyai/v1/contact-form', {
      data: {
        name: 'Playwright Test User',
        email: 'playwright-test@example.com',
        message: 'This is an automated test message from Playwright.'
      }
    });

    expect(response.ok()).toBeTruthy();
    console.log(`✓ Response status: ${response.status()}`);

    const responseBody = await response.json();
    console.log(`✓ Response: ${JSON.stringify(responseBody)}`);

    expect(responseBody.status).toBe('success');
    expect(responseBody.message).toContain('sent successfully');
    console.log('✓ Contact form API working correctly');

    console.log('✅ TEST PASSED: Contact form submission');
  });
});
