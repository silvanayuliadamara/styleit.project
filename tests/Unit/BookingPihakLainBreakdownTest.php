<?php

namespace Tests\Unit;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\PackageItem;
use App\Models\ServiceCategory;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingPihakLainBreakdownTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_pihak_lain_breakdown_calculation(): void
    {
        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer_test@test.com',
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

        // Create Package Items: Melati (pihak lain) and regular item
        PackageItem::create([
            'package_id' => $package->id,
            'name' => 'Melati Pengantin',
            'quantity' => 1,
            'unit' => 'x',
            'is_pihak_lain' => true,
            'biaya_pihak_lain' => 200000,
        ]);

        PackageItem::create([
            'package_id' => $package->id,
            'name' => 'Makeup Regular',
            'quantity' => 1,
            'unit' => 'x',
            'is_pihak_lain' => false,
            'biaya_pihak_lain' => 0,
        ]);

        $booking = Booking::create([
            'booking_code' => 'BOOK-TEST-99',
            'user_id' => $customer->id,
            'package_id' => $package->id,
            'booking_date' => now()->format('Y-m-d'),
            'subtotal' => 5000000,
            'total_price' => 5000000,
            'dp_amount' => 1000000,
            'remaining_payment' => 4000000,
            'status' => 'diterima',
            'status_layanan' => 'terjadwal',
        ]);

        // Create Henna Addon (without setting is_pihak_lain in database, it should auto-detect and use price as fallback)
        $addonHenna = Addon::create([
            'name' => 'Henna incl. kuku palsu',
            'description' => 'Henna',
            'price' => 350000,
            'harga_default' => 350000,
            'is_pihak_lain' => false,
            'biaya_pihak_lain' => 0,
            'is_active' => true,
        ]);

        // Attach Henna Addon
        $booking->addons()->attach($addonHenna->id, [
            'price' => 350000,
            'nama_addon' => 'Henna incl. kuku palsu',
            'qty' => 1,
            'subtotal' => 350000,
            'is_pihak_lain' => false,
            'biaya_pihak_lain' => 0,
        ]);

        // Create Melati Addon (with custom biaya_pihak_lain defined)
        $addonMelati = Addon::create([
            'name' => 'Melati Ekstra',
            'description' => 'Melati',
            'price' => 150000,
            'harga_default' => 150000,
            'is_pihak_lain' => true,
            'biaya_pihak_lain' => 100000,
            'is_active' => true,
        ]);

        // Attach Melati Addon
        $booking->addons()->attach($addonMelati->id, [
            'price' => 150000,
            'nama_addon' => 'Melati Ekstra',
            'qty' => 1,
            'subtotal' => 150000,
            'is_pihak_lain' => true,
            'biaya_pihak_lain' => 100000,
        ]);

        // Create Other Addon (third party, not henna or melati)
        $addonOther = Addon::create([
            'name' => 'Sewa Baju Adat',
            'description' => 'Baju Adat',
            'price' => 500000,
            'harga_default' => 500000,
            'is_pihak_lain' => true,
            'biaya_pihak_lain' => 300000,
            'is_active' => true,
        ]);

        // Attach Other Addon
        $booking->addons()->attach($addonOther->id, [
            'price' => 500000,
            'nama_addon' => 'Sewa Baju Adat',
            'qty' => 1,
            'subtotal' => 500000,
            'is_pihak_lain' => true,
            'biaya_pihak_lain' => 300000,
        ]);

        $breakdown = $booking->pihak_lain_breakdown;

        // Expectations:
        // 1. Melati:
        //    - From Package: Melati Pengantin = 200,000
        //    - From Addon: Melati Ekstra = 100,000 (biaya_pihak_lain > 0)
        //    - Total Melati = 300,000
        $this->assertEquals(300000, $breakdown['melati']);

        // 2. Henna:
        //    - From Addon: Henna incl. kuku palsu = 350,000 (auto-detect based on name, fallback to subtotal because biaya_pihak_lain = 0)
        //    - Total Henna = 350,000
        $this->assertEquals(350000, $breakdown['henna']);

        // 3. Lainnya:
        //    - From Addon: Sewa Baju Adat = 300,000 (biaya_pihak_lain = 300,000)
        //    - Total Lainnya = 300,000
        $this->assertEquals(300000, $breakdown['lainnya']);

        // 4. Total:
        //    - 300,000 + 350,000 + 300,000 = 950,000
        $this->assertEquals(950000, $breakdown['total']);
    }
}
