@extends('layouts.public')
@section('title', 'Tentang Kami | Artomoro')
@section('content')
<section class="page-intro container"><p class="eyebrow">TENTANG KAMI</p><h1>{{ $settings['company_name'] }}</h1><p class="lead">{{ $settings['tagline'] }}</p></section>
<section class="container about-content"><img class="about-image" src="{{ asset('images/industrial.jpg') }}" alt="Instalasi fasilitas industri" width="1200" height="600"><div class="company-grid section"><h2>Partner untuk<br>kebutuhan teknis industri.</h2><p class="lead prose">{{ $settings['profile'] }}</p></div><h2>Bidang Fokus</h2><div class="category-grid">@foreach(['Engineering', 'Mechanical', 'Electrical', 'Instrumentation', 'Oil Spill Response & Prevention'] as $focus)<div class="category-item"><span class="category-number">0{{ $loop->iteration }}</span><h3>{{ $focus }}</h3></div>@endforeach</div><section class="section"><h2>Nilai Kami</h2><div class="value-strip">@foreach(['Reliability', 'Efficiency', 'Operational Continuity', 'Safety', 'Environmental Protection'] as $value)<div><x-icon name="check"/>{{ $value }}</div>@endforeach</div></section>@if($settings['legal_information'])<section id="legal" class="section"><h2>Informasi Legal</h2><p class="prose">{{ $settings['legal_information'] }}</p></section>@endif</section>
@include('partials.inquiry-cta')
@endsection
