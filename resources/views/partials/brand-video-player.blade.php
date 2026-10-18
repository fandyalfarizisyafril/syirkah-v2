@php
    $videoWidth = max(1, (int) ($video['width'] ?? 16));
    $videoHeight = max(1, (int) ($video['height'] ?? 9));
@endphp
<div class="brand-video-player" data-brand-video-player style="--video-ratio:{{ $videoWidth }} / {{ $videoHeight }};--video-width:{{ min(720, round(560 * $videoWidth / $videoHeight)) }}px">
    @if(($video['source'] ?? '') === 'mp4')
        <video controls playsinline preload="{{ !empty($video['poster']) ? 'none' : 'metadata' }}" width="{{ $videoWidth }}" height="{{ $videoHeight }}" aria-label="{{ $videoTitle }}" @if(!empty($video['poster'])) poster="{{ asset('storage/'.$video['poster']) }}" @endif>
            <source src="{{ route('brands.video', $entry->slug) }}" type="video/mp4">
            Browser Anda tidak mendukung video HTML5. <a href="{{ route('brands.video', $entry->slug) }}">Buka video</a>.
        </video>
        <p class="brand-video-error" data-video-playback-error hidden role="status">Video belum dapat diputar. <a href="{{ route('brands.video', $entry->slug) }}" target="_blank" rel="noopener">Buka video di tab baru</a>.</p>
    @elseif(($video['source'] ?? '') === 'youtube' && preg_match('/^[a-zA-Z0-9_-]{11}$/D', $video['youtube_id'] ?? ''))
        <iframe src="https://www.youtube-nocookie.com/embed/{{ $video['youtube_id'] }}?playsinline=1&amp;rel=0" title="{{ $videoTitle }}" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="encrypted-media; fullscreen; picture-in-picture" allowfullscreen></iframe>
    @endif
</div>
