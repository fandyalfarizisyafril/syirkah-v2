<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_catalog_preserves_combined_filters_totals_order_and_pagination(): void
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $products = collect(range(1, 14))->map(fn ($i) => Product::factory()->create([
            'category_id' => $category->id, 'brand_id' => $brand->id,
            'name' => 'Technical Needle '.$i, 'sort_order' => $i,
        ]));
        Product::factory()->create(['name' => 'Unrelated Equipment']);
        Product::factory()->create(['category_id' => $category->id, 'brand_id' => $brand->id, 'status' => 'draft']);
        $query = ['q' => 'Technical Needle', 'category' => $category->id, 'brand' => $brand->id];
        $first = $this->get(route('products.index', $query))->assertOk()->assertSee('14 produk')
            ->assertSee('Katalog Produk')->assertDontSee('Unrelated Equipment')
            ->assertViewHas('products', fn ($p) => $p->perPage() === 12 && $p->total() === 14
                && $p->pluck('id')->all() === $products->take(12)->pluck('id')->all());
        $nextUrl = $first->viewData('products')->nextPageUrl();
        parse_str(parse_url($nextUrl, PHP_URL_QUERY), $nextQuery);
        $this->assertEquals($query + ['page' => 2], $nextQuery);
        $this->get($nextUrl)->assertOk()->assertSee('14 produk')->assertSee('Halaman 2 dari 2')
            ->assertViewHas('products', fn ($p) => $p->pluck('id')->all() === $products->slice(12)->pluck('id')->all());
        $this->get(route('products.index'))->assertSee('15 produk');
    }

    public function test_catalog_keeps_cms_fields_media_fallback_and_empty_reset(): void
    {
        $product = Product::factory()->create(['name' => 'Technical Motor', 'image' => null]);
        $this->get(route('products.index'))->assertSee($product->name)->assertSee($product->short_description)
            ->assertSee('href="'.$product->url.'"', false)
            ->assertSee('src="'.asset('images/catalog-placeholder.svg').'"', false)
            ->assertSee('href="'.route('products.category', $product->category->slug).'"', false);
        $product->update(['name' => 'Updated CMS Motor', 'image' => 'catalog/existing-motor.png', 'short_description' => 'Updated CMS description']);
        $this->get(route('products.index'))->assertSee('Updated CMS Motor')->assertSee('Updated CMS description')
            ->assertSee('src="'.$product->image_url.'"', false)->assertDontSee('Technical Motor');
        $empty = $this->get(route('products.index', ['q' => 'NoMatch987654']))->assertOk()->assertSee('0 produk')
            ->assertSee('Tidak ada produk yang ditemukan.')->assertSee('Reset filter');
        $dom = new \DOMDocument;
        @$dom->loadHTML($empty->getContent());
        $xpath = new \DOMXPath($dom);
        $this->assertSame(route('products.index'), $xpath->query('//div[@class="empty-state"]//a')->item(0)->getAttribute('href'));
        $product->delete();
        $this->get(route('products.index'))->assertSee('0 produk')->assertDontSee('Updated CMS Motor');
    }

    public function test_new_catalog_wrapper_is_only_used_on_listing_not_shared_pages(): void
    {
        $product = Product::factory()->create();
        $this->get(route('products.index'))->assertSee('class="catalog-page"', false);
        foreach (['/', '/tentang-kami', '/brand', '/industri', '/admin/login', $product->url,
            route('products.category', $product->category->slug), route('brands.show', $product->brand->slug)] as $url) {
            $this->get($url)->assertOk()->assertDontSee('class="catalog-page"', false);
        }
    }
}
