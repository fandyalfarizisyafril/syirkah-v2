import { test, expect } from '@playwright/test';

const root = '.home-category-carousel';
const names = ['Electric Motors & Generators', 'Vibration Technology', 'Chemical Metering Pump', 'Hose Pump', 'Industrial Hose', 'Air Compressor', 'Oil Spill Response & Prevention'];
const slugs = ['electric-motors-generators', 'vibration-technology', 'chemical-metering-pump', 'hose-pump', 'industrial-hose', 'air-compressor', 'oil-spill-response-prevention'];
const load = async page => {
    await page.goto('/');
    await page.mouse.move(0, 0);
    await page.locator(root).scrollIntoViewIfNeeded();
    await page.locator('.home-category-image').evaluateAll(images => Promise.all(images.map(image => {
        image.loading = 'eager';
        return image.decode();
    })));
};

for (const [width, visible] of [[1440, 3], [1024, 3], [768, 2], [390, 1], [320, 1]]) {
    test(width + 'px: responsive portrait cards, unique images and no page overflow', async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await load(page);
        const cards = page.locator('.home-category-card');
        await expect(cards).toHaveCount(7);
        await expect(cards.locator('h3')).toHaveText(names);
        expect(await cards.locator('a').evaluateAll(links => links.map(a => new URL(a.href).pathname))).toEqual(slugs.map(s => '/produk/' + s));
        const layout = await page.locator('.home-category-track').evaluate(track => {
            const outer = track.getBoundingClientRect();
            const boxes = [...track.children].map(card => card.getBoundingClientRect());
            return {
                visible: boxes.filter(b => b.left >= outer.left - 1 && b.right <= outer.right + 1).length,
                sizes: boxes.map(b => ({ width: b.width, height: b.height, top: b.top })),
                overflow: document.documentElement.scrollWidth > innerWidth,
            };
        });
        expect(layout.visible).toBe(visible);
        expect(layout.overflow).toBe(false);
        for (const box of layout.sizes) {
            expect(box.height).toBeGreaterThan(box.width);
            expect(box.top).toBe(layout.sizes[0].top);
            expect(box.height).toBe(layout.sizes[0].height);
        }
        expect(new Set(await cards.locator('img').evaluateAll(images => images.map(i => i.currentSrc))).size).toBe(7);
        for (const image of await cards.locator('img').all()) {
            const composition = await image.evaluate(i => {
                const rect = i.getBoundingClientRect();
                const card = i.closest('li').getBoundingClientRect();
                return { ratio: rect.width / rect.height, sourceRatio: i.naturalWidth / i.naturalHeight,
                    width: rect.width / card.width, top: rect.top - card.top, bottom: (rect.bottom - card.top) / card.height };
            });
            expect(composition.ratio).toBeCloseTo(composition.sourceRatio, 2);
            expect(composition.width).toBeGreaterThanOrEqual(.99);
            expect(composition.width).toBeLessThanOrEqual(1.11);
            expect(composition.top).toBeGreaterThan(0);
            expect(composition.bottom).toBeLessThan(.8);
        }
        await expect(cards.first().locator('.home-category-panel')).toHaveCSS('opacity', '0');
        await expect(cards.first().locator('.home-category-title')).toHaveCSS('color', 'rgb(255, 255, 255)');
        await page.locator('.home-product-categories').screenshot({ path: testInfo.outputPath('categories-' + width + '.png') });
    });
}

test('hover reveals one blue panel, preserves geometry and returns to photo title', async ({ page }, testInfo) => {
    await page.setViewportSize({ width: 1440, height: 1000 });
    await load(page);
    const cards = page.locator('.home-category-card');
    const geometry = () => cards.evaluateAll(es => es.map(e => [e.offsetLeft, e.offsetTop, e.offsetWidth, e.offsetHeight]));
    const before = await geometry();
    await cards.nth(1).hover();
    const panel = cards.nth(1).locator('.home-category-panel');
    await expect(panel).toHaveCSS('opacity', '1');
    await expect(panel).toHaveCSS('transform', 'matrix(1, 0, 0, 1, 0, 0)');
    await expect(panel).toHaveCSS('transition-duration', '0.4s, 0.4s');
    await expect(cards.first().locator('.home-category-panel')).toHaveCSS('opacity', '0');
    expect(await geometry()).toEqual(before);
    await page.locator(root).screenshot({ path: testInfo.outputPath('categories-hover.png') });
    await page.mouse.move(0, 0);
    await expect(panel).toHaveCSS('opacity', '0');
    await expect(cards.nth(1).locator('.home-category-title')).toHaveCSS('opacity', '1');
});

test('arrows stop at last card, trackpad works and no autoplay runs', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 1000 });
    await load(page);
    const track = page.locator('.home-category-track');
    const next = page.locator('[data-category-next]');
    const previous = page.locator('[data-category-prev]');
    await expect(previous).toBeDisabled();
    for (let index = 1; index <= 4; index++) {
        await next.click();
        await expect.poll(() => track.evaluate(e => e.scrollLeft)).toBeCloseTo(index * (1240 + 24) / 3, 0);
    }
    await expect(next).toBeDisabled();
    const end = await track.evaluate(e => e.scrollLeft);
    expect(await track.evaluate(e => Math.abs(e.scrollWidth - e.clientWidth - e.scrollLeft))).toBeLessThanOrEqual(1);
    await page.waitForTimeout(5500);
    expect(await track.evaluate(e => e.scrollLeft)).toBe(end);
    await previous.click();
    await expect(next).toBeEnabled();
    await track.hover();
    await page.mouse.wheel(-1800, 0);
    await expect(previous).toBeDisabled();
});

test('keyboard reaches last category and reduced motion removes transitions', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await load(page);
    const last = page.locator('.home-category-link').last();
    await last.focus();
    await expect(last).toBeInViewport();
    await expect(last.locator('.home-category-panel')).toHaveCSS('opacity', '1');
    await expect(last.locator('.home-category-panel')).toHaveCSS('transition-duration', '0s');
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/produk\/oil-spill-response-prevention$/);
    await expect(page.locator(root)).toHaveCount(0);
});

test('mobile info toggles without navigation, swipe advances and link works', async ({ browser, baseURL }, testInfo) => {
    const context = await browser.newContext({ viewport: { width: 390, height: 844 }, hasTouch: true, isMobile: true, baseURL });
    try {
        const page = await context.newPage();
        await load(page);
        const first = page.locator('.home-category-card').first();
        await first.locator('[data-category-info]').tap();
        await expect(first.locator('[data-category-info]')).toHaveAttribute('aria-expanded', 'true');
        await expect(first.locator('.home-category-panel')).toHaveCSS('opacity', '1');
        await page.locator('.home-product-categories').screenshot({ path: testInfo.outputPath('categories-mobile-info.png') });
        await first.locator('[data-category-info]').tap();
        await expect(first.locator('.home-category-panel')).toHaveCSS('opacity', '0');
        const box = await first.boundingBox();
        const session = await context.newCDPSession(page);
        const y = Math.max(150, box.y + box.height / 2);
        await session.send('Input.dispatchTouchEvent', { type: 'touchStart', touchPoints: [{ x: 320, y }] });
        for (const x of [270, 210, 150, 70]) {
            await session.send('Input.dispatchTouchEvent', { type: 'touchMove', touchPoints: [{ x, y }] });
            await page.waitForTimeout(25);
        }
        await session.send('Input.dispatchTouchEvent', { type: 'touchEnd', touchPoints: [] });
        await expect.poll(() => page.locator('.home-category-track').evaluate(e => e.scrollLeft)).toBeGreaterThan(100);
        await page.locator('.home-category-link').last().scrollIntoViewIfNeeded();
        await page.locator('.home-category-link').last().tap();
        await expect(page).toHaveURL(/\/produk\/oil-spill-response-prevention$/);
    } finally { await context.close(); }
});

test('no JavaScript keeps images and links; product listing stays unchanged', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    try {
        const page = await context.newPage();
        await page.goto('/');
        await expect(page.locator('.home-category-image')).toHaveCount(7);
        await expect(page.locator('.home-category-controls')).toBeHidden();
        await page.locator('.home-category-link').first().click();
        await expect(page).toHaveURL(/\/produk\/electric-motors-generators$/);
        await page.goto('/produk');
        await expect(page.locator(root)).toHaveCount(0);
        await expect(page.locator('.category-number')).toHaveCount(7);
    } finally { await context.close(); }
});

test('mouse drag follows the pointer, snaps both ways and never opens a category on release', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 1000 });
    await load(page);
    const track = page.locator('.home-category-track');
    const box = await track.boundingBox();
    const x = box.x + 700;
    const y = box.y + 110;
    await expect(track).toHaveCSS('cursor', 'grab');
    await page.mouse.move(x, y);
    await page.mouse.down();
    await page.mouse.move(x - 320, y, { steps: 12 });
    await expect(track).toHaveClass(/is-dragging/);
    await expect(track).toHaveCSS('cursor', 'grabbing');
    await expect(track).toHaveCSS('scroll-snap-type', 'none');
    expect(await track.evaluate(e => e.scrollLeft)).toBeCloseTo(320, 0);
    expect(await page.evaluate(() => getSelection().toString())).toBe('');
    await page.mouse.up();
    await expect(page).toHaveURL(/\/$/);
    await expect(track).not.toHaveClass(/is-dragging/);
    await expect.poll(() => track.evaluate(e => e.scrollLeft)).toBeCloseTo(1264 / 3, 0);
    await expect(page.locator('[data-category-prev]')).toBeEnabled();
    await page.mouse.move(box.x + 160, y);
    await page.mouse.down();
    await page.mouse.move(box.x + 520, y, { steps: 12 });
    await page.mouse.up();
    await expect(page.locator('[data-category-prev]')).toBeDisabled();
    await expect(page).toHaveURL(/\/$/);
    await page.locator('.home-category-link').first().click();
    await expect(page).toHaveURL(/\/produk\/electric-motors-generators$/);
});

test('a small mouse movement remains a normal category click', async ({ page }) => {
    await load(page);
    const box = await page.locator('.home-category-card').first().boundingBox();
    await page.mouse.move(box.x + 70, box.y + 100);
    await page.mouse.down();
    await page.mouse.move(box.x + 73, box.y + 100);
    await page.mouse.up();
    await expect(page).toHaveURL(/\/produk\/electric-motors-generators$/);
});

test('capture handles release outside the track, cancellation and subsequent keyboard activation', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 1000 });
    await load(page);
    const track = page.locator('.home-category-track');
    const box = await track.boundingBox();
    await page.mouse.move(box.x + 800, box.y + 100);
    await page.mouse.down();
    await page.mouse.move(10, box.y + 100, { steps: 15 });
    await page.mouse.up();
    await expect(track).not.toHaveClass(/is-dragging/);
    await expect.poll(() => track.evaluate(e => e.scrollLeft)).toBeGreaterThan(700);
    await expect(page).toHaveURL(/\/$/);
    await page.mouse.move(box.x + 500, box.y + 100);
    await page.mouse.down();
    await page.mouse.move(box.x + 400, box.y + 100, { steps: 5 });
    await page.evaluate(() => window.dispatchEvent(new Event('blur')));
    await expect(track).not.toHaveClass(/is-dragging/);
    await page.mouse.up();
    const last = page.locator('.home-category-link').last();
    await last.focus();
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/produk\/oil-spill-response-prevention$/);
});

test('Shift wheel navigates horizontally while ordinary wheel keeps normal page scrolling', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 1000 });
    await load(page);
    const track = page.locator('.home-category-track');
    await track.hover();
    await page.keyboard.down('Shift');
    await page.mouse.wheel(0, 120);
    await page.keyboard.up('Shift');
    await expect.poll(() => track.evaluate(e => e.scrollLeft)).toBeCloseTo(1264 / 3, 0);
    await expect(page.locator('[data-category-prev]')).toBeEnabled();
    const horizontal = await track.evaluate(e => e.scrollLeft);
    const vertical = await page.evaluate(() => scrollY);
    await page.mouse.wheel(0, 180);
    await expect.poll(() => page.evaluate(() => scrollY)).toBeGreaterThan(vertical + 100);
    expect(await track.evaluate(e => e.scrollLeft)).toBe(horizontal);
    await track.hover();
    await page.mouse.wheel(1800, 0);
    await expect(page.locator('[data-category-next]')).toBeDisabled();
});

test('release eases through intermediate frames before restoring native snapping', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 1000 });
    await load(page);
    const track = page.locator('.home-category-track');
    const box = await track.boundingBox();
    await page.mouse.move(box.x + 650, box.y + 100);
    await page.mouse.down();
    await page.mouse.move(box.x + 390, box.y + 100, { steps: 12 });
    await page.waitForTimeout(130);
    await page.evaluate(() => {
        window.releaseFrames = [];
        window.addEventListener('pointerup', () => {
            const track = document.querySelector('.home-category-track');
            const sample = time => {
                window.releaseFrames.push({ time, left: track.scrollLeft });
                if (window.releaseFrames.length < 40) requestAnimationFrame(sample);
            };
            requestAnimationFrame(sample);
        }, { once: true });
    });
    await page.mouse.up();
    await expect(track).toHaveClass(/is-settling/);
    await expect(track).toHaveCSS('scroll-snap-type', 'none');
    await expect(track).not.toHaveClass(/is-settling/);
    await expect(track).toHaveCSS('scroll-snap-type', 'x mandatory');
    await expect.poll(() => track.evaluate(e => e.scrollLeft)).toBeCloseTo(1264 / 3, 0);
    const frames = await page.evaluate(() => window.releaseFrames);
    const intermediate = frames.filter(f => f.left > 260 && f.left < 420);
    expect(intermediate.length).toBeGreaterThan(5);
    expect(intermediate.at(-1).time - intermediate[0].time).toBeGreaterThan(150);
    for (let index = 1; index < frames.length; index++) {
        expect(frames[index].left).toBeGreaterThanOrEqual(frames[index - 1].left - 1);
        expect(frames[index].left - frames[index - 1].left).toBeLessThan(65);
    }
});

test('a short flick has light momentum, while pausing before release removes momentum', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 1000 });
    await load(page);
    const track = page.locator('.home-category-track');
    const box = await track.boundingBox();
    const shortDrag = async pause => {
        await page.mouse.move(box.x + 650, box.y + 100);
        await page.mouse.down();
        await page.mouse.move(box.x + 510, box.y + 100, { steps: 4 });
        if (pause) await page.waitForTimeout(150);
        await page.mouse.up();
        await expect(track).not.toHaveClass(/is-settling/);
    };
    await shortDrag(true);
    await expect.poll(() => track.evaluate(e => e.scrollLeft)).toBe(0);
    await shortDrag(false);
    await expect.poll(() => track.evaluate(e => e.scrollLeft)).toBeCloseTo(1264 / 3, 0);
    await expect(page).toHaveURL(/\/$/);
});

test('a new drag interrupts settling and reduced motion releases without animation', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 1000 });
    await load(page);
    const track = page.locator('.home-category-track');
    const box = await track.boundingBox();
    await page.mouse.move(box.x + 650, box.y + 100);
    await page.mouse.down();
    await page.mouse.move(box.x + 370, box.y + 100, { steps: 8 });
    await page.mouse.up();
    await expect(track).toHaveClass(/is-settling/);
    await page.mouse.down();
    await page.mouse.move(box.x + 550, box.y + 100, { steps: 6 });
    await expect(track).toHaveClass(/is-dragging/);
    const paused = await track.evaluate(e => e.scrollLeft);
    await page.waitForTimeout(500);
    expect(await track.evaluate(e => e.scrollLeft)).toBe(paused);
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.mouse.up();
    await expect(track).not.toHaveClass(/is-settling|is-dragging/);
    await expect(track).toHaveCSS('scroll-snap-type', 'x mandatory');
    await expect(page).toHaveURL(/\/$/);
});
