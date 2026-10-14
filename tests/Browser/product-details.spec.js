import { test, expect } from '@playwright/test';

const productPaths = [
    '/produk/air-compressor/demo-rotary-screw-compressor',
    '/produk/electric-motors-generators/demo-induction-motor',
    '/produk/vibration-technology/demo-external-vibrator',
];
const categoryPaths = ['/produk/electric-motors-generators', '/produk/air-compressor', '/produk/vibration-technology'];

for (const width of [1440, 1280, 768, 390, 375]) {
    test(`detail templates fit and reveal at ${width}px`, async ({ page }) => {
        await page.setViewportSize({ width, height: 900 });
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        for (const path of [productPaths[0], categoryPaths[0]]) {
            expect((await page.goto(path)).status()).toBe(200);
            await expect(page.locator('.technical-detail-page')).toHaveCSS('font-family', /SMART Inter/);
            await page.locator('.technical-detail-page img').evaluateAll(images => Promise.all(images.map(image => { image.loading = 'eager'; return image.decode(); })));
            await page.evaluate(async () => {
                for (let y = 0; y < document.documentElement.scrollHeight; y += innerHeight * .7) {
                    scrollTo({ top: y, behavior: 'instant' });
                    await new Promise(resolve => setTimeout(resolve, 50));
                }
            });
            await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
            const layout = await page.locator('.technical-detail-page').evaluate(root => ({
                overflow: document.documentElement.scrollWidth > innerWidth,
                columns: getComputedStyle(root.querySelector('.product-grid')).gridTemplateColumns.split(' ').length,
                images: [...root.querySelectorAll('img')].every(image => image.naturalWidth > 0 && getComputedStyle(image).objectFit === 'contain'),
                textFits: [...root.querySelectorAll('h1,h2,h3,p,li,a')].every(el => el.scrollWidth <= el.clientWidth + 1 || getComputedStyle(el).display === 'inline'),
            }));
            expect(layout).toEqual({ overflow: false, columns: width > 900 ? 3 : width > 640 ? 2 : 1, images: true, textFits: true });
            await expect(page.locator('.site-header [data-scroll-reveal], .inquiry-band [data-scroll-reveal], .site-footer [data-scroll-reveal]')).toHaveCount(0);
        }
        expect(errors).toEqual([]);
    });
}

test('three products keep category, brand, applications, related products and inquiry context', async ({ page }) => {
    for (const path of productPaths) {
        await page.goto(path);
        await expect(page.locator('#product-main-image')).toBeVisible();
        await expect(page.getByRole('heading', { name: 'Tentang Produk', exact: true })).toHaveCount(1);
        await expect(page.getByRole('heading', { name: 'Aplikasi Produk', exact: true })).toHaveCount(1);
        await expect(page.getByRole('heading', { name: 'Spesifikasi Produk', exact: true })).toHaveCount(0);
        await expect(page.locator('.gallery-thumb')).toHaveCount(0);
        const category = path.split('/').slice(0, 3).join('/');
        await expect(page.locator('.product-category-link')).toHaveAttribute('href', new RegExp(`${category}$`));
        const related = await page.locator('.product-grid .text-link').evaluateAll(links => links.map(link => new URL(link.href).pathname));
        expect(related.length).toBeGreaterThan(0);
        expect(related.length).toBeLessThanOrEqual(3);
        expect(related.every(url => url.startsWith(category + '/') && url !== path)).toBe(true);
        for (const link of related) expect((await page.request.get(link)).status()).toBe(200);
        const brand = await page.locator('.product-showcase-info > .eyebrow').getAttribute('href');
        expect((await page.request.get(brand)).status()).toBe(200);
        const inquiry = page.locator('.product-inquiry').getByRole('link', { name: 'Request Inquiry' });
        const id = new URL(await inquiry.getAttribute('href')).searchParams.get('product');
        await inquiry.click();
        await expect(page.locator('select[name="product_id"]')).toHaveValue(id);
    }
});

test('category form retains existing GET destination, category selection, search, brand and reset', async ({ page }) => {
    for (const path of categoryPaths) {
        await page.goto(path);
        const name = await page.locator('h1').innerText();
        await expect(page.locator('#category option:checked')).toHaveText(name);
        await expect(page.locator('.result-count')).toHaveText('3 produk tersedia');
        const product = await page.locator('.product-card h3').first().innerText();
        const brand = await page.locator('.product-card .eyebrow').first().innerText();
        const categoryId = await page.locator('#category').inputValue();
        expect(await page.locator('.related-brand-links a').count()).toBeGreaterThan(0);
        const links = await page.locator('.related-brand-links a,.category-other-links a').evaluateAll(es => es.map(e => e.href));
        for (const link of links) expect((await page.request.get(link)).status()).toBe(200);
        await page.getByLabel('Pencarian', { exact: true }).fill(product);
        await page.getByLabel('Brand', { exact: true }).selectOption({ label: brand });
        await page.getByRole('button', { name: 'Cari', exact: true }).click();
        expect(new URL(page.url()).pathname).toBe('/produk');
        expect(new URL(page.url()).searchParams.get('category')).toBe(categoryId);
        await expect(page.locator('.product-card h3')).toHaveText([product]);
        await page.goto(`${path}?q=NoMatch987654`);
        await expect(page.locator('.empty-state h2')).toHaveText('Tidak ada produk yang ditemukan.');
        await page.locator('.filter-bar').getByRole('link', { name: 'Reset filter' }).click();
        await expect(page).toHaveURL(/\/produk$/);
        await expect(page.locator('#category')).toHaveValue('');
    }
});

test('gallery keeps existing image swap and adds keyboard-accessible selected state', async ({ page }) => {
    await page.goto('/produk');
    const images = await page.locator('.product-card img').evaluateAll(es => es.slice(0, 2).map(e => e.src));
    // A response-only fixture exercises multiple images without editing CMS data.
    await page.route(`**${productPaths[0]}`, async route => {
        const response = await route.fetch();
        const gallery = `<div class="gallery" role="group" aria-label="Test gallery">${images.map((src, i) => `<button type="button" class="gallery-thumb" data-gallery-src="${src}" aria-label="Lihat gambar ${i + 1}" aria-pressed="${i === 0}"><img src="${src}" alt="Gambar ${i + 1}" width="80" height="80"></button>`).join('')}</div>`;
        await route.fulfill({ response, body: (await response.text()).replace('<div class="product-showcase-gallery">', `<div class="product-showcase-gallery">${gallery}`) });
    });
    await page.goto(productPaths[0]);
    const second = page.getByRole('button', { name: 'Lihat gambar 2' });
    await second.focus();
    await page.keyboard.press('Enter');
    await expect(second).toHaveAttribute('aria-pressed', 'true');
    await expect(page.getByRole('button', { name: 'Lihat gambar 1' })).toHaveAttribute('aria-pressed', 'false');
    await expect(page.locator('#product-main-image')).toHaveAttribute('src', images[1]);
    await expect(page.locator('#product-main-image')).toHaveAttribute('alt', 'Gambar 2');
});

test('detail assets are isolated and no-JS or reduced motion remains readable', async ({ page, browser, baseURL }) => {
    for (const path of ['/produk', '/', '/tentang-kami', '/brand', '/brand/wolong', '/publikasi', '/admin/login']) {
        expect((await page.goto(path)).status()).toBe(200);
        await expect(page.locator('script[src*="/product-details-"],link[href*="/product-details-"]')).toHaveCount(0);
    }
    for (const options of [{ javaScriptEnabled: false }, { reducedMotion: 'reduce' }]) {
        const context = await browser.newContext({ baseURL, ...options });
        try {
            const local = await context.newPage();
            for (const path of [productPaths[0], categoryPaths[0]]) {
                await local.goto(path);
                await expect(local.locator('.product-card').first()).toHaveCSS('opacity', '1');
                await expect(local.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
            }
        } finally { await context.close(); }
    }
});
