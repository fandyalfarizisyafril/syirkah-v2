@extends('layouts.public')
@section('title', 'Kontak & Request Inquiry | Artomoro')
@section('content')
<section class="page-intro container"><p class="eyebrow">CONTACT & INQUIRY</p><h1>Mari diskusikan<br>kebutuhan Anda.</h1><p class="lead">Sampaikan kebutuhan equipment, spare part, atau aplikasi industri Anda.</p></section>
<section class="container contact-grid catalog-section"><div class="contact-info"><h2>Hubungi Artomoro</h2><p>{{ $settings['company_name'] }}</p><address>{{ $settings['address'] }}</address><a href="tel:{{ preg_replace('/[^+0-9]/', '', $settings['phone']) }}"><x-icon name="phone"/> {{ $settings['phone'] }}</a><a href="mailto:{{ $settings['email'] }}"><x-icon name="mail"/> {{ $settings['email'] }}</a>@if($settings['sales_email'])<a href="mailto:{{ $settings['sales_email'] }}"><x-icon name="mail"/> {{ $settings['sales_email'] }}</a>@endif @if($settings['whatsapp_number'])<a class="text-link" href="https://wa.me/{{ $settings['whatsapp_number'] }}" target="_blank" rel="noopener noreferrer"><x-icon name="message-circle"/> Chat WhatsApp</a>@endif @if($settings['map_url'])<a href="{{ $settings['map_url'] }}" target="_blank" rel="noopener noreferrer">Lihat Lokasi <x-icon name="arrow-up-right"/></a>@endif</div>
<div>@if(session('inquiry_reference'))<div class="alert success" role="status"><h2>Inquiry berhasil diterima.</h2><p>Nomor referensi Anda:</p><strong class="reference">{{ session('inquiry_reference') }}</strong><p>Tim kami akan menindaklanjuti kebutuhan Anda melalui kontak yang diberikan.</p></div>@endif
@include('partials.alerts')
<form method="post" action="{{ route('inquiry.store') }}" class="inquiry-form" data-submit-form>@csrf
    <h2>Request Inquiry</h2>
    <input type="hidden" name="source_page" value="{{ old('source_page', request('product') ? '/kontak?product='.request('product') : '/kontak') }}">
    <div class="honeypot" aria-hidden="true"><label for="website">Website</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
    <div class="form-grid"><x-field name="name" label="Nama lengkap" required maxlength="150" autocomplete="name"/><x-field name="company" label="Perusahaan" required maxlength="200" autocomplete="organization"/><x-field name="email" type="email" label="Email" required maxlength="254" autocomplete="email"/><x-field name="phone" type="tel" label="Telepon / WhatsApp" required maxlength="30" autocomplete="tel"/></div>
    <div class="field"><label for="product_id">Produk yang diminati</label><select name="product_id" id="product_id"><option value="">Konsultasi umum</option>@foreach($products as $product)<option value="{{ $product->id }}" @selected(old('product_id', request('product')) == $product->id)>{{ $product->name }}</option>@endforeach</select>@error('product_id')<p class="field-error">{{ $message }}</p>@enderror</div>
    <x-field name="application" label="Kebutuhan / aplikasi" maxlength="2000"/><x-field name="message" type="textarea" label="Pesan" required rows="5" minlength="10" maxlength="5000"/>
    <label class="check-field"><input type="checkbox" name="consent" value="1" required @checked(old('consent'))><span>Saya menyetujui pemrosesan data untuk menindaklanjuti inquiry ini sesuai <a href="{{ route('privacy') }}">Kebijakan Privasi</a>.</span></label>
    <button type="submit" class="button">Kirim Inquiry <x-icon name="arrow-up-right"/></button>
</form></div></section>
@endsection
