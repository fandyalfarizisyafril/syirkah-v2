<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Services\PublicationStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    private const HEADERS = [
        'Cache-Control' => 'private, no-store, no-cache, max-age=0, must-revalidate',
        'Pragma' => 'no-cache', 'Expires' => '0', 'X-Content-Type-Options' => 'nosniff',
        'X-Robots-Tag' => 'noindex, noarchive',
    ];

    public function index(PublicationStorage $storage)
    {
        $documents = Publication::published()->ordered()->get()->filter(fn ($entry) => $storage->exists($entry->file_path));

        return response()->view('public.publications.index', [
            'publications' => $documents->where('category', 'perusahaan'),
        ])->withHeaders(array_diff_key(self::HEADERS, ['X-Robots-Tag' => true]));
    }

    private function authorizeDocument(Request $request, Publication $publication): bool
    {
        $admin = $request->routeIs('admin.publications.*');
        if ($admin) {
            abort_unless($request->user()?->can('manage-content'), 403, '', self::HEADERS);
        } else {
            abort_unless(Publication::published()->whereKey($publication->id)->exists(), 404, '', self::HEADERS);
        }

        return $admin;
    }

    public function preview(Request $request, Publication $publication, PublicationStorage $storage)
    {
        $admin = $this->authorizeDocument($request, $publication);
        abort_unless($storage->exists($publication->file_path), 404, '', self::HEADERS);

        return response()->view('public.publications.preview', compact('publication', 'admin'))->withHeaders(self::HEADERS);
    }

    public function file(Request $request, Publication $publication, PublicationStorage $storage)
    {
        $this->authorizeDocument($request, $publication);
        $download = $request->routeIs('*.download');
        abort_if($download && ! $publication->allow_download, 403, '', self::HEADERS);
        $thumbnail = $request->routeIs('*.thumbnail');
        $path = $thumbnail ? $publication->thumbnail_path : $publication->file_path;
        $mime = $thumbnail ? $publication->thumbnail_mime_type : $publication->mime_type;
        abort_unless($storage->exists($path), 404, '', self::HEADERS);
        abort_unless(in_array($mime, ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'], true), 404, '', self::HEADERS);

        return response()->file(Storage::disk('publications')->path($path), self::HEADERS + [
            'Content-Type' => $mime,
            'Content-Security-Policy' => "sandbox; default-src 'none'; frame-ancestors 'self'",
            'X-Frame-Options' => 'SAMEORIGIN',
        ])->setContentDisposition($download ? 'attachment' : 'inline',
            $thumbnail ? 'thumbnail.'.pathinfo($path, PATHINFO_EXTENSION) : $publication->original_filename);
    }
}
