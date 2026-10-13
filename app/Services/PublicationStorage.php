<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicationStorage
{
    public function store(UploadedFile $file): string
    {
        $extension = match ($file->getMimeType()) {
            'application/pdf' => 'pdf', 'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp',
            default => throw new \InvalidArgumentException('Unsupported publication file.'),
        };

        return $file->storeAs('documents', Str::uuid().'.'.$extension, 'publications');
    }

    public function filename(UploadedFile $file): string
    {
        $name = basename(str_replace('\\', '/', $file->getClientOriginalName()));
        $name = preg_replace('/[^\pL\pN ._()-]/u', '_', $name) ?: 'dokumen';

        return Str::limit(trim($name, '. '), 200, '') ?: 'dokumen';
    }

    public function validPath(?string $path): bool
    {
        return is_string($path) && (bool) preg_match('~^documents/[a-f0-9-]{36}\.(pdf|jpg|png|webp)$~D', $path);
    }

    public function exists(?string $path): bool
    {
        return $this->validPath($path) && Storage::disk('publications')->exists($path);
    }

    public function delete(?string $path): void
    {
        if ($this->validPath($path)) {
            Storage::disk('publications')->delete($path);
        }
    }
}
