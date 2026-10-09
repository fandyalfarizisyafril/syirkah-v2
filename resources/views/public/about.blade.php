@extends('layouts.public')
@section('title', 'Tentang Kami | Artomoro')
@push('head')
    @vite(['resources/css/about.css', 'resources/js/about.js'])
@endpush
@section('content')
<div class="about-page">
    <section class="container about-intro" aria-labelledby="about-title" data-about-reveal>
        <div class="about-intro-copy about-reveal-item">
            <p class="about-eyebrow">TENTANG KAMI</p>
            <h1 id="about-title">Mitra untuk kebutuhan<br><span>teknis industri.</span></h1>
            <p class="about-company-name">{{ $settings['company_name'] }}</p>
            <p class="about-company-tagline">{{ $settings['tagline'] }}</p>
        </div>
        <figure class="about-photograph">
            <img src="{{ asset('images/industrial.jpg') }}" alt="Instalasi fasilitas industri" width="1920" height="1280" fetchpriority="high" decoding="async">
        </figure>
    </section>

    <section class="container about-profile" aria-labelledby="about-profile-title" data-about-reveal>
        <h2 id="about-profile-title" class="about-reveal-item">Mengenal SMART<br><span>lebih dekat.</span></h2>
        <div class="about-profile-copy about-reveal-item"><p>{{ $settings['profile'] }}</p></div>
    </section>

    <section class="about-focus" aria-labelledby="about-focus-title" data-about-reveal>
        <div class="container">
            <div class="about-section-heading about-reveal-item">
                <p class="about-eyebrow">AREA KEAHLIAN</p>
                <h2 id="about-focus-title">Bidang Fokus</h2>
            </div>
            <ul class="about-focus-grid">
                @foreach(['Engineering' => 'drafting-compass', 'Mechanical' => 'cog', 'Electrical' => 'zap', 'Instrumentation' => 'gauge', 'Oil Spill Response & Prevention' => 'shield-check'] as $focus => $icon)
                    <li class="about-focus-item about-reveal-item" style="--about-delay:{{ $loop->index * 60 }}ms">
                        <span class="about-focus-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <i data-about-icon="{{ $icon }}" class="about-focus-icon" aria-hidden="true"></i>
                        <h3>{{ $focus }}</h3>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="container about-values" aria-labelledby="about-values-title" data-about-reveal>
        <h2 id="about-values-title" class="about-reveal-item">Nilai Kami</h2>
        <ul class="about-values-list about-reveal-item">
            @foreach(['Reliability', 'Efficiency', 'Operational Continuity', 'Safety', 'Environmental Protection'] as $value)
                <li><x-icon name="check"/><span>{{ $value }}</span></li>
            @endforeach
        </ul>
    </section>

    @if($settings['legal_information'])
        <section id="legal" class="container about-legal"><h2>Informasi Legal</h2><p class="prose">{{ $settings['legal_information'] }}</p></section>
    @endif
</div>
@include('partials.inquiry-cta')
@endsection
