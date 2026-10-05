<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LogicException;

class CategoryPreviewSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new LogicException('Data contoh hanya boleh dibuat di lingkungan local atau testing.');
        }

        $catalog = [
            ['electric-motors-generators', 'wolong', [
                ['induction-motor', 'Motor Induksi Tiga Fasa', 'Penggerak untuk kebutuhan pompa, kipas, dan peralatan industri.', ['Pompa industri', 'Kipas dan blower']],
                ['permanent-magnet-motor', 'Permanent Magnet Motor', 'Pilihan penggerak untuk aplikasi yang memerlukan pengaturan putaran motor.', ['Sistem penggerak', 'Peralatan proses']],
                ['industrial-generator', 'Generator Industri', 'Perangkat pembangkit untuk melengkapi kebutuhan sistem kelistrikan industri.', ['Sistem kelistrikan', 'Pembangkit daya']],
            ]],
            ['vibration-technology', 'oli', [
                ['external-vibrator', 'External Electric Vibrator', 'Penggetar eksternal untuk membantu aliran material pada hopper dan silo.', ['Hopper material', 'Silo penyimpanan']],
                ['screen-vibrator', 'Screen Vibrator', 'Penggerak getaran untuk proses penyaringan dan pemisahan material.', ['Penyaringan material', 'Pengolahan agregat']],
                ['internal-vibrator', 'Internal Concrete Vibrator', 'Peralatan penggetar internal untuk proses pemadatan beton.', ['Pengecoran beton', 'Konstruksi']],
            ]],
            ['chemical-metering-pump', 'qdos', [
                ['chemical-dosing-pump', 'Chemical Dosing Pump', 'Pompa penakar untuk penambahan bahan kimia pada proses industri.', ['Penakaran bahan kimia', 'Proses industri']],
                ['water-treatment-dosing-pump', 'Water Treatment Dosing Pump', 'Pompa dosing untuk kebutuhan pengolahan air dan air limbah.', ['Pengolahan air', 'Pengolahan air limbah']],
                ['process-metering-pump', 'Process Metering Pump', 'Pompa penakar untuk mendukung pengaturan suplai cairan proses.', ['Suplai cairan proses', 'Sistem dosing']],
            ]],
            ['hose-pump', 'bredel', [
                ['peristaltic-hose-pump', 'Peristaltic Hose Pump', 'Pompa selang untuk pemindahan fluida dalam proses produksi.', ['Transfer fluida', 'Proses produksi']],
                ['slurry-hose-pump', 'Slurry Hose Pump', 'Pilihan pompa selang untuk kebutuhan transfer slurry industri.', ['Transfer slurry', 'Pengolahan mineral']],
                ['sludge-transfer-pump', 'Sludge Transfer Hose Pump', 'Pompa selang untuk kebutuhan pemindahan lumpur proses.', ['Pemindahan lumpur', 'Pengolahan air limbah']],
            ]],
            ['industrial-hose', 'aflex', [
                ['ptfe-process-hose', 'PTFE Process Hose', 'Selang proses untuk menghubungkan jalur transfer fluida industri.', ['Jalur transfer fluida', 'Peralatan proses']],
                ['braided-ptfe-hose', 'Stainless Braided PTFE Hose', 'Selang PTFE berlapis anyaman baja untuk instalasi transfer fluida.', ['Instalasi perpipaan', 'Transfer fluida']],
                ['flanged-hose-assembly', 'Flanged Hose Assembly', 'Rakitan selang dengan sambungan flange untuk koneksi peralatan.', ['Koneksi peralatan', 'Jalur proses']],
            ]],
            ['air-compressor', 'tecbell', [
                ['rotary-screw-compressor', 'Rotary Screw Compressor', 'Kompresor untuk menunjang suplai udara bertekanan di fasilitas industri.', ['Udara bertekanan', 'Fasilitas produksi']],
                ['tank-mounted-compressor', 'Tank Mounted Compressor', 'Unit kompresor dengan tangki untuk kebutuhan sistem pneumatik.', ['Sistem pneumatik', 'Bengkel industri']],
                ['refrigerated-air-dryer', 'Refrigerated Air Dryer', 'Pengering udara untuk melengkapi sistem udara bertekanan.', ['Pengolahan udara tekan', 'Sistem kompresor']],
            ]],
            ['oil-spill-response-prevention', 'blu-c', [
                ['oil-containment-boom', 'Oil Containment Boom', 'Perlengkapan pembatas untuk mendukung penanganan tumpahan minyak.', ['Penanganan tumpahan', 'Area perairan']],
                ['oil-absorbent-pads', 'Oil Absorbent Pads', 'Lembaran penyerap untuk kebutuhan pembersihan tumpahan minyak.', ['Pembersihan tumpahan', 'Area operasional']],
                ['oil-spill-response-kit', 'Oil Spill Response Kit', 'Paket perlengkapan untuk kesiapan penanganan tumpahan di tempat kerja.', ['Kesiapan penanganan tumpahan', 'Fasilitas industri']],
            ]],
        ];

        DB::transaction(function () use ($catalog) {
            foreach ($catalog as [$categorySlug, $brandSlug, $examples]) {
                $category = Category::published()->where('slug', $categorySlug)->firstOrFail();
                $brand = Brand::published()->where('slug', $brandSlug)->firstOrFail();
                $isMotor = $categorySlug === 'electric-motors-generators';
                $sourceImage = $isMotor ? Product::where('slug', 'wolong-electric-motor')->value('image') : $category->image;
                $image = null;
                $disk = Storage::disk('public');

                // Keep preview media independent of future category or product uploads.
                if ($sourceImage && $disk->exists($sourceImage)) {
                    $image = 'catalog/demo/'.($isMotor ? 'motor' : $categorySlug).'-illustration.'.pathinfo($sourceImage, PATHINFO_EXTENSION);
                    if (! $disk->exists($image) && ! $disk->copy($sourceImage, $image)) {
                        throw new LogicException('Gagal menyalin ilustrasi produk contoh.');
                    }
                }

                foreach ($examples as $order => [$slug, $name, $description, $applications]) {
                    Product::firstOrCreate(['slug' => 'demo-'.$slug], [
                        'name' => $name.' (Contoh)',
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'short_description' => $description.' Data contoh; gambar ilustrasi sementara.',
                        'description' => 'Konten contoh untuk peninjauan katalog, bukan penawaran produk resmi. Nama, hubungan brand, aplikasi, dan gambar hanya ilustrasi. Ganti dengan informasi dan foto produk resmi sebelum publikasi produksi.',
                        'model_or_series' => 'DEMO-0'.($order + 1),
                        'applications' => $applications,
                        'image' => $image,
                        'image_alt' => 'Ilustrasi kategori sementara, bukan foto resmi '.$name,
                        'featured' => false,
                        'status' => 'published',
                        'sort_order' => 100 + $order,
                    ]);
                }
            }
        });
    }
}
