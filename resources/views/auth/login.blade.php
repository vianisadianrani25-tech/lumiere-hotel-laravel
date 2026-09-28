<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière Hotel | Login</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    <style>
        body.login-page { background: #eee9df; color: #2d2925; }
        .login-page { min-height: 100vh; display: grid; grid-template-columns: 1.05fr .95fr; padding: 45px; gap: 0; }
        .login-visual { min-height: calc(100vh - 90px); position: relative; overflow: hidden; border-radius: 14px 0 0 14px; background: #333; }
        .login-visual img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .visual-overlay { position: absolute; inset: 0; padding: 55px; display: flex; flex-direction: column; justify-content: space-between; background: linear-gradient(180deg, rgba(0,0,0,.30), rgba(0,0,0,.10) 45%, rgba(0,0,0,.65)); }
        .logo { color: white; font-family: Georgia, "Times New Roman", serif; font-size: 23px; font-weight: bold; letter-spacing: 4px; }
        .hotel-star { color: #c7a45b; margin-right: 6px; }
        .visual-text { max-width: 520px; }
        .visual-text span { color: #d2b46c; font-size: 11px; font-weight: bold; letter-spacing: 4px; }
        .visual-text h1 { color: white; font-family: Georgia, "Times New Roman", serif; font-size: clamp(45px, 5vw, 72px); font-weight: normal; line-height: 1.02; margin: 15px 0 25px; }
        .visual-text p { color: rgba(255,255,255,.85); max-width: 450px; line-height: 1.7; font-size: 14px; }
        .login-form-section { min-height: calc(100vh - 90px); display: flex; align-items: center; justify-content: center; padding: 55px; background: #fffdf9; border-radius: 0 14px 14px 0; }
        .login-form-box { width: 100%; max-width: 420px; }
        .mobile-logo { display: none; }
        .welcome { color: #b49355; font-size: 10px; font-weight: bold; letter-spacing: 3px; margin-bottom: 13px; }
        .login-form-box h2 { color: #292521; font-family: Georgia, "Times New Roman", serif; font-size: 38px; font-weight: normal; margin-bottom: 10px; }
        .login-description { color: #81786e; font-size: 13px; line-height: 1.6; margin-bottom: 34px; }
        .input-group { margin-bottom: 19px; }
        .input-group label { display: block; color: #49423b; font-size: 11px; font-weight: bold; margin-bottom: 8px; }
        .input-wrapper { display: flex; align-items: center; gap: 10px; height: 51px; background: #faf8f3; border: 1px solid #ded7cb; border-radius: 5px; padding: 0 14px; transition: .25s; }
        .input-wrapper:focus-within { border-color: #b49355; box-shadow: 0 0 0 3px rgba(180,147,85,.10); }
        .input-wrapper > span { opacity: .65; font-size: 14px; }
        .input-wrapper input { width: 100%; height: 48px; border: none; outline: none; background: transparent; color: #332e29; font-size: 13px; }
        .input-wrapper input::placeholder { color: #a39b91; }
        .show-password { border: none; background: transparent; cursor: pointer; font-size: 14px; opacity: .65; }
        .login-options { display: flex; justify-content: space-between; align-items: center; margin: 5px 0 25px; font-size: 11px; }
        .remember { display: flex; gap: 7px; align-items: center; color: #81786e; }
        .remember input { accent-color: #b49355; }
        .login-options a, .register-text a { color: #b49355; text-decoration: none; font-weight: bold; }
        .login-options a:hover, .register-text a:hover { color: #8f713d; text-decoration: underline; }
        .login-button { width: 100%; height: 52px; border: none; border-radius: 5px; background: #292521; color: white; font-size: 10px; font-weight: bold; letter-spacing: 2px; cursor: pointer; transition: .25s; }
        .login-button span { margin-left: 8px; font-size: 15px; }
        .login-button:hover { background: #b49355; transform: translateY(-1px); }
        .register-text { text-align: center; color: #81786e; font-size: 11px; margin-top: 23px; }
        .demo-account { margin-top: 24px; padding: 13px 15px; background: #f7f3eb; border: 1px solid #e4ddd1; border-radius: 5px; color: #8a8177; font-size: 10px; }
        .demo-account small { display: block; color: #b49355; font-size: 8px; font-weight: bold; letter-spacing: 2px; margin-bottom: 7px; }
        .demo-account p { margin: 3px 0; }
        .demo-account b { color: #4a433c; }
        .login-notification { display: none; padding: 12px 14px; margin: -2px 0 17px; border-radius: 4px; font-size: 11px; line-height: 1.5; }
        .login-notification.show { display: block; }
        .login-notification.error { background: #f8e9e5; border-left: 3px solid #b85c4e; color: #8c4035; }
        .login-notification.success { background: #eaf3ea; border-left: 3px solid #648d69; color: #476b4c; }
        .login-notification.warning { background: #f8f0df; border-left: 3px solid #b49355; color: #7c632f; }
        .login-footer { text-align: center; color: #aaa198; font-size: 9px; margin-top: 45px; }
        @media (max-width: 850px) {
            .login-page { grid-template-columns: 1fr; padding: 0; }
            .login-visual { display: none; }
            .login-form-section { min-height: 100vh; border-radius: 0; padding: 35px 25px; }
            .login-form-box { max-width: 440px; }
            .login-form-box h2 { font-size: 32px; }
        }
    </style>

</head>

<body class="login-page">

    <section class="login-visual">
        <img src="{{ asset('assets/login-bg.jpg') }}" alt="Lumière Hotel">

        <div class="visual-overlay">
            <div class="logo"><span class="hotel-star">✦</span> LUMIÈRE</div>

            <div class="visual-text">
                <span>LUMIÈRE HOTEL & RESORT</span>
                <h1>Stay.<br>Relax.<br>Remember.</h1>
                <p>Experience comfort and elegance in every moment of your stay.</p>
            </div>
        </div>
    </section>

    <section class="login-form-section">

        <div class="login-form-box">

            <div class="mobile-logo">✦ LUMIÈRE</div>

            <p class="welcome">WELCOME BACK</p>
            <h2>Selamat Datang 👋</h2>
            <p class="login-description">Masuk ke akunmu untuk mulai memesan kamar hotel.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <span>♙</span>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" autocomplete="username" autofocus>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span>▣</span>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" autocomplete="current-password">
                        <button type="button" id="togglePassword" class="show-password">👁</button>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="login-notification show error">
                        {{ $errors->first() }}
                    </div>
                @elseif (session('status'))
                    <div class="login-notification show success">
                        ✓ {{ session('status') }}
                    </div>
                @endif

                <div class="login-options">
                    <label class="remember">
                        <input type="checkbox" id="remember" name="remember">
                        <span>Ingat saya</span>
                    </label>

                    <a href="{{ route('password.request') }}">Lupa password?</a>
                </div>

                <button type="submit" class="login-button">
                    MASUK
                    <span>→</span>
                </button>

            </form>

            <p class="register-text">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar sekarang</a>
            </p>

            <div class="demo-account">
                <small>DEMO ACCOUNT</small>
                <p>Username: <b>vianissa</b></p>
                <p>Password: <b>12345</b></p>
            </div>

            <p class="login-footer">© {{ now()->year }} Lumière Hotel & Resort</p>

        </div>

    </section>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                togglePassword.textContent = isHidden ? '🙈' : '👁';
            });
        }
    </script>

</body>

</html>
