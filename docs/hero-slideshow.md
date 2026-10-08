# Hero Bidang Fokus SMART

Revisi 2026-10-08: menggantikan slideshow latar tiga gambar dengan satu hero data-driven berisi lima bidang fokus. Pola navigasi mengikuti referensi interaksi [PETRONAS](https://www.petronas.com/), dengan warna dan komposisi SMART. Bukan salinan desain atau aset PETRONAS.

## Struktur

- Lima identitas, urutan, konten awal, dan posisi gambar tetap di `config/focus-slides.php`. Suntingan Admin disimpan di tabel `hero_slides`; `HeroSlide::slides()` menggabungkan data ini sebagai satu sumber konten untuk homepage dan form Admin.
- Komponen reusable: `resources/views/components/hero-carousel.blade.php`; dipanggil dari Beranda dengan data konfigurasi melalui PublicController.
- Urutan: Engineering, Mechanical, Electrical, Instrumentation, Oil Spill Response & Prevention.
- Satu H1 dengan lapisan teks per slide. Lapisan tidak aktif disembunyikan secara visual dan dari accessibility tree. Seluruh lapisan tetap mengisi track grid yang sama agar tinggi konten stabil.
- Bidang Fokus menjadi satu navigasi tombol di dasar hero. Bar putih lama, CTA hero, caption, panah, dan dots tidak ditampilkan.
- Navbar termasuk Request Inquiry, top bar, seluruh section setelah hero, routing, dan footer tidak diubah.

## Perilaku

- Autoplay setiap 5000 ms, infinite loop. Klik manual mengubah gambar, teks, serta aria-current secara bersamaan setelah gambar siap; timer dihitung ulang dari slide terpilih.
- Crossfade 900 ms mempertahankan gambar keluar di bawah gambar masuk hingga transisi selesai. Animasi copy 450-500 ms memakai pergeseran kecil, tanpa zoom/parallax.
- Gambar pertama dipreload dan fetchpriority high. Setelah halaman load, hanya gambar berikutnya dipreload. Gambar harus selesai decode sebelum commit; kegagalan decode menonaktifkan tombol terkait dan slide dilewati. Klik cepat menggunakan request token agar respons gambar lama tidak menimpa pilihan terbaru.
- Timer berhenti ketika hero di luar viewport, tab tersembunyi, atau reduced motion aktif. Reduced motion tetap mengizinkan pemilihan manual tanpa animasi.
- Tab, Enter/Space, panah kiri/kanan, Home, dan End mendukung navigasi keyboard.
- Navigasi overflow horizontal pada tablet/mobile. Slide aktif digeser ke area terlihat menggunakan scroll pada navigasi saja, tanpa memindahkan halaman atau fokus keyboard.
- Tanpa JavaScript: Engineering statis, tanpa navigasi yang tidak berfungsi.
- Initializer mengembalikan fungsi cleanup yang membatalkan listener, timer, dan observer. Pagehide membersihkan instance saat halaman ditinggalkan; BFCache mempertahankan instance dan melanjutkan timer melalui pageshow.
- Tinggi mengikuti viewport dikurangi tinggi aktual header/top bar (ResizeObserver) dan sedikit ruang untuk memberi petunjuk section berikutnya. Minimum tinggi menjaga konten tidak bertumpuk pada viewport pendek.

## Aset

| Bidang | Aset | Keterangan |
| --- | --- | --- |
| Engineering | `public/images/industrial.jpg` | Reuse foto ilustrasi fasilitas industri sebelumnya, 1920 x 1280. Asal di implementation-artomoro.md. |
| Mechanical | `public/images/hero-pumps.webp` | Reuse ilustrasi instalasi pompa, 1672 x 941, sekitar 310 KiB. |
| Electrical | `public/images/hero/electrical.webp` | Switchgear industrial, 1672 x 941, sekitar 219 KiB. |
| Instrumentation | `public/images/hero/instrumentation.webp` | Transmitter, gauge, dan control valve, 1672 x 941, sekitar 270 KiB. |
| Oil Spill | `public/images/hero/oil-spill-response.webp` | Containment boom di pelabuhan, 1672 x 941, sekitar 224 KiB. |

Tiga aset tambahan dibuat dengan tool imagegen bawaan, mode generate tanpa referensi, kemudian dikonversi PNG ke WebP kualitas 85%. Semua ilustrasi AI adalah data demo, bukan foto fasilitas/proyek perusahaan, model resmi, atau bukti hubungan brand. Aset lama termasuk `hero-compressors.webp` dipertahankan meskipun tidak lagi dipakai di hero.

Inter variable di-host lokal pada `public/fonts/InterVariable.woff2`, hanya diterapkan pada hero. Sumber resmi: [Inter](https://github.com/rsms/inter/tree/master/docs/font-files); lisensi OFL tersimpan di `public/fonts/Inter-LICENSE.txt`. Font global tidak diubah.

## Prompt Electrical

```text
Use case: photorealistic-natural. Asset: 16:9 wide industrial corporate website hero photograph-style illustration. A pristine industrial power distribution room with a long row of tall closed grey metal switchgear cabinets and electrical control panels, clear breakers, small status lights and meters, overhead cable trays. Equipment grouped center-right, an open aisle at left for website text. Realistic large factory electrical infrastructure, not residential. Eye-level architectural photography, neutral daylight, accurate crisp metal detail, restrained corporate palette. No people, logos, readable text, watermarks, neon, dramatic darkness, artificial blur. Generic illustrative facility, not an identifiable real company site. Full-bleed image, no UI.
```

## Prompt Instrumentation

```text
Use case: photorealistic-natural. Asset: wide 16:9 industrial corporate homepage background. Realistic process instrumentation skid inside a modern industrial plant: stainless steel process pipes fitted with blue pressure transmitters, analog pressure gauges, industrial flow meters and a pneumatic control valve, neatly routed signal conduits. Main instruments clearly readable as physical equipment on right half, quiet factory aisle left for text overlay. Professional engineering photography, medium-wide eye-level view, sharp real metal textures, natural clean daylight. Not generic computer screens, not residential. No people, logos, readable brand names, watermarks, UI, dramatic blur or darkness. Generic illustrative installation, not an actual identifiable company facility.
```

## Prompt Oil Spill

```text
Use case: photorealistic-natural. Asset: wide 16:9 corporate industrial website hero photograph-style illustration. A calm professional oil spill preparedness exercise at a modern industrial harbor. Bright yellow floating containment boom deployed in a gentle curve on clean blue water around a small marine response workboat on the right, organized boom reel and equipment on a quay in right background, distant port infrastructure, no disaster or pollution. Left half open calm harbor water for text overlay. Main boom visibly recognizable across middle foreground. Natural daylight, realistic crisp documentary environmental response photography, balanced industrial corporate composition. No people in closeup, logos, brand text, watermarks, dramatic smoke, oil-covered animals, dramatic emergency, baked text or UI. Generic illustrative harbor, not a specific real company project.
```

## Prompt Aset Sebelumnya

### Pompa

```text
Use case: photorealistic-natural. Wide 16:9 industrial website background photograph-style illustration, clean modern industrial pump hall, several blue horizontal electric motors coupled to centrifugal pumps and stainless steel pipes arranged along the right half of the image, clear realistic equipment with believable piping, bright roof daylight, neutral gray concrete floor, left third open quiet factory aisle for overlay text. Eye-level wide architectural photography, equipment fully readable, restrained clean professional engineering context. No people, logos, signage, labels, lettering, watermarks. Not a specific real company facility. No text baked into image. No blur, no artificial vignette.
```

### Kompresor (disimpan, tidak dipakai)

```text
Use case: photorealistic-natural. Wide 16:9 industrial website background photograph-style illustration, a modern clean compressed-air utility room, two teal and silver rotary screw compressor cabinets, vertical silver air receiver vessels and a separate refrigerated dryer, neatly routed compressed-air pipes, main equipment grouped in center-right and right side. Left third a quiet gray aisle and simple wall allowing white overlay website text. Bright soft daylight through high industrial windows, concrete floor, sharp readable realistic generic equipment, eye-level wide architectural photography. No people, logos, text, signage, labels, watermarks. Not an actual identifiable company facility. No blur, no artificial vignette.
```



## Verifikasi dan Pemeliharaan

- Pengujian: `php artisan test`, `npm run build`, `npx playwright test tests/Browser/hero-carousel.spec.js`.
- Browser: seluruh slide pada lebar 1920, 1440, 768, 390, dan 320 px; autoplay, reset manual, keyboard, reduced motion, no-JS, respons lambat/gagal, preload, crossfade, dan timer saat tab tersembunyi.
- Snapshot markup sebelum/sesudah membuktikan top bar, navbar, section setelah hero, dan footer tidak berubah.
- Edit konten melalui **Admin > Hero Beranda > Edit**: label navigasi, eyebrow, headline baris 1/2, deskripsi, dan gambar. Headline baris kedua opsional. Setelah Simpan Hero, perubahan langsung dibaca saat homepage dimuat ulang, tanpa build atau clear cache.
- Akses mengikuti `manage-content`: Administrator dan Editor. Lima bidang dan urutannya tetap; tidak ada tambah/hapus slide, status, atau pengaturan autoplay di CMS.
- Upload JPG/PNG/WebP maksimal 4 MB, dimensi maksimal 8000 x 8000. File disimpan dengan nama acak pada disk public, direktori `hero/`. Membutuhkan storage link yang sudah digunakan katalog.
- Simpan teks tanpa upload mempertahankan gambar terakhir. Opsi Kembalikan gambar awal hanya mereset referensi gambar. File lama dipertahankan, mengikuti pola media katalog. Upload baru dibersihkan jika penyimpanan database gagal.
- Jika file unggahan hilang, background kembali ke aset awal tanpa kehilangan teks editan. GET halaman tidak membuat record database. Migration baru hanya menambah tabel `hero_slides`; katalog dan data produk tidak diubah.
- Deployment: jalankan `php artisan migrate --force` sebelum melayani request dengan kode baru. Tidak memerlukan seed untuk menampilkan lima slide awal.
- Interval: `resources/js/hero-carousel.js`. Styling scoped hero: `resources/css/app.css`. Jalankan build setelah perubahan JS/CSS.
