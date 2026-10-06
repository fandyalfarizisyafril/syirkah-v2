<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class ProductIllustrationCatalog
{
    public static function entries(): array
    {
        $entries = json_decode(file_get_contents(database_path('data/product-illustrations.json')), true, flags: JSON_THROW_ON_ERROR);
        $hashes = [];
        foreach ($entries as $entry) {
            $file = public_path($entry['asset']);
            if (! is_file($file) || ! getimagesize($file)) {
                throw new RuntimeException('Gambar produk tidak tersedia: '.$entry['slug']);
            }
            $hash = hash_file('sha256', $file);
            if (isset($hashes[$hash])) {
                throw new RuntimeException('Gambar produk duplikat: '.$entry['slug']);
            }
            $hashes[$hash] = true;
        }

        return $entries;
    }

    public static function attributes(array $entry): array
    {
        $path = 'catalog/product-illustrations/'.basename($entry['asset']);
        $disk = Storage::disk('public');
        $contents = file_get_contents(public_path($entry['asset']));
        if ($disk->exists($path)) {
            if (hash('sha256', $disk->get($path)) !== hash('sha256', $contents)) {
                throw new RuntimeException('File tujuan berbeda; tidak ditimpa: '.$path);
            }
        } elseif (! $disk->put($path, $contents)) {
            throw new RuntimeException('Gagal menyimpan gambar: '.$path);
        }

        return [
            'name' => $entry['name'], 'short_description' => $entry['short_description'],
            'description' => $entry['description'], 'applications' => $entry['applications'],
            'model_or_series' => null, 'image' => $path, 'image_alt' => $entry['image_alt'],
        ];
    }
}
