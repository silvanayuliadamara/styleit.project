<nav class="navbar navbar-expand-lg public-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="navbar-logo">
            <div>
                <div class="navbar-title">LISA YULI BELTI</div>
                <div class="navbar-subtitle">WEDDING GALLERY DAN MAKEUP ARTIST</div>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('profil') ? 'active' : '' }}" href="{{ route('profil') }}">Profil</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('layanan.*') || request()->routeIs('paket.show') ? 'active' : '' }}" href="{{ route('layanan.index') }}">Layanan</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('portofolio') ? 'active' : '' }}" href="{{ route('portofolio') }}">Portofolio</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('pricelist') ? 'active' : '' }}" href="{{ route('pricelist') }}">Pricelist</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <a href="{{ route('customer.cart.index') }}" class="booking-icon position-relative" title="Keranjang Booking">
                    <i class="bi bi-bag"></i>
                    @if(count(session('cart', [])) > 0)
                        <span class="cart-badge">{{ count(session('cart', [])) }}</span>
                    @endif
                </a>

                <div class="dropdown">
                    <button class="btn login-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person"></i> Preview Customer
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                        <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('customer.bookings.index') }}"><i class="bi bi-calendar-check me-2"></i>Booking Saya</a></li>
                        <li><a class="dropdown-item" href="{{ route('customer.cart.index') }}"><i class="bi bi-bag me-2"></i>Keranjang</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Login asli</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
