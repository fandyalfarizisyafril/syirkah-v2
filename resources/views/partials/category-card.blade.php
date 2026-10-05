<a class="category-item" href="{{ route('products.category', $category->slug) }}">
    @isset($number)<span class="category-number">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>@endisset
    <h3>{{ $category->name }}</h3>
    <p>{{ $category->description }}</p>
    <x-icon name="arrow-up-right"/>
</a>
