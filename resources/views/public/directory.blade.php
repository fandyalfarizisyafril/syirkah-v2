@extends('layouts.public')
@section('title', ($entry?->meta_title ?: ($entry?->name ?: ($isBrand ? 'Brands & Principals' : 'Industri & Aplikasi'))).' | Artomoro')
@section('description', $entry?->meta_description ?: ($entry?->description ?: $settings['meta_description']))
@if($isBrand && !$entry)
    @push('head')
        @vite('resources/css/brand-portfolio.css')
    @endpush
@endif
@section('content')
@if($isBrand && !$entry)
    @include('public.partials.brand-portfolio')
@else
<div class="container page-intro"><nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>/</span><a href="{{ route($isBrand ? 'brands.index' : 'industries.index') }}">{{ $isBrand ? 'Brand' : 'Industri' }}</a>@if($entry)<span>/</span><span>{{ $entry->name }}</span>@endif</nav><p class="eyebrow">{{ $isBrand ? 'BRANDS & PRINCIPALS' : 'INDUSTRIES & APPLICATIONS' }}</p><h1>{{ $entry?->name ?: ($isBrand ? 'Brands & Principals' : 'Industri & Aplikasi') }}</h1><p class="lead">{{ $entry?->description ?: ($isBrand ? 'Pilihan teknologi untuk kebutuhan equipment dan proses industri.' : 'Diskusikan tantangan operasional Anda bersama tim kami.') }}</p></div>
<section class="container catalog-section">
@if($entry)
    @if($relatedCategories->isNotEmpty())<h2>Kategori Terkait</h2><div class="search-links">@foreach($relatedCategories as $category)<a href="{{ route('products.category', $category->slug) }}">{{ $category->name }} <x-icon name="arrow-up-right"/></a>@endforeach</div>@endif
    @if($entry->image)<img class="directory-image" src="{{ $entry->image_url }}" alt="{{ $entry->image_alt ?: $entry->name }}" width="640" height="400">@endif
    @if($isBrand)<p class="lead">{{ $entry->focus }}</p>@if($entry->website_url)<a href="{{ $entry->website_url }}" class="text-link" target="_blank" rel="noopener noreferrer">Website Principal <x-icon name="arrow-up-right"/></a>@endif
    @else<div class="detail-sections"><div><h2>Kebutuhan Industri</h2><p class="prose">{{ $entry->challenges }}</p></div><div><h2>Pendekatan Solusi</h2><p class="prose">{{ $entry->solution_copy }}</p></div></div>@endif
    <h2 class="section-title">Produk Terkait</h2><div class="product-grid">@forelse($products as $product)@include('partials.product-card')@empty<div class="empty-state"><h3>Diskusikan pilihan produk dengan tim kami.</h3><a class="text-link" href="{{ route('contact') }}">Hubungi Kami <x-icon name="arrow-up-right"/></a></div>@endforelse</div>{{ $products->links() }}
@else
    <div class="directory-grid">@forelse($entries as $item)<a href="{{ route($isBrand ? 'brands.show' : 'industries.show', $item->slug) }}" class="directory-item">@if($item->image)<img src="{{ $item->image_url }}" alt="{{ $item->image_alt ?: $item->name }}" width="300" height="180" loading="lazy">@endif<h2>{{ $item->name }}</h2><p>{{ $isBrand ? $item->focus : $item->description }}</p><x-icon name="arrow-up-right"/></a>@empty<div class="empty-state"><h2>Kebutuhan industri Anda adalah fokus kami.</h2><p>Hubungi tim kami untuk mendiskusikan aplikasi dan kebutuhan teknis Anda.</p></div>@endforelse</div>
@endif
</section>
@endif
@include('partials.inquiry-cta')
@endsection
