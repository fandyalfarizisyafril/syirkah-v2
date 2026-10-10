@php
    $heroSummary = filled($entry->focus) ? $entry->focus : $entry->description;
    $profile = trim($entry->description ?? '');
    $profileParagraphs = preg_split('/\R\s*\R/u', $profile, -1, PREG_SPLIT_NO_EMPTY);
    // Respect editorial paragraph boundaries instead of truncating a sentence.
    $heroDescription = $profileParagraphs[0] ?? '';
    $showProfile = filled($profile);
    if (count($profileParagraphs) > 1) {
        $profileParagraphs = array_slice($profileParagraphs, 1);
    }
    $primaryCategory = $relatedCategories->firstWhere('name', $heroSummary);
    $technologyDetails = collect($entry->technology_details ?? []);
    $technologyDescriptions = $relatedCategories->mapWithKeys(function ($category) use ($profile, $heroDescription, $heroSummary) {
        $description = trim($category->description ?? '');
        return [$category->id => filled($description) && !in_array($description, [$profile, $heroDescription, trim($heroSummary ?? '')], true) ? $description : null];
    });
@endphp
<div class="brand-detail">
    <section @class(['container', 'brand-detail-hero', 'has-logo' => filled($entry->image)]) aria-labelledby="brand-title">
        <div class="brand-detail-intro">
            <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>/</span><a href="{{ route('brands.index') }}">Brand</a><span>/</span><span aria-current="page">{{ $entry->name }}</span></nav>
            <p class="brand-detail-eyebrow">BRANDS & PRINCIPALS</p>
            <h1 id="brand-title">{{ $entry->name }}</h1>
            @if(filled($heroDescription))<p class="brand-hero-description">{{ $heroDescription }}</p>@endif
            @if(filled($heroSummary) && ($primaryCategory || trim($heroSummary) !== $profile))
                <p class="brand-detail-summary">
                    @if($primaryCategory)
                        <a href="{{ route('products.category', $primaryCategory->slug) }}">{{ $heroSummary }} <x-icon name="arrow-up-right"/></a>
                    @else
                        {{ $heroSummary }}
                    @endif
                </p>
            @endif
            @if(!$showProfile && $entry->website_url)
                <a class="text-link brand-principal-link" href="{{ $entry->website_url }}" target="_blank" rel="noopener noreferrer">Website Principal <x-icon name="arrow-up-right"/></a>
            @endif
        </div>
        @if($entry->image)
            <div class="brand-detail-logo"><img src="{{ $entry->image_url }}" alt="{{ $entry->image_alt ?: $entry->name }}" width="640" height="400" decoding="async" fetchpriority="high"></div>
        @endif
    </section>

    @if($showProfile)
        <section class="container brand-detail-profile" aria-labelledby="brand-profile-title">
            <div><p class="brand-detail-eyebrow">01 / TENTANG BRAND</p><h2 id="brand-profile-title">Mengenal {{ $entry->name }}</h2></div>
            <div class="brand-detail-profile-copy">
                @foreach($profileParagraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                @if($entry->website_url)
                    <a class="text-link brand-principal-link" href="{{ $entry->website_url }}" target="_blank" rel="noopener noreferrer">Website Principal <x-icon name="arrow-up-right"/></a>
                @endif
            </div>
        </section>
    @endif

    @if($technologyDetails->isNotEmpty() || $relatedCategories->isNotEmpty())
        <section @class(['brand-detail-technology', 'is-compact' => $technologyDetails->isNotEmpty() || $technologyDescriptions->filter()->isEmpty()]) aria-labelledby="brand-technology-title">
            <div class="container">
                <p class="brand-detail-eyebrow">02 / FOKUS TEKNOLOGI</p>
                <h2 id="brand-technology-title">Teknologi dan solusi {{ $entry->name }}</h2>
                <div @class(['brand-technology-grid', 'is-single' => ($technologyDetails->isNotEmpty() ? $technologyDetails->count() : $relatedCategories->count()) === 1])>
                    @if($technologyDetails->isNotEmpty())
                        @foreach($technologyDetails as $technology)
                            <div @class(['brand-technology-item', 'has-description' => filled($technology['description'] ?? null)])>
                                <span class="brand-technology-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3>{{ $technology['name'] }}</h3>
                                @if(filled($technology['description'] ?? null))<p>{{ $technology['description'] }}</p>@endif
                            </div>
                        @endforeach
                    @else
                    @foreach($relatedCategories as $category)
                        @php($showCategoryDescription = filled($technologyDescriptions[$category->id]))
                        <a @class(['brand-technology-item', 'has-description' => $showCategoryDescription]) href="{{ route('products.category', $category->slug) }}">
                            <span class="brand-technology-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $category->name }}</h3>
                            @if($showCategoryDescription)<p>{{ $technologyDescriptions[$category->id] }}</p>@endif
                            <x-icon name="arrow-up-right"/>
                        </a>
                    @endforeach
                    @endif
                </div>
            </div>
        </section>
    @endif

    <section class="container brand-detail-products" aria-labelledby="brand-products-title">
        <div class="brand-products-heading">
            <div><p class="brand-detail-eyebrow">03 / PRODUK TERKAIT</p><h2 id="brand-products-title">Jelajahi produk {{ $entry->name }}</h2></div>
            @if($products->isNotEmpty())<a class="text-link brand-all-products" href="{{ route('products.index', ['brand' => $entry->id]) }}">Semua produk {{ $entry->name }} <x-icon name="arrow-up-right"/></a>@endif
        </div>
        <div class="product-grid">
            @forelse($products as $product)
                @include('partials.product-card')
            @empty
                <div class="empty-state"><x-icon name="package-search"/><h3>Produk belum tersedia.</h3><p>Hubungi tim kami untuk informasi produk {{ $entry->name }} dan kebutuhan teknis Anda.</p><a class="text-link" href="{{ route('contact') }}">Hubungi Kami <x-icon name="arrow-up-right"/></a></div>
            @endforelse
        </div>
    </section>
</div>
