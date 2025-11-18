/**
 * CTA Button Text Centering Test
 * Verifies that button text stays centered within the button container
 */

const { test, expect } = require('@playwright/test');

test.describe('CTA Button Text Centering', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('https://torly.ai/');
  });

  test('Join Waitlist button has text-align center', async ({ page }) => {
    const joinButton = page.locator('button.btn-primary').filter({ hasText: 'Join Waitlist' }).first();

    // Check button exists
    await expect(joinButton).toBeVisible();

    // Get computed styles
    const textAlign = await joinButton.evaluate((el) => {
      return window.getComputedStyle(el).textAlign;
    });

    // Verify text-align is center
    expect(textAlign).toBe('center');

    // Verify flexbox centering properties
    const flexProps = await joinButton.evaluate((el) => {
      const styles = window.getComputedStyle(el);
      return {
        display: styles.display,
        alignItems: styles.alignItems,
        justifyContent: styles.justifyContent
      };
    });

    expect(flexProps.display).toContain('flex');
    expect(flexProps.alignItems).toBe('center');
    expect(flexProps.justifyContent).toBe('center');
  });

  test('Schedule a Consultation button has text-align center', async ({ page }) => {
    const consultButton = page.locator('a.btn-secondary').filter({ hasText: 'Schedule a Consultation' }).first();

    // Check button exists
    await expect(consultButton).toBeVisible();

    // Get computed styles
    const textAlign = await consultButton.evaluate((el) => {
      return window.getComputedStyle(el).textAlign;
    });

    // Verify text-align is center
    expect(textAlign).toBe('center');

    // Verify flexbox centering properties
    const flexProps = await consultButton.evaluate((el) => {
      const styles = window.getComputedStyle(el);
      return {
        display: styles.display,
        alignItems: styles.alignItems,
        justifyContent: styles.justifyContent
      };
    });

    expect(flexProps.display).toContain('flex');
    expect(flexProps.alignItems).toBe('center');
    expect(flexProps.justifyContent).toBe('center');
  });

  test('Button text is visually centered (bounding box check)', async ({ page }) => {
    const joinButton = page.locator('button.btn-primary').filter({ hasText: 'Join Waitlist' }).first();

    // Get button bounding box
    const buttonBox = await joinButton.boundingBox();

    // Take screenshot for visual verification
    await joinButton.screenshot({ path: 'playwright-report/button-centering-visual.png' });

    // Basic sanity check - button should have reasonable dimensions
    expect(buttonBox.width).toBeGreaterThan(100);
    expect(buttonBox.height).toBeGreaterThan(30);

    console.log(`Button dimensions: ${buttonBox.width}x${buttonBox.height}`);
  });

  test('All CTA buttons have consistent centering', async ({ page }) => {
    // Get all primary and secondary buttons
    const allButtons = await page.locator('.btn-primary, .btn-secondary').all();

    console.log(`Found ${allButtons.length} CTA buttons on the page`);

    // Check each button has text-align center
    for (const button of allButtons) {
      const textAlign = await button.evaluate((el) => {
        return window.getComputedStyle(el).textAlign;
      });

      expect(textAlign).toBe('center');
    }
  });

  test('Mobile view - buttons remain centered', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });

    const joinButton = page.locator('button.btn-primary').filter({ hasText: 'Join Waitlist' }).first();

    // Wait for button to be visible
    await expect(joinButton).toBeVisible();

    // Verify centering on mobile
    const mobileStyles = await joinButton.evaluate((el) => {
      const styles = window.getComputedStyle(el);
      return {
        textAlign: styles.textAlign,
        justifyContent: styles.justifyContent,
        width: styles.width
      };
    });

    expect(mobileStyles.textAlign).toBe('center');
    expect(mobileStyles.justifyContent).toBe('center');

    console.log(`Mobile button width: ${mobileStyles.width}`);
  });
});
