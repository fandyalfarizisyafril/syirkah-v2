import { test, expect } from '@playwright/test';

const names = ['Wolong', 'OLI', 'Qdos', 'Bredel', 'Aflex', 'Tecbell', 'BLU-C'];

for (const [width, columns] of [[1920, 4], [1440, 4], [1024, 3], [768, 2], [390, 1], [320, 1]]) {
    test(`brand portfolio layout, logos and typography at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await page.goto('/brand');
        const root = page.locator('.brand-portfolio');
        await expect(root).toHaveCSS('font-family', /SMART Inter/);
        await expect(root.locator('h1')).toHaveText('Brand dan teknologi untuk kebutuhan industri.');
        await expect(root.locator('.brand-portfolio-card h2')).toHaveText(names);
        await root.locator('img').evaluateAll(es => Promise.all(es.map(e => { e.loading = 'eager'; return e.decode(); })));
        const layout = await root.evaluate(e => {
            const cards = [...e.querySelectorAll('.brand-portfolio-card')];
            const rect = el => el.getBoundingClientRect();
            const rows = new Map();
            cards.forEach(c => {
                const r = rect(c), key = Math.round(r.top);
                rows.set(key, [...(rows.get(key) || []), r.height]);
            });
            return {
                columns: getComputedStyle(e.querySelector('.brand-portfolio-grid')).gridTemplateColumns.split(' ').length,
                overflow: document.documentElement.scrollWidth > innerWidth,
                rowsEqual: [...rows.values()].every(row => Math.max(...row) - Math.min(...row) < 1),
                textFits: [...e.querySelectorAll('h1,h2,p')].every(c => c.scrollWidth <= c.clientWidth + 1 && rect(c).right <= innerWidth),
                images: [...e.querySelectorAll('img')].every(i => {
                    const box = rect(i), area = rect(i.parentElement), style = getComputedStyle(i);
                    return i.naturalWidth > 0 && i.naturalHeight > 0 && style.objectFit === 'contain' && style.filter === 'none'
                        && box.width <= area.width && box.height <= area.height && !!i.alt;
                }),
                arrowClear: cards.every(c => {
                    const copy = rect(c.querySelector('.brand-portfolio-copy'));
                    const arrow = rect(c.querySelector(':scope > .icon'));
                    const text = rect(c.querySelector('p'));
                    return arrow.left >= text.right && arrow.bottom <= rect(c).bottom && copy.left >= rect(c).left;
                }),
            };
        });
        expect(layout.columns).toBe(columns);
        expect(layout.overflow).toBe(false);
        expect(layout.rowsEqual).toBe(true);
        expect(layout.textFits).toBe(true);
        expect(layout.images).toBe(true);
        expect(layout.arrowClear).toBe(true);
        await root.screenshot({ path: testInfo.outputPath(`brands-${width}.png`), style: '.site-header {visibility:hidden!important;}' });
    });
}

test('every brand link opens its existing detail page and page assets stay scoped', async ({ page }) => {
    await page.goto('/brand');
    const links = await page.locator('.brand-portfolio-card').evaluateAll(es => es.map(e => ({ href: e.href, name: e.querySelector('h2').textContent, focus: e.querySelector('p').textContent, src: e.querySelector('img')?.src })));
    expect(links).toHaveLength(7);
    for (const item of links) {
        await page.goto('/brand');
        await page.locator(`.brand-portfolio-card[href="${item.href}"]`).click();
        await expect(page).toHaveURL(item.href);
        await expect(page.locator('h1')).toHaveText(item.name);
        await expect(page.locator('.catalog-section>.lead')).toHaveText(item.focus);
        if (item.src) await expect(page.locator('.directory-image')).toHaveAttribute('src', item.src);
        await expect(page.locator('.brand-portfolio')).toHaveCount(0);
        await expect(page.locator('link[rel="stylesheet"][href*="/brand-portfolio-"]')).toHaveCount(0);
    }
    for (const path of ['/', '/tentang-kami', '/produk', '/industri', '/kontak', '/admin/login']) {
        await page.goto(path);
        await expect(page.locator('.brand-portfolio')).toHaveCount(0);
        await expect(page.locator('link[rel="stylesheet"][href*="/brand-portfolio-"]')).toHaveCount(0);
    }
});

test('hover, keyboard navigation and reduced motion preserve card geometry', async ({ page }) => {
    await page.goto('/brand');
    const card = page.locator('.brand-portfolio-card').first();
    await card.scrollIntoViewIfNeeded();
    const before = await card.boundingBox();
    await card.hover();
    await expect(card).toHaveCSS('border-top-color', 'rgb(8, 76, 161)');
    expect(await card.boundingBox()).toEqual(before);
    await card.focus();
    await expect(card).toHaveCSS('outline-style', 'solid');
    await page.keyboard.press('Tab');
    await expect(page.locator('.brand-portfolio-card').nth(1)).toBeFocused();
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await card.hover();
    await expect(card).toHaveCSS('transition-duration', '0s');
    await expect(card.locator(':scope > .icon')).toHaveCSS('transform', 'none');
    await card.focus();
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/brand\/wolong$/);
});

test('portfolio remains readable and navigable without JavaScript', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    try {
        const page = await context.newPage();
        await page.goto('/brand');
        await expect(page.locator('.brand-portfolio-card h2')).toHaveText(names);
        await page.locator('.brand-portfolio-card').last().click();
        await expect(page).toHaveURL(/\/brand\/blu-c$/);
    } finally { await context.close(); }
});
