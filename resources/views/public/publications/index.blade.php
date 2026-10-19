@extends('layouts.public')
@section('title', 'Publikasi Perusahaan | PT. Syirkah Mandiri Artomoro')
@section('description', 'Jelajahi dokumen, panduan, dan informasi yang dipublikasikan oleh PT. Syirkah Mandiri Artomoro.')
@push('head')
    @vite(['resources/css/publications.css', 'resources/js/publications.js'])
@endpush
@section('content')
<div class="publications-page">
    <div class="container publications-intro">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a><span aria-hidden="true">/</span><span aria-current="page">Publikasi Perusahaan</span></nav>
        <p class="eyebrow publications-eyebrow">PUBLICATIONS</p>
        <h1>Publikasi Perusahaan</h1>
        <p class="publications-lead">Jelajahi dokumen, panduan, dan informasi yang dipublikasikan oleh PT. Syirkah Mandiri Artomoro.</p>
    </div>
    <section class="publications-section publications-band" aria-labelledby="publications-title">
        <div class="container">
            <div class="publications-heading"><p class="eyebrow">01 / PUBLIKASI</p><h2 id="publications-title">Publikasi Perusahaan</h2><p>Jelajahi dokumen, panduan, dan materi informasi yang dipublikasikan oleh SMART.</p></div>
            @if($publications->isEmpty())
                <div class="publications-empty"><x-icon name="files"/><div><h3>Publikasi perusahaan belum tersedia.</h3><p>Materi informasi akan ditampilkan setelah disetujui untuk publikasi.</p></div></div>
            @else
                <div class="publications-grid">@foreach($publications as $document)@include('public.publications.card')@endforeach</div>
            @endif
        </div>
    </section>
    <section class="publications-section" aria-labelledby="verification-title">
        <div class="container publications-verification"><div><p class="eyebrow">02 / INFORMASI</p><h2 id="verification-title">Memerlukan informasi lebih lanjut?</h2></div><div><p>Untuk informasi lebih lanjut mengenai publikasi, dokumentasi, atau kebutuhan informasi perusahaan, silakan menghubungi tim PT. Syirkah Mandiri Artomoro.</p><a class="text-link" href="{{ route('contact') }}">Hubungi SMART <x-icon name="arrow-up-right"/></a></div></div>
    </section>
</div>
@include('partials.inquiry-cta')
@endsection
