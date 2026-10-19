@extends('layouts.admin')
@section('title', $entry->exists ? 'Edit Media' : 'Tambah Media')
@section('content')
@vite('resources/js/gallery-editor.js')
<div class="admin-heading"><div><a class="text-link" href="{{ route('admin.gallery.index') }}"><x-icon name="arrow-left"/>Galeri & Media</a><h1>{{ $entry->exists ? 'Edit Media' : 'Tambah Media' }}</h1></div></div>
<form method="post" enctype="multipart/form-data" action="{{ $entry->exists ? route('admin.gallery.update', $entry) : route('admin.gallery.store') }}" class="editor-form" data-submit-form data-gallery-editor data-saved-source="{{ $entry->source }}">
    @csrf @if($entry->exists)@method('PUT')@endif
    <section class="form-section"><h2>Informasi Media</h2>
        <x-field name="title" label="Judul (opsional)" :value="$entry->title" maxlength="200"/>
        <x-field name="description" type="textarea" label="Deskripsi (opsional)" :value="$entry->description" rows="3" maxlength="1500"/>
        <div class="field"><label for="source">Jenis media *</label><select id="source" name="source" required>@foreach(['photo' => 'Foto', 'mp4' => 'Video Perusahaan - MP4', 'youtube' => 'Video Perusahaan - YouTube'] as $value => $label)<option value="{{ $value }}" @selected(old('source', $entry->source) === $value)>{{ $label }}</option>@endforeach</select>@error('source')<p class="field-error">{{ $message }}</p>@enderror</div>
        <div data-gallery-file><x-field name="media_file" type="file" label="File media (foto maks. 8 MB; MP4 maks. 50 MB)" accept=".jpg,.jpeg,.png,.webp,.mp4"/></div>
        <div data-gallery-youtube><x-field name="youtube_url" type="url" label="URL YouTube (HTTPS)" :value="$entry->youtube_id ? 'https://www.youtube.com/watch?v='.$entry->youtube_id : ''" maxlength="500"/></div>
        <div data-gallery-poster><x-field name="poster" type="file" label="Poster video (opsional; JPG, PNG, WebP; maks. 4 MB)" accept=".jpg,.jpeg,.png,.webp"/>
            @if($entry->poster_path)<label class="check-field"><input type="checkbox" name="remove_poster" value="1" @checked(old('remove_poster'))>Hapus poster</label>@endif
        </div>
        <input type="hidden" name="width" value="{{ old('width', $entry->width ?? 16) }}"><input type="hidden" name="height" value="{{ old('height', $entry->height ?? 9) }}">
        <p class="field-error" data-gallery-preview-error role="alert" hidden></p>
        <div class="gallery-admin-preview" data-gallery-preview>
            @if($entry->exists)
                @php($preview = app(\App\Services\GalleryFeed::class)->medium($entry, true))
                @if($preview)
                    @if($entry->source === 'photo')<img src="{{ $preview['url'] }}" alt="{{ $entry->display_title }}">
                    @elseif($entry->source === 'mp4')<video controls playsinline preload="none" @if($preview['thumbnail'])poster="{{ $preview['thumbnail'] }}"@endif src="{{ $preview['url'] }}" aria-label="{{ $entry->display_title }}"></video>
                    @else<iframe src="https://www.youtube-nocookie.com/embed/{{ $entry->youtube_id }}?playsinline=1&amp;rel=0" title="{{ $entry->display_title }}" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="encrypted-media; fullscreen; picture-in-picture" allowfullscreen></iframe>@endif
                @endif
            @endif
        </div>
    </section>
    <section class="form-section"><h2>Penayangan</h2><div class="form-grid">
        <div class="field"><label for="status">Status *</label><select name="status" id="status" required><option value="draft" @selected(old('status', $entry->status) === 'draft')>Draft / Tidak tampil</option><option value="published" @selected(old('status', $entry->status) === 'published')>Published / Tampil</option></select>@error('status')<p class="field-error">{{ $message }}</p>@enderror</div>
        <x-field name="sort_order" type="number" label="Urutan manual (opsional)" :value="$entry->sort_order" min="0" max="100000"/>
    </div></section>
    <div class="form-actions"><button type="submit" class="button"><x-icon name="save"/>Simpan Media</button><a class="button secondary" href="{{ route('admin.gallery.index') }}">Batal</a></div>
</form>
@endsection
