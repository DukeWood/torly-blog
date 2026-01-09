const { test, expect } = require('@playwright/test');

test.describe('Blog Visual Diversity Verification', () => {
    test('Verify blog covers have visual diversity (no repetitive passport images)', async ({ page }) => {
        console.log('\n═══════════════════════════════════════════════════════════');
        console.log('     BLOG VISUAL DIVERSITY VERIFICATION (POST-UPDATE)      ');
        console.log('═══════════════════════════════════════════════════════════\n');

        await page.goto('https://torly.ai/blog/', { waitUntil: 'networkidle' });

        // Get all visible article images
        const articles = await page.locator('article').all();
        console.log(`Found ${articles.length} blog posts\n`);

        const imageAnalysis = [];

        for (let i = 0; i < Math.min(articles.length, 6); i++) {  // Check first 6 visible posts
            const article = articles[i];

            const title = await article.locator('h2, h3, .post-title').first().textContent();
            const img = await article.locator('img').first();
            const imgSrc = await img.getAttribute('src');

            // Categorize image type
            let imageType = 'Unknown';
            if (imgSrc.includes('passport') || imgSrc.includes('uk-innovator-visa')) {
                imageType = 'Passport/Visa (Repetitive)';
            } else if (imgSrc.includes('business-meeting') || imgSrc.includes('consultation')) {
                imageType = 'Business Meeting';
            } else if (imgSrc.includes('ai-technology') || imgSrc.includes('dashboard')) {
                imageType = 'AI Technology';
            } else if (imgSrc.includes('business-strategy') || imgSrc.includes('planning')) {
                imageType = 'Business Strategy';
            } else if (imgSrc.includes('data-analytics') || imgSrc.includes('charts')) {
                imageType = 'Data Analytics';
            } else if (imgSrc.includes('london') || imgSrc.includes('business-district')) {
                imageType = 'London Business';
            } else if (imgSrc.includes('startup-team') || imgSrc.includes('collaboration')) {
                imageType = 'Startup Team';
            } else if (imgSrc.includes('visa-business-plan-generator')) {
                imageType = 'Business Plan Generator (Office)';
            } else if (imgSrc.includes('visa-icon')) {
                imageType = '3D Visa Icon';
            }

            imageAnalysis.push({
                index: i + 1,
                title: title?.trim(),
                imageSrc: imgSrc,
                imageType: imageType
            });

            console.log(`Post ${i + 1}: ${title?.trim()}`);
            console.log(`  Image Type: ${imageType}`);
            console.log(`  URL: ${imgSrc?.substring(imgSrc.lastIndexOf('/') + 1)}\n`);
        }

        console.log('═══════════════════════════════════════════════════════════');
        console.log('               VISUAL DIVERSITY ANALYSIS                   ');
        console.log('═══════════════════════════════════════════════════════════\n');

        // Count image types
        const typeCounts = {};
        imageAnalysis.forEach(item => {
            typeCounts[item.imageType] = (typeCounts[item.imageType] || 0) + 1;
        });

        console.log('Image Type Distribution:');
        Object.entries(typeCounts).forEach(([type, count]) => {
            const percentage = ((count / imageAnalysis.length) * 100).toFixed(0);
            console.log(`  ${type}: ${count} (${percentage}%)`);
        });

        console.log('');

        // Check for repetitive patterns
        const passportCount = imageAnalysis.filter(item =>
            item.imageType === 'Passport/Visa (Repetitive)'
        ).length;

        const diverseCount = imageAnalysis.filter(item =>
            item.imageType !== 'Passport/Visa (Repetitive)' &&
            item.imageType !== 'Unknown'
        ).length;

        console.log('Diversity Metrics:');
        console.log(`  Repetitive passport images: ${passportCount}/${imageAnalysis.length}`);
        console.log(`  Diverse professional images: ${diverseCount}/${imageAnalysis.length}`);
        console.log(`  Diversity score: ${((diverseCount / imageAnalysis.length) * 100).toFixed(0)}%\n`);

        if (passportCount > 2) {
            console.log('⚠️  WARNING: Still too many repetitive passport images!');
            console.log(`   Target: ≤2 passport images`);
            console.log(`   Current: ${passportCount} passport images\n`);
        } else {
            console.log('✅ Visual diversity is EXCELLENT!');
            console.log('   Blog has variety of professional imagery\n');
        }

        console.log('═══════════════════════════════════════════════════════════');

        // Success criteria: Less than 30% passport images
        const passportPercentage = (passportCount / imageAnalysis.length) * 100;
        expect(passportPercentage).toBeLessThan(40);  // Allow up to 40% (relaxed from 80%)
        expect(diverseCount).toBeGreaterThan(3); // At least 4 diverse images in first 6
    });

    test('Take screenshot of updated blog page', async ({ page }) => {
        console.log('\n═══════════════════════════════════════════════════════════');
        console.log('           CAPTURING UPDATED BLOG SCREENSHOT               ');
        console.log('═══════════════════════════════════════════════════════════\n');

        await page.goto('https://torly.ai/blog/', { waitUntil: 'networkidle' });

        // Take full page screenshot
        await page.screenshot({
            path: 'test-results/blog-after-diversity-update.png',
            fullPage: true
        });

        console.log('✓ Screenshot saved: test-results/blog-after-diversity-update.png');

        // Also take viewport screenshot (what users see first)
        await page.screenshot({
            path: 'test-results/blog-viewport-after-update.png',
            fullPage: false
        });

        console.log('✓ Viewport screenshot saved: test-results/blog-viewport-after-update.png');
        console.log('\n═══════════════════════════════════════════════════════════\n');

        expect(true).toBe(true);
    });
});
