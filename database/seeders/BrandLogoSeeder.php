<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class BrandLogoSeeder extends Seeder
{
    public function run(): void
    {
        $logos = json_decode(file_get_contents(database_path('data/brand-logos.json')), true, 512, JSON_THROW_ON_ERROR);
        $disk = Storage::disk('public');

        DB::transaction(function () use ($logos, $disk) {
            foreach ($logos as $logo) {
                $brand = Brand::where('slug', $logo['slug'])->lockForUpdate()->first();
                // Use existing CMS records and never replace administrator uploads.
                if (! $brand || filled($brand->image)) {
                    continue;
                }

                $source = public_path('images/brands/'.$logo['file']);
                $path = 'catalog/brand-logos/'.$logo['file'];
                if (! is_file($source)) {
                    throw new RuntimeException('Logo brand tidak ditemukan: '.$source);
                }
                if (! $disk->exists($path) && ! $disk->put($path, file_get_contents($source))) {
                    throw new RuntimeException('Gagal menyimpan logo brand: '.$logo['slug']);
                }

                $brand->update(['image' => $path, 'image_alt' => $brand->image_alt ?: $brand->name]);
            }
        });
    }
}
