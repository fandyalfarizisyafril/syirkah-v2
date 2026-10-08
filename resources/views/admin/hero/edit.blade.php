@extends('layouts.admin')
@section('title', 'Edit Hero Beranda')
@section('content')
<div class="admin-heading"><div><a class="text-link" href="{{ route('admin.hero.index') }}"><x-icon name="arrow-left"/>Hero Beranda</a><h1>Edit Hero: {{ $default['navLabel'] }}</h1></div></div>
<form method="post" enctype="multipart/form-data" action="{{ route('admin.hero.update', $slide['id']) }}" class="editor-form" data-submit-form>
    @csrf @method('PUT')
    <section class="form-section"><h2>Konten Slide</h2>
        <div class="form-grid">
            <x-field name="nav_label" label="Label Navigasi" :value="$slide['navLabel']" required maxlength="60"/>
            <x-field name="eyebrow" label="Eyebrow / Kategori" :value="$slide['eyebrow']" required maxlength="80"/>
        </div>
        <x-field name="title_line_1" label="Headline - Baris 1" :value="$slide['title'][0]" required maxlength="100"/>
        <x-field name="title_line_2" label="Headline - Baris 2" :value="$slide['title'][1] ?? ''" maxlength="100"/>
        <x-field name="description" label="Deskripsi" type="textarea" :value="$slide['description']" required maxlength="350" rows="4"/>
    </section>
    <section class="form-section"><h2>Gambar Hero</h2>
        <img class="admin-preview" src="{{ asset($slide['image']) }}" alt="Gambar hero saat ini" width="180" height="140">
        <x-field name="image" label="Gambar Baru (JPG, PNG, WebP; maks. 4 MB)" type="file" accept=".jpg,.jpeg,.png,.webp"/>
        @if($entry?->image)<label class="check-field"><input type="checkbox" name="reset_image" value="1" @checked(old('reset_image'))>Kembalikan gambar awal</label>@endif
        @error('reset_image')<p class="field-error">{{ $message }}</p>@enderror
    </section>
    <div class="form-actions"><button class="button" type="submit"><x-icon name="save"/>Simpan Hero</button><a class="button secondary" href="{{ route('admin.hero.index') }}">Batal</a></div>
</form>
@endsection
