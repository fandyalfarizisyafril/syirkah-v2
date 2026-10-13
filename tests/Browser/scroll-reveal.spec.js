import { test, expect } from '@playwright/test';

const settleScroll = async page => {
    await page.evaluate(async () => {
        for (let y = 0; y < document.documentElement.scrollHeight; y += innerHeight * .65) {
            scrollTo({ top: y, behavior: 'instant' });
            await new Promise(resolve => setTimeout(resolve, 45));
        }
    });
    await page.waitForTimeout(1150);
};

for (const width of [1440, 1280, 768, 390, 375]) {
    test(`public reveal is once-only and layout-stable at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 900 });
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        await page.goto('/produk');
        await expect(page.locator('.page-intro')).toHaveAttribute('data-scroll-reveal-state', 'visible');
        const target = page.locator('.category-item').first();
        await expect(target).toHaveAttribute('data-scroll-reveal-state', 'pending');
        const geometry = () => target.evaluate(el => [el.offsetTop, el.offsetWidth, el.offsetHeight]);
        const before = await geometry();
        expect(await page.locator('[data-scroll-reveal-state="pending"]').evaluateAll(elements =>
            elements.every(el => parseInt(el.style.getPropertyValue('--reveal-delay') || '0') <= 360))).toBe(true);
        await target.scrollIntoViewIfNeeded();
        await expect(target).toHaveAttribute('data-scroll-reveal-state', 'revealing');
        await expect(target).toHaveCSS('transition-timing-function', /cubic-bezier\(0.22, 1, 0.36, 1\)/);
        await expect(target).toHaveAttribute('data-scroll-reveal-state', 'visible');
        await expect(target).toHaveCSS('opacity', '1');
        expect(await geometry()).toEqual(before);
        await page.evaluate(() => scrollTo(0, 0));
        await target.scrollIntoViewIfNeeded();
        await expect(target).toHaveAttribute('data-scroll-reveal-state', 'visible');
        await expect(target).toHaveCSS('translate', 'none');
        await page.goto('/publikasi');
        await settleScroll(page);
        await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
        await page.evaluate(() => scrollTo({ top: 0, behavior: 'instant' }));
        await page.screenshot({ path: testInfo.outputPath(`motion-publications-${width}.png`), fullPage: true });
        expect(errors).toEqual([]);
    });
}

test('all public templates are covered without touching protected interactions', async ({ page }) => {
    test.setTimeout(120000);
    await page.goto('/produk');
    const product = await page.locator('.product-card .text-link').first().getAttribute('href');
    const paths = ['/', '/tentang-kami', '/produk', product, '/brand', '/brand/wolong', '/publikasi', '/publikasi/1/preview', '/kontak', '/search?q=pump', '/privasi', '/produk/electric-motors-generators'];
    for (const path of paths) {
        expect((await page.goto(path)).status()).toBe(200);
        await expect(page.locator('[data-public-reveal-root]')).toHaveAttribute('data-scroll-reveal-initialized', 'true');
        expect(await page.locator('[data-scroll-reveal]').count(), path).toBeGreaterThan(0);
        await expect(page.locator('.site-header [data-scroll-reveal], .site-footer [data-scroll-reveal], .inquiry-band [data-scroll-reveal], [data-hero-carousel] [data-scroll-reveal], [data-category-carousel] [data-scroll-reveal], .home-brand-marquee [data-scroll-reveal], .publication-pdf[data-scroll-reveal], .gallery [data-scroll-reveal], input[data-scroll-reveal]')).toHaveCount(0);
        await expect(page.locator('.about-reveal-item[data-scroll-reveal], [data-partner-reveal][data-scroll-reveal]')).toHaveCount(0);
        await settleScroll(page);
        await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1), path).toBe(true);
    }
    await page.goto('/admin/login');
    await expect(page.locator('[data-public-reveal-root], [data-scroll-reveal], script[src*="/scroll-reveal-"], link[href*="/scroll-reveal-"]')).toHaveCount(0);
});

test('reduced motion, unavailable observer and failed initialization stay readable', async ({ browser, baseURL }) => {
    for (const mode of ['reduced', 'missing', 'throws', 'blocked', 'no-js']) {
        const context = await browser.newContext({ baseURL, reducedMotion: mode === 'reduced' ? 'reduce' : 'no-preference', javaScriptEnabled: mode !== 'no-js' });
        try {
            const page = await context.newPage();
            if (mode === 'missing') await page.addInitScript(() => { delete window.IntersectionObserver; });
            if (mode === 'throws') await page.addInitScript(() => { window.IntersectionObserver = class { constructor() { throw new Error('Unavailable observer'); } }; });
            if (mode === 'blocked') await page.route('**/scroll-reveal-*.js', route => route.abort());
            await page.goto('/produk');
            await expect(page.locator('.category-item').last()).toHaveCSS('opacity', '1');
            await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
        } finally { await context.close(); }
    }
});

test('dynamic cards, keyboard focus, reduced motion changes and page restoration reveal safely', async ({ page }) => {
    await page.goto('/produk');
    await page.evaluate(() => {
        const original = document.querySelector('.category-item');
        const card = original.cloneNode(true);
        for (const element of [card, ...card.querySelectorAll('*')]) {
            for (const attr of [...element.attributes]) if (attr.name.startsWith('data-scroll-reveal')) element.removeAttribute(attr.name);
            element.style.removeProperty('--reveal-delay');
            element.style.removeProperty('--reveal-base-transition');
        }
        card.id = 'dynamic-reveal-test';
        original.parentElement.append(card);
    });
    const dynamic = page.locator('#dynamic-reveal-test');
    await expect(dynamic).toHaveAttribute('data-scroll-reveal-state', 'pending');
    await dynamic.focus();
    await expect(dynamic).toHaveAttribute('data-scroll-reveal-state', 'visible');
    await expect(dynamic).toHaveCSS('opacity', '1');
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
    await page.emulateMedia({ reducedMotion: 'no-preference' });
    await expect(dynamic).toHaveAttribute('data-scroll-reveal-state', 'visible');
    await page.goto('/produk');
    await page.evaluate(() => dispatchEvent(new PageTransitionEvent('pageshow', { persisted: true })));
    await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
});

test('inquiry inputs and viewer remain interactive without reveal on controls', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/kontak');
    await page.locator('#name').focus();
    await expect(page.locator('#name')).toBeFocused();
    await expect(page.locator('.contact-grid > div:last-child')).toHaveCSS('opacity', '1');
    await page.getByRole('button', { name: 'Kirim Inquiry' }).click();
    await expect(page.locator('#name')).toBeFocused();
    await expect(page).toHaveURL(/\/kontak$/);
    await page.goto('/publikasi/1/preview');
    await expect(page.locator('.publication-pdf')).not.toHaveAttribute('data-scroll-reveal');
    await expect(page.locator('.publication-pdf')).toHaveCSS('transform', 'none');
    const file = await page.request.get(await page.locator('.publication-pdf').getAttribute('data'));
    expect(file.status()).toBe(200);
});
