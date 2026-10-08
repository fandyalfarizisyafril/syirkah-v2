<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Masuk CMS | Artomoro</title>
    <link rel="preload" as="font" href="{{ asset('fonts/InterVariable.woff2') }}" type="font/woff2" crossorigin>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="cms-login-page">
<main class="cms-login-shell">
    <section class="cms-login-brand" aria-labelledby="login-welcome">
        <div class="cms-login-streaks" aria-hidden="true"><span></span><span></span><span></span></div>
        <div class="cms-login-identity">
            <span class="cms-login-logo-viewport">
                <img src="{{ asset('images/company-logo-footer.png') }}" alt="SMART - Equipment & Parts Solutions" width="500" height="500" class="cms-login-logo">
            </span>
        </div>
        <div class="cms-login-welcome">
            <h2 id="login-welcome">Selamat Datang <br>Kembali.</h2>
            <p>Kelola konten dan informasi website PT. Syirkah Mandiri Artomoro melalui sistem administrasi.</p>
        </div>
        <a class="cms-login-back" href="{{ route('home') }}"><x-icon name="arrow-left"/>Kembali ke Website</a>
    </section>
    <section class="cms-login-form-panel" aria-labelledby="login-title">
        <div class="cms-login-form-content">
            <h1 id="login-title">Masuk ke CMS</h1>
            <p class="cms-login-intro">Silakan masuk dengan akun administrasi Anda.</p>
            @include('partials.alerts')
            <form action="{{ route('login.store') }}" method="post" data-submit-form>
                @csrf
                <x-field name="email" type="email" label="Email" required autocomplete="username" placeholder="nama@perusahaan.com"/>
                <div class="cms-login-password">
                    <x-field name="password" type="password" label="Password" required autocomplete="current-password"/>
                    <button class="cms-login-password-toggle" type="button" data-password-toggle aria-controls="password" aria-label="Tampilkan password" title="Tampilkan password" aria-pressed="false" hidden>
                        <span data-password-show><x-icon name="eye"/></span>
                        <span data-password-hide hidden><x-icon name="eye-off"/></span>
                    </button>
                </div>
                <button class="button full cms-login-submit" type="submit">Masuk <x-icon name="arrow-right"/></button>
            </form>
        </div>
    </section>
</main>
</body>
</html>
