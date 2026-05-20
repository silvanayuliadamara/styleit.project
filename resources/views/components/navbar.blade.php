<nav class="navbar navbar-expand-lg public-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="navbar-logo">

            <div>
                <div class="navbar-title">LISA YULI BELTI</div>
                <div class="navbar-subtitle">WEDDING GALLERY DAN MAKEUP ARTIST</div>
            </div>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#publicNavbar"
            aria-controls="publicNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="publicNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Profil
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Layanan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Portofolio
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Pricelist
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <a href="#" class="booking-icon" title="Booking">
                    <i class="bi bi-bag"></i>
                </a>

                @auth
                    <div class="dropdown">
                        <button
                            class="btn login-btn dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <i class="bi bi-person"></i>
                            {{ Auth::user()->name }}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person-circle me-2"></i>
                                    Akun Saya
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-calendar-check me-2"></i>
                                    Booking Saya
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf

                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn login-btn">
                        <i class="bi bi-person"></i>
                        Login / Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
