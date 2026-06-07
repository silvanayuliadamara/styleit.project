# Refactoring Documentation

Dokumen ini mencatat seluruh keputusan refactoring yang dilakukan selama pengembangan proyek StyleIt, beserta alasan, pendekatan, dan hasil yang dicapai.

---

## Daftar Isi

- [Apa itu Refactoring?](#apa-itu-refactoring)
- [Kapan Refactoring Dilakukan?](#kapan-refactoring-dilakukan)
- [Kandidat Refactoring](#kandidat-refactoring)
- [Riwayat Refactoring](#riwayat-refactoring)

---

## Apa itu Refactoring?

Refactoring adalah proses memperbaiki struktur internal kode tanpa mengubah perilaku eksternal aplikasi. Tujuannya adalah membuat kode lebih mudah dibaca, dipelihara, dan dikembangkan lebih lanjut.

**Prinsip yang digunakan dalam proyek ini:**
- **DRY** (Don't Repeat Yourself) — menghindari duplikasi logika
- **SRP** (Single Responsibility Principle) — setiap class/fungsi punya satu tanggung jawab
- **KISS** (Keep It Simple, Stupid) — solusi sesederhana mungkin

---

## Kapan Refactoring Dilakukan?

Refactoring dilakukan ketika:
1. Ditemukan duplikasi kode di lebih dari dua tempat
2. Sebuah controller memiliki lebih dari satu tanggung jawab bisnis
3. Validasi form ditulis langsung di controller (bukan di FormRequest)
4. Logika bisnis kompleks ditulis langsung di controller (bukan di Service)
5. Ada feedback dari code review tim

---

## Kandidat Refactoring

Berikut daftar area kode yang diidentifikasi perlu direfactor, beserta statusnya.

---

### 1. Ekstraksi FormRequest untuk Validasi

**Kondisi saat ini:**
Validasi input pada `AuthController`, `CartController`, dan `CheckoutController` masih ditulis langsung di dalam method controller menggunakan `$request->validate([...])`.

- `AuthController::login()` — 2 rule + 3 pesan kustom
- `AuthController::register()` — 6 rule + 12 pesan kustom
- `CartController::store()` — 6 rule termasuk nested `addons.*`
- `CheckoutController::store()` — 2 rule dengan validasi file upload

**Target refactoring:**
Pindahkan ke dedicated FormRequest class agar controller lebih bersih dan validasi bisa di-reuse.

```
app/Http/Requests/
├── Auth/
│   ├── LoginRequest.php
│   └── RegisterRequest.php
├── Cart/
│   └── StoreCartRequest.php
└── Checkout/
    └── StoreCheckoutRequest.php
```

**Contoh sebelum refactoring:**
```php
// AuthController.php
public function register(Request $request)
{
    $validated = $request->validate([
        'name'                  => ['required', 'string', 'max:255'],
        'phone'                 => ['required', 'string', 'max:20', 'unique:users,phone'],
        'instagram'             => ['nullable', 'string', 'max:50'],
        'email'                 => ['required', 'email', 'unique:users,email'],
        'password'              => ['required', 'min:8'],
        'password_confirmation' => ['required', 'same:password'],
    ], [
        'name.required'                  => 'Nama lengkap wajib diisi.',
        // ... 11 pesan kustom lainnya
    ]);
    // ...
}
```

**Contoh setelah refactoring:**
```php
// AuthController.php
public function register(RegisterRequest $request)
{
    User::create([
        ...$request->validated(),
        'role' => 'customer',
    ]);
    return redirect()->route('login')->with('success', 'Pendaftaran berhasil. Silakan masuk ke akun Anda.');
}
```

**Prioritas:** 🔴 Tinggi
**Status:** ⬜ Belum dikerjakan

---

### 2. Migrasi PreviewData dari Session ke Database

**Kondisi saat ini:**
Hampir seluruh fitur publik dan customer menggunakan `App\Support\PreviewData` — sebuah class statis yang mengembalikan data hardcoded sebagai `stdClass`. Ini mencakup:

- Data kategori, paket, addon, dan portofolio (hardcoded di `PreviewData`)
- Kalender ketersediaan tanggal booking menggunakan logika hardcoded di `PreviewData::calendarFor()`, padahal model `BlockedDate` sudah tersedia
- Data portofolio hardcoded di `PreviewData::portfolio()`, padahal model `PortfolioItem` sudah tersedia
- Data booking disimpan di session (`preview_bookings`) dan hilang saat session habis
- `CustomerDashboardController`, `CustomerBookingController`, `CartController`, `CheckoutController`, dan `PublicPageController` semuanya bergantung pada `PreviewData`

Seluruh model database (`Booking`, `ServicePackage`, `ServiceCategory`, `Addon`, `BlockedDate`, `PortfolioItem`) beserta relasinya sudah tersedia dan siap dipakai.

**Target refactoring:**
Ganti `PreviewData` dengan query Eloquent ke database sesungguhnya.

```php
// Sebelum — PublicPageController.php
public function layanan()
{
    $categories = PreviewData::categories(); // data hardcoded
    return view('layanan.index', compact('categories'));
}

// Sesudah
public function layanan()
{
    $categories = ServiceCategory::with('packages')->orderBy('sort_order')->get();
    return view('layanan.index', compact('categories'));
}
```

```php
// Sebelum — PublicPageController.php
public function portofolio()
{
    $items = PreviewData::portfolio(); // 6 item hardcoded
    return view('portofolio', compact('items'));
}

// Sesudah
public function portofolio()
{
    $items = PortfolioItem::orderBy('sort_order')->get();
    return view('portofolio', compact('items'));
}
```

```php
// Sebelum — PublicPageController.php
public function paket(string $code)
{
    $calendar = PreviewData::calendarFor($package); // tanggal blocked hardcoded
    // ...
}

// Sesudah
public function paket(string $code)
{
    $blockedDates = BlockedDate::pluck('blocked_date')->map->toDateString()->toArray();
    // gunakan $blockedDates untuk membangun kalender ketersediaan
}
```

```php
// Sebelum — CheckoutController.php
session(['preview_bookings' => $bookings]); // data hilang saat logout

// Sesudah
$booking = Booking::create([
    'booking_code'      => 'LYB-' . strtoupper(Str::random(8)),
    'user_id'           => auth()->id(),
    'package_id'        => $package->id,
    'booking_date'      => $validated['booking_date'],
    // ...
]);
```

**Prioritas:** 🔴 Tinggi
**Status:** ⬜ Belum dikerjakan

---

### 3. Ekstraksi BookingService untuk Logika Kalkulasi

**Kondisi saat ini:**
`CartController::store()` melakukan terlalu banyak hal sekaligus: validasi, pencarian paket, pengambilan addon, kalkulasi harga (subtotal, addon total, DP, sisa bayar), hingga pembentukan array session. Method ini akan semakin kompleks setelah migrasi ke database.

**Target refactoring:**
Pisahkan logika kalkulasi ke `BookingService` agar `CartController` hanya bertanggung jawab menerima request dan merespons.

```
app/Services/
└── BookingService.php
```

**Contoh sebelum refactoring:**
```php
// CartController.php — store()
$package   = PreviewData::packageById((int) $validated['package_id']);
$addons    = PreviewData::addons()->whereIn('id', ...)->values();
$addonTotal = $addons->sum('price');
$subtotal  = $package->price;
$total     = $subtotal + $addonTotal;

$cart[] = [
    'key'               => uniqid('cart_', true),
    'package_id'        => $package->id,
    // ... 11 field lainnya
];
session(['cart' => $cart]);
```

**Contoh setelah refactoring:**
```php
// CartController.php
public function store(StoreCartRequest $request)
{
    $this->bookingService->addToCart($request->validated());
    return redirect()->route('customer.cart.index')->with('success', 'Paket berhasil ditambahkan ke keranjang.');
}
```

**Prioritas:** 🔴 Tinggi (dikerjakan bersamaan dengan kandidat #2)
**Status:** ⬜ Belum dikerjakan

---

### 4. Grouping Route dengan Prefix & Middleware

**Kondisi saat ini:**
Berdasarkan namespace controller (`App\Http\Controllers\Customer\*`), route sudah dikelompokkan per role. Perlu dipastikan pengelompokan di `routes/web.php` konsisten menggunakan `prefix`, `name`, dan `middleware` agar tidak ada duplikasi pengecekan `auth` per-route.

**Target refactoring:**
```php
// Sesudah
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::resource('cart', CartController::class)->only(['index', 'store', 'destroy']);
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
    });
```

**Prioritas:** 🟡 Sedang
**Status:** ⬜ Belum dikerjakan

---

### 5. Penggunaan Route Model Binding

**Kondisi saat ini:**
`CustomerBookingController::show()` menggunakan parameter `string $booking` dan mencari data secara manual via `PreviewData::sessionBookings()->firstWhere('booking_code', $booking)`. Setelah migrasi ke database, ini perlu diganti dengan Route Model Binding untuk memanfaatkan `booking_code` sebagai route key.

**Contoh sebelum refactoring:**
```php
public function show(string $booking)
{
    $booking = PreviewData::sessionBookings()->firstWhere('booking_code', $booking);
    abort_if(! $booking, Response::HTTP_NOT_FOUND);
    return view('customer.bookings.show', compact('booking'));
}
```

**Contoh setelah refactoring:**
```php
// Booking.php — tambahkan getRouteKeyName()
public function getRouteKeyName(): string
{
    return 'booking_code';
}

// CustomerBookingController.php
public function show(Booking $booking)
{
    abort_if($booking->user_id !== auth()->id(), 403);
    return view('customer.bookings.show', compact('booking'));
}
```

**Prioritas:** 🟢 Rendah (bergantung pada selesainya kandidat #2)
**Status:** ⬜ Belum dikerjakan

---

## Riwayat Refactoring

| Tanggal | Area | Deskripsi | Dilakukan oleh |
|---------|------|-----------|----------------|
| — | — | Belum ada entri refactoring final pada dokumen ini. | — |

> Setiap refactoring yang selesai dikerjakan wajib didokumentasikan di tabel ini beserta tanggal, area yang diubah, deskripsi singkat perubahan, dan nama anggota yang melakukan.
