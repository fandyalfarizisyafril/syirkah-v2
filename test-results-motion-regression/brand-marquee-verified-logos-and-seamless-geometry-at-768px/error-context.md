# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: brand-marquee.spec.js >> verified logos and seamless geometry at 768px
- Location: tests\Browser\brand-marquee.spec.js:16:5

# Error details

```
Error: expect(locator).toHaveCount(expected) failed

Locator:  locator('.home-brand-marquee').locator('.home-brand-group').first().locator('img')
Expected: 5
Received: 7
Timeout:  5000ms

Call log:
  - Expect "toHaveCount" locator('.home-brand-marquee').locator('.home-brand-group').first().locator('img') with timeout 5000ms
  - waiting for locator('.home-brand-marquee').locator('.home-brand-group').first().locator('img')
    14 × locator resolved to 7 elements
       - unexpected value "7"

```

# Page snapshot

```yaml
- generic [active] [ref=e1]:
  - link "Langsung ke konten" [ref=e2] [cursor=pointer]:
    - /url: "#main"
  - generic [ref=e4]:
    - generic [ref=e5]: Pekanbaru, Indonesia
    - generic [ref=e9]:
      - link "admin@smartomoro.com" [ref=e10] [cursor=pointer]:
        - /url: mailto:admin@smartomoro.com
      - link "+62 812-6672-3815" [ref=e11] [cursor=pointer]:
        - /url: tel:+6281266723815
  - banner [ref=e12]:
    - generic [ref=e13]:
      - link "PT. Syirkah Mandiri Artomoro, beranda" [ref=e14] [cursor=pointer]:
        - /url: http://127.0.0.1:8000
        - generic [ref=e15]:
          - img "SMART" [ref=e16]
          - generic [ref=e17]:
            - generic [ref=e18]: PT. Syirkah Mandiri Artomoro
            - generic [ref=e19]: Equipment & Parts Solutions
      - button "Buka navigasi" [ref=e20] [cursor=pointer]
  - main [ref=e22]:
    - region "Bidang fokus SMART" [ref=e23]:
      - generic [ref=e25]:
        - paragraph [ref=e26]:
          - generic: ENGINEERING
        - heading "Engineering Solutions for Industrial Excellence." [level=1] [ref=e27]:
          - generic:
            - generic: Engineering Solutions
            - generic: for Industrial Excellence.
        - paragraph [ref=e28]:
          - generic: Solusi engineering terintegrasi untuk mendukung keandalan, efisiensi, dan kebutuhan operasional industri.
      - navigation "Pilih bidang fokus" [ref=e29]:
        - button "Tampilkan Engineering" [ref=e30] [cursor=pointer]:
          - generic [ref=e31]: "01"
          - generic [ref=e32]: Engineering
        - button "Tampilkan Mechanical" [ref=e33] [cursor=pointer]:
          - generic [ref=e34]: "02"
          - generic [ref=e35]: Mechanical
        - button "Tampilkan Electrical" [ref=e36] [cursor=pointer]:
          - generic [ref=e37]: "03"
          - generic [ref=e38]: Electrical
        - button "Tampilkan Instrumentation" [ref=e39] [cursor=pointer]:
          - generic [ref=e40]: "04"
          - generic [ref=e41]: Instrumentation
        - button "Tampilkan Oil Spill Response & Prevention" [ref=e42] [cursor=pointer]:
          - generic [ref=e43]: "05"
          - generic [ref=e44]: Oil Spill Response & Prevention
    - generic [ref=e45]:
      - generic [ref=e46]:
        - generic [ref=e47]:
          - paragraph [ref=e48]: PRODUCTS & SOLUTIONS
          - heading "Peralatan tepat. Operasional lebih baik." [level=2] [ref=e49]: Peralatan tepat.Operasional lebih baik.
        - generic [ref=e50]:
          - paragraph [ref=e51]: Jelajahi kebutuhan teknis Anda melalui tujuh kategori utama kami.
          - link "Semua Produk" [ref=e52] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/produk
      - region "Kategori produk" [ref=e56]:
        - list [ref=e57]:
          - listitem [ref=e58]:
            - link [ref=e59]:
              - /url: http://127.0.0.1:8000/produk/electric-motors-generators
              - generic [aria-hidden] [ref=e60]: Electric Motors & Generators
              - generic [ref=e61]:
                - heading "Electric Motors & Generators" [level=3] [ref=e62]
                - paragraph [ref=e63]: Electric motors, generators, PM motors, dan drive control untuk kebutuhan penggerak industri.
            - button "Informasi Electric Motors & Generators"
          - listitem [ref=e64]:
            - link [ref=e65]:
              - /url: http://127.0.0.1:8000/produk/vibration-technology
              - generic [aria-hidden] [ref=e66]: Vibration Technology
              - generic [ref=e67]:
                - heading "Vibration Technology" [level=3] [ref=e68]
                - paragraph [ref=e69]: Internal dan external vibrator, screen vibrator, serta converter untuk kebutuhan proses material.
            - button "Informasi Vibration Technology"
          - listitem [ref=e70]:
            - link [ref=e71]:
              - /url: http://127.0.0.1:8000/produk/chemical-metering-pump
              - generic [aria-hidden] [ref=e72]: Chemical Metering Pump
              - generic [ref=e73]:
                - heading "Chemical Metering Pump" [level=3] [ref=e74]
                - paragraph [ref=e75]: Chemical dosing pump untuk kebutuhan penakaran bahan kimia dalam proses industri.
            - button "Informasi Chemical Metering Pump"
          - listitem [ref=e76]:
            - link [ref=e77]:
              - /url: http://127.0.0.1:8000/produk/hose-pump
              - generic [aria-hidden] [ref=e78]: Hose Pump
              - generic [ref=e79]:
                - heading "Hose Pump" [level=3] [ref=e80]
                - paragraph [ref=e81]: Hose pump untuk pemindahan fluida pada proses industri.
            - button "Informasi Hose Pump"
          - listitem [ref=e82]:
            - link [ref=e83]:
              - /url: http://127.0.0.1:8000/produk/industrial-hose
              - generic [aria-hidden] [ref=e84]: Industrial Hose
              - generic [ref=e85]:
                - heading "Industrial Hose" [level=3] [ref=e86]
                - paragraph [ref=e87]: PTFE hose untuk kebutuhan transfer fluida industri.
            - button "Informasi Industrial Hose"
          - listitem [ref=e88]:
            - link [ref=e89]:
              - /url: http://127.0.0.1:8000/produk/air-compressor
              - generic [aria-hidden] [ref=e90]: Air Compressor
              - generic [ref=e91]:
                - heading "Air Compressor" [level=3] [ref=e92]
                - paragraph [ref=e93]: Air compressor dan refrigerated dryer untuk kebutuhan sistem udara bertekanan.
            - button "Informasi Air Compressor"
          - listitem [ref=e94]:
            - link [ref=e95]:
              - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention
              - generic [aria-hidden] [ref=e96]: Oil Spill Response & Prevention
              - generic [ref=e97]:
                - heading "Oil Spill Response & Prevention" [level=3] [ref=e98]
                - paragraph [ref=e99]: Oil boom, absorbent, skimmer, dispersant, dan spill kit untuk penanganan tumpahan minyak.
            - button "Informasi Oil Spill Response & Prevention"
        - generic [ref=e100]:
          - button "Kategori sebelumnya" [disabled] [ref=e101]
          - button "Kategori berikutnya" [ref=e104] [cursor=pointer]
    - region [ref=e107]:
      - generic [ref=e108]:
        - generic [ref=e109]:
          - paragraph [ref=e110]: YOUR INDUSTRIAL PARTNER
          - heading "Kebutuhan teknis Anda. Fokus kami." [level=2] [ref=e111]: Kebutuhan teknis Anda.Fokus kami.
        - generic [ref=e112]:
          - paragraph [ref=e113]: PT. Syirkah Mandiri Artomoro menyediakan equipment, spare part, dan solusi teknis untuk kebutuhan industri. Kami membantu menghubungkan kebutuhan operasional pelanggan dengan produk dan teknologi yang sesuai.
          - link "Tentang Artomoro" [ref=e114] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/tentang-kami
    - generic [ref=e118]:
      - generic [ref=e119]:
        - generic [ref=e120]:
          - paragraph [ref=e121]: BRANDS & PRINCIPALS
          - heading "Teknologi untuk setiap kebutuhan." [level=2] [ref=e122]
        - link "Lihat Brand" [ref=e123] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/brand
      - region "Brand dan principal" [ref=e127]:
        - generic [ref=e128]:
          - list [ref=e129]:
            - listitem [ref=e130]:
              - link [ref=e131] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/wolong
                - img "Wolong" [ref=e132]
            - listitem [ref=e133]:
              - link [ref=e134] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/oli
                - img "OLI" [ref=e135]
            - listitem [ref=e136]:
              - link [ref=e137] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/qdos
                - img "Qdos" [ref=e138]
            - listitem [ref=e139]:
              - link [ref=e140] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/bredel
                - img "Bredel" [ref=e141]
            - listitem [ref=e142]:
              - link [ref=e143] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/aflex
                - img "Aflex" [ref=e144]
            - listitem [ref=e145]:
              - link [ref=e146] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/tecbell
                - img "Tecbell" [ref=e147]
            - listitem [ref=e148]:
              - link [ref=e149] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/blu-c
                - img "BLU-C" [ref=e150]
          - list [aria-hidden] [ref=e151]:
            - listitem [ref=e152]:
              - link [ref=e153] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/wolong
            - listitem [ref=e154]:
              - link [ref=e155] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/oli
            - listitem [ref=e156]:
              - link [ref=e157] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/qdos
            - listitem [ref=e158]:
              - link [ref=e159] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/bredel
            - listitem [ref=e160]:
              - link [ref=e161] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/aflex
            - listitem [ref=e162]:
              - link [ref=e163] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/tecbell
            - listitem [ref=e164]:
              - link [ref=e165] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/blu-c
    - generic [ref=e166]:
      - generic [ref=e167]: Reliability
      - generic [ref=e171]: Efficiency
      - generic [ref=e175]: Operational Continuity
      - generic [ref=e179]: Safety
      - generic [ref=e183]: Environmental Protection
    - generic [ref=e188]:
      - generic [ref=e189]:
        - paragraph [ref=e190]: LET'S WORK TOGETHER
        - heading "Temukan solusi untuk kebutuhan industri Anda." [level=2] [ref=e191]: Temukan solusi untukkebutuhan industri Anda.
      - generic [ref=e192]:
        - link "Diskusikan Kebutuhan" [ref=e193] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/kontak
        - link "WhatsApp" [ref=e197] [cursor=pointer]:
          - /url: https://wa.me/6281266723815
  - contentinfo [ref=e200]:
    - generic [ref=e201]:
      - generic [ref=e202]:
        - link "PT. Syirkah Mandiri Artomoro, beranda" [ref=e203] [cursor=pointer]:
          - /url: http://127.0.0.1:8000
          - img "SMART - Equipment & Parts Solutions" [ref=e204]
        - paragraph [ref=e205]: PT. Syirkah Mandiri Artomoro
        - paragraph [ref=e206]: General Supplier & Technical Solutions
        - paragraph [ref=e207]: PT. Syirkah Mandiri Artomoro menyediakan equipment, spare part, dan solusi teknis untuk kebutuhan industri. Kami membantu menghubungkan kebutuhan operasional pelanggan dengan produk dan teknologi yang sesuai.
      - generic [ref=e208]:
        - generic [ref=e209]:
          - heading "Jelajahi" [level=2] [ref=e210]
          - navigation "Navigasi footer" [ref=e211]:
            - link "Tentang Kami" [ref=e212] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/tentang-kami
            - link "Produk & Solusi" [ref=e213] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk
            - link "Brands & Principals" [ref=e214] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/brand
            - link "Publikasi" [ref=e215] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/publikasi
            - link "Kontak" [ref=e216] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/kontak
        - generic [ref=e217]:
          - heading "Produk & Solusi" [level=2] [ref=e218]
          - navigation "Kategori produk" [ref=e219]:
            - link "Electric Motors & Generators" [ref=e220] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/electric-motors-generators
            - link "Vibration Technology" [ref=e221] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/vibration-technology
            - link "Chemical Metering Pump" [ref=e222] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/chemical-metering-pump
            - link "Hose Pump" [ref=e223] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/hose-pump
            - link "Industrial Hose" [ref=e224] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/industrial-hose
            - link "Air Compressor" [ref=e225] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/air-compressor
            - link "Oil Spill Response & Prevention" [ref=e226] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention
        - generic [ref=e227]:
          - heading "Hubungi Kami" [level=2] [ref=e228]
          - link "Telepon +62 812-6672-3815" [ref=e229] [cursor=pointer]:
            - /url: tel:+6281266723815
            - generic [ref=e232]:
              - generic [ref=e233]: Telepon
              - text: +62 812-6672-3815
          - link "Email admin@smartomoro.com" [ref=e234] [cursor=pointer]:
            - /url: mailto:admin@smartomoro.com
            - generic [ref=e238]:
              - generic [ref=e239]: Email
              - text: admin@smartomoro.com
          - link "Sales doni.rahmat@smartomoro.com" [ref=e240] [cursor=pointer]:
            - /url: mailto:doni.rahmat@smartomoro.com
            - generic [ref=e244]:
              - generic [ref=e245]: Sales
              - text: doni.rahmat@smartomoro.com
          - generic [ref=e250]:
            - generic [ref=e251]: Kantor
            - generic [ref=e252]: Jalan Teladan No. 7, RT 04/RW 10, Kel. Simpang Baru, Kec. Bina Widya, Kota Pekanbaru, Riau 28293.
          - link "Hubungi via WhatsApp" [ref=e253] [cursor=pointer]:
            - /url: https://wa.me/6281266723815
    - generic [ref=e259]:
      - generic [ref=e260]:
        - paragraph [ref=e261]: Butuh bantuan menemukan produk yang tepat?
        - link "Diskusikan Kebutuhan" [ref=e262] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/kontak
      - generic [ref=e266]:
        - generic [ref=e267]:
          - generic [ref=e268]: © 2026 Fandy Alfarizi Syafril.
          - generic [ref=e269]: PT. Syirkah Mandiri Artomoro
        - navigation "Informasi legal" [ref=e270]:
          - link "Kebijakan Privasi" [ref=e271] [cursor=pointer]:
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
> 22  |         await expect(original.locator('img')).toHaveCount(5);
      |                                               ^ Error: expect(locator).toHaveCount(expected) failed
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
  88  |     await expect(page).toHaveURL(/\/brand\/tecbell$/);
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
```