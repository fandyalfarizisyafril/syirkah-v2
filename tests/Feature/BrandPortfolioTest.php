<?php

namespace Tests\Feature;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandPortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_portfolio_uses_published_cms_brands_order_images_focus_and_urls(): void
    {
        $later = Brand::factory()->create(['name' => 'Later Brand', 'sort_order' => 20, 'image' => null]);
        $first = Brand::factory()->create(['name' => 'First Brand', 'sort_order' => 1, 'focus' => 'Technical Systems', 'image' => 'catalog/original-logo.png', 'image_alt' => 'Original Company Logo']);
        $draft = Brand::factory()->create(['name' => 'Hidden Draft Brand', 'status' => 'draft']);
        $response = $this->get(route('brands.index'))->assertOk()
            ->assertSee('Brand dan teknologi untuk kebutuhan industri.')
            ->assertSeeInOrder(['First Brand', 'Later Brand'])->assertDontSee($draft->name)
            ->assertSee('Technical Systems')->assertSee('src="'.$first->image_url.'"', false)
            ->assertSee('alt="Original Company Logo"', false);
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $cards = $xpath->query('//a[@class="brand-portfolio-card"]');
        $this->assertSame(2, $cards->length);
        $this->assertSame(route('brands.show', $first->slug), $cards->item(0)->getAttribute('href'));
        $this->assertSame(route('brands.show', $later->slug), $cards->item(1)->getAttribute('href'));
        $this->assertSame(1, $xpath->query('//a[@class="brand-portfolio-card"]//img')->length);
        $this->assertSame(0, $xpath->query('//a[@class="brand-portfolio-card"]//a')->length);
    }

    public function test_cms_changes_new_brands_removal_and_empty_state_are_reflected(): void
    {
        $brand = Brand::factory()->create(['name' => 'Original Brand']);
        $brand->update(['name' => 'Updated Brand', 'focus' => 'Updated Technology', 'image' => 'catalog/new-upload.png']);
        $this->get(route('brands.index'))->assertSee('Updated Brand')->assertSee('Updated Technology')
            ->assertSee('src="'.$brand->image_url.'"', false)->assertDontSee('Original Brand');
        $new = Brand::factory()->create(['name' => '<New & Company>', 'focus' => '<script>unsafe</script>']);
        $this->get(route('brands.index'))->assertSee($new->name)->assertSee($new->focus)->assertDontSee($new->focus, false);
        $brand->delete();
        $new->update(['status' => 'draft']);
        $this->get(route('brands.index'))->assertDontSee('class="brand-portfolio-card"', false)
            ->assertSee('Kebutuhan industri Anda adalah fokus kami.');
    }

    public function test_portfolio_is_not_used_on_detail_or_other_public_pages(): void
    {
        $brand = Brand::factory()->create();
        foreach (['/', '/tentang-kami', '/produk', '/industri', '/kontak', '/admin/login', route('brands.show', $brand->slug)] as $path) {
            $this->get($path)->assertOk()->assertDontSee('class="brand-portfolio"', false);
        }
        $this->get(route('brands.show', $brand->slug))->assertSee($brand->name)->assertSee('Produk Terkait');
    }
}
