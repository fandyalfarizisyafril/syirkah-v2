@extends('layouts.public')
@section('content')
<x-hero-carousel :slides="$focusSlides" />
<section class="section container home-product-categories"><div class="section-heading"><div><p class="eyebrow">PRODUCTS & SOLUTIONS</p><h2>Peralatan tepat.<br>Operasional lebih baik.</h2></div><div><p>Jelajahi kebutuhan teknis Anda melalui tujuh kategori utama kami.</p><a class="text-link" href="{{ route('products.index') }}">Semua Produk <x-icon name="arrow-up-right"/></a></div></div>
    <x-category-carousel :categories="$categories" />
</section>
<section class="home-partner" data-industrial-partner aria-labelledby="home-partner-title">
    <div class="container home-partner-inner">
        <div class="home-partner-heading">
            <p class="home-partner-eyebrow" data-partner-reveal>YOUR INDUSTRIAL PARTNER</p>
            <h2 id="home-partner-title" data-partner-reveal>Kebutuhan teknis Anda.<br><span>Fokus kami.</span></h2>
        </div>
        <div class="home-partner-copy" data-partner-reveal>
            <p>{{ $settings['profile'] }}</p>
            <a class="home-partner-link" href="{{ route('about') }}">Tentang Artomoro <x-icon name="arrow-up-right"/></a>
        </div>
        <svg class="home-partner-signature" viewBox="0 0 216 72" width="216" height="72" fill="none" aria-hidden="true" focusable="false">
            <path d="M1 54H170V14H215" pathLength="1"/>
            <path d="M158 66H182V2" pathLength="1"/>
            <rect x="167" y="51" width="6" height="6"/>
        </svg>
    </div>
</section>
<section class="section container"><div class="section-heading"><div><p class="eyebrow">BRANDS & PRINCIPALS</p><h2>Teknologi untuk setiap kebutuhan.</h2></div><a class="text-link" href="{{ route('brands.index') }}">Lihat Brand <x-icon name="arrow-up-right"/></a></div><x-brand-marquee :brands="$brands" /></section>
@if($products->isNotEmpty())<section class="section container"><p class="eyebrow">PILIHAN PRODUK</p><h2>Untuk kebutuhan operasional Anda.</h2><div class="product-grid">@foreach($products as $product)@include('partials.product-card')@endforeach</div></section>@endif
@if($industries->isNotEmpty())<section class="section surface"><div class="container"><p class="eyebrow">INDUSTRIES & APPLICATIONS</p><h2>Solusi lintas industri.</h2><div class="directory-grid">@foreach($industries as $industry)<a class="directory-item" href="{{ route('industries.show', $industry->slug) }}"><h3>{{ $industry->name }}</h3><p>{{ $industry->description }}</p><x-icon name="arrow-up-right"/></a>@endforeach</div></div></section>@endif
<section class="value-strip container">@foreach(['Reliability', 'Efficiency', 'Operational Continuity', 'Safety', 'Environmental Protection'] as $value)<div><x-icon name="check"/><span>{{ $value }}</span></div>@endforeach</section>
@include('partials.inquiry-cta')
@endsection
