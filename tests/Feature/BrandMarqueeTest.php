<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Database\Seeders\BrandLogoSeeder;
use Database\Seeders\CatalogSeeder;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandMarqueeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function homepageLogos(): array
    {
        $document = new DOMDocument;
        @$document->loadHTML($this->get('/')->assertOk()->getContent());
        $nodes = (new DOMXPath($document))->query('//ul[@class="home-brand-group" and not(@aria-hidden)]//img');

        return array_map(fn ($image) => $image->getAttribute('src'), iterator_to_array($nodes));
    }

    private function logoUpload(string $name): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, file_get_contents(public_path('images/brands/oli.png')));
    }

    public function test_marquee_uses_only_published_cms_images_in_sort_order(): void
    {
        $last = Brand::factory()->create(['image' => 'catalog/last.png', 'sort_order' => 9]);
        $first = Brand::factory()->create(['image' => 'catalog/first.png', 'sort_order' => 1]);
        Brand::factory()->create(['image' => 'catalog/draft.png', 'status' => 'draft']);
        Brand::factory()->create(['image' => null]);

        $this->assertSame([$first->image_url, $last->image_url], $this->homepageLogos());
        $html = $this->get('/')->getContent();
        $this->assertSame(2, substr_count($html, 'class="home-brand-group"'));
        $this->assertSame(2, substr_count($html, 'tabindex="-1"'));
        $this->assertMatchesRegularExpression('/class="home-brand-group"\s+aria-hidden="true"/', $html);
        $this->get('/brand/'.$first->slug)->assertOk()->assertDontSee('home-brand-marquee');
    }

    public function test_empty_and_single_logo_lists_do_not_create_an_empty_animated_track(): void
    {
        Brand::factory()->create(['image' => null]);
        $this->get('/')->assertOk()->assertDontSee('home-brand-track')->assertSee('Teknologi untuk setiap kebutuhan.');
        Brand::factory()->create(['image' => 'catalog/only.png']);
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertStringContainsString('home-brand-marquee is-single', $html);
        $this->assertSame(1, substr_count($html, 'class="home-brand-group"'));
    }

    public function test_cms_upload_replace_remove_draft_and_delete_update_the_marquee(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => 'administrator']));
        $data = ['name' => 'New Principal', 'slug' => 'new-principal', 'status' => 'published', 'sort_order' => 0];
        $this->post(route('admin.catalog.store', 'brands'), $data + ['image' => $this->logoUpload('brand.png')])
            ->assertSessionHasNoErrors()->assertRedirect();
        $brand = Brand::where('slug', 'new-principal')->firstOrFail();
        $this->assertSame([$brand->image_url], $this->homepageLogos());
        $firstImage = $brand->image;
        $url = route('admin.catalog.update', ['brands', $brand->id]);
        $this->put($url, $data + ['image' => $this->logoUpload('replacement.png')])->assertSessionHasNoErrors();
        $brand->refresh();
        $this->assertNotSame($firstImage, $brand->image);
        $this->assertSame([$brand->image_url], $this->homepageLogos());
        $this->put($url, $data + ['remove_image' => 1])->assertSessionHasNoErrors();
        $this->assertNull($brand->refresh()->image);
        $this->assertSame([], $this->homepageLogos());
        // A replacement file wins when remove and upload are submitted together.
        $this->put($url, $data + ['remove_image' => 1, 'image' => $this->logoUpload('new.png')])->assertSessionHasNoErrors();
        $this->assertSame([$brand->refresh()->image_url], $this->homepageLogos());
        $this->put($url, array_replace($data, ['status' => 'draft']))->assertSessionHasNoErrors();
        $this->assertSame([], $this->homepageLogos());
        $this->put($url, $data)->assertSessionHasNoErrors();
        $this->assertSame([$brand->refresh()->image_url], $this->homepageLogos());
        $this->delete(route('admin.catalog.destroy', ['brands', $brand->id]))->assertRedirect();
        $this->assertSame([], $this->homepageLogos());
    }

    public function test_verified_logo_seed_is_idempotent_and_preserves_admin_uploads(): void
    {
        Storage::fake('public');
        $this->seed(CatalogSeeder::class);
        Brand::where('slug', 'oli')->update(['image' => 'catalog/admin-upload.png']);
        $this->seed(BrandLogoSeeder::class);
        $before = Brand::ordered()->get()->toArray();
        $this->seed(BrandLogoSeeder::class);
        $this->assertSame($before, Brand::ordered()->get()->toArray());
        $this->assertSame(7, Brand::count());
        $this->assertSame('catalog/admin-upload.png', Brand::where('slug', 'oli')->first()->image);
        $this->assertNull(Brand::where('slug', 'qdos')->first()->image);
        $this->assertNull(Brand::where('slug', 'blu-c')->first()->image);
        $logos = json_decode(file_get_contents(database_path('data/brand-logos.json')), true, 512, JSON_THROW_ON_ERROR);
        foreach ($logos as $logo) {
            $this->assertFileExists(public_path('images/brands/'.$logo['file']));
            if ($logo['slug'] !== 'oli') {
                $this->assertSame(file_get_contents(public_path('images/brands/'.$logo['file'])), Storage::disk('public')->get('catalog/brand-logos/'.$logo['file']));
            }
        }
    }
}
