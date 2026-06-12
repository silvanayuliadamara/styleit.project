<nav class="navbar navbar-expand-lg public-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="navbar-logo">
            <div>
                <div class="navbar-title">LISA YULI BELTI</div>
                <div class="navbar-subtitle">WEDDING GALLERY DAN MAKEUP ARTIST</div>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar"
            aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profil') ? 'active' : '' }}"
                        href="{{ route('profil') }}">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('layanan.*') || request()->routeIs('paket.show') ? 'active' : '' }}"
                        href="{{ route('layanan.index') }}">Layanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('portofolio') ? 'active' : '' }}"
                        href="{{ route('portofolio') }}">Portofolio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pricelist') ? 'active' : '' }}"
                        href="{{ route('pricelist') }}">Pricelist</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3 flex-wrap">

                {{-- Ikon keranjang hanya untuk customer yang sudah login --}}
                @auth
                    @if (auth()->user()->hasRole('customer'))
                        <a href="{{ route('customer.cart.index') }}" class="booking-icon position-relative"
                            title="Keranjang Booking">
                            <i class="bi bi-bag"></i>
                            @if (count(session('cart', [])) > 0)
                                <span class="cart-badge">{{ count(session('cart', [])) }}</span>
                            @endif
                        </a>
                    @endif
                @endauth

                {{-- Tombol Login/Register untuk tamu --}}
                @guest
                    <a href="{{ route('login') }}" class="btn login-btn">
                        <i class="bi bi-person me-1"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn register-btn">
                        Daftar
                    </a>
                @endguest

                {{-- Dropdown user untuk yang sudah login --}}
                @auth
                    @php
                        $user = auth()->user();

                        if ($user->hasRole('owner')) {
                            $dashboardRoute = route('owner.dashboard');
                            $dashboardLabel = 'Dashboard Owner';
                        } elseif ($user->hasRole('admin')) {
                            $dashboardRoute = route('admin.dashboard');
                            $dashboardLabel = 'Dashboard Admin';
                        } else {
                            $dashboardRoute = route('customer.dashboard');
                            $dashboardLabel = 'Dashboard Saya';
                        }
                    @endphp

                    <div class="dropdown">
                        <button class="user-chip dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span class="user-chip-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <span class="user-chip-name">
                                {{ $user->name }}
                            </span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 mt-2">
                            <li class="dropdown-header text-muted small px-3 pt-2 pb-1">
                                {{ $user->email }}
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ $dashboardRoute }}">
                                    <i class="bi bi-grid me-2"></i> {{ $dashboardLabel }}
                                </a>
                            </li>

                            {{-- Menu khusus customer --}}
                            @if ($user->hasRole('customer'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.bookings.index') }}">
                                        <i class="bi bi-calendar-check me-2"></i> Riwayat Booking
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.cart.index') }}">
                                        <i class="bi bi-bag me-2"></i> Keranjang
                                    </a>
                                </li>
                            @endif

                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth

            </div>
        </div>
    </div>
</nav>
