# Slideshow Hero Beranda

Tanggal: 2026-10-08. Perubahan hanya pada gambar latar hero. Sesuai revisi lanjutan, slideshow berjalan otomatis tanpa tombol kontrol dan indikator, termasuk saat hover atau fokus pada CTA. Preferensi reduced motion tetap dihormati dengan gambar statis. Headline, CTA, ukuran hero, kategori, dan katalog tidak diubah.

## Aset

1. `public/images/industrial.jpg`: foto ilustrasi industri sebelumnya; asal tercatat di `implementation-artomoro.md`.
2. `public/images/hero-pumps.webp`: ilustrasi instalasi pompa dan motor industri, 1672 x 941, sekitar 310 KiB.
3. `public/images/hero-compressors.webp`: ilustrasi kompresor, tangki, dan pengering udara, 1672 x 941, sekitar 223 KiB.

Dua gambar baru dibuat dengan tool imagegen bawaan (mode generate, tanpa referensi), kemudian dikonversi dari PNG ke WebP kualitas 88% tanpa mengubah komposisi. Total transfer keduanya sekitar 533 KiB, dibandingkan PNG sekitar 4.7 MiB. Ilustrasi ini bukan foto fasilitas, proyek, produk/model resmi, atau bukti hubungan brand perusahaan. Ganti dengan foto yang disetujui perusahaan bila tersedia.

## Prompt Pompa

```text
Use case: photorealistic-natural. Wide 16:9 industrial website background photograph-style illustration, clean modern industrial pump hall, several blue horizontal electric motors coupled to centrifugal pumps and stainless steel pipes arranged along the right half of the image, clear realistic equipment with believable piping, bright roof daylight, neutral gray concrete floor, left third open quiet factory aisle for overlay text. Eye-level wide architectural photography, equipment fully readable, restrained clean professional engineering context. No people, logos, signage, labels, lettering, watermarks. Not a specific real company facility. No text baked into image. No blur, no artificial vignette.
```

## Prompt Kompresor

```text
Use case: photorealistic-natural. Wide 16:9 industrial website background photograph-style illustration, a modern clean compressed-air utility room, two teal and silver rotary screw compressor cabinets, vertical silver air receiver vessels and a separate refrigerated dryer, neatly routed compressed-air pipes, main equipment grouped in center-right and right side. Left third a quiet gray aisle and simple wall allowing white overlay website text. Bright soft daylight through high industrial windows, concrete floor, sharp readable realistic generic equipment, eye-level wide architectural photography. No people, logos, text, signage, labels, watermarks. Not an actual identifiable company facility. No blur, no artificial vignette.
```

## Pemeliharaan

- Daftar gambar dan alt text: `resources/views/public/home.blade.php`.
- Interval 6000 ms serta perilaku autoplay: `resources/js/hero-carousel.js`.
- Fade 900 ms: `resources/css/app.css`.
- Saat menambah slide, perbarui pengujian jumlah dan urutan gambar.
- Jalankan `npm run build` setelah perubahan JavaScript/CSS.
