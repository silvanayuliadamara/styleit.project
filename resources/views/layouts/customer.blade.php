<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Customer — LYB' }}</title>
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
                <p class="lyb-admin-label">DASHBOARD CUSTOMER</p>
                <nav class="lyb-admin-nav">
                    <a href="{{ route('customer.dashboard') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <span><i class="bi bi-person-fill"></i> Profil & Riwayat</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </nav>
            </div>
            <div class="lyb-admin-sidebar-footer">
                <div class="lyb-admin-profile">
                    <strong>{{ Auth::user()->name ?? 'Customer' }}</strong>
                    <span>Customer</span>
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
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('customer_content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
