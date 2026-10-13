# Logo Marquee Beranda

Tanggal pemeriksaan aset: 2026-10-09.

## Perilaku dan Pengelolaan

- Hanya daftar pada section BRANDS & PRINCIPALS Beranda yang berubah. Heading dan link tetap.
- Data berasal dari Brand berstatus published, sesuai urutan CMS, dengan field `image` terisi. Tidak ada daftar brand hardcoded di komponen publik.
- Dua kelompok visual bergerak kanan ke kiri, `30s linear infinite`. Lebar setiap kelompok minimal selebar area marquee untuk mencegah ruang kosong pada daftar pendek. Tidak ada record database duplikat.
- Hover menghentikan animasi sementara. Saat fokus keyboard, daftar asli menjadi statis dan logo terfokus digeser ke area terlihat. Duplikasi diabaikan screen reader dan tidak masuk urutan Tab.
- Reduced motion menggunakan satu baris statis yang dapat digeser. Animasi dasar tidak membutuhkan JavaScript; helper kecil hanya mengatur fokus dan reset posisi scroll.
- Admin > Brand > Media mendukung penggantian file JPG/PNG/WebP, serta opsi Hapus logo. Mengubah status menjadi draft atau menghapus brand juga menghapusnya dari marquee. Batasan penghapusan brand yang masih terkait produk tetap berlaku.
- Qdos dan BLU-C menggunakan SVG lokal yang direkonstruksi secara deterministik dari wordmark pada materi branding/kemasan referensi. Keduanya perlu diganti jika perusahaan menyediakan master logo resmi.

## Aset dan Referensi

`database/data/brand-logos.json` mencatat halaman asal dan URL referensi. Aset tidak di-hotlink pada runtime. Lima logo pertama disalin dari sumber principal. SVG Qdos dan BLU-C direkonstruksi dari wordmark yang terlihat pada materi branding dan kemasan karena master logo mandiri belum ditemukan. Logo tetap milik masing-masing pemilik merek; perusahaan perlu mengonfirmasi bentuk, izin penggunaan, dan identitas principal sebelum publikasi resmi.

| Brand | Aset | Sumber |
| --- | --- | --- |
| Wolong | `public/images/brands/wolong.svg` | [Wolong](https://www.wolong-electric.com/) |
| OLI | `public/images/brands/oli.png` | [OLI Vibrators](https://www.olivibra.com/) |
| Bredel | `public/images/brands/bredel.svg` | [WMFTS Brands](https://www.wmfts.com/en/brands/) |
| Aflex | `public/images/brands/aflex.svg` | [WMFTS Brands](https://www.wmfts.com/en/brands/) |
| Tecbell | `public/images/brands/tecbell.png` | [Tecbell](https://www.tecbell.cn/) |
| Qdos | `public/images/brands/qdos.svg` | [WMFTS Qdos](https://www.wmfts.com/en-us/mining-products/watson-marlow-pumps/cased-pumps/qdos-metering-pump/) dan materi branding Qdos |
| BLU-C | `public/images/brands/blu-c.svg` | Wordmark pada kemasan produk BLU-C; identitas principal masih perlu diverifikasi |

## Penerapan

`BrandLogoSeeder` menyalin tujuh aset ke disk public (`catalog/brand-logos/`) dan mengisi media record brand existing yang masih kosong. Seeder dipanggil otomatis oleh `DatabaseSeeder` setelah katalog dasar dibuat. Seeder tidak membuat brand, mengubah status, atau menimpa upload Admin.

Seeder ini ditujukan untuk pemasangan data awal melalui `php artisan migrate:fresh --seed`. Jika `DatabaseSeeder` dijalankan kembali setelah Admin menghapus logo, field kosong dapat diisi lagi; pengelolaan sehari-hari tetap melalui CMS.

SVG bawaan berasal dari sumber tepercaya dan ditampilkan melalui `<img>`, bukan inline. Validasi upload CMS tetap JPG/PNG/WebP; tidak membuka upload SVG umum.

## Verifikasi

- `php artisan test`: termasuk CRUD logo CMS, status publikasi, urutan, seed idempotent, serta daftar kosong/satu logo.
- `npx playwright test tests/Browser/brand-marquee.spec.js`: desktop/mobile, kesinambungan loop, hover, link duplikat, keyboard, reduced motion, daftar pendek, dan tanpa JavaScript.
