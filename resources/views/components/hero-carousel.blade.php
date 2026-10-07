@props(['slides', 'id' => 'focus-hero'])
@push('head')
    <link rel="preload" as="image" href="{{ asset($slides[0]['image']) }}" fetchpriority="high">
    <link rel="preload" as="font" href="{{ asset('fonts/InterVariable.woff2') }}" type="font/woff2" crossorigin>
@endpush
<section class="hero" id="{{ $id }}" data-hero-carousel aria-label="Bidang fokus SMART" aria-roledescription="carousel">
    <div class="hero-slides" aria-hidden="true">
        @foreach($slides as $slide)
            <img class="hero-image {{ $loop->first ? 'is-active' : '' }}"
                 @if($loop->first) src="{{ asset($slide['image']) }}" fetchpriority="high" @else data-src="{{ asset($slide['image']) }}" fetchpriority="low" @endif
                 alt="" width="{{ $slide['width'] }}" height="{{ $slide['height'] }}" decoding="async"
                 style="--image-position:{{ $slide['position'] }};--image-mobile-position:{{ $slide['mobilePosition'] }}"
                 data-slide="{{ $slide['id'] }}">
        @endforeach
    </div>
    <div class="container hero-content" id="{{ $id }}-content">
        <p class="eyebrow hero-copy-stack">
            @foreach($slides as $slide)
                <span class="hero-copy {{ $loop->first ? 'is-active' : '' }}" data-copy="{{ $loop->index }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}">{{ $slide['eyebrow'] }}</span>
            @endforeach
        </p>
        <h1 class="hero-copy-stack">
            @foreach($slides as $slide)
                <span class="hero-copy {{ $loop->first ? 'is-active' : '' }}" data-copy="{{ $loop->index }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}">@foreach($slide['title'] as $line)<span class="hero-title-line">{{ $line }}{{ $loop->last ? '' : ' ' }}</span>@endforeach</span>
            @endforeach
        </h1>
        <p class="hero-description hero-copy-stack">
            @foreach($slides as $slide)
                <span class="hero-copy {{ $loop->first ? 'is-active' : '' }}" data-copy="{{ $loop->index }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}">{{ $slide['description'] }}</span>
            @endforeach
        </p>
    </div>
    <nav class="container hero-focus-nav" aria-label="Pilih bidang fokus" hidden>
        @foreach($slides as $slide)
            <button type="button" class="hero-focus-item" data-focus-slide="{{ $loop->index }}"
                    aria-label="Tampilkan {{ $slide['navLabel'] }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $id }}-content">
                <span class="hero-focus-number">{{ $slide['number'] }}</span>
                <span class="hero-focus-label">{{ $slide['navLabel'] }}</span>
            </button>
        @endforeach
    </nav>
</section>
