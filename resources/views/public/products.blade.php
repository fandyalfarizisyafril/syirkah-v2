@extends('layouts.public')
@section('title', ($current?->meta_title ?: ($current?->name ?: 'Produk & Solusi')).' | Artomoro')
@section('description', $current?->meta_description ?: ($current?->description ?: $settings['meta_description']))
@section('content')
<div class="page-intro container"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>/</span>@if($current)<a href="{{ route('products.index') }}">Produk</a><span>/</span><span>{{ $current->name }}</span>@else<span>Produk & Solusi</span>@endif</nav><p class="eyebrow">PRODUCTS & SOLUTIONS</p><h1>{{ $current?->name ?: 'Produk & Solusi' }}</h1><p class="lead">{{ $current?->description ?: 'Temukan equipment dan teknologi untuk kebutuhan operasional industri Anda.' }}</p></div>
<section class="container catalog-section">
    <form method="get" action="{{ route('products.index') }}" class="filter-bar" role="search">
        <x-field name="q" label="Pencarian" :value="request('q')" placeholder="Nama, seri, atau kata kunci" maxlength="150"/>
        <div class="field"><label for="category">Kategori</label><select id="category" name="category"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category', $current?->id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <div class="field"><label for="brand">Brand</label><select id="brand" name="brand"><option value="">Semua brand</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
        <button class="button" type="submit"><x-icon name="search"/> Cari</button><a class="icon-button" href="{{ route('products.index') }}" title="Reset filter" aria-label="Reset filter"><x-icon name="rotate-ccw"/></a>
    </form>
    <div class="result-count">{{ $products->total() }} produk</div>
    <div class="product-grid">@forelse($products as $product)@include('partials.product-card')@empty<div class="empty-state"><x-icon name="package-search"/><h2>Produk belum tersedia.</h2><p>Hubungi tim kami untuk informasi produk dan kebutuhan teknis Anda.</p><a href="{{ route('contact') }}" class="button">Konsultasi Produk <x-icon name="arrow-up-right"/></a></div>@endforelse</div>
    {{ $products->links() }}
    @if($relatedBrands->isNotEmpty())<section class="section"><h2>Brand Terkait</h2><div class="search-links">@foreach($relatedBrands as $brand)<a href="{{ route('brands.show', $brand->slug) }}">{{ $brand->name }} <x-icon name="arrow-up-right"/></a>@endforeach</div></section>@endif
    @if($relatedIndustries->isNotEmpty())<section class="section"><h2>Industri & Aplikasi Terkait</h2><div class="search-links">@foreach($relatedIndustries as $industry)<a href="{{ route('industries.show', $industry->slug) }}">{{ $industry->name }} <x-icon name="arrow-up-right"/></a>@endforeach</div></section>@endif
    @if(!$current)<div class="section"><h2>Kategori Produk</h2><div class="category-grid">@foreach($categories as $category)<a class="category-item" href="{{ route('products.category', $category->slug) }}"><h3>{{ $category->name }}</h3><p>{{ $category->description }}</p><x-icon name="arrow-up-right"/></a>@endforeach</div></div>@endif
</section>
@include('partials.inquiry-cta')
@endsection
