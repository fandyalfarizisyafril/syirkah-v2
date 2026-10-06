<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\CategoryPreviewSeeder;
use Database\Seeders\ProductIllustrationCatalog;
use Database\Seeders\ProductIllustrationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use LogicException;
use Tests\TestCase;

class ProductIllustrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_correction_matches_all_28_records_to_unique_assets_and_preserves_structure(): void
    {
        $this->withoutVite();
        Storage::fake('public');
        Storage::fake('local');
        $this->seed([CatalogSeeder::class, CategoryPreviewSeeder::class]);
        Product::query()->update(['image' => 'catalog/old-shared.png']);
        $before = Product::orderBy('id')->get()->keyBy('slug');
        $categories = Category::all()->toArray();

        $this->seed(ProductIllustrationSeeder::class);

        $hashes = [];
        foreach (ProductIllustrationCatalog::entries() as $entry) {
            $product = Product::where('slug', $entry['slug'])->firstOrFail();
            $this->assertSame($entry['name'], $product->name);
            $this->assertSame($entry['short_description'], $product->short_description);
            $this->assertSame($entry['description'], $product->description);
            $this->assertSame($entry['image_alt'], $product->image_alt);
            $this->assertSame($entry['category'], $product->category->slug);
            $this->assertNull($product->model_or_series);
            foreach (['id', 'slug', 'category_id', 'brand_id', 'status', 'sort_order', 'featured', 'gallery', 'datasheet_file', 'specifications'] as $field) {
                $this->assertSame($before[$product->slug]->getAttribute($field), $product->getAttribute($field));
            }
            $hash = hash('sha256', Storage::disk('public')->get($product->image));
            $this->assertSame(hash_file('sha256', public_path($entry['asset'])), $hash);
            $hashes[] = $hash;
            if ($product->status === 'published') {
                $this->get($product->url)->assertOk()->assertSee($product->name)
                    ->assertSee('src="'.$product->image_url.'"', false)->assertSee($product->short_description);
            } else {
                $this->get($product->url)->assertNotFound();
            }
        }
        $this->assertCount(28, array_unique($hashes));
        $this->assertDatabaseCount('products', 28);
        $this->assertSame(21, Product::published()->count());
        $this->assertSame($categories, Category::all()->toArray());
        $backups = array_values(array_filter(Storage::disk('local')->files('backups/product-illustrations'), fn ($path) => ! str_ends_with($path, '/completed.json')));
        $this->assertCount(1, $backups);
        $snapshot = json_decode(Storage::disk('local')->get($backups[0]), true);
        $this->assertCount(28, $snapshot);
        $this->assertSame('catalog/old-shared.png', $snapshot[0]['before']['image']);

        $edited = Product::where('slug', 'demo-induction-motor')->firstOrFail();
        $edited->update(['name' => 'Nama resmi dari CMS', 'image' => 'catalog/new-upload.png']);
        $this->seed(ProductIllustrationSeeder::class);
        $this->assertSame('Nama resmi dari CMS', $edited->fresh()->name);
        $this->assertSame('catalog/new-upload.png', $edited->fresh()->image);
    }

    public function test_correction_refuses_production(): void
    {
        $this->app->instance('env', 'production');
        $this->expectException(LogicException::class);
        $this->seed(ProductIllustrationSeeder::class);
    }
}
