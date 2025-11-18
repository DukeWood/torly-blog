/**
 * Playwright Test: Waitlist Modal Positioning
 * Verifies that the modal is properly centered on screen
 */

const { chromium } = require('playwright');

(async () => {
    console.log('🚀 Testing waitlist modal positioning...\n');

    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();
    await page.setViewportSize({ width: 1280, height: 720 });

    try {
        // Navigate to homepage
        console.log('📍 Navigating to https://torly.ai/');
        await page.goto('https://torly.ai/', { waitUntil: 'networkidle' });

        // Get viewport dimensions
        const viewport = page.viewportSize();
        console.log(`📐 Viewport: ${viewport.width}x${viewport.height}`);

        // Click the Join Waitlist button
        console.log('\n🖱️  Clicking Join Waitlist button...');
        const button = await page.locator('button.btn-primary').first();
        await button.click();

        // Wait for modal to appear
        await page.waitForTimeout(500);

        // Check both modal implementations
        const modalNew = page.locator('#waitlist-modal');
        const modalOld = page.locator('#waitlistModal');

        let modal = null;
        let modalType = '';

        if (await modalNew.isVisible()) {
            modal = modalNew;
            modalType = 'new (#waitlist-modal)';
        } else if (await modalOld.isVisible()) {
            modal = modalOld;
            modalType = 'old (#waitlistModal)';
        }

        if (!modal) {
            throw new Error('No modal found!');
        }

        console.log(`✅ Modal visible: ${modalType}`);

        // Get modal position and dimensions
        const boundingBox = await modal.boundingBox();

        console.log('\n📦 Modal Bounding Box:');
        console.log(`   X: ${boundingBox.x}px`);
        console.log(`   Y: ${boundingBox.y}px`);
        console.log(`   Width: ${boundingBox.width}px`);
        console.log(`   Height: ${boundingBox.height}px`);

        // Calculate center position
        const modalCenterX = boundingBox.x + (boundingBox.width / 2);
        const modalCenterY = boundingBox.y + (boundingBox.height / 2);
        const viewportCenterX = viewport.width / 2;
        const viewportCenterY = viewport.height / 2;

        console.log('\n🎯 Centering Analysis:');
        console.log(`   Modal center X: ${modalCenterX.toFixed(1)}px`);
        console.log(`   Viewport center X: ${viewportCenterX}px`);
        console.log(`   X offset: ${Math.abs(modalCenterX - viewportCenterX).toFixed(1)}px`);

        console.log(`   Modal center Y: ${modalCenterY.toFixed(1)}px`);
        console.log(`   Viewport center Y: ${viewportCenterY}px`);
        console.log(`   Y offset: ${Math.abs(modalCenterY - viewportCenterY).toFixed(1)}px`);

        // Check if modal is centered (within 50px tolerance)
        const xCentered = Math.abs(modalCenterX - viewportCenterX) < 50;
        const yCentered = Math.abs(modalCenterY - viewportCenterY) < 50;
        const isCentered = xCentered && yCentered;

        console.log('\n✨ Position Status:');
        console.log(`   Horizontally centered: ${xCentered ? '✓ Yes' : '✗ No'}`);
        console.log(`   Vertically centered: ${yCentered ? '✓ Yes' : '✗ No'}`);
        console.log(`   Overall: ${isCentered ? '✅ CENTERED' : '❌ NOT CENTERED'}`);

        // Check if modal is fully visible (not off-screen)
        const isOnScreen = boundingBox.x >= 0 &&
                          boundingBox.y >= 0 &&
                          (boundingBox.x + boundingBox.width) <= viewport.width &&
                          (boundingBox.y + boundingBox.height) <= viewport.height;

        console.log(`   Fully on screen: ${isOnScreen ? '✓ Yes' : '✗ No'}`);

        // Get modal CSS properties
        const modalStyles = await modal.evaluate((el) => {
            const computed = window.getComputedStyle(el);
            return {
                position: computed.position,
                top: computed.top,
                left: computed.left,
                transform: computed.transform,
                display: computed.display,
                zIndex: computed.zIndex
            };
        });

        console.log('\n🎨 Modal CSS Properties:');
        console.log(`   position: ${modalStyles.position}`);
        console.log(`   top: ${modalStyles.top}`);
        console.log(`   left: ${modalStyles.left}`);
        console.log(`   transform: ${modalStyles.transform}`);
        console.log(`   display: ${modalStyles.display}`);
        console.log(`   z-index: ${modalStyles.zIndex}`);

        // Take screenshot
        console.log('\n📸 Taking screenshot...');
        await page.screenshot({ path: 'modal-position-test.png', fullPage: false });
        console.log('   Screenshot saved: modal-position-test.png');

        // Summary
        console.log('\n' + '='.repeat(60));
        console.log('📊 TEST SUMMARY');
        console.log('='.repeat(60));
        console.log(`${isCentered ? '✅' : '❌'} Modal is properly centered`);
        console.log(`${isOnScreen ? '✅' : '❌'} Modal is fully visible on screen`);
        console.log(`${modalStyles.position === 'fixed' ? '✅' : '❌'} Modal has fixed positioning`);

        if (isCentered && isOnScreen) {
            console.log('\n🎉 POSITIONING TEST PASSED!');
        } else {
            console.log('\n⚠️  POSITIONING TEST FAILED!');
            console.log('Modal is not properly centered or visible on screen.');
        }
        console.log('='.repeat(60));

    } catch (error) {
        console.error('\n❌ ERROR:', error.message);
        await page.screenshot({ path: 'modal-position-error.png', fullPage: false });
        console.log('Error screenshot saved: modal-position-error.png');
    } finally {
        await browser.close();
    }
})();
