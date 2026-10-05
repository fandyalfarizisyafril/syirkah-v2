<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class CategoryMediaSeeder extends Seeder
{
    public function run(): void
    {
        $illustrations = [
            'vibration-technology' => 'Ilustrasi AI motor vibrator industri dengan pemberat eksentrik',
            'chemical-metering-pump' => 'Ilustrasi AI pompa penakar bahan kimia dengan kontrol digital',
            'hose-pump' => 'Ilustrasi AI pompa selang peristaltik dengan motor penggerak',
            'industrial-hose' => 'Ilustrasi AI selang industri berlapis anyaman baja dengan sambungan flange',
            'air-compressor' => 'Ilustrasi AI kompresor udara industri dengan tangki dan pengering',
            'oil-spill-response-prevention' => 'Ilustrasi AI containment boom, absorbent, dan perlengkapan spill kit',
        ];
        $disk = Storage::disk('public');

        DB::transaction(function () use ($illustrations, $disk) {
            foreach ($illustrations as $slug => $alt) {
                $category = Category::where('slug', $slug)->lockForUpdate()->first();
                if (! $category || $category->image) {
                    continue;
                }

                $source = public_path('images/categories/'.$slug.'.png');
                if (! is_file($source)) {
                    throw new RuntimeException('Ilustrasi kategori tidak ditemukan: '.$source);
                }

                $path = 'catalog/categories/'.$slug.'-illustration.png';
                if (! $disk->exists($path) && ! $disk->put($path, file_get_contents($source))) {
                    throw new RuntimeException('Gagal menyimpan ilustrasi kategori: '.$slug);
                }

                // Only fill missing media; never replace an image uploaded through the CMS.
                $category->update(['image' => $path, 'image_alt' => $alt]);
            }
        });
    }
}
