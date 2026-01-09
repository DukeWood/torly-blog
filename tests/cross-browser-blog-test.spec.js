const { test, expect, chromium, firefox, webkit } = require('@playwright/test');

test.describe('Cross-Browser Blog Navigation Test', () => {
    const browsers = [
        { name: 'Chromium (Chrome/Edge)', type: chromium },
        { name: 'Firefox', type: firefox },
        { name: 'WebKit (Safari)', type: webkit }
    ];

    for (const browserConfig of browsers) {
        test(`${browserConfig.name}: Blog navigation should work correctly`, async () => {
            const browser = await browserConfig.type.launch({ headless: false });
            const context = await browser.newContext({
                // Clear all caches and storage
                storageState: undefined
            });
            const page = await context.newPage();

            console.log(`\n=== Testing ${browserConfig.name} ===`);

            // Step 1: Navigate to homepage
            console.log('1. Loading homepage...');
            await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });
            console.log('✓ Homepage loaded');

            // Step 2: Check blog link href
            const blogLink = await page.locator('header nav a[href*="blog"]').first();
            const href = await blogLink.getAttribute('href');
            console.log(`2. Blog link href: ${href}`);
            expect(href).toBe('https://torly.ai/blog/');
            console.log('✓ Blog link href is correct');

            // Step 3: Click blog link
            console.log('3. Clicking Blog link...');
            await blogLink.click();
            await page.waitForLoadState('networkidle');

            // Step 4: Check final URL
            const finalUrl = page.url();
            console.log(`4. Final URL after click: ${finalUrl}`);
            console.log(`✓ Expected: https://torly.ai/blog/`);
            console.log(`✓ Actual: ${finalUrl}`);

            // Step 5: Verify not redirected to homepage
            expect(finalUrl).not.toBe('https://torly.ai/');
            expect(finalUrl).toContain('/blog');
            console.log('✓ NOT redirected to homepage');

            // Step 6: Check page title
            const title = await page.title();
            console.log(`5. Page title: ${title}`);
            expect(title).toContain('Blog');
            console.log('✓ Page title contains "Blog"');

            // Step 7: Check for blog content
            const blogContent = await page.locator('.blog-posts, article, .post').count();
            console.log(`6. Blog posts found: ${blogContent}`);
            expect(blogContent).toBeGreaterThan(0);
            console.log('✓ Blog content is visible');

            console.log(`\n=== ${browserConfig.name} TEST RESULT ===`);
            console.log('✅ Blog navigation works correctly');
            console.log('✅ Does NOT redirect to homepage');
            console.log('✅ Lands on /blog/ page successfully\n');

            await browser.close();
        });
    }

    test('Check for old subdomain references in HTML source', async ({ page }) => {
        console.log('\n=== Checking for old blog.torly.ai references ===');

        await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

        // Get full HTML source
        const htmlContent = await page.content();

        // Check for old subdomain references
        const hasOldSubdomain = htmlContent.includes('blog.torly.ai');

        console.log(`Checking HTML source for "blog.torly.ai"...`);
        if (hasOldSubdomain) {
            console.log('❌ FOUND old subdomain references in HTML');
            // Find all occurrences
            const matches = htmlContent.match(/blog\.torly\.ai/g);
            console.log(`Found ${matches.length} occurrences`);
        } else {
            console.log('✅ No old subdomain references found');
        }

        expect(hasOldSubdomain).toBe(false);
    });

    test('Test with cleared cache and cookies', async ({ browser }) => {
        console.log('\n=== Testing with completely fresh browser context ===');

        const context = await browser.newContext({
            storageState: undefined,
            ignoreHTTPSErrors: false,
            extraHTTPHeaders: {
                'Cache-Control': 'no-cache',
                'Pragma': 'no-cache'
            }
        });

        const page = await context.newPage();

        // Clear all caches
        await context.clearCookies();

        console.log('1. Fresh context created (no cache, no cookies)');

        await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });
        console.log('2. Homepage loaded');

        const blogLink = await page.locator('header nav a[href*="blog"]').first();
        await blogLink.click();
        await page.waitForLoadState('networkidle');

        const finalUrl = page.url();
        console.log(`3. Final URL: ${finalUrl}`);

        expect(finalUrl).toContain('/blog');
        expect(finalUrl).not.toBe('https://torly.ai/');

        console.log('✅ Blog navigation works with fresh cache');

        await context.close();
    });
});
