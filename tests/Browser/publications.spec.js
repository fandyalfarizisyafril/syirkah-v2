import { test, expect } from '@playwright/test';
import { execFileSync } from 'node:child_process';

const publicationCounts = () => JSON.parse(execFileSync('php', ['-r', `
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();
$visible = App\\Models\\Publication::published()->get()->filter(fn ($entry) => app(App\\Services\\PublicationStorage::class)->exists($entry->file_path));
echo json_encode(['public' => $visible->count(), 'admin' => min(15, App\\Models\\Publication::count())]);
`], { encoding: 'utf8' }));

for (const [width, columns] of [[1440, 3], [1280, 3], [768, 2], [390, 1], [375, 1]]) {
    test(`publication library and preview at ${width}px`, async ({ page }, testInfo) => {
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        await page.setViewportSize({ width, height: 1000 });
        expect((await page.goto('/publikasi')).status()).toBe(200);
        await expect(page.locator('h1')).toHaveText('Transparansi dan informasi perusahaan.');
        await expect(page.locator('.publications-empty')).toContainText('Dokumen legalitas belum tersedia');
        await expect(page.locator('.publication-card')).toHaveCount(publicationCounts().public);
        const demo = page.locator('.publication-card').filter({ hasText: 'Panduan Penggunaan Website SMART (Demo)' });
        await expect(demo.locator('h3')).toHaveText('Panduan Penggunaan Website SMART (Demo)');
        await expect(demo.locator('.publication-placeholder')).toBeVisible();
        await expect(demo.locator('.publication-placeholder')).toHaveJSProperty('tagName', 'svg');
        await expect(demo.locator('.publication-download')).toHaveText('Unduh');
        for (const card of await page.locator('.publication-card').all()) {
            const previewBox = await card.locator('.publication-preview').boundingBox();
            expect(Math.abs(previewBox.height / previewBox.width - 11 / 20)).toBeLessThan(0.01);
            for (const link of await card.locator('.publication-actions a').all()) {
                const box = await link.boundingBox();
                expect(box.height).toBeGreaterThanOrEqual(44);
                expect(box.x + box.width).toBeLessThanOrEqual(width);
            }
        }
        const boxes = await page.locator('.publication-card').evaluateAll(cards => cards.map(card => {
            const box = card.getBoundingClientRect();
            const actions = card.querySelector('.publication-actions').getBoundingClientRect();
            return { top: box.top, height: box.height, actions: actions.top };
        }));
        for (const box of boxes) {
            for (const sibling of boxes.filter(other => Math.abs(other.top - box.top) < 1)) {
                expect(Math.abs(sibling.height - box.height)).toBeLessThan(1);
                expect(Math.abs(sibling.actions - box.actions)).toBeLessThan(1);
            }
        }
        expect(await page.locator('.publications-grid').evaluate(el => getComputedStyle(el).gridTemplateColumns.split(' ').length)).toBe(columns);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
        await page.screenshot({ path: testInfo.outputPath(`publications-${width}.png`), fullPage: true });
        const preview = demo.getByRole('link', { name: /^Lihat dokumen / });
        await preview.focus();
        await expect(preview).toHaveCSS('outline-style', 'solid');
        await preview.press('Enter');
        await expect(page.locator('h1')).toHaveText('Panduan Penggunaan Website SMART (Demo)');
        await expect(page.locator('.publication-pdf')).toBeVisible();
        await expect(page.getByRole('link', { name: 'Buka di tab baru' })).toBeVisible();
        const pdf = await page.request.get(await page.locator('.publication-pdf').getAttribute('data'));
        expect(pdf.status()).toBe(200);
        expect(pdf.headers()['content-type']).toBe('application/pdf');
        expect(pdf.headers()['content-disposition']).toContain('inline');
        expect(pdf.headers()['cache-control']).toContain('no-store');
        expect((await pdf.body()).toString('latin1')).toContain('BUKAN DOKUMEN RESMI');
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBe(true);
        await page.screenshot({ path: testInfo.outputPath(`preview-${width}.png`), fullPage: true });
        await page.getByRole('link', { name: 'Kembali ke Publikasi' }).click();
        await expect(page).toHaveURL(/\/publikasi$/);
        expect(errors).toEqual([]);
    });
}

test('navigation, download, drafts, old route and shared components', async ({ page }) => {
    await page.goto('/');
    const before = await page.locator('.inquiry-band').innerHTML();
    const footer = await page.locator('.site-footer').innerHTML();
    await page.locator('#primary-nav').getByRole('link', { name: 'Publikasi', exact: true }).click();
    await expect(page).toHaveURL(/\/publikasi$/);
    await expect(page.locator('#primary-nav a.active')).toHaveText('Publikasi');
    expect(await page.locator('.inquiry-band').innerHTML()).toBe(before);
    expect(await page.locator('.site-footer').innerHTML()).toBe(footer);
    await expect(page.locator('.publications-page')).not.toContainText('Contoh Materi Publikasi');
    const [download] = await Promise.all([
        page.waitForEvent('download'), page.getByRole('link', { name: 'Unduh Panduan Penggunaan Website SMART (Demo)', exact: true }).click(),
    ]);
    expect(download.suggestedFilename()).toBe('website-guide-demo.pdf');
    const redirect = await page.request.get('/industri', { maxRedirects: 0 });
    expect(redirect.status()).toBe(301);
    expect(redirect.headers().location).toMatch(/\/publikasi$/);
    const ids = JSON.parse(execFileSync('php', ['-r', `require 'vendor/autoload.php'; $app = require 'bootstrap/app.php'; $app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap(); echo App\\Models\\Publication::where('status', 'draft')->pluck('id')->toJson();`], { encoding: 'utf8' }));
    for (const id of ids) {
        for (const endpoint of ['preview', 'file', 'thumbnail', 'unduh']) {
            expect((await page.request.get(`/publikasi/${id}/${endpoint}`)).status()).toBe(404);
        }
    }
});

// Render the actual CMS views without creating accounts or changing local sessions/data.
const adminView = (view, baseURL) => execFileSync('php', ['-r', `
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();
Illuminate\\Support\\Facades\\URL::forceRootUrl(getenv('PUBLICATION_TEST_URL'));
view()->share('errors', new Illuminate\\Support\\ViewErrorBag);
Illuminate\\Support\\Facades\\Auth::setUser((new App\\Models\\User)->forceFill(['name' => 'Preview Editor', 'role' => 'editor']));
$controller = app(App\\Http\\Controllers\\Admin\\PublicationController::class);
echo ${view === 'form' ? '$controller->create()' : '$controller->index(Illuminate\\Http\\Request::create("/admin/publications"))'}->render();
`], { encoding: 'utf8', env: { ...process.env, PUBLICATION_TEST_URL: baseURL } });

for (const width of [1440, 768, 390, 375]) {
    test(`CMS publication form and list at ${width}px`, async ({ page, baseURL }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await page.route('**/admin/publications/create', route => route.fulfill({ contentType: 'text/html', body: adminView('form', baseURL) }));
        await page.goto('/admin/publications/create');
        await expect(page.getByLabel('Status', { exact: false })).toHaveValue('draft');
        await expect(page.getByLabel('Izinkan tombol Unduh')).not.toBeChecked();
        await expect(page.locator('[data-publication-approval]')).toBeHidden();
        await page.getByLabel('Kategori').selectOption('legalitas');
        await page.getByLabel('Status').selectOption('published');
        await expect(page.locator('[data-publication-approval]')).toBeVisible();
        await expect(page.locator('[name=legal_confirmation]')).toHaveJSProperty('required', true);
        await page.getByLabel('Status').selectOption('draft');
        await expect(page.locator('[data-publication-approval]')).toBeHidden();
        await expect(page.locator('[name=legal_confirmation]')).toHaveJSProperty('required', false);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
        await page.screenshot({ path: testInfo.outputPath(`publication-form-${width}.png`), fullPage: true });
        await page.route('**/admin/publications', route => route.fulfill({ contentType: 'text/html', body: adminView('index', baseURL) }));
        await page.goto('/admin/publications');
        await expect(page.locator('tbody tr')).toHaveCount(publicationCounts().admin);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
        await page.screenshot({ path: testInfo.outputPath(`publication-admin-${width}.png`), fullPage: true });
    });
}

test('publications remain accessible without JavaScript', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL, viewport: { width: 390, height: 844 } });
    try {
        const page = await context.newPage();
        await page.goto('/publikasi');
        await page.getByRole('link', { name: /^Lihat dokumen / }).first().click();
        await expect(page.locator('h1')).toBeVisible();
        await expect(page.getByRole('link', { name: 'Buka di tab baru' })).toBeVisible();
    } finally { await context.close(); }
});

test('existing thumbnails stay contained and failed loads reveal the fallback', async ({ page }) => {
    await page.goto('/publikasi');
    const image = page.locator('[data-publication-thumbnail]').first();
    await expect(image).toBeAttached();
    await image.scrollIntoViewIfNeeded();
    await expect.poll(() => image.evaluate(el => el.complete && el.naturalWidth > 0)).toBe(true);
    await expect(image).toHaveCSS('object-fit', 'contain');
    const src = await image.getAttribute('src');
    await page.route(src, route => route.abort());
    await page.reload();
    const failedImage = page.locator('[data-publication-thumbnail]').first();
    await failedImage.locator('..').scrollIntoViewIfNeeded();
    await expect(failedImage).toBeHidden();
    await expect(failedImage.locator('..').locator('.publication-placeholder')).toBeVisible();
    await expect(failedImage.locator('..').locator('.publication-file-type')).toHaveText('PDF');
});

test('approved public pages and deferred image assets remain available', async ({ page }) => {
    test.setTimeout(120000);
    for (const width of [1440, 768, 390, 375]) {
        await page.setViewportSize({ width, height: 1000 });
        for (const path of ['/', '/tentang-kami', '/produk', '/brand', '/brand/wolong', '/kontak']) {
            expect((await page.goto(path)).status(), path).toBe(200);
            await expect(page.locator('h1')).toHaveCount(1);
            await expect(page.locator('link[rel=stylesheet][href*="/publications-"]')).toHaveCount(0);
            const failures = await page.locator('img').evaluateAll(async images => {
                // Verify deferred assets without mistaking intentionally unloaded slides for broken images.
                const sources = [...new Set(images.map(image => image.getAttribute('src') || image.dataset.src).filter(Boolean))];
                const results = await Promise.all(sources.map(async src => {
                    const image = new Image();
                    image.src = src;
                    try { await image.decode(); return null; } catch { return src; }
                }));
                return results.filter(Boolean);
            });
            expect(failures, `Image assets at ${path}`).toEqual([]);
            expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1), `Overflow at ${path}`).toBe(true);
        }
    }
});
