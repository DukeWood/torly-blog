const { test, expect } = require('@playwright/test');

test.describe('Blog Navigation Verification', () => {
  test('clicking Blog nav should route to /blog/ not homepage', async ({ page }) => {
    // Navigate to homepage
    await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

    console.log('✓ Homepage loaded');

    // Find and click the Blog navigation link
    const blogLink = page.locator('nav a:has-text("Blog")');
    await expect(blogLink).toBeVisible();

    const href = await blogLink.getAttribute('href');
    console.log(`✓ Blog link href: ${href}`);

    // Click the blog link
    await blogLink.click();

    // Wait for navigation to complete
    await page.waitForLoadState('networkidle');

    // Get the final URL
    const finalUrl = page.url();
    console.log(`✓ Final URL after click: ${finalUrl}`);

    // Verify we're on the blog page, NOT the homepage
    expect(finalUrl).toBe('https://torly.ai/blog/');
    expect(finalUrl).not.toBe('https://torly.ai/');

    // Verify page title indicates blog page
    const title = await page.title();
    console.log(`✓ Page title: ${title}`);
    expect(title).toContain('Blog');

    // Verify blog content is displayed (check for posts or blog-specific elements)
    const blogContent = page.locator('.blog-posts, article, .post');
    await expect(blogContent.first()).toBeVisible({ timeout: 5000 });

    console.log('✓ Blog content is visible');

    console.log('\n=== TEST RESULT ===');
    console.log('✅ Blog navigation works correctly');
    console.log('✅ Does NOT redirect to homepage');
    console.log('✅ Lands on /blog/ page successfully');
  });

  test('blog link href is correct', async ({ page }) => {
    await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

    // Check header blog link
    const headerBlogLink = page.locator('nav a:has-text("Blog")');
    const headerHref = await headerBlogLink.getAttribute('href');

    console.log(`Header Blog link: ${headerHref}`);
    expect(headerHref).toBe('https://torly.ai/blog/');
    expect(headerHref).not.toContain('blog.torly.ai');

    console.log('✅ Blog link href is correct (no old subdomain references)');
  });
});
