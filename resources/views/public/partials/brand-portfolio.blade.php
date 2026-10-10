<div class="brand-portfolio">
    <div class="container brand-portfolio-intro">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>/</span><a href="{{ route('brands.index') }}" aria-current="page">Brand</a></nav>
        <p class="eyebrow">BRANDS & PRINCIPALS</p>
        <h1>Brand dan teknologi untuk kebutuhan industri.</h1>
        <p class="brand-portfolio-description">Jelajahi pilihan brand dan solusi teknologi yang tersedia melalui SMART.</p>
    </div>
    <section class="container brand-portfolio-list" aria-label="Daftar brand dan principal">
        <div class="brand-portfolio-grid">
            @forelse($entries as $item)
                <a class="brand-portfolio-card" href="{{ route('brands.show', $item->slug) }}">
                    <div class="brand-portfolio-logo">
                        @if($item->image)
                            <img src="{{ $item->image_url }}" alt="{{ $item->image_alt ?: $item->name }}" width="300" height="180" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <div class="brand-portfolio-copy">
                        <h2>{{ $item->name }}</h2>
                        <p>{{ $item->focus }}</p>
                    </div>
                    <x-icon name="arrow-up-right"/>
                </a>
            @empty
                <div class="empty-state"><h2>Kebutuhan industri Anda adalah fokus kami.</h2><p>Hubungi tim kami untuk mendiskusikan aplikasi dan kebutuhan teknis Anda.</p></div>
            @endforelse
        </div>
    </section>
</div>
