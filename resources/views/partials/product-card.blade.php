<article class="product-card">
    <a class="product-image" href="{{ $product->url }}"><img src="{{ $product->image_url }}" alt="{{ $product->image_alt ?: ($product->image ? $product->name : 'Gambar produk belum tersedia') }}" width="600" height="450" loading="lazy"></a>
    <div class="product-card-body"><p class="eyebrow">{{ $product->brand->name }}</p><h3><a href="{{ $product->url }}">{{ $product->name }}</a></h3><p>{{ $product->short_description }}</p><a href="{{ $product->url }}" class="text-link">Detail Produk <x-icon name="arrow-up-right"/></a></div>
</article>
