<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BrandVideo
{
    public static function youtubeId(?string $url): ?string
    {
        $parts = parse_url($url ?? '');
        if (! $parts || ($parts['scheme'] ?? '') !== 'https' || isset($parts['user']) || isset($parts['pass']) || isset($parts['port'])) {
            return null;
        }
        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';
        $id = null;
        if ($host === 'youtu.be') {
            $id = ltrim($path, '/');
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'www.youtube-nocookie.com'], true)) {
            if ($path === '/watch') {
                parse_str($parts['query'] ?? '', $query);
                $id = $query['v'] ?? null;
            } elseif (preg_match('~^/(?:embed|shorts|live)/([\w-]{11})/?$~D', $path, $match)) {
                $id = $match[1];
            }
        }

        return is_string($id) && preg_match('/^[a-zA-Z0-9_-]{11}$/D', $id) ? $id : null;
    }

    public function validate(Request $request, Brand $brand): array
    {
        return $request->validate([
            'video_source' => 'required|in:none,mp4,youtube',
            'video_title' => 'nullable|string|max:200',
            'video_description' => 'nullable|string|max:1500',
            'video_width' => 'nullable|integer|min:1|max:8192',
            'video_height' => 'nullable|integer|min:1|max:8192',
            'video_file' => [Rule::requiredIf(fn () => $request->input('video_source') === 'mp4' && ($brand->video['source'] ?? null) !== 'mp4'), 'nullable', 'file', 'extensions:mp4', 'mimetypes:video/mp4', 'max:51200', function ($attribute, $file, $fail) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $header = file_get_contents($file->getRealPath(), false, null, 0, 12);
                    if (substr($header, 4, 4) !== 'ftyp') {
                        $fail('File harus berupa video MP4 yang valid.');
                    }
                }
            }],
            'video_youtube_url' => ['nullable', 'required_if:video_source,youtube', 'string', 'max:500', function ($attribute, $value, $fail) {
                if (! self::youtubeId($value)) {
                    $fail('Gunakan URL HTTPS YouTube yang valid (watch, youtu.be, shorts, atau embed).');
                }
            }],
            'video_poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|extensions:jpg,jpeg,png,webp|max:4096',
            'remove_video_poster' => 'nullable|boolean',
        ]);
    }

    public function prepare(array $data, Brand $brand, array &$created): ?array
    {
        if ($data['video_source'] === 'none') {
            return null;
        }
        $old = $brand->video ?? [];
        $sameSource = ($old['source'] ?? '') === $data['video_source'];
        $video = [
            'source' => $data['video_source'], 'title' => $data['video_title'] ?? null,
            'description' => $data['video_description'] ?? null,
            'width' => (int) ($data['video_width'] ?? ($sameSource ? ($old['width'] ?? 16) : 16)),
            'height' => (int) ($data['video_height'] ?? ($sameSource ? ($old['height'] ?? 9) : 9)),
        ];
        if ($video['source'] === 'mp4') {
            $video['path'] = isset($data['video_file']) ? $this->store($data['video_file'], 'mp4', $created) : $old['path'];
        } else {
            $video['youtube_id'] = self::youtubeId($data['video_youtube_url']);
            $video['width'] = 16;
            $video['height'] = 9;
        }
        $changed = ! $sameSource || isset($data['video_file']) || (($old['youtube_id'] ?? null) !== ($video['youtube_id'] ?? null));
        $video['poster'] = $changed || ($data['remove_video_poster'] ?? false) ? null : ($old['poster'] ?? null);
        if (isset($data['video_poster'])) {
            $video['poster'] = $this->store($data['video_poster'], $data['video_poster']->extension(), $created);
        }

        return $video;
    }

    private function store(UploadedFile $file, string $extension, array &$created): string
    {
        $path = $file->storeAs('catalog/brand-videos', (string) \Illuminate\Support\Str::uuid().'.'.$extension, 'public');
        if (! $path) {
            throw new \RuntimeException('Video tidak dapat disimpan. Periksa kapasitas dan izin storage.');
        }
        $created[] = $path;

        return $path;
    }

    public function deleteUnused(?array $old, ?array $current): void
    {
        foreach (['path', 'poster'] as $key) {
            $path = $old[$key] ?? null;
            if ($path && $path !== ($current[$key] ?? null) && preg_match('~^catalog/brand-videos/[a-f0-9-]{36}\.(mp4|jpg|jpeg|png|webp)$~D', $path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
