# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: scroll-reveal.spec.js >> public reveal is once-only and layout-stable at 1280px
- Location: tests\Browser\scroll-reveal.spec.js:14:5

# Error details

```
Error: expect(locator).toHaveCSS(expected) failed

Locator: locator('.category-item').first()
Expected pattern: /cubic-bezier\(0.22, 1, 0.36, 1\)/
Received string:  "ease, ease"
Timeout: 5000ms

Call log:
  - Expect "toHaveCSS" locator('.category-item').first() with timeout 5000ms
  - waiting for locator('.category-item').first()
    5 × locator resolved to <a class="category-item" data-scroll-reveal="stagger" data-scroll-reveal-state="revealing" href="http://127.0.0.1:8000/produk/electric-motors-generators">…</a>
      - unexpected value "ease, ease"
    9 × locator resolved to <a class="category-item" data-scroll-reveal="stagger" data-scroll-reveal-state="visible" href="http://127.0.0.1:8000/produk/electric-motors-generators">…</a>
      - unexpected value "ease, ease"

```

```yaml
- link "01 Electric Motors & Generators Electric motors, generators, PM motors, dan drive control untuk kebutuhan penggerak industri.":
  - /url: http://127.0.0.1:8000/produk/electric-motors-generators
  - text: "01"
  - heading "Electric Motors & Generators" [level=3]
  - paragraph: Electric motors, generators, PM motors, dan drive control untuk kebutuhan penggerak industri.
```

# Test source

```ts
  1   | import { test, expect } from '@playwright/test';
  2   | 
  3   | const settleScroll = async page => {
  4   |     await page.evaluate(async () => {
  5   |         for (let y = 0; y < document.documentElement.scrollHeight; y += innerHeight * .65) {
  6   |             scrollTo({ top: y, behavior: 'instant' });
  7   |             await new Promise(resolve => setTimeout(resolve, 45));
  8   |         }
  9   |     });
  10  |     await page.waitForTimeout(1150);
  11  | };
  12  | 
  13  | for (const width of [1440, 1280, 768, 390, 375]) {
  14  |     test(`public reveal is once-only and layout-stable at ${width}px`, async ({ page }, testInfo) => {
  15  |         await page.setViewportSize({ width, height: 900 });
  16  |         const errors = [];
  17  |         page.on('pageerror', error => errors.push(error.message));
  18  |         await page.goto('/produk');
  19  |         await expect(page.locator('.page-intro')).toHaveAttribute('data-scroll-reveal-state', 'visible');
  20  |         const target = page.locator('.category-item').first();
  21  |         await expect(target).toHaveAttribute('data-scroll-reveal-state', 'pending');
  22  |         const geometry = () => target.evaluate(el => [el.offsetTop, el.offsetWidth, el.offsetHeight]);
  23  |         const before = await geometry();
  24  |         expect(await page.locator('[data-scroll-reveal-state="pending"]').evaluateAll(elements =>
  25  |             elements.every(el => parseInt(el.style.getPropertyValue('--reveal-delay') || '0') <= 360))).toBe(true);
  26  |         await target.scrollIntoViewIfNeeded();
  27  |         await expect(target).toHaveAttribute('data-scroll-reveal-state', 'revealing');
> 28  |         await expect(target).toHaveCSS('transition-timing-function', /cubic-bezier\(0.22, 1, 0.36, 1\)/);
      |                              ^ Error: expect(locator).toHaveCSS(expected) failed
  29  |         await expect(target).toHaveAttribute('data-scroll-reveal-state', 'visible');
  30  |         await expect(target).toHaveCSS('opacity', '1');
  31  |         expect(await geometry()).toEqual(before);
  32  |         await page.evaluate(() => scrollTo(0, 0));
  33  |         await target.scrollIntoViewIfNeeded();
  34  |         await expect(target).toHaveAttribute('data-scroll-reveal-state', 'visible');
  35  |         await expect(target).toHaveCSS('translate', 'none');
  36  |         await page.goto('/publikasi');
  37  |         await settleScroll(page);
  38  |         await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
  39  |         expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
  40  |         await page.screenshot({ path: testInfo.outputPath(`motion-publications-${width}.png`), fullPage: true });
  41  |         expect(errors).toEqual([]);
  42  |     });
  43  | }
  44  | 
  45  | test('all public templates are covered without touching protected interactions', async ({ page }) => {
  46  |     test.setTimeout(120000);
  47  |     await page.goto('/produk');
  48  |     const product = await page.locator('.product-card .text-link').first().getAttribute('href');
  49  |     const paths = ['/', '/tentang-kami', '/produk', product, '/brand', '/brand/wolong', '/publikasi', '/publikasi/1/preview', '/kontak', '/search?q=pump', '/privasi', '/produk/electric-motors-generators'];
  50  |     for (const path of paths) {
  51  |         expect((await page.goto(path)).status()).toBe(200);
  52  |         await expect(page.locator('[data-public-reveal-root]')).toHaveAttribute('data-scroll-reveal-initialized', 'true');
  53  |         expect(await page.locator('[data-scroll-reveal]').count(), path).toBeGreaterThan(0);
  54  |         await expect(page.locator('.site-header [data-scroll-reveal], .site-footer [data-scroll-reveal], .inquiry-band [data-scroll-reveal], [data-hero-carousel] [data-scroll-reveal], [data-category-carousel] [data-scroll-reveal], .home-brand-marquee [data-scroll-reveal], .publication-pdf[data-scroll-reveal], .gallery [data-scroll-reveal], input[data-scroll-reveal]')).toHaveCount(0);
  55  |         await expect(page.locator('.about-reveal-item[data-scroll-reveal], [data-partner-reveal][data-scroll-reveal]')).toHaveCount(0);
  56  |         await settleScroll(page);
  57  |         await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
  58  |         expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1), path).toBe(true);
  59  |     }
  60  |     await page.goto('/admin/login');
  61  |     await expect(page.locator('[data-public-reveal-root], [data-scroll-reveal], script[src*="/scroll-reveal-"], link[href*="/scroll-reveal-"]')).toHaveCount(0);
  62  | });
  63  | 
  64  | test('reduced motion, unavailable observer and failed initialization stay readable', async ({ browser, baseURL }) => {
  65  |     for (const mode of ['reduced', 'missing', 'throws', 'blocked', 'no-js']) {
  66  |         const context = await browser.newContext({ baseURL, reducedMotion: mode === 'reduced' ? 'reduce' : 'no-preference', javaScriptEnabled: mode !== 'no-js' });
  67  |         try {
  68  |             const page = await context.newPage();
  69  |             if (mode === 'missing') await page.addInitScript(() => { delete window.IntersectionObserver; });
  70  |             if (mode === 'throws') await page.addInitScript(() => { window.IntersectionObserver = class { constructor() { throw new Error('Unavailable observer'); } }; });
  71  |             if (mode === 'blocked') await page.route('**/scroll-reveal-*.js', route => route.abort());
  72  |             await page.goto('/produk');
  73  |             await expect(page.locator('.category-item').last()).toHaveCSS('opacity', '1');
  74  |             await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
  75  |         } finally { await context.close(); }
  76  |     }
  77  | });
  78  | 
  79  | test('dynamic cards, keyboard focus, reduced motion changes and page restoration reveal safely', async ({ page }) => {
  80  |     await page.goto('/produk');
  81  |     await page.evaluate(() => {
  82  |         const original = document.querySelector('.category-item');
  83  |         const card = original.cloneNode(true);
  84  |         for (const element of [card, ...card.querySelectorAll('*')]) {
  85  |             for (const attr of [...element.attributes]) if (attr.name.startsWith('data-scroll-reveal')) element.removeAttribute(attr.name);
  86  |             element.style.removeProperty('--reveal-delay');
  87  |             element.style.removeProperty('--reveal-base-transition');
  88  |         }
  89  |         card.id = 'dynamic-reveal-test';
  90  |         original.parentElement.append(card);
  91  |     });
  92  |     const dynamic = page.locator('#dynamic-reveal-test');
  93  |     await expect(dynamic).toHaveAttribute('data-scroll-reveal-state', 'pending');
  94  |     await dynamic.focus();
  95  |     await expect(dynamic).toHaveAttribute('data-scroll-reveal-state', 'visible');
  96  |     await expect(dynamic).toHaveCSS('opacity', '1');
  97  |     await page.emulateMedia({ reducedMotion: 'reduce' });
  98  |     await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
  99  |     await page.emulateMedia({ reducedMotion: 'no-preference' });
  100 |     await expect(dynamic).toHaveAttribute('data-scroll-reveal-state', 'visible');
  101 |     await page.goto('/produk');
  102 |     await page.evaluate(() => dispatchEvent(new PageTransitionEvent('pageshow', { persisted: true })));
  103 |     await expect(page.locator('[data-scroll-reveal-state="pending"]')).toHaveCount(0);
  104 | });
  105 | 
  106 | test('inquiry inputs and viewer remain interactive without reveal on controls', async ({ page }) => {
  107 |     await page.setViewportSize({ width: 390, height: 844 });
  108 |     await page.goto('/kontak');
  109 |     await page.locator('#name').focus();
  110 |     await expect(page.locator('#name')).toBeFocused();
  111 |     await expect(page.locator('.contact-grid > div:last-child')).toHaveCSS('opacity', '1');
  112 |     await page.getByRole('button', { name: 'Kirim Inquiry' }).click();
  113 |     await expect(page.locator('#name')).toBeFocused();
  114 |     await expect(page).toHaveURL(/\/kontak$/);
  115 |     await page.goto('/publikasi/1/preview');
  116 |     await expect(page.locator('.publication-pdf')).not.toHaveAttribute('data-scroll-reveal');
  117 |     await expect(page.locator('.publication-pdf')).toHaveCSS('transform', 'none');
  118 |     const file = await page.request.get(await page.locator('.publication-pdf').getAttribute('data'));
  119 |     expect(file.status()).toBe(200);
  120 | });
  121 | 
```