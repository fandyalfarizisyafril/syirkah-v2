<div class="category-directory-page technical-detail-page">
    <section class="page-intro container">
        <nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('home') }}">Beranda</a><span>/</span><a href="{{ route('products.index') }}">Produk</a><span>/</span><span aria-current="page">{{ $current->name }}</span></nav>
        <p class="eyebrow detail-eyebrow">PRODUCTS & SOLUTIONS</p>
        <h1>{{ $current->name }}</h1>
        @if($current->description)<p class="lead">{{ $current->description }}</p>@endif
    </section>
    <section class="category-product-directory">
        <div class="container">
            <div class="section-heading category-results-heading"><h2>Produk dalam Kategori Ini</h2><p class="result-count">{{ $products->total() }} produk tersedia</p></div>
            <form method="get" action="{{ route('products.index') }}" class="filter-bar" role="search">
                <x-field name="q" label="Pencarian" :value="request('q')" placeholder="Nama, seri, atau kata kunci" maxlength="150"/>
                <div class="field"><label for="category">Kategori</label><select id="category" name="category"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request()->query('category', $current->id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
                <div class="field"><label for="brand">Brand</label><select id="brand" name="brand"><option value="">Semua brand</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
                <button class="button" type="submit"><x-icon name="search"/> Cari</button>
                <a class="button secondary" href="{{ route('products.index') }}" title="Reset filter" aria-label="Reset filter"><x-icon name="rotate-ccw"/> Reset</a>
            </form>
            <div class="product-grid">
                @forelse($products as $product)
                    @include('partials.product-card')
                @empty
                    <div class="empty-state"><x-icon name="package-search"/>
                        @if(request()->filled('q') || request()->filled('brand') || request()->filled('category'))
                            <h2>Tidak ada produk yang ditemukan.</h2><p>Kami tidak menemukan produk yang sesuai dengan pencarian Anda.</p>
                            <a href="{{ route('products.index') }}" class="button secondary"><x-icon name="rotate-ccw"/> Reset filter</a>
                        @else
                            <h2>Produk belum tersedia.</h2><p>Hubungi tim kami untuk informasi produk dan kebutuhan teknis Anda.</p><a href="{{ route('contact') }}" class="button">Konsultasi Produk <x-icon name="arrow-up-right"/></a>
                        @endif
                    </div>
                @endforelse
            </div>
            {{ $products->links() }}
        </div>
    </section>
    @if($relatedBrands->isNotEmpty())
        <section class="container detail-editorial detail-sections">
            <div><p class="detail-eyebrow">RELATED BRANDS</p><h2>Brand Terkait</h2></div>
            <div class="detail-directory-links related-brand-links">
                @foreach($relatedBrands as $brand)
                    <a href="{{ route('brands.show', $brand->slug) }}">@if($brand->image)<img src="{{ $brand->image_url }}" alt="{{ $brand->image_alt ?: $brand->name }}" width="120" height="56" loading="lazy">@endif<span>{{ $brand->name }}</span><x-icon name="arrow-up-right"/></a>
                @endforeach
            </div>
        </section>
    @endif
    @if($relatedIndustries->isNotEmpty())
        <section class="container detail-editorial detail-sections">
            <div><h2>Industri & Aplikasi Terkait</h2></div>
            <div class="detail-directory-links">@foreach($relatedIndustries as $industry)<a href="{{ route('industries.show', $industry->slug) }}">{{ $industry->name }} <x-icon name="arrow-up-right"/></a>@endforeach</div>
        </section>
    @endif
    @php($otherCategories = $categories->where('id', '!=', $current->id)->take(3))
    @if($otherCategories->isNotEmpty())
        <section class="container category-other-links">
            <div class="section-heading"><h2>Jelajahi Kategori Lainnya</h2></div>
            <div class="detail-directory-links">@foreach($otherCategories as $category)<a href="{{ route('products.category', $category->slug) }}">{{ $category->name }} <x-icon name="arrow-up-right"/></a>@endforeach</div>
        </section>
    @endif
</div>
