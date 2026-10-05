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
        <a href="{{ route('home') }}" class="wordmark" aria-label="{{ $settings['company_name'] }}, beranda">
            @if($settings['logo'])
                <img class="company-logo" src="{{ asset('storage/'.$settings['logo']) }}" alt="{{ $settings['company_name'] }}" width="200" height="55">
            @else
                <span class="company-signature">
                    <img class="signature-image" src="{{ asset('images/company-logo.png') }}" alt="SMART" width="530" height="151">
                    <span class="signature-copy">
                        <span class="signature-name">{{ $settings['company_name'] }}</span>
                        <span class="signature-tagline">Equipment & Parts Solutions</span>
                    </span>
                </span>
            @endif
        </a>
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
        <div class="footer-identity">
            <a href="{{ route('home') }}" class="footer-logo" aria-label="{{ $settings['company_name'] }}, beranda">
                <img src="{{ asset('images/company-logo-footer.png') }}" alt="SMART - Equipment & Parts Solutions" width="500" height="500" loading="lazy">
            </a>
            <p class="footer-company-name">{{ $settings['company_name'] }}</p>
            <p class="footer-company-tagline">{{ $settings['tagline'] }}</p>
            @if($settings['whatsapp_number'])
                <a class="footer-whatsapp" href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" rel="noopener noreferrer"><x-icon name="message-circle"/>Hubungi via WhatsApp<x-icon name="arrow-up-right"/></a>
            @endif
        </div>
        <div class="footer-navigation">
            <h2>Jelajahi</h2>
            <nav aria-label="Navigasi footer">
                <a href="{{ route('about') }}">Tentang Kami</a>
                <a href="{{ route('products.index') }}">Produk & Solusi</a>
                <a href="{{ route('brands.index') }}">Brands & Principals</a>
                <a href="{{ route('industries.index') }}">Industri</a>
                <a href="{{ route('contact') }}">Kontak</a>
            </nav>
        </div>
        <div class="footer-contact">
            <h2>Hubungi Kami</h2>
            <a class="footer-contact-link" href="tel:{{ preg_replace('/[^+0-9]/', '', $settings['phone']) }}"><x-icon name="phone"/><span><span class="footer-label">Telepon</span>{{ $settings['phone'] }}</span></a>
            <a class="footer-contact-link" href="mailto:{{ $settings['email'] }}"><x-icon name="mail"/><span><span class="footer-label">Email</span>{{ $settings['email'] }}</span></a>
            @if($settings['sales_email'])<a class="footer-contact-link" href="mailto:{{ $settings['sales_email'] }}"><x-icon name="mail"/><span><span class="footer-label">Sales</span>{{ $settings['sales_email'] }}</span></a>@endif
        </div>
        <div class="footer-office">
            <h2>Kantor</h2>
            <div class="footer-address"><x-icon name="map-pin"/><address>{{ $settings['address'] }}</address></div>
            @if($settings['map_url'])<a class="text-link" href="{{ $settings['map_url'] }}" target="_blank" rel="noopener noreferrer">Lihat peta <x-icon name="arrow-up-right"/></a>@endif
            <a class="text-link" href="{{ route('contact') }}">Diskusikan Kebutuhan <x-icon name="arrow-up-right"/></a>
        </div>
    </div>
    @if($footerCategories->isNotEmpty())
        <div class="container footer-categories"><h2>Produk & Solusi</h2><nav aria-label="Kategori produk">@foreach($footerCategories as $category)<a href="{{ route('products.category', $category->slug) }}">{{ $category->name }}<x-icon name="arrow-up-right"/></a>@endforeach</nav></div>
    @endif
    <div class="footer-base"><div class="container footer-bottom"><span>&copy; 2026 Fandy Alfarizi Syafril.</span><div class="actions">@if($settings['legal_information'])<a href="{{ route('about') }}#legal">Informasi Legal</a>@endif<a href="{{ route('privacy') }}">Kebijakan Privasi</a></div></div></div>
</footer>
@stack('scripts')
</body>
</html>
