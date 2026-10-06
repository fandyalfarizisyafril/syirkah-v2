<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LogicException;
use RuntimeException;

class ProductIllustrationSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new LogicException('Koreksi ilustrasi hanya untuk local atau testing.');
        }

        $entries = ProductIllustrationCatalog::entries();
        $disk = Storage::disk('local');
        $directory = 'backups/product-illustrations';
        $ledgerPath = $directory.'/completed.json';
        $completed = $disk->exists($ledgerPath) ? json_decode($disk->get($ledgerPath), true, flags: JSON_THROW_ON_ERROR) : [];

        DB::transaction(function () use ($entries, $disk, $directory, &$completed) {
            $changes = [];
            foreach ($entries as $entry) {
                $product = Product::with('category')->where('slug', $entry['slug'])->lockForUpdate()->first();
                if (! $product) {
                    continue;
                }
                $key = $product->id.':'.$product->slug.':'.$product->created_at?->toISOString();
                if (isset($completed[$key])) {
                    continue;
                }
                if ($product->category->slug !== $entry['category']) {
                    throw new RuntimeException('Kategori berubah; tinjau produk: '.$product->slug);
                }
                $changes[] = ['product' => $product, 'before' => $product->getAttributes(), 'after' => ProductIllustrationCatalog::attributes($entry), 'key' => $key];
            }
            if (! $changes) {
                return;
            }

            $backup = $directory.'/'.Str::ulid().'.json';
            $snapshot = array_map(fn ($change) => ['before' => $change['before'], 'after' => $change['after']], $changes);
            if (! $disk->put($backup, json_encode($snapshot, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR))) {
                throw new RuntimeException('Cadangan gagal disimpan; koreksi dibatalkan.');
            }
            foreach ($changes as $change) {
                $change['product']->update($change['after']);
                $completed[$change['key']] = $backup;
            }
        });

        if (! $disk->put($ledgerPath, json_encode($completed, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR))) {
            throw new RuntimeException('Data telah dikoreksi tetapi penanda selesai gagal disimpan; jangan jalankan ulang sebelum diperiksa.');
        }
    }
}
