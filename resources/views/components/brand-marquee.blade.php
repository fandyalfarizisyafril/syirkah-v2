@props(['brands'])
@php($logos = $brands->filter(fn ($brand) => filled($brand->image))->values())
@if($logos->isNotEmpty())
<div @class(['home-brand-marquee', 'is-single' => $logos->count() === 1]) role="region" aria-label="Brand dan principal">
    <div class="home-brand-track">
        @for($copy = 0; $copy < ($logos->count() > 1 ? 2 : 1); $copy++)
            <ul class="home-brand-group" @if($copy) aria-hidden="true" @endif>
                @foreach($logos as $brand)
                    <li>
                        <a href="{{ route('brands.show', $brand->slug) }}" @if($copy) tabindex="-1" @endif>
                            <img src="{{ $brand->image_url }}" alt="{{ $copy ? '' : $brand->name }}" width="160" height="64" loading="lazy" decoding="async">
                        </a>
                    </li>
                @endforeach
            </ul>
        @endfor
    </div>
</div>
@endif
