<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière Hotel | Daftar</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    <style>
        body.register-page { margin: 0; min-height: 100vh; background: #eee9df; color: #2d2925; font-family: Arial, sans-serif; }
        .register-container { min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; padding: 45px; }
        .register-visual { position: relative; overflow: hidden; min-height: calc(100vh - 90px); border-radius: 14px 0 0 14px; }
        .register-visual img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .register-overlay { position: absolute; inset: 0; padding: 55px; display: flex; flex-direction: column; justify-content: space-between; background: linear-gradient(180deg, rgba(0,0,0,.30), rgba(0,0,0,.10) 45%, rgba(0,0,0,.68)); }
        .register-logo { color: white; font-family: Georgia, serif; font-size: 23px; font-weight: bold; letter-spacing: 4px; }
        .register-logo span { color: #d2b46c; }
        .register-visual-text span { color: #d2b46c; font-size: 10px; font-weight: bold; letter-spacing: 4px; }
        .register-visual-text h1 { color: white; font-family: Georgia, serif; font-size: clamp(42px, 5vw, 65px); font-weight: normal; line-height: 1.05; margin: 15px 0 20px; }
        .register-visual-text p { color: rgba(255,255,255,.85); font-size: 14px; line-height: 1.7; max-width: 400px; }
        .register-form-section { background: #fffdf9; min-height: calc(100vh - 90px); border-radius: 0 14px 14px 0; display: flex; align-items: center; justify-content: center; padding: 45px; }
        .register-box { width: 100%; max-width: 430px; }
        .register-title { color: #b49355; font-size: 10px; font-weight: bold; letter-spacing: 3px; margin-bottom: 12px; }
        .register-box h2 { font-family: Georgia, serif; font-size: 36px; font-weight: normal; color: #292521; margin: 0 0 10px; }
        .register-description { color: #81786e; font-size: 13px; line-height: 1.6; margin-bottom: 28px; }
        .register-input { margin-bottom: 15px; }
        .register-input label { display: block; color: #49423b; font-size: 11px; font-weight: bold; margin-bottom: 7px; }
        .register-input input { width: 100%; height: 47px; box-sizing: border-box; border: 1px solid #ded7cb; border-radius: 5px; background: #faf8f3; padding: 0 14px; outline: none; color: #332e29; font-size: 13px; }
        .register-input input:focus { border-color: #b49355; box-shadow: 0 0 0 3px rgba(180,147,85,.10); }
        .register-input input::placeholder { color: #a39b91; }
        .register-notification { display: none; padding: 11px 13px; margin: 3px 0 15px; border-radius: 4px; font-size: 11px; line-height: 1.5; }
        .register-notification.show { display: block; }
        .register-notification.error { background: #f8e9e5; border-left: 3px solid #b85c4e; color: #8c4035; }
        .register-notification.success { background: #eaf3ea; border-left: 3px solid #648d69; color: #476b4c; }
        .register-button { width: 100%; height: 51px; margin-top: 5px; border: none; border-radius: 5px; background: #292521; color: white; font-size: 10px; font-weight: bold; letter-spacing: 2px; cursor: pointer; transition: .25s; }
        .register-button:hover { background: #b49355; transform: translateY(-1px); }
        .login-link { text-align: center; color: #81786e; font-size: 11px; margin-top: 20px; }
        .login-link a { color: #b49355; font-weight: bold; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
        .register-footer { text-align: center; color: #aaa198; font-size: 9px; margin-top: 35px; }
        @media (max-width: 850px) {
            .register-container { grid-template-columns: 1fr; padding: 0; }
            .register-visual { display: none; }
            .register-form-section { min-height: 100vh; border-radius: 0; padding: 35px 25px; }
        }
    </style>

</head>

<body class="register-page">

    <main class="register-container">

        <section class="register-visual">
            <img src="{{ asset('assets/login-bg.jpg') }}" alt="Lumière Hotel">

            <div class="register-overlay">
                <div class="register-logo"><span>✦</span> LUMIÈRE</div>

                <div class="register-visual-text">
                    <span>JOIN LUMIÈRE</span>
                    <h1>Your stay<br>starts here.</h1>
                    <p>Create your account and discover a more comfortable way to book your stay.</p>
                </div>
            </div>
        </section>

        <section class="register-form-section">

            <div class="register-box">

                <p class="register-title">CREATE ACCOUNT</p>
                <h2>Buat Akun</h2>
                <p class="register-description">Daftarkan akunmu untuk mulai memesan kamar di Lumière Hotel.</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="register-input">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="register-input">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Buat username">
                    </div>

                    <div class="register-input">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email">
                    </div>

                    <div class="register-input">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Buat password">
                    </div>

                    <div class="register-input">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password">
                    </div>

                    @if ($errors->any())
                        <div class="register-notification show error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button type="submit" class="register-button">DAFTAR SEKARANG →</button>

                </form>

                <p class="login-link">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Masuk sekarang</a>
                </p>

                <p class="register-footer">© {{ now()->year }} Lumière Hotel & Resort</p>

            </div>

        </section>

    </main>

</body>

</html>
