@extends($admin ? 'layouts.admin' : 'layouts.public')
@section('title', $publication->title.' | Publikasi SMART')
@section('description', 'Preview dokumen '.$publication->title)
@section('content')
@vite(['resources/css/publications.css', 'resources/js/publications.js'])
@php($prefix = $admin ? 'admin.publications.' : 'publications.')
<div class="publications-page publication-viewer {{ $admin ? '' : 'container' }}">
    <a class="text-link" href="{{ route($prefix.'index') }}"><x-icon name="arrow-left"/>Kembali ke Publikasi</a>
    <div class="publication-viewer-heading"><div><p class="eyebrow">{{ $publication->category_label }} / {{ $publication->file_type }}@if($admin) / {{ ucfirst($publication->status) }}@endif</p><h1>{{ $publication->title }}</h1></div>
        @if($publication->allow_download)<a class="button secondary" href="{{ route($prefix.'download', $publication) }}"><x-icon name="download"/>Unduh</a>@endif
    </div>
    @if($publication->is_demo)<p class="publication-demo-notice" role="note">DEMO &mdash; BUKAN DOKUMEN {{ $publication->category === 'legalitas' ? 'LEGALITAS' : 'RESMI' }}</p>@endif
    @if($publication->mime_type === 'application/pdf')
        <object class="publication-pdf" data="{{ route($prefix.'file', $publication) }}" type="application/pdf" aria-label="Preview {{ $publication->title }}"><p>Preview PDF tidak didukung browser ini. <a class="text-link" href="{{ route($prefix.'file', $publication) }}" target="_blank" rel="noopener noreferrer">Buka dokumen <x-icon name="external-link"/></a></p></object>
        <p class="publication-viewer-fallback">PDF tidak tampil? <a class="text-link" href="{{ route($prefix.'file', $publication) }}" target="_blank" rel="noopener noreferrer">Buka di tab baru <x-icon name="external-link"/></a></p>
    @else
        <img class="publication-full-image" src="{{ route($prefix.'file', $publication) }}" alt="{{ $publication->title }}">
    @endif
</div>
@unless($admin)@include('partials.inquiry-cta')@endunless
@endsection
