<a class="category-item{{ isset($revealImage) ? ' has-reveal' : '' }}" href="{{ route('products.category', $category->slug) }}">
    @isset($revealImage)
        <span class="category-reveal" aria-hidden="true">
            <picture>
                <source media="(hover: hover) and (pointer: fine)" srcset="{{ asset($revealImage) }}">
                <img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="" width="768" height="512" loading="lazy" decoding="async">
            </picture>
        </span>
    @endisset
    @isset($number)<span class="category-number">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>@endisset
    <h3>{{ $category->name }}</h3>
    <p>{{ $category->description }}</p>
    <x-icon name="arrow-up-right"/>
</a>
