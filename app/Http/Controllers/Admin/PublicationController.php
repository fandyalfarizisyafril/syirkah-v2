<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicationRequest;
use App\Models\Publication;
use App\Services\PublicationStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => 'nullable|string|max:150',
            'category' => ['nullable', Rule::in(array_keys(Publication::CATEGORIES))],
            'status' => ['nullable', Rule::in(['draft', 'published'])],
        ]);
        $entries = Publication::query()->when($filters['q'] ?? null, fn ($q, $term) => $q->where('title', 'like', '%'.$term.'%'))
            ->when($filters['category'] ?? null, fn ($q, $value) => $q->where('category', $value))
            ->when($filters['status'] ?? null, fn ($q, $value) => $q->where('status', $value))
            ->ordered()->paginate(15)->withQueryString();

        return view('admin.publications.index', compact('entries'));
    }

    public function create()
    {
        return view('admin.publications.form', ['entry' => new Publication]);
    }

    public function edit(Publication $publication)
    {
        return view('admin.publications.form', ['entry' => $publication]);
    }

    public function store(PublicationRequest $request, PublicationStorage $storage)
    {
        return $this->save($request, new Publication, $storage);
    }

    public function update(PublicationRequest $request, Publication $publication, PublicationStorage $storage)
    {
        return $this->save($request, $publication, $storage);
    }

    private function save(PublicationRequest $request, Publication $publication, PublicationStorage $storage)
    {
        $data = $request->safe()->except(['document', 'thumbnail', 'remove_thumbnail', 'legal_confirmation']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['allow_download'] = $request->boolean('allow_download');
        if (! empty($data['published_at'])) {
            $data['published_at'] = Carbon::createFromFormat('!Y-m-d\TH:i', $data['published_at'], config('publications.timezone'))
                ->setTimezone(config('app.timezone'));
        }
        $newPaths = [];
        $oldPaths = [];
        try {
            if ($file = $request->file('document')) {
                $newPaths[] = $data['file_path'] = $storage->store($file);
                $data['original_filename'] = $storage->filename($file);
                $data['mime_type'] = $file->getMimeType();
                $data['file_size'] = $file->getSize();
            }
            if ($file = $request->file('thumbnail')) {
                $newPaths[] = $data['thumbnail_path'] = $storage->store($file);
                $data['thumbnail_mime_type'] = $file->getMimeType();
            } elseif ($request->boolean('remove_thumbnail')) {
                $data['thumbnail_path'] = $data['thumbnail_mime_type'] = null;
            }
            DB::transaction(function () use ($publication, &$data, &$oldPaths) {
                $entry = $publication->exists ? Publication::lockForUpdate()->findOrFail($publication->id) : $publication;
                foreach (['file_path', 'thumbnail_path'] as $field) {
                    if (array_key_exists($field, $data) && $data[$field] !== $entry->$field) {
                        $oldPaths[] = $entry->$field;
                    }
                }
                $data['published_at'] = $data['published_at'] ?? $entry->published_at ?? ($data['status'] === 'published' ? now() : null);
                $entry->fill($data)->save();
            });
        } catch (\Throwable $exception) {
            foreach ($newPaths as $path) {
                $this->cleanup($storage, $path);
            }
            report($exception);

            return back()->withInput($request->except(['document', 'thumbnail']))
                ->withErrors(['document' => 'Dokumen belum berhasil disimpan. Periksa penyimpanan server lalu unggah ulang file.']);
        }
        foreach ($oldPaths as $path) {
            $this->cleanup($storage, $path);
        }

        return redirect()->route('admin.publications.index')->with('success', 'Publikasi berhasil disimpan.');
    }

    public function destroy(Publication $publication, PublicationStorage $storage)
    {
        $paths = DB::transaction(function () use ($publication) {
            $entry = Publication::lockForUpdate()->findOrFail($publication->id);
            $paths = [$entry->file_path, $entry->thumbnail_path];
            $entry->delete();

            return $paths;
        });
        foreach ($paths as $path) {
            $this->cleanup($storage, $path);
        }

        return redirect()->route('admin.publications.index')->with('success', 'Publikasi berhasil dihapus.');
    }

    private function cleanup(PublicationStorage $storage, ?string $path): void
    {
        try {
            $storage->delete($path);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
