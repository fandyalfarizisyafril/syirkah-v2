# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: brand-marquee.spec.js >> verified logos and seamless geometry at 1440px
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
      - navigation "Navigasi utama" [ref=e20]:
        - link "Beranda" [ref=e21] [cursor=pointer]:
          - /url: http://127.0.0.1:8000
        - link "Tentang Kami" [ref=e22] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/tentang-kami
        - link "Produk" [ref=e23] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/produk
        - link "Brand" [ref=e24] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/brand
        - link "Publikasi" [ref=e25] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/publikasi
        - link "Cari" [ref=e26] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/search
        - link "Request Inquiry" [ref=e30] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/kontak
  - main [ref=e34]:
    - region "Bidang fokus SMART" [ref=e35]:
      - generic [ref=e37]:
        - paragraph [ref=e38]:
          - generic: ENGINEERING
        - heading "Engineering Solutions for Industrial Excellence." [level=1] [ref=e39]:
          - generic:
            - generic: Engineering Solutions
            - generic: for Industrial Excellence.
        - paragraph [ref=e40]:
          - generic: Solusi engineering terintegrasi untuk mendukung keandalan, efisiensi, dan kebutuhan operasional industri.
      - navigation "Pilih bidang fokus" [ref=e41]:
        - button "Tampilkan Engineering" [ref=e42] [cursor=pointer]:
          - generic [ref=e43]: "01"
          - generic [ref=e44]: Engineering
        - button "Tampilkan Mechanical" [ref=e45] [cursor=pointer]:
          - generic [ref=e46]: "02"
          - generic [ref=e47]: Mechanical
        - button "Tampilkan Electrical" [ref=e48] [cursor=pointer]:
          - generic [ref=e49]: "03"
          - generic [ref=e50]: Electrical
        - button "Tampilkan Instrumentation" [ref=e51] [cursor=pointer]:
          - generic [ref=e52]: "04"
          - generic [ref=e53]: Instrumentation
        - button "Tampilkan Oil Spill Response & Prevention" [ref=e54] [cursor=pointer]:
          - generic [ref=e55]: "05"
          - generic [ref=e56]: Oil Spill Response & Prevention
    - generic [ref=e57]:
      - generic [ref=e58]:
        - generic [ref=e59]:
          - paragraph [ref=e60]: PRODUCTS & SOLUTIONS
          - heading "Peralatan tepat. Operasional lebih baik." [level=2] [ref=e61]: Peralatan tepat.Operasional lebih baik.
        - generic [ref=e62]:
          - paragraph [ref=e63]: Jelajahi kebutuhan teknis Anda melalui tujuh kategori utama kami.
          - link "Semua Produk" [ref=e64] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/produk
      - region "Kategori produk" [ref=e68]:
        - list [ref=e69]:
          - listitem [ref=e70]:
            - link [ref=e71]:
              - /url: http://127.0.0.1:8000/produk/electric-motors-generators
              - generic [aria-hidden] [ref=e72]: Electric Motors & Generators
              - generic [ref=e73]:
                - heading "Electric Motors & Generators" [level=3] [ref=e74]
                - paragraph [ref=e75]: Electric motors, generators, PM motors, dan drive control untuk kebutuhan penggerak industri.
            - button "Informasi Electric Motors & Generators"
          - listitem [ref=e76]:
            - link [ref=e77]:
              - /url: http://127.0.0.1:8000/produk/vibration-technology
              - generic [aria-hidden] [ref=e78]: Vibration Technology
              - generic [ref=e79]:
                - heading "Vibration Technology" [level=3] [ref=e80]
                - paragraph [ref=e81]: Internal dan external vibrator, screen vibrator, serta converter untuk kebutuhan proses material.
            - button "Informasi Vibration Technology"
          - listitem [ref=e82]:
            - link [ref=e83]:
              - /url: http://127.0.0.1:8000/produk/chemical-metering-pump
              - generic [aria-hidden] [ref=e84]: Chemical Metering Pump
              - generic [ref=e85]:
                - heading "Chemical Metering Pump" [level=3] [ref=e86]
                - paragraph [ref=e87]: Chemical dosing pump untuk kebutuhan penakaran bahan kimia dalam proses industri.
            - button "Informasi Chemical Metering Pump"
          - listitem [ref=e88]:
            - link [ref=e89]:
              - /url: http://127.0.0.1:8000/produk/hose-pump
              - generic [aria-hidden] [ref=e90]: Hose Pump
              - generic [ref=e91]:
                - heading "Hose Pump" [level=3] [ref=e92]
                - paragraph [ref=e93]: Hose pump untuk pemindahan fluida pada proses industri.
            - button "Informasi Hose Pump"
          - listitem [ref=e94]:
            - link [ref=e95]:
              - /url: http://127.0.0.1:8000/produk/industrial-hose
              - generic [aria-hidden] [ref=e96]: Industrial Hose
              - generic [ref=e97]:
                - heading "Industrial Hose" [level=3] [ref=e98]
                - paragraph [ref=e99]: PTFE hose untuk kebutuhan transfer fluida industri.
            - button "Informasi Industrial Hose"
          - listitem [ref=e100]:
            - link [ref=e101]:
              - /url: http://127.0.0.1:8000/produk/air-compressor
              - generic [aria-hidden] [ref=e102]: Air Compressor
              - generic [ref=e103]:
                - heading "Air Compressor" [level=3] [ref=e104]
                - paragraph [ref=e105]: Air compressor dan refrigerated dryer untuk kebutuhan sistem udara bertekanan.
            - button "Informasi Air Compressor"
          - listitem [ref=e106]:
            - link [ref=e107]:
              - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention
              - generic [aria-hidden] [ref=e108]: Oil Spill Response & Prevention
              - generic [ref=e109]:
                - heading "Oil Spill Response & Prevention" [level=3] [ref=e110]
                - paragraph [ref=e111]: Oil boom, absorbent, skimmer, dispersant, dan spill kit untuk penanganan tumpahan minyak.
            - button "Informasi Oil Spill Response & Prevention"
        - generic [ref=e112]:
          - button "Kategori sebelumnya" [disabled] [ref=e113]
          - button "Kategori berikutnya" [ref=e116] [cursor=pointer]
    - region [ref=e119]:
      - generic [ref=e120]:
        - generic [ref=e121]:
          - paragraph [ref=e122]: YOUR INDUSTRIAL PARTNER
          - heading "Kebutuhan teknis Anda. Fokus kami." [level=2] [ref=e123]: Kebutuhan teknis Anda.Fokus kami.
        - generic [ref=e124]:
          - paragraph [ref=e125]: PT. Syirkah Mandiri Artomoro menyediakan equipment, spare part, dan solusi teknis untuk kebutuhan industri. Kami membantu menghubungkan kebutuhan operasional pelanggan dengan produk dan teknologi yang sesuai.
          - link "Tentang Artomoro" [ref=e126] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/tentang-kami
    - generic [ref=e130]:
      - generic [ref=e131]:
        - generic [ref=e132]:
          - paragraph [ref=e133]: BRANDS & PRINCIPALS
          - heading "Teknologi untuk setiap kebutuhan." [level=2] [ref=e134]
        - link "Lihat Brand" [ref=e135] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/brand
      - region "Brand dan principal" [ref=e139]:
        - generic [ref=e140]:
          - list [ref=e141]:
            - listitem [ref=e142]:
              - link [ref=e143] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/wolong
                - img "Wolong" [ref=e144]
            - listitem [ref=e145]:
              - link [ref=e146] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/oli
                - img "OLI" [ref=e147]
            - listitem [ref=e148]:
              - link [ref=e149] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/qdos
                - img "Qdos" [ref=e150]
            - listitem [ref=e151]:
              - link [ref=e152] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/bredel
                - img "Bredel" [ref=e153]
            - listitem [ref=e154]:
              - link [ref=e155] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/aflex
                - img "Aflex" [ref=e156]
            - listitem [ref=e157]:
              - link [ref=e158] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/tecbell
                - img "Tecbell" [ref=e159]
            - listitem [ref=e160]:
              - link [ref=e161] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/blu-c
                - img "BLU-C" [ref=e162]
          - list [aria-hidden] [ref=e163]:
            - listitem [ref=e164]:
              - link [ref=e165] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/wolong
            - listitem [ref=e166]:
              - link [ref=e167] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/oli
            - listitem [ref=e168]:
              - link [ref=e169] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/qdos
            - listitem [ref=e170]:
              - link [ref=e171] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/bredel
            - listitem [ref=e172]:
              - link [ref=e173] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/aflex
            - listitem [ref=e174]:
              - link [ref=e175] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/tecbell
            - listitem [ref=e176]:
              - link [ref=e177] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/blu-c
    - generic [ref=e178]:
      - generic [ref=e179]: Reliability
      - generic [ref=e183]: Efficiency
      - generic [ref=e187]: Operational Continuity
      - generic [ref=e191]: Safety
      - generic [ref=e195]: Environmental Protection
    - generic [ref=e200]:
      - generic [ref=e201]:
        - paragraph [ref=e202]: LET'S WORK TOGETHER
        - heading "Temukan solusi untuk kebutuhan industri Anda." [level=2] [ref=e203]: Temukan solusi untukkebutuhan industri Anda.
      - generic [ref=e204]:
        - link "Diskusikan Kebutuhan" [ref=e205] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/kontak
        - link "WhatsApp" [ref=e209] [cursor=pointer]:
          - /url: https://wa.me/6281266723815
  - contentinfo [ref=e212]:
    - generic [ref=e213]:
      - generic [ref=e214]:
        - link "PT. Syirkah Mandiri Artomoro, beranda" [ref=e215] [cursor=pointer]:
          - /url: http://127.0.0.1:8000
          - img "SMART - Equipment & Parts Solutions" [ref=e216]
        - paragraph [ref=e217]: PT. Syirkah Mandiri Artomoro
        - paragraph [ref=e218]: General Supplier & Technical Solutions
        - paragraph [ref=e219]: PT. Syirkah Mandiri Artomoro menyediakan equipment, spare part, dan solusi teknis untuk kebutuhan industri. Kami membantu menghubungkan kebutuhan operasional pelanggan dengan produk dan teknologi yang sesuai.
      - generic [ref=e220]:
        - generic [ref=e221]:
          - heading "Jelajahi" [level=2] [ref=e222]
          - navigation "Navigasi footer" [ref=e223]:
            - link "Tentang Kami" [ref=e224] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/tentang-kami
            - link "Produk & Solusi" [ref=e225] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk
            - link "Brands & Principals" [ref=e226] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/brand
            - link "Publikasi" [ref=e227] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/publikasi
            - link "Kontak" [ref=e228] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/kontak
        - generic [ref=e229]:
          - heading "Produk & Solusi" [level=2] [ref=e230]
          - navigation "Kategori produk" [ref=e231]:
            - link "Electric Motors & Generators" [ref=e232] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/electric-motors-generators
            - link "Vibration Technology" [ref=e233] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/vibration-technology
            - link "Chemical Metering Pump" [ref=e234] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/chemical-metering-pump
            - link "Hose Pump" [ref=e235] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/hose-pump
            - link "Industrial Hose" [ref=e236] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/industrial-hose
            - link "Air Compressor" [ref=e237] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/air-compressor
            - link "Oil Spill Response & Prevention" [ref=e238] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention
        - generic [ref=e239]:
          - heading "Hubungi Kami" [level=2] [ref=e240]
          - link "Telepon +62 812-6672-3815" [ref=e241] [cursor=pointer]:
            - /url: tel:+6281266723815
            - generic [ref=e244]:
              - generic [ref=e245]: Telepon
              - text: +62 812-6672-3815
          - link "Email admin@smartomoro.com" [ref=e246] [cursor=pointer]:
            - /url: mailto:admin@smartomoro.com
            - generic [ref=e250]:
              - generic [ref=e251]: Email
              - text: admin@smartomoro.com
          - link "Sales doni.rahmat@smartomoro.com" [ref=e252] [cursor=pointer]:
            - /url: mailto:doni.rahmat@smartomoro.com
            - generic [ref=e256]:
              - generic [ref=e257]: Sales
              - text: doni.rahmat@smartomoro.com
          - generic [ref=e262]:
            - generic [ref=e263]: Kantor
            - generic [ref=e264]: Jalan Teladan No. 7, RT 04/RW 10, Kel. Simpang Baru, Kec. Bina Widya, Kota Pekanbaru, Riau 28293.
          - link "Hubungi via WhatsApp" [ref=e265] [cursor=pointer]:
            - /url: https://wa.me/6281266723815
    - generic [ref=e271]:
      - generic [ref=e272]:
        - paragraph [ref=e273]: Butuh bantuan menemukan produk yang tepat?
        - link "Diskusikan Kebutuhan" [ref=e274] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/kontak
      - generic [ref=e278]:
        - generic [ref=e279]:
          - generic [ref=e280]: © 2026 Fandy Alfarizi Syafril.
          - generic [ref=e281]: PT. Syirkah Mandiri Artomoro
        - navigation "Informasi legal" [ref=e282]:
          - link "Kebijakan Privasi" [ref=e283] [cursor=pointer]:
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