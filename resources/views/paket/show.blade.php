@extends('layouts.app', ['title' => $package->name . ' - Lisa Yuli Belti'])

@section('content')
<style>
    /* Premium LYB Styling Sesuai Screenshot */
    :root {
        --lyb-gold: #b08a42;
        --lyb-gold-light: #fbf8f1;
        --lyb-gold-border: #eadfd6;
        --lyb-dark: #211313;
    }

    body {
        background-color: #FAF6F0 !important;
    }

    h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
        font-family: Georgia, "Times New Roman", serif !important;
        color: var(--lyb-dark) !important;
    }

    /* Back Link styling */
    .back-link {
        transition: all 0.3s ease;
    }
    .back-link:hover {
        color: var(--lyb-gold) !important;
        transform: translateX(-4px);
    }

    /* Cover Image sticky styling */
    .cover-image-sticky {
        top: 100px;
        transition: all 0.3s ease;
    }

    /* Wedding gallery thumbnails */
    .thumbnail-container {
        border: 2px solid transparent;
        opacity: 0.6;
        transition: all 0.3s ease;
    }
    .thumbnail-container:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    .thumbnail-container.active {
        border-color: var(--lyb-gold) !important;
        opacity: 1 !important;
        box-shadow: 0 4px 10px rgba(176, 138, 66, 0.2);
    }

    /* Month Calendar Grid Styles */
    .calendar-month-card {
        background: #fff;
        border: 1px solid var(--lyb-gold-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(33, 19, 19, 0.02);
        height: 100%;
        transition: all 0.3s ease;
    }
    .calendar-month-card:hover {
        box-shadow: 0 15px 35px rgba(33, 19, 19, 0.05);
    }
    .calendar-grid-7 {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        text-align: center;
        align-items: center;
    }
    .calendar-day-header {
        font-size: 11px;
        font-weight: 700;
        color: #a3958e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .calendar-day-cell {
        aspect-ratio: 1;
        width: 100%;
        max-width: 40px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 13px;
        font-weight: 600;
        position: relative;
        user-select: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .calendar-day-cell .day-number {
        position: relative;
        z-index: 2;
    }
    /* Available day */
    .calendar-day-cell.available {
        border: 1px solid #c2e7d0;
        background-color: #eafaf1;
        color: #1a7a42;
        cursor: pointer;
    }
    .calendar-day-cell.available:hover {
        background-color: #1a7a42;
        color: #fff;
        border-color: #1a7a42;
        box-shadow: 0 4px 10px rgba(26, 122, 66, 0.15);
        transform: scale(1.05);
    }
    /* When selected (radio checked) */
    .calendar-day-cell.available:has(input:checked) {
        background-color: var(--lyb-gold) !important;
        color: #fff !important;
        border-color: var(--lyb-gold) !important;
        box-shadow: 0 4px 12px rgba(176, 138, 66, 0.4);
        transform: scale(1.1);
    }
    /* Full day */
    .calendar-day-cell.full {
        border: 1px solid #f9d6d6;
        background-color: #fdf2f2;
        color: #dc3545;
        cursor: not-allowed;
    }
    .calendar-day-cell.full:hover {
        transform: none;
    }
    /* Blocked day */
    .calendar-day-cell.blocked {
        border: 1px solid var(--lyb-gold-border);
        background-color: #f6f3f0;
        color: #a3958e;
        cursor: not-allowed;
        text-decoration: line-through;
    }
    .calendar-day-cell.blocked:hover {
        transform: none;
    }
    /* Empty cell */
    .calendar-day-cell.empty {
        background: transparent;
        border: none;
    }
    /* Disabled cell (not in 60-day range) */
    .calendar-day-cell.disabled {
        color: #ccc;
        background: transparent;
        border: none;
        cursor: not-allowed;
    }

    /* Interactive Radio & Checkbox Containers (Capsule shape) */
    .addon-row-clickable, .slot-row-clickable {
        border: 1px solid var(--lyb-gold-border) !important;
        background-color: #fff;
        border-radius: 50px !important;
        padding: 16px 28px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .addon-row-clickable::after, .slot-row-clickable::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle, rgba(176, 138, 66, 0.05) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    .addon-row-clickable:hover::after, .slot-row-clickable:hover::after {
        opacity: 1;
    }
    .addon-row-clickable:hover, .slot-row-clickable:hover {
        background-color: #fffdf9 !important;
        border-color: var(--lyb-gold) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(176, 138, 66, 0.08);
    }
    .addon-row-clickable:has(input:checked), .slot-row-clickable:has(input:checked) {
        background-color: var(--lyb-gold-light) !important;
        border-color: var(--lyb-gold) !important;
        box-shadow: 0 6px 18px rgba(176, 138, 66, 0.12);
        transform: translateY(-1px);
    }
    
    .addon-row-clickable input[type="checkbox"], 
    .slot-row-clickable input[type="radio"] {
        border-color: var(--lyb-gold-border) !important;
        transition: all 0.2s ease;
    }
    
    /* Make custom styling for radios/checkboxes inside active elements */
    .addon-row-clickable:has(input:checked) input[type="checkbox"], 
    .slot-row-clickable:has(input:checked) input[type="radio"] {
        background-color: var(--lyb-gold) !important;
        border-color: var(--lyb-gold) !important;
    }

    /* Softlens Pills layout side-by-side as screenshot */
    .softlens-container {
        display: flex;
        gap: 16px;
    }
    .softlens-pill {
        flex: 1;
        background: #fff;
        border: 1px solid var(--lyb-gold-border);
        border-radius: 50px;
        padding: 14px 28px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s ease;
        user-select: none;
    }
    .softlens-pill:hover {
        border-color: var(--lyb-gold);
        background-color: var(--lyb-gold-light);
    }
    .softlens-pill input[type="radio"] {
        width: 20px;
        height: 20px;
        border-color: var(--lyb-gold-border);
        margin-right: 12px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .softlens-pill:has(input:checked) {
        border-color: var(--lyb-gold) !important;
        background-color: var(--lyb-gold-light) !important;
        box-shadow: 0 4px 12px rgba(176, 138, 66, 0.08);
    }
    .softlens-pill:has(input:checked) input[type="radio"] {
        background-color: var(--lyb-gold) !important;
        border-color: var(--lyb-gold) !important;
    }

    /* Price Info Box / Summary Box */
    .premium-card {
        background-color: #fdfaf6;
        border: 1px solid var(--lyb-gold-border) !important;
        border-radius: 20px;
        padding: 24px;
        transition: all 0.3s ease;
    }
    .premium-card:hover {
        box-shadow: 0 8px 25px rgba(203, 180, 159, 0.12);
    }

    .summary-card {
        background-color: #fff;
        border: 1px solid var(--lyb-gold-border) !important;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(33, 19, 19, 0.01);
    }

    /* Action Buttons */
    .btn-lyb-outline {
        border: 2px solid var(--lyb-dark);
        color: var(--lyb-dark);
        background-color: #fffdfc;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-lyb-outline:hover {
        background-color: var(--lyb-gold-light);
        border-color: var(--lyb-gold);
        color: var(--lyb-gold);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(176, 138, 66, 0.1);
    }
    .btn-lyb-dark {
        background-color: var(--lyb-dark);
        border: 2px solid var(--lyb-dark);
        color: #fff;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-lyb-dark:hover {
        background-color: #3d2525;
        border-color: #3d2525;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(33, 19, 19, 0.25);
    }
    .link-whatsapp-gold {
        color: var(--lyb-gold) !important;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .link-whatsapp-gold:hover {
        color: var(--lyb-dark) !important;
        transform: translateY(-1px);
    }
</style>

<div class="container py-5">
    {{-- Back link --}}
    <a href="{{ route('layanan.kategori', $package->category->slug) }}" class="text-gold-dark fw-bold mb-4 d-inline-block text-decoration-none back-link">
        <i class="bi bi-arrow-left"></i> Kembali ke {{ $package->category->name }}
    </a>

    <form action="{{ route('customer.cart.store') }}" method="POST" id="bookingForm">
        @csrf
        <input type="hidden" name="package_id" value="{{ $package->id }}">
        @if(isset($editItem))
            <input type="hidden" name="edit_key" value="{{ $editItem['key'] }}">
        @endif

        <div class="row g-5">
            {{-- Kolom Kiri: Cover Image --}}
            <div class="col-lg-5">
                <div class="position-sticky cover-image-sticky">
                    @if ($package->image)
                        <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-100 rounded-4 shadow-sm" style="aspect-ratio: 3/4; object-fit: cover; border: 1px solid #eadfd6;">
                    @else
                        <div class="w-100 rounded-4 d-flex align-items-center justify-content-center bg-light border text-muted" style="aspect-ratio: 3/4; border-color: #eadfd6 !important;">
                            <i class="bi bi-image" style="font-size: 48px;"></i>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Rincian & Kalender --}}
            <div class="col-lg-7">
                {{-- Package Information --}}
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2 text-uppercase" style="letter-spacing: 1.5px; font-size: 12px;">
                        <span class="text-gold-dark fw-bold">{{ $package->category->name }}</span>
                        @if ($package->is_popular || $package->category->slug === 'wedding')
                            <span class="badge rounded-pill px-2.5 py-1" style="background-color: #fbf8f1; color: var(--lyb-gold); border: 1px solid var(--lyb-gold-border); font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">BEST SELLER</span>
                        @endif
                    </div>
                    <h1 class="fw-bold text-dark" style="font-size: 2.2rem; font-family: Georgia, serif !important;">{{ $package->name }}</h1>
                    <p class="text-secondary mt-2" style="font-size: 15px; line-height: 1.6;">{{ $package->description }}</p>
                </div>

                {{-- Price Info Box --}}
                <div class="premium-card mb-4" style="background-color: #fdfaf6; border: 1px solid var(--lyb-gold-border) !important;">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-secondary fw-semibold d-block mb-1" style="font-size: 14px;">Harga Paket</span>
                            <span class="text-secondary small d-block" style="font-size: 12px;">DP Saat Checkout</span>
                        </div>
                        <div class="col-6 text-end">
                            <span class="fs-2 fw-bold text-dark d-block" style="font-family: Georgia, serif !important; line-height: 1;">Rp{{ number_format($package->price, 0, ',', '.') }}</span>
                            <span class="fw-bold d-block mt-1" style="color: var(--lyb-gold); font-size: 14px;">Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="text-muted small mt-3 pt-3 border-top" style="font-size: 11px; line-height: 1.5; border-top-color: var(--lyb-gold-border) !important;">
                        <i class="bi bi-info-circle me-1"></i> DP dibayarkan saat checkout. Sisa pelunasan dibayarkan setelah layanan selesai atau sesuai ketentuan owner.
                    </div>
                </div>

                {{-- Includes --}}
                @if($package->items->isNotEmpty())
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3" style="color: var(--lyb-dark); font-size: 16px;">Termasuk Paket</h5>
                        <ul class="list-unstyled d-flex flex-wrap gap-x-4 gap-y-2">
                            @foreach($package->items as $item)
                                <li class="small text-secondary me-3 d-flex align-items-center" style="font-weight: 500;">
                                    <i class="bi bi-sparkles me-2" style="color: var(--lyb-gold);"></i>{{ $item->name }} {{ $item->quantity > 1 ? $item->quantity : '' }} {{ $item->unit }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Datepicker Calendar Grid --}}
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: var(--lyb-dark); font-size: 16px;">
                            <i class="bi bi-calendar3" style="color: var(--lyb-gold); font-size: 16px;"></i> Pilih Tanggal Booking
                        </h5>
                        <div class="calendar-nav-buttons d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-dark rounded-circle d-flex align-items-center justify-content-center" id="prevMonthBtn" style="width: 32px; height: 32px; border-color: var(--lyb-gold-border); color: var(--lyb-dark);" onclick="navigateCalendar(-1)" disabled>
                                <i class="bi bi-chevron-left" style="font-size: 12px; -webkit-text-stroke: 0.5px;"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-dark rounded-circle d-flex align-items-center justify-content-center" id="nextMonthBtn" style="width: 32px; height: 32px; border-color: var(--lyb-gold-border); color: var(--lyb-dark);" onclick="navigateCalendar(1)">
                                <i class="bi bi-chevron-right" style="font-size: 12px; -webkit-text-stroke: 0.5px;"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-muted small mb-3">Tampilan 2 bulan ke depan.</p>

                    @php
                        $calendarByDate = [];
                        foreach ($calendar as $dateItem) {
                            $calendarByDate[$dateItem['date']] = $dateItem;
                        }

                        $groupedMonths = [];
                        foreach ($calendar as $dateItem) {
                            $carbonDate = \Illuminate\Support\Carbon::parse($dateItem['date']);
                            $monthKey = $carbonDate->format('Y-m'); // e.g. "2026-06"
                            if (!isset($groupedMonths[$monthKey])) {
                                $groupedMonths[$monthKey] = [
                                    'name' => $carbonDate->translatedFormat('F Y'), // e.g. "Juni 2026"
                                    'year' => $carbonDate->year,
                                    'month' => $carbonDate->month,
                                ];
                            }
                        }
                    @endphp

                    <div class="row g-4 justify-content-center" id="calendarMonthsWrapper">
                        @foreach ($groupedMonths as $monthKey => $monthInfo)
                            <div class="col-md-6 calendar-month-col" data-month-index="{{ $loop->index }}">
                                <div class="calendar-month-card">
                                    <h6 class="fw-bold text-center mb-3" style="color: #211313;">{{ $monthInfo['name'] }}</h6>
                                    <div class="calendar-grid-7">
                                        <!-- Day names header -->
                                        @foreach (['M', 'S', 'S', 'R', 'K', 'J', 'S'] as $dayName)
                                            <div class="calendar-day-header text-center py-1">{{ $dayName }}</div>
                                        @endforeach

                                        @php
                                            $startOfWeek = \Illuminate\Support\Carbon::create($monthInfo['year'], $monthInfo['month'], 1)->dayOfWeek; // 0 (Sun) to 6 (Sat)
                                            $daysInMonth = \Illuminate\Support\Carbon::create($monthInfo['year'], $monthInfo['month'], 1)->daysInMonth;
                                        @endphp

                                        <!-- Empty cells before start of month -->
                                        @for ($i = 0; $i < $startOfWeek; $i++)
                                            <div class="calendar-day-cell empty"></div>
                                        @endfor

                                        <!-- Days of month -->
                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $currentDateStr = sprintf('%04d-%02d-%02d', $monthInfo['year'], $monthInfo['month'], $day);
                                                $dateData = $calendarByDate[$currentDateStr] ?? null;
                                            @endphp

                                            @if ($dateData)
                                                <label class="calendar-day-cell {{ $dateData['status'] }}" title="{{ $dateData['label'] }}">
                                                    <input type="radio" name="booking_date" value="{{ $currentDateStr }}" {{ $dateData['status'] !== 'available' ? 'disabled' : '' }} {{ old('booking_date', $editItem['booking_date'] ?? '') === $currentDateStr ? 'checked' : '' }} class="d-none">
                                                    <span class="day-number">{{ $day }}</span>
                                                </label>
                                            @else
                                                <div class="calendar-day-cell disabled">
                                                    <span class="day-number">{{ $day }}</span>
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Calendar Legend --}}
                    <div class="calendar-legend d-flex justify-content-center gap-4 mt-4 flex-wrap" style="font-size: 12px; font-weight: 500;">
                        <span class="d-flex align-items-center gap-2 text-secondary"><span style="width: 10px; height: 10px; border-radius: 50%; background-color: #1a7a42; display: inline-block; box-shadow: 0 0 6px rgba(26, 122, 66, 0.4);"></span> Tersedia</span>
                        <span class="d-flex align-items-center gap-2 text-secondary"><span style="width: 10px; height: 10px; border-radius: 50%; background-color: #dc3545; display: inline-block; box-shadow: 0 0 6px rgba(220, 53, 69, 0.4);"></span> Penuh</span>
                        <span class="d-flex align-items-center gap-2 text-secondary"><span style="width: 10px; height: 10px; border-radius: 50%; background-color: #a3958e; display: inline-block; box-shadow: 0 0 6px rgba(163, 149, 142, 0.4);"></span> Diblokir</span>
                    </div>
                </div>

                {{-- Dynamic MUA Slot Selection --}}
                <div id="slotSelectionContainer" class="mb-4 d-none">
                    <label class="form-label fw-bold mb-2" style="color: var(--lyb-dark); font-size: 15px;">Pilih Slot Waktu MUA <span class="text-danger">*</span></label>
                    <div class="d-flex flex-column gap-2" id="slotList">
                        <!-- Dynamically populated via JS -->
                    </div>
                </div>

                @if($package->category->slug !== 'baju')
                <div class="mb-4">
                    <label class="form-label fw-bold" style="color: var(--lyb-dark); font-size: 15px;">Penggunaan Softlens <span class="text-danger">*</span></label>
                    <div class="softlens-container">
                        <label class="softlens-pill">
                            <input type="radio" name="softlens" value="1" required {{ old('softlens', isset($editItem) ? (int)$editItem['softlens'] : null) === 1 ? 'checked' : '' }} class="form-check-input">
                            <span class="fw-semibold text-dark" style="font-size: 14px;">Ya</span>
                        </label>
                        <label class="softlens-pill">
                            <input type="radio" name="softlens" value="0" required {{ old('softlens', isset($editItem) ? (int)$editItem['softlens'] : null) === 0 ? 'checked' : '' }} class="form-check-input">
                            <span class="fw-semibold text-dark" style="font-size: 14px;">Tidak</span>
                        </label>
                    </div>
                </div>
                @else
                    <input type="hidden" name="softlens" value="0">
                @endif

                {{-- Dynamic Fitting Date Selection --}}
                <div id="fittingDateContainer" class="mb-4 d-none">
                    <label class="form-label fw-bold mb-2" for="tanggalFittingInput" style="color: var(--lyb-dark); font-size: 15px;">Tanggal Fitting ke Gallery <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_fitting" id="tanggalFittingInput" class="form-control rounded-4 py-2.5 px-3" style="border: 1px solid var(--lyb-gold-border); background: #fffcf8;">
                    <div class="mt-2 p-3 rounded-4" style="background: #fffdf5; border: 1px solid var(--lyb-gold-border); font-size: 11px; color: #6f625c; line-height: 1.6;">
                        <i class="bi bi-info-circle-fill text-warning me-1"></i>
                        <strong>Informasi Penting:</strong> Pemilihan detail baju dilakukan saat fitting ke gallery LYB. Pelanggan yang datang fitting <strong>lebih awal</strong> mendapat prioritas memilih baju terlebih dahulu. Silakan datang ke gallery sesuai tanggal yang dipilih.
                    </div>
                </div>

                        @if($addons->isNotEmpty())
                        <h5 class="fw-bold mb-3" style="color: var(--lyb-dark); font-size: 15px;">Add-on Opsional</h5>
                        <div class="d-flex flex-column gap-2">
                            @foreach($addons as $addon)
                                @php
                                    $isEditAddon = isset($editItem) && collect($editItem['addons'])->contains('id', $addon->id);
                                @endphp
                                <label class="d-flex align-items-center justify-content-between addon-row-clickable" style="cursor: pointer; transition: all 0.2s ease;">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="checkbox" name="addons[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}" {{ (old('addons') ? in_array($addon->id, old('addons')) : $isEditAddon) ? 'checked' : '' }} class="form-check-input flex-shrink-0" style="width: 20px; height: 20px;">
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark" style="font-size: 14px;">{{ $addon->name }}</span>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-gold-dark" style="font-size: 14px;">+Rp{{ number_format($addon->price, 0, ',', '.') }}</span>
                                </label>
                            @endforeach
                        </div>
                        @endif

                {{-- Summary Calculation Card --}}
                <div class="summary-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary" style="font-size: 14px;">Total Harga Layanan</span>
                        <strong class="fs-5 text-dark" id="grandTotal" style="font-family: Georgia, serif !important;">Rp{{ number_format($package->price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary" style="font-size: 14px;">DP Dibayar Saat Checkout</span>
                        <strong class="text-gold-dark" id="dpTotal" style="color: var(--lyb-gold) !important; font-size: 14px;">Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary" style="font-size: 14px;">Estimasi Sisa Pelunasan</span>
                        <strong class="fs-5 text-dark" id="sisaTotal" style="font-family: Georgia, serif !important;">Rp{{ number_format($package->price - $package->dp_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="row g-3">
                    @if(isset($editItem))
                        <div class="col-12">
                            <button type="submit" name="action" value="cart" class="btn btn-lyb-dark w-100 py-3 rounded-pill">
                                Simpan Perubahan
                            </button>
                        </div>
                    @else
                        <div class="col-sm-6">
                            <button type="submit" name="action" value="cart" class="btn btn-lyb-outline w-100 py-3 rounded-pill">
                                Tambah Keranjang
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" name="action" value="checkout" class="btn btn-lyb-dark w-100 py-3 rounded-pill">
                                Booking Sekarang
                            </button>
                        </div>
                    @endif
                    <div class="col-12 mt-3 text-start">
                        <a href="https://wa.me/6281227545591?text=Halo%20admin%20LYB,%20saya%20mau%20tanya%20paket%20{{ urlencode($package->name) }}" target="_blank" class="link-whatsapp-gold">
                            <i class="bi bi-whatsapp" style="font-size: 16px;"></i> Tanya via WhatsApp (Owner Makeup)
                        </a>
                    </div>
                </div>

                <p class="text-muted text-center mt-3 mb-0" style="font-size: 11px;">
                    Mode preview: booking hanya tersimpan sementara di session, belum masuk database.
                </p>
            </div>
        </div>
    </form>
</div>

<script>
let currentMonthIndex = 0;

function updateCalendarVisibility() {
    const cols = document.querySelectorAll('.calendar-month-col');
    const totalMonths = cols.length;
    
    cols.forEach((col) => {
        const idx = parseInt(col.getAttribute('data-month-index'), 10);
        if (idx === currentMonthIndex || idx === currentMonthIndex + 1) {
            col.classList.remove('d-none');
        } else {
            col.classList.add('d-none');
        }
    });
    
    const prevBtn = document.getElementById('prevMonthBtn');
    const nextBtn = document.getElementById('nextMonthBtn');
    
    if (prevBtn) {
        prevBtn.disabled = (currentMonthIndex === 0);
    }
    if (nextBtn) {
        nextBtn.disabled = (currentMonthIndex + 2 >= totalMonths);
    }
}

function navigateCalendar(direction) {
    const cols = document.querySelectorAll('.calendar-month-col');
    const totalMonths = cols.length;
    
    const newIdx = currentMonthIndex + direction;
    if (newIdx >= 0 && newIdx + 1 < totalMonths) {
        currentMonthIndex = newIdx;
        updateCalendarVisibility();
    }
}



document.addEventListener('DOMContentLoaded', () => {
    updateCalendarVisibility();
    const basePrice = {{ $package->price }};
    const baseDp = {{ $package->dp_amount }};
    const editSlotWaktu = @json($editItem['slot_waktu'] ?? null);
    const editTanggalFitting = @json($editItem['tanggal_fitting'] ?? null);
    
    function updateTotals() {
        const addonTotal = [...document.querySelectorAll('input[name="addons[]"]:checked')].reduce((sum, item) => sum + Number(item.dataset.price), 0);
        const grandTotal = basePrice + addonTotal;
        const sisaTotal = grandTotal - baseDp;
        
        const formatter = new Intl.NumberFormat('id-ID');
        document.getElementById('grandTotal').textContent = 'Rp' + formatter.format(grandTotal);
        document.getElementById('sisaTotal').textContent = 'Rp' + formatter.format(sisaTotal);
    }
    
    // Addon calculation
    document.querySelectorAll('input[name="addons[]"]').forEach((checkbox) => {
        checkbox.addEventListener('change', updateTotals);
    });

    // Run totals once on load to show correct sum if editing/repopulating
    updateTotals();

    // Date change event
    document.querySelectorAll('input[name="booking_date"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            const dateVal = document.querySelector('input[name="booking_date"]:checked').value;
            
            // Show loading state or clear
            const slotContainer = document.getElementById('slotSelectionContainer');
            const slotList = document.getElementById('slotList');
            slotList.innerHTML = '<div class="text-muted small p-2"><i class="bi bi-arrow-clockwise spinner-icon"></i> Mencari slot tersedia...</div>';
            slotContainer.classList.remove('d-none');

            fetch(`/paket/{{ $package->code }}/slots?date=${dateVal}`)
                .then(r => r.json())
                .then(data => {
                    slotList.innerHTML = '';
                    
                    // Populate slots
                    if (data.slots && Object.keys(data.slots).length > 0) {
                        slotContainer.classList.remove('d-none');
                        for (const [key, value] of Object.entries(data.slots)) {
                            const item = document.createElement('label');
                            item.className = 'd-flex align-items-center justify-content-between p-3 rounded-4 border slot-row-clickable ' + 
                                (value.available ? 'bg-white cursor-pointer' : 'opacity-50 bg-body-tertiary cursor-not-allowed');
                            
                            const leftPart = document.createElement('div');
                            leftPart.className = 'd-flex align-items-center gap-3';

                            const radioInput = document.createElement('input');
                            radioInput.type = 'radio';
                            radioInput.name = 'slot_waktu';
                            radioInput.value = key;
                            radioInput.id = 'slot_' + key;
                            radioInput.className = 'form-check-input flex-shrink-0';
                            radioInput.style.width = '20px';
                            radioInput.style.height = '20px';
                            radioInput.required = true;
                            if (!value.available) {
                                radioInput.disabled = true;
                            }
                            
                            // Check if this slot is selected
                            if (key === editSlotWaktu || key === '{{ old('slot_waktu') }}') {
                                radioInput.checked = true;
                            }
                            
                            const label = document.createElement('div');
                            label.className = 'd-flex flex-column';
                            
                            const labelTitle = document.createElement('span');
                            labelTitle.className = 'fw-bold text-dark';
                            labelTitle.style.fontSize = '14px';
                            labelTitle.innerText = value.label;
                            label.appendChild(labelTitle);
                            
                            if (!value.available) {
                                const reason = document.createElement('span');
                                reason.className = 'text-danger small';
                                reason.style.fontSize = '11px';
                                reason.innerText = value.reason;
                                label.appendChild(reason);
                            }
                            
                            leftPart.appendChild(radioInput);
                            leftPart.appendChild(label);
                            item.appendChild(leftPart);
                            slotList.appendChild(item);
                        }
                    } else {
                        slotContainer.classList.add('d-none');
                    }
                    
                    // Fitting date logic
                    const fittingContainer = document.getElementById('fittingDateContainer');
                    const fittingInput = document.getElementById('tanggalFittingInput');
                    if (data.needs_fitting) {
                        fittingContainer.classList.remove('d-none');
                        fittingInput.required = true;
                        
                        // No minimum date constraint (all dates before booking date are available)
                        fittingInput.removeAttribute('min');
                        
                        // Max date is event_date - 1 day
                        const eventDate = new Date(dateVal);
                        eventDate.setDate(eventDate.getDate() - 1);
                        
                        const maxDateStr = eventDate.toISOString().split('T')[0];
                        fittingInput.max = maxDateStr;
                        
                        if (editTanggalFitting) {
                            fittingInput.value = editTanggalFitting;
                        } else if ('{{ old('tanggal_fitting') }}') {
                            fittingInput.value = '{{ old('tanggal_fitting') }}';
                        }
                    } else {
                        fittingContainer.classList.add('d-none');
                        fittingInput.required = false;
                        fittingInput.value = '';
                    }
                })
                .catch(err => {
                    console.error(err);
                    slotList.innerHTML = '<div class="text-danger small p-2">Gagal mengambil jadwal slot.</div>';
                });
        });
    });

    // Trigger change event for already checked booking date on page load (e.g., from old input or edit data)
    const selectedRadio = document.querySelector('input[name="booking_date"]:checked');
    if (selectedRadio) {
        selectedRadio.dispatchEvent(new Event('change'));
    }

    // Frontend validation form submit handler
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            const selectedDate = document.querySelector('input[name="booking_date"]:checked');
            if (!selectedDate) {
                e.preventDefault();
                alert('Silakan pilih tanggal booking terlebih dahulu pada kalender.');
                return false;
            }
            
            const slotContainer = document.getElementById('slotSelectionContainer');
            if (slotContainer && !slotContainer.classList.contains('d-none')) {
                const selectedSlot = document.querySelector('input[name="slot_waktu"]:checked');
                if (!selectedSlot) {
                    e.preventDefault();
                    alert('Silakan pilih slot waktu MUA (Pagi, Siang, atau Sore).');
                    return false;
                }
            }
            
            @if($package->category->slug !== 'baju')
            const selectedSoftlens = document.querySelector('input[name="softlens"]:checked');
            if (!selectedSoftlens) {
                e.preventDefault();
                alert('Silakan pilih apakah Anda menggunakan softlens atau tidak.');
                return false;
            }
            @endif

            const fittingContainer = document.getElementById('fittingDateContainer');
            if (fittingContainer && !fittingContainer.classList.contains('d-none')) {
                const fittingInput = document.getElementById('tanggalFittingInput');
                if (!fittingInput.value) {
                    e.preventDefault();
                    alert('Silakan pilih tanggal fitting ke gallery.');
                    return false;
                }
            }
        });
    }
});
</script>
@endsection
