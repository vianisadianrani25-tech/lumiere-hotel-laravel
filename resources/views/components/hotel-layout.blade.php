@props(['active' => null, 'bodyClass' => ''])
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière Hotel | {{ $title ?? 'Home' }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    {{ $head ?? '' }}

</head>

<body class="{{ $bodyClass }}">

<header class="hotel-navbar">

    <a href="{{ route('home') }}" class="hotel-logo">
        <span>✦</span>
        LUMIÈRE
        <small>HOTEL & RESORT</small>
    </a>

    <nav>
        <a href="{{ route('home') }}" @class(['active' => $active === 'home'])>Home</a>
        <a href="{{ route('home') }}#rooms" @class(['active' => $active === 'rooms'])>Rooms</a>
        <a href="{{ route('home') }}#facilities">Facilities</a>
        <a href="{{ route('home') }}#about">About</a>
        <a href="{{ route('my-bookings.index') }}" @class(['active' => $active === 'my-bookings'])>My Bookings</a>
        <a href="{{ route('laporan-pemesanan.index') }}" @class(['active' => $active === 'laporan-pemesanan'])>Laporan Pemesanan</a>
        <a href="{{ route('rooms.index') }}" @class(['active' => $active === 'kelola-kamar'])>Kelola Kamar</a>
    </nav>

    <div class="hotel-user">

        <div class="user-avatar">✦</div>

        <div class="user-name-box">
            <small>Welcome</small>
            <strong>{{ auth()->user()->name }}</strong>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="hotel-logout">Logout</button>
        </form>

    </div>

</header>

{{ $slot }}

<footer class="hotel-footer">

    <div>
        <div class="footer-logo">✦ LUMIÈRE</div>
        <p>Hotel & Resort</p>
    </div>

    <div>
        <strong>EXPLORE</strong>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('home') }}#rooms">Rooms</a>
        <a href="{{ route('home') }}#facilities">Facilities</a>
    </div>

    <div>
        <strong>CONTACT</strong>
        <p>Medan, Indonesia</p>
        <p>+62 812 3456 7890</p>
    </div>

    <div>
        <strong>LUMIÈRE</strong>
        <p>© {{ now()->year }} Lumière Hotel & Resort</p>
    </div>

</footer>

{{ $scripts ?? '' }}

</body>

</html>
