@php
    $storage = app(\App\Services\PublicationStorage::class);
    $thumbnail = $storage->exists($document->thumbnail_path);
    $image = str_starts_with($document->mime_type, 'image/');
    $publishedAt = $document->published_at?->copy()->setTimezone(config('publications.timezone'));
@endphp
<article class="publication-card">
    <div class="publication-preview">
        <x-icon name="file-text" class="publication-placeholder"/>
        @if($thumbnail || $image)
            <img src="{{ route($thumbnail ? 'publications.thumbnail' : 'publications.file', $document) }}" alt="Preview {{ $document->title }}" width="640" height="400" loading="lazy" data-publication-thumbnail>
        @endif
        <span class="publication-file-type">{{ $document->file_type }}</span>
    </div>
    <div class="publication-card-body">
        <p class="publication-category">{{ $document->category_label }}@if($document->is_demo)<span class="publication-demo">DEMO</span>@endif</p>
        <h3>{{ $document->title }}</h3>
        @if($document->description)<p class="publication-description">{{ $document->description }}</p>@endif
        @if($publishedAt)<p class="publication-date">Dipublikasikan <time datetime="{{ $publishedAt->toDateString() }}">{{ $publishedAt->translatedFormat('d M Y') }}</time></p>@endif
        <div class="publication-actions">
            <a class="text-link" href="{{ route('publications.preview', $document) }}" aria-label="Lihat dokumen {{ $document->title }}">Lihat Dokumen <x-icon name="arrow-up-right"/></a>
            @if($document->allow_download)<a class="text-link publication-download" href="{{ route('publications.download', $document) }}" aria-label="Unduh {{ $document->title }}">Unduh <x-icon name="download"/></a>@endif
        </div>
    </div>
</article>
