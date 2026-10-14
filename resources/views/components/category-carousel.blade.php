@props(['categories'])

<div class="home-category-carousel" data-category-carousel role="region" aria-label="Kategori produk">
    <ul class="home-category-track" id="home-category-track">
        @foreach($categories as $category)
            @php
                $legacyImage = config('homepage-category-images.'.$category->slug);
                $image = $category->image ? $category->image_url : ($legacyImage ? asset($legacyImage) : $category->image_url);
                $imageAlt = $category->image || $legacyImage ? ($category->image_alt ?: $category->name) : 'Gambar kategori belum tersedia';
            @endphp
            <li class="home-category-card" data-category="{{ $category->slug }}">
                <a class="home-category-link" draggable="false" href="{{ route('products.category', $category->slug) }}" aria-labelledby="category-title-{{ $category->id }}" aria-describedby="category-description-{{ $category->id }}">
                    <img class="home-category-image" draggable="false" src="{{ $image }}" alt="{{ $imageAlt }}" width="768" height="512" loading="lazy" decoding="async">
                    <span class="home-category-title" aria-hidden="true">{{ $category->name }}</span>
                    <div class="home-category-panel" id="category-panel-{{ $category->id }}">
                        <h3 id="category-title-{{ $category->id }}">{{ $category->name }}</h3>
                        <p id="category-description-{{ $category->id }}">{{ $category->description }}</p>
                    </div>
                </a>
                <button class="home-category-info" type="button" data-category-info aria-controls="category-panel-{{ $category->id }}" aria-expanded="false" aria-label="Informasi {{ $category->name }}" title="Informasi {{ $category->name }}" hidden><x-icon name="info"/></button>
            </li>
        @endforeach
    </ul>
    <div class="home-category-controls" hidden>
        <button type="button" class="icon-button" data-category-prev aria-label="Kategori sebelumnya" title="Kategori sebelumnya" aria-controls="home-category-track" disabled><x-icon name="arrow-left"/></button>
        <button type="button" class="icon-button" data-category-next aria-label="Kategori berikutnya" title="Kategori berikutnya" aria-controls="home-category-track"><x-icon name="arrow-right"/></button>
    </div>
</div>
