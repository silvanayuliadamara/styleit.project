<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\CancellationRequest;
use App\Models\Schedule;
use App\Models\ServiceCategory;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AutoCancelCancellationRequestTest extends TestCase
{
    use RefreshDatabase;

    private function createDependencies()
    {
        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $category = ServiceCategory::create([
            'name' => 'Wedding',
            'slug' => 'wedding',
            'headline' => 'Wedding Services',
            'description' => 'Wedding Description',
        ]);

        $package = ServicePackage::create([
            'category_id' => $category->id,
            'code' => 'PKG-WED-01',
            'name' => 'Wedding Silver Package',
            'slug' => 'wedding-silver',
            'price' => 5000000,
            'dp_amount' => 1000000,
            'quota_per_day' => 2,
        ]);

        $schedule = Schedule::create([
            'category_id' => $category->id,
            'tanggal' => now()->addDays(5)->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '13:00',
            'jenis_jadwal' => 'pagi',
            'kuota' => 2,
            'terpakai' => 1,
            'status' => 'tersedia',
        ]);

        return [$customer, $package, $schedule];
    }

    public function test_pending_cancellation_requests_under_24_hours_are_not_cancelled(): void
    {
        [$customer, $package, $schedule] = $this->createDependencies();

        $booking = Booking::create([
            'booking_code' => 'BOOK-TEST-01',
            'user_id' => $customer->id,
            'package_id' => $package->id,
            'schedule_id' => $schedule->id,
            'booking_date' => now()->format('Y-m-d'),
            'subtotal' => 5000000,
            'total_price' => 5000000,
            'dp_amount' => 1000000,
            'remaining_payment' => 4000000,
            'status' => 'diterima',
            'status_layanan' => 'terjadwal',
        ]);

        $cancelReq = CancellationRequest::create([
            'booking_id' => $booking->id,
            'alasan' => 'Ada acara mendadak',
            'status_persetujuan' => 'diajukan',
            'customer_dibaca' => true,
        ]);

        // Mock created_at to 23 hours ago
        DB::table('cancellation_requests')->where('id', $cancelReq->id)->update([
            'created_at' => now()->subHours(23),
        ]);

        // Run auto-cancel logic
        Booking::autoCancelPendingCancellations();

        // Assert nothing changed
        $booking->refresh();
        $cancelReq->refresh();
        $schedule->refresh();

        $this->assertEquals('diterima', $booking->status);
        $this->assertEquals('diajukan', $cancelReq->status_persetujuan);
        $this->assertEquals(1, $schedule->terpakai); // Not decremented
    }

    public function test_pending_cancellation_requests_over_24_hours_are_automatically_cancelled(): void
    {
        [$customer, $package, $schedule] = $this->createDependencies();

        $booking = Booking::create([
            'booking_code' => 'BOOK-TEST-02',
            'user_id' => $customer->id,
            'package_id' => $package->id,
            'schedule_id' => $schedule->id,
            'booking_date' => now()->format('Y-m-d'),
            'subtotal' => 5000000,
            'total_price' => 5000000,
            'dp_amount' => 1000000,
            'remaining_payment' => 4000000,
            'status' => 'diterima',
            'status_layanan' => 'terjadwal',
        ]);

        $cancelReq = CancellationRequest::create([
            'booking_id' => $booking->id,
            'alasan' => 'Batal nikah',
            'status_persetujuan' => 'diajukan',
            'customer_dibaca' => true,
        ]);

        // Mock created_at to 25 hours ago
        DB::table('cancellation_requests')->where('id', $cancelReq->id)->update([
            'created_at' => now()->subHours(25),
        ]);

        // Run auto-cancel logic
        Booking::autoCancelPendingCancellations();

        // Assert booking and request are cancelled
        $booking->refresh();
        $cancelReq->refresh();
        $schedule->refresh();

        $this->assertEquals('dibatalkan', $booking->status);
        $this->assertEquals('dibatalkan', $booking->status_layanan);
        $this->assertEquals('disetujui', $cancelReq->status_persetujuan);
        $this->assertNull($cancelReq->approved_by);
        $this->assertFalse($cancelReq->customer_dibaca);
        $this->assertEquals(0, $schedule->terpakai); // Decremented
    }
}
