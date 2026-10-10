# Draft Konten Detail Brand

## Status dan Persetujuan

Pengayaan sementara disetujui pengguna pada 11 Oktober 2026, termasuk satu field JSON nullable `brands.technology_details`, editor item pada CMS Brand, dan pengisian draft tujuh brand. Ini bukan perubahan status publikasi brand menjadi `draft`; status brand existing dipertahankan dan konten sementara tampil pada website lokal.

Profil 68-71 kata per brand berasal dari draft pengguna yang disesuaikan dengan cakupan CMS/PRD serta referensi produsen. Tidak ada klaim stok, distributor resmi, kemitraan eksklusif, sertifikasi, garansi, atau spesifikasi angka. Produk berlabel `(Contoh)` tidak dipakai sebagai bukti katalog dan tidak diubah.

## Isi yang Diterapkan

| Brand | Mengenal Brand | Fokus Teknologi |
| --- | --- | --- |
| Wolong | Fungsi motor, penggerak, dan pertimbangan pemilihan peralatan; 68 kata | Electric Motors, Generators, Drives & Control |
| OLI | Penanganan material dan pemadatan dengan vibrasi; 69 kata | External Vibrators, Internal Vibrators, Screen Vibrators, Converters |
| Qdos | Penakaran bahan kimia dengan pompa peristaltik; 69 kata | Chemical Metering & Dosing, satu cakupan proses |
| Bredel | Prinsip pompa selang dan kebutuhan transfer fluida; 69 kata | Peristaltic Hose Pumps, bukan tiga kategori yang tumpang tindih |
| Aflex | Selang PTFE, sambungan, dan pertimbangan konfigurasi; 71 kata | PTFE Hose Assemblies |
| Tecbell | Perbedaan kompresi dan pengeringan udara; 69 kata | Air Compressors, Air Dryers |
| BLU-C | Konteks kategori pada katalog SMART dan fungsi umum penanganan tumpahan; 71 kata | Pengendalian Tumpahan Minyak, satu penjelasan umum |

Hero menggunakan kalimat ringkas yang berbeda dari profil. Ketiga bagian diambil dari data CMS, bukan fallback frontend berdasarkan slug. Daftar teknologi menjelaskan fungsi umum portofolio/kategori, bukan janji ketersediaan seluruh model melalui SMART. Card informasi teknologi tidak diberi tautan produk palsu; tautan kategori existing tetap pada hero.

## Sumber Verifikasi

Diperiksa pada 11 Oktober 2026. Teks ditulis ulang, bukan disalin verbatim.

- Wolong: [Motors, Generators, Drives & Control](https://www.wolong-electric.com/).
- OLI: [portofolio industrial vibrators dan concrete consolidation](https://www.olivibra.com/), [internal/external vibration](https://concrete.olivibra.com/concrete-vibrators/), [MVE-SV Screen Vibrators](https://www.olivibra.com/products/mve-sv-screen-vibrators/).
- Qdos: [Watson-Marlow Qdos chemical metering pumps](https://www.wmfts.com/en-us/mining-products/watson-marlow-pumps/cased-pumps/qdos-metering-pump/).
- Bredel: [Bredel hose pumps](https://www.wmfts.com/en/brands/bredel-hose-pumps/).
- Aflex: [PTFE hose portfolio dan assemblies](https://www.wmfts.com/en/water-wastewater/aflex-hoses/).
- Tecbell: [portofolio kompresor](https://www.tecbell.cn/), [post-processing dryer](https://www.tecbell.cn/product_list_7_1.html).
- BLU-C: draft pengguna serta kategori CMS/PRD SMART saja. Identitas principal belum dapat diverifikasi, sehingga tidak ada klaim mengenai produsen, keluarga produk, atau kemampuan teknis BLU-C.

Sumber dan catatan batasan juga disimpan per brand dalam `database/data/brand-profile-drafts.json`. File tersebut adalah bahan pengisian awal, bukan sumber data runtime website.

## Cara Mengedit Melalui CMS

1. Buka Admin > Brand > Edit brand.
2. Pada **Deskripsi**, paragraf pertama adalah hero. Beri satu baris kosong, lalu isi paragraf profil Mengenal Brand. Template mempertahankan kalimat utuh dan menempatkan paragraf lanjutan pada profil.
3. **Fokus teknologi** lama tetap label kategori singkat untuk hero/listing. Jangan menggantinya dengan paragraf panjang.
4. Pada **Fokus Teknologi Detail Brand**, isi nama dan penjelasan item. Tombol tambah/hapus tersedia; maksimum 12 item, nama maksimum 150 karakter, penjelasan maksimum 1000 karakter. Nama wajib bila penjelasan diisi. Urutan mengikuti form.
5. Simpan melalui tombol existing. Tidak perlu build atau menjalankan seeder agar perubahan data tampil.

Jika semua item teknologi dihapus, template kembali ke kategori terkait existing. Brand tanpa kategori dan tanpa item tidak menampilkan section kosong. Tidak ada fallback draft yang mengalahkan edit CMS. Field baru dapat dibiarkan kosong untuk brand lain.

## Pengisian Awal dan Backup

- Migration hanya menambah satu field nullable pada tabel `brands`; tidak mengubah data produk/kategori.
- Seeder `BrandProfileDraftSeeder` dijalankan manual, tidak didaftarkan pada `DatabaseSeeder`, startup aplikasi, atau migration.
- Seeder hanya mengisi brand existing jika Deskripsi masih sama persis dengan teks awal dalam `expected_description` dan belum memiliki item teknologi. Brand hilang atau konten berbeda dilewati.
- Pengisian dilakukan dalam transaksi. Backup Deskripsi, item teknologi, ID, slug, dan timestamp awal harus berhasil tersimpan sebelum data diperbarui. Kegagalan backup membatalkan pengisian.
- Backup privat lokal: `storage/app/private/brand-content-backups/20261010-214631-6e320a5a-e022-4496-8cf2-9b3235abbca1.json`. Nama waktu mengikuti timezone aplikasi existing, bukan perubahan timezone project.
- Eksekusi kedua telah diuji: tujuh brand dilewati, tanpa update timestamp atau duplikasi item. Jangan menjalankan ulang untuk mengganti hasil edit CMS. Jika editor sengaja mengembalikan seluruh teks ke salinan awal dan mengosongkan teknologi, guard berbasis isi tidak dapat membedakannya dari data awal; lakukan pengelolaan selanjutnya hanya melalui CMS.
- Seeder menolak lingkungan selain `local`/`testing`. Publikasi draft ke produksi memerlukan review terpisah.

Perintah pengisian awal untuk instalasi lokal lain yang telah disetujui:

```sh
php artisan migrate --path=database/migrations/2026_10_11_000100_add_technology_details_to_brands_table.php
php artisan db:seed --class=BrandProfileDraftSeeder
```

Untuk pemulihan konten, tinjau backup terlebih dahulu dan pulihkan field konten saja melalui CMS. Jangan rollback migration hanya untuk mengembalikan teks: rollback akan menghapus seluruh item teknologi, termasuk edit yang dibuat setelah pengisian.

## Materi Resmi yang Masih Dibutuhkan

- Wolong: keluarga/model yang ditawarkan SMART dan cakupan aplikasi yang disetujui.
- OLI: model vibrator/converter dan aplikasi yang sesuai untuk penawaran perusahaan.
- Qdos: model resmi; produk contoh bertuliskan diaphragm tidak dijadikan klasifikasi lini Qdos.
- Bredel: seri pompa, elemen selang, dan kecocokan aplikasi dari dokumentasi model.
- Aflex: konfigurasi hose assemblies, sambungan, dan kompatibilitas aplikasi.
- Tecbell: model kompresor/pengering dan kebutuhan kualitas udara yang disetujui.
- BLU-C: identitas principal, situs/katalog resmi, serta jenis peralatan yang benar-benar ditawarkan.

## File Implementasi

- `app/Models/Brand.php`: cast field JSON.
- `app/Http/Controllers/Admin/CatalogController.php`: validasi dan penyimpanan item khusus modul Brand.
- `database/migrations/2026_10_11_000100_add_technology_details_to_brands_table.php`: field nullable.
- `database/data/brand-profile-drafts.json`: draft, guard deskripsi awal, dan sumber.
- `database/seeders/BrandProfileDraftSeeder.php`: pengisian manual dengan backup dan guard.
- `resources/views/admin/catalog/form.blade.php`: include editor hanya untuk Brand.
- `resources/views/admin/catalog/brand-technologies.blade.php`: form item lokal.
- `resources/js/brand-editor.js`: tambah/hapus item dan pengelolaan fokus keyboard.
- `vite.config.js`: entry editor khusus CMS Brand.
- `resources/views/public/partials/brand-detail.blade.php`: render item dari CMS, fallback kategori existing.
- `tests/Feature/BrandProfileDraftTest.php`: pengisian, backup, idempotensi, validasi, permission, dan CMS round trip.
- `tests/Browser/brand-detail.spec.js`: verifikasi tujuh brand dan navigasi.
- `tests/Browser/brand-editor.spec.js`: interaksi form desktop/mobile dengan rendering form asli secara read-only; penyimpanan backend diuji oleh feature test pada database in-memory.
- `docs/brand-content-refinement.md`: penanda status historis.
- `docs/brand-draft-content.md`: dokumentasi pekerjaan ini.

Tidak ada perubahan stylesheet, komponen produk, logo, route, authentication, header, CTA, atau footer. Pertambahan tinggi konten berasal dari paragraf dan jumlah item, bukan perubahan padding atau typography.

## Hasil Pemeriksaan

- `npm run build`: berhasil. Warning existing `/fonts/InterVariable.woff2` diselesaikan saat runtime; tidak ada perubahan font pada pekerjaan ini.
- `php artisan test --compact`: 72 test lulus, 2103 assertion, database pengujian in-memory.
- Playwright detail brand, listing brand, dan editor Brand: 20 test lulus; viewport publik mencakup 1440, 1280, 768, 390, 375, dan 320px. Form editor diperiksa pada 1280 dan 390px tanpa menulis akun atau data lokal.
- `node --check resources/js/brand-editor.js` dan `git diff --check`: lulus. Project tidak menyediakan script lint terpisah.
- Snapshot data sebelum/sesudah: seluruh produk, kategori, dan field brand selain Deskripsi/item teknologi/timestamp identik.
- Bagian template Produk Terkait identik terhadap HEAD sebelum pekerjaan. CSS detail, template listing brand, Beranda, layout publik, dan CTA tidak berubah.
- Screenshot desktop/mobile ditinjau: teks utuh, logo proporsional, card teknologi tidak overflow. Link kategori di hero dan link produk tetap berfungsi.
