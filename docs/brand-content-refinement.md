# Review Konten Detail Brand

> Catatan historis sebelum persetujuan pengisian draft. Pengguna kini telah menyetujui field dan pengisian sementara; status terbaru, sumber, batasan, backup, dan cara edit CMS tersedia di [Draft Konten Detail Brand](brand-draft-content.md). Keterbatasan tampilan satu kalimat di bawah tidak lagi menggambarkan data yang telah diisi.

## Sumber dan Batasan

- Field CMS `brands.description` adalah satu-satunya profil naratif; `focus` menyimpan fokus teknologi, bukan deskripsi pendek terpisah.
- Cakupan awal tercantum pada PRD_PT_Syirkah_Mandiri_Artomoro.md, bagian FR-03 dan FR-05. Dokumen tersebut belum menyediakan profil brand lengkap 50-90 kata.
- Sumber resmi diperiksa untuk rekomendasi editorial di bawah (11 Oktober 2026). Rekomendasi ini BELUM dipublikasikan ke website atau dimasukkan ke database.
- Tidak ada perubahan data, schema, Admin CMS, API, relasi, atau urutan produk. Tidak ada profil hardcoded berdasarkan slug.
- Produk berlabel `(Contoh)` tetap data demo, bukan bukti spesifikasi atau ketersediaan produk resmi.

## Perilaku Tampilan

- Penyebab hero terpotong adalah `Str::words` dengan batas maksimal 18 kata atau 75% jumlah kata, bukan CSS. Pembatas tersebut dihapus. Tidak ada ellipsis, pemotongan karakter, atau pemisahan kalimat otomatis.
- Hero memakai paragraf pembuka CMS secara utuh; jika hanya satu paragraf, seluruh paragraf ditampilkan. Ukuran font, natural wrapping, warna, dan lebar 520px existing tidak diubah.
- Jika CMS berisi beberapa paragraf yang dipisahkan baris kosong, paragraf pertama ditampilkan di hero dan paragraf berikutnya di Mengenal Brand. Tidak ada teks asli yang dibuang atau ditulis ulang; pembuka tidak diulang pada profil panjang.
- Data ketujuh brand saat pemeriksaan masih satu kalimat. Kalimat yang valid tetap dipertahankan pada Mengenal Brand untuk menjaga section approved. Akibatnya, pada data singkat saat ini hero dan profil masih berulang. Ini adalah keterbatasan konten yang BELUM terselesaikan, bukan pengayaan profil yang sudah selesai.
- Kategori utama tetap memakai relasi dan URL existing.
- Fokus Teknologi tetap menggunakan deskripsi kategori jika berbeda dari profil/fokus/hero. Data saat ini identik dengan profil, sehingga link kategori valid tetap dipertahankan tanpa paragraf berulang. Tidak dibuat kelompok teknologi atau card tambahan dari produk demo. Presentasi dan spacing section tidak diubah pada pekerjaan ini.
- Semua perubahan bersifat presentasi. Admin tetap dapat memperbarui field Deskripsi, Fokus teknologi, dan Website principal yang sudah tersedia.

## Materi yang Perlu Dilengkapi

Tabel berikut adalah daftar permintaan materi, bukan klaim tambahan yang sudah disetujui untuk dipublikasikan.

| Brand | Cakupan yang sudah tercantum di PRD/CMS | Materi untuk ditinjau sebelum dimasukkan ke CMS |
| --- | --- | --- |
| Wolong | Electric motors, generators, PM motors, drive control | Profil resmi berbahasa Indonesia; keluarga motor/generator dan sistem penggerak yang benar-benar ditawarkan SMART; uraian aplikasi yang disetujui. |
| OLI | Internal/external vibrator, screen vibrator, converter | Penjelasan perbedaan kelompok teknologi dan kebutuhan proses material yang didukung; sumber principal untuk tiap kelompok. |
| Qdos | Chemical dosing pump | Profil lini Qdos, cakupan teknologi dosing yang relevan, dan model resmi yang ditawarkan. Jangan menganggap produk demo sebagai model Qdos terverifikasi. |
| Bredel | Hose pump | Profil principal, cakupan pompa selang, serta aplikasi transfer fluida yang disetujui; jangan menambah klaim kompatibilitas fluida tanpa dokumen. |
| Aflex | PTFE hose | Profil brand, keluarga selang PTFE yang tersedia melalui SMART, serta aplikasi dan batas penggunaan dari dokumen resmi. |
| Tecbell | Air compressor dan refrigerated dryer | Profil brand dan penjelasan kelompok kompresor/pengering udara yang benar-benar ditawarkan; data operasional hanya dari materi resmi. |
| BLU-C | Oil boom, absorbent, skimmer, dispersant, spill kit | Identitas principal dan sumber resminya; profil serta cakupan peralatan respons tumpahan yang disetujui perusahaan. Logo yang diunggah bukan verifikasi status kemitraan. |

## Tahap Berikutnya

Setelah disetujui, editor dapat memakai field Deskripsi existing: satu paragraf pembuka ringkas, baris kosong, lalu profil lanjutan. Template menampilkan keduanya tanpa pengulangan. Perubahan database melalui CMS memerlukan persetujuan terpisah dan tidak dilakukan dalam pekerjaan ini.

Belum ada field terstruktur untuk subkelompok teknologi per brand. Jangan mengubah kategori bersama hanya untuk memperkaya satu halaman. Verifikasi dahulu cakupan yang benar-benar ditawarkan SMART; mekanisme pengelolaan subkelompok menjadi pekerjaan terpisah bila diperlukan. Field ringkasan tersendiri juga opsional, bukan syarat perbaikan ellipsis ini.

## Rekomendasi Editorial untuk Ditinjau

Materi berikut menjelaskan portofolio produsen, BUKAN bukti ketersediaan, stok, status distributor, atau hubungan eksklusif SMART. Tidak diimpor oleh template maupun seeder. Semua profil perlu persetujuan editorial sebelum dimasukkan ke CMS.

### Wolong

Usulan profil lanjutan: Portofolio Wolong mencakup motor tegangan rendah dan tinggi, generator, serta perangkat penggerak dan kontrol. Materi produknya juga membahas motor magnet permanen dan solusi sistem penggerak. Cakupan tersebut dapat menjadi dasar pembahasan kebutuhan motor dan pengendalian peralatan; pemilihan keluarga produk yang ditawarkan melalui SMART tetap perlu dikonfirmasi melalui katalog perusahaan.

Sumber: [Wolong - portofolio produsen](https://www.wolong-electric.com/) dan [Download Center - Permanent Magnet Motors / PM Motors and Drives](https://www.wolong-electric.com/download).

Kandidat electric motors, generators, PM motors, dan drives/control didukung sumber produsen serta cakupan PRD. Namun produk CMS masih contoh, sehingga pemetaan model dan cakupan penawaran SMART belum terverifikasi.

### OLI

Usulan profil lanjutan: OLI mengelompokkan portofolionya dalam industrial vibrators, flow aids, dan concrete consolidation. Materi concrete consolidation mencakup vibrator internal, vibrator eksternal, dan converter. Untuk proses penyaringan, OLI juga memiliki kelompok screen vibrators. Pembagian ini membantu membedakan kebutuhan getaran pada peralatan proses dan pemadatan beton, tanpa menyatakan bahwa seluruh kelompok tersebut tersedia melalui SMART.

Sumber: [OLI - kelompok produk](https://www.olivibra.com/), [Concrete Vibrators](https://concrete.olivibra.com/concrete-vibrators/) dan [MVE-SV Screen Vibrators](https://www.olivibra.com/products/mve-sv-screen-vibrators/).

Empat kandidat pada permintaan pengguna didukung sumber produsen. Model, aplikasi, dan cakupan penawaran SMART tetap memerlukan katalog yang bukan contoh.

### Qdos

Usulan profil lanjutan: Qdos merupakan lini pompa peristaltik untuk penakaran bahan kimia dari Watson-Marlow. Fokusnya adalah proses metering dan dosing, dengan opsi kontrol yang bergantung pada model. Informasi lini ini perlu dibedakan dari kategori pompa dosing secara umum; nama model yang ditawarkan melalui SMART perlu dicocokkan kembali dengan katalog resmi sebelum dipublikasikan sebagai kelompok produk.

Sumber: [Watson-Marlow - Qdos metering pumps](https://www.wmfts.com/en-us/mining-products/watson-marlow-pumps/cased-pumps/qdos-metering-pump/).

Perlu ditinjau: katalog CMS juga memiliki produk contoh dengan nama diaphragm pump. Jangan menjadikannya dasar klasifikasi teknologi Qdos atau mengubah produk tersebut dalam pekerjaan ini.

### Bredel

Usulan profil lanjutan: Bredel memiliki portofolio pompa selang peristaltik beserta elemen selangnya. Katalog produsen membedakan seri Bredel dan APEX serta menjelaskan penggunaan untuk pemindahan, metering, dan dosing fluida. Pengelompokan tersebut dapat membantu pembahasan kebutuhan proses, tetapi pilihan seri, kecocokan fluida, dan batas operasi harus mengacu pada dokumen model yang telah dikonfirmasi.

Sumber: [Bredel - hose pumps](https://www.wmfts.com/en/brands/bredel-hose-pumps/).

Perlu ditinjau: seri dan aplikasi yang ditawarkan SMART, bukan hanya nama generik pompa contoh.

### Aflex

Usulan profil lanjutan: Aflex mengembangkan selang berlapis PTFE untuk jalur transfer fluida. Portofolio produsennya mencakup konfigurasi dengan bagian dalam halus maupun bergelombang, termasuk Corroline+ dan Corroflon. Perbedaan konstruksi tersebut menjadi bagian dari pemilihan selang sesuai kebutuhan proses. Kesesuaian bahan, sambungan, dan kondisi operasi tetap perlu ditentukan berdasarkan dokumen produk yang dipilih.

Sumber: [Aflex - PTFE hose portfolio](https://www.wmfts.com/en/water-wastewater/aflex-hoses/).

Perlu ditinjau: keluarga selang dan koneksi yang benar-benar ditawarkan SMART; jangan menganggap produk contoh synthetic hose sebagai lini Aflex terverifikasi.

### Tecbell

Usulan profil lanjutan: Portofolio Tecbell mencakup kompresor udara dengan beberapa konfigurasi, termasuk screw compressor dan kelompok oil-free. Situs produsen juga memisahkan perangkat pengering udara sebagai kelompok pendukung. Informasi ini dapat digunakan untuk membedakan pembangkitan udara bertekanan dan penanganan udara setelah kompresi. Pemilihan tipe yang relevan untuk katalog SMART masih memerlukan konfirmasi seri dan dokumen produknya.

Sumber: [Tecbell - daftar kelompok produk](https://www.tecbell.cn/).

Perlu ditinjau: model kompresor dan refrigerated dryer yang ditawarkan SMART. Halaman utama menyebut post-processing dryer; tipe dan spesifikasi pengering perlu dokumen model, bukan asumsi dari nama kategori.

### BLU-C

Tidak dibuat usulan profil tambahan. Pencarian belum menghasilkan sumber produsen yang dapat dipastikan cocok dengan identitas BLU-C di CMS. Cakupan oil boom, absorbent, skimmer, dispersant, dan spill kit hanya didukung PRD/data perusahaan saat ini. Diperlukan identitas principal, tautan resmi, dan katalog yang telah disetujui sebelum pemecahan teknologi atau pengayaan profil.
