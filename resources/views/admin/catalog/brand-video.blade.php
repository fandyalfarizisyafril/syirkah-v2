<section class="form-section" data-brand-video-editor>
    <h2>Video Brand</h2>
    <div class="field">
        <label for="video_source">Sumber video</label>
        <select id="video_source" name="video_source">
            @foreach(['none' => 'Tanpa video / hapus video', 'mp4' => 'Upload MP4', 'youtube' => 'URL YouTube'] as $value => $label)
                <option value="{{ $value }}" @selected(old('video_source', $entry->video['source'] ?? 'none') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('video_source')<p class="field-error" role="alert">{{ $message }}</p>@enderror
    </div>
    <div data-video-mp4>
        <x-field name="video_file" type="file" label="Video MP4 (maks. 50 MB; kosongkan untuk mempertahankan video)" accept=".mp4,video/mp4"/>
    </div>
    <div data-video-youtube>
        <x-field name="video_youtube_url" type="url" label="URL YouTube (HTTPS)" :value="isset($entry->video['youtube_id']) ? 'https://www.youtube.com/watch?v='.$entry->video['youtube_id'] : ''" maxlength="500"/>
    </div>
    <div data-video-fields>
        <x-field name="video_title" label="Judul video (opsional)" :value="$entry->video['title'] ?? ''" maxlength="200"/>
        <x-field name="video_description" type="textarea" label="Deskripsi video (opsional)" :value="$entry->video['description'] ?? ''" maxlength="1500" rows="3"/>
        <x-field name="video_poster" type="file" label="Poster / thumbnail video (opsional; JPG, PNG, WebP; maks. 4 MB)" accept=".jpg,.jpeg,.png,.webp"/>
        <img class="admin-preview" data-video-poster-preview @if(!empty($entry->video['poster'])) src="{{ asset('storage/'.$entry->video['poster']) }}" @else hidden @endif alt="Poster video {{ $entry->name }}" width="180" height="140">
        @if(!empty($entry->video['poster']))
            <label class="check-field"><input type="checkbox" name="remove_video_poster" value="1" @checked(old('remove_video_poster'))>Hapus poster</label>
        @endif
        <input type="hidden" name="video_width" value="{{ old('video_width', $entry->video['width'] ?? 16) }}">
        <input type="hidden" name="video_height" value="{{ old('video_height', $entry->video['height'] ?? 9) }}">
        @error('video_width')<p class="field-error" role="alert">{{ $message }}</p>@enderror
        @error('video_height')<p class="field-error" role="alert">{{ $message }}</p>@enderror
        <div class="brand-video-admin-preview" data-video-preview>
            @if($entry->video)
                @include('partials.brand-video-player', ['video' => $entry->video, 'videoTitle' => ($entry->video['title'] ?? null) ?: 'Video '.$entry->name])
            @endif
        </div>
        <p class="field-error" data-video-error role="alert" hidden></p>
    </div>
</section>
