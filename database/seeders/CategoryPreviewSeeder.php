<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryPreviewSeeder extends Seeder
{
    public function run(): void
    {
        $entries = ProductIllustrationCatalog::entries();
        DB::transaction(function () use ($entries) {
            $order = 0;
            foreach ($entries as $entry) {
                if (! $entry['preview']) {
                    continue;
                }
                ProductIllustrationCatalog::ensureAsset($entry);
                $sortOrder = 100 + ($order++ % 3);
                if (Product::where('slug', $entry['slug'])->exists()) {
                    continue;
                }
                $category = Category::published()->where('slug', $entry['category'])->firstOrFail();
                $brand = Brand::published()->where('slug', $entry['brand'])->firstOrFail();
                Product::create(ProductIllustrationCatalog::attributes($entry) + [
                    'slug' => $entry['slug'], 'category_id' => $category->id, 'brand_id' => $brand->id,
                    'status' => 'published', 'featured' => false, 'sort_order' => $sortOrder,
                ]);
            }
        });
    }
}
