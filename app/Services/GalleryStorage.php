<?php

namespace App\Services;

use App\Models\GalleryMedia;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryStorage
{
    public function store(UploadedFile $file): string
    {
        $extension = match ($file->getMimeType()) {
            'video/mp4' => 'mp4', 'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp',
        };

        return $file->storeAs('media', Str::uuid().'.'.$extension, 'gallery');
    }

    public function exists(?string $path): bool
    {
        return $this->validPath($path) && Storage::disk('gallery')->exists($path);
    }

    public function validPath(?string $path): bool
    {
        return is_string($path) && (bool) preg_match('~^media/[a-f0-9-]{36}\.(jpg|png|webp|mp4)$~D', $path);
    }

    public function delete(?string $path): void
    {
        if ($this->validPath($path) && ! GalleryMedia::where('file_path', $path)->orWhere('poster_path', $path)->exists()) {
            Storage::disk('gallery')->delete($path);
        }
    }
}
