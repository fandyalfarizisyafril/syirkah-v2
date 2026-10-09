import { test, expect } from '@playwright/test';

const focusNames = ['Engineering', 'Mechanical', 'Electrical', 'Instrumentation', 'Oil Spill Response & Prevention'];
const values = ['Reliability', 'Efficiency', 'Operational Continuity', 'Safety', 'Environmental Protection'];

for (const [width, columns] of [[1440, 3], [1024, 3], [768, 2], [390, 1], [320, 1]]) {
    test(`about editorial layout and image at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await page.goto('/tentang-kami');
        await page.locator('.about-photograph img').evaluate(i => i.decode());
        for (const section of await page.locator('.about-page [data-about-reveal]').all()) await section.scrollIntoViewIfNeeded();
        await page.waitForTimeout(1000);
        await expect(page.locator('.about-page')).toHaveCSS('font-family', /SMART Inter/);
        await expect(page.locator('#about-profile-title')).toHaveText('Mengenal SMARTlebih dekat.');
        await expect(page.locator('#about-profile-title span')).toHaveCSS('color', 'rgb(8, 76, 161)');
        const icons = page.locator('.about-focus-item svg.about-focus-icon');
        await expect(icons).toHaveCount(5);
        expect(await icons.evaluateAll(es => es.map(e => e.getAttribute('data-about-icon')))).toEqual(['drafting-compass', 'cog', 'zap', 'gauge', 'shield-check']);
        await expect(icons.first()).toHaveCSS('width', width <= 640 ? '22px' : '24px');
        await expect(icons.first()).toHaveCSS('stroke-width', '1.5px');
        expect(await icons.evaluateAll(es => es.every(e => {
            const icon = e.getBoundingClientRect();
            const card = e.closest('li');
            const bounds = card.getBoundingClientRect();
            const number = card.querySelector('.about-focus-number').getBoundingClientRect();
            const title = card.querySelector('h3').getBoundingClientRect();
            return icon.right < bounds.right && icon.top > bounds.top && icon.bottom < title.top
                && Math.abs((icon.top + icon.bottom) / 2 - (number.top + number.bottom) / 2) < 1;
        }))).toBe(true);
        await expect(page.locator('.about-focus-item h3')).toHaveText(focusNames);
        await expect(page.locator('.about-values-list li span')).toHaveText(values);
        await expect(page.locator('.about-focus-item a,.about-focus-item button')).toHaveCount(0);
        await expect(page.locator('.about-page img')).toHaveCount(1);
        await expect(page.locator('.about-photograph img')).toHaveCSS('object-fit', 'cover');
        const geometry = await page.locator('.about-page').evaluate(e => {
            const grid = e.querySelector('.about-focus-grid');
            const profile = e.querySelector('.about-profile');
            const title = profile.querySelector('h2').getBoundingClientRect();
            const copy = profile.querySelector('.about-profile-copy').getBoundingClientRect();
            return {
                columns: getComputedStyle(grid).gridTemplateColumns.split(' ').length,
                stacked: copy.top >= title.bottom,
                sideBySide: copy.left >= title.right,
                pageOverflow: document.documentElement.scrollWidth > innerWidth,
                textContained: [...e.querySelectorAll('p,h1,h2,h3,li')].every(child => {
                    const r = child.getBoundingClientRect();
                    return r.left >= 0 && r.right <= innerWidth && child.scrollWidth <= child.clientWidth + 1;
                }),
                cardHeights: [...grid.children].map(c => c.offsetHeight),
            };
        });
        expect(geometry.columns).toBe(columns);
        expect(width > 640 ? geometry.sideBySide : geometry.stacked).toBe(true);
        expect(geometry.pageOverflow).toBe(false);
        expect(geometry.textContained).toBe(true);
        expect(Math.max(...geometry.cardHeights)).toBeLessThan(190);
        await page.locator('.about-page').screenshot({ path: testInfo.outputPath(`about-${width}.png`), style: '.site-header {visibility:hidden!important;}' });
    });
}

test('focus hover is subtle, does not shift cards, and remains informational', async ({ page }) => {
    await page.goto('/tentang-kami');
    const grid = page.locator('.about-focus-grid');
    await grid.scrollIntoViewIfNeeded();
    await page.waitForTimeout(950);
    const geometry = () => grid.locator('li').evaluateAll(es => es.map(e => [e.offsetLeft, e.offsetTop, e.offsetWidth, e.offsetHeight]));
    const before = await geometry();
    await grid.locator('li').last().hover();
    await expect(grid.locator('li').last()).toHaveCSS('border-top-color', 'rgb(8, 76, 161)');
    await expect(grid.locator('li').last().locator('.about-focus-icon')).toHaveCSS('color', 'rgb(8, 76, 161)');
    expect(await geometry()).toEqual(before);
    await grid.locator('li').last().click();
    await expect(page).toHaveURL(/\/tentang-kami$/);
    await expect(grid.locator('[tabindex],a,button')).toHaveCount(0);
});

test('page-only assets do not load on homepage and shared CTA still navigates', async ({ page }) => {
    await page.goto('/');
    await expect(page.locator('link[rel="stylesheet"][href*="/about-"]')).toHaveCount(0);
    await expect(page.locator('.about-page')).toHaveCount(0);
    await page.goto('/tentang-kami');
    await expect(page.locator('link[rel="stylesheet"][href*="/about-"]')).toHaveCount(1);
    await expect(page.locator('.site-header')).toHaveCount(1);
    await expect(page.locator('.site-footer')).toHaveCount(1);
    await page.locator('.inquiry-band a').first().click();
    await expect(page).toHaveURL(/\/kontak$/);
    await expect(page.locator('link[rel="stylesheet"][href*="/about-"]')).toHaveCount(0);
});

test('reuses one-time entrance animation with reduced-motion support', async ({ page }) => {
    await page.goto('/tentang-kami');
    const focus = page.locator('.about-focus');
    await expect(focus).not.toHaveClass(/is-revealed/);
    await focus.scrollIntoViewIfNeeded();
    await expect(focus).toHaveClass(/is-revealed/);
    await expect(focus.locator('li').last()).toHaveCSS('animation-delay', '0.24s');
    await page.waitForTimeout(950);
    const starts = await focus.evaluate(e => e.getAnimations({ subtree: true }).map(a => a.startTime));
    await page.evaluate(() => scrollTo(0, 0));
    await focus.scrollIntoViewIfNeeded();
    expect(await focus.evaluate(e => e.getAnimations({ subtree: true }).map(a => a.startTime))).toEqual(starts);
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await expect(focus.locator('li').first()).toHaveCSS('animation-name', 'none');
    await expect(focus.locator('li').first()).toHaveCSS('opacity', '1');
});

test('all content stays visible without JavaScript', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    try {
        const page = await context.newPage();
        await page.goto('/tentang-kami');
        await expect(page.locator('.about-company-name')).toBeVisible();
        await expect(page.locator('.about-focus-item h3')).toHaveText(focusNames);
        await expect(page.locator('.about-values-list li span')).toHaveText(values);
        await expect(page.locator('.about-profile-copy')).toHaveCSS('opacity', '1');
        await page.locator('.inquiry-band a').first().click();
        await expect(page).toHaveURL(/\/kontak$/);
    } finally { await context.close(); }
});
