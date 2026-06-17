<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PreviewData
{
    public static function object(array $data): object
    {
        $object = new \stdClass();
        foreach ($data as $key => $value) {
            $object->{$key} = $value;
        }
        return $object;
    }

    public static function categories(): Collection
    {
        $categories = collect([
            ['id' => 1, 'name' => 'Prewedding', 'slug' => 'prewedding', 'headline' => 'Elegant Prewedding Look', 'description' => 'Riasan prewedding natural glam untuk sesi foto indoor maupun outdoor.', 'icon' => 'bi-camera-heart', 'sort_order' => 1],
            ['id' => 2, 'name' => 'Wedding', 'slug' => 'wedding', 'headline' => 'Luxury Bridal Makeup', 'description' => 'Paket rias pengantin lengkap untuk akad, resepsi, dan rangkaian hari bahagia.', 'icon' => 'bi-gem', 'sort_order' => 2],
            ['id' => 3, 'name' => 'Regular', 'slug' => 'regular', 'headline' => 'Makeup Wisuda / Acara Lainnya', 'description' => 'Riasan bersinar untuk wisuda, terbatas 3 customer/hari.', 'icon' => 'bi-stars', 'sort_order' => 3],
            ['id' => 4, 'name' => 'Khusus Baju', 'slug' => 'baju', 'headline' => 'Sewa Baju Pengantin & Kebaya', 'description' => 'Sewa baju elegan untuk wedding, prewedding, wisuda, dan event keluarga.', 'icon' => 'bi-bag-heart', 'sort_order' => 4],
        ])->map(fn ($item) => self::object($item));

        $packages = self::packages();
        foreach ($categories as $category) {
            $category->packages = $packages->where('category_id', $category->id)->values();
        }

        return $categories;
    }

    public static function packages(): Collection
    {
        $categories = collect([
            1 => self::object(['id' => 1, 'name' => 'Prewedding', 'slug' => 'prewedding', 'headline' => 'Elegant Prewedding Look']),
            2 => self::object(['id' => 2, 'name' => 'Wedding', 'slug' => 'wedding', 'headline' => 'Luxury Bridal Makeup']),
            3 => self::object(['id' => 3, 'name' => 'Regular', 'slug' => 'regular', 'headline' => 'Makeup Event Harian']),
            4 => self::object(['id' => 4, 'name' => 'Khusus Baju', 'slug' => 'baju', 'headline' => 'Koleksi Kebaya & Gaun']),
        ]);

        return collect([
            ['id' => 2, 'category_id' => 1, 'code' => 'PKG-PRE-GOLD', 'name' => 'Paket Prewedding Gold', 'description' => 'Makeup prewedding premium dengan look yang detail dan tahan lama.', 'price' => 2500000, 'dp_amount' => 500000, 'quota_per_day' => 2, 'is_popular' => true, 'items' => [['Makeup', 1, 'x'], ['Hairdo/Hijabdo', 1, 'x'], ['Aksesoris', 1, 'set']]],
            ['id' => 3, 'category_id' => 2, 'code' => 'PKG-WED-GOLD', 'name' => 'Paket Wedding Gold', 'description' => 'Paket wedding favorit untuk akad dan resepsi dengan sentuhan luxury.', 'price' => 5000000, 'dp_amount' => 1000000, 'quota_per_day' => 1, 'is_popular' => true, 'items' => [['Makeup Pengantin', 2, 'x'], ['Hairdo/Hijabdo', 2, 'x'], ['Melati', 1, 'x'], ['Retouch', 1, 'x']]],
            ['id' => 5, 'category_id' => 3, 'code' => 'PKG-REG-WIS', 'name' => 'Paket Regular Wisuda', 'description' => 'Makeup wisuda flawless dan fresh untuk hari kelulusan.', 'price' => 500000, 'dp_amount' => 200000, 'quota_per_day' => 3, 'is_popular' => true, 'items' => [['Makeup', 1, 'x'], ['Hairdo/Hijabdo', 1, 'x']]],
            ['id' => 7, 'category_id' => 4, 'code' => 'PKG-BAJU-KEBAYA', 'name' => 'Sewa Kebaya Premium', 'description' => 'Sewa kebaya elegan untuk akad, wisuda, atau acara keluarga.', 'price' => 650000, 'dp_amount' => 200000, 'quota_per_day' => 5, 'is_popular' => false, 'items' => [['Kebaya', 1, 'set'], ['Aksesoris', 1, 'set']]],
            ['id' => 8, 'category_id' => 4, 'code' => 'PKG-BAJU-BRIDAL', 'name' => 'Sewa Gaun Bridal', 'description' => 'Koleksi gaun bridal untuk prewedding dan resepsi.', 'price' => 1200000, 'dp_amount' => 350000, 'quota_per_day' => 3, 'is_popular' => true, 'items' => [['Gaun', 1, 'set'], ['Veil', 1, 'x']]],
        ])->map(function ($item) use ($categories) {
            $item['category'] = $categories[$item['category_id']];
            $item['items'] = collect($item['items'])->map(fn ($row) => self::object(['name' => $row[0], 'quantity' => $row[1], 'unit' => $row[2]]));
            return self::object($item);
        });
    }

    public static function addons(): Collection
    {
        return collect([
            ['id' => 1, 'name' => 'Makeup Keluarga', 'description' => 'Untuk ibu/saudara keluarga inti.', 'price' => 450000],
            ['id' => 2, 'name' => 'Tambahan Baju', 'description' => 'Satu set baju tambahan.', 'price' => 650000],
            ['id' => 3, 'name' => 'Henna incl. kuku palsu', 'description' => 'Henna tangan dengan kuku palsu.', 'price' => 350000],
            ['id' => 4, 'name' => 'Melati', 'description' => 'Melati segar untuk pengantin.', 'price' => 250000],
        ])->map(fn ($item) => self::object($item));
    }

    public static function portfolio(): Collection
    {
        return collect([
            ['title' => 'Wedding Gold Sasha', 'category' => 'wedding', 'description' => 'Look pengantin luxury dengan detail flawless.'],
            ['title' => 'Prewedding Outdoor', 'category' => 'prewedding', 'description' => 'Soft glam natural untuk sesi foto outdoor.'],
            ['title' => 'Wisuda Natural Glam', 'category' => 'regular', 'description' => 'Look fresh, ringan, dan tetap tahan lama.'],
            ['title' => 'Kebaya Premium', 'category' => 'baju', 'description' => 'Koleksi kebaya elegan untuk acara formal.'],
            ['title' => 'Wedding Platinum', 'category' => 'wedding', 'description' => 'Rias pengantin premium untuk akad dan resepsi.'],
            ['title' => 'Party Makeup', 'category' => 'regular', 'description' => 'Makeup glam untuk event malam.'],
        ])->map(fn ($item) => self::object($item));
    }

    public static function packageByCode(string $code): ?object
    {
        return self::packages()->firstWhere('code', $code);
    }

    public static function packageById(int $id): ?object
    {
        return self::packages()->firstWhere('id', $id);
    }

    public static function categoryBySlug(string $slug): ?object
    {
        return self::categories()->firstWhere('slug', $slug);
    }

    public static function calendarFor(object $package): array
    {
        $blocked = [Carbon::today()->addDays(5)->toDateString(), Carbon::today()->addDays(17)->toDateString()];
        $dates = [];
        for ($i = 0; $i <= 60; $i++) {
            $date = Carbon::today()->addDays($i);
            $isBlocked = in_array($date->toDateString(), $blocked, true);
            $isFull = ! $isBlocked && in_array($i % 11, [0], true) && $i > 0;
            $remaining = $isFull ? 0 : max(1, $package->quota_per_day - ($i % max(1, $package->quota_per_day)));
            $dates[] = [
                'date' => $date->toDateString(),
                'day' => $date->format('d'),
                'month' => $date->translatedFormat('M'),
                'label' => $date->translatedFormat('D, d M Y'),
                'status' => $isBlocked ? 'blocked' : ($isFull ? 'full' : 'available'),
                'remaining' => $remaining,
                'reason' => $isBlocked ? 'Jadwal diblokir admin' : null,
            ];
        }
        return $dates;
    }

    public static function demoBookings(): Collection
    {
        $package = self::packageByCode('PKG-WED-GOLD');
        $addon = self::addons()->firstWhere('id', 4);
        $addon->pivot = self::object([
            'price' => $addon->price,
            'nama_addon' => $addon->name,
            'qty' => 1,
            'nama_option' => null,
            'subtotal' => $addon->price
        ]);
        return collect([
            self::object([
                'booking_code' => 'LYB-DEMO-001',
                'package' => $package,
                'booking_date' => Carbon::today()->addDays(14),
                'tanggal_acara' => Carbon::today()->addDays(14),
                'created_at' => Carbon::now()->subDays(2),
                'user' => self::object([
                    'name' => 'Demo User',
                    'phone' => '08123456789',
                    'instagram' => 'demo_user',
                    'address' => 'Jl. Merdeka No. 10'
                ]),
                'latestCancellationRequest' => null,
                'softlens' => true,
                'addons' => collect([$addon]),
                'payments' => collect([self::object(['amount' => $package->dp_amount, 'proof_image' => null, 'status' => 'pending', 'paid_at' => Carbon::now()])]),
                'subtotal' => $package->price,
                'addon_total' => $addon->price,
                'total_price' => $package->price + $addon->price,
                'dp_amount' => $package->dp_amount,
                'total_dibayar' => 0,
                'remaining_payment' => $package->price + $addon->price - $package->dp_amount,
                'sisa_pelunasan' => $package->price + $addon->price,
                'status' => 'menunggu_konfirmasi',
                'status_layanan' => 'pending',
                'payment_status' => 'dp_diupload',
                'notes' => 'Contoh booking demo untuk melihat tampilan detail.',
            ]),
        ]);
    }

    public static function sessionBookings(): Collection
    {
        $items = collect(session('preview_bookings', []))->map(function ($item) {
            $package = self::packageById((int) $item['package_id']) ?? self::packageByCode($item['package_code']);
            $addons = collect($item['addons'] ?? [])->map(function ($addon) {
                $object = self::object($addon);
                $object->pivot = self::object([
                    'price' => $addon['price'] ?? 0,
                    'nama_addon' => $addon['name'] ?? null,
                    'qty' => 1,
                    'nama_option' => null,
                    'subtotal' => $addon['price'] ?? 0
                ]);
                return $object;
            });
            return self::object([
                'booking_code' => $item['booking_code'],
                'package' => $package,
                'booking_date' => Carbon::parse($item['booking_date']),
                'tanggal_acara' => Carbon::parse($item['booking_date']),
                'created_at' => Carbon::now(),
                'user' => auth()->user() ?? self::object([
                    'name' => 'Guest Customer',
                    'phone' => '-',
                    'instagram' => null,
                    'address' => null,
                ]),
                'latestCancellationRequest' => null,
                'softlens' => (bool) $item['softlens'],
                'addons' => $addons,
                'payments' => collect($item['proof_uploaded'] ? [self::object(['amount' => $item['dp_amount'], 'proof_image' => null, 'status' => 'pending', 'paid_at' => Carbon::now()])] : []),
                'subtotal' => $item['subtotal'],
                'addon_total' => $item['addon_total'],
                'total_price' => $item['total_price'],
                'dp_amount' => $item['dp_amount'],
                'total_dibayar' => 0,
                'remaining_payment' => $item['remaining_payment'],
                'sisa_pelunasan' => $item['total_price'],
                'status' => $item['proof_uploaded'] ? 'menunggu_konfirmasi' : 'pending',
                'status_layanan' => 'pending',
                'payment_status' => $item['proof_uploaded'] ? 'dp_diupload' : 'belum_bayar',
                'notes' => $item['notes'] ?? null,
            ]);
        });

        return $items->isEmpty() ? self::demoBookings() : $items->concat(self::demoBookings())->values();
    }
}
