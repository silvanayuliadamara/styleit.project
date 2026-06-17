<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Owner — LYB' }}</title>
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
                <p class="lyb-admin-label">DASHBOARD OWNER</p>
                <nav class="lyb-admin-nav">
                    {{-- Dashboard --}}
                    <a href="{{ route('owner.dashboard') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <span><i class="bi bi-grid-1x2-fill"></i> Dashboard Overview</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    {{-- Booking & Transaksi --}}
                    <a href="{{ route('owner.bookings.index') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.bookings.*') ? 'active' : '' }}">
                        <span><i class="bi bi-receipt"></i> Booking & Transaksi</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    {{-- Laporan Keuangan --}}
                    <a href="{{ route('owner.laporan') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.laporan') ? 'active' : '' }}">
                        <span><i class="bi bi-bar-chart-line-fill"></i> Laporan Keuangan</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    {{-- Jadwal Wedding --}}
                    <a href="{{ route('owner.schedules.wedding') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.schedules.wedding') ? 'active' : '' }}">
                        <span><i class="bi bi-calendar2-week-fill"></i> Jadwal Wedding & Prewedding</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    {{-- Jadwal Regular --}}
                    <a href="{{ route('owner.schedules.regular') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.schedules.regular') ? 'active' : '' }}">
                        <span><i class="bi bi-calendar2-week-fill"></i> Jadwal Regular</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    {{-- Jadwal Baju --}}
                    <a href="{{ route('owner.schedules.baju') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.schedules.baju') ? 'active' : '' }}">
                        <span><i class="bi bi-scissors"></i> Jadwal Khusus Baju</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    {{-- Kategori Layanan --}}
                    <a href="{{ route('owner.categories.index') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                        <span><i class="bi bi-tags-fill"></i> Kategori Layanan</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    {{-- Paket Layanan --}}
                    <a href="{{ route('owner.packages.index') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.packages.*') ? 'active' : '' }}">
                        <span><i class="bi bi-box-seam-fill"></i> Paket Layanan</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    {{-- Kelola Add-on --}}
                    <a href="{{ route('owner.addons.index') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.addons.*') ? 'active' : '' }}">
                        <span><i class="bi bi-plus-circle-fill"></i> Kelola Add-on</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>

                    {{-- WhatsApp Settings --}}
                    <a href="{{ route('owner.whatsapp.index') }}"
                        class="lyb-admin-nav-link {{ request()->routeIs('owner.whatsapp.*') ? 'active' : '' }}">
                        <span><i class="bi bi-whatsapp"></i> Pengaturan WhatsApp</span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </nav>
            </div>
            <div class="lyb-admin-sidebar-footer">
                <div class="lyb-admin-profile">
                    <strong>{{ explode('@', Auth::user()->email)[0] }}</strong>
                    <span>Owner</span>
                </div>
                <a href="{{ route('home') }}" class="lyb-admin-footer-link"><i class="bi bi-house-door"></i> Ke Halaman Publik</a>
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
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('owner_content')
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.lyb-nav-group-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const parent = this.closest('.lyb-nav-group');
                    parent.classList.toggle('open');
                });
            });
        });
    </script>
</body>

</html>
