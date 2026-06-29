@extends('layouts.app', ['title' => $package->name . ' - Lisa Yuli Belti'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/paket-show.css') }}">
@endpush

@section('content')
<div class="package-detail-page">
@php
    $is2xMakeup = in_array($package->code, ['PKG-MU-2X', 'PKG-WED-SILVER', 'PKG-WED-GOLD', 'PKG-WED-GOLD-L']);
    $is3xMakeup = in_array($package->code, ['PKG-MU-3X', 'PKG-WED-DIAMOND-P', 'PKG-WED-DIAMOND-L']);
@endphp

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
                        <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-100 rounded-4 shadow-sm" style="@if($package->category->slug === 'baju') aspect-ratio: 4/5; @else aspect-ratio: 3/4; @endif object-fit: cover; object-position: center top !important; border: 1px solid #eadfd6;">
                    @else
                        <div class="w-100 rounded-4 d-flex align-items-center justify-content-center bg-light border text-muted" style="@if($package->category->slug === 'baju') aspect-ratio: 4/5; @else aspect-ratio: 3/4; @endif border-color: #eadfd6 !important;">
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
                        @if ($package->is_best_seller)
                            <span class="badge rounded-pill px-2.5 py-1" style="background-color: #fbf8f1; color: var(--lyb-gold); border: 1px solid var(--lyb-gold-border); font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">BEST SELLER</span>
                        @endif
                    </div>
                    <h1 class="fw-bold text-dark" style="font-size: 2.2rem; font-family: Georgia, serif !important;">{{ $package->name }}</h1>
                    @php
                        $points = array_map('trim', explode('+', $package->description));
                    @endphp
                    @if(count($points) > 1)
                        <ul class="text-secondary mt-2 ps-3" style="font-size: 15px; line-height: 1.6; list-style-type: disc;">
                            @foreach($points as $point)
                                <li class="mb-1">{{ $point }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-secondary mt-2" style="font-size: 15px; line-height: 1.6;">{!! nl2br(e($package->description)) !!}</p>
                    @endif
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
                        <h5 class="fw-bold mb-2" style="color: var(--lyb-dark); font-size: 16px;">Termasuk Paket</h5>
                        <ul class="text-secondary ps-4" style="font-size: 14.5px; line-height: 1.8; list-style-type: disc;">
                            @foreach($package->items as $item)
                                <li class="mb-1" style="font-weight: 500;">{{ $item->name }}</li>
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
                            <button type="button" class="btn calendar-nav-btn d-flex align-items-center justify-content-center" id="prevMonthBtn" onclick="navigateCalendar(-1)" disabled>
                                <i class="bi bi-chevron-left" style="font-size: 12px; -webkit-text-stroke: 0.5px;"></i>
                            </button>
                            <button type="button" class="btn calendar-nav-btn d-flex align-items-center justify-content-center" id="nextMonthBtn" onclick="navigateCalendar(1)">
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
                    <div class="calendar-legend-custom">
                        <div class="legend-item">
                            <span class="legend-dot available-dot"></span>
                            <span>Tersedia</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot full-dot"></span>
                            <span>Penuh</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot blocked-dot"></span>
                            <span>Diblokir</span>
                        </div>
                    </div>
                </div>

                @if($is2xMakeup || $is3xMakeup)
                <div class="mb-4" id="bookingDate2Container">
                    <label class="form-label fw-bold mb-2" for="bookingDate2Input" style="color: var(--lyb-dark); font-size: 15px;">Tanggal Acara Kedua (Hari 2) <span class="text-danger">*</span></label>
                    <input type="date" name="booking_date_2" id="bookingDate2Input" value="{{ old('booking_date_2', $editItem['booking_date_2'] ?? '') }}" class="form-control rounded-4 py-2.5 px-3" style="border: 1px solid var(--lyb-gold-border); background: #fffcf8;" required>
                    <small class="text-muted mt-1 d-block" style="font-size: 11px;">Silakan pilih tanggal untuk hari kedua acara Anda.</small>
                </div>
                @endif

                @if($is3xMakeup)
                <div class="mb-4" id="bookingDate3Container">
                    <label class="form-label fw-bold mb-2" for="bookingDate3Input" style="color: var(--lyb-dark); font-size: 15px;">Tanggal Acara Ketiga (Hari 3) <span class="text-danger">*</span></label>
                    <input type="date" name="booking_date_3" id="bookingDate3Input" value="{{ old('booking_date_3', $editItem['booking_date_3'] ?? '') }}" class="form-control rounded-4 py-2.5 px-3" style="border: 1px solid var(--lyb-gold-border); background: #fffcf8;" required>
                    <small class="text-muted mt-1 d-block" style="font-size: 11px;">Silakan pilih tanggal untuk hari ketiga acara Anda.</small>
                </div>
                @endif

                {{-- Dynamic MUA Slot Selection --}}
                <div id="slotSelectionContainer1" class="mb-4 d-none">
                    <label class="form-label fw-bold mb-2" style="color: var(--lyb-dark); font-size: 15px;">Pilih Slot Waktu MUA Hari 1 <span class="text-danger">*</span></label>
                    <div class="d-flex flex-column gap-2" id="slotList1">
                        <!-- Dynamically populated via JS -->
                    </div>
                </div>

                @if($is2xMakeup || $is3xMakeup)
                <div id="slotSelectionContainer2" class="mb-4 d-none">
                    <label class="form-label fw-bold mb-2" style="color: var(--lyb-dark); font-size: 15px;">Pilih Slot Waktu MUA Hari 2 <span class="text-danger">*</span></label>
                    <div class="d-flex flex-column gap-2" id="slotList2">
                        <!-- Dynamically populated via JS -->
                    </div>
                </div>
                @endif

                @if($is3xMakeup)
                <div id="slotSelectionContainer3" class="mb-4 d-none">
                    <label class="form-label fw-bold mb-2" style="color: var(--lyb-dark); font-size: 15px;">Pilih Slot Waktu MUA Hari 3 <span class="text-danger">*</span></label>
                    <div class="d-flex flex-column gap-2" id="slotList3">
                        <!-- Dynamically populated via JS -->
                    </div>
                </div>
                @endif

                @if($package->category->slug !== 'baju' && $package->category->slug !== 'regular')
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
                    @if($package->category->slug === 'regular')
                        <div class="mb-4 p-3 rounded-4" style="background: #fffdf5; border: 1px solid var(--lyb-gold-border); font-size: 13.5px; color: #6f625c;">
                            <i class="bi bi-info-circle-fill text-warning me-1"></i>
                            <strong>Keterangan Softlens:</strong> Layanan ini sudah termasuk <strong>Free Softlens</strong>.
                        </div>
                    @endif
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
                                            @if($addon->id == 1 || strtolower($addon->name) === 'makeup keluarga')
                                                <small class="text-muted" style="font-size: 11px;">Notes: 150k per orang</small>
                                            @endif
                                            @if($addon->id == 4 || strpos(strtolower($addon->name), 'melati') !== false)
                                                <small class="text-muted" style="font-size: 11px;">Keterangan: melati depan belakang</small>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="fw-bold text-gold-dark" style="font-size: 14px;">+Rp{{ number_format($addon->price, 0, ',', '.') }}</span>
                                </label>
                            @endforeach
                        </div>
                        @if($package->category->slug === 'baju')
                            <p class="mt-3 text-secondary" style="font-family: Georgia, serif; font-size: 13.5px; line-height: 1.6; color: #6f625c;">
                                <strong>Notes!</strong><br>
                                Jika di luar daerah yang tertulis di paket, ada tambahan biaya tergantung jauhnya lokasi pemasangan
                            </p>
                        @endif
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
                                Checkout
                            </button>
                        </div>
                    @endif
                    <div class="col-12 mt-3 text-start">
                        <a href="https://wa.me/6281227545591?text=Halo%20admin%20LYB,%20saya%20mau%20tanya%20paket%20{{ urlencode($package->name) }}" target="_blank" class="link-whatsapp-gold">
                            <i class="bi bi-whatsapp" style="font-size: 16px;"></i> Tanya via WhatsApp (Owner Makeup)
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
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
    
    function animateNumber(elementId, targetValue, prefix = 'Rp') {
        const el = document.getElementById(elementId);
        if (!el) return;
        
        let currentValue = parseInt(el.getAttribute('data-value'), 10);
        if (isNaN(currentValue)) {
            const text = el.textContent.replace(/\D/g, '');
            currentValue = parseInt(text, 10) || 0;
        }
        
        if (currentValue === targetValue) {
            el.setAttribute('data-value', targetValue);
            return;
        }
        
        const duration = 400; // 400ms duration
        const start = performance.now();
        
        function update(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            
            // Ease out quad
            const easeProgress = progress * (2 - progress);
            
            const currentValueNow = Math.floor(currentValue + (targetValue - currentValue) * easeProgress);
            
            const formatter = new Intl.NumberFormat('id-ID');
            el.textContent = prefix + formatter.format(currentValueNow);
            
            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                el.setAttribute('data-value', targetValue);
                el.textContent = prefix + formatter.format(targetValue);
            }
        }
        
        requestAnimationFrame(update);
    }

    function updateTotals() {
        const addonTotal = [...document.querySelectorAll('input[name="addons[]"]:checked')].reduce((sum, item) => sum + Number(item.dataset.price), 0);
        const grandTotal = basePrice + addonTotal;
        const sisaTotal = grandTotal - baseDp;
        
        animateNumber('grandTotal', grandTotal, 'Rp');
        animateNumber('sisaTotal', sisaTotal, 'Rp');
    }
    
    // Addon calculation
    document.querySelectorAll('input[name="addons[]"]').forEach((checkbox) => {
        checkbox.addEventListener('change', updateTotals);
    });

    // Run totals once on load to show correct sum if editing/repopulating
    updateTotals();

    // Date change event
    function renderSlots(data, dayNumber = 1) {
        const slotContainer = document.getElementById('slotSelectionContainer' + dayNumber);
        const slotList = document.getElementById('slotList' + dayNumber);
        if (!slotContainer || !slotList) return;

        const inputName = dayNumber === 1 ? 'slot_waktu' : 'slot_waktu_' + dayNumber;
        const editSlotWaktu = dayNumber === 1 
            ? @json($editItem['slot_waktu'] ?? null) 
            : (dayNumber === 2 ? @json($editItem['slot_waktu_2'] ?? null) : @json($editItem['slot_waktu_3'] ?? null));

        const oldSlotWaktu = dayNumber === 1 
            ? '{{ old('slot_waktu') }}' 
            : (dayNumber === 2 ? '{{ old('slot_waktu_2') }}' : '{{ old('slot_waktu_3') }}');

        slotList.innerHTML = '';
        
        // Populate slots
        if (data.slots && Object.keys(data.slots).length > 0) {
            slotContainer.classList.remove('d-none');
            slotContainer.classList.add('animate-slide-fade');
            for (const [key, value] of Object.entries(data.slots)) {
                const item = document.createElement('label');
                item.className = 'd-flex align-items-center justify-content-between p-3 rounded-4 border slot-row-clickable ' + 
                    (value.available ? 'bg-white cursor-pointer' : 'opacity-50 bg-body-tertiary cursor-not-allowed');
                
                const leftPart = document.createElement('div');
                leftPart.className = 'd-flex align-items-center gap-3';

                const radioInput = document.createElement('input');
                radioInput.type = 'radio';
                radioInput.name = inputName;
                radioInput.value = key;
                radioInput.id = 'slot_' + dayNumber + '_' + key;
                radioInput.className = 'form-check-input flex-shrink-0';
                radioInput.style.width = '20px';
                radioInput.style.height = '20px';
                radioInput.style.minWidth = '20px';
                radioInput.style.minHeight = '20px';
                radioInput.style.maxWidth = '20px';
                radioInput.style.maxHeight = '20px';
                radioInput.required = true;
                if (!value.available) {
                    radioInput.disabled = true;
                }
                
                // Check if this slot is selected
                if (key === editSlotWaktu || key === oldSlotWaktu) {
                    radioInput.checked = true;
                }
                
                const label = document.createElement('div');
                label.className = 'd-flex flex-column';
                
                const labelTitle = document.createElement('span');
                labelTitle.className = 'fw-bold text-dark';
                labelTitle.style.fontSize = '14px';
                labelTitle.innerText = value.label;
                label.appendChild(labelTitle);
                
                if (value.available && typeof value.remaining !== 'undefined') {
                    const remainingSpan = document.createElement('span');
                    remainingSpan.className = 'text-muted small';
                    remainingSpan.style.fontSize = '11px';
                    remainingSpan.innerText = `Sisa kuota: ${value.remaining} slot`;
                    label.appendChild(remainingSpan);
                }
                
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
            slotContainer.classList.remove('animate-slide-fade');
        }
        
        // Fitting date logic
        if (dayNumber === 1) {
            const fittingContainer = document.getElementById('fittingDateContainer');
            const fittingInput = document.getElementById('tanggalFittingInput');
            const editTanggalFitting = @json($editItem['tanggal_fitting'] ?? null);

            if (data.needs_fitting) {
                fittingContainer.classList.remove('d-none');
                fittingContainer.classList.add('animate-slide-fade');
                fittingInput.required = true;
                
                fittingInput.removeAttribute('min');
                
                const eventDate = new Date(data.date || document.querySelector('input[name="booking_date"]:checked')?.value);
                if (!isNaN(eventDate.getTime())) {
                    eventDate.setDate(eventDate.getDate() - 1);
                    const maxDateStr = eventDate.toISOString().split('T')[0];
                    fittingInput.max = maxDateStr;
                }
                
                if (editTanggalFitting) {
                    fittingInput.value = editTanggalFitting;
                } else if ('{{ old('tanggal_fitting') }}') {
                    fittingInput.value = '{{ old('tanggal_fitting') }}';
                }
            } else {
                fittingContainer.classList.add('d-none');
                fittingContainer.classList.remove('animate-slide-fade');
                fittingInput.required = false;
                fittingInput.value = '';
            }
        }
    }

    async function loadSlotsForDay(dayNumber) {
        let dateVal = '';
        if (dayNumber === 1) {
            const selectedRadio = document.querySelector('input[name="booking_date"]:checked');
            dateVal = selectedRadio ? selectedRadio.value : '';
        } else if (dayNumber === 2) {
            dateVal = document.getElementById('bookingDate2Input')?.value || '';
        } else if (dayNumber === 3) {
            dateVal = document.getElementById('bookingDate3Input')?.value || '';
        }

        const slotContainer = document.getElementById('slotSelectionContainer' + dayNumber);
        if (!dateVal) {
            if (slotContainer) slotContainer.classList.add('d-none');
            return;
        }

        // Show loading spinner immediately for instant response
        const slotList = document.getElementById('slotList' + dayNumber);
        if (slotContainer && slotList) {
            slotContainer.classList.remove('d-none');
            slotList.innerHTML = '<div class="text-secondary small p-2"><i class="bi bi-arrow-repeat spinner-icon"></i> Memuat slot MUA...</div>';
        }

        try {
            const res = await fetch(`/paket/{{ $package->code }}/slots?date=${dateVal}`);
            const data = await res.json();
            renderSlots(data, dayNumber);
        } catch (err) {
            console.error(err);
            const slotList = document.getElementById('slotList' + dayNumber);
            if (slotList) {
                slotList.innerHTML = '<div class="text-danger small p-2">Gagal mengambil jadwal slot.</div>';
            }
        }
    }

    // Register event listeners
    document.querySelectorAll('input[name="booking_date"]').forEach((radio) => {
        radio.addEventListener('change', () => loadSlotsForDay(1));
    });

    const date2Input = document.getElementById('bookingDate2Input');
    const date3Input = document.getElementById('bookingDate3Input');

    if (date2Input) {
        date2Input.addEventListener('change', () => loadSlotsForDay(2));
        
        // Prevent selecting a date before the primary date
        document.querySelectorAll('input[name="booking_date"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                if (radio.checked) {
                    date2Input.min = radio.value;
                    if (date2Input.value && date2Input.value < radio.value) {
                        date2Input.value = '';
                        loadSlotsForDay(2);
                    }
                }
            });
        });
    }

    if (date3Input) {
        date3Input.addEventListener('change', () => loadSlotsForDay(3));
        
        // Prevent selecting a date before the second date
        if (date2Input) {
            date2Input.addEventListener('change', () => {
                date3Input.min = date2Input.value;
                if (date3Input.value && date3Input.value < date2Input.value) {
                    date3Input.value = '';
                    loadSlotsForDay(3);
                }
            });
        }
    }

    // Trigger on load if radio already selected (e.g. edit/old)
    const selectedRadioOnLoad = document.querySelector('input[name="booking_date"]:checked');
    if (selectedRadioOnLoad) {
        // Run sequentially to prevent concurrent request deadlock
        (async () => {
            await loadSlotsForDay(1);
            if (date2Input) {
                date2Input.min = selectedRadioOnLoad.value;
                if (date2Input.value) {
                    await loadSlotsForDay(2);
                }
            }
            if (date2Input && date2Input.value && date3Input) {
                date3Input.min = date2Input.value;
                if (date3Input.value) {
                    await loadSlotsForDay(3);
                }
            }
        })();
    }

    // Frontend validation form submit handler
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            @if($is3xMakeup)
            const dateVal1 = document.querySelector('input[name="booking_date"]:checked')?.value;
            const dateVal2 = document.getElementById('bookingDate2Input')?.value;
            const dateVal3 = document.getElementById('bookingDate3Input')?.value;
            if (!dateVal1 || !dateVal2 || !dateVal3) {
                e.preventDefault();
                alert('Silakan pilih tanggal booking utama, tanggal kedua, dan tanggal ketiga.');
                return false;
            }
            @elseif($is2xMakeup)
            const dateVal1 = document.querySelector('input[name="booking_date"]:checked')?.value;
            const dateVal2 = document.getElementById('bookingDate2Input')?.value;
            if (!dateVal1 || !dateVal2) {
                e.preventDefault();
                alert('Silakan pilih tanggal booking utama dan tanggal acara kedua.');
                return false;
            }
            @else
            const selectedDate = document.querySelector('input[name="booking_date"]:checked');
            if (!selectedDate) {
                e.preventDefault();
                alert('Silakan pilih tanggal booking terlebih dahulu pada kalender.');
                return false;
            }
            @endif
            const slotContainer1 = document.getElementById('slotSelectionContainer1');
            if (slotContainer1 && !slotContainer1.classList.contains('d-none')) {
                const selectedSlot = document.querySelector('input[name="slot_waktu"]:checked');
                if (!selectedSlot) {
                    e.preventDefault();
                    alert('Silakan pilih slot waktu MUA Hari 1.');
                    return false;
                }
            }

            const slotContainer2 = document.getElementById('slotSelectionContainer2');
            if (slotContainer2 && !slotContainer2.classList.contains('d-none')) {
                const selectedSlot2 = document.querySelector('input[name="slot_waktu_2"]:checked');
                if (!selectedSlot2) {
                    e.preventDefault();
                    alert('Silakan pilih slot waktu MUA Hari 2.');
                    return false;
                }
            }

            const slotContainer3 = document.getElementById('slotSelectionContainer3');
            if (slotContainer3 && !slotContainer3.classList.contains('d-none')) {
                const selectedSlot3 = document.querySelector('input[name="slot_waktu_3"]:checked');
                if (!selectedSlot3) {
                    e.preventDefault();
                    alert('Silakan pilih slot waktu MUA Hari 3.');
                    return false;
                }
            }
            
            @if($package->category->slug !== 'baju' && $package->category->slug !== 'regular')
            const selectedSoftlens = document.querySelector('input[name="softlens"]:checked');
            if (!selectedSoftlens) {
                e.preventDefault();
                alert('Silakan pilih apakah Anda menggunakan softlens atau tidak.');
                return false;
            }
            @endif

            const fittingContainer = document.getElementById('fittingDateContainer');
            const fittingInput = document.getElementById('tanggalFittingInput');
            if (fittingContainer && !fittingContainer.classList.contains('d-none') && fittingInput && fittingInput.required) {
                if (!fittingInput.value) {
                    e.preventDefault();
                    alert('Silakan pilih tanggal fitting pakaian terlebih dahulu.');
                    return false;
                }
            }
        });
    }
});
</script>
@endsection
