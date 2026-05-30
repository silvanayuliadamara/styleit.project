<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\BlockedDate;
use App\Models\PackageItem;
use App\Models\PortfolioItem;
use App\Models\ServiceCategory;
use App\Models\ServicePackage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Prewedding', 'slug' => 'prewedding', 'headline' => 'Momen Romantis Pra-Pernikahan', 'description' => 'Riasan elegan untuk sesi prewedding Anda — soft glam yang fotogenik di setiap sudut.', 'icon' => 'bi-heart', 'sort_order' => 1],
            ['name' => 'Wedding', 'slug' => 'wedding', 'headline' => 'Hari Sakral Anda, Sempurna', 'description' => 'Paket lengkap makeup pengantin dengan komponen henna dan melati bernuansa premium.', 'icon' => 'bi-gem', 'sort_order' => 2],
            ['name' => 'Regular', 'slug' => 'regular', 'headline' => 'Wisuda & Acara Spesial', 'description' => 'Riasan bersinar untuk wisuda, lamaran kecil, atau event harian — terbatas 3 customer per hari.', 'icon' => 'bi-magic', 'sort_order' => 3],
            ['name' => 'Khusus Baju', 'slug' => 'baju', 'headline' => 'Koleksi Gaun & Kebaya', 'description' => 'Sewa baju pengantin, kebaya pasangan, dan gaun premium dengan pilihan satuan atau paket.', 'icon' => 'bi-bag-heart', 'sort_order' => 4],
        ];

        foreach ($categories as $data) {
            ServiceCategory::updateOrCreate(['slug' => $data['slug']], $data);
        }

        $packageRows = [
            ['category' => 'prewedding', 'code' => 'PKG-PREW-STD', 'name' => 'Paket Prewedding', 'slug' => 'paket-prewedding', 'description' => 'Makeup prewedding soft glam lengkap dengan opsi baju pasangan.', 'price' => 2500000, 'dp_amount' => 500000, 'quota_per_day' => 1, 'is_popular' => false, 'sort_order' => 1, 'items' => [['Henna', 1, 'x'], ['Konsultasi look', 1, 'sesi']]],
            ['category' => 'wedding', 'code' => 'PKG-WED-GOLD', 'name' => 'Paket Wedding Gold', 'slug' => 'paket-wedding-gold', 'description' => 'Makeup pengantin lengkap, detail glamor lembut, henna, dan melati untuk hari sakral Anda.', 'price' => 5000000, 'dp_amount' => 1000000, 'quota_per_day' => 1, 'is_popular' => true, 'sort_order' => 1, 'items' => [['Henna', 2, 'x'], ['Melati', 1, 'x'], ['Konsultasi bridal', 1, 'sesi']]],
            ['category' => 'regular', 'code' => 'PKG-REG-WIS', 'name' => 'Paket Regular Wisuda', 'slug' => 'paket-regular-wisuda', 'description' => 'Makeup wisuda glowing yang tahan lama, cocok untuk wisuda, lamaran kecil, dan event spesial.', 'price' => 500000, 'dp_amount' => 200000, 'quota_per_day' => 3, 'is_popular' => false, 'sort_order' => 1, 'items' => [['Makeup regular', 1, 'orang'], ['Touch up ringan', 1, 'x']]],
            ['category' => 'baju', 'code' => 'PKG-BAJU-PAS', 'name' => 'Paket Baju Pasangan', 'slug' => 'paket-baju-pasangan', 'description' => 'Sewa baju pengantin pasangan dan kebaya premium untuk momen istimewa.', 'price' => 750000, 'dp_amount' => 250000, 'quota_per_day' => 2, 'is_popular' => false, 'sort_order' => 1, 'items' => [['Baju pasangan', 1, 'set'], ['Fitting', 1, 'sesi']]],
        ];

        foreach ($packageRows as $row) {
            $category = ServiceCategory::where('slug', $row['category'])->first();
            $items = $row['items'];
            unset($row['category'], $row['items']);
            $package = ServicePackage::updateOrCreate(['code' => $row['code']], array_merge($row, ['category_id' => $category->id]));
            $package->items()->delete();
            foreach ($items as [$name, $quantity, $unit]) {
                PackageItem::create(['package_id' => $package->id, 'name' => $name, 'quantity' => $quantity, 'unit' => $unit]);
            }
        }

        foreach ([
            ['name' => 'Makeup Keluarga', 'description' => 'Tambahan makeup untuk keluarga inti.', 'price' => 450000],
            ['name' => 'Tambahan Baju', 'description' => 'Tambahan satu set baju premium.', 'price' => 650000],
            ['name' => 'Henna incl. kuku palsu', 'description' => 'Henna cantik lengkap dengan kuku palsu.', 'price' => 350000],
            ['name' => 'Melati', 'description' => 'Aksesoris melati pengantin.', 'price' => 250000],
        ] as $addon) {
            Addon::updateOrCreate(['name' => $addon['name']], $addon + ['is_active' => true]);
        }

        foreach ([
            ['title' => 'Wedding Glam — Sasha', 'category' => 'wedding', 'description' => 'Look pengantin glamor lembut.'],
            ['title' => 'Prewedding Soft — Anindya', 'category' => 'prewedding', 'description' => 'Soft glam untuk sesi outdoor.'],
            ['title' => 'Wisuda Glow — Rara', 'category' => 'regular', 'description' => 'Riasan wisuda fresh dan tahan lama.'],
            ['title' => 'Bridal Henna — Salma', 'category' => 'wedding', 'description' => 'Detail henna premium untuk pengantin.'],
            ['title' => 'Kebaya Gold Edition', 'category' => 'baju', 'description' => 'Koleksi kebaya gold premium.'],
            ['title' => 'Pasangan Pengantin — Dila & Andre', 'category' => 'wedding', 'description' => 'Busana dan riasan pasangan pengantin.'],
        ] as $index => $item) {
            PortfolioItem::updateOrCreate(['title' => $item['title']], $item + ['sort_order' => $index + 1]);
        }

        foreach ([now()->addDays(9)->toDateString(), now()->addDays(21)->toDateString()] as $date) {
            BlockedDate::updateOrCreate(['blocked_date' => $date], ['reason' => 'Jadwal diblokir admin']);
        }
    }
}
