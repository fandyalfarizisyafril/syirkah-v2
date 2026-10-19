@extends('layouts.admin')
@section('title', 'Galeri & Media')
@section('content')
<div class="admin-heading"><div><p class="eyebrow">MEDIA PERUSAHAAN</p><h1>Galeri & Media</h1></div><div class="actions"><a class="text-link" href="{{ route('admin.catalog.index', 'brands') }}">Video Brand <x-icon name="arrow-up-right"/></a><a class="button" href="{{ route('admin.gallery.create') }}"><x-icon name="plus"/>Tambah Media</a></div></div>
<div class="table-wrap"><table><thead><tr><th>Judul</th><th>Kategori</th><th>Sumber</th><th>Status</th><th>Urutan</th><th>Diperbarui</th><th>Aksi</th></tr></thead><tbody>
@forelse($entries as $entry)
    <tr><td><a class="table-name" href="{{ route('admin.gallery.edit', $entry) }}">{{ $entry->display_title }}</a></td><td>{{ $entry->category_label }}</td><td>{{ strtoupper($entry->source) }}</td><td><span @class(['badge', 'published' => $entry->status === 'published'])>{{ ucfirst($entry->status) }}</span></td><td>{{ $entry->sort_order ?? '-' }}</td><td>{{ $entry->updated_at->format('d M Y') }}</td><td><div class="actions compact">
        <a class="icon-button" href="{{ route('admin.gallery.edit', $entry) }}" title="Edit / Preview" aria-label="Edit {{ $entry->display_title }}"><x-icon name="pencil"/></a>
        <form method="post" action="{{ route('admin.gallery.destroy', $entry) }}" data-confirm="Hapus media ini beserta file miliknya? Tindakan ini tidak dapat dibatalkan.">@csrf @method('DELETE')<button type="submit" class="icon-button danger" title="Hapus" aria-label="Hapus {{ $entry->display_title }}"><x-icon name="trash-2"/></button></form>
    </div></td></tr>
@empty
    <tr><td colspan="7" class="table-empty">Belum ada media perusahaan.</td></tr>
@endforelse
</tbody></table></div>
{{ $entries->links() }}
@endsection
