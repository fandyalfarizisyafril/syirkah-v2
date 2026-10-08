import { test, expect } from '@playwright/test';

test('admin branding matches the public signature and preserves header actions', async ({ page }, testInfo) => {
    test.skip(!process.env.CMS_TEST_EMAIL || !process.env.CMS_TEST_PASSWORD, 'Set local CMS credentials.');
    const errors = [];
    page.on('pageerror', error => errors.push(error.message));
    await page.goto('/admin/login');
    await page.locator('#email').fill(process.env.CMS_TEST_EMAIL);
    await page.locator('#password').fill(process.env.CMS_TEST_PASSWORD);
    await page.getByRole('button', { name: 'Masuk', exact: true }).click();
    await expect(page.getByRole('heading', { name: 'Dashboard', exact: true })).toBeVisible();

    const header = page.locator('.admin-topbar');
    const branding = header.locator('.admin-brand');
    await expect(branding).toHaveAttribute('href', /\/admin$/);
    await expect(branding.locator('.signature-name')).toHaveText('PT. Syirkah Mandiri Artomoro');
    await expect(branding.locator('.signature-tagline')).toHaveText('Content Management');
    await expect(branding.locator('img')).toHaveAttribute('src', /\/images\/company-logo.png$/);
    await expect(branding).toHaveCSS('font-family', /SMART Inter/);
    await expect(branding.locator('.signature-copy')).toHaveCSS('border-left-width', '1px');
    await expect(header.locator('.wordmark-symbol')).toHaveCount(0);
    await page.evaluate(() => document.fonts.ready);
    await branding.locator('img').evaluate(image => image.decode());

    for (const width of [1440, 1024, 768, 640, 390, 360, 320]) {
        await page.setViewportSize({ width, height: 900 });
        const brandBox = await branding.boundingBox();
        const actionBox = await header.locator('.actions').boundingBox();
        expect(brandBox.x + brandBox.width).toBeLessThanOrEqual(actionBox.x);
        expect(actionBox.x + actionBox.width).toBeLessThanOrEqual(width);
        for (const selector of ['.signature-image', '.signature-copy', '.signature-name', '.signature-tagline']) {
            const box = await branding.locator(selector).boundingBox();
            expect(box.x + box.width).toBeLessThanOrEqual(brandBox.x + brandBox.width + 1);
        }
        const actionItems = await header.locator('.actions > *').all();
        const actionCenters = [];
        for (const item of actionItems) {
            const box = await item.boundingBox();
            actionCenters.push(box.y + box.height / 2);
        }
        expect(Math.max(...actionCenters) - Math.min(...actionCenters)).toBeLessThan(2);
        expect(await header.evaluate(element => element.scrollWidth <= element.clientWidth)).toBe(true);
        await header.screenshot({ path: testInfo.outputPath(`admin-header-${width}.png`) });
    }

    await header.getByRole('link', { name: 'Akun saya' }).click();
    await expect(page).toHaveURL(/\/admin\/account$/);
    await expect(page.getByRole('heading', { name: 'Akun Saya', exact: true })).toBeVisible();
    await branding.click();
    await expect(page.getByRole('heading', { name: 'Dashboard', exact: true })).toBeVisible();
    const popupPromise = page.waitForEvent('popup');
    await header.getByRole('link', { name: 'Lihat Website' }).click();
    const publicPage = await popupPromise;
    await publicPage.waitForLoadState('domcontentloaded');
    await expect(publicPage.locator('.site-header .signature-tagline')).toHaveText('Equipment & Parts Solutions');
    await expect(publicPage.locator('.admin-brand')).toHaveCount(0);
    await publicPage.close();
    await header.getByRole('button', { name: 'Keluar' }).click();
    await expect(page).toHaveURL(/\/admin\/login$/);
    await expect(page.locator('.cms-login-logo')).toHaveAttribute('src', /\/images\/company-logo-footer.png$/);
    expect(errors).toEqual([]);
});
