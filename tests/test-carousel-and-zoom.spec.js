const { test, expect } = require('@playwright/test');

test.describe('Product Showcase & Zoom Features', () => {
  
  test('Carousel - thumbnail clicks should change featured image', async ({ page }) => {
    await page.goto('https://torly.ai');
    
    // Wait for page to load
    await page.waitForSelector('#featuredImage');
    
    // Get initial featured image src
    const initialSrc = await page.getAttribute('#featuredImage', 'src');
    console.log('Initial image:', initialSrc);
    
    // Click the second thumbnail
    await page.click('.thumbnail-item:nth-child(2)');
    await page.waitForTimeout(500);
    
    // Check if featured image changed
    const newSrc = await page.getAttribute('#featuredImage', 'src');
    console.log('After click:', newSrc);
    
    expect(newSrc).not.toBe(initialSrc);
    expect(newSrc).toContain('screenshot-2.webp');
  });

  test('Carousel - category tabs should filter thumbnails', async ({ page }) => {
    await page.goto('https://torly.ai');
    await page.waitForSelector('.tab-btn');
    
    // Click "AI Skills Library" tab
    await page.click('.tab-btn[data-category="skills"]');
    await page.waitForTimeout(300);
    
    // Check if tab is active
    const isActive = await page.getAttribute('.tab-btn[data-category="skills"]', 'class');
    expect(isActive).toContain('active');
  });

  test('Assessment Results - images have borders', async ({ page }) => {
    await page.goto('https://torly.ai');
    await page.waitForSelector('.result-screenshot');
    
    // Check if images have border styling
    const borderStyle = await page.$eval('.card-image-wrapper', el => {
      const styles = window.getComputedStyle(el);
      return styles.border;
    });
    
    console.log('Image wrapper border:', borderStyle);
    expect(borderStyle).toContain('2px');
  });

  test('Zoom Modal - clicking result card opens lightbox', async ({ page }) => {
    await page.goto('https://torly.ai');
    await page.waitForSelector('.result-card');
    
    // Check modal is initially hidden
    const modalInitial = await page.$('#screenshotModal');
    const initialClass = await page.getAttribute('#screenshotModal', 'class');
    expect(initialClass).not.toContain('active');
    
    // Click first result card
    await page.click('.result-card:first-child');
    await page.waitForTimeout(500);
    
    // Check modal is now visible
    const modalClass = await page.getAttribute('#screenshotModal', 'class');
    console.log('Modal classes after click:', modalClass);
    expect(modalClass).toContain('active');
    
    // Check modal image is set
    const modalImgSrc = await page.getAttribute('#modalImage', 'src');
    console.log('Modal image src:', modalImgSrc);
    expect(modalImgSrc).toContain('screenshot-9.webp');
  });

  test('Zoom Modal - close button works', async ({ page }) => {
    await page.goto('https://torly.ai');
    await page.waitForSelector('.result-card');
    
    // Open modal
    await page.click('.result-card:first-child');
    await page.waitForTimeout(500);
    
    // Click close button
    await page.click('.modal-close');
    await page.waitForTimeout(500);
    
    // Check modal is closed
    const modalClass = await page.getAttribute('#screenshotModal', 'class');
    expect(modalClass).not.toContain('active');
  });

  test('Zoom Modal - Escape key closes modal', async ({ page }) => {
    await page.goto('https://torly.ai');
    await page.waitForSelector('.result-card');
    
    // Open modal
    await page.click('.result-card:first-child');
    await page.waitForTimeout(500);
    
    // Press Escape key
    await page.keyboard.press('Escape');
    await page.waitForTimeout(300);
    
    // Check modal is closed
    const modalClass = await page.getAttribute('#screenshotModal', 'class');
    expect(modalClass).not.toContain('active');
  });

});
