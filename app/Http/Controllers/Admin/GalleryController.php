<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Models\GalleryMedia;
use App\Services\BrandVideo;
use App\Services\GalleryStorage;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    public function index()
    {
        return view('admin.gallery.index', ['entries' => GalleryMedia::orderByRaw('COALESCE(sort_order, 2147483647)')->latest()->paginate(20)]);
    }

    public function create()
    {
        return view('admin.gallery.form', ['entry' => new GalleryMedia(['source' => 'photo', 'status' => 'draft'])]);
    }

    public function edit(GalleryMedia $medium)
    {
        return view('admin.gallery.form', ['entry' => $medium]);
    }

    public function store(GalleryRequest $request, GalleryStorage $storage)
    {
        return $this->save($request, new GalleryMedia, $storage);
    }

    public function update(GalleryRequest $request, GalleryMedia $medium, GalleryStorage $storage)
    {
        return $this->save($request, $medium, $storage);
    }

    private function save(GalleryRequest $request, GalleryMedia $medium, GalleryStorage $storage)
    {
        $data = $request->safe()->except(['media_file', 'youtube_url', 'poster', 'remove_poster']);
        $created = [];
        $old = [];
        try {
            DB::transaction(function () use ($request, $medium, $storage, &$data, &$created, &$old) {
                $entry = $medium->exists ? GalleryMedia::lockForUpdate()->findOrFail($medium->id) : $medium;
                $old = [$entry->file_path, $entry->poster_path];
                $changed = $data['source'] !== $entry->source || $request->hasFile('media_file');
                if ($data['source'] === 'youtube') {
                    $data['youtube_id'] = BrandVideo::youtubeId($request->input('youtube_url'));
                    $changed = $changed || $entry->youtube_id !== $data['youtube_id'];
                    $data['file_path'] = $data['mime_type'] = null;
                    $data['width'] = 16;
                    $data['height'] = 9;
                } else {
                    $data['youtube_id'] = null;
                    if ($file = $request->file('media_file')) {
                        $created[] = $data['file_path'] = $storage->store($file);
                        $data['mime_type'] = $file->getMimeType();
                        if ($data['source'] === 'photo') {
                            [$data['width'], $data['height']] = getimagesize($file->getRealPath());
                        }
                    } elseif (! $entry->file_path || $data['source'] !== $entry->source) {
                        throw new \RuntimeException('File diperlukan untuk sumber media ini.');
                    }
                }
                $data['width'] = $data['width'] ?? $entry->width ?? 16;
                $data['height'] = $data['height'] ?? $entry->height ?? 9;
                if ($changed || $request->boolean('remove_poster') || $data['source'] === 'photo') {
                    $data['poster_path'] = $data['poster_mime_type'] = null;
                }
                if ($data['source'] !== 'photo' && ($poster = $request->file('poster'))) {
                    $created[] = $data['poster_path'] = $storage->store($poster);
                    $data['poster_mime_type'] = $poster->getMimeType();
                }
                $entry->fill($data)->save();
            });
        } catch (\Throwable $exception) {
            foreach ($created as $path) {
                $this->cleanup($storage, $path);
            }
            report($exception);

            return back()->withInput($request->except(['media_file', 'poster']))->withErrors(['media_file' => 'Media belum berhasil disimpan. Periksa storage dan unggah ulang file.']);
        }
        foreach ($old as $path) {
            $this->cleanup($storage, $path);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Media berhasil disimpan.');
    }

    public function destroy(GalleryMedia $medium, GalleryStorage $storage)
    {
        $paths = DB::transaction(function () use ($medium) {
            $entry = GalleryMedia::lockForUpdate()->findOrFail($medium->id);
            $paths = [$entry->file_path, $entry->poster_path];
            $entry->delete();

            return $paths;
        });
        foreach ($paths as $path) {
            $this->cleanup($storage, $path);
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Media berhasil dihapus.');
    }

    private function cleanup(GalleryStorage $storage, ?string $path): void
    {
        try {
            $storage->delete($path);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
