@extends('layouts.owner', ['title' => 'Kelola Jadwal Booking Baju — LYB'])

@section('owner_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Kelola Jadwal Booking Baju</h2>
            <p>Atur kuota booking baju harian. Jumlah booking dibatasi sesuai kuota per tanggal.</p>
        </div>
    </header>

    {{-- Toolbar --}}
    <div class="lyb-schedule-toolbar">
        {{-- Navigation --}}
        <div class="lyb-calendar-nav">
            <a href="{{ route('owner.schedules.baju', ['year' => $grid1['prevMonth']->year, 'month' => $grid1['prevMonth']->month]) }}"><i class="bi bi-chevron-left"></i></a>
            <span class="lyb-calendar-nav-label">{{ $grid1['monthName'] }} - {{ $grid2['monthName'] }}</span>
            <a href="{{ route('owner.schedules.baju', ['year' => $grid1['nextMonth']->year, 'month' => $grid1['nextMonth']->month]) }}"><i class="bi bi-chevron-right"></i></a>
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

                                // Schedules for Baju on this date
                                $bajuSchedObj = $schedules->get($dateStr)?->first();

                                // Bookings count on this date based on tanggal_fitting
                                $bajuBookings = $bookings->get($dateStr) ?? collect([]);
                                $bookedCount = $bajuBookings->count();

                                $quota = $bajuSchedObj ? $bajuSchedObj->kuota : 5; // default baju kuota is 5
                                $status = $bajuSchedObj ? $bajuSchedObj->status : 'tersedia';

                                if ($status === 'tersedia' && $bookedCount >= $quota) {
                                    $status = 'penuh';
                                }

                                $slotsData = [
                                    'baju' => [
                                        'status' => $status,
                                        'kuota' => $quota,
                                        'booked_count' => $bookedCount,
                                        'jam_mulai' => $bajuSchedObj ? $bajuSchedObj->jam_mulai->format('H:i') : '08:00',
                                        'jam_selesai' => $bajuSchedObj ? $bajuSchedObj->jam_selesai->format('H:i') : '17:00',
                                        'catatan' => $bajuSchedObj ? $bajuSchedObj->catatan : '',
                                    ]
                                ];

                                $pillClass = ($status == 'penuh' ? 'penuh' : ($status == 'diblokir' ? 'diblokir' : 'tersedia'));
                                $qtyLabel = ($status == 'diblokir' ? 'BLOK' : $bookedCount . '/' . $quota);
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
                                    <div class="lyb-slot-pill {{ $pillClass }}">
                                        <span>BAJU</span>
                                        <span class="lyb-slot-qty">{{ $qtyLabel }}</span>
                                    </div>
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
                    <h5 class="modal-title fw-bold" id="modal-date-title">Set Jadwal Booking Baju</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Form --}}
                <form id="schedule-form" action="{{ route('owner.schedules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="category_type" value="baju">
                    <input type="hidden" name="tanggal" id="form-tanggal">

                    <div class="modal-body">
                        <!-- Alert past date -->
                        <div id="past-date-alert" class="alert alert-secondary py-2 small d-none">
                            <i class="bi bi-exclamation-triangle-fill"></i> Tanggal ini sudah berlalu. Perubahan tidak diizinkan.
                        </div>

                        <div class="d-flex flex-column gap-3">
                            <div class="lyb-slot-config active-slot" id="config-card-baju">
                                <div class="lyb-slot-config-head d-flex align-items-center justify-content-between">
                                    <div class="lyb-slot-title">
                                        <i class="bi bi-scissors text-gold"></i>
                                        <span>Kuota Booking Tanggal Ini</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span id="badge-booked-baju" class="lyb-slot-booked-badge d-none">Booked (<span id="booked-count-text-baju">0</span>)</span>
                                        <label class="lyb-sched-switch" onclick="event.stopPropagation()">
                                            <input type="hidden" name="slots[baju][status]" value="diblokir">
                                            <input type="checkbox" name="slots[baju][status]" id="status-baju" value="tersedia" checked onchange="handleStatusToggle()">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="lyb-slot-config-body mt-3 d-block">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="lyb-sched-label fw-bold mb-1">Kuota Booking Baju (Maksimal Customer per Hari) <span class="text-danger">*</span></label>
                                            <input type="number" name="slots[baju][kuota]" id="kuota-baju" class="lyb-sched-input" min="1" value="5" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="lyb-sched-label fw-bold mb-1">Catatan Internal / Keterangan</label>
                                            <input type="text" name="slots[baju][catatan]" id="catatan-baju" class="lyb-sched-input" placeholder="Misal: booking untuk koleksi baru...">
                                        </div>

                                        {{-- Hidden inputs --}}
                                        <input type="hidden" name="slots[baju][jam_mulai]" value="08:00">
                                        <input type="hidden" name="slots[baju][jam_selesai]" value="17:00">
                                    </div>
                                </div>
                            </div>
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
                                Blokir Tanggal Ini
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

    {{-- Helper Hidden Forms --}}
    <form id="quick-block-form" action="{{ route('owner.schedules.toggleBlock') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="category_type" value="baju">
        <input type="hidden" name="tanggal" id="quick-block-tanggal">
        <input type="hidden" name="action" id="quick-block-action">
    </form>

    <form id="reset-schedule-form" action="{{ route('owner.schedules.destroy') }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
        <input type="hidden" name="category_type" value="baju">
        <input type="hidden" name="tanggal" id="reset-tanggal">
    </form>

    <script>
        let myModal;

        document.addEventListener('DOMContentLoaded', function() {
            myModal = new bootstrap.Modal(document.getElementById('scheduleModal'));

            // Register Reset Action
            document.getElementById('btn-reset-default').addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin menghapus kustomisasi jadwal Booking Baju tanggal ini?')) {
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
        });

        function handleStatusToggle() {
            const checkbox = document.getElementById('status-baju');
            const card = document.getElementById('config-card-baju');

            if (checkbox.checked) {
                card.classList.remove('blocked-card');
            } else {
                card.classList.add('blocked-card');
            }
        }

        function openModal(dateStr, formattedDate, isPast) {
            document.getElementById('modal-date-title').innerText = 'Jadwal Baju: ' + formattedDate;
            document.getElementById('form-tanggal').value = dateStr;

            const cell = document.getElementById('cell_' + dateStr);
            const slots = JSON.parse(cell.dataset.slots);
            const sInfo = slots['baju'] || {
                status: 'tersedia',
                kuota: 5,
                booked_count: 0,
                jam_mulai: '08:00',
                jam_selesai: '17:00',
                catatan: ''
            };

            const card = document.getElementById('config-card-baju');
            const checkbox = document.getElementById('status-baju');
            const badgeBooked = document.getElementById('badge-booked-baju');
            const bookedCountText = document.getElementById('booked-count-text-baju');

            checkbox.disabled = false;
            checkbox.checked = (sInfo.status !== 'diblokir');
            badgeBooked.classList.add('d-none');

            document.getElementById('kuota-baju').value = sInfo.kuota;
            document.getElementById('catatan-baju').value = sInfo.catatan;

            if (sInfo.status === 'diblokir') {
                card.classList.add('blocked-card');
            } else {
                card.classList.remove('blocked-card');
            }

            if (sInfo.booked_count > 0) {
                badgeBooked.classList.remove('d-none');
                bookedCountText.innerText = sInfo.booked_count + '/' + sInfo.kuota;
            }

            // Quick block buttons
            const qBtn = document.getElementById('btn-quick-block');
            if (sInfo.status === 'diblokir') {
                qBtn.innerText = 'Buka Tanggal Ini';
                qBtn.className = 'btn btn-success btn-sm fw-bold';
                qBtn.dataset.action = 'unblock';
            } else {
                qBtn.innerText = 'Blokir Tanggal Ini';
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
                checkbox.disabled = true;
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
    </style>
@endsection
