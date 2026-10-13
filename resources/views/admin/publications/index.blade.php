@extends('layouts.admin')
@section('title', 'Publikasi')
@section('content')
@vite(['resources/css/publications.css', 'resources/js/publications.js'])
<div class="publications-admin">
    <div class="admin-heading"><div><p class="eyebrow">DOKUMEN PERUSAHAAN</p><h1>Publikasi</h1></div><a class="button" href="{{ route('admin.publications.create') }}"><x-icon name="plus"/>Tambah Publikasi</a></div>
    <form method="get" action="{{ route('admin.publications.index') }}" class="filter-bar" data-submit-form>
        <x-field name="q" label="Pencarian judul" :value="request('q')" maxlength="150"/>
        <div class="field"><label for="category">Kategori</label><select id="category" name="category"><option value="">Semua kategori</option>@foreach(\App\Models\Publication::CATEGORIES as $value => $label)<option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>@endforeach</select></div>
        <div class="field"><label for="status">Status</label><select id="status" name="status"><option value="">Semua status</option>@foreach(['draft', 'published'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
        <button type="submit" class="icon-button" title="Cari" aria-label="Cari"><x-icon name="search"/></button><a class="icon-button" href="{{ route('admin.publications.index') }}" title="Reset" aria-label="Reset"><x-icon name="rotate-ccw"/></a>
    </form>
    <p class="muted">{{ $entries->total() }} dokumen</p>
    <div class="table-wrap"><table><thead><tr><th>Judul</th><th>Kategori</th><th>File</th><th>Status</th><th>Publikasi</th><th>Diperbarui</th><th>Aksi</th></tr></thead><tbody>
        @forelse($entries as $entry)
            <tr>
                <td><a class="table-name" href="{{ route('admin.publications.edit', $entry) }}">{{ $entry->title }}</a>@if($entry->is_demo)<small>DEMO</small>@endif</td>
                <td>{{ $entry->category_label }}</td><td>{{ $entry->file_type }}<small>{{ number_format($entry->file_size / 1024, 0, ',', '.') }} KB</small></td>
                <td><span @class(['badge', 'published' => $entry->status === 'published'])>{{ ucfirst($entry->status) }}</span>@if($entry->status === 'published' && $entry->published_at?->isFuture())<small>Terjadwal</small>@endif</td>
                <td>{{ $entry->published_at ? $entry->published_at->copy()->setTimezone(config('publications.timezone'))->format('d M Y H:i').' WIB' : '-' }}</td><td>{{ $entry->updated_at->copy()->setTimezone(config('publications.timezone'))->format('d M Y H:i') }} WIB</td>
                <td><div class="actions compact">
                    <a class="icon-button" href="{{ route('admin.publications.preview', $entry) }}" title="Preview" aria-label="Preview {{ $entry->title }}"><x-icon name="eye"/></a>
                    <a class="icon-button" href="{{ route('admin.publications.edit', $entry) }}" title="Edit" aria-label="Edit {{ $entry->title }}"><x-icon name="pencil"/></a>
                    <form method="post" action="{{ route('admin.publications.destroy', $entry) }}" data-confirm="Hapus publikasi beserta filenya? Tindakan ini tidak dapat dibatalkan.">@csrf @method('DELETE')<button class="icon-button danger" title="Hapus" aria-label="Hapus {{ $entry->title }}"><x-icon name="trash-2"/></button></form>
                </div></td>
            </tr>
        @empty
            <tr><td colspan="7" class="table-empty">Belum ada publikasi yang sesuai.</td></tr>
        @endforelse
    </tbody></table></div>
    {{ $entries->links() }}
</div>
@endsection
