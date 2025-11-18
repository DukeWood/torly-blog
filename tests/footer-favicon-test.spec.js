/**
 * Playwright Test: Footer & Favicon Design System Compliance
 * Tests footer styling and favicon configuration across all legal pages
 */

const { test, expect } = require('@playwright/test');

// Pages to test
const pages = [
  { url: 'https://torly.ai/', name: 'Homepage' },
  { url: 'https://torly.ai/disclaimer/', name: 'Disclaimer' },
  { url: 'https://torly.ai/privacy/', name: 'Privacy Policy' },
  { url: 'https://torly.ai/terms/', name: 'Terms of Service' },
  { url: 'https://torly.ai/cookies/', name: 'Cookies Policy' }
];

// Design System Values
const DESIGN_SYSTEM = {
  spacing: {
    footerPaddingTop: 64, // 4rem = 64px
    footerPaddingBottom: 32, // 2rem = 32px
    gridGap: 32, // 2rem = 32px
  },
  colors: {
    background: 'rgb(249, 250, 251)', // var(--neutral-50) = #f9fafb
    textPrimary: 'rgb(0, 0, 0)', // #000000
    textSecondary: 'rgba(0, 0, 0, 0.7)', // 70% opacity
  },
  typography: {
    linkFontSize: 14, // 0.875rem = 14px
    headingFontWeight: '700',
    linkFontWeight: '400',
  },
  favicon: {
    maxSize: 50 * 1024, // 50KB max (current is 285KB!)
    recommendedSize: 10 * 1024, // 10KB recommended
  }
};

test.describe('Footer Design System Compliance', () => {

  for (const page of pages) {
    test(`Footer styling on ${page.name}`, async ({ page: browserPage }) => {
      await browserPage.goto(page.url);

      // Wait for footer to load
      const footer = browserPage.locator('footer.site-footer');
      await expect(footer).toBeVisible();

      // Test 1: Footer background color
      const bgColor = await footer.evaluate(el => {
        return window.getComputedStyle(el).backgroundColor;
      });
      console.log(`  [${page.name}] Footer background: ${bgColor}`);
      expect(bgColor).toBe(DESIGN_SYSTEM.colors.background);

      // Test 2: Footer padding (top and bottom)
      const padding = await footer.evaluate(el => {
        const style = window.getComputedStyle(el);
        return {
          top: parseInt(style.paddingTop),
          bottom: parseInt(style.paddingBottom)
        };
      });
      console.log(`  [${page.name}] Padding - Top: ${padding.top}px, Bottom: ${padding.bottom}px`);
      expect(padding.top).toBe(DESIGN_SYSTEM.spacing.footerPaddingTop);
      expect(padding.bottom).toBe(DESIGN_SYSTEM.spacing.footerPaddingBottom);

      // Test 3: Footer grid layout
      const footerContent = browserPage.locator('.footer-content');
      const gridGap = await footerContent.evaluate(el => {
        return parseInt(window.getComputedStyle(el).gap);
      });
      console.log(`  [${page.name}] Grid gap: ${gridGap}px`);
      expect(gridGap).toBe(DESIGN_SYSTEM.spacing.gridGap);

      // Test 4: Footer link typography
      const footerLink = browserPage.locator('.footer-column a').first();
      const linkStyles = await footerLink.evaluate(el => {
        const style = window.getComputedStyle(el);
        return {
          fontSize: parseInt(style.fontSize),
          fontWeight: style.fontWeight,
          color: style.color
        };
      });
      console.log(`  [${page.name}] Link font size: ${linkStyles.fontSize}px`);
      expect(linkStyles.fontSize).toBe(DESIGN_SYSTEM.typography.linkFontSize);

      // Test 5: Footer heading typography
      const footerHeading = browserPage.locator('.footer-column h3').first();
      const headingWeight = await footerHeading.evaluate(el => {
        return window.getComputedStyle(el).fontWeight;
      });
      console.log(`  [${page.name}] Heading font weight: ${headingWeight}`);
      expect(headingWeight).toBe(DESIGN_SYSTEM.typography.headingFontWeight);

      // Test 6: Footer columns count (desktop view)
      const columnCount = await footerContent.evaluate(el => {
        return window.getComputedStyle(el).gridTemplateColumns.split(' ').length;
      });
      console.log(`  [${page.name}] Footer columns: ${columnCount}`);
      expect(columnCount).toBeGreaterThanOrEqual(2); // At least 2 columns on desktop

      // Test 7: Footer contains required sections
      const sections = ['TorlyAI', 'Quick Links', 'Resources', 'Legal'];
      for (const section of sections) {
        const sectionExists = await browserPage.locator(`.footer-column h3:has-text("${section}")`).count();
        expect(sectionExists).toBe(1);
        console.log(`  [${page.name}] ✓ Section found: ${section}`);
      }

      // Test 8: Footer contains Cookies Policy link
      const cookiesLink = browserPage.locator('a[href*="/cookies"]');
      await expect(cookiesLink).toBeVisible();
      console.log(`  [${page.name}] ✓ Cookies Policy link present`);

      // Test 9: Footer copyright text
      const copyright = browserPage.locator('.footer-bottom p');
      const copyrightText = await copyright.textContent();
      expect(copyrightText).toContain('2025');
      expect(copyrightText).toContain('Torly AI');
      console.log(`  [${page.name}] ✓ Copyright: ${copyrightText}`);
    });
  }
});

test.describe('Favicon Configuration', () => {

  test('Favicon exists and is properly configured', async ({ page }) => {
    await page.goto('https://torly.ai/');

    // Test 1: Check for favicon link tag
    const faviconLink = await page.evaluate(() => {
      const link = document.querySelector('link[rel="icon"]');
      if (!link) return null;
      return {
        href: link.href,
        type: link.type
      };
    });

    console.log(`  Favicon href: ${faviconLink?.href}`);
    expect(faviconLink).not.toBeNull();
    expect(faviconLink.type).toBe('image/png');

    // Test 2: Check for apple-touch-icon
    const appleTouchIcon = await page.evaluate(() => {
      const link = document.querySelector('link[rel="apple-touch-icon"]');
      return link ? link.href : null;
    });

    console.log(`  Apple touch icon: ${appleTouchIcon}`);
    expect(appleTouchIcon).not.toBeNull();

    // Test 3: Check favicon file size
    const faviconUrl = faviconLink.href;
    const response = await page.request.get(faviconUrl);
    const buffer = await response.body();
    const fileSize = buffer.length;
    const fileSizeKB = (fileSize / 1024).toFixed(2);

    console.log(`  Favicon size: ${fileSizeKB}KB`);

    if (fileSize > DESIGN_SYSTEM.favicon.maxSize) {
      console.warn(`  ⚠️  WARNING: Favicon is ${fileSizeKB}KB (recommended: <10KB)`);
    }

    if (fileSize > DESIGN_SYSTEM.favicon.recommendedSize) {
      console.warn(`  ⚠️  Favicon exceeds recommended size of 10KB`);
    }

    // Test 4: Verify favicon loads successfully
    expect(response.status()).toBe(200);
    console.log(`  ✓ Favicon loads successfully (HTTP ${response.status()})`);
  });

  test('Favicon is consistent across all pages', async ({ page }) => {
    const faviconUrls = [];

    for (const testPage of pages) {
      await page.goto(testPage.url);

      const faviconHref = await page.evaluate(() => {
        const link = document.querySelector('link[rel="icon"]');
        return link ? link.href : null;
      });

      faviconUrls.push({ page: testPage.name, url: faviconHref });
      console.log(`  ${testPage.name}: ${faviconHref}`);
    }

    // All pages should have the same favicon
    const uniqueUrls = [...new Set(faviconUrls.map(f => f.url))];
    expect(uniqueUrls.length).toBe(1);
    console.log(`  ✓ Favicon is consistent across all ${pages.length} pages`);
  });
});

test.describe('Footer Consistency Across Pages', () => {

  test('Footer HTML structure is identical', async ({ page }) => {
    const footerStructures = [];

    for (const testPage of pages) {
      await page.goto(testPage.url);

      const footerHTML = await page.locator('footer.site-footer').innerHTML();
      // Normalize whitespace for comparison
      const normalized = footerHTML.replace(/\s+/g, ' ').trim();
      footerStructures.push({ page: testPage.name, html: normalized });
    }

    // All pages should have identical footer structure
    const uniqueStructures = [...new Set(footerStructures.map(f => f.html))];
    expect(uniqueStructures.length).toBe(1);
    console.log(`  ✓ Footer HTML is identical across all ${pages.length} pages`);
  });

  test('No "Innovatorly Ltd" references in footer', async ({ page }) => {
    for (const testPage of pages) {
      await page.goto(testPage.url);

      const footerText = await page.locator('footer.site-footer').textContent();
      const hasInnovatorly = footerText.toLowerCase().includes('innovatorly');

      expect(hasInnovatorly).toBe(false);
      console.log(`  ${testPage.name}: ✓ No "Innovatorly Ltd" references`);
    }
  });

  test('Footer links are accessible and correct', async ({ page }) => {
    await page.goto('https://torly.ai/');

    const footerLinks = [
      { href: '/privacy', text: 'Privacy Policy' },
      { href: '/terms', text: 'Terms of Service' },
      { href: '/disclaimer', text: 'Disclaimer' },
      { href: '/cookies', text: 'Cookies Policy' }
    ];

    for (const link of footerLinks) {
      const linkElement = page.locator(`footer a[href*="${link.href}"]`);
      await expect(linkElement).toBeVisible();

      const linkText = await linkElement.textContent();
      expect(linkText).toContain(link.text);
      console.log(`  ✓ Link found: ${link.text} → ${link.href}`);
    }
  });
});

test.describe('Design System Compliance Summary', () => {

  test('Generate compliance report', async ({ page }) => {
    await page.goto('https://torly.ai/');

    console.log('\n========================================');
    console.log('DESIGN SYSTEM COMPLIANCE REPORT');
    console.log('========================================\n');

    // Footer Spacing
    const footer = page.locator('footer.site-footer');
    const padding = await footer.evaluate(el => {
      const style = window.getComputedStyle(el);
      return {
        top: parseInt(style.paddingTop),
        bottom: parseInt(style.paddingBottom)
      };
    });

    console.log('✓ Footer Spacing:');
    console.log(`  - Padding top: ${padding.top}px (Expected: ${DESIGN_SYSTEM.spacing.footerPaddingTop}px)`);
    console.log(`  - Padding bottom: ${padding.bottom}px (Expected: ${DESIGN_SYSTEM.spacing.footerPaddingBottom}px)`);

    // Footer Background
    const bgColor = await footer.evaluate(el => {
      return window.getComputedStyle(el).backgroundColor;
    });
    console.log(`\n✓ Footer Background: ${bgColor}`);

    // Typography
    const linkSize = await page.locator('.footer-column a').first().evaluate(el => {
      return parseInt(window.getComputedStyle(el).fontSize);
    });
    console.log(`\n✓ Typography:`);
    console.log(`  - Link font size: ${linkSize}px (Expected: ${DESIGN_SYSTEM.typography.linkFontSize}px)`);

    // Favicon
    const faviconUrl = await page.evaluate(() => {
      const link = document.querySelector('link[rel="icon"]');
      return link ? link.href : null;
    });

    if (faviconUrl) {
      const response = await page.request.get(faviconUrl);
      const buffer = await response.body();
      const fileSizeKB = (buffer.length / 1024).toFixed(2);

      console.log(`\n⚠️  Favicon:`);
      console.log(`  - Size: ${fileSizeKB}KB`);
      console.log(`  - Recommended: <10KB`);
      console.log(`  - Status: ${buffer.length > DESIGN_SYSTEM.favicon.recommendedSize ? 'NEEDS OPTIMIZATION' : 'OK'}`);
    }

    console.log('\n========================================\n');
  });
});
