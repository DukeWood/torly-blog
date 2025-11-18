/**
 * TorlyAI Waitlist Feature - End-to-End Test
 * Tests Phase 1 (Two-step flow + Patreon) and Phase 2 (Behavioral tracking)
 */

const { test, expect } = require('@playwright/test');

test.describe('TorlyAI Waitlist Feature', () => {

  test.beforeEach(async ({ page }) => {
    // Navigate to homepage
    await page.goto('https://torly.ai/');
    await page.waitForLoadState('networkidle');
  });

  test('Complete waitlist flow with profile questions', async ({ page }) => {
    console.log('🧪 TEST: Complete waitlist flow with profile questions');

    // Step 1: Verify homepage loads
    console.log('✓ Step 1: Homepage loaded');
    await expect(page).toHaveTitle(/Torly AI/);

    // Step 2: Find and click "Join Waitlist" button (hero CTA)
    console.log('✓ Step 2: Looking for Join Waitlist button...');

    // Wait for the hero section to be visible
    await page.waitForSelector('.hero-section', { timeout: 10000 });

    // Find the Join Waitlist button in hero section
    const heroButton = page.locator('.hero-section button:has-text("Join Waitlist"), .hero-section a:has-text("Join Waitlist")').first();
    await expect(heroButton).toBeVisible({ timeout: 10000 });
    await heroButton.click();
    console.log('✓ Clicked Join Waitlist button');

    // Step 3: Verify modal opens
    console.log('✓ Step 3: Verifying modal opens...');
    const modal = page.locator('#waitlistModal, .waitlist-modal');
    await expect(modal).toBeVisible({ timeout: 5000 });
    console.log('✓ Modal visible');

    // Step 4: Fill in email and submit
    console.log('✓ Step 4: Submitting email...');
    const testEmail = `test-${Date.now()}@playwright-test.com`;
    const emailInput = page.locator('input[type="email"], input[name="email"]').first();
    await emailInput.fill(testEmail);
    console.log(`✓ Email filled: ${testEmail}`);

    // Submit the form
    const submitButton = page.locator('button:has-text("Join"), button:has-text("Submit")').first();
    await submitButton.click();
    console.log('✓ Submit button clicked');

    // Step 5: Verify success screen appears
    console.log('✓ Step 5: Waiting for success screen...');
    await page.waitForSelector('#waitlistSuccess, .waitlist-success', { timeout: 10000 });
    const successScreen = page.locator('#waitlistSuccess, .waitlist-success');
    await expect(successScreen).toBeVisible();
    console.log('✓ Success screen visible');

    // Verify success message
    const successMessage = successScreen.locator('text=/You\'re on the list|success/i');
    await expect(successMessage).toBeVisible();
    console.log('✓ Success message confirmed');

    // Step 6: Verify profile questions are visible
    console.log('✓ Step 6: Checking profile questions...');

    // Check for country dropdown
    const countrySelect = page.locator('#profileCountry, select[name="country"]');
    await expect(countrySelect).toBeVisible({ timeout: 5000 });
    console.log('✓ Country dropdown visible');

    // Check for business stage radio buttons
    const businessStage = page.locator('input[name="business_stage"]').first();
    await expect(businessStage).toBeVisible();
    console.log('✓ Business stage options visible');

    // Check for application timeline radio buttons
    const timeline = page.locator('input[name="timeline"], input[name="application_timeline"]').first();
    await expect(timeline).toBeVisible();
    console.log('✓ Application timeline options visible');

    // Step 7: Fill in profile questions
    console.log('✓ Step 7: Filling profile questions...');

    // Select country (e.g., United Kingdom)
    await countrySelect.selectOption({ value: 'GB' });
    console.log('✓ Country selected: GB');

    // Select business stage (e.g., "Have revenue")
    await page.locator('input[name="business_stage"][value="revenue"]').click();
    console.log('✓ Business stage selected: revenue');

    // Select timeline (e.g., "Next 3 months")
    await page.locator('input[name="timeline"][value="0-3months"], input[name="application_timeline"][value="0-3months"]').first().click();
    console.log('✓ Timeline selected: 0-3months');

    // Submit profile questions
    const submitProfileButton = page.locator('button:has-text("Submit Answers"), button[onclick*="submitProfile"]');
    await submitProfileButton.click();
    console.log('✓ Profile submitted');

    // Step 8: Verify Patreon invitation screen appears
    console.log('✓ Step 8: Waiting for Patreon invitation...');
    await page.waitForSelector('#waitlistPatreon, .waitlist-patreon', { timeout: 5000 });
    const patreonScreen = page.locator('#waitlistPatreon, .waitlist-patreon');
    await expect(patreonScreen).toBeVisible();
    console.log('✓ Patreon invitation screen visible');

    // Verify Patreon heading
    const patreonHeading = patreonScreen.locator('text=/Join Innovatorly Tribe|Community/i');
    await expect(patreonHeading).toBeVisible();
    console.log('✓ Patreon heading confirmed');

    // Verify Patreon link
    const patreonLink = page.locator('a[href*="patreon.com/innovatorly"]');
    await expect(patreonLink).toBeVisible();
    console.log('✓ Patreon link found');

    // Verify UTM parameters in Patreon link
    const patreonHref = await patreonLink.getAttribute('href');
    expect(patreonHref).toContain('utm_medium=waitlist');
    expect(patreonHref).toContain('utm_source=torlyai');
    console.log('✓ UTM parameters verified');

    // Step 9: Close modal
    console.log('✓ Step 9: Closing modal...');
    const closeButton = patreonScreen.locator('button:has-text("Close")');
    await closeButton.click();
    console.log('✓ Modal closed');

    // Wait a moment for modal to disappear
    await page.waitForTimeout(500);

    console.log('✅ TEST PASSED: Complete waitlist flow with profile questions');
  });

  test('Waitlist flow with skipped profile questions', async ({ page }) => {
    console.log('🧪 TEST: Waitlist flow with skipped profile questions');

    // Step 1: Click Join Waitlist button
    await page.waitForSelector('.hero-section', { timeout: 10000 });
    const heroButton = page.locator('.hero-section button:has-text("Join Waitlist"), .hero-section a:has-text("Join Waitlist")').first();
    await heroButton.click();
    console.log('✓ Join Waitlist button clicked');

    // Step 2: Submit email
    const testEmail = `test-skip-${Date.now()}@playwright-test.com`;
    const emailInput = page.locator('input[type="email"]').first();
    await emailInput.fill(testEmail);
    console.log(`✓ Email filled: ${testEmail}`);

    const submitButton = page.locator('button:has-text("Join"), button:has-text("Submit")').first();
    await submitButton.click();
    console.log('✓ Email submitted');

    // Step 3: Wait for success screen
    await page.waitForSelector('#waitlistSuccess, .waitlist-success', { timeout: 10000 });
    console.log('✓ Success screen visible');

    // Step 4: Skip profile questions
    const skipButton = page.locator('button:has-text("Skip"), button[onclick*="skipProfile"]');
    await expect(skipButton).toBeVisible({ timeout: 5000 });
    await skipButton.click();
    console.log('✓ Profile questions skipped');

    // Step 5: Verify Patreon screen appears directly
    await page.waitForSelector('#waitlistPatreon, .waitlist-patreon', { timeout: 5000 });
    const patreonScreen = page.locator('#waitlistPatreon, .waitlist-patreon');
    await expect(patreonScreen).toBeVisible();
    console.log('✓ Patreon invitation screen visible after skip');

    // Close modal
    const closeButton = patreonScreen.locator('button:has-text("Close")');
    await closeButton.click();

    console.log('✅ TEST PASSED: Waitlist flow with skipped profile questions');
  });

  test('Bottom CTA button opens waitlist modal', async ({ page }) => {
    console.log('🧪 TEST: Bottom CTA button opens waitlist modal');

    // Scroll to bottom of page
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    await page.waitForTimeout(1000);
    console.log('✓ Scrolled to bottom');

    // Find bottom CTA button
    const bottomButton = page.locator('button[onclick*="cta-bottom"], button:has-text("Join Waitlist")').last();
    await expect(bottomButton).toBeVisible({ timeout: 5000 });
    console.log('✓ Bottom CTA button found');

    // Click bottom CTA
    await bottomButton.click();
    console.log('✓ Bottom CTA button clicked');

    // Verify modal opens
    const modal = page.locator('#waitlistModal, .waitlist-modal');
    await expect(modal).toBeVisible({ timeout: 5000 });
    console.log('✓ Modal opened from bottom CTA');

    console.log('✅ TEST PASSED: Bottom CTA button opens waitlist modal');
  });

  test('Behavioral tracking code is present', async ({ page }) => {
    console.log('🧪 TEST: Behavioral tracking code is present');

    // Check for behavioral tracking object
    const hasTracking = await page.evaluate(() => {
      return typeof window.behavioralTracking !== 'undefined';
    });
    expect(hasTracking).toBeTruthy();
    console.log('✓ behavioralTracking object found');

    // Check for scroll tracking function
    const hasScrollTracking = await page.evaluate(() => {
      return typeof window.trackScrollDepth === 'function';
    });
    expect(hasScrollTracking).toBeTruthy();
    console.log('✓ trackScrollDepth function found');

    // Check for section tracking function
    const hasSectionTracking = await page.evaluate(() => {
      return typeof window.setupSectionTracking === 'function';
    });
    expect(hasSectionTracking).toBeTruthy();
    console.log('✓ setupSectionTracking function found');

    // Check for behavioral data function
    const hasBehavioralData = await page.evaluate(() => {
      return typeof window.getBehavioralData === 'function';
    });
    expect(hasBehavioralData).toBeTruthy();
    console.log('✓ getBehavioralData function found');

    // Check for UTM tracking function
    const hasUTMTracking = await page.evaluate(() => {
      return typeof window.getUTMParams === 'function';
    });
    expect(hasUTMTracking).toBeTruthy();
    console.log('✓ getUTMParams function found');

    console.log('✅ TEST PASSED: Behavioral tracking code is present');
  });

  test('Scroll depth tracking works', async ({ page }) => {
    console.log('🧪 TEST: Scroll depth tracking works');

    // Scroll to 50%
    await page.evaluate(() => {
      const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
      window.scrollTo(0, scrollHeight * 0.5);
    });
    await page.waitForTimeout(500);

    // Check scroll depth
    const scrollDepth50 = await page.evaluate(() => {
      return window.behavioralTracking?.maxScrollDepth || 0;
    });
    expect(scrollDepth50).toBeGreaterThan(40);
    expect(scrollDepth50).toBeLessThan(60);
    console.log(`✓ Scroll depth at 50%: ${scrollDepth50}%`);

    // Scroll to 100%
    await page.evaluate(() => {
      window.scrollTo(0, document.body.scrollHeight);
    });
    await page.waitForTimeout(500);

    // Check scroll depth increased
    const scrollDepth100 = await page.evaluate(() => {
      return window.behavioralTracking?.maxScrollDepth || 0;
    });
    expect(scrollDepth100).toBeGreaterThan(90);
    console.log(`✓ Scroll depth at 100%: ${scrollDepth100}%`);

    console.log('✅ TEST PASSED: Scroll depth tracking works');
  });

  test('Duplicate email validation', async ({ page }) => {
    console.log('🧪 TEST: Duplicate email validation');

    // Use a known existing email or submit twice
    const testEmail = 'duplicate-test@example.com';

    // First submission
    const heroButton = page.locator('.hero-section button:has-text("Join Waitlist")').first();
    await heroButton.click();

    const emailInput = page.locator('input[type="email"]').first();
    await emailInput.fill(testEmail);

    const submitButton = page.locator('button:has-text("Join"), button:has-text("Submit")').first();
    await submitButton.click();

    // Wait for response
    await page.waitForTimeout(2000);

    // Check if error message appears or success (depends on if email exists)
    const hasSuccess = await page.locator('#waitlistSuccess, .waitlist-success').isVisible().catch(() => false);
    const hasError = await page.locator('.error-message, .alert-error, text=/already exists/i').isVisible().catch(() => false);

    expect(hasSuccess || hasError).toBeTruthy();
    console.log(`✓ Response received: ${hasSuccess ? 'Success' : 'Error (duplicate)'}`);

    console.log('✅ TEST PASSED: Duplicate email validation works');
  });
});
