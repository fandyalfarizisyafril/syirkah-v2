<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BrandVideoController extends Controller
{
    public function __invoke(string $slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        abort_unless($brand->status === 'published' || Gate::allows('manage-content'), 404);
        $video = $brand->video;
        $path = $video['path'] ?? '';
        abort_unless(($video['source'] ?? '') === 'mp4' && preg_match('~^catalog/brand-videos/[a-f0-9-]{36}\.mp4$~D', $path), 404);
        $disk = Storage::disk('public');
        abort_unless($disk->exists($path), 404);

        // BinaryFileResponse handles Range requests for native seeking, including artisan serve.
        return response()->file($disk->path($path), [
            'Content-Type' => 'video/mp4', 'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-cache, max-age=0, must-revalidate',
        ]);
    }
}
