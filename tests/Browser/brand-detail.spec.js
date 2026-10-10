import { test, expect } from '@playwright/test';

for (const [width, columns] of [[1440, 3], [1280, 3], [768, 2], [390, 1], [375, 1], [320, 1]]) {
    test(`brand detail geometry, logos and products at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await page.goto('/brand/wolong');
        const root = page.locator('.brand-detail');
        await expect(root).toHaveCSS('font-family', /SMART Inter/);
        await expect(root.locator('h1')).toHaveText('Wolong');
        await expect(root.locator('.brand-hero-description')).toBeVisible();
        await expect(root.locator('.brand-hero-description')).toHaveCSS('font-size', '16px');
        await expect(root.locator('.product-card')).toHaveCount(3);
        await root.locator('img').evaluateAll(es => Promise.all(es.map(e => { e.loading = 'eager'; return e.decode(); })));
        const layout = await root.evaluate(e => {
            const rect = el => el.getBoundingClientRect();
            const logo = e.querySelector('.brand-detail-logo');
            const intro = e.querySelector('.brand-detail-intro');
            const cards = [...e.querySelectorAll('.product-card')];
            const row = cards.filter(c => Math.abs(rect(c).top - rect(cards[0]).top) < 1);
            return {
                columns: getComputedStyle(e.querySelector('.product-grid')).gridTemplateColumns.split(' ').length,
                stacked: rect(logo).top >= rect(intro).bottom,
                overflow: document.documentElement.scrollWidth > innerWidth,
                images: [...e.querySelectorAll('img')].every(i => i.naturalWidth > 0 && getComputedStyle(i).objectFit === 'contain'),
                textFits: [...e.querySelectorAll('h1,h2,h3,p,a')].every(c => c.scrollWidth <= c.clientWidth + 1 && rect(c).right <= innerWidth + 1 && rect(c).left >= 0),
                links: row.map(c => rect(c.querySelector('.text-link')).bottom),
            };
        });
        expect(layout.columns).toBe(columns);
        expect(layout.stacked).toBe(width <= 640);
        expect(layout.overflow).toBe(false);
        expect(layout.images).toBe(true);
        expect(layout.textFits).toBe(true);
        expect(Math.max(...layout.links) - Math.min(...layout.links)).toBeLessThan(1);
        await root.screenshot({ path: testInfo.outputPath(`brand-detail-${width}.png`), style: '.site-header {visibility:hidden!important;}' });
    });
}

test('all brands keep their own logo, profile, categories and product relationships', async ({ page }, testInfo) => {
    await page.goto('/brand');
    const brands = await page.locator('.brand-portfolio-card').evaluateAll(es => es.map(e => ({ name: e.querySelector('h2').textContent, focus: e.querySelector('p').textContent, href: e.href, image: e.querySelector('img')?.src })));
    expect(brands).toHaveLength(7);
    for (const brand of brands) {
        await page.goto(brand.href);
        await expect(page.locator('.brand-detail h1')).toHaveText(brand.name);
        await expect(page.locator('.breadcrumb [aria-current="page"]')).toHaveText(brand.name);
        await expect(page.locator('.brand-detail-summary')).toHaveText(brand.focus);
        const summary = await page.locator('.brand-hero-description').innerText();
        const profile = await page.locator('.brand-detail-profile-copy p').first().innerText();
        expect(summary.length).toBeGreaterThan(0);
        // The current CMS has one sentence per brand; preserve it until enriched.
        expect(summary).toBe(profile);
        expect(summary).not.toMatch(/\.\.\.|\u2026/);
        expect(summary).toMatch(/[.!?]$/);
        await expect(page.locator('.brand-hero-description')).toHaveCSS('white-space', 'normal');
        await expect(page.locator('.brand-hero-description')).toHaveCSS('overflow', 'visible');
        await expect(page.locator('.brand-hero-description')).toHaveCSS('-webkit-line-clamp', 'none');
        await expect(page.locator('.brand-detail-logo img')).toHaveAttribute('src', brand.image);
        expect((await page.locator('.brand-detail-profile-copy p').innerText()).length).toBeGreaterThan(0);
        await expect(page.locator('.brand-technology-item h3')).toHaveText([brand.focus]);
        expect((await page.locator('.product-card .eyebrow').allTextContents()).every(n => n.trim() === brand.name)).toBe(true);
        expect(await page.locator('.product-card').count()).toBeLessThanOrEqual(6);
        const category = page.locator('.brand-technology-item').first();
        const categoryUrl = await category.getAttribute('href');
        await category.click();
        await expect(page).toHaveURL(categoryUrl);
        await expect(page.locator('h1')).toHaveText(brand.focus);
        await page.goto(brand.href);
        const productName = await page.locator('.product-card h3').first().innerText();
        const productUrl = await page.locator('.product-card .text-link').first().getAttribute('href');
        await page.locator('.product-card .text-link').first().click();
        await expect(page).toHaveURL(productUrl);
        await expect(page.locator('h1')).toHaveText(productName);
        await page.goto(brand.href);
        await page.locator('.brand-all-products').click();
        expect(new URL(page.url()).searchParams.get('brand')).toBeTruthy();
        expect((await page.locator('.product-card .eyebrow').allTextContents()).every(n => n.trim() === brand.name)).toBe(true);
    }
    for (const slug of ['oli', 'qdos', 'blu-c']) {
        await page.setViewportSize({ width: 1280, height: 900 });
        await page.goto(`/brand/${slug}`);
        await page.locator('.brand-detail-logo img').evaluate(e => e.decode());
        await page.screenshot({ path: testInfo.outputPath(`${slug}-hero.png`) });
    }
});

test('keyboard links and reduced motion work while shared CTA stays intact', async ({ page }) => {
    await page.goto('/brand/wolong');
    const category = page.locator('.brand-technology-item');
    await category.focus();
    await expect(category).toHaveCSS('outline-style', 'solid');
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await category.hover();
    await expect(category).toHaveCSS('transition-duration', '0s');
    await expect(category.locator(':scope > .icon')).toHaveCSS('transform', 'none');
    await category.focus();
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/produk\/electric-motors-generators$/);
    await page.goto('/brand/wolong');
    await page.locator('.inquiry-band a').first().click();
    await expect(page).toHaveURL(/\/kontak$/);
});

test('detail works without JavaScript and assets stay off approved pages', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    try {
        const page = await context.newPage();
        await page.goto('/brand/wolong');
        await expect(page.locator('.brand-detail h1')).toBeVisible();
        await expect(page.locator('.product-card')).toHaveCount(3);
        await page.locator('.brand-all-products').click();
        await expect(page).toHaveURL(/\/produk\?brand=/);
        for (const path of ['/', '/brand', '/tentang-kami', '/produk', '/industri', '/kontak', '/admin/login']) {
            await page.goto(path);
            await expect(page.locator('.brand-detail')).toHaveCount(0);
            await expect(page.locator('link[rel="stylesheet"][href*="/brand-detail-"]')).toHaveCount(0);
        }
    } finally { await context.close(); }
});
