# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: website.spec.js >> public website: desktop
- Location: tests\Browser\website.spec.js:11:5

# Error details

```
Error: Images at /

expect(received).toEqual(expected) // deep equality

- Expected  -  1
+ Received  + 23

- Array []
+ Array [
+   "",
+   "",
+   "",
+   "http://127.0.0.1:8000/images/products/demo-induction-motor.png",
+   "http://127.0.0.1:8000/images/category-reveals/air-compressor.webp",
+   "http://127.0.0.1:8000/images/category-reveals/oil-spill-response-prevention.webp",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/wolong.svg",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/oli.png",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/qdos.svg",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/bredel.svg",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/aflex.svg",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/tecbell.png",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/blu-c.svg",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/wolong.svg",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/oli.png",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/qdos.svg",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/bredel.svg",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/aflex.svg",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/tecbell.png",
+   "http://127.0.0.1:8000/storage/catalog/brand-logos/blu-c.svg",
+   "http://127.0.0.1:8000/images/company-logo-footer.png",
+ ]
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
  1  | import { test, expect } from '@playwright/test';
  2  | 
  3  | const viewports = [
  4  |     { name: 'desktop', width: 1440, height: 1000 },
  5  |     { name: 'tablet', width: 768, height: 1024 },
  6  |     { name: 'mobile', width: 390, height: 844 },
  7  |     { name: 'small-mobile', width: 360, height: 740 },
  8  | ];
  9  | 
  10 | for (const viewport of viewports) {
  11 |     test(`public website: ${viewport.name}`, async ({ page }, testInfo) => {
  12 |         await page.setViewportSize(viewport);
  13 |         const errors = [];
  14 |         page.on('pageerror', error => errors.push(error.message));
  15 |         for (const path of ['/', '/tentang-kami', '/produk', '/produk/electric-motors-generators', '/brand', '/brand/wolong', '/industri', '/kontak', '/search?q=pump', '/privasi']) {
  16 |             const response = await page.goto(path);
  17 |             expect(response.status()).toBe(200);
  18 |             await expect(page.locator('h1')).toHaveCount(1);
  19 |             const layout = await page.evaluate(() => ({
  20 |                 overflow: document.documentElement.scrollWidth > window.innerWidth + 1,
  21 |                 brokenImages: Array.from(document.images).filter(image => !image.complete || image.naturalWidth === 0).map(image => image.src),
  22 |             }));
  23 |             expect(layout.overflow, `Overflow at ${path}`).toBe(false);
> 24 |             expect(layout.brokenImages, `Images at ${path}`).toEqual([]);
     |                                                              ^ Error: Images at /
  25 |             if (path === '/' || path === '/kontak' || path === '/produk') {
  26 |                 await page.screenshot({ path: testInfo.outputPath(`${path === '/' ? 'home' : path.slice(1)}-${viewport.name}.png`), fullPage: true });
  27 |             }
  28 |         }
  29 |         expect(errors).toEqual([]);
  30 |         await page.goto('/');
  31 |         await expect(page.locator('.hero-image.is-active')).toBeVisible();
  32 |         if (viewport.width < 901) {
  33 |             const toggle = page.getByRole('button', { name: 'Buka navigasi' });
  34 |             await toggle.click();
  35 |             await expect(page.locator('#primary-nav')).toBeVisible();
  36 |             await page.keyboard.press('Escape');
  37 |             await expect(page.locator('#primary-nav')).toBeHidden();
  38 |             await expect(toggle).toBeFocused();
  39 |             await toggle.click();
  40 |             await page.locator('#primary-nav').getByRole('link', { name: 'Produk', exact: true }).click();
  41 |             await expect(page).toHaveURL(/\/produk$/);
  42 |         }
  43 |     });
  44 | }
  45 | 
  46 | test('inquiry form has working native validation and privacy link', async ({ page }) => {
  47 |     await page.goto('/kontak');
  48 |     await page.getByRole('button', { name: 'Kirim Inquiry' }).click();
  49 |     await expect(page.locator('#name')).toBeFocused();
  50 |     await expect(page).toHaveURL(/\/kontak$/);
  51 |     await page.getByRole('link', { name: 'Kebijakan Privasi', exact: true }).first().click();
  52 |     await expect(page.getByRole('heading', { name: 'Kebijakan Privasi', exact: true })).toBeVisible();
  53 | });
  54 | 
  55 | test('admin login, catalog forms, responsive dashboard, and logout', async ({ page }, testInfo) => {
  56 |     test.skip(!process.env.CMS_TEST_EMAIL || !process.env.CMS_TEST_PASSWORD, 'Set CMS_TEST_EMAIL and CMS_TEST_PASSWORD for an existing local administrator.');
  57 |     await page.goto('/admin');
  58 |     await page.getByRole('textbox', { name: 'Email', exact: true }).fill(process.env.CMS_TEST_EMAIL);
  59 |     await page.getByLabel(/^Password/).fill(process.env.CMS_TEST_PASSWORD);
  60 |     await page.getByRole('button', { name: 'Masuk', exact: true }).click();
  61 |     await expect(page.getByRole('heading', { name: 'Dashboard', exact: true })).toBeVisible();
  62 |     for (const viewport of viewports.filter(item => item.name !== 'small-mobile')) {
  63 |         await page.setViewportSize(viewport);
  64 |         for (const path of ['/admin', '/admin/catalog/products', '/admin/catalog/products/create', '/admin/catalog/categories', '/admin/catalog/brands', '/admin/catalog/industries', '/admin/settings', '/admin/inquiries', '/admin/account']) {
  65 |             const response = await page.goto(path);
  66 |             expect(response.status()).toBe(200);
  67 |             expect(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth + 1), `Admin overflow: ${path}`).toBe(true);
  68 |             if (path === '/admin' || path === '/admin/catalog/products/create') {
  69 |                 await page.screenshot({ path: testInfo.outputPath(`${path === '/admin' ? 'dashboard' : 'product-form'}-${viewport.name}.png`), fullPage: true });
  70 |             }
  71 |         }
  72 |     }
  73 |     await page.goto('/admin/catalog/products/create');
  74 |     await page.getByRole('textbox', { name: 'Nama', exact: true }).fill('Test Motor Browser');
  75 |     await expect(page.getByLabel('Slug URL')).toHaveValue('test-motor-browser');
  76 |     await page.getByRole('button', { name: 'Tambah Spesifikasi' }).click();
  77 |     await expect(page.locator('.spec-row')).toHaveCount(2);
  78 |     await page.getByRole('button', { name: 'Hapus spesifikasi' }).last().click();
  79 |     await expect(page.locator('.spec-row')).toHaveCount(1);
  80 |     await page.getByRole('button', { name: 'Keluar', exact: true }).click();
  81 |     await expect(page).toHaveURL(/\/admin\/login$/);
  82 | });
  83 | 
```