@extends('layouts.public')
@section('title', 'Publikasi & Media | PT. Syirkah Mandiri Artomoro')
@section('description', 'Jelajahi video brand, dokumentasi produk, dan berbagai informasi yang dipublikasikan oleh PT. Syirkah Mandiri Artomoro.')
@push('head')
    @vite(['resources/css/publications.css', 'resources/js/publications.js'])
    @vite('resources/js/gallery.js')
@endpush
@section('content')
<div class="publications-page">
    <div class="container publications-intro">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Publikasi</span></nav>
        <p class="eyebrow publications-eyebrow">PUBLICATIONS & MEDIA</p>
        <h1>Informasi dan dokumentasi perusahaan.</h1>
        <p class="publications-lead">Jelajahi video brand, dokumentasi produk, dan berbagai informasi yang dipublikasikan oleh PT. Syirkah Mandiri Artomoro.</p>
    </div>
    <section class="publications-section gallery-section" aria-labelledby="gallery-title" data-gallery>
        <div class="container">
            <div class="publications-heading"><p class="eyebrow">01 / GALERI & MEDIA</p><h2 id="gallery-title">Galeri & Media</h2><p>Jelajahi video, dokumentasi produk, dan aktivitas perusahaan SMART.</p></div>
            @if($gallery['items']->isEmpty())
                <div class="publications-empty" data-gallery-empty><x-icon name="image"/><div><h3>Media belum tersedia.</h3><p>Dokumentasi perusahaan akan ditampilkan setelah dipublikasikan.</p></div></div>
            @endif
            <div class="directory-grid gallery-masonry" id="gallery-items" data-gallery-grid>@include('public.gallery.items', ['items' => $gallery['items']])</div>
            <div class="gallery-more">
                @if($gallery['next'])<a class="button secondary" href="{{ route('publications.index', ['media_page' => $gallery['next']]) }}#gallery-title" data-gallery-more data-next="{{ route('gallery.index', ['media_page' => $gallery['next']]) }}" aria-controls="gallery-items">Muat Lebih Banyak <x-icon name="plus"/></a>@endif
                <p data-gallery-status role="status" aria-live="polite"></p>
            </div>
        </div>
        <dialog class="gallery-dialog" data-gallery-dialog aria-labelledby="gallery-dialog-title">
            <div class="gallery-dialog-header"><h2 id="gallery-dialog-title"></h2><button type="button" class="icon-button" data-gallery-close aria-label="Tutup media" title="Tutup media"><x-icon name="x"/></button></div>
            <div class="gallery-dialog-media" data-gallery-viewer></div>
            <p class="gallery-dialog-description" data-gallery-description></p>
            <a class="text-link" data-gallery-original target="_blank" rel="noopener noreferrer">Buka media di tab baru <x-icon name="external-link"/></a>
        </dialog>
    </section>
    <section class="publications-section publications-band" aria-labelledby="publications-title">
        <div class="container">
            <div class="publications-heading"><p class="eyebrow">02 / PUBLIKASI</p><h2 id="publications-title">Publikasi Perusahaan</h2><p>Jelajahi dokumen, panduan, dan materi informasi yang dipublikasikan oleh SMART.</p></div>
            @if($publications->isEmpty())
                <div class="publications-empty"><x-icon name="files"/><div><h3>Publikasi perusahaan belum tersedia.</h3><p>Materi informasi akan ditampilkan setelah disetujui untuk publikasi.</p></div></div>
            @else
                <div class="publications-grid">@foreach($publications as $document)@include('public.publications.card')@endforeach</div>
            @endif
        </div>
    </section>
    <section class="publications-section" aria-labelledby="verification-title">
        <div class="container publications-verification"><div><p class="eyebrow">03 / INFORMASI</p><h2 id="verification-title">Memerlukan informasi lebih lanjut?</h2></div><div><p>Untuk kebutuhan verifikasi dokumen atau informasi administrasi perusahaan, silakan menghubungi tim PT. Syirkah Mandiri Artomoro.</p><a class="text-link" href="{{ route('contact') }}">Hubungi SMART <x-icon name="arrow-up-right"/></a></div></div>
    </section>
</div>
@include('partials.inquiry-cta')
@endsection
