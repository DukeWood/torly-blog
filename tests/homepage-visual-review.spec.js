/**
 * Homepage Visual Design Review
 * Comprehensive frontend analysis of torly.ai homepage
 */

const { test, expect } = require('@playwright/test');

test.describe('Homepage Visual Design Review', () => {
  test('capture full homepage screenshots', async ({ page }) => {
    await page.goto('https://torly.ai/');

    // Wait for page to fully load
    await page.waitForLoadState('networkidle');

    // Desktop view
    await page.setViewportSize({ width: 1920, height: 1080 });
    await page.screenshot({
      path: 'playwright-report/homepage-desktop-full.png',
      fullPage: true
    });

    // Tablet view
    await page.setViewportSize({ width: 768, height: 1024 });
    await page.screenshot({
      path: 'playwright-report/homepage-tablet-full.png',
      fullPage: true
    });

    // Mobile view
    await page.setViewportSize({ width: 375, height: 667 });
    await page.screenshot({
      path: 'playwright-report/homepage-mobile-full.png',
      fullPage: true
    });

    console.log('✓ Screenshots saved to playwright-report/');
  });

  test('analyze hero section design', async ({ page }) => {
    await page.goto('https://torly.ai/');
    await page.setViewportSize({ width: 1920, height: 1080 });

    const heroSection = page.locator('.hero-section');
    await expect(heroSection).toBeVisible();

    // Capture hero section
    await heroSection.screenshot({
      path: 'playwright-report/section-hero.png'
    });

    // Analyze hero elements
    const heroTitle = page.locator('.hero-title');
    const heroButtons = page.locator('.hero-buttons');

    const titleStyles = await heroTitle.evaluate((el) => {
      const styles = window.getComputedStyle(el);
      return {
        fontSize: styles.fontSize,
        fontWeight: styles.fontWeight,
        lineHeight: styles.lineHeight,
        color: styles.color,
        textAlign: styles.textAlign
      };
    });

    console.log('Hero Title Styles:', titleStyles);

    // Check button spacing
    const buttonGap = await heroButtons.evaluate((el) => {
      return window.getComputedStyle(el).gap;
    });

    console.log('Hero Buttons Gap:', buttonGap);
  });

  test('analyze features section grid', async ({ page }) => {
    await page.goto('https://torly.ai/');

    const featuresSection = page.locator('.features-section');
    await featuresSection.scrollIntoViewIfNeeded();

    await featuresSection.screenshot({
      path: 'playwright-report/section-features.png'
    });

    // Count feature cards
    const featureCards = await page.locator('.feature-card').count();
    console.log(`Feature Cards Count: ${featureCards}`);

    // Check grid layout
    const gridStyles = await page.locator('.features-grid').evaluate((el) => {
      const styles = window.getComputedStyle(el);
      return {
        display: styles.display,
        gridTemplateColumns: styles.gridTemplateColumns,
        gap: styles.gap
      };
    });

    console.log('Features Grid Layout:', gridStyles);
  });

  test('analyze color palette usage', async ({ page }) => {
    await page.goto('https://torly.ai/');

    // Extract CSS variables
    const colorPalette = await page.evaluate(() => {
      const root = document.documentElement;
      const styles = window.getComputedStyle(root);

      return {
        colorYellow: styles.getPropertyValue('--color-yellow'),
        colorGreen: styles.getPropertyValue('--color-green'),
        colorOrange: styles.getPropertyValue('--color-orange'),
        colorChatGreen: styles.getPropertyValue('--color-chat-green'),
        textPrimary: styles.getPropertyValue('--text-primary'),
        bgPrimary: styles.getPropertyValue('--bg-primary'),
        black: styles.getPropertyValue('--black'),
        white: styles.getPropertyValue('--white')
      };
    });

    console.log('Design System Colors:', colorPalette);

    // Verify design system compliance
    expect(colorPalette.colorYellow?.trim()).toBe('hsl(60, 100%, 50%)');
    expect(colorPalette.colorGreen?.trim()).toBe('hsl(108, 100%, 50%)');
    expect(colorPalette.colorOrange?.trim()).toBe('hsl(30, 100%, 50%)');
  });

  test('analyze typography scale', async ({ page }) => {
    await page.goto('https://torly.ai/');

    const typographyElements = {
      heroTitle: '.hero-title',
      sectionTitle: '.section-title',
      featureTitle: '.feature-title',
      body: '.hero-description'
    };

    const typography = {};

    for (const [name, selector] of Object.entries(typographyElements)) {
      const element = page.locator(selector).first();

      if (await element.count() > 0) {
        typography[name] = await element.evaluate((el) => {
          const styles = window.getComputedStyle(el);
          return {
            fontSize: styles.fontSize,
            fontWeight: styles.fontWeight,
            lineHeight: styles.lineHeight,
            fontFamily: styles.fontFamily
          };
        });
      }
    }

    console.log('Typography Scale:', JSON.stringify(typography, null, 2));
  });

  test('analyze spacing and whitespace', async ({ page }) => {
    await page.goto('https://torly.ai/');

    const sections = await page.locator('section[class*="section"]').all();
    const spacingData = [];

    for (const section of sections.slice(0, 5)) {
      const className = await section.getAttribute('class');
      const styles = await section.evaluate((el) => {
        const computed = window.getComputedStyle(el);
        return {
          paddingTop: computed.paddingTop,
          paddingBottom: computed.paddingBottom,
          marginTop: computed.marginTop,
          marginBottom: computed.marginBottom
        };
      });

      spacingData.push({
        section: className,
        ...styles
      });
    }

    console.log('Section Spacing:', JSON.stringify(spacingData, null, 2));
  });

  test('check mobile responsiveness', async ({ page }) => {
    const viewports = [
      { name: 'Mobile Small', width: 320, height: 568 },
      { name: 'Mobile Medium', width: 375, height: 667 },
      { name: 'Mobile Large', width: 414, height: 896 },
      { name: 'Tablet', width: 768, height: 1024 },
      { name: 'Desktop Small', width: 1024, height: 768 }
    ];

    for (const viewport of viewports) {
      await page.setViewportSize({ width: viewport.width, height: viewport.height });
      await page.goto('https://torly.ai/');

      // Check if buttons are visible and properly sized
      const joinButton = page.locator('button.btn-primary').first();
      const buttonBox = await joinButton.boundingBox();

      console.log(`${viewport.name} (${viewport.width}x${viewport.height}): Button width = ${buttonBox?.width}px`);

      // Ensure button is visible
      await expect(joinButton).toBeVisible();
    }
  });

  test('analyze CTA visibility and contrast', async ({ page }) => {
    await page.goto('https://torly.ai/');

    const ctaButtons = await page.locator('.btn-primary, .btn-secondary').all();

    for (let i = 0; i < ctaButtons.length && i < 3; i++) {
      const button = ctaButtons[i];

      const styles = await button.evaluate((el) => {
        const computed = window.getComputedStyle(el);
        return {
          background: computed.background,
          color: computed.color,
          border: computed.border,
          backdropFilter: computed.backdropFilter,
          fontSize: computed.fontSize,
          padding: computed.padding
        };
      });

      console.log(`CTA Button ${i + 1}:`, styles);
    }
  });

  test('check visual consistency across sections', async ({ page }) => {
    await page.goto('https://torly.ai/');

    // Capture individual sections
    const sections = [
      { name: 'social-proof', selector: '.social-proof-section' },
      { name: 'how-it-works', selector: '.how-it-works-section' },
      { name: 'testimonials', selector: '.testimonials-section' },
      { name: 'stats', selector: '.uk-visa-stats-section' },
      { name: 'faq', selector: '.faq-section' },
      { name: 'cta', selector: '.cta-section' }
    ];

    for (const section of sections) {
      const element = page.locator(section.selector);

      if (await element.count() > 0) {
        await element.scrollIntoViewIfNeeded();
        await element.screenshot({
          path: `playwright-report/section-${section.name}.png`
        });
      }
    }

    console.log('✓ All section screenshots saved');
  });
});
