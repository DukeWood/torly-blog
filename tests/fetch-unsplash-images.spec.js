const { test } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

test('Fetch diverse Unsplash images for blog posts', async ({ page }) => {
    console.log('\n═══════════════════════════════════════════════════════════');
    console.log('      FETCHING DIVERSE UNSPLASH IMAGES FOR BLOG POSTS      ');
    console.log('═══════════════════════════════════════════════════════════\n');

    const imageData = [];

    // 1. Business Meeting/Consultation (for Office Hours post)
    console.log('1. Searching for: Professional Business Meeting...');
    await page.goto('https://unsplash.com/s/photos/business-meeting', { waitUntil: 'networkidle' });
    await page.waitForTimeout(2000);

    const meetingImg = await page.locator('img[srcset*="photo"]').first().getAttribute('src');
    const meetingLink = await page.locator('a[href*="/photos/"]').first().getAttribute('href');

    imageData.push({
        theme: 'Business Meeting/Consultation',
        post: 'Join Our AI-Powered UK Innovator Visa Office Hours',
        imageUrl: meetingImg,
        unsplashLink: 'https://unsplash.com' + meetingLink,
        downloadUrl: meetingImg.replace(/\?.*$/, '') + '?w=1200'
    });
    console.log(`   ✓ Found: ${meetingImg?.substring(0, 80)}...`);

    // 2. AI Technology/Dashboard (for AI Guide post)
    console.log('\n2. Searching for: AI Technology Dashboard...');
    await page.goto('https://unsplash.com/s/photos/ai-technology', { waitUntil: 'networkidle' });
    await page.waitForTimeout(2000);

    const aiImg = await page.locator('img[srcset*="photo"]').nth(2).getAttribute('src');
    const aiLink = await page.locator('a[href*="/photos/"]').nth(2).getAttribute('href');

    imageData.push({
        theme: 'AI Technology/Dashboard',
        post: 'Comprehensive AI-Powered Guide to UK Innovator Visa',
        imageUrl: aiImg,
        unsplashLink: 'https://unsplash.com' + aiLink,
        downloadUrl: aiImg.replace(/\?.*$/, '') + '?w=1200'
    });
    console.log(`   ✓ Found: ${aiImg?.substring(0, 80)}...`);

    // 3. Business Planning/Strategy (for Step-by-Step post)
    console.log('\n3. Searching for: Business Planning Strategy...');
    await page.goto('https://unsplash.com/s/photos/business-strategy', { waitUntil: 'networkidle' });
    await page.waitForTimeout(2000);

    const planningImg = await page.locator('img[srcset*="photo"]').nth(1).getAttribute('src');
    const planningLink = await page.locator('a[href*="/photos/"]').nth(1).getAttribute('href');

    imageData.push({
        theme: 'Business Planning/Strategy',
        post: 'Step-by-Step AI-Guided Innovator Visa 2025',
        imageUrl: planningImg,
        unsplashLink: 'https://unsplash.com' + planningLink,
        downloadUrl: planningImg.replace(/\?.*$/, '') + '?w=1200'
    });
    console.log(`   ✓ Found: ${planningImg?.substring(0, 80)}...`);

    // 4. Data Analytics/Charts (for Comparison post)
    console.log('\n4. Searching for: Data Analytics Charts...');
    await page.goto('https://unsplash.com/s/photos/data-analytics', { waitUntil: 'networkidle' });
    await page.waitForTimeout(2000);

    const analyticsImg = await page.locator('img[srcset*="photo"]').nth(3).getAttribute('src');
    const analyticsLink = await page.locator('a[href*="/photos/"]').nth(3).getAttribute('href');

    imageData.push({
        theme: 'Data Analytics/Charts',
        post: 'Why AI-Powered Innovator Founder Visa Outperforms Tier 1',
        imageUrl: analyticsImg,
        unsplashLink: 'https://unsplash.com' + analyticsLink,
        downloadUrl: analyticsImg.replace(/\?.*$/, '') + '?w=1200'
    });
    console.log(`   ✓ Found: ${analyticsImg?.substring(0, 80)}...`);

    // 5. London/UK Business (for Requirements post)
    console.log('\n5. Searching for: London Business Skyline...');
    await page.goto('https://unsplash.com/s/photos/london-business', { waitUntil: 'networkidle' });
    await page.waitForTimeout(2000);

    const londonImg = await page.locator('img[srcset*="photo"]').nth(1).getAttribute('src');
    const londonLink = await page.locator('a[href*="/photos/"]').nth(1).getAttribute('href');

    imageData.push({
        theme: 'London/UK Business',
        post: 'UK Innovator Founder Visa 2025: Requirements & Benefits',
        imageUrl: londonImg,
        unsplashLink: 'https://unsplash.com' + londonLink,
        downloadUrl: londonImg.replace(/\?.*$/, '') + '?w=1200'
    });
    console.log(`   ✓ Found: ${londonImg?.substring(0, 80)}...`);

    // 6. Startup/Entrepreneurship (for Business Setup post)
    console.log('\n6. Searching for: Startup Office Collaboration...');
    await page.goto('https://unsplash.com/s/photos/startup-team', { waitUntil: 'networkidle' });
    await page.waitForTimeout(2000);

    const startupImg = await page.locator('img[srcset*="photo"]').nth(2).getAttribute('src');
    const startupLink = await page.locator('a[href*="/photos/"]').nth(2).getAttribute('href');

    imageData.push({
        theme: 'Startup/Entrepreneurship',
        post: 'AI-Assisted Business Setup with UK Innovator Founder Visa',
        imageUrl: startupImg,
        unsplashLink: 'https://unsplash.com' + startupLink,
        downloadUrl: startupImg.replace(/\?.*$/, '') + '?w=1200'
    });
    console.log(`   ✓ Found: ${startupImg?.substring(0, 80)}...`);

    // Save data to JSON file
    const outputPath = path.join(__dirname, '../curated-unsplash-images.json');
    fs.writeFileSync(outputPath, JSON.stringify(imageData, null, 2));

    console.log('\n═══════════════════════════════════════════════════════════');
    console.log(`✓ Curated ${imageData.length} diverse images`);
    console.log(`✓ Saved to: curated-unsplash-images.json`);
    console.log('═══════════════════════════════════════════════════════════\n');

    // Display summary
    console.log('CURATED IMAGES SUMMARY:\n');
    imageData.forEach((img, index) => {
        console.log(`${index + 1}. ${img.theme}`);
        console.log(`   Post: ${img.post}`);
        console.log(`   Unsplash: ${img.unsplashLink}`);
        console.log(`   Download: ${img.downloadUrl}\n`);
    });
});
