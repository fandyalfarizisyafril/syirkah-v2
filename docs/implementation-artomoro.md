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

Spesifikasi menggunakan pasangan parameter/nilai. Relasi industri dan produk dapat diatur dari kedua form. Galeri, gambar utama, dan datasheet PDF dapat diperbarui lewat CMS. Gambar dibatasi JPG/PNG/WebP maksimal 4 MB; PDF maksimal 10 MB. Sesuaikan batas PHP `upload_max_filesize` (minimal 10M) dan `post_max_size` (misalnya 64M) agar mendukung batas aplikasi. File lama tetap disimpan untuk mencegah penghapusan file yang masih dirujuk. Pembersihan file yatim belum dijadwalkan.

Logo perusahaan dapat diunggah melalui Pengaturan. Header publik menggunakan logo asli (`public/images/company-logo.png`) dengan nama perusahaan dan tagline sebagai teks di sampingnya. Susunan horizontal menjaga header ringkas (76 px pada desktop dan 70 px pada mobile, di luar utility bar dan border). Logo yang diunggah melalui CMS tetap diprioritaskan. Logo principal belum disertakan; nama brand ditampilkan sebagai teks.

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
