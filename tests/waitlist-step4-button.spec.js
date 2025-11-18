/**
 * Test Waitlist Modal Step 4 Button Text
 * Verifies "Join Innovatorly Tribe" button appears correctly
 */

const { test, expect } = require('@playwright/test');

test.describe('Waitlist Modal Step 4 - Button Text', () => {
  test('Step 4 shows "Join Innovatorly Tribe" button', async ({ page }) => {
    await page.goto('https://torly.ai/');

    // Open waitlist modal
    const joinButton = page.locator('button.btn-primary').filter({ hasText: 'Join Waitlist' }).first();
    await joinButton.click();
    await page.waitForTimeout(500);

    // Fill email and submit
    const emailInput = page.locator('#waitlist-email');
    const uniqueEmail = `step4-test-${Date.now()}@example.com`;
    await emailInput.fill(uniqueEmail);

    const submitButton = page.locator('form button[type="submit"]').first();
    await submitButton.click();

    // Wait for Step 2 to appear
    await page.waitForTimeout(2000);

    // Click through preferences (Step 2)
    const continueBtn = page.locator('#waitlist-step-2 button.btn-primary').first();
    if (await continueBtn.isVisible()) {
      await continueBtn.click();
      await page.waitForTimeout(500);
    }

    // Fill name (Step 3)
    const nameInput = page.locator('#full-name');
    if (await nameInput.isVisible()) {
      await nameInput.fill('Test User');
      const step3ContinueBtn = page.locator('#waitlist-step-3 button.btn-primary').first();
      await step3ContinueBtn.click();
      await page.waitForTimeout(500);
    }

    // Step 4 should now be visible
    const step4 = page.locator('#waitlist-step-4');
    await expect(step4).toBeVisible({ timeout: 3000 });

    // Take screenshot of Step 4
    await page.screenshot({ path: 'playwright-report/waitlist-step4-button.png', fullPage: true });

    // Verify button text
    const tribeButton = step4.locator('a.btn-primary').filter({ hasText: 'Join Innovatorly Tribe' });
    await expect(tribeButton).toBeVisible();

    // Verify button href
    const href = await tribeButton.getAttribute('href');
    expect(href).toBe('https://www.patreon.com/innovatorly');

    // Verify page title
    const title = step4.locator('.waitlist-title');
    await expect(title).toHaveText('Welcome to the Tribe!');

    console.log('✅ Step 4 button correctly shows "Join Innovatorly Tribe"');
    console.log('✅ Button links to Patreon: https://www.patreon.com/innovatorly');
  });

  test('Button should NOT show "Take Free Assessment"', async ({ page }) => {
    await page.goto('https://torly.ai/');

    // Open modal and navigate to Step 4
    const joinButton = page.locator('button.btn-primary').filter({ hasText: 'Join Waitlist' }).first();
    await joinButton.click();
    await page.waitForTimeout(500);

    const emailInput = page.locator('#waitlist-email');
    await emailInput.fill(`no-old-button-${Date.now()}@example.com`);

    const submitButton = page.locator('form button[type="submit"]').first();
    await submitButton.click();
    await page.waitForTimeout(2000);

    // Click through steps
    const continueBtn = page.locator('#waitlist-step-2 button.btn-primary').first();
    if (await continueBtn.isVisible()) {
      await continueBtn.click();
      await page.waitForTimeout(500);
    }

    const nameInput = page.locator('#full-name');
    if (await nameInput.isVisible()) {
      await nameInput.fill('Test User');
      const step3ContinueBtn = page.locator('#waitlist-step-3 button.btn-primary').first();
      await step3ContinueBtn.click();
      await page.waitForTimeout(500);
    }

    // Verify old button text does NOT exist
    const oldButton = page.locator('a.btn-primary').filter({ hasText: 'Take Free Assessment' });
    await expect(oldButton).not.toBeVisible();

    // Verify old URL does NOT exist
    const assessmentLink = page.locator('a[href="/visa-assessment"]');
    await expect(assessmentLink).not.toBeVisible();

    console.log('✅ Old "Take Free Assessment" button is gone');
    console.log('✅ Old "/visa-assessment" link removed');
  });

  test('Complete summary - Step 4 button verification', async ({ page }) => {
    console.log('\n📋 STEP 4 BUTTON VERIFICATION SUMMARY');
    console.log('═══════════════════════════════════════');

    await page.goto('https://torly.ai/');

    // Quick verification by checking template content
    const response = await page.goto('https://torly.ai/');
    const content = await response.text();

    const hasNewButton = content.includes('Join Innovatorly Tribe');
    const hasOldButton = content.includes('Take Free Assessment');
    const hasPatreonLink = content.includes('patreon.com/innovatorly');
    const hasOldLink = content.includes('/visa-assessment');

    console.log(`  New Button Text Present    ${hasNewButton ? '✅ PASS' : '❌ FAIL'}`);
    console.log(`  Old Button Text Removed    ${!hasOldButton ? '✅ PASS' : '❌ FAIL'}`);
    console.log(`  Patreon Link Present       ${hasPatreonLink ? '✅ PASS' : '❌ FAIL'}`);
    console.log(`  Old Assessment Link Gone   ${!hasOldLink ? '✅ PASS' : '❌ FAIL'}`);
    console.log('═══════════════════════════════════════');

    expect(hasNewButton && !hasOldButton && hasPatreonLink && !hasOldLink).toBe(true);
  });
});
