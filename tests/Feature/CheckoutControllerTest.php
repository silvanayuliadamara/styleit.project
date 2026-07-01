<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\ServiceCategory;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    private function setupInitialData()
    {
        // Set up Spatie role
        Role::create(['name' => 'customer']);

        $customer = User::create([
            'name' => 'Test Client',
            'email' => 'client@test.com',
            'phone' => '08123456789',
            'instagram' => 'testclient',
            'address' => 'Jakarta, Indonesia',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $customer->assignRole('customer');

        $weddingCategory = ServiceCategory::create([
            'name' => 'Wedding',
            'slug' => 'wedding',
            'headline' => 'Wedding headline',
            'description' => 'Wedding desc',
        ]);

        $preweddingCategory = ServiceCategory::create([
            'name' => 'Prewedding',
            'slug' => 'prewedding',
            'headline' => 'Prewedding headline',
            'description' => 'Prewedding desc',
        ]);

        $weddingPackage = ServicePackage::create([
            'category_id' => $weddingCategory->id,
            'code' => 'PKG-WEDD-01',
            'name' => 'Wedding Gold',
            'slug' => 'wedding-gold',
            'price' => 10000000,
            'dp_amount' => 2000000,
            'quota_per_day' => 2,
        ]);

        $preweddingPackage = ServicePackage::create([
            'category_id' => $preweddingCategory->id,
            'code' => 'PKG-PREW-01',
            'name' => 'Prewedding Basic',
            'slug' => 'prewedding-basic',
            'price' => 3000000,
            'dp_amount' => 500000,
            'quota_per_day' => 1,
        ]);

        return [$customer, $weddingPackage, $preweddingPackage];
    }

    public function test_checkout_validation_requires_address_for_wedding_package(): void
    {
        [$customer, $weddingPackage, $preweddingPackage] = $this->setupInitialData();

        $cart = [
            [
                'key' => 'cart_1',
                'package_id' => $weddingPackage->id,
                'booking_date' => now()->addDays(10)->format('Y-m-d'),
                'slot_waktu' => 'pagi',
                'total_price' => 10000000,
                'dp_amount' => 2000000,
                'remaining_payment' => 8000000,
            ]
        ];

        // Accessing as authenticated user
        Auth::login($customer);
        session(['cart' => $cart]);

        // Attempting store without address (address should be required since it is a wedding category package)
        $response = $this->post(route('customer.checkout.store'), [
            'phone' => '08123456789',
            'instagram' => 'testclient',
            'payment_method' => 'va',
            'notes' => 'Some client request notes',
            'address' => '', // Empty address
        ]);

        $response->assertSessionHasErrors(['address']);
    }

    public function test_checkout_validation_allows_empty_address_for_prewedding_only_package(): void
    {
        [$customer, $weddingPackage, $preweddingPackage] = $this->setupInitialData();

        $cart = [
            [
                'key' => 'cart_2',
                'package_id' => $preweddingPackage->id,
                'booking_date' => now()->addDays(10)->format('Y-m-d'),
                'slot_waktu' => 'pagi',
                'total_price' => 3000000,
                'dp_amount' => 500000,
                'remaining_payment' => 2500000,
            ]
        ];

        Auth::login($customer);
        session(['cart' => $cart]);

        // Clear user address
        $customer->update(['address' => null]);

        $response = $this->post(route('customer.checkout.store'), [
            'phone' => '08123456789',
            'instagram' => 'testclient',
            'payment_method' => 'va',
            'notes' => 'Some notes',
            'address' => '', // Empty address should be fine for prewedding
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'package_id' => $preweddingPackage->id,
            'user_id' => $customer->id,
            'status' => 'pending',
            'payment_status' => 'belum_bayar',
        ]);
    }

    public function test_checkout_successfully_creates_booking_and_pending_payment(): void
    {
        [$customer, $weddingPackage, $preweddingPackage] = $this->setupInitialData();

        $bookingDate = now()->addDays(15)->format('Y-m-d');
        $cart = [
            [
                'key' => 'cart_3',
                'package_id' => $preweddingPackage->id,
                'booking_date' => $bookingDate,
                'slot_waktu' => 'pagi',
                'total_price' => 3000000,
                'dp_amount' => 500000,
                'remaining_payment' => 2500000,
            ]
        ];

        Auth::login($customer);
        session(['cart' => $cart]);

        $response = $this->post(route('customer.checkout.store'), [
            'phone' => '0811111111',
            'instagram' => 'client_ig',
            'payment_method' => 'wallet',
            'address' => 'Some address',
        ]);

        $response->assertRedirect();

        // Verify database entry
        $this->assertDatabaseHas('bookings', [
            'package_id' => $preweddingPackage->id,
            'booking_date' => $bookingDate . ' 00:00:00',
            'status' => 'pending',
            'payment_status' => 'belum_bayar',
        ]);

        $booking = Booking::where('package_id', $preweddingPackage->id)->first();

        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'amount' => 500000,
            'status' => 'pending',
            'metode_pembayaran' => 'E-Wallet',
        ]);

        // Verify schedule creation
        $this->assertDatabaseHas('schedules', [
            'category_id' => $preweddingPackage->category_id,
            'tanggal' => $bookingDate . ' 00:00:00',
            'jenis_jadwal' => 'pagi',
            'status' => 'tersedia',
        ]);

        // Verify cart session has been cleared
        $this->assertNull(session('cart'));
    }
}