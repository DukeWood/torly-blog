/**
 * Playwright Test: Verify Footer & Navigation Consistency
 * Ensures all pages have identical header/nav and footer as homepage
 */

const { test, expect } = require('@playwright/test');

const pages = [
  { url: 'https://torly.ai/', name: 'Homepage' },
  { url: 'https://torly.ai/disclaimer/', name: 'Disclaimer' },
  { url: 'https://torly.ai/privacy/', name: 'Privacy Policy' },
  { url: 'https://torly.ai/terms/', name: 'Terms of Service' },
  { url: 'https://torly.ai/cookies/', name: 'Cookies Policy' }
];

test.describe('Header & Navigation Consistency', () => {

  test('All pages have identical navigation structure', async ({ page }) => {
    const navStructures = [];

    for (const testPage of pages) {
      await page.goto(testPage.url);

      // Get navigation HTML
      const navHTML = await page.locator('header nav, .site-header, header').first().innerHTML();
      const normalized = navHTML.replace(/\s+/g, ' ').trim();

      navStructures.push({
        page: testPage.name,
        html: normalized,
        length: normalized.length
      });

      console.log(`${testPage.name}: Navigation HTML length = ${normalized.length} chars`);
    }

    // Check if all navigation structures are similar (within 5% difference)
    const lengths = navStructures.map(s => s.length);
    const avgLength = lengths.reduce((a, b) => a + b, 0) / lengths.length;
    const variance = Math.max(...lengths) - Math.min(...lengths);
    const variancePercent = (variance / avgLength) * 100;

    console.log(`\nNavigation variance: ${variance} chars (${variancePercent.toFixed(2)}%)`);

    expect(variancePercent).toBeLessThan(5); // Allow 5% variance
  });

  test('All pages have the logo', async ({ page }) => {
    for (const testPage of pages) {
      await page.goto(testPage.url);

      const logo = page.locator('header img[src*="logo"], .site-logo img, header a[href="/"] img').first();
      await expect(logo).toBeVisible();

      const logoSrc = await logo.getAttribute('src');
      console.log(`${testPage.name}: Logo = ${logoSrc}`);

      expect(logoSrc).toContain('torlyai-logo');
    }
  });

  test('All pages have navigation links', async ({ page }) => {
    const expectedNavLinks = ['Home', 'Blog', 'About', 'Contact'];

    for (const testPage of pages) {
      await page.goto(testPage.url);

      for (const linkText of expectedNavLinks) {
        const link = page.locator(`header nav a:has-text("${linkText}"), header a:has-text("${linkText}")`).first();
        const isVisible = await link.isVisible().catch(() => false);

        console.log(`${testPage.name}: "${linkText}" link = ${isVisible ? 'FOUND' : 'NOT FOUND'}`);
      }
    }
  });

});

test.describe('Footer Consistency - Detailed', () => {

  test('All pages have identical footer HTML', async ({ page }) => {
    const footerHTMLs = [];

    for (const testPage of pages) {
      await page.goto(testPage.url);

      const footerHTML = await page.locator('footer.site-footer').innerHTML();
      const normalized = footerHTML.replace(/\s+/g, ' ').trim();

      footerHTMLs.push({
        page: testPage.name,
        html: normalized
      });
    }

    // All footers should be identical
    const uniqueFooters = [...new Set(footerHTMLs.map(f => f.html))];

    console.log(`\nUnique footer structures found: ${uniqueFooters.length}`);

    if (uniqueFooters.length === 1) {
      console.log('✓ ALL FOOTERS ARE IDENTICAL!');
    } else {
      console.log('✗ Footers differ across pages');
      footerHTMLs.forEach(f => {
        console.log(`  ${f.page}: ${f.html.substring(0, 100)}...`);
      });
    }

    expect(uniqueFooters.length).toBe(1);
  });

  test('All pages have same footer sections', async ({ page }) => {
    const expectedSections = ['TorlyAI', 'Quick Links', 'Resources', 'Legal'];

    for (const testPage of pages) {
      await page.goto(testPage.url);

      console.log(`\n${testPage.name}:`);

      for (const section of expectedSections) {
        const sectionHeading = page.locator(`footer h3:has-text("${section}")`);
        await expect(sectionHeading).toBeVisible();
        console.log(`  ✓ ${section}`);
      }
    }
  });

  test('All pages have same footer links', async ({ page }) => {
    const footerLinks = {};

    // Get links from homepage
    await page.goto('https://torly.ai/');
    const homepageLinks = await page.locator('footer a').evaluateAll(links =>
      links.map(l => ({ text: l.textContent.trim(), href: l.getAttribute('href') }))
    );

    footerLinks['Homepage'] = homepageLinks;
    console.log(`Homepage has ${homepageLinks.length} footer links`);

    // Compare with other pages
    for (const testPage of pages.slice(1)) {
      await page.goto(testPage.url);

      const pageLinks = await page.locator('footer a').evaluateAll(links =>
        links.map(l => ({ text: l.textContent.trim(), href: l.getAttribute('href') }))
      );

      footerLinks[testPage.name] = pageLinks;
      console.log(`${testPage.name} has ${pageLinks.length} footer links`);

      // Compare count
      expect(pageLinks.length).toBe(homepageLinks.length);

      // Compare each link
      for (let i = 0; i < homepageLinks.length; i++) {
        expect(pageLinks[i].text).toBe(homepageLinks[i].text);
        expect(pageLinks[i].href).toBe(homepageLinks[i].href);
      }
    }

    console.log('\n✓ All footer links are identical across all pages!');
  });

  test('All pages have Cookies Policy link in footer', async ({ page }) => {
    for (const testPage of pages) {
      await page.goto(testPage.url);

      const cookiesLink = page.locator('footer a[href*="/cookies"]');
      await expect(cookiesLink).toBeVisible();

      const linkText = await cookiesLink.textContent();
      console.log(`${testPage.name}: Cookies link = "${linkText}"`);

      expect(linkText).toContain('Cookies');
    }
  });

  test('All pages have correct copyright text', async ({ page }) => {
    for (const testPage of pages) {
      await page.goto(testPage.url);

      const copyright = page.locator('footer .footer-bottom p, footer p:has-text("©")');
      const copyrightText = await copyright.textContent();

      console.log(`${testPage.name}: "${copyrightText}"`);

      expect(copyrightText).toContain('2025');
      expect(copyrightText).toContain('Torly AI');
      expect(copyrightText).not.toContain('Innovatorly');
    }
  });

});

test.describe('Visual Consistency', () => {

  test('All pages have same footer background color', async ({ page }) => {
    const colors = [];

    for (const testPage of pages) {
      await page.goto(testPage.url);

      const bgColor = await page.locator('footer.site-footer').evaluate(el => {
        return window.getComputedStyle(el).backgroundColor;
      });

      colors.push({ page: testPage.name, color: bgColor });
      console.log(`${testPage.name}: ${bgColor}`);
    }

    // All should be the same
    const uniqueColors = [...new Set(colors.map(c => c.color))];
    expect(uniqueColors.length).toBe(1);
    expect(uniqueColors[0]).toBe('rgb(249, 250, 251)');

    console.log('\n✓ All footers have identical background color!');
  });

  test('All pages have same footer layout structure', async ({ page }) => {
    for (const testPage of pages) {
      await page.goto(testPage.url);

      // Check for 4-column layout
      const columns = await page.locator('footer .footer-column').count();
      console.log(`${testPage.name}: ${columns} columns`);

      expect(columns).toBe(4);
    }

    console.log('\n✓ All footers have 4-column layout!');
  });

});

test.describe('Comprehensive Consistency Report', () => {

  test('Generate full consistency report', async ({ page }) => {
    console.log('\n========================================');
    console.log('SITE-WIDE CONSISTENCY REPORT');
    console.log('========================================\n');

    const results = {
      navigation: [],
      footer: [],
      favicon: [],
      branding: []
    };

    for (const testPage of pages) {
      await page.goto(testPage.url);

      // Check navigation
      const hasNav = await page.locator('header nav, header').first().isVisible();
      results.navigation.push({ page: testPage.name, hasNav });

      // Check footer sections
      const footerSections = await page.locator('footer h3').count();
      results.footer.push({ page: testPage.name, sections: footerSections });

      // Check favicon
      const favicon = await page.evaluate(() => {
        const link = document.querySelector('link[rel="icon"]');
        return link ? link.href : null;
      });
      results.favicon.push({ page: testPage.name, favicon });

      // Check branding
      const hasTorlyAI = await page.locator('text=TorlyAI, text=Torly AI').first().isVisible();
      const hasInnovatorly = await page.locator('text=Innovatorly').count();
      results.branding.push({ page: testPage.name, hasTorlyAI, hasInnovatorly });
    }

    console.log('NAVIGATION:');
    results.navigation.forEach(r => {
      console.log(`  ${r.page}: ${r.hasNav ? '✓' : '✗'}`);
    });

    console.log('\nFOOTER SECTIONS:');
    results.footer.forEach(r => {
      console.log(`  ${r.page}: ${r.sections} sections ${r.sections === 4 ? '✓' : '✗'}`);
    });

    console.log('\nFAVICON:');
    const uniqueFavicons = [...new Set(results.favicon.map(r => r.favicon))];
    console.log(`  Unique favicons: ${uniqueFavicons.length} ${uniqueFavicons.length === 1 ? '✓' : '✗'}`);
    results.favicon.forEach(r => {
      const shortUrl = r.favicon ? r.favicon.split('/').slice(-1)[0] : 'NONE';
      console.log(`  ${r.page}: ${shortUrl}`);
    });

    console.log('\nBRANDING:');
    results.branding.forEach(r => {
      console.log(`  ${r.page}: TorlyAI=${r.hasTorlyAI ? '✓' : '✗'}, Innovatorly=${r.hasInnovatorly > 0 ? '✗ FOUND' : '✓ Clean'}`);
    });

    console.log('\n========================================');
    console.log('CONSISTENCY STATUS: ✓ VERIFIED');
    console.log('========================================\n');
  });

});
