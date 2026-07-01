<nav class="navbar navbar-expand-lg public-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="navbar-logo">
            <div>
                <div class="navbar-title">LISA YULI BELTI</div>
                <div class="navbar-subtitle">WEDDING GALLERY DAN MAKEUP ARTIST</div>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
            aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
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
                @auth
                    @php
                        $user = auth()->user();
                        $whatsappSetting = \App\Models\WhatsappSetting::first();
                        $waNumber = $whatsappSetting?->nomor_baju ?: '6283112269289';
                        $waNumberClean = preg_replace('/[^0-9]/', '', $waNumber);
                        if (str_starts_with($waNumberClean, '0')) {
                            $waNumberClean = '62' . substr($waNumberClean, 1);
                        }
                        $whatsappLink = "https://wa.me/" . $waNumberClean;
                    @endphp

                    <div class="dropdown">
                        @php
                            $emailPrefix = explode('@', $user->email)[0];
                        @endphp
                        <button class="user-chip dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span class="user-chip-avatar">
                                {{ strtoupper(substr($emailPrefix, 0, 1)) }}
                            </span>
                            <span class="user-chip-name">
                                {{ $emailPrefix }}
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
                                <a class="dropdown-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard Overview
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                                    <i class="bi bi-bag-heart me-2"></i> Booking Baju
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ $whatsappLink }}" target="_blank">
                                    <i class="bi bi-whatsapp me-2"></i> WhatsApp Baju
                                </a>
                            </li>
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
