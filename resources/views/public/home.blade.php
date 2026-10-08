@extends('layouts.public')
@section('content')
<x-hero-carousel :slides="$focusSlides" />
<section class="section container home-product-categories"><div class="section-heading"><div><p class="eyebrow">PRODUCTS & SOLUTIONS</p><h2>Peralatan tepat.<br>Operasional lebih baik.</h2></div><div><p>Jelajahi kebutuhan teknis Anda melalui tujuh kategori utama kami.</p><a class="text-link" href="{{ route('products.index') }}">Semua Produk <x-icon name="arrow-up-right"/></a></div></div>
    <div class="category-grid">@foreach($categories as $category)@include('partials.category-card', ['number' => $loop->iteration, 'revealImage' => config('homepage-category-images.'.$category->slug)])@endforeach</div>
</section>
<section class="section company-band"><div class="container company-grid"><div><p class="eyebrow">YOUR INDUSTRIAL PARTNER</p><h2>Kebutuhan teknis Anda.<br>Fokus kami.</h2></div><div><p class="lead">{{ $settings['profile'] }}</p><a class="text-link" href="{{ route('about') }}">Tentang Artomoro <x-icon name="arrow-up-right"/></a></div></div></section>
<section class="section container"><div class="section-heading"><div><p class="eyebrow">BRANDS & PRINCIPALS</p><h2>Teknologi untuk setiap kebutuhan.</h2></div><a class="text-link" href="{{ route('brands.index') }}">Lihat Brand <x-icon name="arrow-up-right"/></a></div><x-brand-marquee :brands="$brands" /></section>
@if($products->isNotEmpty())<section class="section container"><p class="eyebrow">PILIHAN PRODUK</p><h2>Untuk kebutuhan operasional Anda.</h2><div class="product-grid">@foreach($products as $product)@include('partials.product-card')@endforeach</div></section>@endif
@if($industries->isNotEmpty())<section class="section surface"><div class="container"><p class="eyebrow">INDUSTRIES & APPLICATIONS</p><h2>Solusi lintas industri.</h2><div class="directory-grid">@foreach($industries as $industry)<a class="directory-item" href="{{ route('industries.show', $industry->slug) }}"><h3>{{ $industry->name }}</h3><p>{{ $industry->description }}</p><x-icon name="arrow-up-right"/></a>@endforeach</div></div></section>@endif
<section class="value-strip container">@foreach(['Reliability', 'Efficiency', 'Operational Continuity', 'Safety', 'Environmental Protection'] as $value)<div><x-icon name="check"/><span>{{ $value }}</span></div>@endforeach</section>
@include('partials.inquiry-cta')
@endsection
