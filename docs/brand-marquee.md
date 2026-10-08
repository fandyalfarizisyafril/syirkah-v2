# Logo Marquee Beranda

Tanggal pemeriksaan aset: 2026-10-09.

## Perilaku dan Pengelolaan

- Hanya daftar pada section BRANDS & PRINCIPALS Beranda yang berubah. Heading dan link tetap.
- Data berasal dari Brand berstatus published, sesuai urutan CMS, dengan field `image` terisi. Tidak ada daftar brand hardcoded di komponen publik.
- Dua kelompok visual bergerak kanan ke kiri, `30s linear infinite`. Lebar setiap kelompok minimal selebar area marquee untuk mencegah ruang kosong pada daftar pendek. Tidak ada record database duplikat.
- Hover menghentikan animasi sementara. Saat fokus keyboard, daftar asli menjadi statis dan logo terfokus digeser ke area terlihat. Duplikasi diabaikan screen reader dan tidak masuk urutan Tab.
- Reduced motion menggunakan satu baris statis yang dapat digeser. Animasi dasar tidak membutuhkan JavaScript; helper kecil hanya mengatur fokus dan reset posisi scroll.
- Admin > Brand > Media mendukung penggantian file JPG/PNG/WebP, serta opsi Hapus logo. Mengubah status menjadi draft atau menghapus brand juga menghapusnya dari marquee. Batasan penghapusan brand yang masih terkait produk tetap berlaku.
- Logo yang belum tersedia tidak diganti dengan teks, placeholder, atau logo rekaan. Qdos dan BLU-C tetap ada dalam katalog dan menunggu aset dari perusahaan.

## Aset Resmi

File disalin tanpa perubahan bentuk/warna dari sumber principal. `database/data/brand-logos.json` mencatat halaman asal dan URL file lengkap. Aset tidak di-hotlink pada runtime. Logo tetap milik masing-masing pemilik merek; sumber resmi tidak berarti pemberian lisensi atau bukti hubungan principal. Perusahaan perlu mengonfirmasi izin penggunaan sebelum publikasi resmi.

| Brand | Aset | Sumber |
| --- | --- | --- |
| Wolong | `public/images/brands/wolong.svg` | [Wolong](https://www.wolong-electric.com/) |
| OLI | `public/images/brands/oli.png` | [OLI Vibrators](https://www.olivibra.com/) |
| Bredel | `public/images/brands/bredel.svg` | [WMFTS Brands](https://www.wmfts.com/en/brands/) |
| Aflex | `public/images/brands/aflex.svg` | [WMFTS Brands](https://www.wmfts.com/en/brands/) |
| Tecbell | `public/images/brands/tecbell.png` | [Tecbell](https://www.tecbell.cn/) |
| Qdos | Belum tersedia | Identitas produk sudah ditemukan pada WMFTS, tetapi file logo mandiri belum diperoleh. Tidak digantikan logo Watson-Marlow. |
| BLU-C | Belum tersedia | Sumber principal dan logo resmi belum dapat diverifikasi. |

## Penerapan

`php artisan db:seed --class=BrandLogoSeeder` menyalin lima aset ke disk public (`catalog/brand-logos/`) dan mengisi media record brand existing yang masih kosong. Tidak membuat brand, mengubah status, atau menimpa upload Admin. Perintah sudah dijalankan pada database lokal.

Seeder ini sengaja tidak dimasukkan ke alur seed umum. Jalankan hanya saat pemasangan aset awal; jika dijalankan kembali setelah Admin menghapus logo, field kosong dapat diisi lagi. Pengelolaan sehari-hari tetap melalui CMS.

SVG bawaan berasal dari sumber tepercaya dan ditampilkan melalui `<img>`, bukan inline. Validasi upload CMS tetap JPG/PNG/WebP; tidak membuka upload SVG umum.

## Verifikasi

- `php artisan test`: termasuk CRUD logo CMS, status publikasi, urutan, seed idempotent, serta daftar kosong/satu logo.
- `npx playwright test tests/Browser/brand-marquee.spec.js`: desktop/mobile, kesinambungan loop, hover, link duplikat, keyboard, reduced motion, daftar pendek, dan tanpa JavaScript.
