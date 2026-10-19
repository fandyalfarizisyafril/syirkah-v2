import { test, expect } from '@playwright/test';
import { execFileSync } from 'node:child_process';

const viewports = [
    { name: 'desktop', width: 1440, height: 1000 },
    { name: 'tablet', width: 768, height: 1024 },
    { name: 'mobile', width: 390, height: 844 },
    { name: 'small-mobile', width: 360, height: 740 },
];

test.describe('publication media gallery', () => {
    test('missing MP4 posters decode one lazy frame without changing masonry or existing posters', async ({ page }) => {
        await page.setViewportSize({ width: 1440, height: 320 });
        const requests = [];
        page.on('request', request => { if (/\/brand\/[^/]+\/video/.test(request.url())) requests.push(request.url()); });
        await page.addInitScript(() => {
            window.maxPreviewDecoders = 0;
            new MutationObserver(() => { window.maxPreviewDecoders = Math.max(window.maxPreviewDecoders, document.querySelectorAll('[data-frame-state="loading"]').length); })
                .observe(document, { subtree: true, attributes: true, attributeFilter: ['data-frame-state'] });
        });
        await page.goto('/publikasi/media');
        await page.waitForTimeout(300);
        expect(requests).toEqual([]);
        const pending = await page.locator('[data-gallery-item]').evaluateAll(links => links.map(link => JSON.parse(link.dataset.galleryItem)).filter(item => item.source === 'mp4' && !item.thumbnail));
        expect(pending.length).toBeGreaterThan(0);
        for (const item of pending) {
            const card = page.locator(`[data-gallery-key="${item.key}"]`);
            const before = await card.evaluate(el => [el.offsetLeft, el.offsetTop, el.offsetWidth, el.offsetHeight]);
            await card.scrollIntoViewIfNeeded();
            const img = card.locator('[data-gallery-frame]');
            await expect(img).toBeVisible({ timeout: 15000 });
            await expect(img).toHaveAttribute('src', /^blob:/);
            const pixels = await img.evaluate(el => {
                const canvas = document.createElement('canvas'); canvas.width = 80; canvas.height = 45;
                const context = canvas.getContext('2d'); context.drawImage(el, 0, 0, 80, 45);
                const colors = [...context.getImageData(0, 0, 80, 45).data].filter((_, i) => i % 4 !== 3);
                return Math.max(...colors) - Math.min(...colors);
            });
            expect(pixels).toBeGreaterThan(10);
            expect(await card.evaluate(el => [el.offsetLeft, el.offsetTop, el.offsetWidth, el.offsetHeight])).toEqual(before);
            await card.locator('.gallery-play').click();
            await expect(page.locator('dialog video')).toBeVisible();
            await expect(page.locator('dialog video')).toHaveJSProperty('paused', true);
            await page.keyboard.press('Escape');
            await expect(page.locator('dialog video')).toHaveCount(0);
        }
        expect(await page.evaluate(() => window.maxPreviewDecoders)).toBe(1);
        const count = requests.length;
        await page.evaluate(() => scrollTo(0, 0));
        await page.locator('[data-gallery-grid]').scrollIntoViewIfNeeded();
        await page.waitForTimeout(300);
        expect(requests.length).toBe(count);
        const posted = await page.locator('[data-gallery-item]').evaluateAll(links => links.map(link => JSON.parse(link.dataset.galleryItem)).filter(item => item.source === 'mp4' && item.thumbnail).map(item => item.url));
        expect(requests.some(url => posted.includes(url))).toBe(false);
    });

    test('MP4 preview errors and data-saving mode keep the existing fallback usable', async ({ page, browser, baseURL }) => {
        await page.route('**/brand/*/video', route => route.abort());
        await page.goto('/publikasi/media');
        const item = await page.locator('[data-gallery-item]').evaluateAll(links => links.map(link => JSON.parse(link.dataset.galleryItem)).find(item => item.source === 'mp4' && !item.thumbnail));
        const card = page.locator(`[data-gallery-key="${item.key}"]`);
        await card.scrollIntoViewIfNeeded();
        await expect(card.locator('[data-frame-state]')).toHaveAttribute('data-frame-state', 'fallback');
        await expect(card.locator('[data-gallery-frame]')).toHaveCount(0);
        await expect(card.locator('.gallery-play')).toBeVisible();
        const context = await browser.newContext();
        await context.addInitScript(() => Object.defineProperty(navigator, 'connection', { value: { saveData: true } }));
        const saving = await context.newPage(); const requests = [];
        saving.on('request', request => { if (/\/brand\/[^/]+\/video/.test(request.url())) requests.push(request.url()); });
        await saving.goto(baseURL + '/publikasi/media');
        await saving.locator(`[data-gallery-key="${item.key}"]`).scrollIntoViewIfNeeded();
        await saving.waitForTimeout(300);
        expect(requests).toEqual([]);
        await expect(saving.locator(`[data-gallery-key="${item.key}"] .gallery-play`)).toBeVisible();
        await context.close();
    });

    test('publication dropdown, separate pages, legacy URL and keyboard navigation', async ({ page }) => {
        await page.setViewportSize({ width: 1440, height: 1000 });
        await page.goto('/publikasi');
        await expect(page).toHaveURL(/\/publikasi\/perusahaan$/);
        await expect(page.locator('h1')).toHaveText('Publikasi Perusahaan');
        await expect(page.locator('[data-gallery-grid]')).toHaveCount(0);
        const dropdown = page.locator('[data-publications-nav]');
        const summary = dropdown.locator('summary');
        const media = dropdown.getByRole('link', { name: 'Media', exact: true });
        await summary.hover();
        await expect(summary).toHaveAttribute('aria-expanded', 'true');
        await media.click();
        await expect(page).toHaveURL(/\/publikasi\/media$/);
        await expect(page.locator('h1')).toHaveText('Galeri & Media');
        await expect(page.locator('#publications-title')).toHaveCount(0);
        await expect(summary).toHaveClass(/active/);
        await summary.focus();
        await page.keyboard.press('ArrowDown');
        await expect(dropdown.getByRole('link', { name: 'Publikasi Perusahaan' })).toBeFocused();
        await page.keyboard.press('ArrowDown');
        await expect(media).toBeFocused();
        await expect(media).toHaveAttribute('aria-current', 'page');
        await page.keyboard.press('Escape');
        await expect(summary).toHaveAttribute('aria-expanded', 'false');
        await expect(summary).toBeFocused();
        await page.keyboard.press('Enter');
        await expect(media).toBeVisible();
        await page.locator('h1').click();
        await expect(media).not.toBeVisible();
        for (const width of [768, 390, 375]) {
            await page.setViewportSize({ width, height: 900 });
            await page.getByRole('button', { name: 'Buka navigasi' }).click();
            await summary.click();
            await expect(media).toBeVisible();
            await summary.click();
            await expect(media).not.toBeVisible();
            await summary.click();
            await page.keyboard.press('Escape');
            await expect(page.locator('#primary-nav')).toBeVisible();
            await expect(media).not.toBeVisible();
            await page.keyboard.press('Escape');
            await expect(page.locator('#primary-nav')).not.toBeVisible();
            expect(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth + 1)).toBe(false);
        }
    });

    test('CMS gallery editor previews uploads and switches sources without saving data', async ({ page, baseURL }) => {
        const preview = JSON.parse(execFileSync('php', ['-r', `
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();
Illuminate\\Support\\Facades\\URL::forceRootUrl(getenv('GALLERY_TEST_URL'));
view()->share('errors', new Illuminate\\Support\\ViewErrorBag);
Illuminate\\Support\\Facades\\Auth::setUser((new App\\Models\\User)->forceFill(['name'=>'Gallery Preview','role'=>'editor']));
$brand = App\\Models\\Brand::where('slug', 'bredel')->first();
echo json_encode(['html'=>app(App\\Http\\Controllers\\Admin\\GalleryController::class)->create()->render(), 'brandHtml'=>$brand ? app(App\\Http\\Controllers\\Admin\\CatalogController::class)->form('brands', $brand->id)->render() : null, 'video'=>($brand->video['path'] ?? null) ? Illuminate\\Support\\Facades\\Storage::disk('public')->path($brand->video['path']) : null]);
        `], { encoding: 'utf8', env: { ...process.env, GALLERY_TEST_URL: baseURL } }));
        await page.route('**/__gallery_editor_preview', route => route.fulfill({ contentType: 'text/html', body: preview.html }));
        await page.route('**/__brand_poster_preview', route => route.fulfill({ contentType: 'text/html', body: preview.brandHtml }));
        await page.route('https://www.youtube-nocookie.com/**', route => route.fulfill({ contentType: 'text/html', body: '<p>Embed boundary test</p>' }));
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        await page.goto('/__gallery_editor_preview');
        await expect(page.locator('#media_file')).toBeVisible();
        await expect(page.locator('#youtube_url')).not.toBeVisible();
        await expect(page.locator('#status')).toHaveValue('draft');
        await page.locator('#media_file').setInputFiles('public/images/company-logo.png');
        await expect(page.locator('[data-gallery-preview] img')).toBeVisible();
        await expect(page.locator('[name=width]')).not.toHaveValue('16');
        await page.locator('#media_file').setInputFiles([]);
        await page.locator('#source').selectOption('mp4');
        await page.locator('#poster').setInputFiles('public/images/company-logo.png');
        await expect(page.locator('[data-gallery-poster-preview]')).toBeVisible();
        await expect(page.locator('[data-gallery-poster-preview]')).toHaveAttribute('src', /^blob:/);
        if (preview.video) {
            await page.locator('#media_file').setInputFiles(preview.video);
            await expect(page.locator('[data-gallery-preview] video')).toBeVisible();
            await expect.poll(async () => page.locator('[data-gallery-preview] video').evaluate(v => v.videoHeight > v.videoWidth && v.paused)).toBe(true);
        }
        await page.locator('#source').selectOption('youtube');
        await expect(page.locator('#media_file')).toBeDisabled();
        await page.locator('#youtube_url').fill('https://example.com/video');
        await page.locator('#youtube_url').blur();
        await expect(page.locator('[data-gallery-preview-error]')).toHaveText('URL YouTube tidak valid.');
        await page.locator('#youtube_url').fill('https://youtu.be/M7lc1UVf-VE');
        await page.locator('#youtube_url').blur();
        await expect(page.locator('[data-gallery-preview] iframe')).toBeVisible();
        for (const width of [1440, 768, 390, 375]) {
            await page.setViewportSize({ width, height: 900 });
            expect(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth + 1)).toBe(false);
        }
        if (preview.brandHtml) {
            await page.goto('/__brand_poster_preview');
            await page.locator('#video_poster').setInputFiles('public/images/company-logo.png');
            await expect(page.locator('[data-video-poster-preview]')).toBeVisible();
            await expect(page.locator('[data-video-poster-preview]')).toHaveAttribute('src', /^blob:/);
            await expect(page.locator('[data-video-preview] video')).toHaveAttribute('poster', /^blob:/);
        }
        expect(errors).toEqual([]);
    });

    test('masonry preserves media ratios, column counts, and keyboard modal controls', async ({ page }) => {
        const errors = [];
        const videoRequests = [];
        page.on('pageerror', error => errors.push(error.message));
        page.on('request', request => { if (/\/brand\/[^/]+\/video/.test(request.url())) videoRequests.push(request.url()); });
        await page.goto('/publikasi/media');
        await expect(page.getByRole('heading', { name: 'Galeri & Media', level: 1 })).toBeVisible();
        await expect(page.locator('#legal-title')).toHaveCount(0);
        const grid = page.locator('[data-gallery-grid]');
        for (const [width, columns] of [[1440, 3], [1280, 3], [768, 2], [390, 1], [375, 1]]) {
            await page.setViewportSize({ width, height: 900 });
            await expect(grid).toHaveCSS('--gallery-columns', String(columns));
            await grid.scrollIntoViewIfNeeded();
            await page.waitForTimeout(750);
            const layout = await grid.evaluate(el => {
                const cards = [...el.children].map(card => ({ x: card.offsetLeft, y: card.offsetTop, w: card.offsetWidth, h: card.offsetHeight }));
                return {
                    overflow: document.documentElement.scrollWidth > innerWidth + 1,
                    overlap: cards.some((a, i) => cards.slice(i + 1).some(b => a.x < b.x + b.w - 1 && b.x < a.x + a.w - 1 && a.y < b.y + b.h - 1 && b.y < a.y + a.h - 1)),
                    distorted: [...el.querySelectorAll('img')].some(img => img.naturalWidth && Math.abs(img.clientWidth / img.clientHeight - img.naturalWidth / img.naturalHeight) > .02),
                };
            });
            expect(layout).toEqual({ overflow: false, overlap: false, distorted: false });
        }
        const automaticUrls = await page.locator('[data-gallery-item]').evaluateAll(links => links.map(link => JSON.parse(link.dataset.galleryItem)).filter(item => item.source === 'mp4' && !item.thumbnail).map(item => item.url));
        expect(videoRequests.every(url => automaticUrls.includes(url))).toBe(true);
        const videos = page.locator('[data-gallery-item]').filter({ has: page.locator('.gallery-play') });
        if (await videos.count()) {
            await videos.first().click();
            await expect(page.locator('dialog')).toBeVisible();
            expect(await page.locator('dialog').evaluate(el => !el.querySelector('video')?.autoplay)).toBe(true);
            await page.keyboard.press('Escape');
            await expect(page.locator('dialog')).not.toBeVisible();
            await expect(page.locator('dialog video, dialog iframe')).toHaveCount(0);
            await expect(videos.first()).toBeFocused();
        }
        expect(errors).toEqual([]);
    });

    test('photo and YouTube lightboxes, thumbnail fallback, and load-more work without database writes', async ({ page }) => {
        // Browser-only fixtures reuse an existing image; no media is inserted into the CMS.
        await page.route('**/publikasi/media', async route => {
            const response = await route.fetch();
            const html = await response.text();
            const body = html.includes('data-gallery-more')
                ? html.replace(/data-next="[^"]*"/, 'data-next="/__gallery_test_page"')
                : html.replace('<div class="gallery-more">', '<div class="gallery-more"><a href="#gallery-title" data-gallery-more data-next="/__gallery_test_page">Muat Lebih Banyak</a>');
            await route.fulfill({ response, body });
        });
        const fixture = (key, source, extra) => {
            const item = { key, source, title: 'Browser fixture', description: '', width: 536, height: 154, url: '/images/company-logo.png', thumbnail: '/images/company-logo.png', ...extra };
            const json = JSON.stringify(item).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
            return `<article class="gallery-card" data-gallery-key="${key}"><a class="gallery-open" href="${item.url}" data-gallery-item="${json}"><div class="gallery-thumbnail" style="--media-ratio:536 / 154"><img src="${item.thumbnail}" width="536" height="154" data-gallery-thumbnail></div><div class="gallery-caption"><h3>${item.title}</h3></div></a></article>`;
        };
        await page.route('**/__gallery_test_page', route => route.fulfill({ json: { html: fixture('test-photo', 'photo', {}) + fixture('test-youtube', 'youtube', { youtube_id: 'M7lc1UVf-VE', url: 'https://www.youtube.com/watch?v=M7lc1UVf-VE', thumbnail: '/__missing_gallery_thumbnail' }), next: null } }));
        await page.route('**/__missing_gallery_thumbnail', route => route.fulfill({ status: 404, body: '' }));
        await page.route('https://www.youtube-nocookie.com/**', route => route.fulfill({ contentType: 'text/html', body: '<p>Embed boundary test</p>' }));
        await page.goto('/publikasi/media');
        await page.locator('[data-gallery-more]').click();
        await expect(page.locator('[data-gallery-key="test-photo"]')).toBeVisible();
        await expect(page.locator('[data-gallery-more]')).not.toBeVisible();
        await page.locator('[data-gallery-key="test-photo"] a').click();
        await expect(page.locator('[data-gallery-viewer] img')).toBeVisible();
        await page.locator('[data-gallery-close]').click();
        await expect(page.locator('[data-gallery-viewer] img')).toHaveCount(0);
        await expect(page.locator('[data-gallery-key="test-youtube"] img')).not.toBeVisible();
        await page.locator('[data-gallery-key="test-youtube"] a').click();
        await expect(page.locator('[data-gallery-viewer] iframe')).toHaveAttribute('src', 'https://www.youtube-nocookie.com/embed/M7lc1UVf-VE?playsinline=1&rel=0');
        await page.keyboard.press('Escape');
        await expect(page.locator('[data-gallery-viewer] iframe')).toHaveCount(0);
    });

    test('gallery is readable without JavaScript and respects reduced motion', async ({ browser, baseURL }) => {
        const context = await browser.newContext({ javaScriptEnabled: false, viewport: { width: 390, height: 844 } });
        const page = await context.newPage();
        await page.goto(baseURL + '/publikasi/media');
        await expect(page.locator('[data-gallery-grid]')).toHaveCSS('column-count', '1');
        for (const link of await page.locator('[data-gallery-item]').all()) await expect(link).toBeVisible();
        await context.close();
        const reduced = await browser.newContext({ reducedMotion: 'reduce' });
        const motionPage = await reduced.newPage();
        await motionPage.goto(baseURL + '/publikasi/media');
        for (const card of await motionPage.locator('.gallery-card').all()) await expect(card).toHaveCSS('opacity', '1');
        await reduced.close();
    });
});

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
