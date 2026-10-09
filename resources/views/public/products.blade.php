@extends('layouts.public')
@section('title', ($current?->meta_title ?: ($current?->name ?: 'Produk & Solusi')).' | Artomoro')
@section('description', $current?->meta_description ?: ($current?->description ?: $settings['meta_description']))
@if(!$current)
    @push('head')
        @vite('resources/css/catalog.css')
    @endpush
@endif
@section('content')
@if(!$current)<div class="catalog-page">@endif
<div class="page-intro container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>/</span>@if($current)<a href="{{ route('products.index') }}">Produk</a><span>/</span><span>{{ $current->name }}</span>@else<span>Produk & Solusi</span>@endif</nav>
    <div @class(['category-intro' => $current?->image])>
        <div class="category-intro-copy">
            <p class="eyebrow">PRODUCTS & SOLUTIONS</p>
            <h1>{{ $current?->name ?: 'Produk & Solusi' }}</h1>
            <p class="lead">{{ $current?->description ?: 'Temukan equipment dan teknologi untuk kebutuhan operasional industri Anda.' }}</p>
        </div>
        @if($current?->image)
            <img class="category-detail-image" src="{{ $current->image_url }}" alt="{{ $current->image_alt ?: $current->name }}" width="640" height="400">
        @endif
    </div>
</div>
<section class="container catalog-section">
    <form method="get" action="{{ route('products.index') }}" class="filter-bar" role="search">
        <x-field name="q" label="Pencarian" :value="request('q')" placeholder="Nama, seri, atau kata kunci" maxlength="150"/>
        <div class="field"><label for="category">Kategori</label><select id="category" name="category"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request()->query('category', $current?->id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <div class="field"><label for="brand">Brand</label><select id="brand" name="brand"><option value="">Semua brand</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
        <button class="button" type="submit"><x-icon name="search"/> Cari</button><a class="{{ $current ? 'icon-button' : 'button secondary catalog-reset' }}" href="{{ route('products.index') }}" title="Reset filter" aria-label="Reset filter"><x-icon name="rotate-ccw"/>@if(!$current) Reset @endif</a>
    </form>
    @if(!$current)<div class="catalog-results-heading"><h2>Katalog Produk</h2>@endif
    <div class="result-count">{{ $products->total() }} produk</div>
    @if(!$current)</div>@endif
    <div class="product-grid">
        @forelse($products as $product)
            @include('partials.product-card')
        @empty
            <div class="empty-state">
                <x-icon name="package-search"/>
                @if(!$current)
                    <h2>Tidak ada produk yang ditemukan.</h2>
                    <p>Kami tidak menemukan produk yang sesuai dengan pencarian Anda.</p>
                    <a href="{{ route('products.index') }}" class="button secondary"><x-icon name="rotate-ccw"/> Reset filter</a>
                @else
                    <h2>Produk belum tersedia.</h2><p>Hubungi tim kami untuk informasi produk dan kebutuhan teknis Anda.</p><a href="{{ route('contact') }}" class="button">Konsultasi Produk <x-icon name="arrow-up-right"/></a>
                @endif
            </div>
        @endforelse
    </div>
    {{ $products->links() }}
    @if($relatedBrands->isNotEmpty())<section class="section"><h2>Brand Terkait</h2><div class="search-links">@foreach($relatedBrands as $brand)<a href="{{ route('brands.show', $brand->slug) }}">{{ $brand->name }} <x-icon name="arrow-up-right"/></a>@endforeach</div></section>@endif
    @if($relatedIndustries->isNotEmpty())<section class="section"><h2>Industri & Aplikasi Terkait</h2><div class="search-links">@foreach($relatedIndustries as $industry)<a href="{{ route('industries.show', $industry->slug) }}">{{ $industry->name }} <x-icon name="arrow-up-right"/></a>@endforeach</div></section>@endif
    @if(!$current)<div class="section"><h2>Kategori Produk</h2><div class="category-grid">@foreach($categories as $category)@include('partials.category-card', ['number' => $loop->iteration])@endforeach</div></div>@endif
</section>
@if(!$current)</div>@endif
@include('partials.inquiry-cta')
@endsection
