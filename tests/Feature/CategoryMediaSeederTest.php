<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\CategoryMediaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryMediaSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_category_details_use_media_without_changing_products_or_listing_cards(): void
    {
        $this->withoutVite();
        Storage::fake('public');
        $this->seed(CatalogSeeder::class);
        $motor = Category::where('slug', 'electric-motors-generators')->firstOrFail();
        $motor->update(['image' => 'catalog/customer-motor.jpg', 'image_alt' => 'Foto motor milik perusahaan']);
        $before = $motor->fresh()->getAttributes();
        $products = Product::orderBy('id')->get()->toArray();

        $this->seed(CategoryMediaSeeder::class);
        $firstRun = Category::orderBy('id')->get()->toArray();
        $this->seed(CategoryMediaSeeder::class);

        $this->assertSame($firstRun, Category::orderBy('id')->get()->toArray());
        $this->assertSame($before, $motor->fresh()->getAttributes());
        $this->assertSame($products, Product::orderBy('id')->get()->toArray());
        $this->assertCount(6, Storage::disk('public')->allFiles('catalog/categories'));

        foreach (Category::all() as $category) {
            $this->assertNotEmpty($category->image);
            if ($category->id !== $motor->id) {
                Storage::disk('public')->assertExists($category->image);
                $this->assertSame(hash_file('sha256', public_path('images/categories/'.$category->slug.'.png')), hash('sha256', Storage::disk('public')->get($category->image)));
            }
            $this->get('/produk/'.$category->slug)->assertOk()
                ->assertSee('class="category-intro"', false)
                ->assertSee('src="'.$category->image_url.'"', false)
                ->assertSee($category->image_alt)
                ->assertSee('value="'.$category->id.'" selected', false)
                ->assertSee('0 produk');
            foreach (['/', '/produk'] as $url) {
                $this->get($url)->assertOk()->assertDontSee('src="'.$category->image_url.'"', false);
            }
        }
    }

    public function test_existing_uploads_and_draft_status_are_preserved(): void
    {
        Storage::fake('public');
        $this->seed(CatalogSeeder::class);
        $uploaded = Category::where('slug', 'vibration-technology')->firstOrFail();
        $uploaded->update(['image' => 'catalog/uploaded-vibrator.jpg', 'image_alt' => 'Foto resmi']);
        $draft = Category::where('slug', 'hose-pump')->firstOrFail();
        $draft->update(['status' => 'draft', 'image' => '']);

        $this->seed(CategoryMediaSeeder::class);

        $this->assertSame('catalog/uploaded-vibrator.jpg', $uploaded->fresh()->image);
        $this->assertSame('Foto resmi', $uploaded->fresh()->image_alt);
        $this->assertSame('draft', $draft->fresh()->status);
        Storage::disk('public')->assertExists($draft->fresh()->image);
        $this->get('/produk/'.$draft->slug)->assertNotFound();
    }
}
