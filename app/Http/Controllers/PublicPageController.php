<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\BlockedDate;
use App\Models\PortfolioItem;
use App\Models\ServiceCategory;
use App\Models\ServicePackage;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class PublicPageController extends Controller
{
    public function home()
    {
        $categories = ServiceCategory::orderBy('sort_order')->get();
        $portfolioItems = PortfolioItem::orderBy('sort_order')->take(4)->get();

        return view('home', compact('categories', 'portfolioItems'));
    }

    public function profil()
    {
        return view('profil');
    }

    public function layanan()
    {
        $categories = ServiceCategory::with(['packages' => function ($q) {
            $q->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        return view('layanan.index', compact('categories'));
    }

    public function kategori(string $slug)
    {
        $category = ServiceCategory::with(['packages' => function ($q) {
            $q->orderBy('sort_order');
        }])->where('slug', $slug)->first();

        abort_if(! $category, Response::HTTP_NOT_FOUND);

        return view('layanan.kategori', compact('category'));
    }

    public function paket(string $code)
    {
        $package = ServicePackage::with(['category', 'items'])
            ->where('code', $code)
            ->first();

        abort_if(! $package, Response::HTTP_NOT_FOUND);

        $addons = Addon::where('is_active', true)->get();
        $calendar = $this->buildCalendar($package);

        // Prepopulate edit data if editing cart item
        $editItem = null;
        if (request()->has('edit_key')) {
            $cart = session('cart', []);
            $editItem = collect($cart)->firstWhere('key', request()->query('edit_key'));
        }

        return view('paket.show', compact('package', 'addons', 'calendar', 'editItem'));
    }

    public function portofolio()
    {
        $items = PortfolioItem::orderBy('sort_order')->get();
        return view('portofolio', compact('items'));
    }

    public function pricelist()
    {
        $categories = ServiceCategory::with(['packages' => function ($q) {
            $q->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        $packages = ServicePackage::with('category')->orderBy('sort_order')->get();

        return view('pricelist', compact('categories', 'packages'));
    }

    // =========================================================
    // Helper: bangun kalender 60 hari ke depan
    // =========================================================
    private function buildCalendar(ServicePackage $package): array
    {
        $categorySlug = $package->category->slug ?? '';

        // Ambil tanggal yang diblokir dari database
        $blockedDates = BlockedDate::pluck('blocked_date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        // Optimized slot availability for Wedding/Prewedding
        $weddingCategory = ServiceCategory::where('slug', 'wedding')->first();
        $preweddingCategory = ServiceCategory::where('slug', 'prewedding')->first();
        $categoryIds = array_filter([$weddingCategory?->id, $preweddingCategory?->id]);

        $weddingBookings = [];
        $blockedSchedules = [];
        if ($categorySlug === 'wedding' || $categorySlug === 'prewedding') {
            $weddingBookings = Booking::whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
                ->whereDate('tanggal_acara', '>=', Carbon::today())
                ->whereDate('tanggal_acara', '<=', Carbon::today()->addDays(60))
                ->get()
                ->groupBy(fn($b) => $b->tanggal_acara->toDateString() . '_' . $b->slot_waktu)
                ->toArray();

            $blockedSchedules = Schedule::whereIn('category_id', $categoryIds)
                ->whereDate('tanggal', '>=', Carbon::today())
                ->whereDate('tanggal', '<=', Carbon::today()->addDays(60))
                ->where('status', 'diblokir')
                ->get()
                ->groupBy(fn($s) => $s->tanggal->toDateString() . '_' . $s->jenis_jadwal)
                ->toArray();
        }

        // Regular: needs schedule set by owner to be available
        $regularCategory = ServiceCategory::where('slug', 'regular')->first();
        $regularCategoryId = $regularCategory?->id ?? 0;
        $regularSchedules = [];
        $regularBookings = [];
        if ($categorySlug === 'regular') {
            $regularSchedules = Schedule::where('category_id', $regularCategoryId)
                ->whereDate('tanggal', '>=', Carbon::today())
                ->whereDate('tanggal', '<=', Carbon::today()->addDays(60))
                ->get()
                ->groupBy(fn($s) => $s->tanggal->toDateString())
                ->toArray();

            $regularBookings = Booking::whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
                ->whereDate('tanggal_acara', '>=', Carbon::today())
                ->whereDate('tanggal_acara', '<=', Carbon::today()->addDays(60))
                ->get()
                ->groupBy(fn($b) => $b->tanggal_acara->toDateString() . '_' . $b->slot_waktu)
                ->toArray();
        }

        // General fallback booking count
        $bookingCounts = $package->bookings()
            ->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima'])
            ->where('booking_date', '>=', Carbon::today())
            ->selectRaw('booking_date, COUNT(*) as total')
            ->groupBy('booking_date')
            ->pluck('total', 'booking_date')
            ->toArray();

        $dates = [];
        for ($i = 0; $i <= 60; $i++) {
            $date = Carbon::today()->addDays($i);
            $dateStr = $date->toDateString();

            $isBlocked = in_array($dateStr, $blockedDates, true);
            $isFull = false;
            $remaining = $package->quota_per_day;

            if (!$isBlocked) {
                if ($categorySlug === 'wedding' || $categorySlug === 'prewedding') {
                    // Check slots: pagi, siang, sore
                    $unavailableSlotsCount = 0;
                    foreach (['pagi', 'siang', 'sore'] as $slot) {
                        $key = $dateStr . '_' . $slot;
                        $booked = isset($weddingBookings[$key]) || Booking::whereDate('tanggal_acara', $dateStr)
                            ->where('slot_waktu', $slot)
                            ->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
                            ->exists(); // fallback check

                        $blocked = isset($blockedSchedules[$key]);
                        if ($booked || $blocked) {
                            $unavailableSlotsCount++;
                        }
                    }
                    $isFull = ($unavailableSlotsCount >= 3);
                    $remaining = max(0, 3 - $unavailableSlotsCount);
                } elseif ($categorySlug === 'regular') {
                    // Check if owner opened any slots for this day
                    $daySchedules = $regularSchedules[$dateStr] ?? [];
                    $availableCount = 0;
                    foreach ($daySchedules as $sched) {
                        $schedObj = is_array($sched) ? (object) $sched : $sched;
                        if ($schedObj->status === 'tersedia') {
                            $key = $dateStr . '_' . $schedObj->jenis_jadwal;
                            $activeCount = isset($regularBookings[$key]) ? count($regularBookings[$key]) : 0;
                            $availableCount += max(0, $schedObj->kuota - $activeCount);
                        }
                    }
                    $isFull = ($availableCount <= 0);
                    $remaining = $availableCount;
                } else {
                    // Default logic (for Baju/etc.)
                    $bookedCount = $bookingCounts[$dateStr] ?? 0;
                    $isFull = $bookedCount >= $package->quota_per_day;
                    $remaining = $isFull ? 0 : $package->quota_per_day - $bookedCount;
                }
            } else {
                $remaining = 0;
            }

            $dates[] = [
                'date'      => $dateStr,
                'day'       => $date->format('d'),
                'month'     => $date->translatedFormat('M'),
                'label'     => $date->translatedFormat('D, d M Y'),
                'status'    => $isBlocked ? 'blocked' : ($isFull ? 'full' : 'available'),
                'remaining' => $remaining,
                'reason'    => $isBlocked
                    ? (BlockedDate::where('blocked_date', $dateStr)->value('reason') ?? 'Jadwal diblokir admin')
                    : null,
            ];
        }

        return $dates;
    }

    public function getAvailableSlots(Request $request, string $code)
    {
        $package = ServicePackage::with('category')->where('code', $code)->firstOrFail();
        $dateStr = $request->input('date');

        if (!$dateStr) {
            return response()->json(['error' => 'Parameter tanggal wajib diisi.'], 400);
        }

        $categorySlug = $package->category->slug;

        $slots = [
            'pagi'  => ['available' => true, 'label' => 'Pagi (06:00 - 11:00)', 'reason' => null],
            'siang' => ['available' => true, 'label' => 'Siang (12:00 - 16:00)', 'reason' => null],
            'sore'  => ['available' => true, 'label' => 'Sore (17:00 - 21:00)', 'reason' => null],
        ];

        if ($categorySlug === 'wedding' || $categorySlug === 'prewedding') {
            // Wedding & Prewedding: 1 MUA location per slot.
            // Check if slot has any active bookings in wedding OR prewedding categories on that date.
            $weddingCategory = ServiceCategory::where('slug', 'wedding')->first();
            $preweddingCategory = ServiceCategory::where('slug', 'prewedding')->first();
            $categoryIds = array_filter([$weddingCategory?->id, $preweddingCategory?->id]);

            foreach ($slots as $slotKey => &$slotInfo) {
                // Check if booked
                $isBooked = Booking::whereDate('tanggal_acara', $dateStr)
                    ->where('slot_waktu', $slotKey)
                    ->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
                    ->exists();

                if ($isBooked) {
                    $slotInfo['available'] = false;
                    $slotInfo['reason'] = 'Sudah dibooking pelanggan lain';
                    continue;
                }

                // Check if blocked by owner in schedules
                $isBlockedByOwner = Schedule::whereDate('tanggal', $dateStr)
                    ->whereIn('category_id', $categoryIds)
                    ->where('jenis_jadwal', $slotKey)
                    ->where('status', 'diblokir')
                    ->exists();

                if ($isBlockedByOwner) {
                    $slotInfo['available'] = false;
                    $slotInfo['reason'] = 'Diblokir oleh MUA';
                }
            }
        } elseif ($categorySlug === 'regular') {
            // Regular: customer can only choose slots opened by the owner in schedules
            $regularCategory = ServiceCategory::where('slug', 'regular')->first();
            $regularCategoryId = $regularCategory?->id ?? 0;

            foreach ($slots as $slotKey => &$slotInfo) {
                $schedule = Schedule::where('category_id', $regularCategoryId)
                    ->whereDate('tanggal', $dateStr)
                    ->where('jenis_jadwal', $slotKey)
                    ->first();

                // Count active bookings for this slot
                $activeCount = Booking::whereDate('tanggal_acara', $dateStr)
                    ->where('slot_waktu', $slotKey)
                    ->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
                    ->count();

                if (!$schedule || $schedule->status !== 'tersedia' || $activeCount >= $schedule->kuota) {
                    $slotInfo['available'] = false;
                    $slotInfo['reason'] = !$schedule ? 'Jadwal belum dibuka oleh MUA' : ($schedule->status === 'diblokir' ? 'Diblokir oleh MUA' : 'Kuota MUA sudah penuh di jam ini');
                }
            }
        } else {
            // Khusus Baju or others: no MUA slot limitation.
            $slots = [];
        }

        // Check if this package needs clothing fitting
        $needsFitting = ($categorySlug === 'baju');

        return response()->json([
            'category' => $categorySlug,
            'slots' => $slots,
            'needs_fitting' => $needsFitting,
        ]);
    }
}
