@extends('layouts.public')
@section('title', ($product->meta_title ?: $product->name).' | Artomoro')
@section('description', $product->meta_description ?: $product->short_description)
@section('og_image', $product->image_url)
@push('head')
    @vite('resources/js/product-details.js')
@endpush
@section('content')
@php
    $gallery = array_values(array_unique(array_filter(array_merge([$product->image], $product->gallery ?? []))));
    $shortDescription = trim($product->short_description ?? '');
    $description = trim($product->description ?? '');
    $sectionNumber = 0;
@endphp
<div class="product-showcase-page technical-detail-page">
    <section class="container product-showcase-hero">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Beranda</a><span>/</span><a href="{{ route('products.index') }}">Produk</a><span>/</span>
            <a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a><span>/</span><span aria-current="page">{{ $product->name }}</span>
        </nav>
        <div class="product-detail">
            <div class="product-showcase-gallery">
                <img class="product-main-image" id="product-main-image" src="{{ $product->image ? $product->image_url : (count($gallery) ? asset('storage/'.$gallery[0]) : $product->image_url) }}" alt="{{ $product->image_alt ?: (count($gallery) ? $product->name : 'Gambar produk belum tersedia') }}" width="700" height="600">
                @if(count($gallery) > 1)
                    <div class="gallery" role="group" aria-label="Galeri {{ $product->name }}">
                        @foreach($gallery as $path)
                            <button type="button" class="gallery-thumb" data-gallery-src="{{ asset('storage/'.$path) }}" aria-label="Lihat gambar {{ $loop->iteration }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                <img src="{{ asset('storage/'.$path) }}" alt="{{ $product->name }}, gambar {{ $loop->iteration }}" width="80" height="80">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="product-showcase-info">
                <a class="eyebrow detail-eyebrow" href="{{ route('brands.show', $product->brand->slug) }}">{{ $product->brand->name }}</a>
                <h1>{{ $product->name }}</h1>
                <a class="text-link product-category-link" href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }} <x-icon name="arrow-up-right"/></a>
                @if($shortDescription)<p class="product-summary">{{ $shortDescription }}</p>@endif
                <div class="product-inquiry">
                    @if($product->model_or_series)<dl class="product-series"><dt>Model / Seri</dt><dd>{{ $product->model_or_series }}</dd></dl>@endif
                    <div class="actions">
                        <a class="button" href="{{ route('contact', ['product' => $product->id]) }}">Request Inquiry <x-icon name="arrow-up-right"/></a>
                        @if($product->datasheet_file)<a class="button secondary" href="{{ asset('storage/'.$product->datasheet_file) }}" target="_blank" rel="noopener"><x-icon name="download"/> Datasheet PDF</a>@endif
                    </div>
                    @if(!$product->datasheet_file)<p class="product-help">Hubungi tim kami untuk informasi datasheet.</p>@endif
                </div>
            </div>
        </div>
    </section>
    @if($description && $description !== $shortDescription)
        <section class="container detail-editorial detail-sections">
            <div><p class="detail-eyebrow">{{ sprintf('%02d', ++$sectionNumber) }} / PRODUCT OVERVIEW</p><h2>Tentang Produk</h2></div>
            <div class="detail-copy"><p>{{ $description }}</p></div>
        </section>
    @endif
    @if($product->specifications)
        <section class="detail-technical-band">
            <div class="container detail-editorial detail-sections">
                <div><p class="detail-eyebrow">{{ sprintf('%02d', ++$sectionNumber) }} / TECHNICAL INFORMATION</p><h2>Spesifikasi Produk</h2></div>
                <div class="table-wrap"><table class="spec-table"><tbody>
                    @foreach($product->specifications as $spec)<tr><th scope="row">{{ $spec['label'] }}</th><td>{{ $spec['value'] }} {{ $spec['unit'] ?? '' }}</td></tr>@endforeach
                </tbody></table></div>
            </div>
        </section>
    @endif
    @if($product->applications)
        <section class="container detail-editorial detail-sections">
            <div><p class="detail-eyebrow">{{ sprintf('%02d', ++$sectionNumber) }} / APPLICATIONS</p><h2>Aplikasi Produk</h2></div>
            <div><ul class="detail-item-list">@foreach($product->applications as $application)<li><span aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</span>{{ $application }}</li>@endforeach</ul></div>
        </section>
    @endif
    @if($product->benefits)
        <section class="container detail-editorial detail-sections">
            <div><p class="detail-eyebrow">{{ sprintf('%02d', ++$sectionNumber) }} / PRODUCT BENEFITS</p><h2>Keunggulan</h2></div>
            <div><ul class="detail-item-list">@foreach($product->benefits as $benefit)<li><x-icon name="check"/>{{ $benefit }}</li>@endforeach</ul></div>
        </section>
    @endif
    @if($product->industries->isNotEmpty())
        <section class="container detail-editorial detail-sections">
            <div><h2>Industri Terkait</h2></div>
            <div class="detail-directory-links">@foreach($product->industries as $industry)<a href="{{ route('industries.show', $industry->slug) }}">{{ $industry->name }} <x-icon name="arrow-up-right"/></a>@endforeach</div>
        </section>
    @endif
    @if($related->isNotEmpty())
        <section class="detail-related">
            <div class="container">
                <div class="section-heading"><div><p class="detail-eyebrow">RELATED PRODUCTS</p><h2>Produk Terkait</h2></div></div>
                <div class="product-grid">@foreach($related->take(3) as $product)@include('partials.product-card')@endforeach</div>
            </div>
        </section>
    @endif
</div>
@include('partials.inquiry-cta')
@endsection
