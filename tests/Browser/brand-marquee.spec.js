import { test, expect } from '@playwright/test';

async function openMarquee(page) {
    await page.goto('/');
    const marquee = page.locator('.home-brand-marquee');
    await marquee.scrollIntoViewIfNeeded();
    await page.mouse.move(0, 0);
    await marquee.locator('img').evaluateAll(images => Promise.all(images.map(image => {
        image.loading = 'eager';
        return image.decode();
    })));
    return marquee;
}

for (const width of [1920, 1440, 768, 390, 320]) {
    test(`verified logos and seamless geometry at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 900 });
        const marquee = await openMarquee(page);
        const groups = marquee.locator('.home-brand-group');
        await expect(groups).toHaveCount(2);
        const original = groups.first();
        await expect(original.locator('img')).toHaveCount(5);
        expect(await original.locator('img').evaluateAll(images => images.map(image => image.alt)))
            .toEqual(['Wolong', 'OLI', 'Bredel', 'Aflex', 'Tecbell']);
        await expect(groups.last()).toHaveAttribute('aria-hidden', 'true');
        await expect(groups.last().locator('a:not([tabindex="-1"])')).toHaveCount(0);
        await expect(groups.last().locator('img:not([alt=""])')).toHaveCount(0);
        await expect(marquee.locator('img').first()).toHaveCSS('object-fit', 'contain');
        const track = marquee.locator('.home-brand-track');
        await expect(track).toHaveCSS('animation-duration', '30s');
        await expect(track).toHaveCSS('animation-timing-function', 'linear');
        await track.evaluate(element => {
            const animation = element.getAnimations()[0];
            animation.pause();
            animation.currentTime = 0;
        });
        const start = await original.boundingBox();
        const duplicate = await groups.last().boundingBox();
        const viewport = await marquee.boundingBox();
        expect(start.width).toBeCloseTo(duplicate.width, 1);
        expect(start.width).toBeGreaterThanOrEqual(viewport.width);
        expect(start.x + start.width).toBeCloseTo(duplicate.x, 1);
        await track.evaluate(element => element.getAnimations()[0].currentTime = 29999);
        expect((await groups.last().boundingBox()).x).toBeCloseTo(start.x, 0);
        await track.evaluate(element => element.getAnimations()[0].currentTime = 30000);
        expect((await original.boundingBox()).x).toBeCloseTo(start.x, 0);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
        await marquee.locator('..').screenshot({ path: testInfo.outputPath(`brands-${width}.png`) });
    });
}

test('autoplay moves left, hover pauses and visible duplicate links navigate normally', async ({ page }) => {
    const marquee = await openMarquee(page);
    const track = marquee.locator('.home-brand-track');
    const before = await track.boundingBox();
    await page.waitForTimeout(200);
    expect((await track.boundingBox()).x).toBeLessThan(before.x);
    await marquee.hover();
    await expect(track).toHaveCSS('animation-play-state', 'paused');
    const paused = await track.boundingBox();
    await page.waitForTimeout(150);
    expect((await track.boundingBox()).x).toBeCloseTo(paused.x, 1);
    await track.evaluate(element => element.getAnimations()[0].currentTime = 27000);
    const link = marquee.locator('[aria-hidden="true"] a').first();
    const href = await link.getAttribute('href');
    await link.click();
    await expect(page).toHaveURL(href);
    await expect(page.locator('.home-brand-marquee')).toHaveCount(0);
});

test('keyboard users can reach every original logo without moving or duplicate focus targets', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    const marquee = await openMarquee(page);
    const links = marquee.locator('.home-brand-group').first().locator('a');
    await page.keyboard.press('Tab');
    for (const link of await links.all()) {
        await link.focus();
        await expect(link).toBeFocused();
        await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'none');
        await expect(marquee.locator('[aria-hidden="true"]')).toBeHidden();
        await expect.poll(async () => {
            const item = await link.boundingBox();
            const view = await marquee.boundingBox();
            return item.x >= view.x - 1 && item.x + item.width <= view.x + view.width + 1;
        }).toBe(true);
    }
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/brand\/tecbell$/);
});

test('reduced motion shows one scrollable list and responds to preference changes', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.setViewportSize({ width: 320, height: 740 });
    const marquee = await openMarquee(page);
    await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'none');
    await expect(marquee.locator('[aria-hidden="true"]')).toBeHidden();
    await marquee.evaluate(element => element.scrollLeft = element.scrollWidth);
    expect(await marquee.evaluate(element => element.scrollLeft)).toBeGreaterThan(0);
    await page.emulateMedia({ reducedMotion: 'no-preference' });
    await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'home-brand-scroll');
    await expect.poll(() => marquee.evaluate(element => element.scrollLeft)).toBe(0);
});

test('small CMS lists still cover the viewport without a gap at the loop', async ({ page }) => {
    await page.setViewportSize({ width: 1920, height: 900 });
    const marquee = await openMarquee(page);
    await marquee.locator('.home-brand-group').evaluateAll(groups => groups.forEach(group => {
        [...group.children].slice(2).forEach(item => item.remove());
    }));
    await marquee.locator('.home-brand-track').evaluate(element => element.getAnimations()[0].pause());
    const group = await marquee.locator('.home-brand-group').first().boundingBox();
    const duplicate = await marquee.locator('.home-brand-group').last().boundingBox();
    expect(group.width).toBeGreaterThanOrEqual((await marquee.boundingBox()).width);
    expect(group.x + group.width).toBeCloseTo(duplicate.x, 1);
});

test('marquee has no JavaScript dependency', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    try {
        const page = await context.newPage();
        const marquee = await openMarquee(page);
        await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'home-brand-scroll');
        await marquee.hover();
        await marquee.locator('a').first().click();
        await expect(page).toHaveURL(/\/brand\/wolong$/);
    } finally { await context.close(); }
});
