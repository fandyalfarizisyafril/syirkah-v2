import { test, expect } from '@playwright/test';

test('Hero Beranda admin uses existing navigation and responsive forms for all five slides', async ({ page }, testInfo) => {
    test.skip(!process.env.CMS_TEST_EMAIL || !process.env.CMS_TEST_PASSWORD, 'Set CMS_TEST_EMAIL and CMS_TEST_PASSWORD for a local administrator.');
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    await page.goto('/admin/login');
    await page.getByRole('textbox', { name: 'Email', exact: true }).fill(process.env.CMS_TEST_EMAIL);
    await page.getByLabel(/^Password/).fill(process.env.CMS_TEST_PASSWORD);
    await page.getByRole('button', { name: 'Masuk', exact: true }).click();
    await expect(page.getByRole('heading', { name: 'Dashboard', exact: true })).toBeVisible();
    await page.getByRole('navigation', { name: 'Navigasi CMS' }).getByRole('link', { name: 'Hero Beranda', exact: true }).click();
    await expect(page).toHaveURL(/\/admin\/hero$/);
    const keys = ['engineering', 'mechanical', 'electrical', 'instrumentation', 'oil-spill-response'];
    for (const width of [1440, 768, 390, 320]) {
        await page.setViewportSize({ width, height: 1000 });
        await page.goto('/admin/hero');
        await expect(page.locator('tbody tr')).toHaveCount(5);
        await page.locator('tbody img').evaluateAll(images => Promise.all(images.map(image => image.decode())));
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
        await page.screenshot({ path: testInfo.outputPath(`hero-admin-${width}.png`), fullPage: true });
        for (const key of keys) {
            const response = await page.goto(`/admin/hero/${key}/edit`);
            expect(response.status()).toBe(200);
            await expect(page.getByLabel('Label Navigasi', { exact: false })).not.toBeEmpty();
            await expect(page.getByLabel('Headline - Baris 1', { exact: false })).not.toBeEmpty();
            await expect(page.getByLabel('Deskripsi', { exact: false })).not.toBeEmpty();
            await expect(page.getByRole('button', { name: 'Simpan Hero', exact: true })).toBeVisible();
            await page.locator('.admin-preview').evaluate(image => image.decode());
            expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
            if (key === 'engineering') await page.screenshot({ path: testInfo.outputPath(`hero-edit-${width}.png`), fullPage: true });
        }
    }
    await page.getByLabel('Headline - Baris 1', { exact: false }).fill('');
    await page.getByRole('button', { name: 'Simpan Hero', exact: true }).click();
    await expect(page.getByLabel('Headline - Baris 1', { exact: false })).toBeFocused();
    expect(errors).toEqual([]);
    await page.getByRole('button', { name: 'Keluar', exact: true }).click();
    await expect(page).toHaveURL(/\/admin\/login$/);
});
