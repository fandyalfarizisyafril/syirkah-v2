# Implementasi Lokal Artomoro

Tanggal: 2026-09-29

## Menjalankan Project

Kebutuhan: PHP 8.2+, Composer, Node.js, MySQL/MariaDB. Dependency PHP menggunakan Laravel 12. Konfigurasi database lokal mengikuti `.env` yang sudah ada.

```powershell
composer install
npm install
php artisan migrate
php artisan db:seed --class=CatalogSeeder
php artisan storage:link
npm run build
php artisan serve --host=127.0.0.1 --port=8000
```

- Website: http://127.0.0.1:8000
- CMS: http://127.0.0.1:8000/admin
- Sitemap: http://127.0.0.1:8000/sitemap.xml
- Akun lokal awal: `admin@artomoro.test`. Password diberikan saat pembuatan akun dan tidak disimpan di dokumentasi.
- Password dapat diganti di `/admin/account`.

Seeder menggunakan `firstOrCreate`: menjalankannya lagi tidak menimpa konten yang sudah diedit. Jangan menggunakan `migrate:fresh` pada database berisi data yang ingin dipertahankan.

## Hak Akses

| Role | Akses |
|---|---|
| administrator | Katalog, inquiry, perusahaan, legal, kontak, SEO |
| editor | Kategori, brand, produk, industri |
| sales | Inquiry dan catatan tindak lanjut |
| none | Tidak memiliki akses CMS |

Semua role CMS dapat memperbarui password sendiri. Akun dibuat melalui CLI, tanpa registrasi publik:

```powershell
php artisan admin:create admin@example.com --name="Administrator"
php artisan admin:create editor@example.com --name="Editor" --role=editor
php artisan admin:create sales@example.com --name="Sales" --role=sales
```

Command menampilkan password acak satu kali dan menolak email yang sudah terdaftar. Tidak mereset akun lama.

## Katalog dan Media

Tujuh kategori dan tujuh brand dari PRD dimasukkan sebagai data awal. Tujuh contoh nama produk serta enam industri disimpan sebagai draft. Lengkapi data resmi lalu ubah status menjadi published di CMS.

Produk publik membutuhkan status published pada produk, kategori, dan brand sekaligus. Aturan ini berlaku pada daftar, detail, pencarian, sitemap, dan pilihan produk di form inquiry. Menghapus kategori atau brand yang masih memiliki produk akan ditolak.

Kartu kategori di beranda dan daftar kategori `/produk` hanya menampilkan nomor, nama, deskripsi, dan link, meskipun kategori memiliki gambar. Gambar kategori dari CMS hanya ditampilkan pada halaman detail `/produk/{category}`, di sebelah kanan judul/deskripsi pada desktop dan tablet, lalu bertumpuk pada mobile (640 px ke bawah). Filter dan daftar produk berada di bawah pengantar tersebut. Kategori tanpa gambar tetap menampilkan judul/deskripsi dan daftar produknya.

`categories.image` adalah media representasi kategori. `products.image` dan `products.gallery` merupakan media setiap produk; produk terkait melalui `products.category_id`. Media kategori tidak menjadi item produk dan tidak menggantikan gambar produk. Jumlah hasil hanya menghitung produk yang lolos filter dan berstatus published (termasuk kategori dan brand-nya). Karena itu, kategori yang memiliki gambar tetapi seluruh produknya masih draft tetap menampilkan `0 produk`. Perubahan penempatan ini tidak memublikasikan atau mengubah data produk.

Spesifikasi menggunakan pasangan parameter/nilai. Relasi industri dan produk dapat diatur dari kedua form. Galeri, gambar utama, dan datasheet PDF dapat diperbarui lewat CMS. Gambar dibatasi JPG/PNG/WebP maksimal 4 MB; PDF maksimal 10 MB. Sesuaikan batas PHP `upload_max_filesize` (minimal 10M) dan `post_max_size` (misalnya 64M) agar mendukung batas aplikasi. File lama tetap disimpan untuk mencegah penghapusan file yang masih dirujuk. Pembersihan file yatim belum dijadwalkan.

### Ilustrasi Media Seluruh Kategori

Seluruh tujuh kategori menggunakan template detail yang sama: pengantar di kiri, media kategori di kanan, lalu filter dan daftar produk di bawahnya. Pada layar 640 px ke bawah, pengantar dan gambar bertumpuk. Gambar Electric Motors & Generators tetap menggunakan unggahan asli.

Enam ilustrasi kategori lainnya dibuat dengan tool image generation bawaan pada 2026-10-06, tanpa logo atau klaim model resmi. Aset sumber berada di `public/images/categories/`. Daftar aset dan prompt lengkap tersedia di `docs/category-image-prompts.md`. Ilustrasi ini bukan foto produk resmi dan perlu persetujuan perusahaan sebelum launch.

Jalankan `php artisan db:seed --class=CategoryMediaSeeder` untuk mengisi media yang kosong pada enam kategori yang sudah ada. Seeder menyalin aset ke public storage `catalog/categories/`, tidak membuat kategori baru, tidak menimpa gambar/alt yang sudah diunggah, dan tidak mengubah status kategori maupun data produk. Seeder dijalankan secara eksplisit, bukan otomatis melalui DatabaseSeeder. Media dapat diganti dari Admin > Kategori > Media seperti unggahan biasa. Kartu daftar kategori tetap tanpa gambar.

### Pratinjau Data Produk

`php artisan db:seed --class=CategoryPreviewSeeder` menambahkan tiga produk published bertanda `(Contoh)` untuk masing-masing dari tujuh kategori (total 21) di lingkungan local/testing saja. Seeder ini tidak dipanggil oleh DatabaseSeeder dan tidak mengubah produk draft yang sudah ada. Slug berawalan `demo-` dipakai untuk mencegah duplikasi; menjalankannya kembali tidak menimpa perubahan CMS, termasuk tiga contoh motor yang sudah ada. Semua kategori dan brand terkait harus sudah published; seeder tidak memublikasikan kategori atau brand secara otomatis.

Sejak koreksi 2026-10-07, masing-masing dari 21 produk contoh memiliki gambar khusus yang berbeda di `public/images/products/`, disalin ke `catalog/product-illustrations/`. Seeder tidak lagi menyalin satu gambar kategori untuk beberapa produk dan tidak membutuhkan unggahan kategori. Nama, deskripsi, dan alt text mengikuti jenis serta bentuk peralatan pada setiap gambar. Nomor seri DEMO dikosongkan karena bukan model resmi. Data contoh masih muncul pada semua daftar publik karena berstatus published. Sebelum launch, ganti dengan katalog resmi atau ubah menjadi draft.

Seluruh 28 record lama, termasuk tujuh draft, dikoreksi melalui `php artisan db:seed --class=ProductIllustrationSeeder` (local/testing saja). Tujuh draft diberi nama ilustratif dan gambar terpisah dari produk lain tanpa dipublikasikan. Status, URL, kategori, brand, serta media kategori tidak diubah. Cadangan data sebelumnya tersimpan pada disk local `backups/product-illustrations/`. Penanda selesai mencegah koreksi ulang menimpa suntingan CMS. Pemetaan lengkap, prompt, dan batasan ilustrasi: [product-image-corrections.md](product-image-corrections.md). Gambar ini bukan foto model resmi atau bukti spesifikasi dan hubungan brand.

Contoh yang ditambahkan: external/screen/internal vibrator; chemical/water treatment/process dosing pump; peristaltic/slurry/sludge hose pump; process/braided/flanged hose; screw/tank mounted compressor dan air dryer; containment boom, absorbent pads, dan spill kit. Nama dan aplikasi hanya untuk peninjauan tampilan, bukan katalog resmi brand terkait.

Logo perusahaan dapat diunggah melalui Pengaturan. Header publik menggunakan logo asli (`public/images/company-logo.png`) dengan nama perusahaan dan tagline sebagai teks di sampingnya. Susunan horizontal menjaga header ringkas (76 px pada desktop dan 70 px pada mobile, di luar utility bar dan border). Logo yang diunggah melalui CMS tetap diprioritaskan. Logo principal belum disertakan; nama brand ditampilkan sebagai teks.

Footer menggunakan logo khusus yang diberikan pada 2026-10-06 (`public/images/company-logo-footer.png`). PNG asli dipertahankan; ruang transparan dibatasi lewat CSS. Latar footer terang menjaga logo biru dan tagline hitam terbaca, dengan navigasi, kontak, alamat, kategori produk, dan bar copyright gelap. Tampilan dan tautan footer diperiksa pada lebar 320, 390, 768, 901, 1024, dan 1440 px.

## Slideshow Hero Beranda

Revisi 2026-10-08 mempertahankan layout, ukuran, teks, dan CTA hero. Tiga gambar berganti otomatis setiap enam detik dengan fade 900 ms. Sesuai revisi lanjutan, tombol putar/jeda, sebelumnya/berikutnya, dan indikator dihapus. Hover serta fokus pada CTA tidak menghentikan autoplay. Tab tersembunyi dan hero di luar viewport menghentikan timer sementara.

Preferensi reduced motion menonaktifkan autoplay dan fade; hero tetap statis selama preferensi ini aktif. Tanpa JavaScript, gambar pertama dan CTA tetap tampil. Gambar berikutnya baru dimuat setelah window load dan harus selesai di-decode sebelum ditampilkan; gambar gagal dimuat dilewati. Tidak ada perubahan CMS atau data produk. Pengaturan slide saat ini berada di template Beranda, bukan Admin.

Gambar tambahan berupa ilustrasi AI generik, bukan foto fasilitas perusahaan. Aset WebP dan prompt lengkap: [hero-slideshow.md](hero-slideshow.md). Pengujian browser khusus: `npx playwright test tests/Browser/hero-carousel.spec.js`, termasuk ukuran hero yang sama pada lebar 320, 390, 768, dan 1440 px.

## Inquiry dan Email

Inquiry disimpan sebelum notifikasi email diproses. Kegagalan email dicatat tanpa menggagalkan penyimpanan inquiry. Status: new, contacted, qualified, closed, spam. Catatan internal hanya tersedia bagi administrator/sales.

Environment lokal mempertahankan `MAIL_MAILER=log`. Email masuk ke `storage/logs/laravel.log`, bukan dikirim ke kotak masuk sungguhan. Kolom `notified_at` berarti mailer telah memproses pesan, bukan bukti email diterima penerima. Data inquiry di log perlu dilindungi sama seperti database.

Email penerima awal mengikuti PRD: `admin@smartomoro.com`. Dapat diganti dari Pengaturan. Sebelum produksi, atur SMTP di `.env`, konfirmasi penerima, lalu uji pengiriman sungguhan. Notifikasi saat ini sinkron; retry melalui antrean belum diimplementasikan. Acknowledgement ke pengunjung tidak dikirim.

Form menerapkan CSRF, validasi server, honeypot, pembatasan 5 submit/menit per IP, dan persetujuan penggunaan data. Login juga dibatasi 5 percobaan/menit per IP.

## Pengujian

```powershell
php artisan test
npm run build
npx playwright test
```

Feature tests menggunakan SQLite in-memory, bukan MySQL lokal. Playwright memerlukan website lokal berjalan dan Google Chrome terpasang. Untuk menjalankan skenario CMS, set `CMS_TEST_EMAIL` dan `CMS_TEST_PASSWORD` dengan akun administrator lokal. Tanpa variabel tersebut, hanya skenario CMS yang dilewati. `PLAYWRIGHT_BASE_URL` dapat digunakan untuk mengganti URL pengujian.

Hasil 2026-09-29: 20 test backend lulus (192 assertions), 6 skenario browser lulus pada viewport desktop/tablet/mobile, build Vite berhasil, serta kompilasi Blade dan route cache berhasil. Route cache dibersihkan kembali untuk development. Audit WCAG menyeluruh, screen reader, Core Web Vitals, dan pengujian email production belum dilakukan.

Screenshot dan hasil browser disimpan di `test-results/`, yang diabaikan Git. Pengujian browser tidak memublikasikan produk atau mengirim inquiry ke perusahaan.

## Aset dan Konten Sementara

- Foto hero lokal: `public/images/industrial.jpg`, diunduh dari `https://images.unsplash.com/photo-1567789884554-0b844b597180?auto=format&fit=crop&w=1920&q=85` sebagai ilustrasi fasilitas industri. Bukan foto kantor/proyek Artomoro atau bukti hubungan dengan merek pada foto. Ganti dengan aset resmi yang disetujui sebelum launch; dokumentasikan lisensinya.
- Placeholder katalog: `public/images/catalog-placeholder.svg`, hanya menandai gambar belum tersedia.
- Nomor WhatsApp memakai nomor telepon dari PRD dalam format internasional. Perlu konfirmasi bahwa nomor tersebut aktif di WhatsApp.
- Nomor legalitas, scan dokumen, link peta, spesifikasi, dan datasheet tidak dikarang.
- Halaman privasi berisi penjelasan alur data saat ini dan perlu review perusahaan sebelum publikasi.

## Pekerjaan Sebelum Launch

Konten resmi dan persetujuan owner, SMTP production, domain/HTTPS, backup, monitoring, audit aksesibilitas lengkap, pengukuran Core Web Vitals, dan deployment belum selesai. Fitur lanjutan yang masih terbuka tercatat di `task-list-artomoro.md`.

Panduan deployment: set `APP_ENV=production`, `APP_DEBUG=false`, dan `APP_URL` domain HTTPS; atur database/mail; jalankan migration, storage link, build, `config:cache`, `route:cache`, dan `view:cache`. Atur document root ke `public/`, lindungi `.env`, dan siapkan backup database/media sebelum menggunakan data sungguhan.
