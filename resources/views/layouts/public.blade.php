<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $settings['meta_title'])</title>
    <meta name="description" content="@yield('description', $settings['meta_description'])">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', $settings['meta_title'])">
    <meta property="og:description" content="@yield('description', $settings['meta_description'])">
    <meta property="og:type" content="website"><meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/industrial.jpg'))">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'Organization', 'name' => $settings['company_name'], 'url' => url('/'), 'email' => $settings['email'], 'telephone' => $settings['phone'], 'address' => $settings['address']], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) !!}</script>
</head>
<body>
<a class="skip-link" href="#main">Langsung ke konten</a>
<div class="utility"><div class="container utility-inner"><span><x-icon name="map-pin"/> Pekanbaru, Indonesia</span><div><a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a><a href="tel:{{ preg_replace('/[^+0-9]/', '', $settings['phone']) }}">{{ $settings['phone'] }}</a></div></div></div>
<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="wordmark" aria-label="Artomoro, beranda">@if($settings['logo'])<img class="company-logo" src="{{ asset('storage/'.$settings['logo']) }}" alt="{{ $settings['company_name'] }}" width="200" height="55">@else<span class="wordmark-symbol">A<span>.</span></span><span>ARTOMORO<small>SYIRKAH MANDIRI</small></span>@endif</a>
        <button class="icon-button menu-toggle" type="button" aria-label="Buka navigasi" aria-expanded="false" aria-controls="primary-nav" title="Navigasi"><x-icon name="menu"/></button>
        <nav id="primary-nav" class="primary-nav" aria-label="Navigasi utama">
            @foreach(['home' => 'Beranda', 'about' => 'Tentang Kami', 'products.index' => 'Produk', 'brands.index' => 'Brand', 'industries.index' => 'Industri'] as $route => $label)
                <a href="{{ route($route) }}" @class(['active' => request()->routeIs(str_replace('.index', '.*', $route))]) @if(request()->routeIs(str_replace('.index', '.*', $route))) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <a href="{{ route('search') }}" class="icon-button" aria-label="Cari" title="Cari"><x-icon name="search"/></a>
            <a class="button small" href="{{ route('contact') }}">Request Inquiry <x-icon name="arrow-up-right"/></a>
        </nav>
    </div>
</header>
<main id="main">@yield('content')</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div><a href="{{ route('home') }}" class="footer-brand">ARTOMORO<span>.</span></a><p>{{ $settings['company_name'] }}</p><p>{{ $settings['tagline'] }}</p></div>
        <div><h2>Jelajahi</h2><a href="{{ route('about') }}">Tentang Kami</a><a href="{{ route('products.index') }}">Produk & Solusi</a><a href="{{ route('brands.index') }}">Brands & Principals</a><a href="{{ route('industries.index') }}">Industri</a></div>
        <div><h2>Hubungi Kami</h2><a href="tel:{{ preg_replace('/[^+0-9]/', '', $settings['phone']) }}">{{ $settings['phone'] }}</a><a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a>@if($settings['sales_email'])<a href="mailto:{{ $settings['sales_email'] }}">{{ $settings['sales_email'] }}</a>@endif</div>
        <div><h2>Kantor</h2><p>{{ $settings['address'] }}</p>@if($settings['map_url'])<a href="{{ $settings['map_url'] }}" target="_blank" rel="noopener noreferrer">Lihat peta <x-icon name="arrow-up-right"/></a>@endif</div>
    </div>
    <div class="container footer-categories"><h2>Kategori Produk</h2><nav aria-label="Kategori produk">@foreach($footerCategories as $category)<a href="{{ route('products.category', $category->slug) }}">{{ $category->name }}</a>@endforeach</nav></div>
    <div class="container footer-bottom"><span>&copy; {{ date('Y') }} {{ $settings['company_name'] }}</span><div class="actions">@if($settings['legal_information'])<a href="{{ route('about') }}#legal">Informasi Legal</a>@endif<a href="{{ route('privacy') }}">Kebijakan Privasi</a></div></div>
</footer>
@stack('scripts')
</body>
</html>
