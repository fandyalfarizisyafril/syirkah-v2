<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDetailPresentationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_product_sections_preserve_cms_data_gallery_and_inquiry_context(): void
    {
        $product = Product::factory()->create([
            'name' => 'Technical Product (Contoh)', 'short_description' => 'Short summary.',
            'description' => 'Detailed CMS description.', 'image' => 'catalog/main.png',
            'gallery' => ['catalog/main.png', 'catalog/second.png'], 'model_or_series' => 'Series A',
            'applications' => ['Application A'], 'benefits' => ['Benefit A'],
            'specifications' => [['label' => 'Power', 'value' => '15', 'unit' => 'kW']],
            'datasheet_file' => 'catalog/datasheet.pdf',
        ]);
        $related = Product::factory()->create(['category_id' => $product->category_id]);
        $draft = Product::factory()->create(['category_id' => $product->category_id, 'status' => 'draft']);
        $response = $this->get($product->url)->assertOk()
            ->assertSeeInOrder(['01 / PRODUCT OVERVIEW', '02 / TECHNICAL INFORMATION', '03 / APPLICATIONS', '04 / PRODUCT BENEFITS'])
            ->assertSee('Short summary.')->assertSee('Detailed CMS description.')->assertSee('Series A')
            ->assertSee('15 kW')->assertSee('Application A')->assertSee('Benefit A')
            ->assertSee('href="'.route('contact', ['product' => $product->id]).'"', false)
            ->assertSee('href="'.asset('storage/catalog/datasheet.pdf').'"', false)
            ->assertSee($related->name)->assertDontSee($draft->name);
        $this->assertSame(2, substr_count($response->getContent(), 'data-gallery-src='));
        $this->assertSame(1, substr_count($response->getContent(), 'aria-pressed="true"'));
    }

    public function test_sparse_data_has_no_duplicate_description_empty_specs_or_gallery(): void
    {
        $product = Product::factory()->create([
            'short_description' => 'Only one description.', 'description' => 'Only one description.',
            'image' => null, 'gallery' => null, 'specifications' => null,
            'applications' => ['Available application'], 'benefits' => null,
        ]);
        $this->get($product->url)->assertOk()->assertSee('01 / APPLICATIONS')
            ->assertDontSee('PRODUCT OVERVIEW')->assertDontSee('TECHNICAL INFORMATION')
            ->assertDontSee('data-gallery-src', false)->assertDontSee('RELATED PRODUCTS')
            ->assertSee('src="'.asset('images/catalog-placeholder.svg').'"', false);
        $product->update(['short_description' => '', 'description' => 'Long description only.', 'applications' => null]);
        $this->get($product->url)->assertOk()->assertSee('01 / PRODUCT OVERVIEW')
            ->assertSee('Long description only.')->assertDontSee('/ APPLICATIONS');
    }

    public function test_category_directory_preserves_filters_pagination_and_real_relations(): void
    {
        $category = Category::factory()->create();
        $brand = Brand::factory()->create(['image' => 'catalog/brand-logo.png', 'image_alt' => 'Official brand logo']);
        Product::factory()->count(13)->create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name' => 'Matching motor']);
        $other = Product::factory()->create(['name' => 'Other category product']);
        Product::factory()->create(['category_id' => $category->id, 'status' => 'draft', 'name' => 'Hidden draft']);
        $response = $this->get(route('products.category', ['category' => $category->slug, 'q' => 'Matching', 'brand' => $brand->id]))
            ->assertOk()->assertSee('13 produk tersedia')->assertSee('Produk dalam Kategori Ini')
            ->assertSee('value="'.$category->id.'" selected', false)
            ->assertSee('action="'.route('products.index').'"', false)
            ->assertSee('href="'.route('brands.show', $brand->slug).'"', false)
            ->assertSee('src="'.$brand->image_url.'"', false)->assertSee('alt="Official brand logo"', false)
            ->assertDontSee($other->name)->assertDontSee('Hidden draft')
            ->assertViewHas('products', fn ($p) => $p->total() === 13 && $p->perPage() === 12);
        $next = $response->viewData('products')->nextPageUrl();
        $this->assertStringContainsString('q=Matching', $next);
        $this->assertStringContainsString('brand='.$brand->id, $next);
        $this->get($next)->assertOk()->assertSee('Halaman 2 dari 2')->assertSee('13 produk tersedia');
        $this->get(route('products.category', ['category' => $category->slug, 'q' => 'NoMatchingProduct']))
            ->assertOk()->assertSee('0 produk tersedia')->assertSee('Tidak ada produk yang ditemukan.');
    }

    public function test_category_links_exclude_current_and_detail_wrapper_is_isolated(): void
    {
        $category = Category::factory()->create();
        Category::factory()->count(5)->create();
        $response = $this->get(route('products.category', $category->slug))->assertOk();
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $links = $xpath->query('//section[contains(@class,"category-other-links")]//a');
        $this->assertSame(3, $links->length);
        foreach ($links as $link) {
            $this->assertNotSame(route('products.category', $category->slug), $link->getAttribute('href'));
        }
        foreach (['/', '/produk', '/brand', '/tentang-kami', '/publikasi/perusahaan', '/publikasi/media', '/admin/login'] as $path) {
            $this->get($path)->assertOk()->assertDontSee('technical-detail-page');
        }
    }
}
