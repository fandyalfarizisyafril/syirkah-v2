@extends('layouts.public')
@section('content')
<section class="hero" data-hero-carousel aria-label="Sorotan industri" aria-roledescription="carousel">
    <div class="hero-slides" id="hero-slides">
        <img class="hero-image is-active" src="{{ asset('images/industrial.jpg') }}" alt="Fasilitas industri dengan peralatan dan instalasi produksi" width="1920" height="1280" fetchpriority="high" data-slide>
        <img class="hero-image" data-src="{{ asset('images/hero-pumps.webp') }}" alt="Ilustrasi instalasi pompa dan motor listrik industri" width="1672" height="941" decoding="async" fetchpriority="low" data-slide aria-hidden="true">
        <img class="hero-image" data-src="{{ asset('images/hero-compressors.webp') }}" alt="Ilustrasi ruang kompresor dengan tangki dan pengering udara" width="1672" height="941" decoding="async" fetchpriority="low" data-slide aria-hidden="true">
    </div>
    <div class="container hero-content"><p class="eyebrow">GENERAL SUPPLIER & TECHNICAL SOLUTIONS</p><h1>PT. Syirkah<br>Mandiri Artomoro<span>.</span></h1><p class="hero-subtitle">Industrial Supply &<br>Integrated Technical Solutions.</p><p class="hero-description">Equipment, spare part, dan dukungan teknis yang menghubungkan kebutuhan Anda dengan solusi industri.</p><div class="actions"><a class="button" href="{{ route('products.index') }}">Jelajahi Produk <x-icon name="arrow-up-right"/></a><a class="button outline-light" href="{{ route('contact') }}">Request Inquiry</a></div></div>
    <div class="hero-caption">PEKANBARU, RIAU <span>INDUSTRIAL SOLUTIONS</span></div>
</section>
<section class="focus-strip"><div class="container focus-grid">@foreach(['Engineering', 'Mechanical', 'Electrical', 'Instrumentation', 'Oil Spill Response'] as $focus)<div><span class="focus-number">0{{ $loop->iteration }}</span><span>{{ $focus }}</span></div>@endforeach</div></section>
<section class="section container"><div class="section-heading"><div><p class="eyebrow">PRODUCTS & SOLUTIONS</p><h2>Peralatan tepat.<br>Operasional lebih baik.</h2></div><div><p>Jelajahi kebutuhan teknis Anda melalui tujuh kategori utama kami.</p><a class="text-link" href="{{ route('products.index') }}">Semua Produk <x-icon name="arrow-up-right"/></a></div></div>
    <div class="category-grid">@foreach($categories as $category)@include('partials.category-card', ['number' => $loop->iteration])@endforeach</div>
</section>
<section class="section company-band"><div class="container company-grid"><div><p class="eyebrow">YOUR INDUSTRIAL PARTNER</p><h2>Kebutuhan teknis Anda.<br>Fokus kami.</h2></div><div><p class="lead">{{ $settings['profile'] }}</p><a class="text-link" href="{{ route('about') }}">Tentang Artomoro <x-icon name="arrow-up-right"/></a></div></div></section>
<section class="section container"><div class="section-heading"><div><p class="eyebrow">BRANDS & PRINCIPALS</p><h2>Teknologi untuk setiap kebutuhan.</h2></div><a class="text-link" href="{{ route('brands.index') }}">Lihat Brand <x-icon name="arrow-up-right"/></a></div><div class="brand-strip">@foreach($brands as $brand)<a href="{{ route('brands.show', $brand->slug) }}">@if($brand->image)<img src="{{ $brand->image_url }}" alt="{{ $brand->name }}" width="150" height="64" loading="lazy">@else<span>{{ $brand->name }}</span>@endif</a>@endforeach</div></section>
@if($products->isNotEmpty())<section class="section container"><p class="eyebrow">PILIHAN PRODUK</p><h2>Untuk kebutuhan operasional Anda.</h2><div class="product-grid">@foreach($products as $product)@include('partials.product-card')@endforeach</div></section>@endif
@if($industries->isNotEmpty())<section class="section surface"><div class="container"><p class="eyebrow">INDUSTRIES & APPLICATIONS</p><h2>Solusi lintas industri.</h2><div class="directory-grid">@foreach($industries as $industry)<a class="directory-item" href="{{ route('industries.show', $industry->slug) }}"><h3>{{ $industry->name }}</h3><p>{{ $industry->description }}</p><x-icon name="arrow-up-right"/></a>@endforeach</div></div></section>@endif
<section class="value-strip container">@foreach(['Reliability', 'Efficiency', 'Operational Continuity', 'Safety', 'Environmental Protection'] as $value)<div><x-icon name="check"/><span>{{ $value }}</span></div>@endforeach</section>
@include('partials.inquiry-cta')
@endsection
