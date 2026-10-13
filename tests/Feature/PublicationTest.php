<?php

namespace Tests\Feature;

use App\Models\Publication;
use App\Models\User;
use App\Services\PublicationStorage;
use Database\Seeders\PublicationDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('publications');
    }

    private function admin(string $role = 'administrator'): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function pdf(string $name = 'demo.pdf'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, PublicationDemoSeeder::pdf());
    }

    private function data(array $override = []): array
    {
        return array_replace(['title' => 'Dokumen Pengujian', 'description' => 'Materi demo.', 'category' => 'perusahaan', 'status' => 'draft', 'allow_download' => '0', 'sort_order' => 0], $override);
    }

    private function document(array $override = []): Publication
    {
        $path = app(PublicationStorage::class)->store($this->pdf());

        return Publication::create(array_replace($this->data(), [
            'file_path' => $path, 'original_filename' => 'demo.pdf', 'mime_type' => 'application/pdf', 'file_size' => 100,
        ], $override));
    }

    public function test_new_page_redirect_navigation_seo_and_empty_states(): void
    {
        $this->get('/industri')->assertStatus(301)->assertRedirect('/publikasi');
        $response = $this->get('/publikasi')->assertOk()->assertSee('Transparansi dan informasi perusahaan.')
            ->assertSee('Dokumen legalitas belum tersedia untuk ditampilkan.')
            ->assertSee('Publikasi perusahaan belum tersedia.')
            ->assertSee('<title>Publikasi &amp; Legalitas | PT. Syirkah Mandiri Artomoro</title>', false)
            ->assertSee('href="'.url('/publikasi').'"', false);
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->get('/sitemap.xml')->assertSee(url('/publikasi'))->assertDontSee('<loc>'.url('/industri').'</loc>', false);
        $this->get('/')->assertSee('href="'.route('publications.index').'"', false)->assertDontSee('href="'.url('/industri').'"', false);
    }

    public function test_all_admin_routes_require_authentication_and_content_permission(): void
    {
        $entry = $this->document();
        $paths = ['/admin/publications', '/admin/publications/create', '/admin/publications/'.$entry->id.'/edit',
            '/admin/publications/'.$entry->id.'/preview', '/admin/publications/'.$entry->id.'/file',
            '/admin/publications/'.$entry->id.'/thumbnail', '/admin/publications/'.$entry->id.'/unduh'];
        foreach ($paths as $path) {
            $this->get($path)->assertRedirect('/admin/login');
        }
        $this->post('/admin/publications')->assertRedirect('/admin/login');
        $this->put('/admin/publications/'.$entry->id)->assertRedirect('/admin/login');
        $this->delete('/admin/publications/'.$entry->id)->assertRedirect('/admin/login');
        foreach (['sales', 'none'] as $role) {
            $this->actingAs($this->admin($role));
            foreach ($paths as $path) {
                $this->get($path)->assertForbidden();
            }
            $this->post('/admin/publications', $this->data())->assertForbidden();
            $this->put('/admin/publications/'.$entry->id, $this->data())->assertForbidden();
            $this->delete('/admin/publications/'.$entry->id)->assertForbidden();
        }
        $this->actingAs($this->admin('editor'))->get('/admin/publications')->assertOk();
        $this->get('/admin/publications/create')->assertOk()->assertSee('name="status"', false);
        $this->get('/admin/publications/'.$entry->id.'/edit')->assertOk();
    }

    public function test_login_create_publish_download_replace_revoke_and_delete_workflow(): void
    {
        $user = User::factory()->create(['role' => 'administrator', 'password' => 'PublicationTest123!']);
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'PublicationTest123!'])->assertRedirect('/admin');
        $this->post('/admin/publications', $this->data(['document' => $this->pdf()]))->assertSessionHasNoErrors()->assertRedirect('/admin/publications');
        $entry = Publication::sole();
        $original = $entry->file_path;
        Storage::disk('publications')->assertExists($original);
        $this->assertFalse($entry->allow_download);
        $this->get('/admin/publications/'.$entry->id.'/preview')->assertOk()->assertSee($entry->title);
        $this->get('/admin/publications/'.$entry->id.'/file')->assertOk()->assertHeader('Content-Type', 'application/pdf');
        foreach (['preview', 'file', 'thumbnail', 'unduh'] as $endpoint) {
            $this->get('/publikasi/'.$entry->id.'/'.$endpoint)->assertNotFound();
        }
        $this->get('/publikasi')->assertDontSee($entry->title);
        $this->get('/storage/'.$original)->assertForbidden();
        $this->get('/storage/publications/'.$original)->assertForbidden();
        $this->put('/admin/publications/'.$entry->id, $this->data(['status' => 'published', 'allow_download' => '1']))->assertSessionHasNoErrors();
        $this->assertNotNull($entry->fresh()->published_at);
        $firstDate = $entry->fresh()->published_at;
        $this->get('/publikasi')->assertSee($entry->title)->assertSee(route('publications.download', $entry), false);
        $this->get('/publikasi/'.$entry->id.'/preview')->assertOk()->assertSee('<object', false);
        $file = $this->get('/publikasi/'.$entry->id.'/file')->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringStartsWith('inline;', $file->headers->get('Content-Disposition'));
        $this->assertStringContainsString('no-store', $file->headers->get('Cache-Control'));
        $this->get('/publikasi/'.$entry->id.'/unduh')->assertDownload('demo.pdf');
        $this->put('/admin/publications/'.$entry->id, $this->data(['status' => 'published', 'title' => 'Judul Baru', 'description' => 'Deskripsi Baru']))->assertSessionHasNoErrors();
        $this->get('/publikasi')->assertSee('Judul Baru')->assertSee('Deskripsi Baru')->assertDontSee(route('publications.download', $entry), false);
        $this->get('/publikasi/'.$entry->id.'/unduh')->assertForbidden();
        $this->assertEquals($firstDate, $entry->fresh()->published_at);
        $this->put('/admin/publications/'.$entry->id, $this->data(['document' => $this->pdf('replacement.pdf'), 'status' => 'published']))->assertSessionHasNoErrors();
        $replacement = $entry->fresh()->file_path;
        $this->assertNotSame($original, $replacement);
        Storage::disk('publications')->assertMissing($original);
        Storage::disk('publications')->assertExists($replacement);
        $this->put('/admin/publications/'.$entry->id, $this->data())->assertSessionHasNoErrors();
        $this->app['auth']->forgetGuards();
        foreach (['preview', 'file', 'thumbnail', 'unduh'] as $endpoint) {
            $this->get('/publikasi/'.$entry->id.'/'.$endpoint)->assertNotFound();
        }
        $this->get('/publikasi')->assertDontSee($entry->title);
        $this->actingAs($user)->delete('/admin/publications/'.$entry->id)->assertRedirect('/admin/publications');
        $this->assertDatabaseMissing('publications', ['id' => $entry->id]);
        Storage::disk('publications')->assertMissing($replacement);
    }

    public function test_public_access_stays_private_for_draft_future_and_missing_files(): void
    {
        foreach ([['status' => 'draft'], ['status' => 'published', 'published_at' => now()->addDay()], ['status' => 'published', 'published_at' => null]] as $data) {
            $entry = $this->document($data);
            foreach (['preview', 'file', 'thumbnail', 'unduh'] as $endpoint) {
                $this->get('/publikasi/'.$entry->id.'/'.$endpoint)->assertNotFound();
            }
            $this->get('/publikasi')->assertDontSee($entry->title);
        }
        $entry = $this->document(['status' => 'published', 'published_at' => now()]);
        Storage::disk('publications')->delete($entry->file_path);
        $this->get('/publikasi')->assertDontSee($entry->title);
        $this->get('/publikasi/'.$entry->id.'/file')->assertNotFound();
        $this->get('/publikasi/'.$entry->id.'/preview')->assertNotFound();
        $this->get('/search?q=unique-private-document')->assertDontSee('publications.preview');
    }

    public function test_wib_input_publishes_immediately_and_round_trips_without_shifting(): void
    {
        $this->travelTo(Carbon::parse('2026-10-13 15:50:00', 'UTC'));
        $this->assertSame('UTC', config('app.timezone'));
        $this->actingAs($this->admin());
        $data = $this->data(['status' => 'published', 'published_at' => '2026-10-13T22:49']);
        $this->post('/admin/publications', $data + ['document' => $this->pdf()])->assertSessionHasNoErrors();
        $entry = Publication::sole();
        $this->assertSame('2026-10-13 15:49:00', $entry->getRawOriginal('published_at'));
        $this->get('/admin/publications/'.$entry->id.'/edit')->assertOk()
            ->assertSee('Tanggal Publikasi (WIB)')->assertSee('value="2026-10-13T22:49"', false);
        $this->get('/admin/publications')->assertOk()->assertSee('13 Oct 2026 22:49 WIB');
        $this->put('/admin/publications/'.$entry->id, $data)->assertSessionHasNoErrors();
        $this->assertSame('2026-10-13 15:49:00', $entry->fresh()->getRawOriginal('published_at'));
        $this->put('/admin/publications/'.$entry->id, array_replace($data, ['published_at' => '']))->assertSessionHasNoErrors();
        $this->assertSame('2026-10-13 15:49:00', $entry->fresh()->getRawOriginal('published_at'));
        $this->app['auth']->forgetGuards();
        $this->get('/publikasi')->assertOk()->assertSee($entry->title);
        $this->get('/publikasi/'.$entry->id.'/file')->assertOk();
    }

    public function test_future_wib_schedule_remains_private_until_its_exact_time(): void
    {
        $this->travelTo(Carbon::parse('2026-10-13 16:59:00', 'UTC'));
        $this->actingAs($this->admin());
        $this->post('/admin/publications', $this->data([
            'status' => 'published', 'published_at' => '2026-10-14T00:00', 'document' => $this->pdf(),
        ]))->assertSessionHasNoErrors();
        $entry = Publication::sole();
        $this->assertSame('2026-10-13 17:00:00', $entry->getRawOriginal('published_at'));
        $this->get('/admin/publications')->assertSee('Terjadwal')->assertSee('14 Oct 2026 00:00 WIB');
        $this->app['auth']->forgetGuards();
        $this->get('/publikasi')->assertDontSee($entry->title);
        $this->get('/publikasi/'.$entry->id.'/file')->assertNotFound();
        $this->travelTo(Carbon::parse('2026-10-13 17:00:00', 'UTC'));
        $this->get('/publikasi')->assertSee($entry->title)->assertSee('datetime="2026-10-14"', false);
        $this->get('/publikasi/'.$entry->id.'/file')->assertOk();
    }

    public function test_blank_publish_date_uses_current_storage_time(): void
    {
        $this->travelTo(Carbon::parse('2026-10-13 15:50:00', 'UTC'));
        $this->actingAs($this->admin());
        $this->post('/admin/publications', $this->data([
            'status' => 'published', 'published_at' => '', 'document' => $this->pdf(),
        ]))->assertSessionHasNoErrors();
        $entry = Publication::sole();
        $this->assertSame('2026-10-13 15:50:00', $entry->getRawOriginal('published_at'));
        $this->get('/admin/publications/'.$entry->id.'/edit')->assertSee('value="2026-10-13T22:50"', false);
        $this->get('/publikasi')->assertSee($entry->title);
    }

    public function test_legal_publication_requires_confirmation_each_time(): void
    {
        $this->actingAs($this->admin());
        $data = $this->data(['category' => 'legalitas', 'status' => 'published', 'document' => $this->pdf()]);
        $this->post('/admin/publications', $data)->assertSessionHasErrors('legal_confirmation');
        $this->assertDatabaseCount('publications', 0);
        $data['document'] = $this->pdf();
        $this->post('/admin/publications', $data + ['legal_confirmation' => '1'])->assertSessionHasNoErrors();
        $entry = Publication::sole();
        $this->get('/publikasi')->assertSee($entry->title);
        unset($data['document']);
        $this->put('/admin/publications/'.$entry->id, $data)->assertSessionHasErrors('legal_confirmation');
        $this->put('/admin/publications/'.$entry->id, array_replace($data, ['status' => 'draft']))->assertSessionHasNoErrors();
    }

    public function test_public_sections_follow_admin_category_not_document_title(): void
    {
        $legal = $this->document(['title' => 'Dokumen Disetujui', 'category' => 'legalitas', 'status' => 'published', 'published_at' => now()]);
        $company = $this->document(['title' => 'SK Kemenkumham Pengesahan', 'category' => 'perusahaan', 'status' => 'published', 'published_at' => now()]);
        $draft = $this->document(['title' => 'Dokumen Belum Disetujui', 'category' => 'legalitas']);
        $this->get('/publikasi')->assertOk()
            ->assertViewHas('legalities', fn ($entries) => $entries->values()->modelKeys() === [$legal->id])
            ->assertViewHas('publications', fn ($entries) => $entries->values()->modelKeys() === [$company->id])
            ->assertSee($legal->title)->assertSee($company->title)->assertDontSee($draft->title);
        $this->assertSame('perusahaan', $company->fresh()->category);
    }

    public function test_cards_use_existing_thumbnails_fallback_and_download_permission(): void
    {
        $entry = $this->document(['status' => 'published', 'published_at' => now()]);
        $card = $this->view('public.publications.card', ['document' => $entry]);
        $card->assertSee('publication-placeholder')->assertSee('PDF')->assertDontSee('data-publication-thumbnail')
            ->assertDontSee('publication-download')->assertSee(route('publications.preview', $entry), false);
        $thumbnail = app(PublicationStorage::class)->store(UploadedFile::fake()->createWithContent('thumb.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl6cS8AAAAASUVORK5CYII=')));
        $entry->update(['thumbnail_path' => $thumbnail, 'thumbnail_mime_type' => 'image/png', 'allow_download' => true]);
        $this->view('public.publications.card', ['document' => $entry])->assertSee('data-publication-thumbnail')
            ->assertSee(route('publications.thumbnail', $entry), false)->assertSee('publication-download')
            ->assertSee('Unduh <', false)->assertSee('aria-label="Unduh '.$entry->title.'"', false);
        $this->get('/publikasi/'.$entry->id.'/unduh')->assertDownload('demo.pdf');
        Storage::disk('publications')->delete($thumbnail);
        $this->view('public.publications.card', ['document' => $entry])->assertDontSee('data-publication-thumbnail')->assertSee('publication-placeholder');
    }

    public function test_invalid_mime_signature_extensions_size_and_metadata_are_rejected(): void
    {
        $this->actingAs($this->admin());
        foreach ([['script.php', '<?php echo 1;'], ['pretend.pdf', '<?php echo 1;'], ['fake.jpg', PublicationDemoSeeder::pdf()], ['incomplete.pdf', '%PDF-1.4 broken'], ['fake.png', 'not an image'], ['image.svg', '<svg xmlns="http://www.w3.org/2000/svg"></svg>']] as [$name, $content]) {
            $this->post('/admin/publications', $this->data(['document' => UploadedFile::fake()->createWithContent($name, $content)]))->assertSessionHasErrors('document');
        }
        config(['publications.max_upload_kb' => 1]);
        $this->post('/admin/publications', $this->data(['document' => UploadedFile::fake()->createWithContent('big.pdf', PublicationDemoSeeder::pdf().str_repeat(' ', 2048))]))->assertSessionHasErrors('document');
        $this->post('/admin/publications', $this->data(['category' => 'other', 'status' => 'public', 'sort_order' => -1, 'allow_download' => 'yes', 'published_at' => 'not-a-date']))
            ->assertSessionHasErrors(['category', 'status', 'sort_order', 'allow_download', 'published_at', 'document']);
        $this->assertDatabaseCount('publications', 0);
        $this->assertSame([], Storage::disk('publications')->allFiles());
    }

    public function test_image_preview_thumbnail_removal_and_private_thumbnail(): void
    {
        $this->actingAs($this->admin());
        $image = fn () => UploadedFile::fake()->createWithContent('sample.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl6cS8AAAAASUVORK5CYII='));
        $this->post('/admin/publications', $this->data(['document' => $image(), 'thumbnail' => $image()]))->assertSessionHasNoErrors();
        $entry = Publication::sole();
        $thumbnail = $entry->thumbnail_path;
        $this->get('/publikasi/'.$entry->id.'/thumbnail')->assertNotFound();
        $this->get('/admin/publications/'.$entry->id.'/thumbnail')->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->put('/admin/publications/'.$entry->id, $this->data(['status' => 'published']))->assertSessionHasNoErrors();
        $this->get('/publikasi/'.$entry->id.'/preview')->assertOk()->assertSee('publication-full-image');
        $this->get('/publikasi/'.$entry->id.'/thumbnail')->assertOk();
        $this->put('/admin/publications/'.$entry->id, $this->data(['status' => 'published', 'remove_thumbnail' => '1']))->assertSessionHasNoErrors();
        Storage::disk('publications')->assertMissing($thumbnail);
        $this->get('/publikasi/'.$entry->id.'/thumbnail')->assertNotFound();
        $this->get('/publikasi')->assertSee(route('publications.file', $entry), false);
    }

    public function test_path_traversal_in_storage_metadata_is_rejected_and_names_are_safe(): void
    {
        $entry = $this->document(['status' => 'published', 'published_at' => now(), 'file_path' => '../../.env']);
        $this->get('/publikasi/'.$entry->id.'/file')->assertNotFound();
        $storage = app(PublicationStorage::class);
        foreach (['../secret.pdf', '/etc/passwd', 'documents/../.env', 'C:\\secret.pdf', 'documents/script.php'] as $path) {
            $this->assertFalse($storage->validPath($path));
        }
        $this->assertSame('demo.pdf', $storage->filename($this->pdf('../../demo.pdf')));
    }

    public function test_admin_search_category_status_pagination_and_order(): void
    {
        for ($i = 0; $i < 17; $i++) {
            $this->document(['title' => 'Item '.$i, 'sort_order' => $i]);
        }
        $this->document(['title' => 'Legal Test', 'category' => 'legalitas', 'status' => 'published', 'published_at' => now()]);
        $this->actingAs($this->admin());
        $this->get('/admin/publications')->assertViewHas('entries', fn ($entries) => $entries->total() === 18 && $entries->count() === 15);
        $this->get('/admin/publications?page=2')->assertViewHas('entries', fn ($entries) => $entries->count() === 3);
        $this->get('/admin/publications?q=Legal&category=legalitas&status=published')->assertSee('Legal Test')->assertDontSee('Item 0')
            ->assertViewHas('entries', fn ($entries) => $entries->total() === 1);
        $this->get('/admin/publications?q=missing')->assertSee('Belum ada publikasi yang sesuai.');
    }

    public function test_demo_seed_is_explicit_idempotent_preserves_edits_and_cleanup_preserves_real_data(): void
    {
        $real = $this->document(['title' => 'Non-demo']);
        $this->seed(PublicationDemoSeeder::class);
        $demo = Publication::where('demo_key', 'website-guide')->firstOrFail();
        $demo->update(['title' => 'Edited Demo']);
        $this->seed(PublicationDemoSeeder::class);
        $this->assertDatabaseCount('publications', 4);
        $this->assertSame('Edited Demo', $demo->fresh()->title);
        $this->assertCount(4, Storage::disk('publications')->allFiles());
        $this->get('/publikasi')->assertSee('Edited Demo')->assertDontSee('Contoh Dokumen Administrasi')->assertDontSee('Contoh Materi Publikasi');
        $pdf = Storage::disk('publications')->get($demo->file_path);
        $this->assertStringContainsString('BUKAN DOKUMEN RESMI', $pdf);
        $this->assertStringContainsString('xref', $pdf);
        $this->artisan('publications:clear-demo', ['--force' => true])->assertSuccessful();
        $this->assertDatabaseCount('publications', 1);
        $this->assertSame($real->id, Publication::sole()->id);
        Storage::disk('publications')->assertExists($real->file_path);
        Storage::disk('publications')->assertMissing($demo->file_path);
    }

    public function test_demo_is_never_public_in_production_and_legal_demo_never_publishes(): void
    {
        $this->seed(PublicationDemoSeeder::class);
        $demo = Publication::where('demo_key', 'website-guide')->firstOrFail();
        $legal = Publication::where('category', 'legalitas')->firstOrFail();
        $this->actingAs($this->admin());
        $this->put('/admin/publications/'.$legal->id, $this->data(['category' => 'legalitas', 'status' => 'published', 'legal_confirmation' => '1']))->assertSessionHasErrors('status');
        $this->app['env'] = 'production';
        $this->get('/publikasi')->assertDontSee($demo->title);
        $this->get('/publikasi/'.$demo->id.'/file')->assertNotFound();
        $this->expectException(\RuntimeException::class);
        (new PublicationDemoSeeder)->run();
    }

    public function test_publication_writes_have_csrf_protection(): void
    {
        $this->actingAs($this->admin());
        $this->app['env'] = 'local';
        $this->post('/admin/publications', $this->data())->assertStatus(419);
    }
}
