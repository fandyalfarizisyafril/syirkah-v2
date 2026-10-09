import { test, expect } from '@playwright/test';

for (const width of [1440, 1024, 768, 390, 320]) {
    test(`editorial layout stays contained and readable at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await page.goto('/');
        const section = page.locator('.home-partner');
        await section.scrollIntoViewIfNeeded();
        await expect(section).toHaveClass(/is-revealed/);
        await page.waitForTimeout(950);
        await expect(section.locator('.home-partner-eyebrow')).toHaveText('YOUR INDUSTRIAL PARTNER');
        await expect(section.locator('h2')).toHaveText('Kebutuhan teknis Anda.Fokus kami.');
        await expect(section.locator('h2 span')).toHaveCSS('color', 'rgb(8, 76, 161)');
        await expect(section.locator('img')).toHaveCount(0);
        await expect(section.locator('a')).toHaveCount(1);
        await expect(section.locator('a')).toHaveAttribute('href', /\/tentang-kami$/);
        const geometry = await section.evaluate(e => {
            const heading = e.querySelector('.home-partner-heading').getBoundingClientRect();
            const copy = e.querySelector('.home-partner-copy').getBoundingClientRect();
            const signature = e.querySelector('.home-partner-signature').getBoundingClientRect();
            const container = e.querySelector('.container').getBoundingClientRect();
            return {
                columns: copy.left >= heading.right,
                stacked: copy.top >= heading.bottom,
                decorationClear: !signature.height || signature.top >= Math.max(heading.bottom, copy.bottom),
                overflow: document.documentElement.scrollWidth > innerWidth,
                contained: [...e.querySelectorAll('p,h2,a')].every(c => {
                    const r = c.getBoundingClientRect();
                    return r.left >= container.left - 1 && r.right <= container.right + 1 && c.scrollWidth <= c.clientWidth + 1;
                }),
            };
        });
        expect(width > 900 ? geometry.columns : geometry.stacked).toBe(true);
        expect(geometry.decorationClear).toBe(true);
        expect(geometry.contained).toBe(true);
        expect(geometry.overflow).toBe(false);
        await section.screenshot({ path: testInfo.outputPath(`partner-${width}.png`), style: '.site-header { visibility:hidden!important; }' });
    });
}

test('entry animation staggers once and does not restart after scrolling back', async ({ page }) => {
    await page.goto('/');
    const section = page.locator('.home-partner');
    await expect(section).not.toHaveClass(/is-revealed/);
    await section.scrollIntoViewIfNeeded();
    await expect(section).toHaveClass(/is-revealed/);
    await expect(section.locator('.home-partner-copy')).toHaveCSS('animation-delay', '0.16s');
    await expect(section.locator('.home-partner-signature path').first()).toHaveCSS('animation-name', 'partner-line-draw');
    await page.waitForTimeout(1000);
    const animations = await section.evaluate(e => e.getAnimations({ subtree: true }).map(a => ({ start: a.startTime, state: a.playState })));
    expect(animations.length).toBe(5);
    expect(animations.every(a => a.state === 'finished')).toBe(true);
    await page.evaluate(() => scrollTo(0, 0));
    await section.scrollIntoViewIfNeeded();
    expect(await section.evaluate(e => e.getAnimations({ subtree: true }).map(a => ({ start: a.startTime, state: a.playState })))).toEqual(animations);
    await section.locator('a').hover();
    await expect(section.locator('a .icon')).toHaveCSS('transform', 'matrix(1, 0, 0, 1, 3, -3)');
});

test('reduced motion and keyboard navigation show all copy immediately', async ({ page }) => {
    await page.emulateMedia({ reducedMotion: 'reduce' });
    await page.goto('/');
    const section = page.locator('.home-partner');
    await section.scrollIntoViewIfNeeded();
    await expect(section.locator('.home-partner-copy')).toHaveCSS('opacity', '1');
    await expect(section.locator('.home-partner-copy')).toHaveCSS('animation-name', 'none');
    await section.locator('a').focus();
    await expect(section.locator('a')).toBeFocused();
    await expect(section.locator('a .icon')).toHaveCSS('transition-duration', '0s');
    await page.keyboard.press('Enter');
    await expect(page).toHaveURL(/\/tentang-kami$/);
    await expect(page.locator('.home-partner')).toHaveCount(0);
});

test('content and link remain available without JavaScript', async ({ browser, baseURL }) => {
    const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
    try {
        const page = await context.newPage();
        await page.goto('/');
        const section = page.locator('.home-partner');
        await expect(section.locator('h2')).toHaveCSS('opacity', '1');
        await expect(section.locator('.home-partner-copy')).toHaveCSS('opacity', '1');
        await section.locator('a').click();
        await expect(page).toHaveURL(/\/tentang-kami$/);
    } finally { await context.close(); }
});
