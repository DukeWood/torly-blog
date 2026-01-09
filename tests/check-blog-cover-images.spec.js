const { test, expect } = require('@playwright/test');

test.describe('Blog Cover Image Check', () => {
    test('Extract all blog posts and their cover images', async ({ page }) => {
        console.log('\n═══════════════════════════════════════════════════════════');
        console.log('         BLOG COVER IMAGE VERIFICATION        ');
        console.log('═══════════════════════════════════════════════════════════\n');

        // Navigate to blog page
        await page.goto('https://torly.ai/blog/', { waitUntil: 'networkidle' });
        console.log('✓ Loaded blog page\n');

        // Get all blog post articles
        const articles = await page.locator('article').all();
        console.log(`Found ${articles.length} blog posts\n`);

        const posts = [];

        for (let i = 0; i < articles.length; i++) {
            const article = articles[i];

            // Get post title
            const titleElement = await article.locator('h2, h3, .post-title').first();
            const title = await titleElement.textContent();

            // Get post link
            const linkElement = await article.locator('a[href*="/blog/"]').first();
            const href = await linkElement.getAttribute('href');

            // Get featured image
            const imgElement = await article.locator('img').first();
            const imgSrc = imgElement ? await imgElement.getAttribute('src') : null;
            const imgAlt = imgElement ? await imgElement.getAttribute('alt') : null;

            posts.push({
                index: i + 1,
                title: title?.trim(),
                url: href,
                imageSrc: imgSrc,
                imageAlt: imgAlt
            });

            console.log(`Post ${i + 1}:`);
            console.log(`  Title: ${title?.trim()}`);
            console.log(`  URL: ${href}`);
            console.log(`  Image: ${imgSrc}`);
            console.log(`  Alt: ${imgAlt}\n`);
        }

        // Check for duplicate images
        console.log('═══════════════════════════════════════════════════════════');
        console.log('         DUPLICATE IMAGE ANALYSIS        ');
        console.log('═══════════════════════════════════════════════════════════\n');

        const imageSources = posts.map(p => p.imageSrc).filter(Boolean);
        const uniqueImages = new Set(imageSources);

        console.log(`Total posts with images: ${imageSources.length}`);
        console.log(`Unique images: ${uniqueImages.size}`);
        console.log(`Duplicate images: ${imageSources.length - uniqueImages.size}\n`);

        if (imageSources.length !== uniqueImages.size) {
            console.log('⚠️  DUPLICATES DETECTED!\n');

            // Find which images are duplicated
            const imageCount = {};
            imageSources.forEach(src => {
                imageCount[src] = (imageCount[src] || 0) + 1;
            });

            console.log('Duplicate image URLs:');
            Object.entries(imageCount).forEach(([src, count]) => {
                if (count > 1) {
                    console.log(`  ${src} (used ${count} times)`);

                    // Find which posts use this image
                    const postsWithImage = posts.filter(p => p.imageSrc === src);
                    postsWithImage.forEach(post => {
                        console.log(`    - "${post.title}"`);
                    });
                    console.log('');
                }
            });
        } else {
            console.log('✅ All images are unique!\n');
        }

        console.log('═══════════════════════════════════════════════════════════');
        console.log('         IMAGE SOURCE ANALYSIS        ');
        console.log('═══════════════════════════════════════════════════════════\n');

        const unsplashImages = imageSources.filter(src => src?.includes('unsplash'));
        const localImages = imageSources.filter(src => src && !src.includes('unsplash') && !src.includes('http'));
        const themeImages = imageSources.filter(src => src?.includes('torly-theme'));

        console.log(`Unsplash images: ${unsplashImages.length}`);
        console.log(`Local images: ${localImages.length}`);
        console.log(`Theme images: ${themeImages.length}\n`);

        // Save data for further processing
        await page.evaluate((data) => {
            window.blogPostsData = data;
        }, posts);

        console.log('═══════════════════════════════════════════════════════════\n');

        // Export findings
        expect(posts.length).toBeGreaterThan(0);
    });

    test('Visit individual blog posts to check cover images', async ({ page }) => {
        console.log('\n═══════════════════════════════════════════════════════════');
        console.log('         INDIVIDUAL POST IMAGE CHECK        ');
        console.log('═══════════════════════════════════════════════════════════\n');

        // First get all post URLs
        await page.goto('https://torly.ai/blog/', { waitUntil: 'networkidle' });

        const postLinks = await page.locator('article a[href*="/blog/"]').evaluateAll(links =>
            [...new Set(links.map(link => link.href))]
        );

        console.log(`Found ${postLinks.length} unique post URLs\n`);

        const postDetails = [];

        for (let i = 0; i < postLinks.length; i++) {
            const url = postLinks[i];
            console.log(`Post ${i + 1}: ${url}`);

            await page.goto(url, { waitUntil: 'networkidle' });

            // Get post title
            const title = await page.locator('h1').first().textContent();

            // Get featured image (could be in article, header, or featured image div)
            const featuredImg = await page.locator('article img, .featured-image img, .post-thumbnail img').first();
            const imgSrc = featuredImg ? await featuredImg.getAttribute('src') : null;

            postDetails.push({
                index: i + 1,
                title: title?.trim(),
                url: url,
                featuredImage: imgSrc
            });

            console.log(`  Title: ${title?.trim()}`);
            console.log(`  Featured Image: ${imgSrc}\n`);
        }

        // Check for duplicates in individual posts
        const images = postDetails.map(p => p.featuredImage).filter(Boolean);
        const uniqueImages = new Set(images);

        console.log('═══════════════════════════════════════════════════════════');
        console.log(`Total posts checked: ${postDetails.length}`);
        console.log(`Posts with images: ${images.length}`);
        console.log(`Unique images: ${uniqueImages.size}`);
        console.log(`Duplicates: ${images.length - uniqueImages.size}`);

        if (images.length !== uniqueImages.size) {
            console.log('\n⚠️  DUPLICATE IMAGES FOUND IN INDIVIDUAL POSTS\n');
        } else {
            console.log('\n✅ All post images are unique\n');
        }

        console.log('═══════════════════════════════════════════════════════════\n');

        expect(postDetails.length).toBeGreaterThan(0);
    });
});
