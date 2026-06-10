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
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('profil') ? 'active' : '' }}"
                        href="{{ route('profil') }}">Profil</a></li>
                <li class="nav-item"><a
                        class="nav-link {{ request()->routeIs('layanan.*') || request()->routeIs('paket.show') ? 'active' : '' }}"
                        href="{{ route('layanan.index') }}">Layanan</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('portofolio') ? 'active' : '' }}"
                        href="{{ route('portofolio') }}">Portofolio</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('pricelist') ? 'active' : '' }}"
                        href="{{ route('pricelist') }}">Pricelist</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <a href="{{ route('customer.cart.index') }}" class="booking-icon position-relative"
                    title="Keranjang Booking">
                    <i class="bi bi-bag"></i>
                    @if (count(session('cart', [])) > 0)
                        <span class="cart-badge">{{ count(session('cart', [])) }}</span>
                    @endif
                </a>

                @guest
                    <a href="{{ route('login') }}" class="btn login-btn">
                        <i class="bi bi-person"></i>
                        Login
                    </a>
                @endguest

                @auth
                    @php
                        $user = auth()->user();

                        if ($user->role === 'owner') {
                            $userRoute = route('owner.dashboard');
                        } elseif ($user->role === 'admin') {
                            $userRoute = route('admin.dashboard');
                        } else {
                            $userRoute = route('customer.dashboard');
                        }
                    @endphp

                    <a href="{{ $userRoute }}" class="user-chip {{ request()->url() === $userRoute ? 'active' : '' }}">
                        <span class="user-chip-avatar">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                        <span class="user-chip-name">
                            {{ $user->name }}
                        </span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
