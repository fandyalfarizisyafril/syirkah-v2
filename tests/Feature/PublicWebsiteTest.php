<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Product;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_public_pages_render_and_catalog_seed_is_idempotent(): void
    {
        $this->seed(CatalogSeeder::class);
        $this->seed(CatalogSeeder::class);
        $this->assertDatabaseCount('categories', 7);
        $this->assertDatabaseCount('brands', 7);
        $this->assertDatabaseCount('products', 7);
        $this->assertSame(0, Product::published()->count());
        foreach (['/', '/tentang-kami', '/produk', '/brand', '/industri', '/kontak', '/privasi', '/search', '/sitemap.xml', '/admin/login'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_only_published_products_with_published_parents_are_public(): void
    {
        $visible = Product::factory()->create(['name' => 'Visible Motor']);
        $draft = Product::factory()->create(['status' => 'draft', 'name' => 'Secret Motor']);
        $hiddenCategory = Product::factory()->create(['category_id' => Category::factory()->create(['status' => 'draft']), 'name' => 'Private Category Motor']);
        $hiddenBrand = Product::factory()->create(['brand_id' => Brand::factory()->create(['status' => 'draft']), 'name' => 'Private Brand Motor']);
        $this->get('/produk')->assertOk()->assertSee($visible->name)->assertDontSee($draft->name)->assertDontSee($hiddenCategory->name)->assertDontSee($hiddenBrand->name);
        foreach ([$draft, $hiddenCategory, $hiddenBrand] as $product) {
            $this->get($product->url)->assertNotFound();
            $this->get('/search?q='.urlencode($product->name))->assertDontSee('>'.$product->name.'<', false);
            $this->get('/sitemap.xml')->assertDontSee($product->slug);
        }
    }

    public function test_product_details_and_contextual_inquiry(): void
    {
        $product = Product::factory()->create();
        $industry = Industry::factory()->create();
        $product->industries()->attach($industry);
        $this->get($product->url)->assertOk()->assertSee('Power')->assertSee('15 kW')->assertSee('product='.$product->id);
        $this->get('/kontak?product='.$product->id)->assertSee('value="'.$product->id.'" selected', false);
        $this->get('/produk/wrong-category/'.$product->slug)->assertNotFound();
        $this->get('/brand/'.$product->brand->slug)->assertOk()->assertSee($product->name);
        $this->get('/industri/'.$industry->slug)->assertOk()->assertSee($product->name);
        $this->get('/produk/'.$product->category->slug)->assertSee($industry->name)->assertSee('Brand Terkait');
        $this->get('/brand/'.$product->brand->slug)->assertSee($product->category->name)->assertSee('Kategori Terkait');
    }

    public function test_product_filters_and_keyword_search(): void
    {
        $match = Product::factory()->create(['name' => 'Precision Dosing Pump']);
        $other = Product::factory()->create(['name' => 'Unrelated Motor']);
        $this->get('/produk?category='.$match->category_id.'&brand='.$match->brand_id)->assertSee($match->name)->assertDontSee($other->name);
        $this->get('/produk?q=Precision')->assertSee($match->name)->assertDontSee($other->name);
        $this->get('/produk?q='.urlencode($match->brand->name))->assertSee($match->name)->assertDontSee($other->name);
        $this->get('/produk/'.$match->category->slug)->assertOk()->assertSee($match->name)->assertDontSee($other->name);
    }

    public function test_seo_metadata_is_escaped_and_present(): void
    {
        $product = Product::factory()->create(['meta_title' => 'Technical Motor', 'meta_description' => 'Industrial product description']);
        $this->get($product->url)->assertSee('<title>Technical Motor | Artomoro</title>', false)->assertSee('rel="canonical"', false)->assertSee('property="og:title"', false)->assertSee('Industrial product description');
        $this->get('/missing-page')->assertNotFound()->assertSee('Halaman tidak ditemukan.');
    }
}
