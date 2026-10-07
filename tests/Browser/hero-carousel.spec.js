import { test, expect } from '@playwright/test';

const ready = async page => {
    await page.goto('/');
    await expect(page.locator('.hero-focus-nav')).toBeVisible();
    await page.evaluate(() => document.fonts.ready);
    await page.locator('.hero-image.is-active').evaluate(image => image.decode());
};

const assertSlide = async (page, index) => {
    await expect(page.locator('.hero')).toHaveAttribute('data-active-slide', String(index));
    await expect(page.locator('[data-focus-slide][aria-current="true"]')).toHaveAttribute('data-focus-slide', String(index));
    await expect(page.locator('h1 .hero-copy.is-active')).toHaveAttribute('data-copy', String(index));
    await expect(page.locator('.hero .eyebrow .is-active')).toHaveAttribute('data-copy', String(index));
    await expect(page.locator('.hero-description .is-active')).toHaveAttribute('data-copy', String(index));
    await expect(page.locator('.hero-image.is-active')).toHaveJSProperty('complete', true);
    expect(await page.locator('.hero-image.is-active').evaluate(image => image.naturalWidth)).toBeGreaterThan(0);
};

test('five synchronized slides autoplay in order and loop without CTA or duplicate focus bar', async ({ page }) => {
    await page.clock.install();
    await ready(page);
    await expect(page.locator('.hero a, .hero .button, .focus-strip, .hero-caption, [data-carousel-dot]')).toHaveCount(0);
    await expect(page.locator('.hero-focus-nav')).toHaveCount(1);
    await expect(page.locator('.hero button')).toHaveCount(5);
    await expect(page.locator('.site-header .button')).toHaveText('Request Inquiry');
    await expect(page.locator('.hero + section h2')).toContainText('Peralatan tepat.');
    await expect(page.locator('.hero')).toHaveCSS('font-family', /SMART Inter/);
    const bounds = await page.locator('.hero').boundingBox();
    for (const index of [1, 2, 3, 4, 0]) {
        await page.clock.fastForward(5100);
        await assertSlide(page, index);
        expect(await page.locator('.hero').boundingBox()).toEqual(bounds);
    }
});

test('manual navigation resets the five second timer and keeps autoplay running', async ({ page }) => {
    await page.clock.install();
    await ready(page);
    await page.clock.fastForward(4000);
    await page.locator('[data-focus-slide="2"]').click();
    await assertSlide(page, 2);
    await page.clock.fastForward(3000);
    await assertSlide(page, 2);
    await page.clock.fastForward(2100);
    await assertSlide(page, 3);
    await page.locator('[data-focus-slide="4"]').click();
    await assertSlide(page, 4);
    await page.clock.fastForward(5100);
    await assertSlide(page, 0);
});

test('keyboard navigation supports arrows, Home, End and Enter without scrolling the page', async ({ page }) => {
    await ready(page);
    await page.locator('[data-focus-slide="0"]').focus();
    const before = await page.evaluate(() => scrollY);
    await page.keyboard.press('ArrowRight');
    await assertSlide(page, 1);
    await expect(page.locator('[data-focus-slide="1"]')).toBeFocused();
    await page.keyboard.press('End');
    await assertSlide(page, 4);
    await page.keyboard.press('Home');
    await assertSlide(page, 0);
    await page.keyboard.press('ArrowLeft');
    await assertSlide(page, 4);
    await page.locator('[data-focus-slide="2"]').focus();
    await page.keyboard.press('Enter');
    await assertSlide(page, 2);
    expect(await page.evaluate(() => scrollY)).toBe(before);
});

test('crossfade keeps the outgoing image beneath the incoming image and animates copy', async ({ page }) => {
    await ready(page);
    await page.locator('[data-focus-slide="1"]').click();
    await assertSlide(page, 1);
    await expect(page.locator('.hero-image.is-previous')).toHaveCount(1);
    await expect(page.locator('.hero-image.is-previous')).toHaveCSS('opacity', '1');
    await expect(page.locator('.hero-image.is-active')).toHaveCSS('transition-duration', '0.9s');
    await expect(page.locator('h1 .hero-copy.is-active')).toHaveCSS('transition-duration', '0.45s, 0.5s, 0s');
    await expect(page.locator('.hero-image.is-active')).toHaveCSS('opacity', '1');
    await expect(page.locator('.hero-image.is-previous')).toHaveCount(0);
    await expect(page.locator('h1 .hero-copy.is-active')).toHaveCSS('transform', 'matrix(1, 0, 0, 1, 0, 0)');
});

test('only active and next images load initially; manual changes remain synchronized during slow loads', async ({ page }) => {
    const requests = [];
    page.on('request', request => { if (request.resourceType() === 'image') requests.push(request.url()); });
    await ready(page);
    await expect(page.locator('[data-slide="mechanical"]')).toHaveJSProperty('complete', true);
    expect(requests.some(url => url.includes('/hero/electrical'))).toBe(false);
    expect(requests.some(url => url.includes('/hero/instrumentation'))).toBe(false);
    expect(requests.some(url => url.includes('/hero/oil-spill-response'))).toBe(false);
    let release;
    const gate = new Promise(resolve => { release = resolve; });
    await page.route('**/hero/electrical.webp', async route => { await gate; await route.continue(); });
    await page.locator('[data-focus-slide="2"]').click();
    await assertSlide(page, 0);
    await page.locator('[data-focus-slide="4"]').click();
    await assertSlide(page, 4);
    release();
    await page.waitForTimeout(500);
    await assertSlide(page, 4);
});

test('unavailable slide is skipped without replacing current content with a broken image', async ({ page }) => {
    await page.route('**/hero-pumps.webp', route => route.abort());
    await page.clock.install();
    await ready(page);
    await expect(page.locator('[data-focus-slide="1"]')).toBeDisabled();
    await page.clock.fastForward(5100);
    await assertSlide(page, 2);
});

test('reduced motion stops autoplay while manual selection remains available', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.clock.install();
    await ready(page);
    await page.clock.fastForward(16000);
    await assertSlide(page, 0);
    await page.locator('[data-focus-slide="3"]').click();
    await assertSlide(page, 3);
    await expect(page.locator('.hero-image.is-active')).toHaveCSS('transition-duration', '0s');
    await expect(page.locator('h1 .hero-copy.is-active')).toHaveCSS('transition-duration', '0s');
    await page.clock.fastForward(16000);
    await assertSlide(page, 3);
});

test('offscreen and hidden tabs suspend autoplay and resume without timer duplication', async ({ page }) => {
    await page.clock.install();
    await ready(page);
    await page.locator('.site-footer').scrollIntoViewIfNeeded();
    await page.waitForTimeout(100);
    await page.clock.fastForward(15000);
    await assertSlide(page, 0);
    await page.locator('.hero').scrollIntoViewIfNeeded();
    await page.waitForTimeout(100);
    await page.clock.fastForward(5100);
    await assertSlide(page, 1);
    await page.evaluate(() => {
        Object.defineProperty(document, 'hidden', { configurable: true, value: true });
        document.dispatchEvent(new Event('visibilitychange'));
    });
    await page.clock.fastForward(15000);
    await assertSlide(page, 1);
    await page.evaluate(() => {
        delete document.hidden;
        document.dispatchEvent(new Event('visibilitychange'));
    });
    await page.clock.fastForward(5100);
    await assertSlide(page, 2);
});

test('leaving the page cleans up timers and event handlers', async ({ page }) => {
    await page.clock.install();
    await ready(page);
    await page.evaluate(() => window.dispatchEvent(new PageTransitionEvent('pagehide', { persisted: false })));
    await page.clock.fastForward(16000);
    await assertSlide(page, 0);
    await page.locator('[data-focus-slide="2"]').click();
    await assertSlide(page, 0);
    await expect(page.locator('.hero')).not.toHaveAttribute('data-initialized');
});

test('no JavaScript fallback renders Engineering without dead controls', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false });
    try {
        const page = await context.newPage();
        await page.goto(baseURL);
        await expect(page.locator('.hero-image.is-active')).toBeVisible();
        await expect(page.locator('h1 .is-active')).toContainText('Engineering Solutions');
        await expect(page.locator('.hero-focus-nav')).toBeHidden();
        await expect(page.locator('.hero a')).toHaveCount(0);
    } finally { await context.close(); }
});

for (const [width, height] of [[1920, 1080], [1440, 900], [768, 1024], [390, 844], [320, 740]]) {
    test(`responsive focus navigation and all images at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height });
        await page.emulateMedia({ reducedMotion: 'reduce' });
        await ready(page);
        const original = await page.locator('.hero').boundingBox();
        const nav = await page.locator('.hero-focus-nav').boundingBox();
        expect(nav.y).toBeGreaterThan(original.y);
        expect(nav.y + nav.height).toBeLessThanOrEqual(original.y + original.height);
        const content = await page.locator('.hero-content').boundingBox();
        expect(content.y + content.height).toBeLessThanOrEqual(nav.y + 1);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
        const scrollY = await page.evaluate(() => window.scrollY);
        for (let i = 0; i < 5; i++) {
            await page.locator('[data-focus-slide="' + i + '"]').evaluate(button => button.click());
            await assertSlide(page, i);
            const item = await page.locator('[data-focus-slide="' + i + '"]').boundingBox();
            expect(item.x).toBeGreaterThanOrEqual(nav.x - 1);
            expect(item.x + item.width).toBeLessThanOrEqual(nav.x + nav.width + 1);
            expect(await page.locator('.hero').boundingBox()).toEqual(original);
            expect(await page.evaluate(() => window.scrollY)).toBe(scrollY);
            await page.screenshot({ path: testInfo.outputPath(`hero-${width}-slide-${i + 1}.png`) });
        }
    });
}
