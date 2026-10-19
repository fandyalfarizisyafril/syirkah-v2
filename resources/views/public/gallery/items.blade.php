@foreach($items as $item)
    <article class="gallery-card" data-gallery-key="{{ $item['key'] }}">
        <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" class="gallery-open" data-gallery-item="{{ json_encode($item) }}">
            <div class="gallery-thumbnail" style="--media-ratio:{{ $item['width'] }} / {{ $item['height'] }}">
                <span class="gallery-fallback" aria-hidden="true"><x-icon :name="$item['source'] === 'photo' ? 'image' : 'video'"/></span>
                @if($item['thumbnail'])<img src="{{ $item['thumbnail'] }}" alt="" width="{{ $item['width'] }}" height="{{ $item['height'] }}" loading="lazy" decoding="async" data-gallery-thumbnail>@endif
                @if($item['source'] !== 'photo')<span class="gallery-play" aria-hidden="true"><x-icon name="play"/></span>@endif
            </div>
            <div class="gallery-caption"><p class="gallery-label">{{ $item['label'] }}</p><h3>{{ $item['title'] }}</h3>@if($item['description'])<p class="gallery-description">{{ $item['description'] }}</p>@endif</div>
        </a>
    </article>
@endforeach
