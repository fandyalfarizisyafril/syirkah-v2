<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_detail_uses_cms_profile_logo_focus_and_published_relations(): void
    {
        $brand = Brand::factory()->create(['name' => 'Technical Brand', 'focus' => 'Drive Systems', 'description' => 'Verified profile.', 'image' => 'catalog/company-logo.png', 'image_alt' => 'Technical Brand logo', 'website_url' => 'https://example.com']);
        $category = Category::factory()->create(['name' => 'Drive Systems', 'description' => 'Category information.']);
        $product = Product::factory()->create(['brand_id' => $brand->id, 'category_id' => $category->id]);
        $draft = Product::factory()->create(['brand_id' => $brand->id, 'status' => 'draft']);
        $other = Product::factory()->create();
        $hiddenCategory = Category::factory()->create(['status' => 'draft']);
        $hidden = Product::factory()->create(['brand_id' => $brand->id, 'category_id' => $hiddenCategory->id]);
        $response = $this->get(route('brands.show', $brand->slug))->assertOk()
            ->assertSee('Mengenal Technical Brand')->assertSee('Verified profile.')->assertSee('Drive Systems')
            ->assertSee('Category information.')->assertSee('src="'.$brand->image_url.'"', false)
            ->assertSee('alt="Technical Brand logo"', false)->assertSee('href="https://example.com"', false)
            ->assertSee('href="'.$product->url.'"', false)->assertSee('href="'.route('products.category', $category->slug).'"', false)
            ->assertDontSee($draft->name)->assertDontSee($other->name)->assertDontSee($hidden->name)->assertDontSee($hiddenCategory->name);
        $this->assertSame(2, substr_count($response->getContent(), '>Verified profile.</p>'));
        $this->assertCount(1, $response->viewData('products'));
    }

    public function test_preview_is_capped_at_six_in_existing_order_with_working_all_products_filter(): void
    {
        $brand = Brand::factory()->create();
        $category = Category::factory()->create();
        $products = collect(range(1, 8))->map(fn ($i) => Product::factory()->create(['brand_id' => $brand->id, 'category_id' => $category->id, 'sort_order' => $i]));
        $lastCategory = Category::factory()->create();
        $products->last()->update(['category_id' => $lastCategory->id]);
        $response = $this->get(route('brands.show', $brand->slug))->assertOk()
            ->assertViewHas('products', fn ($p) => $p->pluck('id')->all() === $products->take(6)->pluck('id')->all())
            ->assertSee($lastCategory->name)->assertSee('href="'.route('products.index', ['brand' => $brand->id]).'"', false);
        $this->get(route('products.index', ['brand' => $brand->id]))->assertSee('8 produk');
        $industry = Industry::factory()->create();
        $industry->products()->attach($products->pluck('id'));
        $this->get(route('industries.show', $industry->slug))->assertViewHas('products', fn ($p) => $p->perPage() === 12 && $p->total() === 8);
    }

    public function test_sparse_profiles_preserve_valid_copy_without_inventing_content(): void
    {
        $brand = Brand::factory()->create(['focus' => null, 'description' => 'Only available company profile.', 'image' => null]);
        $response = $this->get(route('brands.show', $brand->slug))->assertOk()->assertSee('Produk belum tersedia.')
            ->assertSee('id="brand-profile-title"', false)->assertDontSee('id="brand-technology-title"', false)
            ->assertDontSee('class="brand-detail-logo"', false);
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $this->assertSame(2, (new \DOMXPath($dom))->query('//p[normalize-space(.)="Only available company profile."]')->length);
        $brand->update(['focus' => 'Control Equipment', 'description' => 'Verified new profile.', 'image' => 'catalog/new-logo.png']);
        $category = Category::factory()->create(['description' => 'Verified new profile.']);
        Product::factory()->create(['brand_id' => $brand->id, 'category_id' => $category->id]);
        $updated = $this->get(route('brands.show', $brand->slug))->assertSee('Control Equipment')->assertSee('id="brand-profile-title"', false)->assertSee($brand->image_url);
        $this->assertSame(2, substr_count($updated->getContent(), '>Verified new profile.</p>'));
        $brand->update(['description' => '<script>alert(1)</script>']);
        $this->get(route('brands.show', $brand->slug))->assertSee($brand->description)->assertDontSee($brand->description, false);
        $brand->update(['status' => 'draft']);
        $this->get(route('brands.show', $brand->slug))->assertNotFound();
    }

    public function test_refinement_uses_complete_editorial_paragraphs_without_truncation_or_repetition(): void
    {
        $profile = "Motor systems for equipment operation with supporting control technology.\n\nA second verified paragraph describing the scope of this company.";
        $brand = Brand::factory()->create(['focus' => 'Electric Systems', 'description' => $profile]);
        $category = Category::factory()->create(['name' => 'Electric Systems', 'description' => 'Specific category coverage.']);
        Product::factory()->create(['brand_id' => $brand->id, 'category_id' => $category->id]);
        $response = $this->get(route('brands.show', $brand->slug))->assertOk()->assertSee('Specific category coverage.');
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $summary = trim($xpath->query('//p[@class="brand-hero-description"]')->item(0)->textContent);
        $this->assertSame('Motor systems for equipment operation with supporting control technology.', $summary);
        $this->assertNotSame($profile, $summary);
        $this->assertSame(1, $xpath->query('//div[@class="brand-detail-profile-copy"]/p')->length);
        $this->assertSame('A second verified paragraph describing the scope of this company.', trim($xpath->query('//div[@class="brand-detail-profile-copy"]/p')->item(0)->textContent));
        $this->assertSame(1, $xpath->query('//p[normalize-space(.)="'.$summary.'"]')->length);
        $this->assertSame(route('products.category', $category->slug), $xpath->query('//p[@class="brand-detail-summary"]/a')->item(0)->getAttribute('href'));
        $this->assertSame($profile, $brand->fresh()->description);
        $brand->update(['description' => 'Specific category coverage.']);
        $this->get(route('brands.show', $brand->slug))->assertSee('brand-detail-technology is-compact', false);
    }

    public function test_single_paragraph_is_never_cut_by_word_count_and_missing_copy_stays_empty(): void
    {
        $description = 'PT. Example supplies motor equipment and supporting control technology for industrial operations, with information maintained by the company through its existing content management system.';
        $brand = Brand::factory()->create(['description' => $description, 'focus' => null]);
        $response = $this->get(route('brands.show', $brand->slug))->assertOk();
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $this->assertSame($description, $xpath->query('//p[@class="brand-hero-description"]')->item(0)->textContent);
        $this->assertSame($description, $brand->fresh()->description);

        $brand->update(['description' => null]);
        $this->get(route('brands.show', $brand->slug))->assertOk()
            ->assertDontSee('class="brand-hero-description"', false)
            ->assertDontSee('id="brand-profile-title"', false)
            ->assertDontSee('id="brand-technology-title"', false);
    }
}
