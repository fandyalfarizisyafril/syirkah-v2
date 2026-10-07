import { test, expect } from '@playwright/test';

const ready = async page => {
    await page.goto('/');
    await expect(page.locator('[data-hero-carousel]')).toHaveAttribute('data-active-slide', '0');
    await page.waitForFunction(() => [...document.querySelectorAll('[data-slide]')].every(i => i.complete && i.naturalWidth > 0));
};

test('automatic rotation loops without controls, even on hover and focus', async ({ page }) => {
    await page.clock.install();
    await ready(page);
    const hero = page.locator('[data-hero-carousel]');
    await expect(hero.locator('button')).toHaveCount(0);
    await expect(page.locator('[data-carousel-controls], [data-carousel-dot]')).toHaveCount(0);
    await hero.hover();
    await page.locator('.hero .actions a').first().focus();
    const original = await page.locator('.hero-content').boundingBox();
    for (const index of [1, 2, 0, 1]) {
        await page.clock.fastForward(6100);
        await expect(hero).toHaveAttribute('data-active-slide', String(index));
        expect(await page.locator('.hero-content').boundingBox()).toEqual(original);
    }
});

test('offscreen rotation pauses and resumes when the hero returns', async ({ page }) => {
    await page.clock.install();
    await ready(page);
    await page.locator('.site-footer').scrollIntoViewIfNeeded();
    await page.waitForTimeout(100);
    await page.clock.fastForward(12500);
    await expect(page.locator('.hero')).toHaveAttribute('data-active-slide', '0');
    await page.locator('.hero').scrollIntoViewIfNeeded();
    await page.waitForTimeout(100);
    await page.clock.fastForward(6100);
    await expect(page.locator('.hero')).toHaveAttribute('data-active-slide', '1');
});

test('reduced motion keeps a static hero and preference changes restart autoplay', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.clock.install();
    await ready(page);
    await page.clock.fastForward(12500);
    await expect(page.locator('.hero')).toHaveAttribute('data-active-slide', '0');
    expect(await page.locator('.hero-image').first().evaluate(i => getComputedStyle(i).transitionDuration)).toBe('0s');
    await page.emulateMedia({ reducedMotion: 'no-preference' });
    await page.waitForTimeout(100);
    await page.clock.fastForward(6100);
    await expect(page.locator('.hero')).toHaveAttribute('data-active-slide', '1');
});

test('unavailable secondary image is skipped automatically', async ({ page }) => {
    await page.route('**/hero-pumps.webp', route => route.abort());
    await page.clock.install();
    await page.goto('/');
    await page.waitForFunction(() => {
        const image = document.querySelector('[data-src*="hero-pumps"]');
        return image.src && image.complete && image.naturalWidth === 0;
    });
    await page.clock.fastForward(6100);
    await expect(page.locator('.hero')).toHaveAttribute('data-active-slide', '2');
    await expect(page.locator('.hero-image.is-active')).toHaveJSProperty('naturalWidth', 1672);
});

test('without JavaScript the original hero and CTA remain available', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false });
    try {
        const page = await context.newPage();
        await page.goto(baseURL);
        await expect(page.locator('.hero-image.is-active')).toBeVisible();
        await expect(page.locator('.hero button')).toHaveCount(0);
        await expect(page.locator('.hero .actions a').first()).toHaveAttribute('href', /\/produk$/);
    } finally { await context.close(); }
});

for (const [width, height] of [[1440, 760], [768, 760], [390, 590], [320, 635.90625]]) {
    test(`automatic hero preserves dimensions at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await page.clock.install();
        await ready(page);
        expect((await page.locator('.hero').boundingBox()).height).toBeCloseTo(height, 0);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
        await expect(page.locator('.hero button')).toHaveCount(0);
        for (let i = 0; i < 3; i++) {
            if (i) await page.clock.fastForward(6100);
            await expect(page.locator('.hero')).toHaveAttribute('data-active-slide', String(i));
            await expect(page.locator('.hero-image.is-active')).toHaveCSS('opacity', '1');
            await page.screenshot({ path: testInfo.outputPath(`hero-${width}-slide-${i + 1}.png`) });
        }
    });
}
