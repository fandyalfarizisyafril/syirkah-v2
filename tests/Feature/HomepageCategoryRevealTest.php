<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageCategoryRevealTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_homepage_cards_receive_seven_distinct_carousel_images(): void
    {
        $this->withoutVite();
        $this->seed(CatalogSeeder::class);
        $images = config('homepage-category-images');
        $this->assertCount(7, $images);
        $hashes = [];
        $response = $this->get('/')->assertOk();
        $this->assertSame(7, substr_count($response->getContent(), 'class="home-category-card"'));
        foreach ($images as $slug => $image) {
            $this->assertFileExists(public_path($image));
            $hashes[] = hash_file('sha256', public_path($image));
            $response->assertSee('src="'.asset($image).'"', false)
                ->assertSee('href="'.route('products.category', $slug).'"', false);
            $this->get('/produk/'.$slug)->assertOk()->assertDontSee('home-category-carousel');
        }
        $this->assertCount(7, array_unique($hashes));
        $this->get('/produk')->assertOk()->assertDontSee('home-category-carousel');
        $response->assertDontSee('class="category-reveal"', false)->assertDontSee('has-reveal')
            ->assertSee('data-category-next', false)->assertSee('data-category-info', false);
        $response->assertSeeInOrder(Category::published()->ordered()->pluck('name')->all());
    }

    public function test_new_categories_use_cms_images_and_unpublished_categories_stay_hidden(): void
    {
        $this->withoutVite();
        $visible = Category::factory()->create(['slug' => 'custom-category', 'image' => 'catalog/custom.png']);
        $draft = Category::factory()->create(['slug' => 'air-compressor', 'status' => 'draft', 'image' => 'catalog/private.png']);
        $this->get('/')->assertOk()->assertSee($visible->name)
            ->assertSee('class="home-category-image"', false)
            ->assertSee('src="'.$visible->image_url.'"', false)
            ->assertDontSee('data-category="'.$draft->slug.'"', false)->assertDontSee($draft->image_url);
    }

    public function test_cms_replacement_overrides_legacy_image_and_survives_slug_changes(): void
    {
        $this->withoutVite();
        $category = Category::factory()->create(['slug' => 'air-compressor', 'image' => 'catalog/upload.png', 'image_alt' => 'Foto kompresor perusahaan']);
        $this->get('/')->assertOk()->assertSee('src="'.$category->image_url.'"', false)
            ->assertSee('alt="Foto kompresor perusahaan"', false)
            ->assertDontSee('src="'.asset(config('homepage-category-images.air-compressor')).'"', false);
        $category->update(['image' => 'catalog/replacement.png', 'slug' => 'custom-compressor']);
        $this->get('/')->assertOk()->assertSee('src="'.$category->image_url.'"', false)
            ->assertSee('href="'.route('products.category', $category->slug).'"', false)
            ->assertDontSee('src="'.asset('storage/catalog/upload.png').'"', false);
    }

    public function test_category_without_upload_or_legacy_image_uses_existing_placeholder(): void
    {
        $this->withoutVite();
        $category = Category::factory()->create(['slug' => 'new-category', 'image' => null]);
        $this->get('/')->assertOk()->assertSee($category->name)
            ->assertSee('src="'.asset('images/catalog-placeholder.svg').'"', false)
            ->assertSee('alt="Gambar kategori belum tersedia"', false);
    }
}
