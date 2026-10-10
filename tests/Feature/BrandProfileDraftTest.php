<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\BrandProfileDraftSeeder;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandProfileDraftTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('local');
    }

    private function drafts(): array
    {
        return json_decode(file_get_contents(database_path('data/brand-profile-drafts.json')), true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_drafts_enrich_all_seven_brands_without_changing_catalog_or_other_brand_fields(): void
    {
        $this->seed(CatalogSeeder::class);
        $before = Brand::ordered()->get()->keyBy('slug');
        $categories = Category::all()->toArray();
        $products = Product::all()->toArray();
        $listing = $this->get('/brand')->getContent();
        $home = $this->get('/')->getContent();
        $this->seed(BrandProfileDraftSeeder::class);

        foreach ($this->drafts() as $draft) {
            $brand = Brand::where('slug', $draft['slug'])->firstOrFail();
            $this->assertSame($draft['hero']."\n\n".$draft['profile'], $brand->description);
            $this->assertSame($draft['technology_details'], $brand->technology_details);
            $this->assertSame(collect($before[$brand->slug]->toArray())->except(['description', 'technology_details', 'updated_at'])->all(), collect($brand->toArray())->except(['description', 'technology_details', 'updated_at'])->all());
            $this->assertGreaterThanOrEqual(60, str_word_count($draft['profile']));
            $this->assertLessThanOrEqual(100, str_word_count($draft['profile']));
            $this->assertNotSame($draft['hero'], $draft['profile']);
            $response = $this->get(route('brands.show', $brand->slug))->assertOk()->assertSee($draft['hero'])->assertSee($draft['profile']);
            foreach ($draft['technology_details'] as $technology) {
                $response->assertSee($technology['name'])->assertSee($technology['description']);
            }
        }

        $this->assertSame($categories, Category::all()->toArray());
        $this->assertSame($products, Product::all()->toArray());
        $this->assertSame($listing, $this->get('/brand')->getContent());
        $this->assertSame($home, $this->get('/')->getContent());
        $backups = Storage::disk('local')->files('brand-content-backups');
        $this->assertCount(1, $backups);
        $originals = json_decode(Storage::disk('local')->get($backups[0]), true, 512, JSON_THROW_ON_ERROR);
        $this->assertCount(7, $originals);
        foreach ($originals as $original) {
            $this->assertSame($before[$original['slug']]->description, $original['description']);
        }
        $seeded = Brand::all()->toArray();
        $this->seed(BrandProfileDraftSeeder::class);
        $this->assertSame($seeded, Brand::all()->toArray());
        $this->assertCount(1, Storage::disk('local')->files('brand-content-backups'));
    }

    public function test_seeder_preserves_user_edits_and_does_not_recreate_missing_brands(): void
    {
        $this->seed(CatalogSeeder::class);
        Brand::where('slug', 'wolong')->firstOrFail()->update(['description' => 'Profile edited by the user.']);
        $oli = Brand::where('slug', 'oli')->firstOrFail();
        $oli->update(['technology_details' => [['name' => 'User technology', 'description' => 'Verified by editor.']]]);
        Product::where('brand_id', Brand::where('slug', 'aflex')->value('id'))->delete();
        Brand::where('slug', 'aflex')->delete();
        $this->seed(BrandProfileDraftSeeder::class);
        $this->assertSame('Profile edited by the user.', Brand::where('slug', 'wolong')->first()->description);
        $this->assertSame($oli->toArray(), $oli->fresh()->toArray());
        $this->assertDatabaseMissing('brands', ['slug' => 'aflex']);
        $qdos = Brand::where('slug', 'qdos')->firstOrFail();
        $qdos->update(['description' => 'New editor profile.', 'technology_details' => []]);
        $this->seed(BrandProfileDraftSeeder::class);
        $this->assertSame($qdos->toArray(), $qdos->fresh()->toArray());
    }

    public function test_backup_failure_prevents_content_changes(): void
    {
        $draft = $this->drafts()[0];
        $brand = Brand::factory()->create(['slug' => $draft['slug'], 'description' => $draft['expected_description']]);
        $brand->refresh();
        Storage::shouldReceive('disk')->with('local')->once()->andReturnSelf();
        Storage::shouldReceive('put')->once()->andReturn(false);
        try {
            $this->seed(BrandProfileDraftSeeder::class);
            $this->fail('A failed backup must stop the seed.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('Backup gagal', $e->getMessage());
        }
        $this->assertSame($brand->toArray(), $brand->fresh()->toArray());
    }

    public function test_draft_seeder_refuses_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('local/testing');
        app(BrandProfileDraftSeeder::class)->run();
    }

    public function test_editor_can_manage_technology_items_without_touching_identity_or_relations(): void
    {
        $brand = Brand::factory()->create(['focus' => 'Equipment', 'description' => 'Hero.']);
        $data = ['name' => $brand->name, 'slug' => $brand->slug, 'status' => 'published', 'sort_order' => 0];
        $url = route('admin.catalog.update', ['brands', $brand->id]);
        $this->actingAs(User::factory()->create(['role' => 'editor']));
        $this->get(route('admin.catalog.edit', ['brands', $brand->id]))->assertSee('data-brand-technologies', false);
        $this->put($url, $data + ['description' => "Hero from CMS.\n\nProfile from CMS.", 'technology_details' => [
            ['name' => 'First technology', 'description' => 'First scope.'],
            ['name' => 'Second technology', 'description' => null],
            ['name' => null, 'description' => null],
        ]])->assertSessionHasNoErrors();
        $this->assertSame([['name' => 'First technology', 'description' => 'First scope.'], ['name' => 'Second technology', 'description' => '']], $brand->fresh()->technology_details);
        $this->get(route('brands.show', $brand->slug))->assertSee('Hero from CMS.')->assertSee('Profile from CMS.')->assertSee('First technology')->assertSee('Second technology');
        $this->put($url, $data)->assertSessionHasNoErrors();
        $this->assertCount(2, $brand->fresh()->technology_details);
        $this->put($url, $data + ['technology_details_present' => 1])->assertSessionHasNoErrors();
        $this->assertSame([], $brand->fresh()->technology_details);
        $this->assertSame('Equipment', $brand->fresh()->focus);
        $this->get(route('brands.show', $brand->slug))->assertDontSee('First technology')->assertDontSee('id="brand-technology-title"', false);
        $this->get(route('admin.catalog.create', 'categories'))->assertDontSee('data-brand-technologies', false);
        $this->get(route('admin.catalog.create', 'products'))->assertDontSee('data-brand-technologies', false);
    }

    public function test_validation_rejects_invalid_items_and_permissions_remain_unchanged(): void
    {
        $brand = Brand::factory()->create();
        $url = route('admin.catalog.update', ['brands', $brand->id]);
        $data = ['name' => $brand->name, 'slug' => $brand->slug, 'status' => 'published', 'sort_order' => 0];
        $this->actingAs(User::factory()->create(['role' => 'sales']))->put($url, $data)->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'editor']));
        foreach ([
            [['name' => '', 'description' => 'Missing title']],
            [['name' => str_repeat('a', 151), 'description' => 'Too long']],
            [['name' => 'Scope', 'description' => str_repeat('a', 1001)]],
            [['name' => 'Scope', 'url' => 'javascript:alert(1)']],
            array_fill(0, 13, ['name' => 'Too many', 'description' => 'Scope']),
        ] as $items) {
            $this->put($url, $data + ['technology_details' => $items])->assertSessionHasErrors();
        }
        $this->assertNull($brand->fresh()->technology_details);
        $edit = route('admin.catalog.edit', ['brands', $brand->id]);
        $this->from($edit)->put($url, $data + ['technology_details' => 'invalid'])->assertSessionHasErrors('technology_details');
        $this->get($edit)->assertOk();
        $this->put($url, $data + ['technology_details' => [['name' => '<script>alert(1)</script>', 'description' => '<b>Plain text</b>']]])->assertSessionHasNoErrors();
        $this->get(route('brands.show', $brand->slug))->assertSee('<script>alert(1)</script>')->assertDontSee('<script>alert(1)</script>', false);
    }
}
