<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    private function payload(): array
    {
        return ['nav_label' => 'Engineering Baru', 'eyebrow' => 'ENGINEERING UPDATE',
            'title_line_1' => 'Solusi Engineering Baru', 'title_line_2' => 'Untuk Kebutuhan Industri.',
            'description' => 'Deskripsi hero yang dikelola melalui Admin.'];
    }

    private function image(string $name = 'hero.png'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl6cS8AAAAASUVORK5CYII='));
    }

    private function staff(string $role = 'administrator'): User
    {
        return User::factory()->create(['role' => $role]);
    }

    public function test_defaults_are_unchanged_and_admin_get_requests_do_not_create_records(): void
    {
        $this->assertSame(config('focus-slides'), HeroSlide::slides());
        $this->get('/')->assertViewHas('focusSlides', config('focus-slides'));
        $this->actingAs($this->staff())->get('/admin/hero')->assertOk()->assertSee('Hero Beranda');
        foreach (config('focus-slides') as $slide) {
            $this->get('/admin/hero/'.$slide['id'].'/edit')->assertOk()->assertSee($slide['description']);
        }
        $this->assertDatabaseCount('hero_slides', 0);
    }

    public function test_routes_enforce_content_permissions_for_reads_and_writes(): void
    {
        $this->get('/admin/hero')->assertRedirect('/admin/login');
        $this->put('/admin/hero/engineering', $this->payload())->assertRedirect('/admin/login');
        foreach (['sales', 'none'] as $role) {
            $this->actingAs($this->staff($role));
            $this->get('/admin/hero')->assertForbidden();
            $this->get('/admin/hero/engineering/edit')->assertForbidden();
            $this->put('/admin/hero/engineering', $this->payload())->assertForbidden();
        }
        $this->actingAs($this->staff('editor'))->get('/admin/hero')->assertOk();
        $this->put('/admin/hero/engineering', $this->payload())->assertRedirect('/admin/hero');
    }

    public function test_each_of_the_five_slides_can_be_updated_without_changing_order_or_other_slides(): void
    {
        $this->actingAs($this->staff());
        $initial = config('focus-slides');
        foreach ($initial as $index => $slide) {
            $before = HeroSlide::slides();
            $data = array_replace($this->payload(), ['nav_label' => 'Bidang '.$slide['number']]);
            $this->put('/admin/hero/'.$slide['id'], $data)->assertRedirect('/admin/hero')->assertSessionHasNoErrors();
            $after = HeroSlide::slides();
            $this->assertSame($data['nav_label'], $after[$index]['navLabel']);
            $this->assertSame([$data['title_line_1'], $data['title_line_2']], $after[$index]['title']);
            $this->assertSame($data['description'], $after[$index]['description']);
            $this->assertSame($slide['image'], $after[$index]['image']);
            foreach ($after as $other => $item) {
                if ($index !== $other) {
                    $this->assertSame($before[$other], $item);
                }
            }
            $this->get('/')->assertSee($data['nav_label'])->assertSee($data['description']);
        }
        $this->assertDatabaseCount('hero_slides', 5);
        $this->assertSame(array_column($initial, 'id'), array_column(HeroSlide::slides(), 'id'));
        $this->put('/admin/hero/engineering', $this->payload())->assertSessionHasNoErrors();
        $this->assertDatabaseCount('hero_slides', 5);
    }

    public function test_upload_replacement_text_only_save_and_restore_default_image(): void
    {
        $this->actingAs($this->staff());
        $data = $this->payload();
        $this->put('/admin/hero/engineering', $data + ['image' => $this->image()])->assertSessionHasNoErrors();
        $entry = HeroSlide::firstOrFail();
        $first = $entry->image;
        Storage::disk('public')->assertExists($first);
        $this->assertSame(1, $entry->image_width);
        $this->get('/')->assertSee('src="'.asset(Storage::disk('public')->url($first)).'"', false);

        $this->put('/admin/hero/engineering', $data + ['image' => $this->image('new.png')])->assertSessionHasNoErrors();
        $second = $entry->fresh()->image;
        $this->assertNotSame($first, $second);
        Storage::disk('public')->assertExists($second);
        $this->get('/')->assertSee(Storage::disk('public')->url($second))->assertDontSee(Storage::disk('public')->url($first));

        $this->put('/admin/hero/engineering', array_replace($data, ['title_line_2' => '']))->assertSessionHasNoErrors();
        $this->assertSame($second, $entry->fresh()->image);
        $this->assertSame([$data['title_line_1']], HeroSlide::slides()[0]['title']);
        $this->put('/admin/hero/engineering', $data + ['reset_image' => '1'])->assertSessionHasNoErrors();
        $this->assertNull($entry->fresh()->image);
        $this->assertSame(config('focus-slides.0.image'), HeroSlide::slides()[0]['image']);
        Storage::disk('public')->assertExists($first);
    }

    public function test_missing_uploaded_image_falls_back_without_losing_edited_text(): void
    {
        $this->actingAs($this->staff())->put('/admin/hero/engineering', $this->payload() + ['image' => $this->image()]);
        Storage::disk('public')->delete(HeroSlide::firstOrFail()->image);
        $slide = HeroSlide::slides()[0];
        $this->assertSame(config('focus-slides.0.image'), $slide['image']);
        $this->assertSame(config('focus-slides.0.width'), $slide['width']);
        $this->assertSame($this->payload()['description'], $slide['description']);
    }

    public function test_invalid_fields_and_uploads_do_not_mutate_data(): void
    {
        $this->actingAs($this->staff());
        $this->put('/admin/hero/engineering', [])->assertSessionHasErrors(['nav_label', 'eyebrow', 'title_line_1', 'description']);
        foreach (['nav_label' => 61, 'eyebrow' => 81, 'title_line_1' => 101, 'title_line_2' => 101, 'description' => 351] as $field => $length) {
            $this->put('/admin/hero/engineering', array_replace($this->payload(), [$field => str_repeat('x', $length)]))->assertSessionHasErrors($field);
        }
        foreach ([UploadedFile::fake()->create('script.php', 1, 'text/plain'),
            UploadedFile::fake()->createWithContent('fake.png', '<script>alert(1)</script>'),
            $this->image('disguised.php'), $this->image()->size(4097),
            UploadedFile::fake()->createWithContent('bad.svg', '<svg xmlns="http://www.w3.org/2000/svg"></svg>')] as $image) {
            $this->put('/admin/hero/engineering', $this->payload() + ['image' => $image])->assertSessionHasErrors('image');
        }
        $this->put('/admin/hero/engineering', $this->payload() + ['image' => $this->image(), 'reset_image' => '1'])->assertSessionHasErrors('image');
        $this->assertDatabaseCount('hero_slides', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_unknown_slides_and_extra_fields_cannot_change_the_carousel_structure(): void
    {
        $this->actingAs($this->staff());
        $this->get('/admin/hero/unknown/edit')->assertNotFound();
        $this->put('/admin/hero/unknown', $this->payload())->assertNotFound();
        $this->delete('/admin/hero/engineering')->assertStatus(405);
        $this->post('/admin/hero', $this->payload())->assertStatus(405);
        $this->put('/admin/hero/engineering', $this->payload() + ['focus_key' => 'other', 'sort_order' => 99, 'image_width' => 99])->assertSessionHasNoErrors();
        $this->assertSame('engineering', HeroSlide::first()->focus_key);
        $this->assertNull(HeroSlide::first()->image_width);
    }

    public function test_html_in_content_is_escaped(): void
    {
        $this->actingAs($this->staff());
        $value = '<script>alert(1)</script>';
        $this->put('/admin/hero/engineering', array_fill_keys(array_keys($this->payload()), $value))->assertSessionHasNoErrors();
        foreach (['/', '/admin/hero', '/admin/hero/engineering/edit'] as $url) {
            $this->get($url)->assertSee($value)->assertDontSee($value, false);
        }
    }

    public function test_new_upload_is_cleaned_up_if_database_save_fails(): void
    {
        $this->actingAs($this->staff());
        HeroSlide::saving(fn () => throw new \RuntimeException('Save failed'));
        $this->withoutExceptionHandling();
        try {
            $this->put('/admin/hero/engineering', $this->payload() + ['image' => $this->image()]);
            $this->fail('Expected save failure.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Save failed', $exception->getMessage());
        } finally {
            HeroSlide::flushEventListeners();
        }
        $this->assertSame([], Storage::disk('public')->allFiles());
        $this->assertDatabaseCount('hero_slides', 0);
    }
}
