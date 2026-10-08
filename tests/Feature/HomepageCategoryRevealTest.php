<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageCategoryRevealTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_homepage_cards_receive_seven_distinct_decorative_hover_images(): void
    {
        $this->withoutVite();
        $this->seed(CatalogSeeder::class);
        $images = config('homepage-category-images');
        $this->assertCount(7, $images);
        $hashes = [];
        $response = $this->get('/')->assertOk();
        $this->assertSame(7, substr_count($response->getContent(), 'class="category-reveal"'));
        foreach ($images as $slug => $image) {
            $this->assertFileExists(public_path($image));
            $hashes[] = hash_file('sha256', public_path($image));
            $response->assertSee('srcset="'.asset($image).'"', false)
                ->assertSee('href="'.route('products.category', $slug).'"', false);
            $this->get('/produk/'.$slug)->assertOk()->assertDontSee('category-reveal');
        }
        $this->assertCount(7, array_unique($hashes));
        $this->get('/produk')->assertOk()->assertDontSee('category-reveal')->assertDontSee('has-reveal');
        $response->assertSee('media="(hover: hover) and (pointer: fine)"', false)
            ->assertSee('class="category-reveal" aria-hidden="true"', false);
    }

    public function test_unmapped_and_unpublished_categories_do_not_receive_unrelated_images(): void
    {
        $this->withoutVite();
        $visible = Category::factory()->create(['slug' => 'custom-category', 'image' => 'catalog/custom.png']);
        Category::factory()->create(['slug' => 'air-compressor', 'status' => 'draft']);
        $this->get('/')->assertOk()->assertSee($visible->name)
            ->assertDontSee('category-reveal')->assertDontSee('has-reveal')
            ->assertDontSee('src="'.$visible->image_url.'"', false);
    }
}
