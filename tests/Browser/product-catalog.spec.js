import { test, expect } from '@playwright/test';

for (const [width, columns] of [[1920, 3], [1440, 3], [1024, 3], [768, 2], [390, 1], [320, 1]]) {
    test(`catalog images, grid and controls at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await page.goto('/produk');
        const catalog = page.locator('.catalog-page');
        await expect(catalog).toHaveCSS('font-family', /SMART Inter/);
        await expect(page.locator('.product-card')).toHaveCount(12);
        await expect(page.locator('.category-grid .category-item')).toHaveCount(7);
        await expect(page.locator('.category-grid img')).toHaveCount(0);
        await page.locator('.product-image img').evaluateAll(es => Promise.all(es.map(e => { e.loading = 'eager'; return e.decode(); })));
        const layout = await catalog.evaluate(e => {
            const cards = [...e.querySelectorAll('.product-card')];
            const rect = el => el.getBoundingClientRect();
            const row = cards.filter(c => Math.abs(rect(c).top - rect(cards[0]).top) < 1);
            return {
                columns: getComputedStyle(e.querySelector('.product-grid')).gridTemplateColumns.split(' ').length,
                categoryColumns: getComputedStyle(e.querySelector('.category-grid')).gridTemplateColumns.split(' ').length,
                overflow: document.documentElement.scrollWidth > innerWidth,
                controls: [...e.querySelectorAll('.filter-bar input,.filter-bar select,.filter-bar .button')].map(c => rect(c).height),
                heights: row.map(c => rect(c).height),
                links: row.map(c => rect(c.querySelector('.text-link')).bottom),
                images: cards.every(c => {
                    const image = c.querySelector('img');
                    const area = rect(c.querySelector('.product-image'));
                    return image.naturalWidth > 0 && getComputedStyle(image).objectFit === 'contain' && Math.abs(area.width / area.height - 4 / 3) < .02;
                }),
                textFits: [...e.querySelectorAll('h1,h2,h3,p,a,select,input')].every(c => rect(c).right <= innerWidth + 1 && rect(c).left >= 0 && (c.tagName === 'SELECT' || c.scrollWidth <= c.clientWidth + 1)),
            };
        });
        expect(layout.columns).toBe(columns);
        expect(layout.categoryColumns).toBe(columns);
        expect(layout.overflow).toBe(false);
        expect(layout.images).toBe(true);
        expect(layout.textFits).toBe(true);
        expect(layout.controls.every(h => h >= 48 && h <= 49)).toBe(true);
        expect(Math.max(...layout.heights) - Math.min(...layout.heights)).toBeLessThan(1);
        expect(Math.max(...layout.links) - Math.min(...layout.links)).toBeLessThan(1);
        await page.screenshot({ path: testInfo.outputPath(`catalog-${width}.png`), fullPage: true });
    });
}

test('search, each filter, combined filters, empty state and reset work through existing GET form', async ({ page }) => {
    await page.goto('/produk');
    const first = page.locator('.product-card').first();
    const name = await first.locator('h3').innerText();
    const brandName = (await first.locator('.eyebrow').innerText()).trim();
    const detail = await first.locator('.text-link').getAttribute('href');
    const categoryPath = new URL(detail).pathname.split('/').slice(0, 3).join('/');
    const categoryName = (await page.locator(`.category-grid a[href$="${categoryPath}"] h3`).innerText()).trim();
    const submit = () => page.getByRole('button', { name: 'Cari', exact: true }).click();
    const reset = () => page.locator('.catalog-reset').click();
    await page.getByLabel('Pencarian', { exact: true }).fill(name);
    await submit();
    await expect(page.locator('.product-card h3')).toHaveText([name]);
    await expect(page.locator('.result-count')).toHaveText('1 produk');
    await reset();
    await page.getByLabel('Kategori', { exact: true }).selectOption({ label: categoryName });
    await submit();
    expect((await page.locator('.product-card .text-link').evaluateAll(es => es.map(e => new URL(e.href).pathname)))).toEqual(expect.arrayContaining([new URL(detail).pathname]));
    expect(await page.locator('.product-card .text-link').evaluateAll((es, path) => es.every(e => new URL(e.href).pathname.startsWith(path + '/')), categoryPath)).toBe(true);
    await reset();
    await page.getByLabel('Brand', { exact: true }).selectOption({ label: brandName });
    await submit();
    expect(await page.locator('.product-card .eyebrow').allTextContents()).toEqual(expect.arrayContaining([brandName]));
    expect((await page.locator('.product-card .eyebrow').allTextContents()).every(n => n.trim() === brandName)).toBe(true);
    await page.getByLabel('Kategori', { exact: true }).selectOption({ label: categoryName });
    await page.getByLabel('Pencarian', { exact: true }).fill(name);
    await submit();
    await expect(page.locator('.product-card h3')).toHaveText([name]);
    expect(new URL(page.url()).searchParams.has('brand')).toBe(true);
    expect(new URL(page.url()).searchParams.has('category')).toBe(true);
    await page.getByLabel('Pencarian', { exact: true }).fill('NoMatchingEquipment987654');
    await submit();
    await expect(page.locator('.result-count')).toHaveText('0 produk');
    await expect(page.locator('.empty-state h2')).toHaveText('Tidak ada produk yang ditemukan.');
    await page.locator('.empty-state').getByRole('link', { name: 'Reset filter' }).click();
    await expect(page).toHaveURL(/\/produk$/);
    await expect(page.locator('#q')).toHaveValue('');
    await expect(page.locator('#category')).toHaveValue('');
    await expect(page.locator('#brand')).toHaveValue('');
});

test('pagination preserves total and query, and existing detail/category links still work', async ({ page }) => {
    await page.goto('/produk?q=Contoh');
    const total = Number((await page.locator('.result-count').innerText()).split(' ')[0]);
    const firstNames = await page.locator('.product-card h3').allTextContents();
    await expect(page.locator('.pagination .disabled')).toHaveCount(1);
    await page.getByRole('link', { name: 'Halaman berikutnya' }).click();
    await expect(page.locator('.pagination')).toContainText('Halaman 2 dari 2');
    expect(new URL(page.url()).searchParams.get('q')).toBe('Contoh');
    await expect(page.locator('.result-count')).toHaveText(`${total} produk`);
    const secondNames = await page.locator('.product-card h3').allTextContents();
    expect(firstNames.length + secondNames.length).toBe(total);
    expect(secondNames.some(n => firstNames.includes(n))).toBe(false);
    await expect(page.locator('.pagination a[rel="next"]')).toHaveCount(0);
    await page.getByRole('link', { name: 'Halaman sebelumnya' }).click();
    await expect(page.locator('.product-card h3')).toHaveText(firstNames);
    const detail = await page.locator('.product-card .text-link').first().getAttribute('href');
    await page.locator('.product-card .text-link').first().click();
    await expect(page).toHaveURL(detail);
    await expect(page.locator('h1')).toHaveText(firstNames[0]);
    await expect(page.locator('.catalog-page')).toHaveCount(0);
    await page.goto('/produk');
    const category = page.locator('.category-grid a').first();
    const categoryName = await category.locator('h3').innerText();
    const categoryUrl = await category.getAttribute('href');
    await category.click();
    await expect(page).toHaveURL(categoryUrl);
    await expect(page.locator('h1')).toHaveText(categoryName);
    await expect(page.locator('.catalog-page')).toHaveCount(0);
});

test('keyboard focus, hover and reduced motion do not move cards', async ({ page }) => {
    await page.goto('/produk');
    await page.locator('#q').focus();
    await expect(page.locator('#q')).toHaveCSS('outline-style', 'solid');
    await page.keyboard.press('Tab');
    await expect(page.locator('#category')).toBeFocused();
    await page.keyboard.press('Tab');
    await expect(page.locator('#brand')).toBeFocused();
    await page.keyboard.press('Tab');
    await expect(page.getByRole('button', { name: 'Cari', exact: true })).toBeFocused();
    const card = page.locator('.product-card').first();
    const size = () => card.evaluate(e => [e.offsetWidth, e.offsetHeight]);
    const before = await size();
    await card.hover();
    await expect(card).toHaveCSS('border-top-color', 'rgb(8, 76, 161)');
    expect(await size()).toEqual(before);
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await expect(card).toHaveCSS('transition-duration', '0s');
    await expect(card.locator('.text-link .icon')).toHaveCSS('transform', 'none');
});

test('catalog works without JavaScript and styles stay isolated from other pages', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    try {
        const page = await context.newPage();
        await page.goto('/produk');
        await expect(page.locator('.product-card')).toHaveCount(12);
        await expect(page.locator('link[rel="stylesheet"][href*="/catalog-"]')).toHaveCount(1);
        await page.locator('#q').fill('NoMatching987654');
        await page.getByRole('button', { name: 'Cari', exact: true }).click();
        await expect(page.locator('.empty-state')).toBeVisible();
        for (const path of ['/', '/tentang-kami', '/produk/electric-motors-generators', '/brand/wolong', '/industri', '/admin/login']) {
            await page.goto(path);
            await expect(page.locator('link[rel="stylesheet"][href*="/catalog-"]')).toHaveCount(0);
            await expect(page.locator('.catalog-page')).toHaveCount(0);
        }
    } finally { await context.close(); }
});
