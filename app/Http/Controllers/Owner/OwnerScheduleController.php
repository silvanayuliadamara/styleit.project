<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\ServiceCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerScheduleController extends Controller
{
    /**
     * Helper to prepare calendar days grid.
     */
    private function getCalendarGrid($year, $month)
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth()->endOfDay();

        $days = [];

        // Pad start of month
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sun) - 6 (Sat)
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $days[] = null; // empty cell
        }

        // Add days of the month
        $currentDate = $startOfMonth->copy();
        while ($currentDate->lte($endOfMonth)) {
            $days[] = $currentDate->copy();
            $currentDate->addDay();
        }

        // Pad end of month to complete grid row (multiple of 7)
        $totalCells = count($days);
        $rem = $totalCells % 7;
        if ($rem > 0) {
            $pad = 7 - $rem;
            for ($i = 0; $i < $pad; $i++) {
                $days[] = null;
            }
        }

        return [
            'days' => $days,
            'start' => $startOfMonth,
            'end' => $endOfMonth,
            'monthName' => $startOfMonth->translatedFormat('F Y'),
            'prevMonth' => $startOfMonth->copy()->subMonth(),
            'nextMonth' => $startOfMonth->copy()->addMonth(),
        ];
    }

    /**
     * 1. Halaman Kelola Jadwal Wedding & Prewedding (Shared)
     */
    public function wedding(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $grid1 = $this->getCalendarGrid($year, $month);

        $nextMonthDate = Carbon::create($year, $month, 1)->addMonth();
        $grid2 = $this->getCalendarGrid($nextMonthDate->year, $nextMonthDate->month);

        // Get Wedding and Prewedding categories
        $weddingCat = ServiceCategory::where('slug', 'wedding')->first();
        $preweddingCat = ServiceCategory::where('slug', 'prewedding')->first();

        if (! $weddingCat || ! $preweddingCat) {
            return redirect()->route('owner.dashboard')->with('error', 'Kategori Wedding/Prewedding belum dikonfigurasi di database.');
        }

        $startDate = $grid1['start']->toDateString();
        $endDate = $grid2['end']->toDateString();

        // Get schedules
        $schedules = Schedule::whereIn('category_id', [$weddingCat->id, $preweddingCat->id])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal->toDateString().'_'.$item->jenis_jadwal;
            });

        // Get bookings (active only)
        $bookings = Booking::whereIn('package_id', function ($q) use ($weddingCat, $preweddingCat) {
            $q->select('id')->from('service_packages')
                ->whereIn('category_id', [$weddingCat->id, $preweddingCat->id]);
        })
            ->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
            ->whereBetween('tanggal_acara', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) {
                // assume booking_date or tanggal_acara. We use tanggal_acara as the real event date
                $date = $item->tanggal_acara ? $item->tanggal_acara->toDateString() : ($item->booking_date ? $item->booking_date->toDateString() : null);
                if (! $date && $item->schedule) {
                    $date = $item->schedule->tanggal->toDateString();
                }

                return $date.'_'.($item->schedule ? $item->schedule->jenis_jadwal : 'pagi');
            });

        return view('owner.schedule.wedding', [
            'grid1' => $grid1,
            'grid2' => $grid2,
            'year' => $year,
            'month' => $month,
            'weddingCat' => $weddingCat,
            'preweddingCat' => $preweddingCat,
            'schedules' => $schedules,
            'bookings' => $bookings,
        ]);
    }

    /**
     * 2. Halaman Kelola Jadwal Regular
     */
    public function regular(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $grid1 = $this->getCalendarGrid($year, $month);

        $nextMonthDate = Carbon::create($year, $month, 1)->addMonth();
        $grid2 = $this->getCalendarGrid($nextMonthDate->year, $nextMonthDate->month);

        $regularCat = ServiceCategory::where('slug', 'regular')->first();
        $weddingCat = ServiceCategory::where('slug', 'wedding')->first();
        $preweddingCat = ServiceCategory::where('slug', 'prewedding')->first();

        if (! $regularCat) {
            return redirect()->route('owner.dashboard')->with('error', 'Kategori Regular belum dikonfigurasi.');
        }

        $startDate = $grid1['start']->toDateString();
        $endDate = $grid2['end']->toDateString();

        // Schedules for regular
        $schedules = Schedule::where('category_id', $regularCat->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal->toDateString().'_'.$item->jenis_jadwal;
            });

        // Bookings for regular
        $bookings = Booking::whereIn('package_id', function ($q) use ($regularCat) {
            $q->select('id')->from('service_packages')->where('category_id', $regularCat->id);
        })
            ->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
            ->whereBetween('tanggal_acara', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) {
                $date = $item->tanggal_acara ? $item->tanggal_acara->toDateString() : ($item->booking_date ? $item->booking_date->toDateString() : null);
                if (! $date && $item->schedule) {
                    $date = $item->schedule->tanggal->toDateString();
                }

                return $date.'_'.($item->schedule ? $item->schedule->jenis_jadwal : 'pagi');
            });

        // Wedding/Prewedding bookings on these days (to auto-block regular)
        $weddingBookings = Booking::whereIn('package_id', function ($q) use ($weddingCat, $preweddingCat) {
            $q->select('id')->from('service_packages')
                ->whereIn('category_id', [$weddingCat?->id ?? 0, $preweddingCat?->id ?? 0]);
        })
            ->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
            ->whereBetween('tanggal_acara', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) {
                $date = $item->tanggal_acara ? $item->tanggal_acara->toDateString() : ($item->booking_date ? $item->booking_date->toDateString() : null);
                if (! $date && $item->schedule) {
                    $date = $item->schedule->tanggal->toDateString();
                }

                return $date.'_'.($item->schedule ? $item->schedule->jenis_jadwal : 'pagi');
            });

        return view('owner.schedule.regular', [
            'grid1' => $grid1,
            'grid2' => $grid2,
            'year' => $year,
            'month' => $month,
            'regularCat' => $regularCat,
            'schedules' => $schedules,
            'bookings' => $bookings,
            'weddingBookings' => $weddingBookings,
        ]);
    }

    /**
     * 3. Halaman Kelola Jadwal Baju
     */
    public function baju(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $grid1 = $this->getCalendarGrid($year, $month);

        $nextMonthDate = Carbon::create($year, $month, 1)->addMonth();
        $grid2 = $this->getCalendarGrid($nextMonthDate->year, $nextMonthDate->month);

        $bajuCat = ServiceCategory::where('slug', 'baju')->first();

        if (! $bajuCat) {
            return redirect()->route('owner.dashboard')->with('error', 'Kategori Khusus Baju belum dikonfigurasi.');
        }

        $startDate = $grid1['start']->toDateString();
        $endDate = $grid2['end']->toDateString();

        // Schedules for baju
        $schedules = Schedule::where('category_id', $bajuCat->id)
            ->where('jenis_jadwal', 'baju')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal->toDateString();
            });

        // Bookings for baju (based on booking_date/tanggal_acara)
        $bookings = Booking::whereIn('package_id', function ($q) use ($bajuCat) {
            $q->select('id')->from('service_packages')->where('category_id', $bajuCat->id);
        })
            ->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_acara', [$startDate, $endDate])
                    ->orWhereBetween('booking_date', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_acara ? $item->tanggal_acara->toDateString() : ($item->booking_date ? $item->booking_date->toDateString() : null);
            });

        return view('owner.schedule.baju', [
            'grid1' => $grid1,
            'grid2' => $grid2,
            'year' => $year,
            'month' => $month,
            'bajuCat' => $bajuCat,
            'schedules' => $schedules,
            'bookings' => $bookings,
        ]);
    }

    /**
     * Simpan atau update jadwal (dipanggil via POST dari modal modal)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_type' => 'required|in:wedding_prewedding,regular,baju',
            'tanggal' => 'required|date_format:Y-m-d',
            'slots' => 'required|array',
            'slots.*.status' => 'required|in:tersedia,diblokir',
            'slots.*.kuota' => 'required|integer|min:0',
            'slots.*.jam_mulai' => 'required_if:slots.*.status,tersedia|nullable|date_format:H:i',
            'slots.*.jam_selesai' => 'required_if:slots.*.status,tersedia|nullable|date_format:H:i',
            'slots.*.catatan' => 'nullable|string|max:255',
        ]);

        $categoryType = $validated['category_type'];
        $tanggal = $validated['tanggal'];
        $slots = $validated['slots'];

        // Determine category IDs to apply schedules to
        $categoryIds = [];
        if ($categoryType === 'wedding_prewedding') {
            $w = ServiceCategory::where('slug', 'wedding')->first();
            $p = ServiceCategory::where('slug', 'prewedding')->first();
            if ($w) {
                $categoryIds[] = $w->id;
            }
            if ($p) {
                $categoryIds[] = $p->id;
            }
        } elseif ($categoryType === 'regular') {
            $r = ServiceCategory::where('slug', 'regular')->first();
            if ($r) {
                $categoryIds[] = $r->id;
            }
        } elseif ($categoryType === 'baju') {
            $b = ServiceCategory::where('slug', 'baju')->first();
            if ($b) {
                $categoryIds[] = $b->id;
            }
        }

        if (empty($categoryIds)) {
            return redirect()->back()->with('error', 'Kategori tidak valid.');
        }

        DB::transaction(function () use ($categoryIds, $tanggal, $slots) {
            foreach ($categoryIds as $catId) {
                foreach ($slots as $jenisJadwal => $slotData) {
                    // Cek jika schedule sudah ada
                    $schedule = Schedule::where('category_id', $catId)
                        ->where('tanggal', $tanggal)
                        ->where('jenis_jadwal', $jenisJadwal)
                        ->first();

                    // Get default times if null/missing
                    $defaultTimes = [
                        'pagi' => ['start' => '06:00', 'end' => '11:00'],
                        'siang' => ['start' => '12:00', 'end' => '16:00'],
                        'baju' => ['start' => '08:00', 'end' => '17:00'],
                    ];
                    $jamMulai = $slotData['jam_mulai'] ?? ($schedule ? $schedule->jam_mulai : ($defaultTimes[$jenisJadwal]['start'] ?? '06:00'));
                    $jamSelesai = $slotData['jam_selesai'] ?? ($schedule ? $schedule->jam_selesai : ($defaultTimes[$jenisJadwal]['end'] ?? '11:00'));

                    if ($schedule) {
                        // Jangan kurangi terpakai, tapi update kuota & status & times
                        $schedule->update([
                            'jam_mulai' => $jamMulai,
                            'jam_selesai' => $jamSelesai,
                            'kuota' => $slotData['kuota'],
                            'status' => ($slotData['status'] === 'tersedia' && $schedule->terpakai >= $slotData['kuota']) ? 'penuh' : $slotData['status'],
                            'catatan' => $slotData['catatan'] ?? null,
                        ]);
                    } else {
                        // Create baru
                        Schedule::create([
                            'category_id' => $catId,
                            'tanggal' => $tanggal,
                            'jenis_jadwal' => $jenisJadwal,
                            'jam_mulai' => $jamMulai,
                            'jam_selesai' => $jamSelesai,
                            'kuota' => $slotData['kuota'],
                            'terpakai' => 0,
                            'status' => $slotData['status'],
                            'catatan' => $slotData['catatan'] ?? null,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Jadwal tanggal '.Carbon::parse($tanggal)->translatedFormat('d F Y').' berhasil diperbarui.');
    }

    /**
     * Blokir atau buka seluruh tanggal secara cepat
     */
    public function toggleBlock(Request $request)
    {
        $validated = $request->validate([
            'category_type' => 'required|in:wedding_prewedding,regular,baju',
            'tanggal' => 'required|date_format:Y-m-d',
            'action' => 'required|in:block,unblock',
        ]);

        $categoryType = $validated['category_type'];
        $tanggal = $validated['tanggal'];
        $action = $validated['action'];
        $status = ($action === 'block') ? 'diblokir' : 'tersedia';

        $categoryIds = [];
        $defaultSlots = [];
        if ($categoryType === 'wedding_prewedding') {
            $w = ServiceCategory::where('slug', 'wedding')->first();
            $p = ServiceCategory::where('slug', 'prewedding')->first();
            if ($w) {
                $categoryIds[] = $w->id;
            }
            if ($p) {
                $categoryIds[] = $p->id;
            }
            $defaultSlots = [
                'pagi' => ['start' => '06:00', 'end' => '11:00'],
                'siang' => ['start' => '12:00', 'end' => '16:00'],
            ];
        } elseif ($categoryType === 'regular') {
            $r = ServiceCategory::where('slug', 'regular')->first();
            if ($r) {
                $categoryIds[] = $r->id;
            }
            $defaultSlots = [
                'pagi' => ['start' => '06:00', 'end' => '11:00'],
                'siang' => ['start' => '12:00', 'end' => '16:00'],
            ];
        } elseif ($categoryType === 'baju') {
            $b = ServiceCategory::where('slug', 'baju')->first();
            if ($b) {
                $categoryIds[] = $b->id;
            }
            $defaultSlots = [
                'baju' => ['start' => '08:00', 'end' => '17:00'],
            ];
        }

        DB::transaction(function () use ($categoryIds, $tanggal, $status, $defaultSlots, $categoryType) {
            foreach ($categoryIds as $catId) {
                foreach ($defaultSlots as $jenis => $times) {
                    $schedule = Schedule::where('category_id', $catId)
                        ->where('tanggal', $tanggal)
                        ->where('jenis_jadwal', $jenis)
                        ->first();

                    if ($schedule) {
                        $schedule->update([
                            'status' => $status,
                        ]);
                    } else {
                        $kuota = 1;
                        if ($categoryType === 'baju') {
                            $kuota = 5;
                        } elseif ($categoryType === 'regular') {
                            $kuota = 3;
                        } elseif ($categoryType === 'wedding_prewedding') {
                            $kuota = ($jenis === 'pagi') ? 2 : 1;
                        }

                        Schedule::create([
                            'category_id' => $catId,
                            'tanggal' => $tanggal,
                            'jenis_jadwal' => $jenis,
                            'jam_mulai' => $times['start'],
                            'jam_selesai' => $times['end'],
                            'kuota' => $kuota,
                            'terpakai' => 0,
                            'status' => $status,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }
        });

        $msg = ($action === 'block') ? 'diblokir' : 'dibuka kembali';

        return redirect()->back()->with('success', 'Jadwal tanggal '.Carbon::parse($tanggal)->translatedFormat('d F Y').' berhasil '.$msg.'.');
    }

    /**
     * Reset/Hapus kustomisasi jadwal tanggal tertentu
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'category_type' => 'required|in:wedding_prewedding,regular,baju',
            'tanggal' => 'required|date_format:Y-m-d',
        ]);

        $categoryType = $validated['category_type'];
        $tanggal = $validated['tanggal'];

        $categoryIds = [];
        if ($categoryType === 'wedding_prewedding') {
            $w = ServiceCategory::where('slug', 'wedding')->first();
            $p = ServiceCategory::where('slug', 'prewedding')->first();
            if ($w) {
                $categoryIds[] = $w->id;
            }
            if ($p) {
                $categoryIds[] = $p->id;
            }
        } elseif ($categoryType === 'regular') {
            $r = ServiceCategory::where('slug', 'regular')->first();
            if ($r) {
                $categoryIds[] = $r->id;
            }
        } elseif ($categoryType === 'baju') {
            $b = ServiceCategory::where('slug', 'baju')->first();
            if ($b) {
                $categoryIds[] = $b->id;
            }
        }

        DB::transaction(function () use ($categoryIds, $tanggal) {
            // Kita hanya hapus schedule yang terpakai-nya 0 (tidak ada booking)
            Schedule::whereIn('category_id', $categoryIds)
                ->where('tanggal', $tanggal)
                ->where('terpakai', 0)
                ->delete();
        });

        return redirect()->back()->with('success', 'Kustomisasi jadwal tanggal '.Carbon::parse($tanggal)->translatedFormat('d F Y').' berhasil di-reset.');
    }
}
