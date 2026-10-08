import { test, expect } from '@playwright/test';

for (const [width, height] of [[1440, 900], [1024, 768], [768, 1024], [390, 844], [320, 740]]) {
    test(`split login layout at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height });
        await page.goto('/admin/login');
        await page.evaluate(() => document.fonts.ready);
        await page.locator('.cms-login-logo').evaluate(image => image.decode());
        await expect(page.getByRole('heading', { name: 'Masuk ke CMS', exact: true })).toBeVisible();
        await expect(page.locator('input:not([type=hidden])')).toHaveCount(2);
        await expect(page.locator('input[name=_token]')).toHaveCount(1);
        await expect(page.locator('form')).toHaveAttribute('action', /\/admin\/login$/);
        await expect(page.locator('.cms-login-logo')).toHaveAttribute('src', /\/images\/company-logo-footer.png$/);
        await expect(page.locator('.cms-login-logo')).toHaveAttribute('alt', 'SMART - Equipment & Parts Solutions');
        const shell = await page.locator('.cms-login-shell').boundingBox();
        const brand = await page.locator('.cms-login-brand').boundingBox();
        const form = await page.locator('.cms-login-form-panel').boundingBox();
        const logo = await page.locator('.cms-login-logo-viewport').boundingBox();
        expect(logo.x + logo.width / 2).toBeCloseTo(brand.x + brand.width / 2, 0);
        expect(logo.width).toBeGreaterThan(width > 700 ? 240 : 200);
        const naturalRatio = await page.locator('.cms-login-logo').evaluate(image => image.naturalWidth / image.naturalHeight);
        const imageBox = await page.locator('.cms-login-logo').boundingBox();
        expect(imageBox.width / imageBox.height).toBeCloseTo(naturalRatio, 2);
        await expect(page.locator('.cms-login-logo')).toHaveCSS('filter', 'none');
        await expect(page.locator('.cms-login-brand')).toHaveCSS('background-image', 'none');
        const panelColor = await page.locator('.cms-login-brand').evaluate(element => getComputedStyle(element).backgroundColor);
        await expect(page.locator('.cms-login-submit')).toHaveCSS('background-color', panelColor);
        await expect(page.locator('.cms-login-submit')).toHaveCSS('color', 'rgb(255, 255, 255)');
        for (const selector of ['.cms-login-identity', '.cms-login-logo-viewport', '.cms-login-logo']) {
            await expect(page.locator(selector)).toHaveCSS('background-color', 'rgba(0, 0, 0, 0)');
            await expect(page.locator(selector)).toHaveCSS('background-image', 'none');
            await expect(page.locator(selector)).toHaveCSS('border-top-width', '0px');
            await expect(page.locator(selector)).toHaveCSS('box-shadow', 'none');
            await expect(page.locator(selector)).toHaveCSS('outline-style', 'none');
        }
        const cornerAlpha = await page.locator('.cms-login-logo').evaluate(image => {
            const canvas = document.createElement('canvas');
            canvas.width = image.naturalWidth;
            canvas.height = image.naturalHeight;
            const context = canvas.getContext('2d');
            context.drawImage(image, 0, 0);
            return context.getImageData(0, 0, 1, 1).data[3];
        });
        expect(cornerAlpha).toBe(0);
        await expect(page.getByRole('link', { name: 'Kembali ke Website' })).toHaveCount(1);
        await expect(page.locator('.cms-login-form-panel .cms-login-back')).toHaveCount(0);
        const back = page.locator('.cms-login-brand .cms-login-back');
        await expect(back.locator(':scope > svg:first-child')).toHaveAttribute('data-lucide', 'arrow-left');
        await expect(back).toHaveCSS('background-color', 'rgb(255, 255, 255)');
        const backBox = await back.boundingBox();
        const welcome = await page.locator('.cms-login-welcome').boundingBox();
        expect(logo.y + logo.height).toBeLessThanOrEqual(welcome.y);
        const identity = page.locator('.cms-login-identity');
        await identity.evaluate(element => element.style.transform = 'none');
        const originalLogo = await page.locator('.cms-login-logo-viewport').boundingBox();
        expect(logo.y - originalLogo.y).toBeCloseTo(20, 1);
        expect(logo.x).toBe(originalLogo.x);
        expect(logo.width).toBe(originalLogo.width);
        expect(logo.height).toBe(originalLogo.height);
        expect(await page.locator('.cms-login-welcome').boundingBox()).toEqual(welcome);
        expect(await back.boundingBox()).toEqual(backBox);
        expect(await page.locator('.cms-login-form-panel').boundingBox()).toEqual(form);
        await identity.evaluate(element => element.style.removeProperty('transform'));
        expect(backBox.x).toBeCloseTo(welcome.x, 0);
        expect(backBox.y).toBeGreaterThanOrEqual(welcome.y + welcome.height);
        expect(backBox.y + backBox.height).toBeLessThan(brand.y + brand.height);
        if (width > 700) {
            expect(welcome.y).toBeGreaterThan(brand.y + brand.height * 0.4);
            expect(backBox.y - welcome.y - welcome.height).toBeCloseTo(32, 0);
        }
        expect(shell.x + shell.width / 2).toBeCloseTo(width / 2, 0);
        if (shell.height + 80 < height) expect(shell.y + shell.height / 2).toBeCloseTo(height / 2, 0);
        if (width > 700) {
            expect(brand.y).toBe(form.y);
            expect(brand.width).toBeCloseTo(form.width, 0);
            expect(brand.x + brand.width).toBeCloseTo(form.x, 0);
        } else {
            expect(brand.y + brand.height).toBeCloseTo(form.y, 0);
        }
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
        await page.screenshot({ path: testInfo.outputPath(`login-${width}.png`), fullPage: true });
    });
}

test('blue submit hover preserves dimensions and white text', async ({ page }) => {
    await page.goto('/admin/login');
    const submit = page.locator('.cms-login-submit');
    const initialBox = await submit.boundingBox();
    await submit.hover();
    await expect(submit).toHaveCSS('background-color', 'rgb(10, 38, 61)');
    await expect(submit).toHaveCSS('color', 'rgb(255, 255, 255)');
    expect(await submit.boundingBox()).toEqual(initialBox);
});

test('password visibility is keyboard accessible and never submits the login form', async ({ page }) => {
    await page.goto('/admin/login');
    const input = page.locator('#password');
    await input.fill('Example-password-123');
    const toggle = page.locator('[data-password-toggle]');
    await expect(toggle).toHaveAttribute('type', 'button');
    await toggle.focus();
    await page.keyboard.press('Enter');
    await expect(input).toHaveAttribute('type', 'text');
    await expect(input).toHaveValue('Example-password-123');
    await expect(toggle).toHaveAccessibleName('Sembunyikan password');
    await expect(toggle).toHaveAttribute('aria-pressed', 'true');
    await expect(page).toHaveURL(/\/admin\/login$/);
    await page.keyboard.press('Space');
    await expect(input).toHaveAttribute('type', 'password');
    await expect(toggle).toHaveAccessibleName('Tampilkan password');
    await page.getByRole('button', { name: 'Masuk', exact: true }).click();
    await expect(page.locator('#email')).toBeFocused();
    await page.getByRole('link', { name: 'Kembali ke Website' }).click();
    await expect(page.locator('.hero')).toBeVisible();
});

test('invalid credentials keep existing errors, email, and usable form', async ({ page }, testInfo) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/admin/login');
    await page.locator('#email').fill('login-ui-check@example.invalid');
    await page.locator('#password').fill('NotARealPassword123');
    await page.getByRole('button', { name: 'Masuk', exact: true }).click();
    await expect(page.getByRole('alert')).toContainText('Email atau password tidak sesuai.');
    await expect(page.getByRole('alert')).toBeFocused();
    await expect(page.locator('#email')).toHaveValue('login-ui-check@example.invalid');
    await expect(page.locator('#email')).toHaveAttribute('aria-invalid', 'true');
    await expect(page.locator('#password')).toHaveValue('');
    await expect(page.getByRole('button', { name: 'Masuk', exact: true })).toBeEnabled();
    expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
    await page.screenshot({ path: testInfo.outputPath('login-error-mobile.png'), fullPage: true });
});

test('existing administrator login still redirects to the dashboard and logs out', async ({ page }) => {
    test.skip(!process.env.CMS_TEST_EMAIL || !process.env.CMS_TEST_PASSWORD, 'Set local CMS credentials.');
    await page.goto('/admin/login');
    await page.locator('#email').fill(process.env.CMS_TEST_EMAIL);
    await page.locator('#password').fill(process.env.CMS_TEST_PASSWORD);
    await page.getByRole('button', { name: 'Masuk', exact: true }).click();
    await expect(page.getByRole('heading', { name: 'Dashboard', exact: true })).toBeVisible();
    await expect(page.locator('.cms-login-shell')).toHaveCount(0);
    await page.getByRole('button', { name: 'Keluar', exact: true }).click();
    await expect(page).toHaveURL(/\/admin\/login$/);
});

test('without JavaScript the login form remains available and visibility toggle is hidden', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    try {
        const page = await context.newPage();
        await page.goto('/admin/login');
        await expect(page.locator('[data-password-toggle]')).toBeHidden();
        await expect(page.locator('#password')).toHaveAttribute('type', 'password');
        await expect(page.getByRole('button', { name: 'Masuk', exact: true })).toBeEnabled();
        await expect(page.locator('input[name=_token]')).toHaveCount(1);
    } finally { await context.close(); }
});
