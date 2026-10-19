<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\GalleryMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GalleryFeed
{
    public function page(int $page = 1): array
    {
        $brands = Brand::published()->whereIn('video->source', ['mp4', 'youtube'])
            ->selectRaw("id, 'brand' as origin, sort_order, updated_at as media_date");
        $gallery = GalleryMedia::where('status', 'published')
            ->selectRaw("id, 'gallery' as origin, sort_order, created_at as media_date");
        $rows = DB::query()->fromSub($gallery->unionAll($brands), 'media_feed')
            ->orderByRaw('COALESCE(sort_order, 2147483647)')->orderByDesc('media_date')->orderBy('origin')->orderByDesc('id')
            ->simplePaginate(8, ['*'], 'media_page', $page);
        $keys = collect($rows->items());
        $brandModels = Brand::whereIn('id', $keys->where('origin', 'brand')->pluck('id'))->get()->keyBy('id');
        $galleryModels = GalleryMedia::whereIn('id', $keys->where('origin', 'gallery')->pluck('id'))->get()->keyBy('id');
        $items = $keys->map(function ($row) use ($brandModels, $galleryModels) {
            $entry = $row->origin === 'brand' ? $brandModels->get($row->id) : $galleryModels->get($row->id);

            return $entry ? ($row->origin === 'brand' ? $this->brand($entry) : $this->medium($entry)) : null;
        })->filter()->values();

        return ['items' => $items, 'next' => $rows->hasMorePages() ? $page + 1 : null];
    }

    public function brand(Brand $brand): ?array
    {
        $video = $brand->video;
        if ($brand->status !== 'published' || ! $video) {
            return null;
        }
        $source = $video['source'] ?? '';
        if ($source === 'mp4') {
            $path = $video['path'] ?? '';
            if (! preg_match('~^catalog/brand-videos/[a-f0-9-]{36}\.mp4$~D', $path) || ! Storage::disk('public')->exists($path)) {
                return null;
            }
            $url = route('brands.video', $brand->slug);
        } elseif ($source === 'youtube' && preg_match('/^[a-zA-Z0-9_-]{11}$/D', $video['youtube_id'] ?? '')) {
            $url = 'https://www.youtube.com/watch?v='.$video['youtube_id'];
        } else {
            return null;
        }
        $poster = $video['poster'] ?? null;
        $thumbnail = $poster && Storage::disk('public')->exists($poster) ? asset('storage/'.$poster) : null;

        return [
            'key' => 'brand-'.$brand->id, 'source' => $source, 'label' => 'VIDEO BRAND',
            'title' => ($video['title'] ?? null) ?: 'Video '.$brand->name,
            'description' => $video['description'] ?? null, 'url' => $url,
            'youtube_id' => $video['youtube_id'] ?? null,
            'thumbnail' => $thumbnail ?: ($source === 'youtube' ? 'https://i.ytimg.com/vi/'.$video['youtube_id'].'/hqdefault.jpg' : null),
            'width' => max(1, (int) ($video['width'] ?? 16)), 'height' => max(1, (int) ($video['height'] ?? 9)),
        ];
    }

    public function medium(GalleryMedia $medium, bool $admin = false): ?array
    {
        $storage = app(GalleryStorage::class);
        $prefix = $admin ? 'admin.gallery.' : 'gallery.';
        if (! $admin && $medium->status !== 'published') {
            return null;
        }
        if ($medium->source === 'youtube') {
            if (! preg_match('/^[a-zA-Z0-9_-]{11}$/D', $medium->youtube_id ?? '')) {
                return null;
            }
            $url = 'https://www.youtube.com/watch?v='.$medium->youtube_id;
        } else {
            if (! $storage->exists($medium->file_path)) {
                return null;
            }
            $url = route($prefix.'file', $medium);
        }
        $thumbnail = $medium->source === 'photo' ? $url : ($storage->exists($medium->poster_path) ? route($prefix.'poster', $medium) : null);

        return [
            'key' => 'gallery-'.$medium->id, 'source' => $medium->source,
            'label' => $medium->source === 'photo' ? 'GALERI FOTO' : 'VIDEO PERUSAHAAN',
            'title' => $medium->display_title, 'description' => $medium->description, 'url' => $url,
            'youtube_id' => $medium->youtube_id,
            'thumbnail' => $thumbnail ?: ($medium->source === 'youtube' ? 'https://i.ytimg.com/vi/'.$medium->youtube_id.'/hqdefault.jpg' : null),
            'width' => $medium->width, 'height' => $medium->height,
        ];
    }
}
