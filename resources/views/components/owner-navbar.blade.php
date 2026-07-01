<nav class="navbar navbar-expand-lg public-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="navbar-logo">
            <div>
                <div class="navbar-title">LISA YULI BELTI</div>
                <div class="navbar-subtitle">WEDDING GALLERY DAN MAKEUP ARTIST</div>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ownerNavbar"
            aria-controls="ownerNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="ownerNavbar">
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

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 mt-2" style="max-height: 80vh; overflow-y: auto;">
                            <li class="dropdown-header text-muted small px-3 pt-2 pb-1">
                                {{ $user->email }}
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                                    <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard Owner
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.bookings.*') ? 'active' : '' }}" href="{{ route('owner.bookings.index') }}">
                                    <i class="bi bi-wallet2 me-2"></i> Booking & Transaksi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.laporan') ? 'active' : '' }}" href="{{ route('owner.laporan') }}">
                                    <i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan Keuangan
                                </a>
                            </li>
                            
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li class="dropdown-header text-muted small px-3">Kelola Layanan</li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}" href="{{ route('owner.categories.index') }}">
                                    <i class="bi bi-tags me-2"></i> Kategori Layanan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.packages.*') ? 'active' : '' }}" href="{{ route('owner.packages.index') }}">
                                    <i class="bi bi-box-seam me-2"></i> Paket Layanan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.addons.*') ? 'active' : '' }}" href="{{ route('owner.addons.index') }}">
                                    <i class="bi bi-plus-circle me-2"></i> Kelola Add-on
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li class="dropdown-header text-muted small px-3">Jadwal</li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.schedules.wedding') ? 'active' : '' }}" href="{{ route('owner.schedules.wedding') }}">
                                    <i class="bi bi-calendar-heart me-2"></i> Jadwal Wedding
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.schedules.regular') ? 'active' : '' }}" href="{{ route('owner.schedules.regular') }}">
                                    <i class="bi bi-calendar-event me-2"></i> Jadwal Regular
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.schedules.baju') ? 'active' : '' }}" href="{{ route('owner.schedules.baju') }}">
                                    <i class="bi bi-calendar-check me-2"></i> Jadwal Khusus Baju
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('owner.whatsapp.*') ? 'active' : '' }}" href="{{ route('owner.whatsapp.index') }}">
                                    <i class="bi bi-whatsapp me-2"></i> Pengaturan WhatsApp
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
