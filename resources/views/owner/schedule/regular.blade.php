@extends('layouts.owner', ['title' => 'Kelola Jadwal Regular — LYB'])

@section('owner_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Kelola Jadwal Regular</h2>
            <p>Atur ketersediaan slot dan kuota per slot (pagi, siang, sore) untuk wisuda, lamaran, dll. Slot otomatis diblokir jika terdapat booking Wedding/Prewedding di hari & jam yang sama.</p>
        </div>
    </header>

    {{-- Toolbar --}}
    <div class="lyb-schedule-toolbar">
        {{-- Navigation --}}
        <div class="lyb-calendar-nav">
            <a href="{{ route('owner.schedules.regular', ['year' => $grid1['prevMonth']->year, 'month' => $grid1['prevMonth']->month]) }}"><i class="bi bi-chevron-left"></i></a>
            <span class="lyb-calendar-nav-label">{{ $grid1['monthName'] }} - {{ $grid2['monthName'] }}</span>
            <a href="{{ route('owner.schedules.regular', ['year' => $grid1['nextMonth']->year, 'month' => $grid1['nextMonth']->month]) }}"><i class="bi bi-chevron-right"></i></a>
        </div>

        {{-- Legend --}}
        <div class="lyb-schedule-legend">
            <div class="lyb-legend-item">
                <span class="lyb-legend-dot tersedia"></span>
                <span>Tersedia / Buka</span>
            </div>
            <div class="lyb-legend-item">
                <span class="lyb-legend-dot penuh"></span>
                <span>Penuh (Kuota Habis)</span>
            </div>
            <div class="lyb-legend-item">
                <span class="lyb-legend-dot diblokir"></span>
                <span>Diblokir Owner</span>
            </div>
            <div class="lyb-legend-item">
                <span class="lyb-legend-dot wedding"></span>
                <span>Diblokir Wedding/Prewedding</span>
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
                    <!-- Days Headers -->
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

                                $slotsData = [];
                                foreach(['pagi', 'siang', 'sore'] as $slot) {
                                    $schedKey = $dateStr . '_' . $slot;
                                    $schedule = $schedules->get($schedKey)?->first();

                                    // Bookings count for regular
                                    $regBookings = $bookings->get($schedKey) ?? collect([]);
                                    $bookedCount = $regBookings->count();

                                    // Check if blocked by wedding/prewedding booking
                                    $wedBook = $weddingBookings->get($schedKey)?->first();

                                    $status = 'belum';
                                    $kuota = $schedule ? $schedule->kuota : 3; // default regular kuota is 3

                                    if ($wedBook) {
                                        $status = 'wedding_blocked';
                                    } elseif ($schedule) {
                                        $status = $schedule->status;
                                        if ($status == 'tersedia' && $bookedCount >= $kuota) {
                                            $status = 'penuh';
                                        }
                                    } else {
                                        $status = 'diblokir'; // default is blocked/closed from the start
                                    }

                                    $slotsData[$slot] = [
                                        'status' => $status,
                                        'db_status' => $schedule ? $schedule->status : 'diblokir',
                                        'kuota' => $kuota,
                                        'booked_count' => $bookedCount,
                                        'jam_mulai' => $schedule ? $schedule->jam_mulai->format('H:i') : ($slot == 'pagi' ? '06:00' : ($slot == 'siang' ? '12:00' : '17:00')),
                                        'jam_selesai' => $schedule ? $schedule->jam_selesai->format('H:i') : ($slot == 'pagi' ? '11:00' : ($slot == 'siang' ? '16:00' : '21:00')),
                                        'catatan' => $schedule ? $schedule->catatan : '',
                                        'wedding_blocked' => $wedBook ? 1 : 0,
                                        'wedding_booking_details' => $wedBook ? $wedBook->booking_code . ' - ' . ($wedBook->user->name ?? '') : '',
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
                                    @foreach(['pagi', 'siang', 'sore'] as $slot)
                                        @php
                                            $sInfo = $slotsData[$slot];
                                            $pillClass = $sInfo['wedding_blocked'] ? 'wedding-block' : ($sInfo['status'] == 'penuh' ? 'penuh' : ($sInfo['status'] == 'diblokir' ? 'diblokir' : 'tersedia'));

                                            if ($sInfo['wedding_blocked']) {
                                                $qtyLabel = 'BLOCKED MUA';
                                            } elseif ($sInfo['status'] == 'diblokir') {
                                                $qtyLabel = 'BLOK';
                                            } else {
                                                $qtyLabel = $sInfo['booked_count'] . '/' . $sInfo['kuota'];
                                            }

                                            $titleAttr = '';
                                            if ($sInfo['wedding_blocked']) {
                                                $titleAttr = 'Terblokir Booking Wedding: ' . $sInfo['wedding_booking_details'];
                                            } elseif ($sInfo['status'] == 'penuh') {
                                                $titleAttr = 'Kuota penuh (' . $sInfo['booked_count'] . '/' . $sInfo['kuota'] . ')';
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

    {{-- Schedule Modal --}}
    <div class="modal fade lyb-sched-modal" id="scheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modal-date-title">Set Jadwal Regular</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Form --}}
                <form id="schedule-form" action="{{ route('owner.schedules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="category_type" value="regular">
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
                                            <span id="badge-wedding-block-{{ $slot }}" class="lyb-slot-wedding-badge d-none"><i class="bi bi-shield-fill-exclamation"></i> Terblokir MUA Wedding</span>
                                            <span id="badge-booked-{{ $slot }}" class="lyb-slot-booked-badge d-none">Booked (<span id="booked-count-text-{{ $slot }}">0</span>)</span>
                                            <label class="lyb-sched-switch" id="switch-container-{{ $slot }}" onclick="event.stopPropagation()">
                                                <input type="hidden" name="slots[{{ $slot }}][status]" value="diblokir">
                                                <input type="checkbox" name="slots[{{ $slot }}][status]" id="status-{{ $slot }}" value="tersedia" checked onchange="handleStatusToggle('{{ $slot }}')">
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="lyb-slot-config-body mt-3" id="config-body-{{ $slot }}">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <label class="lyb-sched-label">Kuota Slot Customer <span class="text-danger">*</span></label>
                                                <input type="number" name="slots[{{ $slot }}][kuota]" id="kuota-{{ $slot }}" class="lyb-sched-input" min="1" value="3" required>
                                            </div>
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
                                                <input type="text" name="slots[{{ $slot }}][catatan]" id="catatan-{{ $slot }}" class="lyb-sched-input" placeholder="Keterangan hari MUA senggang...">
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
        <input type="hidden" name="category_type" value="regular">
        <input type="hidden" name="tanggal" id="quick-block-tanggal">
        <input type="hidden" name="action" id="quick-block-action">
    </form>

    <form id="reset-schedule-form" action="{{ route('owner.schedules.destroy') }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
        <input type="hidden" name="category_type" value="regular">
        <input type="hidden" name="tanggal" id="reset-tanggal">
    </form>

    <script>
        let myModal;

        document.addEventListener('DOMContentLoaded', function() {
            myModal = new bootstrap.Modal(document.getElementById('scheduleModal'));

            // Register Reset Action
            document.getElementById('btn-reset-default').addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin menghapus kustomisasi jadwal Regular tanggal ini?')) {
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
            document.getElementById('modal-date-title').innerText = 'Jadwal Regular: ' + formattedDate;
            document.getElementById('form-tanggal').value = dateStr;

            const cell = document.getElementById('cell_' + dateStr);
            const slots = JSON.parse(cell.dataset.slots);

            let allBlocked = true;

            for (const slot of ['pagi', 'siang', 'sore']) {
                const sInfo = slots[slot];
                const card = document.getElementById('config-card-' + slot);
                const body = document.getElementById('config-body-' + slot);
                const checkbox = document.getElementById('status-' + slot);
                const switchContainer = document.getElementById('switch-container-' + slot);
                const badgeWeddingBlock = document.getElementById('badge-wedding-block-' + slot);
                const badgeBooked = document.getElementById('badge-booked-' + slot);
                const bookedCountText = document.getElementById('booked-count-text-' + slot);

                // Reset
                card.className = 'lyb-slot-config';
                body.style.display = 'none';
                checkbox.disabled = false;
                checkbox.checked = (sInfo.db_status !== 'diblokir');
                badgeWeddingBlock.classList.add('d-none');
                badgeBooked.classList.add('d-none');

                document.getElementById('kuota-' + slot).value = sInfo.kuota;
                document.getElementById('jam-mulai-' + slot).value = sInfo.jam_mulai;
                document.getElementById('jam-selesai-' + slot).value = sInfo.jam_selesai;
                document.getElementById('catatan-' + slot).value = sInfo.catatan;
                document.getElementById('time-display-' + slot).innerText = `(${sInfo.jam_mulai} - ${sInfo.jam_selesai} WIB)`;

                if (sInfo.db_status === 'diblokir') {
                    card.classList.add('blocked-card');
                } else {
                    allBlocked = false;
                }

                // Apply locking for Wedding Block
                if (sInfo.wedding_blocked) {
                    card.classList.add('wedding-locked-slot');
                    badgeWeddingBlock.classList.remove('d-none');
                }

                // If booked, display count
                if (sInfo.booked_count > 0) {
                    badgeBooked.classList.remove('d-none');
                    bookedCountText.innerText = sInfo.booked_count + '/' + sInfo.kuota;
                }

                // Keep switch container visible and enabled for overriding
                switchContainer.classList.remove('d-none');
                checkbox.disabled = false;

                if (sInfo.db_status !== 'diblokir') {
                    card.classList.add('active-slot');
                    body.style.display = 'block';
                } else {
                    card.classList.remove('active-slot');
                    body.style.display = 'none';
                }
            }

            // Quick block buttons
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

            // Disable for past date
            const submitBtn = document.getElementById('btn-submit-save');
            const resetBtn = document.getElementById('btn-reset-default');
            const pastAlert = document.getElementById('past-date-alert');

            if (isPast === '1') {
                submitBtn.disabled = true;
                resetBtn.disabled = true;
                qBtn.disabled = true;
                pastAlert.classList.remove('d-none');
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
        .wedding-locked-slot {
            border-color: #ffebcc !important;
            background: #fffdf5 !important;
        }
        .wedding-locked-slot .lyb-slot-title span {
            color: #896414;
        }
        .locked-slot {
            border-color: #fde2e2 !important;
            background: #fff8f8 !important;
        }
    </style>
@endsection
