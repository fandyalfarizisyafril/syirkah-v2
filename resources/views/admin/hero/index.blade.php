@extends('layouts.admin')
@section('title', 'Hero Beranda')
@section('content')
<div class="admin-heading"><div><p class="eyebrow">BERANDA</p><h1>Hero Beranda</h1></div><a class="text-link" href="{{ route('home') }}" target="_blank" rel="noopener">Lihat Beranda <x-icon name="external-link"/></a></div>
<div class="table-wrap"><table>
    <thead><tr><th>Slide</th><th>Gambar</th><th>Label Navigasi</th><th>Headline</th><th>Aksi</th></tr></thead>
    <tbody>@foreach($slides as $slide)<tr>
        <td>{{ $slide['number'] }}</td>
        <td><img class="admin-preview" src="{{ asset($slide['image']) }}" alt="{{ $slide['navLabel'] }}" width="180" height="140"></td>
        <td><a class="table-name" href="{{ route('admin.hero.edit', $slide['id']) }}">{{ $slide['navLabel'] }}</a></td>
        <td>{{ implode(' ', $slide['title']) }}</td>
        <td><a class="icon-button" href="{{ route('admin.hero.edit', $slide['id']) }}" title="Edit {{ $slide['navLabel'] }}" aria-label="Edit {{ $slide['navLabel'] }}"><x-icon name="pencil"/></a></td>
    </tr>@endforeach</tbody>
</table></div>
@endsection
