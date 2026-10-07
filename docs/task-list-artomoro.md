# Task List - Website PT. Syirkah Mandiri Artomoro

**Sumber:** `PRD_PT_Syirkah_Mandiri_Artomoro.md` dan `design-artomoro.md`  
**Target implementasi:** Laravel monolith  
**Status:** Implementasi lokal berjalan; QA dan persiapan launch bertahap  
**Tanggal:** 2026-09-28

Dokumen ini berisi daftar pekerjaan implementasi website company profile, katalog produk, admin CMS, dan inquiry untuk PT. Syirkah Mandiri Artomoro. Task disusun mengikuti fase implementasi pada design specification agar dapat digunakan sebagai backlog development.

## Catatan Eksekusi 2026-09-29

- Implementasi lokal: website publik, CMS, authentication/role, katalog, upload gambar/PDF, inquiry, pengaturan perusahaan, dan SEO dasar tersedia.
- Petunjuk menjalankan dan batasan implementasi: [implementation-artomoro.md](implementation-artomoro.md).
- Kontak mengikuti PRD. Asumsi desain dipakai untuk implementasi, tetapi belum dianggap sebagai konfirmasi owner.
- Kategori dan brand awal tersedia. Produk dan industri awal masih draft sampai data resmi dan persetujuan publikasi tersedia.
- Notifikasi email lokal memakai mailer `log`; pengiriman SMTP production belum diuji.
- Verifikasi: 20 test backend (192 assertions), 6 skenario Playwright, build Vite, kompilasi Blade, dan route cache berhasil. Browser diuji pada lebar 1440, 768, 390, dan 360 px.
- Checklist selesai menunjukkan implementasi lokal, bukan deployment production. Konten final, approval owner, dan pekerjaan post-launch tetap terbuka.

## Legend

- `[ ]` Belum dikerjakan.
- `[~]` Sedang dikerjakan.
- `[x]` Selesai.
- **P0** Wajib untuk versi pertama.
- **P1** Penting, tetapi dapat disesuaikan jika waktu terbatas.
- **P2** Tambahan setelah versi pertama stabil.

## 0. Klarifikasi Awal Owner

Task ini perlu dikonfirmasi seawal mungkin karena memengaruhi konten, konfigurasi, dan scope rilis.

- [ ] **P0** Konfirmasi bahwa website tidak memiliki cart, checkout, pembayaran, atau harga publik.
- [ ] **P0** Konfirmasi bahasa awal website: Bahasa Indonesia saja.
- [ ] **P0** Konfirmasi apakah admin CMS wajib tersedia pada versi pertama.
- [ ] **P0** Konfirmasi email penerima inquiry utama.
- [ ] **P0** Konfirmasi nomor WhatsApp yang digunakan untuk CTA.
- [ ] **P0** Konfirmasi informasi legal yang boleh ditampilkan publik.
- [x] **P0** Kumpulkan logo resmi perusahaan. Logo diterima dan dipasang pada header publik pada 2026-10-05.
- [ ] **P0** Kumpulkan logo brand/principal yang boleh dipublikasikan.
- [ ] **P0** Kumpulkan foto produk atau gambar kategori yang berizin.
- [ ] **P0** Kumpulkan daftar produk/model awal untuk tujuh kategori utama.
- [ ] **P1** Kumpulkan datasheet PDF yang boleh dipublikasikan.
- [ ] **P1** Konfirmasi industri prioritas yang ingin ditampilkan.
- [ ] **P1** Konfirmasi copy final profil perusahaan.
- [ ] **P1** Konfirmasi akses domain, hosting, dan email SMTP mendekati deployment.

## 1. Foundation

### 1.1 Project Setup

- [x] **P0** Review konfigurasi Laravel yang sudah ada pada project.
- [x] **P0** Pastikan `.env` lokal memiliki konfigurasi database, mail, app URL, dan storage yang benar.
- [x] **P0** Jalankan migration bawaan dan pastikan koneksi database berhasil.
- [x] **P0** Pastikan Vite/build asset berjalan untuk CSS dan JavaScript.
- [x] **P0** Tambahkan struktur folder Blade untuk layout publik dan admin.
- [x] **P0** Buat base layout publik dengan slot metadata, content, dan script.
- [x] **P0** Buat base layout admin dengan navigasi, konten, alert, dan state aktif.
- [ ] **P1** Tambahkan helper format umum, misalnya nomor WhatsApp, excerpt, dan status label.

### 1.2 Design System Dasar

- [x] **P0** Definisikan token warna awal sesuai design specification.
- [x] **P0** Definisikan skala tipografi, spacing, container, dan breakpoint.
- [x] **P0** Buat komponen dasar: button, input, textarea, select, card, badge, table, alert.
- [x] **P0** Buat header publik dengan utility bar, logo, navigasi, search icon, dan CTA inquiry.
- [x] **P0** Buat mobile navigation yang dapat dibuka dan ditutup.
- [x] **P0** Buat footer publik berisi kontak, navigasi, kategori, brand, legal, dan copyright. Link legal tampil setelah konten legal disetujui dan diisi.
- [x] **P1** Tambahkan state hover, focus, disabled, loading, dan error untuk komponen form.
- [x] **P1** Tambahkan placeholder image untuk produk, kategori, dan brand tanpa gambar.

### 1.3 Authentication dan Authorization Admin

- [x] **P0** Siapkan authentication admin.
- [x] **P0** Buat model/role awal: Administrator, Editor, Sales.
- [x] **P0** Lindungi route admin dengan middleware auth.
- [x] **P0** Tambahkan rate limiting untuk login admin.
- [x] **P0** Buat dashboard admin awal.
- [x] **P1** Tambahkan policy/permission untuk modul produk, brand, industri, inquiry, dan settings.
- [ ] **P1** Tambahkan audit sederhana untuk perubahan penting admin.

## 2. Data Model dan Database

### 2.1 Master Content

- [x] **P0** Buat migration, model, factory, dan seeder untuk `categories`.
- [x] **P0** Buat migration, model, factory, dan seeder untuk `brands`.
- [x] **P0** Buat migration, model, factory, dan seeder untuk `products`.
- [x] **P0** Buat struktur fleksibel untuk product specifications.
- [x] **P0** Buat migration, model, factory, dan seeder untuk `industries`.
- [x] **P0** Buat relasi produk dengan kategori dan brand.
- [x] **P0** Buat relasi produk dengan industri.
- [x] **P1** Tambahkan field SEO pada kategori, brand, produk, dan industri.
- [x] **P1** Tambahkan field sort order dan status draft/published pada master content.

### 2.2 Inquiry dan Settings

- [x] **P0** Buat migration dan model untuk `inquiries`.
- [x] **P0** Tambahkan nomor referensi inquiry yang unik.
- [x] **P0** Tambahkan status inquiry: new, contacted, qualified, closed, spam.
- [x] **P0** Buat migration dan model untuk global settings.
- [x] **P0** Simpan informasi perusahaan: nama, tagline, alamat, telepon, email, WhatsApp, map URL.
- [x] **P0** Simpan legal information yang dapat dikelola dari admin.
- [x] **P1** Tambahkan default SEO settings.

### 2.3 Media dan Datasheet

- [x] **P0** Siapkan public storage link.
- [x] **P0** Buat mekanisme upload gambar untuk produk, kategori, brand, dan industri.
- [x] **P0** Validasi tipe dan ukuran file gambar.
- [x] **P0** Buat mekanisme upload datasheet PDF untuk produk.
- [x] **P0** Validasi tipe dan ukuran file PDF.
- [x] **P1** Tambahkan alt text untuk gambar informatif.
- [ ] **P1** Tambahkan thumbnail atau image variant jika pipeline tersedia.
- [x] **P1** Pastikan file lama tidak langsung dihapus jika masih digunakan konten lain.

## 3. Admin CMS

### 3.1 Dashboard dan Navigasi

- [x] **P0** Buat dashboard ringkas berisi jumlah produk, brand, inquiry baru, dan konten draft.
- [x] **P0** Buat navigasi admin: Dashboard, Company Profile, Categories, Products, Brands, Industries, Inquiries, Legal, Settings.
- [x] **P0** Tambahkan flash message untuk create, update, delete, dan error.
- [x] **P1** Tambahkan empty state untuk setiap modul admin.

### 3.2 Category Management

- [x] **P0** Buat halaman daftar kategori.
- [x] **P0** Buat form tambah kategori.
- [x] **P0** Buat form edit kategori.
- [x] **P0** Tambahkan validasi name, slug, description, image, sort order, dan status.
- [x] **P0** Tambahkan fitur publish/unpublish kategori.
- [x] **P1** Tambahkan pengecekan sebelum menghapus kategori yang masih memiliki produk.

### 3.3 Brand Management

- [x] **P0** Buat halaman daftar brand/principal.
- [x] **P0** Buat form tambah brand.
- [x] **P0** Buat form edit brand.
- [x] **P0** Tambahkan validasi name, slug, logo, focus, description, website URL, sort order, dan status.
- [x] **P0** Tambahkan fitur publish/unpublish brand.
- [x] **P1** Tambahkan pengecekan sebelum menghapus brand yang masih memiliki produk.

### 3.4 Product Management

- [x] **P0** Buat halaman daftar produk dengan filter kategori, brand, dan status.
- [x] **P0** Buat form tambah produk.
- [x] **P0** Buat form edit produk.
- [x] **P0** Tambahkan field kategori, brand, name, slug, model/series, short description, description.
- [x] **P0** Tambahkan field benefits dan applications.
- [x] **P0** Tambahkan field spesifikasi fleksibel berbasis label dan value.
- [x] **P0** Tambahkan upload primary image, gallery, dan datasheet.
- [x] **P0** Tambahkan relasi produk ke industri.
- [x] **P0** Tambahkan status draft/published dan featured.
- [x] **P0** Tambahkan validasi server untuk seluruh field penting.
- [ ] **P1** Tambahkan preview produk dari admin.
- [ ] **P1** Tambahkan bulk publish/unpublish jika produk awal cukup banyak.

### 3.5 Industry Management

- [x] **P0** Buat halaman daftar industri.
- [x] **P0** Buat form tambah industri.
- [x] **P0** Buat form edit industri.
- [x] **P0** Tambahkan field name, slug, image, summary, challenges, solution copy, dan status.
- [x] **P0** Tambahkan relasi industri ke produk relevan.
- [x] **P1** Tambahkan urutan tampil industri.

### 3.6 Inquiry Management

- [x] **P0** Buat halaman daftar inquiry.
- [x] **P0** Buat halaman detail inquiry.
- [x] **P0** Tampilkan reference number, kontak, produk, pesan, source page, dan consent time.
- [x] **P0** Tambahkan update status inquiry.
- [x] **P0** Tambahkan internal notes.
- [x] **P0** Pastikan inquiry tidak dapat diakses route publik.
- [x] **P1** Tambahkan filter status dan pencarian inquiry.
- [x] **P1** Tambahkan indikator inquiry baru di dashboard.

### 3.7 Company, Legal, dan Global Settings

- [x] **P0** Buat halaman pengaturan company profile.
- [x] **P0** Buat halaman pengaturan kontak perusahaan.
- [x] **P0** Buat halaman pengaturan legal information.
- [x] **P0** Buat halaman pengaturan SEO default.
- [x] **P0** Validasi email, telepon, URL, dan konten legal.
- [ ] **P1** Tambahkan pengaturan social links jika diperlukan.

## 4. Public Website

### 4.1 Routing dan Controller Publik

- [x] **P0** Definisikan public route: `/`, `/tentang-kami`, `/produk`, `/produk/{category}`, `/produk/{category}/{product}`, `/brand`, `/brand/{brand}`, `/industri`, `/industri/{industry}`, `/kontak`, `/search`.
- [x] **P0** Pastikan hanya konten published yang tampil di publik.
- [x] **P0** Tambahkan 404 yang informatif untuk konten tidak ditemukan.
- [ ] **P1** Tambahkan redirect saat slug berubah jika slug history disimpan.

### 4.2 Homepage

- [x] **P0** Revisi 2026-10-08: hero data-driven lima slide, H1 dan deskripsi mengikuti Bidang Fokus aktif, dengan identitas visual SMART.
- [x] **P0** Sesuai revisi, hapus CTA di dalam hero; Request Inquiry pada navbar tetap.
- [x] **P0** Integrasikan Engineering, Mechanical, Electrical, Instrumentation, Oil Spill Response & Prevention sebagai navigasi carousel di dalam hero; hapus bar putih lama.
- [x] **P0** Tampilkan tujuh featured product categories.
- [x] **P0** Tampilkan company positioning singkat.
- [x] **P0** Tampilkan brand/principal.
- [x] **P0** Tampilkan industries/applications.
- [x] **P0** Tampilkan value proposition: Reliability, Efficiency, Operational Continuity, Safety, Environmental Protection.
- [x] **P0** Tampilkan inquiry banner dengan CTA inquiry dan WhatsApp.
- [x] **P1** Pastikan hero menggunakan gambar industrial resmi atau placeholder yang layak sampai aset final tersedia.

### 4.3 About Us

- [x] **P0** Buat halaman About Us.
- [x] **P0** Tampilkan company overview dan latar belakang.
- [x] **P0** Tampilkan posisi sebagai General Supplier & Technical Solutions.
- [x] **P0** Tampilkan lima bidang fokus.
- [x] **P0** Tampilkan nilai perusahaan.
- [ ] **P0** Tampilkan legal information yang disetujui owner.
- [x] **P0** Tambahkan CTA menuju Contact atau Inquiry.

### 4.4 Products & Solutions

- [x] **P0** Buat halaman archive produk.
- [x] **P0** Tampilkan kategori, brand, produk, dan ringkasan produk.
- [x] **P0** Tambahkan search berdasarkan nama, brand, kategori, atau keyword.
- [x] **P0** Tambahkan filter kategori.
- [x] **P0** Tambahkan filter brand.
- [x] **P0** Tambahkan pagination.
- [x] **P0** Tambahkan empty state ketika tidak ada hasil.
- [x] **P0** Buat halaman kategori produk.
- [x] **P0** Tampilkan deskripsi kategori, brand terkait, produk, industri/aplikasi terkait, dan CTA konsultasi.

### 4.5 Product Detail

- [x] **P0** Buat halaman detail produk.
- [x] **P0** Tampilkan breadcrumb.
- [x] **P0** Tampilkan brand dan kategori.
- [x] **P0** Tampilkan nama produk atau seri.
- [x] **P0** Tampilkan galeri gambar.
- [x] **P0** Tampilkan ringkasan, deskripsi, benefits, dan applications.
- [x] **P0** Tampilkan technical specifications yang fleksibel.
- [x] **P0** Tampilkan link datasheet jika tersedia.
- [x] **P0** Tampilkan produk terkait.
- [x] **P0** Tambahkan CTA Request Inquiry dengan konteks produk.
- [x] **P1** Tampilkan state datasheet unavailable secara halus.

### 4.6 Brands & Principals

- [x] **P0** Buat halaman archive brand.
- [x] **P0** Tampilkan logo, nama, fokus, dan link detail brand.
- [x] **P0** Buat halaman detail brand.
- [x] **P0** Tampilkan deskripsi principal, fokus teknologi, kategori terkait, produk terkait, dan CTA inquiry.
- [x] **P1** Tambahkan link website principal jika disetujui owner.

### 4.7 Industries & Applications

- [x] **P0** Buat halaman archive industri.
- [x] **P0** Buat halaman detail industri.
- [x] **P0** Tampilkan kebutuhan umum, nilai solusi perusahaan, produk relevan, dan CTA konsultasi.
- [ ] **P1** Urutkan industri sesuai prioritas owner.

### 4.8 Contact dan Inquiry Publik

- [x] **P0** Buat halaman contact.
- [x] **P0** Tampilkan alamat, phone, email, dan link peta jika tersedia.
- [x] **P0** Buat form inquiry publik.
- [x] **P0** Form memuat nama lengkap, perusahaan, email, telepon/WhatsApp, produk, kebutuhan/aplikasi, pesan, dan persetujuan pemrosesan data.
- [x] **P0** Jika inquiry berasal dari product detail, produk otomatis terpilih.
- [x] **P0** Validasi seluruh input di server.
- [x] **P0** Simpan inquiry ke database sebelum mengirim email.
- [x] **P0** Kirim notifikasi email admin berisi detail inquiry.
- [x] **P0** Tampilkan halaman atau pesan sukses berisi reference number.
- [x] **P0** Jika email gagal, inquiry tetap tersimpan dan user tetap mendapat pesan yang sesuai.
- [ ] **P1** Tambahkan alternatif CTA WhatsApp setelah submit berhasil.

### 4.9 Search

- [x] **P1** Buat halaman hasil search global.
- [x] **P1** Search minimal mencakup produk, kategori, brand, dan industri.
- [x] **P1** Tambahkan empty state dan saran reset pencarian.
- [ ] **P2** Tambahkan tracking event pencarian setelah analytics disetujui.

## 5. Inquiry, Email, dan Spam Protection

- [x] **P0** Tambahkan CSRF protection pada form.
- [x] **P0** Tambahkan honeypot field pada inquiry.
- [x] **P0** Tambahkan rate limiting untuk submit inquiry.
- [x] **P0** Sanitasi input inquiry.
- [x] **P0** Batasi panjang field pesan dan aplikasi.
- [x] **P0** Pastikan product_id yang dikirim valid dan published.
- [x] **P0** Buat template email notifikasi admin.
- [x] **P0** Tambahkan logging untuk kegagalan email.
- [ ] **P1** Buat email acknowledgement ke pengunjung jika disetujui owner.
- [ ] **P2** Tambahkan CAPTCHA hanya jika spam menjadi masalah nyata.

## 6. SEO, Metadata, dan Analytics

### 6.1 SEO Dasar

- [x] **P0** Set satu H1 per halaman.
- [x] **P0** Tambahkan title dan meta description untuk halaman utama.
- [x] **P0** Tambahkan metadata dinamis untuk produk, kategori, brand, dan industri.
- [x] **P0** Tambahkan canonical URL.
- [x] **P0** Tambahkan Open Graph metadata dasar.
- [x] **P0** Tambahkan breadcrumb pada kategori, produk, brand, dan industri.
- [x] **P1** Generate XML sitemap.
- [x] **P1** Tambahkan robots configuration.
- [x] **P1** Tambahkan structured data Organization.
- [ ] **P1** Tambahkan structured data Product hanya jika data produk memenuhi persyaratan.

### 6.2 Analytics

- [ ] **P1** Konfirmasi platform analytics dan kebijakan consent.
- [ ] **P1** Tambahkan event click WhatsApp, phone, email, dan Request Inquiry.
- [ ] **P1** Tambahkan event view product.
- [ ] **P1** Tambahkan event search product dan apply filter.
- [ ] **P1** Tambahkan event download datasheet.
- [ ] **P1** Tambahkan event submit inquiry success/failure.

## 7. Accessibility dan Responsive QA

- [x] **P0** Pastikan seluruh navigasi dapat digunakan dengan keyboard.
- [x] **P0** Pastikan focus state terlihat jelas.
- [x] **P0** Pastikan mobile menu mengelola focus dengan benar.
- [x] **P0** Pastikan form memiliki label yang terhubung dengan field.
- [x] **P0** Pastikan error form dijelaskan dengan teks, bukan warna saja.
- [x] **P0** Pastikan pesan sukses/error dapat dibaca assistive technology.
- [x] **P0** Pastikan semua gambar informatif memiliki alt text.
- [ ] **P0** Pastikan kontras teks dan CTA memenuhi target WCAG 2.2 AA.
- [x] **P0** Pastikan product grid responsif di mobile, tablet, dan desktop.
- [x] **P0** Pastikan specification table terbaca di mobile.
- [x] **P0** Pastikan filter produk nyaman digunakan di mobile.
- [x] **P1** Hormati `prefers-reduced-motion`.
- [ ] **P1** Uji halaman utama dengan screen reader dasar jika memungkinkan.

## 8. Performance dan Security

### 8.1 Performance

- [x] **P0** Hero lima bidang fokus: autoplay 5000 ms, fade 900 ms, reset timer saat klik manual, keyboard, reduced motion, dan navigasi mobile horizontal. Prioritaskan gambar pertama; preload hanya slide berikutnya. Tanpa dependency carousel tambahan.
- [x] **P0** Lazy-load gambar di bawah fold.
- [x] **P0** Tetapkan width/height atau aspect ratio gambar untuk mencegah layout shift.
- [x] **P0** Gunakan pagination dan eager loading pada daftar produk.
- [x] **P0** Pastikan CSS dan JavaScript build production ter-minify.
- [ ] **P1** Tambahkan cache untuk halaman publik setelah konten stabil.
- [ ] **P1** Hapus cache saat konten terkait diperbarui.
- [ ] **P1** Uji Core Web Vitals awal: LCP, CLS, INP.

### 8.2 Security dan Privacy

- [x] **P0** Pastikan output user-generated content di-escape.
- [x] **P0** Pastikan authorization admin dicek di server.
- [x] **P0** Pastikan route admin tidak mengekspos data tanpa login.
- [x] **P0** Validasi upload berdasarkan tipe, ukuran, dan ekstensi.
- [x] **P0** Pastikan file publik tidak mengandung data sensitif pada nama file.
- [x] **P0** Buat halaman privacy policy sederhana untuk data inquiry.
- [x] **P0** Jangan publikasikan scan dokumen legal tanpa persetujuan eksplisit owner.
- [ ] **P1** Jadwalkan backup database dan media.
- [ ] **P1** Review dependency yang sudah usang atau memiliki vulnerability.

## 9. Testing

### 9.1 Automated Tests

- [x] **P0** Test public homepage dapat diakses.
- [x] **P0** Test product archive hanya menampilkan produk published.
- [x] **P0** Test product detail menampilkan spesifikasi fleksibel.
- [x] **P0** Test filter produk berdasarkan kategori dan brand.
- [x] **P0** Test inquiry valid tersimpan ke database.
- [x] **P0** Test inquiry dari product detail membawa konteks produk.
- [x] **P0** Test inquiry tetap tersimpan saat email notification gagal.
- [x] **P0** Test validasi inquiry untuk field wajib.
- [x] **P0** Test admin route membutuhkan login.
- [x] **P0** Test admin dapat CRUD kategori, brand, produk, dan industri.
- [x] **P1** Test upload gambar dan datasheet.
- [x] **P1** Test update status inquiry dan internal notes.
- [x] **P1** Test SEO metadata pada halaman publik.

### 9.2 Manual QA

- [x] **P0** QA homepage desktop, tablet, dan mobile. Screenshot, gambar, overflow, navigasi, dan menu keyboard diperiksa pada data awal.
- [~] **P0** QA About Us desktop, tablet, dan mobile. Smoke test browser dan overflow lulus; review copy final menunggu owner.
- [~] **P0** QA product archive dan product detail desktop, tablet, dan mobile. Archive diperiksa di browser; detail/spesifikasi diuji dengan feature test. Visual produk nyata menunggu aset resmi.
- [~] **P0** QA brand archive/detail dan industry archive/detail. Route dan data diuji; review visual dengan logo dan konten industri final menyusul.
- [x] **P0** QA contact dan inquiry form. Screenshot responsif, validasi browser, penyimpanan, konteks produk, dan kegagalan email diuji.
- [x] **P0** QA admin CMS modul utama. Login/logout, layout responsif, form, relasi, upload, CRUD, dan hak akses diuji.
- [~] **P0** QA state error, empty, loading, dan success. Empty state, validasi, dan success backend diuji; review seluruh state dengan konten final menyusul.
- [~] **P0** QA link phone, email, WhatsApp, datasheet, dan map. Struktur link dan upload diuji; validasi nomor WhatsApp, peta, serta PDF resmi menunggu data perusahaan.
- [ ] **P1** QA konten final bersama owner sebelum launch.

## 10. Content Migration

- [x] **P0** Input tujuh kategori produk awal.
- [x] **P0** Input brand/principal awal: OLI, Wolong, Qdos, Bredel, Aflex, Tecbell, BLU-C.
- [ ] **P0** Input produk awal berdasarkan data yang disetujui.
- [ ] **P0** Upload gambar kategori, brand, dan produk.
- [ ] **P1** Upload datasheet PDF yang disetujui.
- [ ] **P0** Input company profile final.
- [ ] **P0** Input kontak final.
- [ ] **P0** Input legal information yang boleh ditampilkan.
- [ ] **P1** Input industri prioritas beserta produk relevan.
- [ ] **P0** Review seluruh copy agar konsisten dan tidak memuat klaim yang belum disetujui.

## 11. Deployment dan Launch

- [ ] **P0** Siapkan environment production.
- [ ] **P0** Konfigurasi database production.
- [ ] **P0** Konfigurasi mail production.
- [ ] **P0** Konfigurasi storage production.
- [ ] **P0** Konfigurasi APP_URL sesuai domain.
- [ ] **P0** Aktifkan HTTPS.
- [ ] **P0** Jalankan migration dan seeder awal di production.
- [ ] **P0** Buat akun administrator awal.
- [ ] **P0** Jalankan build asset production.
- [ ] **P0** Pastikan cache config, route, dan view production berjalan.
- [ ] **P0** Test submit inquiry di production.
- [ ] **P0** Test email notification di production.
- [ ] **P0** Test WhatsApp, phone, email, sitemap, robots, dan 404.
- [ ] **P0** Minta final approval owner sebelum publikasi.
- [ ] **P1** Setup backup rutin.
- [ ] **P1** Setup monitoring error/log.

## 12. Post-Launch

- [ ] **P1** Pantau error log selama minggu pertama.
- [ ] **P1** Pantau inquiry masuk dan validasi alur tindak lanjut.
- [ ] **P1** Perbaiki typo, gambar, atau data produk berdasarkan feedback owner.
- [ ] **P1** Review performa halaman produk dengan gambar nyata.
- [ ] **P1** Review keyword SEO setelah konten final online.
- [ ] **P2** Pertimbangkan multilingual Bahasa Inggris.
- [ ] **P2** Pertimbangkan project/portfolio.
- [ ] **P2** Pertimbangkan news/articles.
- [ ] **P2** Pertimbangkan product comparison.
- [ ] **P2** Pertimbangkan rekomendasi produk berdasarkan industri.

## Milestone Rilis

### Milestone 1 - Foundation Ready

- Laravel config, database, auth admin, layout, header, footer, dan design token siap.

### Milestone 2 - Catalog CMS Ready

- Admin dapat mengelola kategori, brand, produk, spesifikasi, industri, media, dan datasheet.

### Milestone 3 - Public Website Ready

- Homepage, About Us, Products, Product Detail, Brands, Industries, Contact, dan Inquiry tersedia untuk pengunjung.

### Milestone 4 - Quality Ready

- Testing, responsive QA, accessibility QA, SEO dasar, performance, dan security review selesai.

### Milestone 5 - Launch Ready

- Konten final telah dimigrasikan, production siap, inquiry/email berhasil diuji, dan owner memberi persetujuan publikasi.

## Acceptance Criteria Versi Pertama

- [x] Pengunjung dapat memahami bidang usaha perusahaan dari homepage.
- [x] Tujuh kategori produk dapat dikelola dari admin dan ditampilkan di publik.
- [x] Produk dapat dihubungkan dengan kategori, brand, dan industri.
- [x] Product detail dapat menampilkan spesifikasi berbeda untuk setiap produk.
- [x] Datasheet dapat ditambahkan tanpa mengubah kode.
- [x] Inquiry dari product detail otomatis membawa konteks produk.
- [x] Inquiry tersimpan sebelum email notifikasi dikirim.
- [x] Admin dapat mengelola produk, brand, industri, informasi perusahaan, legal, dan inquiry.
- [x] Website dapat digunakan pada mobile, tablet, dan desktop.
- [x] Navigasi dan form dapat digunakan dengan keyboard.
- [x] Halaman publik memiliki metadata SEO dasar.
- [x] Data pribadi dan admin route tidak terekspos secara publik.
- [x] Website tidak memiliki cart, checkout, pembayaran, atau harga publik.
- [ ] Logo, foto, datasheet, legal information, dan copy publik telah disetujui perusahaan.
