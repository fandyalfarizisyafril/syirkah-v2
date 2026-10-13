# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: website.spec.js >> public website: tablet
- Location: tests\Browser\website.spec.js:11:5

# Error details

```
Error: Images at /

expect(received).toEqual(expected) // deep equality

- Expected  -  1
+ Received  + 24

- Array []
+ Array [
+   "",
+   "",
+   "",
+   "http://127.0.0.1:8000/images/products/demo-induction-motor.png",
+   "http://127.0.0.1:8000/images/category-reveals/industrial-hose.webp",
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