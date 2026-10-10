@php
    $technologyRows = old('technology_details', old('technology_details_present') ? [] : ($entry->technology_details ?? []));
    $technologyRows = is_array($technologyRows) ? array_filter($technologyRows, 'is_array') : [];
@endphp
<section class="form-section" data-brand-technologies>
    <h2>Fokus Teknologi Detail Brand</h2>
    <input type="hidden" name="technology_details_present" value="1">
    @error('technology_details')<p class="field-error" role="alert">{{ $message }}</p>@enderror
    <div data-technology-rows>
        @foreach($technologyRows as $index => $technology)
            <div class="spec-row" data-technology-row>
                <div class="field">
                    <label for="technology-name-{{ $index }}">Nama teknologi</label>
                    <input id="technology-name-{{ $index }}" name="technology_details[{{ $index }}][name]" value="{{ $technology['name'] ?? '' }}" maxlength="150" @error("technology_details.$index.name") aria-invalid="true" aria-describedby="technology-name-error-{{ $index }}" @enderror>
                    @error("technology_details.$index.name")<p class="field-error" id="technology-name-error-{{ $index }}">{{ $message }}</p>@enderror
                </div>
                <div class="field">
                    <label for="technology-description-{{ $index }}">Penjelasan singkat</label>
                    <textarea id="technology-description-{{ $index }}" name="technology_details[{{ $index }}][description]" maxlength="1000" rows="3" @error("technology_details.$index.description") aria-invalid="true" aria-describedby="technology-description-error-{{ $index }}" @enderror>{{ $technology['description'] ?? '' }}</textarea>
                    @error("technology_details.$index.description")<p class="field-error" id="technology-description-error-{{ $index }}">{{ $message }}</p>@enderror
                </div>
                <button type="button" class="icon-button danger" data-remove-technology title="Hapus teknologi" aria-label="Hapus teknologi"><x-icon name="trash-2"/></button>
            </div>
        @endforeach
    </div>
    <button type="button" class="button secondary small" data-add-technology><x-icon name="plus"/>Tambah Teknologi</button>
    <template data-technology-template>
        <div class="spec-row" data-technology-row>
            <div class="field"><label data-technology-label="name">Nama teknologi</label><input data-technology-field="name" maxlength="150"></div>
            <div class="field"><label data-technology-label="description">Penjelasan singkat</label><textarea data-technology-field="description" maxlength="1000" rows="3"></textarea></div>
            <button type="button" class="icon-button danger" data-remove-technology title="Hapus teknologi" aria-label="Hapus teknologi"><x-icon name="trash-2"/></button>
        </div>
    </template>
</section>
@vite('resources/js/brand-editor.js')
