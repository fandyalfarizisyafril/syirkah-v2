@if(session('success'))<div class="alert success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())
<div class="alert error" role="alert" tabindex="-1" data-error-summary>
    <strong>Periksa kembali data berikut.</strong>
    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif
