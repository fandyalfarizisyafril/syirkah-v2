# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: brand-marquee.spec.js >> keyboard users can reach every original logo without moving or duplicate focus targets
- Location: tests\Browser\brand-marquee.spec.js:71:1

# Error details

```
Error: expect(page).toHaveURL(expected) failed

Expected pattern: /\/brand\/tecbell$/
Received string:  "http://127.0.0.1:8000/brand/blu-c"
Timeout: 5000ms

Call log:
  - Expect "toHaveURL" with timeout 5000ms
    - waiting for "http://127.0.0.1:8000/brand/blu-c" navigation to finish...
    - navigated to "http://127.0.0.1:8000/brand/blu-c"
    13 × locator resolved to <html lang="id">…</html>
       - unexpected value "http://127.0.0.1:8000/brand/blu-c"

```

```yaml
- link "Langsung ke konten":
  - /url: "#main"
- text: Pekanbaru, Indonesia
- link "+62 812-6672-3815":
  - /url: tel:+6281266723815
- banner:
  - link "PT. Syirkah Mandiri Artomoro, beranda":
    - /url: http://127.0.0.1:8000
    - img "SMART"
    - text: PT. Syirkah Mandiri Artomoro Equipment & Parts Solutions
  - button "Buka navigasi"
- main:
  - region "BLU-C":
    - navigation "Breadcrumb":
      - link "Beranda":
        - /url: http://127.0.0.1:8000
      - text: /
      - link "Brand":
        - /url: http://127.0.0.1:8000/brand
      - text: / BLU-C
    - paragraph: BRANDS & PRINCIPALS
    - heading "BLU-C" [level=1]
    - paragraph: Dalam katalog SMART, BLU-C berada pada cakupan penanganan dan pencegahan penyebaran tumpahan minyak.
    - paragraph:
      - link "Oil Spill Response & Prevention":
        - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention
    - img "BLU-C"
  - region "Mengenal BLU-C":
    - paragraph: 01 / TENTANG BRAND
    - heading "Mengenal BLU-C" [level=2]
    - paragraph: BLU-C ditempatkan dalam kategori Oil Spill Response & Prevention pada katalog SMART. Secara umum, bidang ini berkaitan dengan kesiapan respons, pembatasan penyebaran, dan penanganan material yang tumpah. Pendekatan yang sesuai bergantung pada lokasi kejadian, karakteristik fluida, kondisi lingkungan, serta prosedur keselamatan yang berlaku. Peninjauan kebutuhan peralatan menjadi bagian dari perencanaan respons, bukan pengganti prosedur penanganan di lapangan. Rincian jenis dan spesifikasi produk perlu dikonfirmasi sebelum menentukan solusi untuk suatu kebutuhan operasional.
  - region "Teknologi dan solusi BLU-C":
    - paragraph: 02 / FOKUS TEKNOLOGI
    - heading "Teknologi dan solusi BLU-C" [level=2]
    - text: "01"
    - heading "Pengendalian Tumpahan Minyak" [level=3]
    - paragraph: Cakupan umum yang meliputi kesiapan respons dan pembatasan penyebaran tumpahan sesuai kondisi lapangan. Pemilihan peralatan pendukung perlu mengikuti karakteristik tumpahan serta prosedur penanganan yang berlaku.
  - region "Jelajahi produk BLU-C":
    - paragraph: 03 / PRODUK TERKAIT
    - heading "Jelajahi produk BLU-C" [level=2]
    - link "Semua produk BLU-C":
      - /url: http://127.0.0.1:8000/produk?brand=7
    - article:
      - link "Ilustrasi oil containment boom dengan rok pemberat, bukan foto model resmi":
        - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention/demo-oil-containment-boom
        - img "Ilustrasi oil containment boom dengan rok pemberat, bukan foto model resmi"
      - paragraph: BLU-C
      - heading "Oil Containment Boom dengan Rok Pemberat (Contoh)" [level=3]:
        - link "Oil Containment Boom dengan Rok Pemberat (Contoh)":
          - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention/demo-oil-containment-boom
      - paragraph: Pembatas tumpahan dengan pelampung kuning dan rok hitam berpemberat untuk membatasi penyebaran minyak. Ilustrasi contoh.
      - link "Detail Produk":
        - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention/demo-oil-containment-boom
    - article:
      - link "Ilustrasi lembaran absorben minyak berperforasi, bukan foto model resmi":
        - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention/demo-oil-absorbent-pads
        - img "Ilustrasi lembaran absorben minyak berperforasi, bukan foto model resmi"
      - paragraph: BLU-C
      - heading "Lembaran Absorben Minyak Berperforasi (Contoh)" [level=3]:
        - link "Lembaran Absorben Minyak Berperforasi (Contoh)":
          - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention/demo-oil-absorbent-pads
      - paragraph: Lembaran absorben putih dengan pola bertekstur dan garis perforasi untuk penanganan tumpahan minyak. Ilustrasi contoh.
      - link "Detail Produk":
        - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention/demo-oil-absorbent-pads
    - article:
      - link "Ilustrasi spill kit portabel dalam tas, bukan foto model resmi":
        - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention/demo-oil-spill-response-kit
        - img "Ilustrasi spill kit portabel dalam tas, bukan foto model resmi"
      - paragraph: BLU-C
      - heading "Spill Kit Portabel dalam Tas (Contoh)" [level=3]:
        - link "Spill Kit Portabel dalam Tas (Contoh)":
          - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention/demo-oil-spill-response-kit
      - paragraph: Paket penanganan tumpahan dalam tas portabel, dengan lembaran absorben, absorbent socks, sarung tangan, dan kantong limbah. Ilustrasi contoh.
      - link "Detail Produk":
        - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention/demo-oil-spill-response-kit
  - paragraph: LET'S WORK TOGETHER
  - heading "Temukan solusi untuk kebutuhan industri Anda." [level=2]
  - link "Diskusikan Kebutuhan":
    - /url: http://127.0.0.1:8000/kontak
  - link "WhatsApp":
    - /url: https://wa.me/6281266723815
- contentinfo:
  - link "PT. Syirkah Mandiri Artomoro, beranda":
    - /url: http://127.0.0.1:8000
    - img "SMART - Equipment & Parts Solutions"
  - paragraph: PT. Syirkah Mandiri Artomoro
  - paragraph: General Supplier & Technical Solutions
  - paragraph: PT. Syirkah Mandiri Artomoro menyediakan equipment, spare part, dan solusi teknis untuk kebutuhan industri. Kami membantu menghubungkan kebutuhan operasional pelanggan dengan produk dan teknologi yang sesuai.
  - heading "Jelajahi" [level=2]
  - navigation "Navigasi footer":
    - link "Tentang Kami":
      - /url: http://127.0.0.1:8000/tentang-kami
    - link "Produk & Solusi":
      - /url: http://127.0.0.1:8000/produk
    - link "Brands & Principals":
      - /url: http://127.0.0.1:8000/brand
    - link "Publikasi":
      - /url: http://127.0.0.1:8000/publikasi
    - link "Kontak":
      - /url: http://127.0.0.1:8000/kontak
  - heading "Produk & Solusi" [level=2]
  - navigation "Kategori produk":
    - link "Electric Motors & Generators":
      - /url: http://127.0.0.1:8000/produk/electric-motors-generators
    - link "Vibration Technology":
      - /url: http://127.0.0.1:8000/produk/vibration-technology
    - link "Chemical Metering Pump":
      - /url: http://127.0.0.1:8000/produk/chemical-metering-pump
    - link "Hose Pump":
      - /url: http://127.0.0.1:8000/produk/hose-pump
    - link "Industrial Hose":
      - /url: http://127.0.0.1:8000/produk/industrial-hose
    - link "Air Compressor":
      - /url: http://127.0.0.1:8000/produk/air-compressor
    - link "Oil Spill Response & Prevention":
      - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention
  - heading "Hubungi Kami" [level=2]
  - link "Telepon +62 812-6672-3815":
    - /url: tel:+6281266723815
  - link "Email admin@smartomoro.com":
    - /url: mailto:admin@smartomoro.com
  - link "Sales doni.rahmat@smartomoro.com":
    - /url: mailto:doni.rahmat@smartomoro.com
  - text: Kantor Jalan Teladan No. 7, RT 04/RW 10, Kel. Simpang Baru, Kec. Bina Widya, Kota Pekanbaru, Riau 28293.
  - link "Hubungi via WhatsApp":
    - /url: https://wa.me/6281266723815
  - paragraph: Butuh bantuan menemukan produk yang tepat?
  - link "Diskusikan Kebutuhan":
    - /url: http://127.0.0.1:8000/kontak
  - text: © 2026 Fandy Alfarizi Syafril. PT. Syirkah Mandiri Artomoro
  - navigation "Informasi legal":
    - link "Kebijakan Privasi":
      - /url: http://127.0.0.1:8000/privasi
```

# Test source

```ts
  1   | import { test, expect } from '@playwright/test';
  2   | 
  3   | async function openMarquee(page) {
  4   |     await page.goto('/');
  5   |     const marquee = page.locator('.home-brand-marquee');
  6   |     await marquee.scrollIntoViewIfNeeded();
  7   |     await page.mouse.move(0, 0);
  8   |     await marquee.locator('img').evaluateAll(images => Promise.all(images.map(image => {
  9   |         image.loading = 'eager';
  10  |         return image.decode();
  11  |     })));
  12  |     return marquee;
  13  | }
  14  | 
  15  | for (const width of [1920, 1440, 768, 390, 320]) {
  16  |     test(`verified logos and seamless geometry at ${width}px`, async ({ page }, testInfo) => {
  17  |         await page.setViewportSize({ width, height: 900 });
  18  |         const marquee = await openMarquee(page);
  19  |         const groups = marquee.locator('.home-brand-group');
  20  |         await expect(groups).toHaveCount(2);
  21  |         const original = groups.first();
  22  |         await expect(original.locator('img')).toHaveCount(5);
  23  |         expect(await original.locator('img').evaluateAll(images => images.map(image => image.alt)))
  24  |             .toEqual(['Wolong', 'OLI', 'Bredel', 'Aflex', 'Tecbell']);
  25  |         await expect(groups.last()).toHaveAttribute('aria-hidden', 'true');
  26  |         await expect(groups.last().locator('a:not([tabindex="-1"])')).toHaveCount(0);
  27  |         await expect(groups.last().locator('img:not([alt=""])')).toHaveCount(0);
  28  |         await expect(marquee.locator('img').first()).toHaveCSS('object-fit', 'contain');
  29  |         const track = marquee.locator('.home-brand-track');
  30  |         await expect(track).toHaveCSS('animation-duration', '30s');
  31  |         await expect(track).toHaveCSS('animation-timing-function', 'linear');
  32  |         await track.evaluate(element => {
  33  |             const animation = element.getAnimations()[0];
  34  |             animation.pause();
  35  |             animation.currentTime = 0;
  36  |         });
  37  |         const start = await original.boundingBox();
  38  |         const duplicate = await groups.last().boundingBox();
  39  |         const viewport = await marquee.boundingBox();
  40  |         expect(start.width).toBeCloseTo(duplicate.width, 1);
  41  |         expect(start.width).toBeGreaterThanOrEqual(viewport.width);
  42  |         expect(start.x + start.width).toBeCloseTo(duplicate.x, 1);
  43  |         await track.evaluate(element => element.getAnimations()[0].currentTime = 29999);
  44  |         expect((await groups.last().boundingBox()).x).toBeCloseTo(start.x, 0);
  45  |         await track.evaluate(element => element.getAnimations()[0].currentTime = 30000);
  46  |         expect((await original.boundingBox()).x).toBeCloseTo(start.x, 0);
  47  |         expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
  48  |         await marquee.locator('..').screenshot({ path: testInfo.outputPath(`brands-${width}.png`) });
  49  |     });
  50  | }
  51  | 
  52  | test('autoplay moves left, hover pauses and visible duplicate links navigate normally', async ({ page }) => {
  53  |     const marquee = await openMarquee(page);
  54  |     const track = marquee.locator('.home-brand-track');
  55  |     const before = await track.boundingBox();
  56  |     await page.waitForTimeout(200);
  57  |     expect((await track.boundingBox()).x).toBeLessThan(before.x);
  58  |     await marquee.hover();
  59  |     await expect(track).toHaveCSS('animation-play-state', 'paused');
  60  |     const paused = await track.boundingBox();
  61  |     await page.waitForTimeout(150);
  62  |     expect((await track.boundingBox()).x).toBeCloseTo(paused.x, 1);
  63  |     await track.evaluate(element => element.getAnimations()[0].currentTime = 27000);
  64  |     const link = marquee.locator('[aria-hidden="true"] a').first();
  65  |     const href = await link.getAttribute('href');
  66  |     await link.click();
  67  |     await expect(page).toHaveURL(href);
  68  |     await expect(page.locator('.home-brand-marquee')).toHaveCount(0);
  69  | });
  70  | 
  71  | test('keyboard users can reach every original logo without moving or duplicate focus targets', async ({ page }) => {
  72  |     await page.setViewportSize({ width: 390, height: 844 });
  73  |     const marquee = await openMarquee(page);
  74  |     const links = marquee.locator('.home-brand-group').first().locator('a');
  75  |     await page.keyboard.press('Tab');
  76  |     for (const link of await links.all()) {
  77  |         await link.focus();
  78  |         await expect(link).toBeFocused();
  79  |         await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'none');
  80  |         await expect(marquee.locator('[aria-hidden="true"]')).toBeHidden();
  81  |         await expect.poll(async () => {
  82  |             const item = await link.boundingBox();
  83  |             const view = await marquee.boundingBox();
  84  |             return item.x >= view.x - 1 && item.x + item.width <= view.x + view.width + 1;
  85  |         }).toBe(true);
  86  |     }
  87  |     await page.keyboard.press('Enter');
> 88  |     await expect(page).toHaveURL(/\/brand\/tecbell$/);
      |                        ^ Error: expect(page).toHaveURL(expected) failed
  89  | });
  90  | 
  91  | test('reduced motion shows one scrollable list and responds to preference changes', async ({ page }) => {
  92  |     await page.emulateMedia({ reducedMotion: 'reduce' });
  93  |     await page.setViewportSize({ width: 320, height: 740 });
  94  |     const marquee = await openMarquee(page);
  95  |     await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'none');
  96  |     await expect(marquee.locator('[aria-hidden="true"]')).toBeHidden();
  97  |     await marquee.evaluate(element => element.scrollLeft = element.scrollWidth);
  98  |     expect(await marquee.evaluate(element => element.scrollLeft)).toBeGreaterThan(0);
  99  |     await page.emulateMedia({ reducedMotion: 'no-preference' });
  100 |     await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'home-brand-scroll');
  101 |     await expect.poll(() => marquee.evaluate(element => element.scrollLeft)).toBe(0);
  102 | });
  103 | 
  104 | test('small CMS lists still cover the viewport without a gap at the loop', async ({ page }) => {
  105 |     await page.setViewportSize({ width: 1920, height: 900 });
  106 |     const marquee = await openMarquee(page);
  107 |     await marquee.locator('.home-brand-group').evaluateAll(groups => groups.forEach(group => {
  108 |         [...group.children].slice(2).forEach(item => item.remove());
  109 |     }));
  110 |     await marquee.locator('.home-brand-track').evaluate(element => element.getAnimations()[0].pause());
  111 |     const group = await marquee.locator('.home-brand-group').first().boundingBox();
  112 |     const duplicate = await marquee.locator('.home-brand-group').last().boundingBox();
  113 |     expect(group.width).toBeGreaterThanOrEqual((await marquee.boundingBox()).width);
  114 |     expect(group.x + group.width).toBeCloseTo(duplicate.x, 1);
  115 | });
  116 | 
  117 | test('marquee has no JavaScript dependency', async ({ browser, baseURL }) => {
  118 |     const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
  119 |     try {
  120 |         const page = await context.newPage();
  121 |         const marquee = await openMarquee(page);
  122 |         await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'home-brand-scroll');
  123 |         await marquee.hover();
  124 |         await marquee.locator('a').first().click();
  125 |         await expect(page).toHaveURL(/\/brand\/wolong$/);
  126 |     } finally { await context.close(); }
  127 | });
  128 | 
```