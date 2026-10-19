<?php

namespace App\Http\Controllers;

use App\Models\GalleryMedia;
use App\Services\GalleryFeed;
use App\Services\GalleryStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request, GalleryFeed $feed)
    {
        $data = $request->validate(['media_page' => 'nullable|integer|min:1|max:100000']);
        $gallery = $feed->page($data['media_page'] ?? 1);

        return response()->json([
            'html' => view('public.gallery.items', ['items' => $gallery['items']])->render(),
            'next' => $gallery['next'] ? route('gallery.index', ['media_page' => $gallery['next']]) : null,
        ])->header('Cache-Control', 'private, no-store');
    }

    public function file(Request $request, GalleryMedia $medium, GalleryStorage $storage)
    {
        if ($request->routeIs('admin.gallery.*')) {
            abort_unless($request->user()?->can('manage-content'), 403);
        } else {
            abort_unless($medium->status === 'published', 404);
        }
        $poster = $request->routeIs('*.poster');
        $path = $poster ? $medium->poster_path : $medium->file_path;
        $mime = $poster ? $medium->poster_mime_type : $medium->mime_type;
        abort_unless($storage->exists($path) && in_array($mime, ['image/jpeg', 'image/png', 'image/webp', 'video/mp4'], true), 404);

        return response()->file(Storage::disk('gallery')->path($path), [
            'Content-Type' => $mime, 'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff', 'X-Robots-Tag' => 'noindex',
        ]);
    }
}
