<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandDetailTest extends TestCase
{
    use RefreshDatabase;

    private function videoPayload(Brand $brand, array $extra = []): array
    {
        return array_merge(['name' => $brand->name, 'slug' => $brand->slug, 'status' => $brand->status, 'sort_order' => 0], $extra);
    }

    private function mp4(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('brand.mp4', pack('N', 32).'ftypisom'.pack('N', 512).'isomiso2avc1mp41')->mimeType('video/mp4');
    }

    public function test_brand_video_upload_replace_remove_and_public_rendering(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => 'administrator']));
        $brand = Brand::factory()->create();
        $originalDescription = $brand->description;
        $route = route('admin.catalog.update', ['brands', $brand->id]);
        $this->put($route, $this->videoPayload($brand, [
            'video_source' => 'mp4', 'video_file' => $this->mp4(), 'video_poster' => UploadedFile::fake()->image('poster.jpg'),
            'video_width' => 464, 'video_height' => 832, 'video_title' => 'Video resmi',
        ]))->assertRedirect()->assertSessionHasNoErrors();
        $video = $brand->fresh()->video;
        Storage::disk('public')->assertExists([$video['path'], $video['poster']]);
        $this->assertSame($originalDescription, $brand->fresh()->description);
        $this->get(route('brands.video', $brand->slug), ['Range' => 'bytes=0-15'])->assertStatus(206)->assertHeader('Content-Type', 'video/mp4')->assertHeader('Content-Length', '16');
        $this->get(route('brands.show', $brand->slug))->assertOk()->assertSee('03 / BRAND VIDEO')
            ->assertSee('04 / PRODUK TERKAIT')->assertSee('preload="none"', false)
            ->assertSee('--video-ratio:464 / 832', false)->assertSee('Video resmi');
        $this->put($route, $this->videoPayload($brand, ['video_source' => 'mp4', 'video_title' => 'Judul baru']))->assertSessionHasNoErrors();
        $this->assertSame($video['path'], $brand->fresh()->video['path']);
        $this->put($route, $this->videoPayload($brand, ['video_source' => 'mp4', 'video_file' => $this->mp4()]))->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing([$video['path'], $video['poster']]);
        $replacement = $brand->fresh()->video['path'];
        $this->put($route, $this->videoPayload($brand, ['video_source' => 'none']))->assertSessionHasNoErrors();
        $this->assertNull($brand->fresh()->video);
        Storage::disk('public')->assertMissing($replacement);
        $this->get(route('brands.show', $brand->slug))->assertDontSee('BRAND VIDEO')->assertSee('03 / PRODUK TERKAIT');
    }

    public function test_brand_youtube_is_normalized_and_never_renders_arbitrary_embeds(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => 'administrator']));
        $brand = Brand::factory()->create();
        $route = route('admin.catalog.update', ['brands', $brand->id]);
        $this->put($route, $this->videoPayload($brand, ['video_source' => 'youtube', 'video_youtube_url' => 'https://youtu.be/M7lc1UVf-VE?si=test', 'video_title' => '<script>bad</script>']))->assertSessionHasNoErrors();
        $this->assertSame('M7lc1UVf-VE', $brand->fresh()->video['youtube_id']);
        $this->get(route('brands.show', $brand->slug))->assertSee('https://www.youtube-nocookie.com/embed/M7lc1UVf-VE', false)
            ->assertSee('loading="lazy"', false)->assertDontSee('<script>bad</script>', false)->assertDontSee('autoplay=1');
        foreach (['https://youtube.com.evil.test/watch?v=M7lc1UVf-VE', 'javascript:alert(1)', 'https://www.youtube.com/watch?v[]=M7lc1UVf-VE', 'https://user@youtube.com/watch?v=M7lc1UVf-VE'] as $url) {
            $this->put($route, $this->videoPayload($brand, ['video_source' => 'youtube', 'video_youtube_url' => $url]))->assertSessionHasErrors('video_youtube_url');
        }
        $this->put($route, $this->videoPayload($brand))->assertSessionHasNoErrors();
        $this->assertSame('M7lc1UVf-VE', $brand->fresh()->video['youtube_id']);
    }

    public function test_brand_video_validation_and_authorization(): void
    {
        Storage::fake('public');
        $brand = Brand::factory()->create();
        $route = route('admin.catalog.update', ['brands', $brand->id]);
        $this->put($route, [])->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create(['role' => 'sales']));
        $this->put($route, [])->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'administrator']));
        $this->put($route, $this->videoPayload($brand, ['video_source' => 'mp4']))->assertSessionHasErrors('video_file');
        $this->put($route, $this->videoPayload($brand, ['video_source' => 'mp4', 'video_file' => UploadedFile::fake()->createWithContent('bad.mp4', '<?php echo 1;')]))->assertSessionHasErrors('video_file');
        $this->put($route, $this->videoPayload($brand, ['video_source' => 'mp4', 'video_file' => $this->mp4()->size(51201)]))->assertSessionHasErrors('video_file');
        $this->put($route, $this->videoPayload($brand, ['video_source' => 'youtube', 'video_youtube_url' => 'https://youtu.be/M7lc1UVf-VE', 'video_poster' => UploadedFile::fake()->createWithContent('poster.svg', '<svg/>')]))->assertSessionHasErrors('video_poster');
        $this->assertNull($brand->fresh()->video);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_video_endpoint_checks_status_and_brand_creation_supports_video(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'administrator']);
        $this->actingAs($user);
        $this->post(route('admin.catalog.store', 'brands'), [
            'name' => 'Video Brand', 'slug' => 'video-brand', 'status' => 'draft', 'sort_order' => 0,
            'video_source' => 'mp4', 'video_file' => $this->mp4(),
        ])->assertSessionHasNoErrors();
        $brand = Brand::where('slug', 'video-brand')->firstOrFail();
        $path = $brand->video['path'];
        $url = route('brands.video', $brand->slug);
        $this->get($url)->assertOk();
        auth()->logout();
        $this->get($url)->assertNotFound();
        $brand->update(['status' => 'published']);
        $this->get($url)->assertOk();
        $this->actingAs($user);
        $this->put(route('admin.catalog.update', ['brands', $brand->id]), $this->videoPayload($brand, [
            'video_source' => 'youtube', 'video_youtube_url' => 'https://youtube.com/shorts/M7lc1UVf-VE',
        ]))->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($path);
        $this->get($url)->assertNotFound();
        $this->put(route('admin.catalog.update', ['brands', $brand->id]), $this->videoPayload($brand, [
            'video_source' => 'mp4', 'video_file' => $this->mp4(),
        ]))->assertSessionHasNoErrors();
        $path = $brand->fresh()->video['path'];
        $this->delete(route('admin.catalog.destroy', ['brands', $brand->id]))->assertRedirect();
        Storage::disk('public')->assertMissing($path);
    }

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
