import { test, expect } from '@playwright/test';

const viewports = [
    { name: 'desktop', width: 1440, height: 1000 },
    { name: 'tablet', width: 768, height: 1024 },
    { name: 'mobile', width: 390, height: 844 },
    { name: 'small-mobile', width: 360, height: 740 },
];

for (const viewport of viewports) {
    test(`public website: ${viewport.name}`, async ({ page }, testInfo) => {
        await page.setViewportSize(viewport);
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        for (const path of ['/', '/tentang-kami', '/produk', '/produk/electric-motors-generators', '/brand', '/brand/wolong', '/industri', '/kontak', '/search?q=pump', '/privasi']) {
            const response = await page.goto(path);
            expect(response.status()).toBe(200);
            await expect(page.locator('h1')).toHaveCount(1);
            const layout = await page.evaluate(() => ({
                overflow: document.documentElement.scrollWidth > window.innerWidth + 1,
                brokenImages: Array.from(document.images).filter(image => !image.complete || image.naturalWidth === 0).map(image => image.src),
            }));
            expect(layout.overflow, `Overflow at ${path}`).toBe(false);
            expect(layout.brokenImages, `Images at ${path}`).toEqual([]);
            if (path === '/' || path === '/kontak' || path === '/produk') {
                await page.screenshot({ path: testInfo.outputPath(`${path === '/' ? 'home' : path.slice(1)}-${viewport.name}.png`), fullPage: true });
            }
        }
        expect(errors).toEqual([]);
        await page.goto('/');
        await expect(page.locator('.hero-image.is-active')).toBeVisible();
        if (viewport.width < 901) {
            const toggle = page.getByRole('button', { name: 'Buka navigasi' });
            await toggle.click();
            await expect(page.locator('#primary-nav')).toBeVisible();
            await page.keyboard.press('Escape');
            await expect(page.locator('#primary-nav')).toBeHidden();
            await expect(toggle).toBeFocused();
            await toggle.click();
            await page.locator('#primary-nav').getByRole('link', { name: 'Produk', exact: true }).click();
            await expect(page).toHaveURL(/\/produk$/);
        }
    });
}

test('inquiry form has working native validation and privacy link', async ({ page }) => {
    await page.goto('/kontak');
    await page.getByRole('button', { name: 'Kirim Inquiry' }).click();
    await expect(page.locator('#name')).toBeFocused();
    await expect(page).toHaveURL(/\/kontak$/);
    await page.getByRole('link', { name: 'Kebijakan Privasi', exact: true }).first().click();
    await expect(page.getByRole('heading', { name: 'Kebijakan Privasi', exact: true })).toBeVisible();
});

test('admin login, catalog forms, responsive dashboard, and logout', async ({ page }, testInfo) => {
    test.skip(!process.env.CMS_TEST_EMAIL || !process.env.CMS_TEST_PASSWORD, 'Set CMS_TEST_EMAIL and CMS_TEST_PASSWORD for an existing local administrator.');
    await page.goto('/admin');
    await page.getByRole('textbox', { name: 'Email', exact: true }).fill(process.env.CMS_TEST_EMAIL);
    await page.getByLabel(/^Password/).fill(process.env.CMS_TEST_PASSWORD);
    await page.getByRole('button', { name: 'Masuk', exact: true }).click();
    await expect(page.getByRole('heading', { name: 'Dashboard', exact: true })).toBeVisible();
    for (const viewport of viewports.filter(item => item.name !== 'small-mobile')) {
        await page.setViewportSize(viewport);
        for (const path of ['/admin', '/admin/catalog/products', '/admin/catalog/products/create', '/admin/catalog/categories', '/admin/catalog/brands', '/admin/catalog/industries', '/admin/settings', '/admin/inquiries', '/admin/account']) {
            const response = await page.goto(path);
            expect(response.status()).toBe(200);
            expect(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth + 1), `Admin overflow: ${path}`).toBe(true);
            if (path === '/admin' || path === '/admin/catalog/products/create') {
                await page.screenshot({ path: testInfo.outputPath(`${path === '/admin' ? 'dashboard' : 'product-form'}-${viewport.name}.png`), fullPage: true });
            }
        }
    }
    await page.goto('/admin/catalog/products/create');
    await page.getByRole('textbox', { name: 'Nama', exact: true }).fill('Test Motor Browser');
    await expect(page.getByLabel('Slug URL')).toHaveValue('test-motor-browser');
    await page.getByRole('button', { name: 'Tambah Spesifikasi' }).click();
    await expect(page.locator('.spec-row')).toHaveCount(2);
    await page.getByRole('button', { name: 'Hapus spesifikasi' }).last().click();
    await expect(page.locator('.spec-row')).toHaveCount(1);
    await page.getByRole('button', { name: 'Keluar', exact: true }).click();
    await expect(page).toHaveURL(/\/admin\/login$/);
});
