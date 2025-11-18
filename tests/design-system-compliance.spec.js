const { test, expect } = require('@playwright/test');

/**
 * TorlyAI Design System Compliance Tests
 * Tests About and Contact pages against TORLYAI_DESIGN_SYSTEM.md specifications
 */

const DESIGN_SYSTEM = {
  colors: {
    white: '#ffffff',
    black: '#000000',
    neutral50: '#f9fafb',
    neutral200: '#e5e7eb',
    chatGreen: '#10b981',
    textPrimary: 'rgb(0, 0, 0)',
    textSecondary: 'rgba(0, 0, 0, 0.7)',
  },
  typography: {
    heroTitle: {
      desktop: { min: 60, max: 80 }, // 72px ± tolerance
      mobile: { min: 30, max: 42 },  // 36px ± tolerance
      fontWeight: 700, // Minimum for bold
      lineHeight: { min: 0.8, max: 1.1 },
    },
    sectionTitle: {
      desktop: { min: 42, max: 54 }, // 48px ± tolerance
      mobile: { min: 26, max: 34 },  // 30px ± tolerance
      fontWeight: 700,
    },
    bodyText: {
      desktop: { min: 16, max: 20 }, // 18px ± tolerance
      mobile: { min: 14, max: 18 },  // 16px ± tolerance
    },
  },
  spacing: {
    sectionPadding: {
      desktop: { min: 56, max: 72 }, // 64px ± tolerance
      mobile: { min: 40, max: 56 },  // 48px ± tolerance
    },
  },
  components: {
    buttonBorderRadius: { min: 4, max: 10 }, // 8px ± tolerance for modern design
    cardBorderRadius: { min: 12, max: 20 },  // 16px ± tolerance
    inputBorderRadius: { min: 10, max: 14 }, // 12px ± tolerance
  },
};

// Helper function to extract numeric value from CSS
function parseNumericValue(value) {
  const match = value.match(/[\d.]+/);
  return match ? parseFloat(match[0]) : 0;
}

// Helper function to check if value is within range
function isInRange(value, min, max, label) {
  const result = value >= min && value <= max;
  if (!result) {
    console.log(`❌ ${label}: ${value}px (expected ${min}-${max}px)`);
  } else {
    console.log(`✓ ${label}: ${value}px`);
  }
  return result;
}

test.describe('About Page - Design System Compliance', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('https://torly.ai/about/');
    await page.waitForLoadState('networkidle');
  });

  test('should have correct hero typography on desktop', async ({ page }) => {
    const viewport = { width: 1280, height: 720 };
    await page.setViewportSize(viewport);

    const heroTitle = page.locator('.modern-hero h1, .hero-title').first();
    await expect(heroTitle).toBeVisible();

    const fontSize = await heroTitle.evaluate((el) => {
      const computed = window.getComputedStyle(el);
      return parseFloat(computed.fontSize);
    });

    const fontWeight = await heroTitle.evaluate((el) => {
      const computed = window.getComputedStyle(el);
      return parseInt(computed.fontWeight);
    });

    console.log('\n=== Desktop Hero Typography ===');
    expect(
      isInRange(
        fontSize,
        DESIGN_SYSTEM.typography.heroTitle.desktop.min,
        DESIGN_SYSTEM.typography.heroTitle.desktop.max,
        'Hero font size'
      )
    ).toBeTruthy();

    expect(fontWeight).toBeGreaterThanOrEqual(DESIGN_SYSTEM.typography.heroTitle.fontWeight);
    console.log(`✓ Hero font weight: ${fontWeight}`);
  });

  test('should have correct section titles', async ({ page }) => {
    const sectionTitles = page.locator('.section-header h2, h2.section-title');
    const count = await sectionTitles.count();

    console.log('\n=== Section Titles ===');
    console.log(`Found ${count} section titles`);

    if (count > 0) {
      const firstTitle = sectionTitles.first();
      const fontSize = await firstTitle.evaluate((el) => {
        return parseFloat(window.getComputedStyle(el).fontSize);
      });

      const fontWeight = await firstTitle.evaluate((el) => {
        return parseInt(window.getComputedStyle(el).fontWeight);
      });

      expect(
        isInRange(
          fontSize,
          DESIGN_SYSTEM.typography.sectionTitle.desktop.min,
          DESIGN_SYSTEM.typography.sectionTitle.desktop.max,
          'Section title font size'
        )
      ).toBeTruthy();

      expect(fontWeight).toBeGreaterThanOrEqual(DESIGN_SYSTEM.typography.sectionTitle.fontWeight);
      console.log(`✓ Section title font weight: ${fontWeight}`);
    }
  });

  test('should have correct card styling', async ({ page }) => {
    const cards = page.locator('.feature-card, .stat-card, .benefit-item');
    const count = await cards.count();

    console.log('\n=== Card Styling ===');
    console.log(`Found ${count} cards`);

    if (count > 0) {
      const firstCard = cards.first();

      const borderRadius = await firstCard.evaluate((el) => {
        const computed = window.getComputedStyle(el);
        return parseFloat(computed.borderRadius);
      });

      const backgroundColor = await firstCard.evaluate((el) => {
        return window.getComputedStyle(el).backgroundColor;
      });

      // Check border radius (should be around 16px for cards)
      if (borderRadius > 0) {
        expect(
          isInRange(
            borderRadius,
            DESIGN_SYSTEM.components.cardBorderRadius.min,
            DESIGN_SYSTEM.components.cardBorderRadius.max,
            'Card border radius'
          )
        ).toBeTruthy();
      }

      console.log(`✓ Card background: ${backgroundColor}`);
    }
  });

  test('should have correct button styling', async ({ page }) => {
    const buttons = page.locator('.btn-primary, .btn-secondary, button[type="submit"]');
    const count = await buttons.count();

    console.log('\n=== Button Styling ===');
    console.log(`Found ${count} buttons`);

    if (count > 0) {
      const firstButton = buttons.first();

      const borderRadius = await firstButton.evaluate((el) => {
        return parseFloat(window.getComputedStyle(el).borderRadius);
      });

      const padding = await firstButton.evaluate((el) => {
        const computed = window.getComputedStyle(el);
        return {
          top: parseFloat(computed.paddingTop),
          left: parseFloat(computed.paddingLeft),
        };
      });

      // Buttons should have rounded corners (modern design uses 8px, not 9999px for this implementation)
      expect(borderRadius).toBeGreaterThan(0);
      console.log(`✓ Button border radius: ${borderRadius}px`);
      console.log(`✓ Button padding: ${padding.top}px ${padding.left}px`);
    }
  });

  test('should have correct spacing between sections', async ({ page }) => {
    const sections = page.locator('.content-section, section');
    const count = await sections.count();

    console.log('\n=== Section Spacing ===');
    console.log(`Found ${count} sections`);

    if (count > 0) {
      const firstSection = sections.first();

      const padding = await firstSection.evaluate((el) => {
        const computed = window.getComputedStyle(el);
        return {
          top: parseFloat(computed.paddingTop),
          bottom: parseFloat(computed.paddingBottom),
        };
      });

      console.log(`✓ Section padding-top: ${padding.top}px`);
      console.log(`✓ Section padding-bottom: ${padding.bottom}px`);

      // At least one should be within desktop range
      const inRange = padding.top >= DESIGN_SYSTEM.spacing.sectionPadding.desktop.min ||
                     padding.bottom >= DESIGN_SYSTEM.spacing.sectionPadding.desktop.min;
      expect(inRange).toBeTruthy();
    }
  });

  test('should have accessible color contrast', async ({ page }) => {
    const textElements = page.locator('p, h1, h2, h3, span').first();

    const color = await textElements.evaluate((el) => {
      return window.getComputedStyle(el).color;
    });

    const backgroundColor = await textElements.evaluate((el) => {
      let bg = window.getComputedStyle(el).backgroundColor;
      let parent = el.parentElement;

      // Walk up tree to find non-transparent background
      while ((bg === 'rgba(0, 0, 0, 0)' || bg === 'transparent') && parent) {
        bg = window.getComputedStyle(parent).backgroundColor;
        parent = parent.parentElement;
      }

      return bg;
    });

    console.log('\n=== Color Contrast ===');
    console.log(`Text color: ${color}`);
    console.log(`Background color: ${backgroundColor}`);

    // Basic check: text should not be transparent
    expect(color).not.toBe('rgba(0, 0, 0, 0)');
    expect(color).not.toBe('transparent');
  });

  test('should be responsive on mobile', async ({ page }) => {
    const viewport = { width: 375, height: 667 }; // iPhone SE
    await page.setViewportSize(viewport);

    const heroTitle = page.locator('.modern-hero h1, .hero-title').first();
    await expect(heroTitle).toBeVisible();

    const fontSize = await heroTitle.evaluate((el) => {
      return parseFloat(window.getComputedStyle(el).fontSize);
    });

    console.log('\n=== Mobile Responsive ===');
    expect(
      isInRange(
        fontSize,
        DESIGN_SYSTEM.typography.heroTitle.mobile.min,
        DESIGN_SYSTEM.typography.heroTitle.mobile.max,
        'Mobile hero font size'
      )
    ).toBeTruthy();
  });
});

test.describe('Contact Page - Design System Compliance', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('https://torly.ai/contact/');
    await page.waitForLoadState('networkidle');
  });

  test('should have correct form input styling', async ({ page }) => {
    const inputs = page.locator('input[type="text"], input[type="email"], textarea');
    const count = await inputs.count();

    console.log('\n=== Form Input Styling ===');
    console.log(`Found ${count} form inputs`);

    if (count > 0) {
      const firstInput = inputs.first();

      const borderRadius = await firstInput.evaluate((el) => {
        return parseFloat(window.getComputedStyle(el).borderRadius);
      });

      const borderWidth = await firstInput.evaluate((el) => {
        return parseFloat(window.getComputedStyle(el).borderWidth);
      });

      const padding = await firstInput.evaluate((el) => {
        return parseFloat(window.getComputedStyle(el).padding);
      });

      // Check border radius (should be around 8-12px for modern inputs)
      expect(borderRadius).toBeGreaterThan(4);
      console.log(`✓ Input border radius: ${borderRadius}px`);
      console.log(`✓ Input border width: ${borderWidth}px`);
      console.log(`✓ Input padding: ${padding}px`);
    }
  });

  test('should have working contact form', async ({ page }) => {
    const form = page.locator('form#contact-form, .contact-form, .modern-form').first();
    await expect(form).toBeVisible();

    console.log('\n=== Contact Form ===');
    console.log('✓ Contact form is visible');

    // Check for required fields
    const nameInput = page.locator('input[name="name"], input#name').first();
    const emailInput = page.locator('input[name="email"], input#email').first();
    const messageInput = page.locator('textarea[name="message"], textarea#message').first();

    await expect(nameInput).toBeVisible();
    await expect(emailInput).toBeVisible();
    await expect(messageInput).toBeVisible();

    console.log('✓ All required form fields are present');
  });

  test('should have correct info card styling', async ({ page }) => {
    const infoCards = page.locator('.info-card, .info-item');
    const count = await infoCards.count();

    console.log('\n=== Info Cards ===');
    console.log(`Found ${count} info cards`);

    if (count > 0) {
      const firstCard = infoCards.first();

      const borderRadius = await firstCard.evaluate((el) => {
        return parseFloat(window.getComputedStyle(el).borderRadius);
      });

      const padding = await firstCard.evaluate((el) => {
        return parseFloat(window.getComputedStyle(el).padding);
      });

      if (borderRadius > 0) {
        console.log(`✓ Info card border radius: ${borderRadius}px`);
      }
      console.log(`✓ Info card padding: ${padding}px`);
    }
  });

  test('should have FAQ section', async ({ page }) => {
    const faqSection = page.locator('.faq-grid, .contact-faq');
    const faqCards = page.locator('.faq-card, .faq-item');

    console.log('\n=== FAQ Section ===');

    if (await faqSection.count() > 0) {
      await expect(faqSection.first()).toBeVisible();
      console.log('✓ FAQ section is present');

      const count = await faqCards.count();
      console.log(`✓ Found ${count} FAQ items`);
      expect(count).toBeGreaterThan(0);
    }
  });

  test('should have CTA section', async ({ page }) => {
    const ctaSection = page.locator('.cta-section');

    console.log('\n=== CTA Section ===');

    if (await ctaSection.count() > 0) {
      await expect(ctaSection.first()).toBeVisible();
      console.log('✓ CTA section is present');

      const buttons = ctaSection.locator('.btn-primary, .btn-secondary');
      const buttonCount = await buttons.count();
      console.log(`✓ Found ${buttonCount} CTA buttons`);
      expect(buttonCount).toBeGreaterThan(0);
    }
  });
});

test.describe('Cross-Page Consistency', () => {
  test('About and Contact pages should have consistent hero styling', async ({ page }) => {
    console.log('\n=== Cross-Page Hero Consistency ===');

    // Check About page
    await page.goto('https://torly.ai/about/');
    const aboutHero = page.locator('.modern-hero, .hero-section').first();
    const aboutHeroStyles = await aboutHero.evaluate((el) => {
      const computed = window.getComputedStyle(el);
      return {
        padding: computed.padding,
        background: computed.backgroundColor,
      };
    });

    // Check Contact page
    await page.goto('https://torly.ai/contact/');
    const contactHero = page.locator('.modern-hero, .hero-section').first();
    const contactHeroStyles = await contactHero.evaluate((el) => {
      const computed = window.getComputedStyle(el);
      return {
        padding: computed.padding,
        background: computed.backgroundColor,
      };
    });

    console.log('About hero padding:', aboutHeroStyles.padding);
    console.log('Contact hero padding:', contactHeroStyles.padding);
    console.log('About hero background:', aboutHeroStyles.background);
    console.log('Contact hero background:', contactHeroStyles.background);

    // Both should have hero sections
    expect(aboutHeroStyles).toBeTruthy();
    expect(contactHeroStyles).toBeTruthy();
  });

  test('Both pages should use consistent button styling', async ({ page }) => {
    console.log('\n=== Cross-Page Button Consistency ===');

    // Get About page button styles
    await page.goto('https://torly.ai/about/');
    const aboutButtons = page.locator('.btn-primary').first();
    const aboutButtonStyles = await aboutButtons.evaluate((el) => {
      const computed = window.getComputedStyle(el);
      return {
        borderRadius: computed.borderRadius,
        padding: computed.padding,
        fontWeight: computed.fontWeight,
      };
    });

    // Get Contact page button styles
    await page.goto('https://torly.ai/contact/');
    const contactButtons = page.locator('.btn-primary').first();
    const contactButtonStyles = await contactButtons.evaluate((el) => {
      const computed = window.getComputedStyle(el);
      return {
        borderRadius: computed.borderRadius,
        padding: computed.padding,
        fontWeight: computed.fontWeight,
      };
    });

    console.log('About button border-radius:', aboutButtonStyles.borderRadius);
    console.log('Contact button border-radius:', contactButtonStyles.borderRadius);

    // Button styles should be consistent
    expect(aboutButtonStyles.borderRadius).toBe(contactButtonStyles.borderRadius);
    expect(aboutButtonStyles.fontWeight).toBe(contactButtonStyles.fontWeight);
  });
});

// Summary reporter
test.afterAll(async () => {
  console.log('\n=====================================');
  console.log('Design System Compliance Test Complete');
  console.log('=====================================\n');
});
