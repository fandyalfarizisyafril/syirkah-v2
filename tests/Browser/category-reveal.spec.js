import { test, expect } from '@playwright/test';

const section = '.home-product-categories';
const cards = section + ' .category-item';

test('all seven hover images reveal independently without changing card geometry or links', async ({ page }, testInfo) => {
    await page.setViewportSize({ width: 1440, height: 1000 });
    await page.goto('/');
    await page.mouse.move(0, 0);
    const grid = page.locator(section + ' .category-grid');
    await grid.scrollIntoViewIfNeeded();
    const items = page.locator(cards);
    await expect(items).toHaveCount(7);
    await items.locator('img').evaluateAll(images => Promise.all(images.map(i => i.decode())));
    const initial = await items.evaluateAll(elements => elements.map(e => ({ x: e.offsetLeft, y: e.offsetTop, width: e.offsetWidth, height: e.offsetHeight, href: e.href })));
    const before = await grid.screenshot();
    const sources = await items.locator('img').evaluateAll(images => images.map(i => i.currentSrc));
    expect(new Set(sources).size).toBe(7);
    for (let i = 0; i < 7; i++) {
        const card = items.nth(i);
        await card.hover();
        await expect(card.locator('.category-reveal')).toHaveCSS('opacity', '1');
        await expect(card.locator('.category-reveal')).toHaveCSS('transform', 'matrix(1, 0, 0, 1, 0, 0)');
        await expect(card.locator('.category-reveal')).toHaveCSS('transition-duration', '0.5s, 0.5s');
        await expect(card.locator('h3')).toHaveCSS('color', 'rgb(255, 255, 255)');
        await expect(card.locator('p')).toHaveCSS('color', 'rgb(255, 255, 255)');
        await expect(card.locator('.icon')).toHaveCSS('color', 'rgb(255, 255, 255)');
        expect(await items.evaluateAll(elements => elements.map(e => ({ x: e.offsetLeft, y: e.offsetTop, width: e.offsetWidth, height: e.offsetHeight, href: e.href })))).toEqual(initial);
        for (let other = 0; other < 7; other++) {
            if (other !== i) await expect(items.nth(other).locator('.category-reveal')).toHaveCSS('opacity', '0');
        }
        const contained = await card.evaluate(e => {
            const outer = e.getBoundingClientRect();
            return [...e.querySelectorAll('.category-number,h3,p,.icon')].every(child => {
                const bounds = child.getBoundingClientRect();
                return bounds.left >= outer.left && bounds.right <= outer.right && bounds.top >= outer.top && bounds.bottom <= outer.bottom;
            });
        });
        expect(contained).toBe(true);
        await card.screenshot({ path: testInfo.outputPath(`category-hover-${i + 1}.png`) });
    }
    await page.mouse.move(0, 0);
    await expect(items.last().locator('.category-reveal')).toHaveCSS('opacity', '0');
    await expect(items.last().locator('h3')).toHaveCSS('color', 'rgb(10, 38, 61)');
    expect((await grid.screenshot()).equals(before)).toBe(true);
    await items.first().click();
    await expect(page).toHaveURL(initial[0].href);
    await expect(page.locator('.category-reveal')).toHaveCount(0);
});

test('keyboard navigation remains a normal link and reduced motion removes reveal animation', async ({ page }) => {
    await page.goto('/');
    await page.mouse.move(0, 0);
    const card = page.locator(cards).first();
    await card.focus();
    await expect(card.locator('.category-reveal')).toHaveCSS('opacity', '0');
    await expect(card).toBeFocused();
    const href = await card.getAttribute('href');
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(href);
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.goto('/');
    await card.hover();
    await expect(card.locator('.category-reveal')).toHaveCSS('opacity', '1');
    await expect(card.locator('.category-reveal')).toHaveCSS('transition-duration', '0s');
});

test('product listing cards have no hover image layers', async ({ page }) => {
    await page.goto('/produk');
    await expect(page.locator('.category-reveal, .has-reveal')).toHaveCount(0);
    const card = page.locator('.category-item').first();
    await card.hover();
    await expect(card.locator('h3')).toHaveCSS('color', 'rgb(10, 38, 61)');
});

for (const width of [768, 390, 320]) {
    test(`touch device at ${width}px keeps default cards and navigates with one tap`, async ({ browser, baseURL }, testInfo) => {
        const context = await browser.newContext({ viewport: { width, height: 1000 }, hasTouch: true, isMobile: true, baseURL });
        try {
            const page = await context.newPage();
            const requests = [];
            page.on('request', request => { if (request.url().includes('/category-reveals/')) requests.push(request.url()); });
            await page.goto('/');
            await page.locator(section).scrollIntoViewIfNeeded();
            expect(await page.evaluate(() => matchMedia('(hover: hover) and (pointer: fine)').matches)).toBe(false);
            await expect(page.locator(cards)).toHaveCount(7);
            const card = page.locator(cards).first();
            await card.hover();
            await expect(card.locator('.category-reveal')).toHaveCSS('opacity', '0');
            await expect(card.locator('h3')).toHaveCSS('color', 'rgb(10, 38, 61)');
            expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
            expect(requests).toEqual([]);
            await page.locator(section).screenshot({ path: testInfo.outputPath(`categories-touch-${width}.png`) });
            const href = await card.getAttribute('href');
            await card.tap();
            await expect(page).toHaveURL(href);
        } finally { await context.close(); }
    });
}
