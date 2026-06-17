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
                    <a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}"
                        href="{{ route('owner.dashboard') }}">Dashboard Owner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('owner.bookings.*') ? 'active' : '' }}"
                        href="{{ route('owner.bookings.index') }}">Booking & Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('owner.laporan') ? 'active' : '' }}"
                        href="{{ route('owner.laporan') }}">Laporan Keuangan</a>
                </li>

                {{-- Dropdown Kelola Layanan (menyimpan menu-menu lainnya) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('owner.categories.*', 'owner.packages.*', 'owner.addons.*', 'owner.schedules.*', 'owner.whatsapp.*') ? 'active' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Kelola Layanan
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm rounded-4 mt-2">
                        <li><h6 class="dropdown-header">Layanan & Paket</h6></li>
                        <li><a class="dropdown-item" href="{{ route('owner.categories.index') }}">Kategori Layanan</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.packages.index') }}">Paket Layanan</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.addons.index') }}">Kelola Add-on</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">Jadwal</h6></li>
                        <li><a class="dropdown-item" href="{{ route('owner.schedules.wedding') }}">Jadwal Wedding</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.schedules.regular') }}">Jadwal Regular</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.schedules.baju') }}">Jadwal Khusus Baju</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('owner.whatsapp.index') }}">Pengaturan WhatsApp</a></li>
                    </ul>
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

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 mt-2">
                            <li class="dropdown-header text-muted small px-3 pt-2 pb-1">
                                {{ $user->email }}
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
