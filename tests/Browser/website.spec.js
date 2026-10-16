import { test, expect } from '@playwright/test';
import { execFileSync } from 'node:child_process';

const viewports = [
    { name: 'desktop', width: 1440, height: 1000 },
    { name: 'tablet', width: 768, height: 1024 },
    { name: 'mobile', width: 390, height: 844 },
    { name: 'small-mobile', width: 360, height: 740 },
];

test.describe('admin independent scrolling', () => {
    let previews;
    test.beforeAll(async ({ baseURL }) => {
        // Render real CMS views in memory, without login changes or database writes.
        previews = JSON.parse(execFileSync('php', ['-r', `
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();
Illuminate\\Support\\Facades\\URL::forceRootUrl(getenv('ADMIN_SCROLL_TEST_URL'));
view()->share('errors', new Illuminate\\Support\\ViewErrorBag);
Illuminate\\Support\\Facades\\Auth::setUser((new App\\Models\\User)->forceFill(['name' => 'Layout Preview', 'role' => 'administrator']));
$dashboard = app(App\\Http\\Controllers\\Admin\\DashboardController::class);
$catalog = app(App\\Http\\Controllers\\Admin\\CatalogController::class);
$views = ['dashboard' => $dashboard->index()->render(), 'settings' => $dashboard->settings()->render()];
foreach (['categories','brands','products','industries'] as $module) {
    $views[$module] = $catalog->form($module)->render();
}
$views['product-list'] = $catalog->index(Illuminate\\Http\\Request::create('/admin/catalog/products'), 'products')->render();
$views['hero'] = app(App\\Http\\Controllers\\Admin\\HeroController::class)->edit('engineering')->render();
$views['publications'] = app(App\\Http\\Controllers\\Admin\\PublicationController::class)->create()->render();
$inquiry = (new App\\Models\\Inquiry)->forceFill(['id'=>0,'name'=>'Layout Preview','company'=>'Preview','email'=>'preview@example.test','phone'=>'00000000','reference_number'=>'LAYOUT-PREVIEW','status'=>'new','consent_at'=>now(),'message'=>'Preview only']);
$views['inquiry'] = $dashboard->inquiry($inquiry)->render();
echo json_encode($views);
        `], { encoding: 'utf8', maxBuffer: 8 * 1024 * 1024, env: { ...process.env, ADMIN_SCROLL_TEST_URL: baseURL } }));
    });

    for (const [width, height] of [[1440, 900], [1280, 720], [768, 800], [390, 844], [375, 667]]) {
        test(`header and navigation stay visible at ${width}px`, async ({ page }) => {
            test.setTimeout(120000);
            await page.setViewportSize({ width, height });
            let html;
            await page.route('**/__admin_scroll_preview', route => route.fulfill({ contentType: 'text/html', body: html }));
            const errors = [];
            page.on('pageerror', error => errors.push(error.message));
            for (const [name, body] of Object.entries(previews)) {
                html = body;
                await page.goto('/__admin_scroll_preview');
                await expect(page.locator('.admin-main')).toHaveCSS('overflow-y', 'auto');
                const geometry = () => page.evaluate(() => {
                    const box = selector => {
                        const { x, y, width, height } = document.querySelector(selector).getBoundingClientRect();
                        return { x, y, width, height };
                    };
                    return { header: box('.admin-topbar'), sidebar: box('.admin-sidebar'), main: box('.admin-main') };
                });
                const before = await geometry();
                await page.locator('.admin-main').evaluate(el => { el.scrollTop = el.scrollHeight; });
                expect(await geometry(), name).toEqual(before);
                expect(before.header.y, name).toBe(0);
                expect(before.sidebar.y, name).toBeCloseTo(before.header.height, 0);
                expect(before.main.y + before.main.height, name).toBeCloseTo(height, 0);
                expect(await page.evaluate(() => ({
                    windowY: scrollY,
                    pageOverflow: document.documentElement.scrollHeight > innerHeight + 1,
                    horizontalOverflow: document.documentElement.scrollWidth > innerWidth + 1,
                    mainOverflow: document.querySelector('.admin-main').scrollWidth > document.querySelector('.admin-main').clientWidth + 1,
                })), name).toEqual({ windowY: 0, pageOverflow: false, horizontalOverflow: false, mainOverflow: false });
                if (['products', 'settings', 'publications'].includes(name)) {
                    expect(await page.locator('.admin-main').evaluate(el => el.scrollTop), name).toBeGreaterThan(0);
                    await expect(page.locator('.admin-main').getByRole('button', { name: /^Simpan/ }).last()).toBeInViewport();
                }
                const lastMenu = page.locator('.admin-sidebar nav a').last();
                await lastMenu.focus();
                await expect(lastMenu).toBeInViewport();
                expect(await geometry(), name).toEqual(before);
            }
            expect(errors).toEqual([]);
        });
    }

    test('long navigation scrolls independently and form controls remain usable', async ({ page }) => {
        await page.setViewportSize({ width: 1280, height: 420 });
        await page.route('**/__admin_scroll_preview', route => route.fulfill({ contentType: 'text/html', body: previews.products }));
        await page.goto('/__admin_scroll_preview');
        const sidebar = page.locator('.admin-sidebar');
        const main = page.locator('.admin-main');
        await main.evaluate(el => { el.scrollTop = 300; });
        await sidebar.evaluate(el => { el.scrollTop = el.scrollHeight; });
        expect(await sidebar.evaluate(el => el.scrollTop)).toBeGreaterThan(0);
        expect(await main.evaluate(el => el.scrollTop)).toBe(300);
        await expect(page.locator('.admin-topbar')).toBeInViewport();
        await page.getByLabel('Status', { exact: false }).selectOption('draft');
        await expect(page.locator('#status')).toHaveValue('draft');
        await page.getByRole('button', { name: 'Tambah Spesifikasi' }).click();
        await expect(page.locator('.spec-row')).toHaveCount(2);
        await page.getByRole('button', { name: 'Hapus spesifikasi' }).last().click();
        await expect(page.locator('.spec-row')).toHaveCount(1);
        await page.locator('.admin-main button[type="submit"]').click();
        await expect(page.locator('#name')).toBeFocused();
        await expect(page.locator('#name')).toBeInViewport();
        expect(await page.evaluate(() => scrollY)).toBe(0);
    });

    test('public pages and login retain document scrolling', async ({ page }) => {
        for (const path of ['/', '/produk', '/publikasi', '/admin/login']) {
            await page.goto(path);
            await expect(page.locator('body')).not.toHaveClass(/admin-body/);
            await expect(page.locator('body')).not.toHaveCSS('overflow-y', 'hidden');
        }
    });
});

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
