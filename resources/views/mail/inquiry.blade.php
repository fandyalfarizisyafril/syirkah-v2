<h1>Inquiry baru</h1>
<p>Referensi: {{ $inquiry->reference_number }}</p>
<p>{{ $inquiry->name }} / {{ $inquiry->company }}</p>
<p>Email: {{ $inquiry->email }}<br>Telepon: {{ $inquiry->phone }}</p>
<p>Produk: {{ $inquiry->product?->name ?: 'Konsultasi umum' }}</p>
<p>Aplikasi: {{ $inquiry->application }}</p>
<p style="white-space: pre-line">{{ $inquiry->message }}</p>
<a href="{{ route('admin.inquiries.show', $inquiry) }}">Buka inquiry di CMS</a>
