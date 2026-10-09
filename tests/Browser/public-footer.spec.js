import { test, expect } from '@playwright/test';

for (const width of [1920, 1440, 1100, 768, 390, 320]) {
    test(`footer composition, readable content and no overflow at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await page.goto('/');
        const footer = page.locator('.site-footer');
        await footer.scrollIntoViewIfNeeded();
        await footer.locator('img').evaluate(i => i.decode());
        await page.evaluate(() => document.fonts.ready);
        await expect(footer).toHaveCSS('background-color', 'rgb(11, 41, 66)');
        await expect(footer).toHaveCSS('font-family', /SMART Inter/);
        await expect(footer.locator('nav[aria-label="Kategori produk"] a')).toHaveCount(7);
        await expect(footer.locator('.smart-footer-copyright')).toContainText('2026 Fandy Alfarizi Syafril.');
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
        const geometry = await footer.evaluate(e => {
            const brand = e.querySelector('.smart-footer-brand').getBoundingClientRect();
            const nav = e.querySelector('.smart-footer-navigation').getBoundingClientRect();
            const columns = [...e.querySelectorAll('.smart-footer-column')].map(c => c.getBoundingClientRect());
            return {
                stacked: nav.top >= brand.bottom - 1,
                sideBySide: nav.left >= brand.right,
                columnRows: columns.map(c => c.top),
                contained: [...e.querySelectorAll('a,p,address,h2')].every(child => {
                    const r = child.getBoundingClientRect();
                    // The logo viewport deliberately crops transparent padding in the original PNG.
                    return r.left >= -1 && r.right <= innerWidth + 1 && (child.matches('.smart-footer-logo') || child.scrollWidth <= child.clientWidth + 1);
                }),
            };
        });
        expect(geometry.contained).toBe(true);
        expect(width > 1100 ? geometry.sideBySide : geometry.stacked).toBe(true);
        if (width > 640) expect(new Set(geometry.columnRows).size).toBe(1);
        else expect(geometry.columnRows[2]).toBeGreaterThan(geometry.columnRows[1]);
        await footer.screenshot({ path: testInfo.outputPath(`footer-${width}.png`), style: '.site-header { visibility:hidden !important; }' });
    });
}

test('existing destinations, contact protocols and inquiry action still work', async ({ page }) => {
    await page.goto('/');
    const footer = page.locator('.site-footer');
    const hrefs = await footer.locator('a').evaluateAll(es => es.map(e => e.href));
    for (const href of new Set(hrefs.filter(href => href.startsWith('http://127.0.0.1:8000')))) {
        expect((await page.request.get(href)).status()).toBe(200);
    }
    await expect(footer.locator('a[href^="tel:"]')).toHaveAttribute('href', 'tel:+6281266723815');
    await expect(footer.locator('a[href^="mailto:"]')).toHaveCount(2);
    await expect(footer.locator('a[href^="https://wa.me/"]')).toHaveCount(1);
    await expect(footer.locator('a[href^="https://wa.me/"]')).toHaveAttribute('rel', 'noopener noreferrer');
    await footer.locator('.smart-footer-action a').click();
    await expect(page).toHaveURL(/\/kontak$/);
    await page.locator('.smart-footer-bottom a').last().click();
    await expect(page).toHaveURL(/\/privasi$/);
    await page.locator('.smart-footer-logo').click();
    await expect(page).toHaveURL(/\/$/);
});

test('keyboard and reduced-motion footer links work without animation', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.goto('/');
    const action = page.locator('.smart-footer-action a');
    await action.focus();
    await expect(action).toBeFocused();
    await expect(action).toHaveCSS('transition-duration', '0s');
    await expect(action).toHaveCSS('outline-style', 'solid');
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/kontak$/);
});

test('footer retains working navigation with JavaScript disabled', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    try {
        const page = await context.newPage();
        await page.goto('/');
        await page.locator('.smart-footer-products a').first().click();
        await expect(page).toHaveURL(/\/produk\/electric-motors-generators$/);
        await expect(page.locator('.site-footer')).toHaveCount(1);
    } finally { await context.close(); }
});
