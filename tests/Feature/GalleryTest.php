<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\GalleryMedia;
use App\Models\User;
use App\Services\GalleryFeed;
use App\Services\GalleryStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('gallery');
        Storage::fake('public');
    }

    private function data(array $extra = []): array
    {
        return array_replace(['title' => 'Media pengujian', 'description' => 'Dokumentasi pengujian.', 'source' => 'photo', 'status' => 'draft'], $extra);
    }

    private function mp4(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('video.mp4', pack('N', 32).'ftypisom'.pack('N', 512).'isomiso2avc1mp41')->mimeType('video/mp4');
    }

    private function youtube(array $extra = []): GalleryMedia
    {
        return GalleryMedia::create(array_replace($this->data(), ['source' => 'youtube', 'youtube_id' => 'M7lc1UVf-VE', 'status' => 'published'], $extra));
    }

    public function test_authentication_authorization_and_csrf_cover_gallery_routes(): void
    {
        $entry = $this->youtube(['status' => 'draft']);
        $routes = ['/admin/gallery', '/admin/gallery/create', "/admin/gallery/{$entry->id}/edit", "/admin/gallery/{$entry->id}/file", "/admin/gallery/{$entry->id}/poster"];
        foreach ($routes as $route) {
            $this->get($route)->assertRedirect('/admin/login');
        }
        $this->post('/admin/gallery')->assertRedirect('/admin/login');
        $this->put('/admin/gallery/'.$entry->id)->assertRedirect('/admin/login');
        $this->delete('/admin/gallery/'.$entry->id)->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create(['role' => 'sales']));
        foreach ($routes as $route) {
            $this->get($route)->assertForbidden();
        }
        $this->post('/admin/gallery', $this->data())->assertForbidden();
        $this->put('/admin/gallery/'.$entry->id, $this->data())->assertForbidden();
        $this->delete('/admin/gallery/'.$entry->id)->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'editor']));
        $this->get('/admin/gallery')->assertOk()->assertSee('Galeri &amp; Media', false);
        $this->get('/admin/gallery/create')->assertOk();
        $this->get('/admin/gallery/'.$entry->id.'/edit')->assertOk();
        $this->app['env'] = 'local';
        $this->post('/admin/gallery', $this->data())->assertStatus(419);
    }

    public function test_photo_create_publish_replace_unpublish_delete_and_private_storage(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'administrator']));
        $this->post('/admin/gallery', $this->data(['media_file' => UploadedFile::fake()->image('photo.png', 300, 400)]))
            ->assertSessionHasNoErrors()->assertRedirect('/admin/gallery');
        $entry = GalleryMedia::sole();
        $path = $entry->file_path;
        $this->assertSame([300, 400], [$entry->width, $entry->height]);
        Storage::disk('gallery')->assertExists($path);
        $this->assertFalse(Storage::disk('public')->exists($path));
        $this->get(route('gallery.file', $entry))->assertNotFound();
        $this->get('/publikasi')->assertDontSee($entry->title);
        $this->get(route('admin.gallery.file', $entry))->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->put('/admin/gallery/'.$entry->id, $this->data(['status' => 'published']))->assertSessionHasNoErrors();
        $this->get('/publikasi')->assertSee($entry->title)->assertSee(route('gallery.file', $entry), false);
        $response = $this->get(route('gallery.file', $entry))->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->put('/admin/gallery/'.$entry->id, $this->data(['status' => 'published', 'title' => 'Foto baru', 'media_file' => UploadedFile::fake()->image('new.jpg', 800, 450)]))->assertSessionHasNoErrors();
        $entry->refresh();
        Storage::disk('gallery')->assertMissing($path);
        $this->assertSame([800, 450], [$entry->width, $entry->height]);
        $this->put('/admin/gallery/'.$entry->id, $this->data())->assertSessionHasNoErrors();
        $this->get(route('gallery.file', $entry))->assertNotFound();
        $this->get('/publikasi/galeri')->assertDontSee('Foto baru');
        $this->delete('/admin/gallery/'.$entry->id)->assertRedirect('/admin/gallery');
        Storage::disk('gallery')->assertMissing($entry->file_path);
        $this->assertDatabaseCount('gallery_media', 0);
    }

    public function test_video_range_poster_youtube_switch_and_escaped_content(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'editor']));
        $this->post('/admin/gallery', $this->data(['source' => 'mp4', 'status' => 'published', 'media_file' => $this->mp4(), 'width' => 464, 'height' => 832, 'poster' => UploadedFile::fake()->image('poster.jpg')]))->assertSessionHasNoErrors();
        $entry = GalleryMedia::sole();
        $paths = [$entry->file_path, $entry->poster_path];
        $this->get(route('gallery.file', $entry), ['Range' => 'bytes=0-15'])->assertStatus(206)->assertHeader('Content-Length', '16');
        $this->get(route('gallery.poster', $entry))->assertOk();
        $this->get('/publikasi')->assertSee('--media-ratio:464 / 832', false)->assertDontSee('<video', false);
        $this->put('/admin/gallery/'.$entry->id, $this->data(['source' => 'youtube', 'status' => 'published', 'youtube_url' => 'https://youtu.be/M7lc1UVf-VE', 'title' => '<script>bad</script>']))->assertSessionHasNoErrors();
        Storage::disk('gallery')->assertMissing($paths);
        $entry->refresh();
        $this->assertNull($entry->file_path);
        $this->assertSame('M7lc1UVf-VE', $entry->youtube_id);
        $this->get('/publikasi')->assertSee('&lt;script&gt;bad&lt;/script&gt;', false)->assertDontSee('<script>bad</script>', false);
        $this->get(route('gallery.file', $entry))->assertNotFound();
        $this->get(route('gallery.poster', $entry))->assertNotFound();
        $this->put('/admin/gallery/'.$entry->id, $this->data(['source' => 'photo']))->assertSessionHasErrors('media_file');
    }

    public function test_invalid_uploads_urls_metadata_and_path_traversal_are_rejected(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'editor']));
        foreach ([['photo', 'bad.svg', '<svg/>'], ['photo', 'bad.png', '<?php echo 1;'], ['mp4', 'bad.mp4', '<?php echo 1;'], ['mp4', 'script.php', 'ftypisom']] as [$source, $name, $content]) {
            $this->post('/admin/gallery', $this->data(['source' => $source, 'media_file' => UploadedFile::fake()->createWithContent($name, $content)]))->assertSessionHasErrors('media_file');
        }
        $this->post('/admin/gallery', $this->data(['media_file' => UploadedFile::fake()->image('huge.png')->size(8193)]))->assertSessionHasErrors('media_file');
        $this->post('/admin/gallery', $this->data(['source' => 'mp4', 'media_file' => $this->mp4()->size(51201)]))->assertSessionHasErrors('media_file');
        foreach (['https://youtube.com.evil.test/watch?v=M7lc1UVf-VE', 'javascript:alert(1)', 'https://example.com/video', 'http://youtu.be/M7lc1UVf-VE'] as $url) {
            $this->post('/admin/gallery', $this->data(['source' => 'youtube', 'youtube_url' => $url]))->assertSessionHasErrors('youtube_url');
        }
        $this->post('/admin/gallery', $this->data(['source' => 'other', 'status' => 'bad', 'sort_order' => -1, 'width' => 0]))->assertSessionHasErrors(['source', 'status', 'sort_order', 'width']);
        $this->assertDatabaseCount('gallery_media', 0);
        $this->assertSame([], Storage::disk('gallery')->allFiles());
        $entry = GalleryMedia::create($this->data(['status' => 'published']) + ['file_path' => '../../.env', 'mime_type' => 'image/png']);
        $this->get(route('gallery.file', $entry))->assertNotFound();
        $this->assertNull(app(GalleryFeed::class)->medium($entry));
    }

    public function test_brand_feed_uses_existing_data_and_updates_without_copying_video(): void
    {
        $path = 'catalog/brand-videos/11111111-1111-4111-8111-111111111111.mp4';
        Storage::disk('public')->put($path, 'video fixture');
        $brand = Brand::create(['name' => 'Bredel', 'slug' => 'bredel', 'status' => 'published', 'sort_order' => 0, 'video' => ['source' => 'mp4', 'path' => $path, 'width' => 464, 'height' => 832, 'title' => 'Bredel video']]);
        $this->get('/publikasi')->assertSee('Bredel video')->assertSee(route('brands.video', 'bredel'), false);
        $this->assertDatabaseCount('gallery_media', 0);
        $brand->update(['video' => ['source' => 'youtube', 'youtube_id' => 'M7lc1UVf-VE', 'title' => 'Video pengganti']]);
        $this->get('/publikasi')->assertSee('Video pengganti')->assertDontSee('Bredel video');
        $brand->update(['status' => 'draft']);
        $this->get('/publikasi/galeri')->assertDontSee('Video pengganti');
        $brand->update(['status' => 'published', 'video' => null]);
        $this->get('/publikasi')->assertSee('Media belum tersedia.');
        Storage::disk('public')->assertExists($path);
    }

    public function test_mixed_feed_order_load_more_and_missing_file_fallback(): void
    {
        for ($i = 0; $i < 14; $i++) {
            $this->youtube(['title' => 'Video '.$i, 'created_at' => now()->subMinutes(14 - $i)]);
        }
        $first = $this->youtube(['title' => 'Prioritas manual', 'sort_order' => 0]);
        $this->youtube(['title' => 'Private', 'status' => 'draft']);
        $page = app(GalleryFeed::class)->page();
        $this->assertCount(12, $page['items']);
        $this->assertSame('gallery-'.$first->id, $page['items']->first()['key']);
        $this->assertSame(2, $page['next']);
        $this->get('/publikasi')->assertSee('Muat Lebih Banyak')->assertDontSee('Private');
        $response = $this->getJson('/publikasi/galeri?media_page=2')->assertOk()->assertJsonPath('next', null);
        $this->assertSame(3, substr_count($response->json('html'), 'data-gallery-key='));
        $this->getJson('/publikasi/galeri?media_page=-1')->assertUnprocessable();
        $entry = $this->youtube(['poster_path' => 'media/22222222-2222-4222-8222-222222222222.jpg']);
        $this->assertStringContainsString('i.ytimg.com', app(GalleryFeed::class)->medium($entry)['thumbnail']);
    }

    public function test_deleting_media_preserves_a_file_referenced_by_another_entry(): void
    {
        $path = app(GalleryStorage::class)->store(UploadedFile::fake()->image('shared.png'));
        $first = GalleryMedia::create($this->data() + ['file_path' => $path, 'mime_type' => 'image/png']);
        $second = GalleryMedia::create($this->data() + ['file_path' => $path, 'mime_type' => 'image/png']);
        $this->actingAs(User::factory()->create(['role' => 'editor']))->delete('/admin/gallery/'.$first->id)->assertRedirect();
        Storage::disk('gallery')->assertExists($path);
        $this->delete('/admin/gallery/'.$second->id)->assertRedirect();
        Storage::disk('gallery')->assertMissing($path);
    }
}
