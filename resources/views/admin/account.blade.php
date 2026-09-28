@extends('layouts.admin')
@section('title', 'Akun Saya')
@section('content')
<div class="admin-heading"><div><h1>Akun Saya</h1><p>{{ auth()->user()->email }}</p></div></div><form class="editor-form" method="post" action="{{ route('admin.account.update') }}">@csrf @method('PUT')<section class="form-section"><h2>Ubah Password</h2><x-field name="current_password" type="password" label="Password saat ini" required autocomplete="current-password"/><x-field name="password" type="password" label="Password baru (min. 12 karakter, huruf besar/kecil dan angka)" required minlength="12" autocomplete="new-password"/><x-field name="password_confirmation" type="password" label="Konfirmasi password baru" required minlength="12" autocomplete="new-password"/></section><button class="button"><x-icon name="save"/>Simpan Password</button></form>
@endsection
