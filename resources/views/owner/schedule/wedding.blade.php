@extends('layouts.owner', ['title' => 'Kelola Jadwal Wedding & Prewedding — LYB'])

@section('owner_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Kelola Jadwal Wedding & Prewedding</h2>
            <p>Atur ketersediaan slot pagi dan siang untuk hari sakral customer. Kuota dibatasi 2 untuk pagi dan 1 untuk siang harian.</p>
        </div>
    </header>

    {{-- Toolbar --}}
    <div class="lyb-schedule-toolbar">
        {{-- Navigation --}}
        <div class="lyb-calendar-nav">
            <a href="{{ route('owner.schedules.wedding', ['year' => $grid1['prevMonth']->year, 'month' => $grid1['prevMonth']->month]) }}"><i class="bi bi-chevron-left"></i></a>
            <span class="lyb-calendar-nav-label">{{ $grid1['monthName'] }} - {{ $grid2['monthName'] }}</span>
            <a href="{{ route('owner.schedules.wedding', ['year' => $grid1['nextMonth']->year, 'month' => $grid1['nextMonth']->month]) }}"><i class="bi bi-chevron-right"></i></a>
        </div>

        {{-- Legend --}}
        <div class="lyb-schedule-legend">
            <div class="lyb-legend-item">
                <span class="lyb-legend-dot tersedia"></span>
                <span>Slot Tersedia</span>
            </div>
            <div class="lyb-legend-item">
                <span class="lyb-legend-dot penuh"></span>
                <span>Telah Dibooking / Penuh</span>
            </div>
            <div class="lyb-legend-item">
                <span class="lyb-legend-dot diblokir"></span>
                <span>Diblokir Owner</span>
            </div>
            <div class="lyb-legend-item">
                <span class="lyb-legend-dot belum"></span>
                <span>Belum Diset (Default Tersedia)</span>
            </div>
        </div>
    </div>

    {{-- Calendar Grid --}}
    <section class="lyb-admin-section">
        <div class="row g-4">
            @foreach(['grid1', 'grid2'] as $gridName)
            @php
                $gridData = $$gridName;
                $days = $gridData['days'];
                $monthName = $gridData['monthName'];
            @endphp
            <div class="col-12 col-xl-6">
                <h5 class="fw-bold mb-3" style="color: #211313;">{{ $monthName }}</h5>
                <div class="lyb-cal-grid shadow-sm p-3 bg-white" style="border-radius: 20px; border: 1px solid #eadfd6;">
                    <!-- Days of Week Headers -->
                    @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $dayName)
                        <div class="lyb-cal-dayname">{{ $dayName }}</div>
                    @endforeach

                    <!-- Cells -->
                    @foreach($days as $day)
                        @if($day === null)
                            <div class="lyb-cal-cell empty"></div>
                        @else
                            @php
                                $dateStr = $day->toDateString();
                                $isToday = $day->isToday();
                                $isPast = $day->isPast() && !$isToday;

                                // Gather slots info
                                $slotsData = [];
                                foreach(['pagi', 'siang'] as $slot) {
                                    $schedKey = $dateStr . '_' . $slot;
                                    $schedule = $schedules->get($schedKey)?->first();
                                    $booking = $bookings->get($schedKey)?->first();

                                    $status = 'belum';
                                    $label = 'Tersedia (Default)';
                                    if ($booking) {
                                        $status = 'penuh';
                                        $label = 'Booked: ' . ($booking->user->name ?? 'Customer');
                                    } elseif ($schedule) {
                                        $status = $schedule->status;
                                        $label = ($schedule->status == 'diblokir') ? 'Diblokir' : 'Tersedia';
                                    }

                                    $slotsData[$slot] = [
                                        'status' => $schedule ? $schedule->status : 'tersedia',
                                        'kuota' => $schedule ? $schedule->kuota : 1,
                                        'jam_mulai' => $schedule ? $schedule->jam_mulai->format('H:i') : ($slot == 'pagi' ? '06:00' : ($slot == 'siang' ? '12:00' : '17:00')),
                                        'jam_selesai' => $schedule ? $schedule->jam_selesai->format('H:i') : ($slot == 'pagi' ? '11:00' : ($slot == 'siang' ? '16:00' : '21:00')),
                                        'catatan' => $schedule ? $schedule->catatan : '',
                                        'booking_exists' => $booking ? 1 : 0,
                                        'booking_details' => $booking ? $booking->booking_code . ' - ' . ($booking->user->name ?? '') : '',
                                    ];
                                }
                            @endphp

                            <div class="lyb-cal-cell {{ $isToday ? 'today' : '' }} {{ $isPast ? 'past' : '' }}"
                                 onclick="openModal('{{ $dateStr }}', '{{ $day->translatedFormat('d F Y') }}', '{{ $isPast ? 1 : 0 }}')"
                                 id="cell_{{ $dateStr }}"
                                 data-date="{{ $dateStr }}"
                                 data-slots="{{ json_encode($slotsData) }}">

                                <div class="lyb-cal-date">
                                    {{ $day->day }}
                                    @if($isToday)
                                        <span class="lyb-cal-today-dot"></span>
                                    @endif
                                </div>

                                <div class="lyb-cal-slots">
                                    @foreach(['pagi', 'siang'] as $slot)
                                        @php
                                            $sInfo = $slotsData[$slot];
                                            $pillClass = $sInfo['booking_exists'] ? 'penuh' : ($sInfo['status'] == 'diblokir' ? 'diblokir' : ($sInfo['status'] == 'belum' || $sInfo['status'] == 'tersedia' ? 'tersedia' : $sInfo['status']));
                                            $qtyLabel = $sInfo['booking_exists'] ? 'BOOKED' : ($sInfo['status'] == 'diblokir' ? 'BLOK' : 'BUKA');

                                            $titleAttr = '';
                                            if ($sInfo['booking_exists']) {
                                                $titleAttr = 'Booked: ' . $sInfo['booking_details'];
                                            } elseif ($sInfo['catatan']) {
                                                $titleAttr = 'Catatan: ' . $sInfo['catatan'];
                                            }
                                        @endphp
                                        <div class="lyb-slot-pill {{ $pillClass }}" {!! $titleAttr ? 'title="' . e($titleAttr) . '"' : '' !!}>
                                            <span>{{ strtoupper($slot) }}</span>
                                            <span class="lyb-slot-qty">{{ $qtyLabel }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Schedule Configuration Modal --}}
    <div class="modal fade lyb-sched-modal" id="scheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modal-date-title">Set Jadwal Tanggal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Store Form --}}
                <form id="schedule-form" action="{{ route('owner.schedules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="category_type" value="wedding_prewedding">
                    <input type="hidden" name="tanggal" id="form-tanggal">

                    <div class="modal-body">
                        <!-- Alert past date -->
                        <div id="past-date-alert" class="alert alert-secondary py-2 small d-none">
                            <i class="bi bi-exclamation-triangle-fill"></i> Tanggal ini sudah berlalu. Perubahan tidak diizinkan.
                        </div>

                        <div class="d-flex flex-column gap-3">
                            @foreach(['pagi', 'siang', 'sore'] as $slot)
                                <div class="lyb-slot-config" id="config-card-{{ $slot }}">
                                    <div class="lyb-slot-config-head" onclick="toggleActiveSlot('{{ $slot }}')">
                                        <div class="lyb-slot-title">
                                            <i class="bi bi-clock-fill"></i>
                                            <span>Slot {{ ucfirst($slot) }}</span>
                                            <span class="lyb-slot-time" id="time-display-{{ $slot }}">(00:00 - 00:00 WIB)</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span id="badge-booked-{{ $slot }}" class="lyb-slot-booked-badge d-none"><i class="bi bi-lock-fill"></i> Booked</span>
                                            <label class="lyb-sched-switch" id="switch-container-{{ $slot }}" onclick="event.stopPropagation()">
                                                <input type="hidden" name="slots[{{ $slot }}][status]" value="diblokir">
                                                <input type="checkbox" name="slots[{{ $slot }}][status]" id="status-{{ $slot }}" value="tersedia" checked onchange="handleStatusToggle('{{ $slot }}')">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="lyb-slot-config-body mt-3" id="config-body-{{ $slot }}">
                                        <div class="row g-2">
                                            <input type="hidden" name="slots[{{ $slot }}][kuota]" value="1">
                                            <div class="col-6">
                                                <label class="lyb-sched-label">Jam Mulai</label>
                                                <input type="time" name="slots[{{ $slot }}][jam_mulai]" id="jam-mulai-{{ $slot }}" class="lyb-sched-input" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="lyb-sched-label">Jam Selesai</label>
                                                <input type="time" name="slots[{{ $slot }}][jam_selesai]" id="jam-selesai-{{ $slot }}" class="lyb-sched-input" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="lyb-sched-label">Catatan Internal / Keterangan</label>
                                                <input type="text" name="slots[{{ $slot }}][catatan]" id="catatan-{{ $slot }}" class="lyb-sched-input" placeholder="Misal: Siap siaga MUA...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <div>
                            <button type="button" id="btn-reset-default" class="btn btn-outline-danger btn-sm" style="border-radius: 8px;">
                                Reset Jadwal
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" id="btn-quick-block" class="btn btn-warning btn-sm fw-bold text-dark" style="border-radius: 8px;">
                                Blokir Hari Ini
                            </button>
                            <button type="submit" id="btn-submit-save" class="btn btn-dark btn-sm fw-bold" style="border-radius: 8px; background: #211313; border: none;">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Helper Hidden Forms for Quick Action --}}
    <form id="quick-block-form" action="{{ route('owner.schedules.toggleBlock') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="category_type" value="wedding_prewedding">
        <input type="hidden" name="tanggal" id="quick-block-tanggal">
        <input type="hidden" name="action" id="quick-block-action">
    </form>

    <form id="reset-schedule-form" action="{{ route('owner.schedules.destroy') }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
        <input type="hidden" name="category_type" value="wedding_prewedding">
        <input type="hidden" name="tanggal" id="reset-tanggal">
    </form>

    <script>
        let myModal;

        document.addEventListener('DOMContentLoaded', function() {
            myModal = new bootstrap.Modal(document.getElementById('scheduleModal'));

            // Register Reset Action
            document.getElementById('btn-reset-default').addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin menghapus kustomisasi jadwal tanggal ini dan kembali ke status default?')) {
                    const dateVal = document.getElementById('form-tanggal').value;
                    document.getElementById('reset-tanggal').value = dateVal;
                    document.getElementById('reset-schedule-form').submit();
                }
            });

            // Register Quick Block Action
            document.getElementById('btn-quick-block').addEventListener('click', function() {
                const action = this.dataset.action;
                const dateVal = document.getElementById('form-tanggal').value;
                document.getElementById('quick-block-tanggal').value = dateVal;
                document.getElementById('quick-block-action').value = action;
                document.getElementById('quick-block-form').submit();
            });

            // Enable disabled fields on form submission so they are sent to the backend
            document.getElementById('schedule-form').addEventListener('submit', function() {
                this.querySelectorAll('input:disabled, select:disabled').forEach(function(input) {
                    input.disabled = false;
                });
            });
        });

        function toggleActiveSlot(slot) {
            const card = document.getElementById('config-card-' + slot);
            card.classList.toggle('active-slot');
            const body = document.getElementById('config-body-' + slot);
            if (card.classList.contains('active-slot')) {
                body.style.display = 'block';
            } else {
                body.style.display = 'none';
            }
        }

        function handleStatusToggle(slot) {
            const checkbox = document.getElementById('status-' + slot);
            const card = document.getElementById('config-card-' + slot);
            const body = document.getElementById('config-body-' + slot);

            if (checkbox.checked) {
                card.classList.remove('blocked-card');
                card.classList.add('active-slot');
                body.style.display = 'block';
            } else {
                card.classList.add('blocked-card');
                card.classList.remove('active-slot');
                body.style.display = 'none';
            }
        }

        function openModal(dateStr, formattedDate, isPast) {
            document.getElementById('modal-date-title').innerText = 'Jadwal: ' + formattedDate;
            document.getElementById('form-tanggal').value = dateStr;

            // Fetch date data attributes
            const cell = document.getElementById('cell_' + dateStr);
            const slots = JSON.parse(cell.dataset.slots);

            let allBlocked = true;
            let anyBooked = false;

            // Setup slots inside modal
            for (const slot of ['pagi', 'siang']) {
                const sInfo = slots[slot];
                const card = document.getElementById('config-card-' + slot);
                const body = document.getElementById('config-body-' + slot);
                const checkbox = document.getElementById('status-' + slot);
                const switchContainer = document.getElementById('switch-container-' + slot);
                const badgeBooked = document.getElementById('badge-booked-' + slot);

                // Default styles resets
                card.className = 'lyb-slot-config';
                body.style.display = 'none';
                checkbox.disabled = false;
                checkbox.checked = (sInfo.status === 'tersedia');

                document.getElementById('jam-mulai-' + slot).value = sInfo.jam_mulai;
                document.getElementById('jam-selesai-' + slot).value = sInfo.jam_selesai;
                document.getElementById('catatan-' + slot).value = sInfo.catatan;
                document.getElementById('time-display-' + slot).innerText = `(${sInfo.jam_mulai} - ${sInfo.jam_selesai} WIB)`;

                if (sInfo.status === 'tersedia') {
                    allBlocked = false;
                } else {
                    card.classList.add('blocked-card');
                }

                // If booked, lock card
                if (sInfo.booking_exists) {
                    anyBooked = true;
                    card.classList.add('locked-slot');
                    badgeBooked.classList.remove('d-none');

                    // disable inputs
                    document.getElementById('jam-mulai-' + slot).disabled = true;
                    document.getElementById('jam-selesai-' + slot).disabled = true;
                    document.getElementById('catatan-' + slot).disabled = true;
                } else {
                    badgeBooked.classList.add('d-none');

                    // enable inputs
                    document.getElementById('jam-mulai-' + slot).disabled = false;
                    document.getElementById('jam-selesai-' + slot).disabled = false;
                    document.getElementById('catatan-' + slot).disabled = false;
                }

                // Keep switch container visible and enabled for overriding
                switchContainer.classList.remove('d-none');
                checkbox.disabled = false;

                if (sInfo.status === 'tersedia') {
                    card.classList.add('active-slot');
                    body.style.display = 'block';
                } else {
                    card.classList.remove('active-slot');
                    body.style.display = 'none';
                }
            }

            // Quick block button text toggling
            const qBtn = document.getElementById('btn-quick-block');
            if (allBlocked) {
                qBtn.innerText = 'Buka Semua Slot';
                qBtn.className = 'btn btn-success btn-sm fw-bold';
                qBtn.dataset.action = 'unblock';
            } else {
                qBtn.innerText = 'Blokir Hari Ini';
                qBtn.className = 'btn btn-warning btn-sm fw-bold text-dark';
                qBtn.dataset.action = 'block';
            }

            // Disable submissions if date is in past or booked elements are active
            const submitBtn = document.getElementById('btn-submit-save');
            const resetBtn = document.getElementById('btn-reset-default');
            const pastAlert = document.getElementById('past-date-alert');

            if (isPast === '1') {
                submitBtn.disabled = true;
                resetBtn.disabled = true;
                qBtn.disabled = true;
                pastAlert.classList.remove('d-none');

                // disable all checkboxes
                document.querySelectorAll('.lyb-sched-switch input').forEach(el => el.disabled = true);
            } else {
                submitBtn.disabled = false;
                resetBtn.disabled = false;
                qBtn.disabled = false;
                pastAlert.classList.add('d-none');
            }

            myModal.show();
        }
    </script>

    <style>
        .blocked-card {
            background: #f4ede6 !important;
            border-color: #d8c8be !important;
            opacity: 0.75;
        }
        .locked-slot {
            border-color: #fde2e2 !important;
            background: #fff8f8 !important;
        }
        .locked-slot .lyb-slot-title span {
            color: #a03131;
        }
    </style>
@endsection
