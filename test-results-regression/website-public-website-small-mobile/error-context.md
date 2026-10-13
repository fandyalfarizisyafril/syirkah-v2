# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: website.spec.js >> public website: small-mobile
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
+   "http://127.0.0.1:8000/images/category-reveals/hose-pump.webp",
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