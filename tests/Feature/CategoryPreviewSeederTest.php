<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\CategoryMediaSeeder;
use Database\Seeders\CategoryPreviewSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use LogicException;
use Tests\TestCase;

class CategoryPreviewSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_products_are_separate_editable_and_not_duplicated(): void
    {
        $this->withoutVite();
        Storage::fake('public');
        $this->seed(CatalogSeeder::class);
        $original = Product::where('slug', 'wolong-electric-motor')->firstOrFail();
        $original->update(['image' => 'catalog/original.jpg']);
        Storage::disk('public')->put($original->image, 'test-image');
        $before = $original->fresh()->getAttributes();

        $this->seed(CategoryPreviewSeeder::class);
        $demo = Product::where('slug', 'demo-induction-motor')->firstOrFail();
        $demo->update(['name' => 'Edited preview', 'status' => 'draft']);
        $this->seed(CategoryPreviewSeeder::class);

        $this->assertDatabaseCount('products', 28);
        $this->assertSame($before, $original->fresh()->getAttributes());
        $this->assertSame('Edited preview', $demo->fresh()->name);
        $this->assertSame('draft', $demo->fresh()->status);
        $this->assertNotSame($original->image, $demo->image);
        Storage::disk('public')->assertExists([$original->image, $demo->image]);
        $this->get('/produk/electric-motors-generators')->assertOk()
            ->assertSee('2 produk')->assertSee('Permanent Magnet Motor (Contoh)')
            ->assertDontSee('Edited preview')->assertDontSee('Produk belum tersedia.');
        $visible = Product::where('slug', 'demo-industrial-generator')->firstOrFail();
        $this->get($visible->url)->assertOk()->assertSee('bukan penawaran produk resmi');
    }

    public function test_preview_can_use_placeholder_when_no_product_photo_exists(): void
    {
        Storage::fake('public');
        $this->seed(CatalogSeeder::class);
        $this->seed(CategoryPreviewSeeder::class);
        $this->assertSame(21, Product::published()->count());
        $this->assertNull(Product::where('slug', 'demo-induction-motor')->firstOrFail()->image);
    }

    public function test_preview_seeder_refuses_production(): void
    {
        $this->app->instance('env', 'production');
        $this->expectException(LogicException::class);
        $this->seed(CategoryPreviewSeeder::class);
    }

    public function test_each_category_has_three_preview_products_with_independent_media_and_working_filters(): void
    {
        $this->withoutVite();
        Storage::fake('public');
        $this->seed(CatalogSeeder::class);
        $this->seed(CategoryMediaSeeder::class);
        $originals = Product::orderBy('id')->get();
        $before = $originals->toArray();
        $categoriesBefore = Category::orderBy('id')->get()->toArray();

        $this->seed(CategoryPreviewSeeder::class);
        $this->seed(CategoryPreviewSeeder::class);

        $this->assertDatabaseCount('products', 28);
        $this->assertSame($before, Product::whereIn('id', $originals->modelKeys())->orderBy('id')->get()->toArray());
        $this->assertSame($categoriesBefore, Category::orderBy('id')->get()->toArray());
        foreach (Category::all() as $category) {
            $products = Product::published()->where('category_id', $category->id)->get();
            $this->assertCount(3, $products);
            $response = $this->get('/produk/'.$category->slug)->assertOk()
                ->assertSee('3 produk')->assertDontSee('Produk belum tersedia.');
            $this->assertSame(3, substr_count($response->getContent(), 'class="product-card"'));
            foreach ($products as $product) {
                $response->assertSee($product->name);
                $this->assertStringEndsWith('(Contoh)', $product->name);
                if ($category->image) {
                    $this->assertNotSame($category->image, $product->image);
                    Storage::disk('public')->assertExists($product->image);
                    $this->assertSame(Storage::disk('public')->get($category->image), Storage::disk('public')->get($product->image));
                }
                $this->get($product->url)->assertOk()->assertSee('bukan penawaran produk resmi');
                $this->get('/produk?'.http_build_query(['q' => $product->name, 'category' => $category->id, 'brand' => $product->brand_id]))
                    ->assertOk()->assertViewHas('products', fn ($results) => $results->total() === 1 && $results->first()->id === $product->id);
            }
        }
        $this->get('/produk')->assertOk()->assertViewHas('products', fn ($results) => $results->total() === 21 && $results->count() === 12);
        $this->get('/produk?page=2')->assertOk()->assertViewHas('products', fn ($results) => $results->count() === 9);
    }
}
