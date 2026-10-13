# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: brand-marquee.spec.js >> small CMS lists still cover the viewport without a gap at the loop
- Location: tests\Browser\brand-marquee.spec.js:104:1

# Error details

```
Error: expect(received).toBeGreaterThanOrEqual(expected)

Expected: >= 1240
Received:    1239.9999389648438
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
          - list [aria-hidden] [ref=e148]:
            - listitem [ref=e149]:
              - link [ref=e150] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/wolong
            - listitem [ref=e151]:
              - link [ref=e152] [cursor=pointer]:
                - /url: http://127.0.0.1:8000/brand/oli
    - generic [ref=e153]:
      - generic [ref=e154]: Reliability
      - generic [ref=e158]: Efficiency
      - generic [ref=e162]: Operational Continuity
      - generic [ref=e166]: Safety
      - generic [ref=e170]: Environmental Protection
    - generic [ref=e175]:
      - generic [ref=e176]:
        - paragraph [ref=e177]: LET'S WORK TOGETHER
        - heading "Temukan solusi untuk kebutuhan industri Anda." [level=2] [ref=e178]: Temukan solusi untukkebutuhan industri Anda.
      - generic [ref=e179]:
        - link "Diskusikan Kebutuhan" [ref=e180] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/kontak
        - link "WhatsApp" [ref=e184] [cursor=pointer]:
          - /url: https://wa.me/6281266723815
  - contentinfo [ref=e187]:
    - generic [ref=e188]:
      - generic [ref=e189]:
        - link "PT. Syirkah Mandiri Artomoro, beranda" [ref=e190] [cursor=pointer]:
          - /url: http://127.0.0.1:8000
          - img "SMART - Equipment & Parts Solutions" [ref=e191]
        - paragraph [ref=e192]: PT. Syirkah Mandiri Artomoro
        - paragraph [ref=e193]: General Supplier & Technical Solutions
        - paragraph [ref=e194]: PT. Syirkah Mandiri Artomoro menyediakan equipment, spare part, dan solusi teknis untuk kebutuhan industri. Kami membantu menghubungkan kebutuhan operasional pelanggan dengan produk dan teknologi yang sesuai.
      - generic [ref=e195]:
        - generic [ref=e196]:
          - heading "Jelajahi" [level=2] [ref=e197]
          - navigation "Navigasi footer" [ref=e198]:
            - link "Tentang Kami" [ref=e199] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/tentang-kami
            - link "Produk & Solusi" [ref=e200] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk
            - link "Brands & Principals" [ref=e201] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/brand
            - link "Publikasi" [ref=e202] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/publikasi
            - link "Kontak" [ref=e203] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/kontak
        - generic [ref=e204]:
          - heading "Produk & Solusi" [level=2] [ref=e205]
          - navigation "Kategori produk" [ref=e206]:
            - link "Electric Motors & Generators" [ref=e207] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/electric-motors-generators
            - link "Vibration Technology" [ref=e208] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/vibration-technology
            - link "Chemical Metering Pump" [ref=e209] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/chemical-metering-pump
            - link "Hose Pump" [ref=e210] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/hose-pump
            - link "Industrial Hose" [ref=e211] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/industrial-hose
            - link "Air Compressor" [ref=e212] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/air-compressor
            - link "Oil Spill Response & Prevention" [ref=e213] [cursor=pointer]:
              - /url: http://127.0.0.1:8000/produk/oil-spill-response-prevention
        - generic [ref=e214]:
          - heading "Hubungi Kami" [level=2] [ref=e215]
          - link "Telepon +62 812-6672-3815" [ref=e216] [cursor=pointer]:
            - /url: tel:+6281266723815
            - generic [ref=e219]:
              - generic [ref=e220]: Telepon
              - text: +62 812-6672-3815
          - link "Email admin@smartomoro.com" [ref=e221] [cursor=pointer]:
            - /url: mailto:admin@smartomoro.com
            - generic [ref=e225]:
              - generic [ref=e226]: Email
              - text: admin@smartomoro.com
          - link "Sales doni.rahmat@smartomoro.com" [ref=e227] [cursor=pointer]:
            - /url: mailto:doni.rahmat@smartomoro.com
            - generic [ref=e231]:
              - generic [ref=e232]: Sales
              - text: doni.rahmat@smartomoro.com
          - generic [ref=e237]:
            - generic [ref=e238]: Kantor
            - generic [ref=e239]: Jalan Teladan No. 7, RT 04/RW 10, Kel. Simpang Baru, Kec. Bina Widya, Kota Pekanbaru, Riau 28293.
          - link "Hubungi via WhatsApp" [ref=e240] [cursor=pointer]:
            - /url: https://wa.me/6281266723815
    - generic [ref=e246]:
      - generic [ref=e247]:
        - paragraph [ref=e248]: Butuh bantuan menemukan produk yang tepat?
        - link "Diskusikan Kebutuhan" [ref=e249] [cursor=pointer]:
          - /url: http://127.0.0.1:8000/kontak
      - generic [ref=e253]:
        - generic [ref=e254]:
          - generic [ref=e255]: © 2026 Fandy Alfarizi Syafril.
          - generic [ref=e256]: PT. Syirkah Mandiri Artomoro
        - navigation "Informasi legal" [ref=e257]:
          - link "Kebijakan Privasi" [ref=e258] [cursor=pointer]:
            - /url: http://127.0.0.1:8000/privasi
```

# Test source

```ts
  13  |     const marquee = page.locator('.home-brand-marquee');
  14  |     await marquee.scrollIntoViewIfNeeded();
  15  |     await page.mouse.move(0, 0);
  16  |     await marquee.locator('img').evaluateAll(images => Promise.all(images.map(image => {
  17  |         image.loading = 'eager';
  18  |         return image.decode();
  19  |     })));
  20  |     return marquee;
  21  | }
  22  | 
  23  | for (const width of [1920, 1440, 768, 390, 320]) {
  24  |     test(`verified logos and seamless geometry at ${width}px`, async ({ page }, testInfo) => {
  25  |         await page.setViewportSize({ width, height: 900 });
  26  |         const marquee = await openMarquee(page);
  27  |         const groups = marquee.locator('.home-brand-group');
  28  |         await expect(groups).toHaveCount(2);
  29  |         const original = groups.first();
  30  |         const names = expectedLogos();
  31  |         await expect(original.locator('img')).toHaveCount(names.length);
  32  |         expect(await original.locator('img').evaluateAll(images => images.map(image => image.alt)))
  33  |             .toEqual(names);
  34  |         await expect(groups.last()).toHaveAttribute('aria-hidden', 'true');
  35  |         await expect(groups.last().locator('a:not([tabindex="-1"])')).toHaveCount(0);
  36  |         await expect(groups.last().locator('img:not([alt=""])')).toHaveCount(0);
  37  |         await expect(marquee.locator('img').first()).toHaveCSS('object-fit', 'contain');
  38  |         const track = marquee.locator('.home-brand-track');
  39  |         await expect(track).toHaveCSS('animation-duration', '30s');
  40  |         await expect(track).toHaveCSS('animation-timing-function', 'linear');
  41  |         await track.evaluate(element => {
  42  |             const animation = element.getAnimations()[0];
  43  |             animation.pause();
  44  |             animation.currentTime = 0;
  45  |         });
  46  |         const start = await original.boundingBox();
  47  |         const duplicate = await groups.last().boundingBox();
  48  |         const viewport = await marquee.boundingBox();
  49  |         expect(start.width).toBeCloseTo(duplicate.width, 1);
  50  |         expect(start.width).toBeGreaterThanOrEqual(viewport.width);
  51  |         expect(start.x + start.width).toBeCloseTo(duplicate.x, 1);
  52  |         await track.evaluate(element => element.getAnimations()[0].currentTime = 29999);
  53  |         expect((await groups.last().boundingBox()).x).toBeCloseTo(start.x, 0);
  54  |         await track.evaluate(element => element.getAnimations()[0].currentTime = 30000);
  55  |         expect((await original.boundingBox()).x).toBeCloseTo(start.x, 0);
  56  |         expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth + 1)).toBe(true);
  57  |         await marquee.locator('..').screenshot({ path: testInfo.outputPath(`brands-${width}.png`) });
  58  |     });
  59  | }
  60  | 
  61  | test('autoplay moves left, hover pauses and visible duplicate links navigate normally', async ({ page }) => {
  62  |     const marquee = await openMarquee(page);
  63  |     const track = marquee.locator('.home-brand-track');
  64  |     const before = await track.boundingBox();
  65  |     await page.waitForTimeout(200);
  66  |     expect((await track.boundingBox()).x).toBeLessThan(before.x);
  67  |     await marquee.hover();
  68  |     await expect(track).toHaveCSS('animation-play-state', 'paused');
  69  |     const paused = await track.boundingBox();
  70  |     await page.waitForTimeout(150);
  71  |     expect((await track.boundingBox()).x).toBeCloseTo(paused.x, 1);
  72  |     await track.evaluate(element => element.getAnimations()[0].currentTime = 27000);
  73  |     const link = marquee.locator('[aria-hidden="true"] a').first();
  74  |     const href = await link.getAttribute('href');
  75  |     await link.click();
  76  |     await expect(page).toHaveURL(href);
  77  |     await expect(page.locator('.home-brand-marquee')).toHaveCount(0);
  78  | });
  79  | 
  80  | test('keyboard users can reach every original logo without moving or duplicate focus targets', async ({ page }) => {
  81  |     await page.setViewportSize({ width: 390, height: 844 });
  82  |     const marquee = await openMarquee(page);
  83  |     const links = marquee.locator('.home-brand-group').first().locator('a');
  84  |     await page.keyboard.press('Tab');
  85  |     for (const link of await links.all()) {
  86  |         await link.focus();
  87  |         await expect(link).toBeFocused();
  88  |         await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'none');
  89  |         await expect(marquee.locator('[aria-hidden="true"]')).toBeHidden();
  90  |         await expect.poll(async () => {
  91  |             const item = await link.boundingBox();
  92  |             const view = await marquee.boundingBox();
  93  |             return item.x >= view.x - 1 && item.x + item.width <= view.x + view.width + 1;
  94  |         }).toBe(true);
  95  |     }
  96  |     await page.keyboard.press('Enter');
  97  |     await expect(page).toHaveURL(/\/brand\/tecbell$/);
  98  | });
  99  | 
  100 | test('reduced motion shows one scrollable list and responds to preference changes', async ({ page }) => {
  101 |     await page.emulateMedia({ reducedMotion: 'reduce' });
  102 |     await page.setViewportSize({ width: 320, height: 740 });
  103 |     const marquee = await openMarquee(page);
  104 |     await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'none');
  105 |     await expect(marquee.locator('[aria-hidden="true"]')).toBeHidden();
  106 |     await marquee.evaluate(element => element.scrollLeft = element.scrollWidth);
  107 |     expect(await marquee.evaluate(element => element.scrollLeft)).toBeGreaterThan(0);
  108 |     await page.emulateMedia({ reducedMotion: 'no-preference' });
  109 |     await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'home-brand-scroll');
  110 |     await expect.poll(() => marquee.evaluate(element => element.scrollLeft)).toBe(0);
  111 | });
  112 | 
> 113 | test('small CMS lists still cover the viewport without a gap at the loop', async ({ page }) => {
      |                         ^ Error: expect(received).toBeGreaterThanOrEqual(expected)
  114 |     await page.setViewportSize({ width: 1920, height: 900 });
  115 |     const marquee = await openMarquee(page);
  116 |     await marquee.locator('.home-brand-group').evaluateAll(groups => groups.forEach(group => {
  117 |         [...group.children].slice(2).forEach(item => item.remove());
  118 |     }));
  119 |     await marquee.locator('.home-brand-track').evaluate(element => element.getAnimations()[0].pause());
  120 |     const group = await marquee.locator('.home-brand-group').first().boundingBox();
  121 |     const duplicate = await marquee.locator('.home-brand-group').last().boundingBox();
  122 |     expect(group.width).toBeGreaterThanOrEqual((await marquee.boundingBox()).width);
  123 |     expect(group.x + group.width).toBeCloseTo(duplicate.x, 1);
  124 | });
  125 | 
  126 | test('marquee has no JavaScript dependency', async ({ browser, baseURL }) => {
  127 |     const context = await browser.newContext({ javaScriptEnabled: false, baseURL });
  128 |     try {
  129 |         const page = await context.newPage();
  130 |         const marquee = await openMarquee(page);
  131 |         await expect(marquee.locator('.home-brand-track')).toHaveCSS('animation-name', 'home-brand-scroll');
  132 |         await marquee.hover();
  133 |         await marquee.locator('a').first().click();
  134 |         await expect(page).toHaveURL(/\/brand\/wolong$/);
  135 |     } finally { await context.close(); }
  136 | });
  137 | 
```