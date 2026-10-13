@extends('layouts.admin')
@section('title', ($entry->exists ? 'Edit' : 'Tambah').' Publikasi')
@section('content')
@vite(['resources/css/publications.css', 'resources/js/publications.js'])
<div class="publications-admin">
    <div class="admin-heading"><div><a class="text-link" href="{{ route('admin.publications.index') }}"><x-icon name="arrow-left"/>Publikasi</a><h1>{{ $entry->exists ? 'Edit' : 'Tambah' }} Publikasi</h1></div>@if($entry->exists)<a class="button secondary" href="{{ route('admin.publications.preview', $entry) }}"><x-icon name="eye"/>Preview</a>@endif</div>
    <form method="post" enctype="multipart/form-data" action="{{ $entry->exists ? route('admin.publications.update', $entry) : route('admin.publications.store') }}" class="editor-form" data-submit-form data-publication-editor>
        @csrf @if($entry->exists)@method('PUT')@endif
        <section class="form-section"><h2>Informasi Dokumen</h2>
            <x-field name="title" label="Judul Dokumen" :value="$entry->title" required maxlength="200"/>
            <x-field name="description" type="textarea" label="Deskripsi" :value="$entry->description" maxlength="3000" rows="4"/>
            <div class="field"><label for="category">Kategori *</label><select id="category" name="category" required><option value="">Pilih kategori</option>@foreach(\App\Models\Publication::CATEGORIES as $value => $label)<option value="{{ $value }}" @selected(old('category', $entry->category) === $value)>{{ $label }}</option>@endforeach</select></div>
        </section>
        <section class="form-section"><h2>File & Thumbnail</h2>
            @if($entry->exists)<p class="publication-filename">File saat ini: {{ $entry->original_filename }} ({{ number_format($entry->file_size / 1024, 0, ',', '.') }} KB)</p>@endif
            <x-field name="document" type="file" :label="'File Dokumen (PDF, JPG, PNG, WEBP; maks. '.number_format(config('publications.max_upload_kb') / 1024, 1).' MB)'" :required="!$entry->exists" accept=".pdf,.jpg,.jpeg,.png,.webp"/>
            @if($entry->exists)<p class="publication-help">Kosongkan unggahan untuk mempertahankan file saat ini.</p>@endif
            <x-field name="thumbnail" type="file" :label="'Thumbnail opsional (JPG, PNG, WEBP; maks. '.number_format(config('publications.max_thumbnail_kb') / 1024, 1).' MB)'" accept=".jpg,.jpeg,.png,.webp"/>
            @if(app(\App\Services\PublicationStorage::class)->exists($entry->thumbnail_path))<img class="admin-preview" src="{{ route('admin.publications.thumbnail', $entry) }}" alt="Thumbnail saat ini" width="180" height="140"><label class="check-field"><input type="checkbox" name="remove_thumbnail" value="1" @checked(old('remove_thumbnail'))>Hapus thumbnail saat ini</label>@endif
        </section>
        <section class="form-section"><h2>Pengaturan Publikasi</h2>
            <div class="form-grid">
                <div class="field"><label for="status">Status *</label><select id="status" name="status" required>@foreach(['draft', 'published'] as $status)<option value="{{ $status }}" @selected(old('status', $entry->status) === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
                <x-field name="sort_order" label="Urutan Tampilan" type="number" :value="$entry->sort_order" min="0" max="100000"/>
                <x-field name="published_at" label="Tanggal Publikasi (WIB)" type="datetime-local" :value="$entry->published_at?->copy()->setTimezone(config('publications.timezone'))->format('Y-m-d\TH:i')"/>
            </div>
            <p class="publication-help">Zona waktu: WIB (UTC+7). Tanggal kosong menggunakan waktu publikasi pertama; tanggal mendatang menjadwalkan akses publik.</p>
            <input type="hidden" name="allow_download" value="0"><label class="check-field"><input type="checkbox" name="allow_download" value="1" @checked(old('allow_download', $entry->allow_download))>Izinkan tombol Unduh</label>
            <p class="publication-help">File yang dapat dilihat tetap dapat disimpan melalui fasilitas browser, meskipun tombol Unduh dinonaktifkan.</p>
            <div class="publication-approval" data-publication-approval><p>Pastikan dokumen ini telah disetujui perusahaan untuk ditampilkan kepada publik dan tidak mengandung informasi sensitif yang perlu disamarkan.</p><label class="check-field"><input type="checkbox" name="legal_confirmation" value="1">Saya mengonfirmasi persetujuan publikasi dokumen legalitas ini.</label></div>
            @if($entry->is_demo)<p class="publication-help">Dokumen DEMO. Legalitas demo tidak dapat dipublikasikan. Demo publikasi hanya tersedia untuk pengujian lokal atau staging.</p>@endif
        </section>
        <div class="form-actions"><button class="button" type="submit"><x-icon name="save"/>Simpan Publikasi</button><a class="button secondary" href="{{ route('admin.publications.index') }}">Batal</a></div>
    </form>
</div>
@endsection
