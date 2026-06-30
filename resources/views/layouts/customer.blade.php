<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Customer — LYB' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner.css') }}">
    <style>
        body, table, input, button, select, textarea, h1, h2, h3, h4, h5, h6, p, span, a, strong {
            font-family: 'Outfit', sans-serif !important;
        }
        .lyb-admin-sidebar {
            background: #1c0e0e !important;
            border-right: 1px solid rgba(176, 138, 66, 0.1);
        }
        .lyb-admin-brand h1 {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700;
        }
        .lyb-admin-nav-link:hover, .lyb-admin-nav-link.active {
            background: #2b1717 !important;
            box-shadow: inset 4px 0 0 #b08a42 !important;
        }
        .lyb-admin-profile {
            background: #251313 !important;
        }
    </style>
    @stack('styles')
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
                        <span><i class="bi bi-person-badge"></i> Profil & Riwayat</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="{{ route('customer.profile.edit') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('customer.profile.edit') ? 'active' : '' }}">
                        <span><i class="bi bi-person-gear"></i> Ubah Profil</span>
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
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('customer_content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
