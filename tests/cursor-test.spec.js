// @ts-check
const { test, expect } = require('@playwright/test');

test.describe('Custom Cursor Tests', () => {

    test('cursor appears on homepage when mouse moves', async ({ page }) => {
        await page.goto('https://torly.ai/');
        await page.waitForLoadState('domcontentloaded');

        // Wait for cursor elements to be created by JS
        await page.waitForTimeout(1000);

        // Move mouse to trigger cursor visibility
        await page.mouse.move(500, 300);
        await page.waitForTimeout(500);

        // Check if cursor elements exist
        const cursor = page.locator('.custom-cursor');
        const cursorRing = page.locator('.custom-cursor-ring');

        await expect(cursor).toBeAttached();
        await expect(cursorRing).toBeAttached();

        // Check cursor is visible (opacity > 0)
        const cursorOpacity = await cursor.evaluate(el => window.getComputedStyle(el).opacity);
        const ringOpacity = await cursorRing.evaluate(el => window.getComputedStyle(el).opacity);

        console.log(`Homepage - Cursor opacity: ${cursorOpacity}, Ring opacity: ${ringOpacity}`);

        expect(parseFloat(cursorOpacity)).toBeGreaterThan(0);
        expect(parseFloat(ringOpacity)).toBeGreaterThan(0);
    });

    test('cursor appears on blog page when mouse moves', async ({ page }) => {
        await page.goto('https://torly.ai/blog/');
        await page.waitForLoadState('domcontentloaded');

        // Wait for cursor elements to be created by JS
        await page.waitForTimeout(1000);

        // Move mouse to trigger cursor visibility
        await page.mouse.move(500, 300);
        await page.waitForTimeout(500);

        // Check if cursor elements exist
        const cursor = page.locator('.custom-cursor');
        const cursorRing = page.locator('.custom-cursor-ring');

        await expect(cursor).toBeAttached();
        await expect(cursorRing).toBeAttached();

        // Check cursor is visible (opacity > 0)
        const cursorOpacity = await cursor.evaluate(el => window.getComputedStyle(el).opacity);
        const ringOpacity = await cursorRing.evaluate(el => window.getComputedStyle(el).opacity);

        console.log(`Blog - Cursor opacity: ${cursorOpacity}, Ring opacity: ${ringOpacity}`);

        expect(parseFloat(cursorOpacity)).toBeGreaterThan(0);
        expect(parseFloat(ringOpacity)).toBeGreaterThan(0);
    });

    test('cursor has correct styling (black color)', async ({ page }) => {
        await page.goto('https://torly.ai/');
        await page.waitForLoadState('domcontentloaded');
        await page.waitForTimeout(1000);

        // Move mouse to create cursor
        await page.mouse.move(500, 300);
        await page.waitForTimeout(500);

        const cursor = page.locator('.custom-cursor');
        const cursorRing = page.locator('.custom-cursor-ring');

        // Check cursor background color
        const bgColor = await cursor.evaluate(el => window.getComputedStyle(el).backgroundColor);
        console.log(`Cursor background color: ${bgColor}`);

        // Should be black (rgb(0, 0, 0))
        expect(bgColor).toMatch(/rgb\(0,\s*0,\s*0\)/);

        // Check ring border color
        const borderColor = await cursorRing.evaluate(el => window.getComputedStyle(el).borderColor);
        console.log(`Ring border color: ${borderColor}`);

        // Should be black
        expect(borderColor).toMatch(/rgb\(0,\s*0,\s*0\)/);
    });

    test('cursor ring expands on hover over interactive elements', async ({ page }) => {
        await page.goto('https://torly.ai/');
        await page.waitForLoadState('domcontentloaded');
        await page.waitForTimeout(1000);

        // Move mouse first
        await page.mouse.move(500, 300);
        await page.waitForTimeout(300);

        const cursorRing = page.locator('.custom-cursor-ring');

        // Get initial width
        const initialWidth = await cursorRing.evaluate(el => el.offsetWidth);
        console.log(`Initial ring width: ${initialWidth}px`);

        // Find a button or link
        const button = page.locator('.btn-primary').first();
        const buttonBox = await button.boundingBox();

        if (buttonBox) {
            // Hover over the button
            await page.mouse.move(buttonBox.x + buttonBox.width / 2, buttonBox.y + buttonBox.height / 2);
            await page.waitForTimeout(500);

            // Check if ring has hover class
            const hasHoverClass = await cursorRing.evaluate(el => el.classList.contains('hover'));
            console.log(`Ring has hover class: ${hasHoverClass}`);

            expect(hasHoverClass).toBe(true);
        }
    });

    test('CSS version is 3.0.3 on both pages', async ({ page }) => {
        // Check homepage
        await page.goto('https://torly.ai/');
        const homeContent = await page.content();
        const homeVersionMatch = homeContent.match(/style\.css\?ver=([0-9.]+)/);
        const homeVersion = homeVersionMatch ? homeVersionMatch[1] : 'not found';
        console.log(`Homepage CSS version: ${homeVersion}`);
        expect(homeVersion).toBe('3.0.3');

        // Check blog
        await page.goto('https://torly.ai/blog/');
        const blogContent = await page.content();
        const blogVersionMatch = blogContent.match(/style\.css\?ver=([0-9.]+)/);
        const blogVersion = blogVersionMatch ? blogVersionMatch[1] : 'not found';
        console.log(`Blog CSS version: ${blogVersion}`);
        expect(blogVersion).toBe('3.0.3');
    });
});
