@extends('layouts.public')
@section('title', 'Kebijakan Privasi | Artomoro')
@section('content')
<article class="container section reading"><p class="eyebrow">INFORMASI PENGUNJUNG</p><h1>Kebijakan Privasi</h1><p>Form inquiry mengumpulkan nama, perusahaan, email, nomor telepon, produk yang diminati, serta kebutuhan dan pesan yang Anda kirimkan.</p><h2>Penggunaan Data</h2><p>Data digunakan oleh {{ $settings['company_name'] }} untuk menindaklanjuti inquiry, menjawab pertanyaan teknis, dan menghubungi Anda terkait kebutuhan yang disampaikan. Akses data dibatasi pada petugas yang berwenang.</p><h2>Penyimpanan dan Kontak</h2><p>Inquiry disimpan pada sistem perusahaan. Untuk pertanyaan mengenai data, permintaan koreksi, atau permintaan penghapusan, hubungi <a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a>.</p><h2>Cookie</h2><p>Website menggunakan cookie sesi untuk keamanan formulir dan login admin.</p></article>
@endsection
