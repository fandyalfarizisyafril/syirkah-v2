# Koreksi Gambar dan Data Produk

Tanggal: 2026-10-07.

## Ruang Lingkup

28 produk diperiksa: 21 contoh published dan 7 draft. Setiap produk mendapat gambar utama berbeda, nama spesifik sesuai bentuk, deskripsi, dan alt text. Status, slug/URL, relasi brand/kategori, urutan, serta fitur website tidak diubah. Model DEMO dikosongkan karena bukan model resmi. Gambar tidak mengesahkan spesifikasi atau merek. Semua konten ini masih perlu validasi katalog resmi.

21 gambar dibuat menggunakan built-in image_gen, bukan CLI. Tujuh draft menggunakan gambar terurai motor unggahan pengguna dan enam ilustrasi kategori yang sudah tersedia; setiap gambar itu hanya dipakai satu produk. Aset produk disimpan di `public/images/products/` lalu disalin ke `storage/app/public/catalog/product-illustrations/`. Media kategori tidak diubah.

## Penerapan

- `php artisan db:seed --class=ProductIllustrationSeeder` mengoreksi record yang sudah ada, hanya local/testing; tidak membuat produk atau memublikasikan draft.
- Cadangan perubahan disimpan pada disk local, direktori `backups/product-illustrations/`, sebelum transaksi perubahan database.
- Seeder koreksi hanya sekali per produk. Penanda pada cadangan selesai mencegah pengulangan menimpa suntingan CMS. Simpan cadangan bersama database lokal.
- CategoryPreviewSeeder memakai manifest yang sama untuk membuat contoh baru, tanpa mengubah record yang sudah ada.
- Manifest: `database/data/product-illustrations.json`.

## Pemetaan Produk

| Slug Tetap | Nama Terkoreksi | Aset |
|---|---|---|
| demo-induction-motor | Motor Induksi Berkaki dengan Poros Horizontal (Contoh) | images/products/demo-induction-motor.png |
| demo-permanent-magnet-motor | Motor Sinkron Magnet Permanen Berflensa (Contoh) | images/products/demo-permanent-magnet-motor.png |
| demo-industrial-generator | Alternator Industri dengan Rumah Berventilasi (Contoh) | images/products/demo-industrial-generator.png |
| demo-external-vibrator | Motor Vibrator Eksternal Berkaki (Contoh) | images/products/demo-external-vibrator.png |
| demo-screen-vibrator | Unit Penggetar Ayakan dengan Pemberat Eksentrik (Contoh) | images/products/demo-screen-vibrator.png |
| demo-internal-vibrator | Vibrator Beton Internal dengan Selang Fleksibel (Contoh) | images/products/demo-internal-vibrator.png |
| demo-chemical-dosing-pump | Pompa Dosing Diafragma dengan Pengatur Putar (Contoh) | images/products/demo-chemical-dosing-pump.png |
| demo-water-treatment-dosing-pump | Pompa Dosing Digital dengan Kepala Peristaltik (Contoh) | images/products/demo-water-treatment-dosing-pump.png |
| demo-process-metering-pump | Pompa Metering Diafragma Bermotor (Contoh) | images/products/demo-process-metering-pump.png |
| demo-peristaltic-hose-pump | Pompa Selang Peristaltik dengan Tutup Transparan (Contoh) | images/products/demo-peristaltic-hose-pump.png |
| demo-slurry-hose-pump | Pompa Selang Slurry dengan Sambungan Flange (Contoh) | images/products/demo-slurry-hose-pump.png |
| demo-sludge-transfer-pump | Pompa Selang Lumpur pada Troli (Contoh) | images/products/demo-sludge-transfer-pump.png |
| demo-ptfe-process-hose | Selang PTFE Bergelombang dengan Ujung Ulir (Contoh) | images/products/demo-ptfe-process-hose.png |
| demo-braided-ptfe-hose | Selang PTFE Anyaman Baja dengan Kopling Saniter (Contoh) | images/products/demo-braided-ptfe-hose.png |
| demo-flanged-hose-assembly | Selang Industri Berjaket Hitam dengan Flange (Contoh) | images/products/demo-flanged-hose-assembly.png |
| demo-rotary-screw-compressor | Kompresor Screw Kabinet Berdiri (Contoh) | images/products/demo-rotary-screw-compressor.png |
| demo-tank-mounted-compressor | Kompresor Piston dengan Tangki Horizontal (Contoh) | images/products/demo-tank-mounted-compressor.png |
| demo-refrigerated-air-dryer | Pengering Udara Refrigerasi Kabinet Kompak (Contoh) | images/products/demo-refrigerated-air-dryer.png |
| demo-oil-containment-boom | Oil Containment Boom dengan Rok Pemberat (Contoh) | images/products/demo-oil-containment-boom.png |
| demo-oil-absorbent-pads | Lembaran Absorben Minyak Berperforasi (Contoh) | images/products/demo-oil-absorbent-pads.png |
| demo-oil-spill-response-kit | Spill Kit Portabel dalam Tas (Contoh) | images/products/demo-oil-spill-response-kit.png |
| wolong-electric-motor | Motor Listrik dengan Tampilan Komponen Terurai (Ilustrasi) | images/products/wolong-electric-motor.jpg |
| oli-industrial-vibrator | Motor Vibrator dengan Penutup Pemberat Oranye (Ilustrasi) | images/products/oli-industrial-vibrator.png |
| qdos-chemical-dosing-pump | Pompa Dosing dengan Panel dan Kepala Diafragma (Ilustrasi) | images/products/qdos-chemical-dosing-pump.png |
| bredel-hose-pump | Pompa Selang dengan Rumah Bundar dan Gearmotor (Ilustrasi) | images/products/bredel-hose-pump.png |
| aflex-ptfe-hose | Selang PTFE Anyaman Baja dengan Flange (Ilustrasi) | images/products/aflex-ptfe-hose.png |
| tecbell-air-compressor | Paket Kompresor Bertangki dengan Pengering Udara (Ilustrasi) | images/products/tecbell-air-compressor.png |
| blu-c-oil-spill-kit | Spill Kit Kontainer dengan Boom dan Absorben (Ilustrasi) | images/products/blu-c-oil-spill-kit.png |

## Prompt Final

### demo-induction-motor

```text
Use case: product-mockup. Create a dedicated catalog illustration. A single dark green foot-mounted industrial induction electric motor, horizontal silver output shaft pointing front-left, ribbed cast iron housing, top rectangular terminal box, rear fan cover. No other equipment. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-permanent-magnet-motor

```text
Use case: product-mockup. Create a dedicated catalog illustration. A single compact permanent-magnet synchronous motor, smooth silver cylindrical casing with black rear cap, square front mounting flange, short keyed output shaft, two electrical connectors on top. Show whole exterior, no cutaway. Clearly distinct from a ribbed foot-mounted induction motor. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-industrial-generator

```text
Use case: product-mockup. Create a dedicated catalog illustration. A standalone industrial brushless alternator generator head, long cream-colored cylindrical housing, prominent dark ventilation grilles at both ends, large rectangular terminal enclosure on top, circular drive coupling flange and mounting feet. No engine, no electric motor fan cover. Not a complete genset. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-external-vibrator

```text
Use case: product-mockup. Create a dedicated catalog illustration. A single industrial external electric vibration motor, red heavy rounded eccentric-weight covers at both ends, compact black ribbed center, four strong bolted mounting feet, small top junction box. No hopper. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-screen-vibrator

```text
Use case: product-mockup. Create a dedicated catalog illustration. A single mechanical twin-shaft vibrating screen exciter unit, yellow rectangular heavy cast gearbox housing on mounting base, two parallel horizontal shafts with large visible dark eccentric counterweight discs at the ends. No electric motor, no actual screen. Mechanically plausible industrial catalog visualization. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-internal-vibrator

```text
Use case: product-mockup. Create a dedicated catalog illustration. One complete portable concrete immersion vibrator set: small orange electric drive unit with carry handle on floor, long black flexible drive hose coiled in a single loop connected to a slender stainless steel cylindrical poker head in foreground. Make poker clearly visible. No concrete. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-chemical-dosing-pump

```text
Use case: product-mockup. Create a dedicated catalog illustration. A single compact wall-mount solenoid diaphragm chemical metering pump, yellow rectangular casing, black rotary adjustment dial, translucent round pump head on front, small top and bottom tube fittings. No screen, no loose hoses. Clearly visible diaphragm head. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-water-treatment-dosing-pump

```text
Use case: product-mockup. Create a dedicated catalog illustration. A single small desktop digital peristaltic chemical dosing pump, white and teal control body with blank dark display and three plain buttons, round transparent pump head prominently on front showing small rollers and U-shaped flexible tube, two hose tails. No text. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-process-metering-pump

```text
Use case: product-mockup. Create a dedicated catalog illustration. One industrial motor-driven diaphragm metering pump: vertical black ribbed electric motor atop blue gearbox, horizontal stainless-steel circular bolted diaphragm pump head projecting to the right, vertical inlet and outlet pipe fittings at head, compact base. No tank. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-peristaltic-hose-pump

```text
Use case: product-mockup. Create a dedicated catalog illustration. One compact peristaltic hose pump, teal round casing with transparent circular front cover visibly exposing two rollers and a thick U-shaped internal hose, two short hose connections exiting on right, small silver horizontal gearmotor behind and steel base. No tank. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-slurry-hose-pump

```text
Use case: product-mockup. Create a dedicated catalog illustration. One heavy-duty industrial peristaltic slurry hose pump, large orange circular cast-metal casing with opaque bolted front cover, two large black flanged inlet/outlet ports on left, dark gray horizontal gearmotor behind on robust skid base. No transparent cutaway, no fluid. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-sludge-transfer-pump

```text
Use case: product-mockup. Create a dedicated catalog illustration. One mobile peristaltic sludge-transfer hose pump on a stainless-steel four-wheel trolley with push handle, blue round pump casing and black motor, two large hose couplings clearly visible, short black reinforced hose attached. Distinct wheeled unit, no tank, no puddles. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-ptfe-process-hose

```text
Use case: product-mockup. Create a dedicated catalog illustration. One white translucent corrugated PTFE process hose formed into a shallow U shape, visible corrugations, stainless threaded male fittings at both ends. No metal braid, no flanges. Full hose visible. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-braided-ptfe-hose

```text
Use case: product-mockup. Create a dedicated catalog illustration. One stainless steel braided flexible PTFE hose in a loose S curve, two clearly visible sanitary tri-clamp ferrule ends, white inner liner visible in near end. Metallic woven braid crisp and detailed. No bolt-hole flanges, no threaded ends. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-flanged-hose-assembly

```text
Use case: product-mockup. Create a dedicated catalog illustration. One black rubber-jacketed industrial flexible hose bent in a broad U, large silver circular bolt-hole flange at each end, black smooth exterior with slight reinforcement rings. No steel braid, no small threaded fittings. Full hose visible. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-rotary-screw-compressor

```text
Use case: product-mockup. Create a dedicated catalog illustration. One standalone floor-standing rotary screw air compressor cabinet, cobalt blue and charcoal rectangular enclosure on short feet, front blank control screen, side ventilation grilles. No receiver tank, no attached dryer, no external piston heads. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-tank-mounted-compressor

```text
Use case: product-mockup. Create a dedicated catalog illustration. One belt-driven reciprocating piston air compressor on a red horizontal receiver tank, two visible finned silver cylinders in V arrangement, black electric motor, black mesh belt guard, pressure gauge, two wheels and stabilizing feet. No enclosure cabinet. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-refrigerated-air-dryer

```text
Use case: product-mockup. Create a dedicated catalog illustration. One standalone refrigerated compressed-air dryer, compact white upright rectangular cabinet with green top edge, large black front condenser grille, small blank digital controller, two brass compressed-air pipe ports emerging above rear edge. No receiver tank, no compressor motor, no hoses. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-oil-containment-boom

```text
Use case: product-mockup. Create a dedicated catalog illustration. One yellow floating oil containment boom laid in a loose wide curve on a studio floor: segmented yellow cylindrical floats along top, hanging black flexible skirt visibly laid out below, metal ballast chain along bottom and aluminum end connectors. No spill kit, no pads, no water. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-oil-absorbent-pads

```text
Use case: product-mockup. Create a dedicated catalog illustration. A neat stack of white oil-absorbent polypropylene pads, one single pad lifted and folded back to reveal dimpled texture and central perforation line. Only absorbent sheet pads, no rolls, no container, no gloves. Pale gray seamless studio backdrop to distinguish white pads. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, white or very pale gray background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

### demo-oil-spill-response-kit

```text
Use case: product-mockup. Create a dedicated catalog illustration. One portable oil spill response kit: open yellow zippered duffel bag, neatly arranged white absorbent pads and two short white absorbent socks, dark protective gloves and folded black disposal bags. Compact carry-bag kit, no wheelie bin, no containment boom, no text. Landscape 3:2 clean photorealistic studio illustration, centered whole equipment, generous margins, pure white background, soft contact shadow. No text, no logo, no brand, no model number, no watermark. This is unbranded illustrative equipment, not a claimed real manufacturer's model.
```

