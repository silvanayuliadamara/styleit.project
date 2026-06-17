<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin — LYB' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner.css') }}">
</head>

<body class="lyb-admin-body">
    <div class="lyb-admin-shell">
        <aside class="lyb-admin-sidebar">
            <div>
                <div class="lyb-admin-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="LYB Logo">
                    <div>
                        <h1>LISA YULI<br>BELTI</h1>
                        <p>Wedding Gallery dan Makeup Artist</p>
                    </div>
                </div>
                <p class="lyb-admin-label">DASHBOARD ADMIN</p>
                <nav class="lyb-admin-nav">
                    <a href="{{ route('admin.dashboard') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span><i class="bi bi-grid-1x2-fill"></i> Dashboard Overview</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <span><i class="bi bi-bag-heart"></i> Booking Baju</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </nav>
            </div>
            <div class="lyb-admin-sidebar-footer">
                <div class="lyb-admin-profile">
                    <strong>{{ Auth::user()->name ?? 'Admin Baju' }}</strong>
                    <span>Admin</span>
                </div>
                <a href="{{ route('home') }}" class="lyb-admin-footer-link"><i class="bi bi-house-door"></i> Ke Halaman
                    Publik</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="lyb-admin-footer-link lyb-admin-logout"><i
                            class="bi bi-box-arrow-left"></i> Keluar</button>
                </form>
            </div>
        </aside>
        <main class="lyb-admin-main">
            <div class="lyb-admin-content">
                @yield('admin_content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
