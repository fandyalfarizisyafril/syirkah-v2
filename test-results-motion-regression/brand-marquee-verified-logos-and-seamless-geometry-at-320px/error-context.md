# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: brand-marquee.spec.js >> verified logos and seamless geometry at 320px
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
    - link "+62 812-6672-3815" [ref=e10] [cursor=pointer]:
      - /url: tel:+6281266723815
  - banner [ref=e11]:
    - generic [ref=e12]:
      - link "PT. Syirkah Mandiri Artomoro, beranda" [ref=e13] [cursor=pointer]:
        - /url: http://127.0.0.1:8000
        - generic [ref=e14]:
          - img "SMART" [ref=e15]
          - generic [ref=e16]:
            - generic [ref=e17]: PT. Syirkah Mandiri Artomoro
            - generic [ref=e18]: Equipment & Parts Solutions
      - button "Buka navigasi" [ref=e19] [cursor=pointer]
  - main [ref=e21]:
    - region "Bidang fokus SMART" [ref=e22]:
      - generic [ref=e24]:
        - paragraph [ref=e25]:
          - generic: ENGINEERING
        - heading "Engineering Solutions for Industrial Excellence." [level=1] [ref=e26]:
          - generic:
            - generic: Engineering Solutions
            - generic: for Industrial Excellence.
        - paragraph [ref=e27]:
          - generic: Solusi engineering terintegrasi untuk mendukung keandalan, efisiensi, dan kebutuhan operasional industri.
      - navigation "Pilih bidang fokus" [ref=e28]:
        - button "Tampilkan Engineering" [ref=e29] [cursor=pointer]:
          - generic [ref=e30]: "01"
          - generic [ref=e31]: Engineering
        - button "Tampilkan Mechanical" [ref=e32] [cursor=pointer]:
          - generic [ref=e33]: "02"
          - generic [ref=e34]: Mechanical
        - button "Tampilkan Electrical" [ref=e35] [cursor=pointer]:
          - generic [ref=e36]: "03"
          - generic [ref=e37]: Electrical
        - button "Tampilkan Instrumentation" [ref=e38] [cursor=pointer]:
          - generic [ref=e39]: "04"
          - generic [ref=e40]: Instrumentation
        - button "Tampilkan Oil Spill Response & Prevention" [ref=e41] [cursor=pointer]:
          - generic [ref=e42]: "05"
          - generic [ref=e43]: Oil Spill Response & Prevention
    - generic [ref=e44]:
      - generic [ref=e45]:
        - generic [ref=e46]:
          - paragraph [ref=e47]: PRODUCTS & SOLUTIONS
          - heading "Peralatan tepat. Operasional lebih baik." [level=2] [ref=e48]: Peralatan tepat.Operasional lebih baik.
        - generic [ref=e49]:
          - paragraph [ref=e50]: Jelajahi kebutuhan teknis Anda melalui tujuh kategori utama kami.
          - link "Semua Produk" [ref=e51] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/produk
      - region "Kategori produk" [ref=e55]:
        - list [ref=e56]:
          - listitem [ref=e57]:
            - link [ref=e58]:
              - /url: http://127.0.0.1:8000/produk/electric-motors-generators
              - generic [aria-hidden] [ref=e59]: Electric Motors & Generators
              - generic [ref=e60]:
                - heading "Electric Motors & Generators" [level=3] [ref=e61]
                - paragraph [ref=e62]: Electric motors, generators, PM motors, dan drive control untuk kebutuhan penggerak industri.
            - button "Informasi Electric Motors & Generators"
          - listitem [ref=e63]:
            - link [ref=e64]:
              - /url: http://127.0.0.1:8000/produk/vibration-technology
              - generic [aria-hidden] [ref=e65]: Vibration Technology
              - generic [ref=e66]:
                - heading "Vibration Technology" [level=3] [ref=e67]
                - paragraph [ref=e68]: Internal dan external vibrator, screen vibrator, serta converter untuk kebutuhan proses material.
            - button "Informasi Vibration Technology"
          - listitem [ref=e69]:
            - link [ref=e70]:
              - /url: http://127.0.0.1:8000/produk/chemical-metering-pump
              - generic [aria-hidden] [ref=e71]: Chemical Metering Pump
              - generic [ref=e72]:
                - heading "Chemical Metering Pump" [level=3] [ref=e73]
                - paragraph [ref=e74]: Chemical dosing pump untuk kebutuhan penakaran bahan kimia dalam proses industri.
            - button "Informasi Chemical Metering Pump"
          - listitem [ref=e75]:
            - link [ref=e76]:
              - /url: http://127.0.0.1:8000/produk/hose-pump
              - generic [aria-hidden] [ref=e77]: Hose Pump
              - generic [ref=e78]:
                - heading "Hose Pump" [level=3] [ref=e79]
                - paragraph [ref=e80]: Hose pump untuk pemindahan fluida pada proses industri.
            - button "Informasi Hose Pump"
          - listitem [ref=e81]:
            - link [ref=e82]:
              - /url: http://127.0.0.1:8000/produk/industrial-hose
              - generic [aria-hidden] [ref=e83]: Industrial Hose
              - generic [ref=e84]:
                - heading "Industrial Hose" [level=3] [ref=e85]
                - paragraph [ref=e86]: PTFE hose untuk kebutuhan transfer fluida industri.
            - button "Informasi Industrial Hose"
          - listitem [ref=e87]:
            - link [ref=e88]:
              - /url: http://127.0.0.1:8000/produk/air-compressor
              - generic [aria-hidden] [ref=e89]: Air Compressor
              - generic [ref=e90]:
                - heading "Air Compressor" [level=3] [ref=e91]
                - paragraph [ref=e92]: Air compressor dan refrigerated dryer untuk kebutuhan sistem udara bertekanan.
            - button "Informasi Air Compressor"
          - listitem [ref=e93]:
            - link [ref=e94]:
              - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention
              - generic [aria-hidden] [ref=e95]: Oil Spill Response & Prevention
              - generic [ref=e96]:
                - heading "Oil Spill Response & Prevention" [level=3] [ref=e97]
                - paragraph [ref=e98]: Oil boom, absorbent, skimmer, dispersant, dan spill kit untuk penanganan tumpahan minyak.
            - button "Informasi Oil Spill Response & Prevention"
        - generic [ref=e99]:
          - button "Kategori sebelumnya" [disabled] [ref=e100]
          - button "Kategori berikutnya" [ref=e103] [cursor=pointer]
    - region [ref=e106]:
      - generic [ref=e107]:
        - generic [ref=e108]:
          - paragraph [ref=e109]: YOUR INDUSTRIAL PARTNER
          - heading "Kebutuhan teknis Anda. Fokus kami." [level=2] [ref=e110]: Kebutuhan teknis Anda.Fokus kami.
        - generic [ref=e111]:
          - paragraph [ref=e112]: PT. Syirkah Mandiri Artomoro menyediakan equipment, spare part, dan solusi teknis untuk kebutuhan industri. Kami membantu menghubungkan kebutuhan operasional pelanggan dengan produk dan teknologi yang sesuai.
          - link "Tentang Artomoro" [ref=e113] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/tentang-kami
    - generic [ref=e117]:
      - generic [ref=e118]:
        - generic [ref=e119]:
          - paragraph [ref=e120]: BRANDS & PRINCIPALS
          - heading "Teknologi untuk setiap kebutuhan." [level=2] [ref=e121]
        - link "Lihat Brand" [ref=e122] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/brand
      - region "Brand dan principal" [ref=e126]:
        - generic [ref=e127]:
          - list [ref=e128]:
            - listitem [ref=e129]:
              - link [ref=e130] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/wolong
                - img "Wolong" [ref=e131]
            - listitem [ref=e132]:
              - link [ref=e133] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/oli
                - img "OLI" [ref=e134]
            - listitem [ref=e135]:
              - link [ref=e136] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/qdos
                - img "Qdos" [ref=e137]
            - listitem [ref=e138]:
              - link [ref=e139] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/bredel
                - img "Bredel" [ref=e140]
            - listitem [ref=e141]:
              - link [ref=e142] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/aflex
                - img "Aflex" [ref=e143]
            - listitem [ref=e144]:
              - link [ref=e145] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/tecbell
                - img "Tecbell" [ref=e146]
            - listitem [ref=e147]:
              - link [ref=e148] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/blu-c
                - img "BLU-C" [ref=e149]
          - list [aria-hidden] [ref=e150]:
            - listitem [ref=e151]:
              - link [ref=e152] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/wolong
            - listitem [ref=e153]:
              - link [ref=e154] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/oli
            - listitem [ref=e155]:
              - link [ref=e156] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/qdos
            - listitem [ref=e157]:
              - link [ref=e158] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/bredel
            - listitem [ref=e159]:
              - link [ref=e160] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/aflex
            - listitem [ref=e161]:
              - link [ref=e162] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/tecbell
            - listitem [ref=e163]:
              - link [ref=e164] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/blu-c
    - generic [ref=e165]:
      - generic [ref=e166]: Reliability
      - generic [ref=e170]: Efficiency
      - generic [ref=e174]: Operational Continuity
      - generic [ref=e178]: Safety
      - generic [ref=e182]: Environmental Protection
    - generic [ref=e187]:
      - generic [ref=e188]:
        - paragraph [ref=e189]: LET'S WORK TOGETHER
        - heading "Temukan solusi untuk kebutuhan industri Anda." [level=2] [ref=e190]: Temukan solusi untukkebutuhan industri Anda.
      - generic [ref=e191]:
        - link "Diskusikan Kebutuhan" [ref=e192] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/kontak
        - link "WhatsApp" [ref=e196] [cursor=pointer]:
          - /url: https://wa.me/6281266723815
  - contentinfo [ref=e199]:
    - generic [ref=e200]:
      - generic [ref=e201]:
        - link "PT. Syirkah Mandiri Artomoro, beranda" [ref=e202] [cursor=pointer]:
          - /url: http://127.0.0.1:8000
          - img "SMART - Equipment & Parts Solutions" [ref=e203]
        - paragraph [ref=e204]: PT. Syirkah Mandiri Artomoro
        - paragraph [ref=e205]: General Supplier & Technical Solutions
        - paragraph [ref=e206]: PT. Syirkah Mandiri Artomoro menyediakan equipment, spare part, dan solusi teknis untuk kebutuhan industri. Kami membantu menghubungkan kebutuhan operasional pelanggan dengan produk dan teknologi yang sesuai.
      - generic [ref=e207]:
        - generic [ref=e208]:
          - heading "Jelajahi" [level=2] [ref=e209]
          - navigation "Navigasi footer" [ref=e210]:
            - link "Tentang Kami" [ref=e211] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/tentang-kami
            - link "Produk & Solusi" [ref=e212] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk
            - link "Brands & Principals" [ref=e213] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/brand
            - link "Publikasi" [ref=e214] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/publikasi
            - link "Kontak" [ref=e215] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/kontak
        - generic [ref=e216]:
          - heading "Produk & Solusi" [level=2] [ref=e217]
          - navigation "Kategori produk" [ref=e218]:
            - link "Electric Motors & Generators" [ref=e219] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/electric-motors-generators
            - link "Vibration Technology" [ref=e220] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/vibration-technology
            - link "Chemical Metering Pump" [ref=e221] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/chemical-metering-pump
            - link "Hose Pump" [ref=e222] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/hose-pump
            - link "Industrial Hose" [ref=e223] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/industrial-hose
            - link "Air Compressor" [ref=e224] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/air-compressor
            - link "Oil Spill Response & Prevention" [ref=e225] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention
        - generic [ref=e226]:
          - heading "Hubungi Kami" [level=2] [ref=e227]
          - link "Telepon +62 812-6672-3815" [ref=e228] [cursor=pointer]:
            - /url: tel:+6281266723815
            - generic [ref=e231]:
              - generic [ref=e232]: Telepon
              - text: +62 812-6672-3815
          - link "Email admin@smartomoro.com" [ref=e233] [cursor=pointer]:
            - /url: mailto:admin@smartomoro.com
            - generic [ref=e237]:
              - generic [ref=e238]: Email
              - text: admin@smartomoro.com
          - link "Sales doni.rahmat@smartomoro.com" [ref=e239] [cursor=pointer]:
            - /url: mailto:doni.rahmat@smartomoro.com
            - generic [ref=e243]:
              - generic [ref=e244]: Sales
              - text: doni.rahmat@smartomoro.com
          - generic [ref=e249]:
            - generic [ref=e250]: Kantor
            - generic [ref=e251]: Jalan Teladan No. 7, RT 04/RW 10, Kel. Simpang Baru, Kec. Bina Widya, Kota Pekanbaru, Riau 28293.
          - link "Hubungi via WhatsApp" [ref=e252] [cursor=pointer]:
            - /url: https://wa.me/6281266723815
    - generic [ref=e258]:
      - generic [ref=e259]:
        - paragraph [ref=e260]: Butuh bantuan menemukan produk yang tepat?
        - link "Diskusikan Kebutuhan" [ref=e261] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/kontak
      - generic [ref=e265]:
        - generic [ref=e266]:
          - generic [ref=e267]: © 2026 Fandy Alfarizi Syafril.
          - generic [ref=e268]: PT. Syirkah Mandiri Artomoro
        - navigation "Informasi legal" [ref=e269]:
          - link "Kebijakan Privasi" [ref=e270] [cursor=pointer]:
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