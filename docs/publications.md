# Publikasi & Legalitas SMART

## Lingkup implementasi

- Halaman `/publikasi` menggunakan design tokens, Inter, container, CTA, dan footer existing.
- Navbar dan footer hanya mengganti tautan Industri menjadi Publikasi.
- `/industri` melakukan redirect permanen 301 ke `/publikasi`.
- `/industri/{slug}`, relasi industri-produk, dan modul CMS Industri tidak dihapus karena masih dipakai katalog existing.
- Tidak ada perubahan data produk, brand, kategori, atau konten profil brand.
- Sidebar CMS mendapat satu menu Publikasi untuk pengguna yang memiliki izin `manage-content` (administrator/editor). Tidak ada login atau sistem role baru.

## File implementasi

Baru:

- `app/Models/Publication.php`
- `app/Http/Controllers/PublicationController.php`
- `app/Http/Controllers/Admin/PublicationController.php`
- `app/Http/Requests/PublicationRequest.php`
- `app/Rules/PublicationFile.php`
- `app/Services/PublicationStorage.php`
- `app/Console/Commands/ClearPublicationDemos.php`
- `config/publications.php`
- `database/migrations/2026_10_13_000100_create_publications_table.php`
- `database/seeders/PublicationDemoSeeder.php`
- `resources/views/public/publications/{index,card,preview}.blade.php`
- `resources/views/admin/publications/{index,form}.blade.php`
- `resources/css/publications.css`
- `resources/js/publications.js`
- `tests/Feature/PublicationTest.php`
- `tests/Browser/publications.spec.js`
- `docs/publications.md`

Integrasi terbatas pada file existing:

- `routes/web.php`: route publik/admin dan redirect Industri.
- `config/filesystems.php`: disk private khusus publikasi.
- `app/Http/Controllers/PublicController.php`: URL listing Publikasi pada sitemap saja.
- `resources/views/layouts/public.blade.php`: dua tautan navigasi saja.
- `resources/views/layouts/admin.blade.php`: satu tautan sidebar saja.
- `vite.config.js`: dua entry asset lokal Publikasi.
- `tests/Feature/{PublicWebsiteTest,PublicFooterTest,BrandPortfolioTest,ProductCatalogTest}.php`: ekspektasi URL listing baru, tanpa melemahkan pemeriksaan halaman lainnya.

## Migration dan deployment

Migration hanya membuat tabel baru `publications`. Tabel produk, brand, kategori, dan user tidak diubah.

```sh
php artisan migrate --path=database/migrations/2026_10_13_000100_create_publications_table.php
npm run build
```

Migration dan demo sudah dijalankan pada environment `local` project ini. Tidak ada reset database atau pemanggilan seeder utama.

Tambahkan konfigurasi berikut hanya bila batas default perlu disesuaikan:

```dotenv
PUBLICATIONS_MAX_UPLOAD_KB=10240
PUBLICATIONS_MAX_THUMBNAIL_KB=2048
```

Pastikan batas `upload_max_filesize`, `post_max_size`, serta reverse proxy/web server cukup untuk dokumen beserta thumbnail. Batas aplikasi tetap divalidasi server-side. Konfigurasi PHP lokal yang diperiksa mengizinkan 2G, tetapi aplikasi membatasi dokumen ke 10 MB dan thumbnail ke 2 MB.

Document root deployment harus direktori `public`, bukan root repository. Berikan akses tulis pada `storage/app/publications` untuk proses PHP. Jangan membuat symlink atau static alias ke direktori itu. Jangan cache respons `/publikasi/*` dan `/admin/publications/*` di CDN/reverse proxy. Tidak diperlukan `storage:link` untuk dokumen publikasi.

## Route

| Tujuan | Route |
| --- | --- |
| Daftar publik | `GET /publikasi` |
| Preview publik | `GET /publikasi/{id}/preview` |
| File inline | `GET /publikasi/{id}/file` |
| Thumbnail | `GET /publikasi/{id}/thumbnail` |
| Unduh | `GET /publikasi/{id}/unduh` |
| Daftar CMS / simpan baru | `GET / POST /admin/publications` |
| Form baru | `GET /admin/publications/create` |
| Form edit | `GET /admin/publications/{id}/edit` |
| Simpan perubahan / hapus | `PUT / DELETE /admin/publications/{id}` |
| Preview/file/thumbnail/unduh CMS | `GET /admin/publications/{id}/{preview,file,thumbnail,unduh}` |

## Pengelolaan dari CMS

1. Login menggunakan akun administrator/editor existing, lalu pilih **Publikasi**.
2. Pilih **Tambah Publikasi**, isi judul/kategori, lalu unggah PDF/JPG/PNG/WEBP.
3. Deskripsi, thumbnail, urutan, dan tanggal publikasi bersifat opsional.
4. Default **Draft** dan izin unduh nonaktif. Simpan, kemudian periksa melalui **Preview**.
5. Ubah status menjadi **Published** setelah peninjauan. Untuk Legalitas, checkbox persetujuan wajib dicentang pada setiap penyimpanan berstatus Published.
6. Tanggal kosong memakai waktu pertama publikasi. Tanggal mendatang menjadwalkan akses. Input dan tampilan tanggal Publikasi menggunakan WIB (`Asia/Jakarta`, UTC+7), lalu dikonversi ke zona waktu aplikasi untuk penyimpanan (UTC pada konfigurasi existing). Konfigurasi zona waktu global tidak diubah. Form edit mengonversi kembali ke WIB, sehingga penyimpanan ulang tidak menggeser jadwal.
7. Centang **Izinkan tombol Unduh** bila sesuai. Endpoint unduh juga memeriksa izin ini.
8. Pada form edit, unggahan kosong mempertahankan file existing. Unggahan baru mengganti file, dan file lama dibersihkan sesudah transaksi berhasil.
9. Ubah kembali ke Draft untuk mencabut akses publik. Hapus menghapus record serta file milik publikasi tersebut.

Daftar CMS mendukung pencarian judul, kategori, status, pagination, dan aksi preview/edit/hapus. Form mempertahankan input setelah validasi gagal; file perlu dipilih ulang. Submit menggunakan disabled/busy state existing, bukan request AJAX baru.

## Keamanan dan keterbatasan

- Semua file, termasuk Published, berada pada disk private `publications` di `storage/app/publications`. Disk ini di luar root disk local yang memiliki fasilitas signed serving Laravel dan tidak memiliki static URL.
- Endpoint publik memeriksa status Published dan tanggal akses setiap request, termasuk request thumbnail dan download. Endpoint admin memakai middleware `auth`, `admin`, dan `can:manage-content`.
- Draft tetap 404 pada endpoint publik walaupun pemohon sedang login. Preview Draft tersedia lewat route admin yang berwenang.
- Respons file/preview menggunakan `no-store`, `no-cache`, `nosniff`, serta file inline memakai CSP sandbox dan pembatasan frame same-origin. Nama storage menggunakan UUID dan pola path terbatas.
- Validasi meliputi ekstensi, MIME hasil deteksi server, header/footer PDF, signature/dimensi gambar, batas ukuran, kategori, status, metadata, dan CSRF.
- Validasi signature bukan pemeriksaan malware atau validasi semantik seluruh isi PDF. Tidak ada antivirus/CDR baru. Unggah hanya materi yang telah ditinjau; sebelum production, pertimbangkan pemindaian malware sesuai kebijakan perusahaan.
- Unpublish mencabut request berikutnya, bukan salinan yang sudah diunduh/dilihat. Menonaktifkan tombol unduh tidak mencegah fungsi simpan/cetak dari viewer browser.
- Preview PDF bergantung pada viewer bawaan browser. Disediakan tautan buka di tab baru bila inline preview tidak didukung. Tidak ada plugin tambahan.
- Bila pembersihan file lama gagal akibat filesystem, record baru tetap tersimpan dan file lama tidak memiliki endpoint publik. Error dicatat pada log Laravel untuk ditindaklanjuti.

## Dokumen demo

Jalankan secara eksplisit, hanya pada local/staging/testing:

```sh
php artisan db:seed --class=PublicationDemoSeeder
```

Seeder tidak terdaftar pada startup atau seeder utama. `demo_key` unik mencegah duplikasi; record yang sudah ada tidak ditimpa, termasuk perubahan melalui CMS. Generator fixture PDF sederhana berada di seeder khusus ini; file hasilnya disimpan melalui disk private Publikasi.

| Dokumen | Kategori | Status awal |
| --- | --- | --- |
| Panduan Penggunaan Website SMART (Demo) | Publikasi Perusahaan | Published |
| Contoh Materi Publikasi (Demo) | Publikasi Perusahaan | Draft |
| Contoh Dokumen Administrasi (Demo) | Legalitas | Draft |

PDF menampilkan watermark DEMO dan keterangan bukan dokumen resmi/legalitas. Tidak ada nomor dokumen, QR, tanda tangan, stempel, atau tiruan format pemerintah. Legalitas demo tidak dapat dipublikasikan melalui CMS. Semua demo disembunyikan dari endpoint publik jika environment berubah menjadi production, walaupun record masih berstatus Published.

Hapus demo sebelum production:

```sh
php artisan publications:clear-demo
```

Perintah meminta konfirmasi dan hanya menghapus record `is_demo=true` beserta filenya. Untuk proses non-interaktif yang telah disetujui, tambahkan `--force`. Dokumen non-demo tidak terpengaruh. Jangan mengubah record demo menjadi dokumen resmi; buat entri baru untuk dokumen perusahaan yang telah disetujui.

## Pemeriksaan

```sh
php artisan test --compact
npm run build
npx playwright test tests/Browser/publications.spec.js
```

Tes fitur menggunakan SQLite in-memory dan storage fake. Cakupan mencakup login, role, CSRF, CRUD, publish/unpublish, izin unduh, file replacement, privasi Draft/thumbnail, penjadwalan, input berbahaya, ukuran upload, konfirmasi Legalitas, seeder idempotent, cleanup, serta pembatasan production.

Tes browser Publikasi membutuhkan server lokal dan tiga demo awal. Pengujian halaman publik berjalan pada 1440/1280/768/390/375 px, termasuk link preview, unduh, keyboard, no-JavaScript, serta pemeriksaan overflow. Form dan daftar admin dirender read-only dari view sebenarnya, tanpa membuat akun atau mengubah sesi admin lokal. CRUD/login diverifikasi melalui test HTTP Laravel; browser tidak menggunakan kredensial admin pribadi.

Hasil validasi: 86 tes Laravel lulus (2317 assertions), 11 tes Playwright Publikasi dan 1 tes tambahan aset/regresi lulus (dijalankan terpisah), build Vite dan Pint file PHP baru lulus. Build masih memberi peringatan font Inter existing yang di-resolve saat runtime; tidak ada perubahan pada font atau design system.

Regresi browser existing: 47 lulus, 2 dilewati karena kredensial admin pengujian tidak tersedia, dan 4 tes `website.spec.js` gagal pada pemeriksaan gambar Beranda sebelum lazy-load selesai (termasuk slide yang sengaja belum memiliki `src`). Tes lama dan kode Beranda tidak diubah. Pemeriksaan tambahan pada `publications.spec.js` berhasil memverifikasi aset `src`/`data-src` dengan decode gambar tersendiri pada Beranda, Tentang Kami, katalog, listing/detail brand, dan Kontak di 1440/768/390/375 px, tanpa overflow.

Checksum SHA-256 data produk, brand, dan kategori identik sebelum dan sesudah migration/demo. CTA/footer diperiksa lewat tes browser; hanya tautan footer Industri yang memang diganti. File CSS/JS global, template halaman approved, login, dashboard, serta konten brand tidak diedit. Server existing di `http://127.0.0.1:8000` digunakan untuk pemeriksaan; tidak dihentikan atau diganti.
