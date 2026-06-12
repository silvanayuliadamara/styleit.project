# Dependency Documentation

Dokumen ini berisi daftar dependency/package yang digunakan dan direncanakan pada project StyleIt. Dependency digunakan untuk mendukung kebutuhan sistem, seperti autentikasi, validasi, upload file, invoice PDF, payment gateway, laporan, dan pengelolaan gambar.

---

## 1. Ringkasan Dependency

|  No | Package / Dependency        | Fungsi                                           | Alasan Digunakan                                                                | Versi            | Risiko                                                                   |
| --: | --------------------------- | ------------------------------------------------ | ------------------------------------------------------------------------------- | ---------------- | ------------------------------------------------------------------------ |
|   1 | Laravel Authentication      | Mengelola login, logout, session, dan user aktif | Dibutuhkan untuk akses customer, admin, dan owner                               | Bawaan Laravel   | Konfigurasi guard atau session harus tepat                               |
|   2 | Laravel Validation          | Memvalidasi input form                           | Mencegah data kosong, format salah, atau data tidak valid                       | Bawaan Laravel   | Rule validasi harus sesuai kebutuhan form                                |
|   3 | Laravel File Storage        | Mengelola upload dan penyimpanan file            | Dibutuhkan untuk gambar portofolio, logo, banner, sertifikat, dan paket layanan | Bawaan Laravel   | Permission storage harus diperhatikan                                    |
|   4 | barryvdh/laravel-dompdf     | Generate PDF dari Blade Laravel                  | Dibutuhkan untuk invoice atau bukti booking PDF                                 | ^3.1             | Perlu kompatibilitas dengan Laravel dan dapat menambah ukuran dependency |
|   5 | Spatie Laravel Permission   | Mengelola role dan permission                    | Dibutuhkan untuk role customer, admin, dan owner                                | ^6.x             | Perlu konfigurasi role yang rapi                                         |
|   6 | Midtrans PHP                | Integrasi payment gateway                        | Dibutuhkan untuk pembayaran DP online                                           | Rencana          | Perlu konfigurasi API key dan keamanan transaksi                         |
|   7 | Laravel Excel               | Export/import data Excel                         | Dibutuhkan untuk laporan keuangan dan rekap transaksi                           | Rencana/Opsional | Perlu maintenance package dan format data                                |
|   8 | Intervention Image          | Resize, crop, dan kompres gambar                 | Dibutuhkan untuk optimasi gambar upload                                         | Rencana/Opsional | Perlu konfigurasi ukuran gambar                                          |
|   9 | WhatsApp Integration        | Mengarahkan user ke WhatsApp                     | Dibutuhkan untuk komunikasi customer dengan pihak usaha                         | Link/Integrasi   | Nomor WhatsApp harus valid                                               |
|  10 | Laravel Mail / Notification | Mengirim email atau notifikasi                   | Rencana pengembangan untuk notifikasi booking/invoice                           | Bawaan Laravel   | Perlu konfigurasi mail server                                            |

---

## 2. Dependency yang Sudah Diimplementasikan

### 2.1 barryvdh/laravel-dompdf

#### Fungsi

`barryvdh/laravel-dompdf` adalah package Laravel yang digunakan untuk membuat file PDF dari tampilan Blade.

#### Alasan Digunakan

Project StyleIt memiliki kebutuhan fitur invoice atau bukti booking. Dengan DomPDF, invoice dapat dikembangkan agar bisa dicetak atau diunduh dalam bentuk PDF.

#### Cara Install

```bash
composer require barryvdh/laravel-dompdf --no-audit
```

#### Perubahan File

Setelah dependency DomPDF ditambahkan, file yang berubah adalah:

```text
composer.json
composer.lock
```

Pada `composer.json`, package yang ditambahkan adalah:

```json
"barryvdh/laravel-dompdf": "^3.1"
```

#### Dependency Pendukung

Saat DomPDF diinstall, terdapat beberapa package pendukung yang ikut terinstall, antara lain:

```text
barryvdh/laravel-dompdf
dompdf/dompdf
dompdf/php-font-lib
dompdf/php-svg-lib
masterminds/html5
sabberworm/php-css-parser
thecodingmachine/safe
```

#### Dampak pada Project

- Project dapat dikembangkan untuk generate invoice PDF.
- File `composer.json` dan `composer.lock` bertambah.
- Anggota kelompok lain dapat menjalankan `composer install` agar dependency terpasang sesuai versi yang sama.
- Ukuran dependency project bertambah karena ada package pendukung DomPDF.

#### Risiko

- Perlu memastikan package kompatibel dengan versi Laravel.
- Generate PDF dapat memengaruhi performa jika data terlalu besar.
- Package perlu diperbarui jika ada update keamanan atau kompatibilitas.

---

### 2.2 spatie/laravel-permission

#### Fungsi

`spatie/laravel-permission` adalah package Laravel yang digunakan untuk mengelola role dan permission pengguna.

#### Alasan Digunakan

Project StyleIt memiliki tiga role pengguna, yaitu customer, admin, dan owner. Setiap role memiliki hak akses yang berbeda terhadap halaman dan fitur aplikasi.

#### Cara Install

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

#### Perubahan File

Setelah dependency Spatie ditambahkan, file yang berubah adalah:

```text
composer.json
composer.lock
config/permission.php
database/migrations/xxxx_xx_xx_create_permission_tables.php
app/Models/User.php
database/seeders/RoleSeeder.php
app/Http/Controllers/AuthController.php
routes/web.php
```

Pada `composer.json`, package yang ditambahkan adalah:

```json
"spatie/laravel-permission": "^6.0"
```

#### Tabel Database yang Ditambahkan

Setelah migration dijalankan, tabel berikut ditambahkan ke database:

```text
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions
```

#### Perubahan Kode

**`app/Models/User.php`** — tambahkan trait `HasRoles`:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
}
```

**`database/seeders/RoleSeeder.php`** — seeder untuk membuat role:

```php
use Spatie\Permission\Models\Role;

public function run(): void
{
    Role::firstOrCreate(['name' => 'customer']);
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'owner']);
}
```

**`app/Http/Controllers/AuthController.php`** — assign role saat register:

```php
$user = User::create([...]);
$user->assignRole('customer');
```

**`routes/web.php`** — proteksi route dengan middleware role:

```php
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    // route customer
});
```

#### Dampak pada Project

- Role customer, admin, dan owner dapat dikelola langsung dari database.
- Akses halaman otomatis dibatasi berdasarkan role user yang login.
- Setiap user yang register otomatis mendapat role customer.
- Tabel `model_has_roles` mencatat relasi antara user dan role-nya.

#### Risiko

- Role harus sudah ada di database sebelum `assignRole()` dipanggil — pastikan `RoleSeeder` sudah dijalankan.
- Jika salah konfigurasi middleware, user dapat mengakses halaman yang bukan haknya.
- Cache permission perlu dibersihkan jika ada perubahan role: `php artisan optimize:clear`.

---

## 3. Dependency Bawaan Laravel

### 3.1 Laravel Authentication

#### Fungsi

Digunakan untuk mengelola proses login, logout, session, dan pengecekan user yang sedang aktif.

#### Alasan Digunakan

Aplikasi StyleIt memiliki beberapa jenis pengguna, yaitu customer, admin, dan owner. Setiap role membutuhkan akses yang berbeda.

#### Dampak pada Project

- User dapat masuk ke sistem.
- Sistem dapat membatasi akses halaman berdasarkan user yang login.
- Dashboard dapat diarahkan sesuai kebutuhan role.

#### Risiko

- Konfigurasi autentikasi harus sesuai.
- Akses halaman perlu dilindungi dengan middleware.

---

### 3.2 Laravel Validation

#### Fungsi

Digunakan untuk memvalidasi input form sebelum data diproses atau disimpan.

#### Alasan Digunakan

Validasi dibutuhkan pada form login, register, booking, checkout, pembayaran DP, portofolio, dan form lainnya.

#### Dampak pada Project

- Data yang masuk lebih aman dan sesuai format.
- Mengurangi risiko data kosong atau tidak valid.
- Membantu menjaga kualitas data pada database.

#### Risiko

- Rule validasi harus disesuaikan dengan kebutuhan tiap form.
- Validasi yang terlalu ketat dapat menghambat user jika tidak dijelaskan dengan baik.

---

### 3.3 Laravel File Storage

#### Fungsi

Digunakan untuk menyimpan dan mengelola file upload.

#### Alasan Digunakan

Project StyleIt membutuhkan upload file seperti logo, banner, foto paket, portofolio, sertifikat, dan kemungkinan foto ulasan.

#### Dampak pada Project

- File upload dapat dikelola lebih terstruktur.
- Penyimpanan file dapat menggunakan disk lokal atau layanan lain.
- File lebih mudah dipanggil pada tampilan aplikasi.

#### Risiko

- Permission folder storage harus benar.
- File berukuran besar dapat memengaruhi penyimpanan.
- Perlu validasi jenis dan ukuran file.

---

## 4. Dependency Rencana Pengembangan

### 4.1 Midtrans PHP

#### Fungsi

Digunakan untuk integrasi payment gateway Midtrans.

#### Alasan Digunakan

Project StyleIt memiliki kebutuhan pembayaran DP online. Midtrans dapat digunakan untuk memproses pembayaran secara digital.

#### Cara Install

```bash
composer require midtrans/midtrans-php
```

#### Dampak pada Project

- Customer dapat melakukan pembayaran DP online.
- Sistem dapat menerima status pembayaran dari payment gateway.
- Invoice dapat dikaitkan dengan status transaksi.

#### Risiko

- Perlu API key dan konfigurasi environment yang aman.
- Perlu menangani callback/notifikasi pembayaran.
- Perlu pengujian sandbox sebelum production.

---

### 4.2 Laravel Excel

#### Fungsi

Digunakan untuk export dan import data Excel.

#### Alasan Digunakan

Owner dapat membutuhkan laporan booking, pembayaran, invoice, dan transaksi dalam bentuk Excel.

#### Cara Install

```bash
composer require maatwebsite/excel
```

#### Dampak pada Project

- Data laporan dapat diekspor.
- Owner lebih mudah menyimpan rekap transaksi.
- Mendukung kebutuhan laporan keuangan.

#### Risiko

- Package perlu disesuaikan dengan versi Laravel.
- Export data besar dapat memengaruhi performa.
- Format laporan perlu dirancang dengan rapi.

---

### 4.3 Intervention Image

#### Fungsi

Digunakan untuk memproses gambar, seperti resize, crop, dan kompres.

#### Alasan Digunakan

Project StyleIt memiliki banyak file gambar seperti logo, banner, portofolio, sertifikat, dan foto paket layanan.

#### Cara Install

```bash
composer require intervention/image-laravel
```

#### Dampak pada Project

- Gambar upload dapat diperkecil ukurannya.
- Tampilan website lebih ringan.
- Penyimpanan file menjadi lebih efisien.

#### Risiko

- Perlu konfigurasi ukuran gambar yang sesuai.
- Hasil kompresi harus tetap menjaga kualitas visual.
- Perlu validasi file upload.

---

### 4.4 WhatsApp Integration

#### Fungsi

Digunakan untuk mengarahkan user ke WhatsApp melalui link atau tombol.

#### Alasan Digunakan

Customer membutuhkan media komunikasi cepat dengan pihak usaha.

#### Contoh Link

```text
https://wa.me/6281234567890?text=Halo%20saya%20ingin%20bertanya%20tentang%20booking
```

#### Dampak pada Project

- Customer dapat menghubungi pihak usaha dengan mudah.
- Cocok untuk konfirmasi booking atau pertanyaan layanan.
- Tidak membutuhkan package Composer tambahan.

#### Risiko

- Nomor WhatsApp harus valid.
- Format pesan perlu disusun dengan jelas.
- Jika nomor berubah, link harus diperbarui.

---

### 4.5 Laravel Mail / Notification

#### Fungsi

Digunakan untuk mengirim email atau notifikasi ke pengguna.

#### Alasan Digunakan

Rencana pengembangan untuk notifikasi booking, konfirmasi pembayaran DP, dan pengiriman invoice ke email customer.

#### Cara Install

Sudah tersedia sebagai fitur bawaan Laravel, tidak perlu install package tambahan. Cukup konfigurasi di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=email@gmail.com
MAIL_FROM_NAME="Lisa Yuli Belti"
```

#### Dampak pada Project

- Customer dapat menerima notifikasi booking melalui email.
- Invoice dapat dikirim langsung ke email customer.
- Konfirmasi pembayaran DP dapat diotomatisasi.

#### Risiko

- Perlu konfigurasi mail server yang benar.
- Jika menggunakan Gmail, perlu mengaktifkan App Password.
- Email dapat masuk ke folder spam jika tidak dikonfigurasi dengan benar.

---

## 5. Kendala Implementasi Dependency

### 5.1 barryvdh/laravel-dompdf

Pada proses implementasi DomPDF, salah satu laptop anggota mengalami kendala SSL certificate Composer. Error yang muncul berkaitan dengan `curl error 60`, sehingga Composer tidak dapat mengakses Packagist melalui koneksi HTTPS.

Solusi yang dilakukan:

- Mengecek konfigurasi Composer.
- Mengecek konfigurasi PHP dan file certificate.
- Menjalankan instalasi dependency pada laptop anggota lain yang Composer-nya berjalan normal.
- Melakukan commit perubahan `composer.json` dan `composer.lock`.
- Anggota lain melakukan `git pull` dan menjalankan `composer install`.

### 5.2 spatie/laravel-permission

Pada proses implementasi Spatie Laravel Permission, ditemukan beberapa kendala berikut:

- RoleSeeder tidak otomatis membuat role — seeder harus dijalankan manual setelah migration menggunakan `php artisan db:seed --class=RoleSeeder` atau melalui tinker.
- Error `assignRole()` muncul karena trait `HasRoles` belum ditambahkan ke model `User`.
- Error `RoleDoesNotExist` muncul karena tabel `roles` masih kosong saat register pertama kali dijalankan.

Solusi yang dilakukan:

- Menambahkan `use HasRoles` di dalam `class User` pada `app/Models/User.php`.
- Membuat role langsung melalui `php artisan tinker` dengan perintah `Role::create()`.
- Memastikan `RoleSeeder` dijalankan sebelum fitur register digunakan.

---

## 6. Kesimpulan

Dependency pada project StyleIt digunakan untuk mendukung kebutuhan sistem agar lebih mudah dikembangkan. Pada tahap ini, dependency yang sudah berhasil diimpleme# Dependency Documentation

Dokumen ini berisi daftar dependency/package yang digunakan dan direncanakan pada project StyleIt. Dependency digunakan untuk mendukung kebutuhan sistem, seperti autentikasi, validasi, upload file, invoice PDF, payment gateway, laporan, dan pengelolaan gambar.

---

## 1. Ringkasan Dependency

|  No | Package / Dependency        | Fungsi                                           | Alasan Digunakan                                                                | Versi            | Risiko                                                                   |
| --: | --------------------------- | ------------------------------------------------ | ------------------------------------------------------------------------------- | ---------------- | ------------------------------------------------------------------------ |
|   1 | Laravel Authentication      | Mengelola login, logout, session, dan user aktif | Dibutuhkan untuk akses customer, admin, dan owner                               | Bawaan Laravel   | Konfigurasi guard atau session harus tepat                               |
|   2 | Laravel Validation          | Memvalidasi input form                           | Mencegah data kosong, format salah, atau data tidak valid                       | Bawaan Laravel   | Rule validasi harus sesuai kebutuhan form                                |
|   3 | Laravel File Storage        | Mengelola upload dan penyimpanan file            | Dibutuhkan untuk gambar portofolio, logo, banner, sertifikat, dan paket layanan | Bawaan Laravel   | Permission storage harus diperhatikan                                    |
|   4 | barryvdh/laravel-dompdf     | Generate PDF dari Blade Laravel                  | Dibutuhkan untuk invoice atau bukti booking PDF                                 | ^3.1             | Perlu kompatibilitas dengan Laravel dan dapat menambah ukuran dependency |
|   5 | Spatie Laravel Permission   | Mengelola role dan permission                    | Dibutuhkan untuk role customer, admin, dan owner                                | ^6.x             | Perlu konfigurasi role yang rapi                                         |
|   6 | Midtrans PHP                | Integrasi payment gateway                        | Dibutuhkan untuk pembayaran DP online via Snap                                  | ^2.6             | Perlu konfigurasi API key dan keamanan transaksi                         |
|   7 | Laravel Excel               | Export/import data Excel                         | Dibutuhkan untuk laporan keuangan dan rekap transaksi                           | Rencana/Opsional | Perlu maintenance package dan format data                                |
|   8 | Intervention Image          | Resize, crop, dan kompres gambar                 | Dibutuhkan untuk optimasi gambar upload                                         | Rencana/Opsional | Perlu konfigurasi ukuran gambar                                          |
|   9 | WhatsApp Integration        | Mengarahkan user ke WhatsApp                     | Dibutuhkan untuk komunikasi customer dengan pihak usaha                         | Link/Integrasi   | Nomor WhatsApp harus valid                                               |
|  10 | Laravel Mail / Notification | Mengirim email atau notifikasi                   | Rencana pengembangan untuk notifikasi booking/invoice                           | Bawaan Laravel   | Perlu konfigurasi mail server                                            |

---

## 2. Dependency yang Sudah Diimplementasikan

### 2.1 barryvdh/laravel-dompdf

#### Fungsi

`barryvdh/laravel-dompdf` adalah package Laravel yang digunakan untuk membuat file PDF dari tampilan Blade.

#### Alasan Digunakan

Project StyleIt memiliki kebutuhan fitur invoice atau bukti booking. Dengan DomPDF, invoice dapat dikembangkan agar bisa dicetak atau diunduh dalam bentuk PDF.

#### Cara Install

```bash
composer require barryvdh/laravel-dompdf --no-audit
```

#### Perubahan File

Setelah dependency DomPDF ditambahkan, file yang berubah adalah:

```text
composer.json
composer.lock
```

Pada `composer.json`, package yang ditambahkan adalah:

```json
"barryvdh/laravel-dompdf": "^3.1"
```

#### Dependency Pendukung

Saat DomPDF diinstall, terdapat beberapa package pendukung yang ikut terinstall, antara lain:

```text
barryvdh/laravel-dompdf
dompdf/dompdf
dompdf/php-font-lib
dompdf/php-svg-lib
masterminds/html5
sabberworm/php-css-parser
thecodingmachine/safe
```

#### Dampak pada Project

- Project dapat dikembangkan untuk generate invoice PDF.
- File `composer.json` dan `composer.lock` bertambah.
- Anggota kelompok lain dapat menjalankan `composer install` agar dependency terpasang sesuai versi yang sama.
- Ukuran dependency project bertambah karena ada package pendukung DomPDF.

#### Risiko

- Perlu memastikan package kompatibel dengan versi Laravel.
- Generate PDF dapat memengaruhi performa jika data terlalu besar.
- Package perlu diperbarui jika ada update keamanan atau kompatibilitas.

---

### 2.2 spatie/laravel-permission

#### Fungsi

`spatie/laravel-permission` adalah package Laravel yang digunakan untuk mengelola role dan permission pengguna.

#### Alasan Digunakan

Project StyleIt memiliki tiga role pengguna, yaitu customer, admin, dan owner. Setiap role memiliki hak akses yang berbeda terhadap halaman dan fitur aplikasi.

#### Cara Install

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

#### Perubahan File

Setelah dependency Spatie ditambahkan, file yang berubah adalah:

```text
composer.json
composer.lock
config/permission.php
database/migrations/xxxx_xx_xx_create_permission_tables.php
app/Models/User.php
database/seeders/RoleSeeder.php
app/Http/Controllers/AuthController.php
routes/web.php
```

Pada `composer.json`, package yang ditambahkan adalah:

```json
"spatie/laravel-permission": "^6.0"
```

#### Tabel Database yang Ditambahkan

Setelah migration dijalankan, tabel berikut ditambahkan ke database:

```text
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions
```

#### Perubahan Kode

**`app/Models/User.php`** — tambahkan trait `HasRoles`:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
}
```

**`database/seeders/RoleSeeder.php`** — seeder untuk membuat role:

```php
use Spatie\Permission\Models\Role;

public function run(): void
{
    Role::firstOrCreate(['name' => 'customer']);
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'owner']);
}
```

**`app/Http/Controllers/AuthController.php`** — assign role saat register:

```php
$user = User::create([...]);
$user->assignRole('customer');
```

**`routes/web.php`** — proteksi route dengan middleware role:

```php
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    // route customer
});
```

#### Dampak pada Project

- Role customer, admin, dan owner dapat dikelola langsung dari database.
- Akses halaman otomatis dibatasi berdasarkan role user yang login.
- Setiap user yang register otomatis mendapat role customer.
- Tabel `model_has_roles` mencatat relasi antara user dan role-nya.

#### Risiko

- Role harus sudah ada di database sebelum `assignRole()` dipanggil — pastikan `RoleSeeder` sudah dijalankan.
- Jika salah konfigurasi middleware, user dapat mengakses halaman yang bukan haknya.
- Cache permission perlu dibersihkan jika ada perubahan role: `php artisan optimize:clear`.

---

### 2.3 midtrans/midtrans-php

#### Fungsi

`midtrans/midtrans-php` adalah SDK resmi Midtrans untuk PHP yang digunakan untuk integrasi payment gateway. Dipakai untuk generate Snap token dan memvalidasi notifikasi webhook dari Midtrans.

#### Alasan Digunakan

Project StyleIt membutuhkan pembayaran DP secara online. Dengan Midtrans Snap, customer dapat melakukan pembayaran DP melalui berbagai metode (Virtual Account, QRIS, e-wallet) tanpa perlu upload bukti transfer manual. Status pembayaran diupdate otomatis via webhook callback dari Midtrans.

#### Cara Install

```bash
composer require midtrans/midtrans-php
```

#### Perubahan File

Setelah dependency Midtrans ditambahkan, file yang berubah atau ditambahkan adalah:

```text
composer.json
composer.lock
config/midtrans.php              ← file baru
.env                             ← tambah variabel Midtrans
.env.example                     ← tambah placeholder Midtrans
app/Services/MidtransService.php ← file baru
app/Http/Controllers/customer/MidtransController.php ← file baru
routes/web.php                   ← tambah route snap-token dan webhook
bootstrap/app.php                ← exclude webhook dari CSRF
```

Pada `composer.json`, package yang ditambahkan adalah:

```json
"midtrans/midtrans-php": "^2.6"
```

#### Konfigurasi

**`config/midtrans.php`** — file konfigurasi baru:

```php
return [
    'merchant_id'   => env('MIDTRANS_MERCHANT_ID'),
    'client_key'    => env('MIDTRANS_CLIENT_KEY'),
    'server_key'    => env('MIDTRANS_SERVER_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized'  => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds'        => env('MIDTRANS_IS_3DS', true),
];
```

**`.env`** — tambahkan variabel berikut (gunakan Sandbox key untuk development):

```env
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=Mid-client-xxxxx
MIDTRANS_SERVER_KEY=Mid-server-xxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

> ⚠️ **Jangan commit `.env` asli ke git.** Gunakan Sandbox keys (`MIDTRANS_IS_PRODUCTION=false`) selama development.

#### Perubahan Kode

**`app/Services/MidtransService.php`** — generate Snap token:

```php
use Midtrans\Config;
use Midtrans\Snap;

Config::$serverKey = config('midtrans.server_key');
Config::$isProduction = config('midtrans.is_production');

$token = Snap::getSnapToken([
    'transaction_details' => [
        'order_id'     => $booking->booking_code . '-' . time(),
        'gross_amount' => (int) $booking->dp_amount,
    ],
    'customer_details' => [
        'first_name' => $booking->user->name,
        'email'      => $booking->user->email,
    ],
    'item_details' => [...],
]);
```

**`app/Http/Controllers/customer/MidtransController.php`** — dua method utama:

```php
// GET /customer/checkout/{booking}/snap-token
public function getSnapToken(Booking $booking) { ... }

// POST /midtrans/notification  (webhook publik dari Midtrans)
public function notification(Request $request) { ... }
```

**`routes/web.php`** — route yang ditambahkan:

```php
// Di dalam group customer (auth)
Route::get('/checkout/{booking}/snap-token', [MidtransController::class, 'getSnapToken'])
    ->name('checkout.snap-token');

// Di luar group auth (webhook publik)
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])
    ->name('midtrans.notification');
```

**`bootstrap/app.php`** — exclude webhook dari CSRF:

```php
$middleware->validateCsrfTokens(except: [
    'midtrans/notification',
]);
```

#### Alur Pembayaran DP

```
Customer checkout
  └─ Booking dibuat (status: pending, payment_status: belum_bayar)
       └─ Customer klik "Bayar DP" di halaman detail booking
            └─ Frontend request snap-token ke backend
                 └─ MidtransService::getSnapToken() → Snap token
                      └─ Snap.js popup pembayaran (VA/QRIS/e-wallet)
                           ├─ Berhasil → webhook /midtrans/notification
                           │    └─ booking diupdate: payment_status=dp_diterima, status=diterima
                           └─ Gagal/expired → webhook
                                └─ booking diupdate: payment_status=belum_bayar, status=dibatalkan
```

#### Dampak pada Project

- Customer dapat membayar DP secara online tanpa upload bukti transfer manual.
- Status booking diupdate otomatis via webhook saat pembayaran berhasil.
- Admin tidak perlu konfirmasi DP secara manual.
- Pelunasan tetap dikonfirmasi manual oleh owner (klik "Konfirmasi Lunas" di dashboard).

#### Progress Implementasi

| Tahap | File | Status |
|---|---|---|
| Install SDK | `composer.json`, `composer.lock` | ✅ Selesai |
| Config | `config/midtrans.php`, `.env` | ✅ Selesai |
| Service (Snap token) | `app/Services/MidtransService.php` | ✅ Selesai |
| Controller (token + webhook) | `app/Http/Controllers/customer/MidtransController.php` | ✅ Selesai |
| Routes & CSRF exclude | `routes/web.php`, `bootstrap/app.php` | ✅ Selesai |
| Update CheckoutController | `app/Http/Controllers/customer/CheckoutController.php` | ⏳ Pending |
| View checkout + Snap.js popup | `resources/views/customer/...` | ⏳ Pending |
| Konfirmasi Lunas (owner) | `app/Http/Controllers/owner/...` | ⏳ Planned |

#### Risiko

- Server Key bersifat rahasia — jangan pernah di-commit ke git atau dibagikan secara publik.
- Webhook endpoint harus dapat diakses oleh server Midtrans — gunakan ngrok saat testing lokal.
- Perlu pengujian di Sandbox Midtrans sebelum beralih ke Production.
- Jika Server Key Production pernah terekspos, segera hubungi Support Midtrans untuk regenerate key.

---

## 3. Dependency Bawaan Laravel

### 3.1 Laravel Authentication

#### Fungsi

Digunakan untuk mengelola proses login, logout, session, dan pengecekan user yang sedang aktif.

#### Alasan Digunakan

Aplikasi StyleIt memiliki beberapa jenis pengguna, yaitu customer, admin, dan owner. Setiap role membutuhkan akses yang berbeda.

#### Dampak pada Project

- User dapat masuk ke sistem.
- Sistem dapat membatasi akses halaman berdasarkan user yang login.
- Dashboard dapat diarahkan sesuai kebutuhan role.

#### Risiko

- Konfigurasi autentikasi harus sesuai.
- Akses halaman perlu dilindungi dengan middleware.

---

### 3.2 Laravel Validation

#### Fungsi

Digunakan untuk memvalidasi input form sebelum data diproses atau disimpan.

#### Alasan Digunakan

Validasi dibutuhkan pada form login, register, booking, checkout, pembayaran DP, portofolio, dan form lainnya.

#### Dampak pada Project

- Data yang masuk lebih aman dan sesuai format.
- Mengurangi risiko data kosong atau tidak valid.
- Membantu menjaga kualitas data pada database.

#### Risiko

- Rule validasi harus disesuaikan dengan kebutuhan tiap form.
- Validasi yang terlalu ketat dapat menghambat user jika tidak dijelaskan dengan baik.

---

### 3.3 Laravel File Storage

#### Fungsi

Digunakan untuk menyimpan dan mengelola file upload.

#### Alasan Digunakan

Project StyleIt membutuhkan upload file seperti logo, banner, foto paket, portofolio, sertifikat, dan kemungkinan foto ulasan.

#### Dampak pada Project

- File upload dapat dikelola lebih terstruktur.
- Penyimpanan file dapat menggunakan disk lokal atau layanan lain.
- File lebih mudah dipanggil pada tampilan aplikasi.

#### Risiko

- Permission folder storage harus benar.
- File berukuran besar dapat memengaruhi penyimpanan.
- Perlu validasi jenis dan ukuran file.

---

## 4. Dependency Rencana Pengembangan

### 4.1 Laravel Excel

#### Fungsi

Digunakan untuk export dan import data Excel.

#### Alasan Digunakan

Owner dapat membutuhkan laporan booking, pembayaran, invoice, dan transaksi dalam bentuk Excel.

#### Cara Install

```bash
composer require maatwebsite/excel
```

#### Dampak pada Project

- Data laporan dapat diekspor.
- Owner lebih mudah menyimpan rekap transaksi.
- Mendukung kebutuhan laporan keuangan.

#### Risiko

- Package perlu disesuaikan dengan versi Laravel.
- Export data besar dapat memengaruhi performa.
- Format laporan perlu dirancang dengan rapi.

---

### 4.2 Intervention Image

#### Fungsi

Digunakan untuk memproses gambar, seperti resize, crop, dan kompres.

#### Alasan Digunakan

Project StyleIt memiliki banyak file gambar seperti logo, banner, portofolio, sertifikat, dan foto paket layanan.

#### Cara Install

```bash
composer require intervention/image-laravel
```

#### Dampak pada Project

- Gambar upload dapat diperkecil ukurannya.
- Tampilan website lebih ringan.
- Penyimpanan file menjadi lebih efisien.

#### Risiko

- Perlu konfigurasi ukuran gambar yang sesuai.
- Hasil kompresi harus tetap menjaga kualitas visual.
- Perlu validasi file upload.

---

### 4.3 WhatsApp Integration

#### Fungsi

Digunakan untuk mengarahkan user ke WhatsApp melalui link atau tombol.

#### Alasan Digunakan

Customer membutuhkan media komunikasi cepat dengan pihak usaha.

#### Contoh Link

```text
https://wa.me/6281234567890?text=Halo%20saya%20ingin%20bertanya%20tentang%20booking
```

#### Dampak pada Project

- Customer dapat menghubungi pihak usaha dengan mudah.
- Cocok untuk konfirmasi booking atau pertanyaan layanan.
- Tidak membutuhkan package Composer tambahan.

#### Risiko

- Nomor WhatsApp harus valid.
- Format pesan perlu disusun dengan jelas.
- Jika nomor berubah, link harus diperbarui.

---

### 4.4 Laravel Mail / Notification

#### Fungsi

Digunakan untuk mengirim email atau notifikasi ke pengguna.

#### Alasan Digunakan

Rencana pengembangan untuk notifikasi booking, konfirmasi pembayaran DP, dan pengiriman invoice ke email customer.

#### Cara Install

Sudah tersedia sebagai fitur bawaan Laravel, tidak perlu install package tambahan. Cukup konfigurasi di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=email@gmail.com
MAIL_FROM_NAME="Lisa Yuli Belti"
```

#### Dampak pada Project

- Customer dapat menerima notifikasi booking melalui email.
- Invoice dapat dikirim langsung ke email customer.
- Konfirmasi pembayaran DP dapat diotomatisasi.

#### Risiko

- Perlu konfigurasi mail server yang benar.
- Jika menggunakan Gmail, perlu mengaktifkan App Password.
- Email dapat masuk ke folder spam jika tidak dikonfigurasi dengan benar.

---

## 5. Kendala Implementasi Dependency

### 5.1 barryvdh/laravel-dompdf

Pada proses implementasi DomPDF, salah satu laptop anggota mengalami kendala SSL certificate Composer. Error yang muncul berkaitan dengan `curl error 60`, sehingga Composer tidak dapat mengakses Packagist melalui koneksi HTTPS.

Solusi yang dilakukan:

- Mengecek konfigurasi Composer.
- Mengecek konfigurasi PHP dan file certificate.
- Menjalankan instalasi dependency pada laptop anggota lain yang Composer-nya berjalan normal.
- Melakukan commit perubahan `composer.json` dan `composer.lock`.
- Anggota lain melakukan `git pull` dan menjalankan `composer install`.

### 5.2 spatie/laravel-permission

Pada proses implementasi Spatie Laravel Permission, ditemukan beberapa kendala berikut:

- RoleSeeder tidak otomatis membuat role — seeder harus dijalankan manual setelah migration menggunakan `php artisan db:seed --class=RoleSeeder` atau melalui tinker.
- Error `assignRole()` muncul karena trait `HasRoles` belum ditambahkan ke model `User`.
- Error `RoleDoesNotExist` muncul karena tabel `roles` masih kosong saat register pertama kali dijalankan.

Solusi yang dilakukan:

- Menambahkan `use HasRoles` di dalam `class User` pada `app/Models/User.php`.
- Membuat role langsung melalui `php artisan tinker` dengan perintah `Role::create()`.
- Memastikan `RoleSeeder` dijalankan sebelum fitur register digunakan.

### 5.3 midtrans/midtrans-php

Pada proses implementasi Midtrans, ditemukan beberapa hal yang perlu diperhatikan:

- Database kosong menyebabkan checkout gagal karena `bookings.package_id` memiliki foreign key constraint ke tabel `service_packages` yang masih kosong.
- Data paket di `PreviewData.php` menggunakan ID hardcode (1–8) yang tidak ada di database.

Solusi yang dilakukan:

- Menjalankan seed data kategori dan paket secara manual via `php artisan tinker` dengan ID yang sesuai PreviewData (Opsi B sementara).
- Integrasi Midtrans (SDK, config, service, controller, route) diselesaikan dalam 3 commit terpisah.
- Webhook `/midtrans/notification` di-exclude dari validasi CSRF di `bootstrap/app.php`.

---

## 6. Kesimpulan

Dependency pada project StyleIt digunakan untuk mendukung kebutuhan sistem agar lebih mudah dikembangkan. Pada tahap ini, dependency yang sudah berhasil diimplementasikan adalah:

- `barryvdh/laravel-dompdf` — untuk kebutuhan generate invoice PDF.
- `spatie/laravel-permission` — untuk pengelolaan role customer, admin, dan owner beserta proteksi akses halaman.
- `midtrans/midtrans-php` — untuk integrasi payment gateway Midtrans Snap pada pembayaran DP customer secara online (backend selesai, frontend Snap.js pending).

Dependency lain seperti Laravel Excel, Intervention Image, WhatsApp Integration, dan Laravel Mail dapat digunakan pada tahap pengembangan berikutnya sesuai kebutuhan fitur project.
ntasikan adalah:

- `barryvdh/laravel-dompdf` — untuk kebutuhan generate invoice PDF.
- `spatie/laravel-permission` — untuk pengelolaan role customer, admin, dan owner beserta proteksi akses halaman.

Dependency lain seperti Midtrans PHP, Laravel Excel, Intervention Image, WhatsApp Integration, dan Laravel Mail dapat digunakan pada tahap pengembangan berikutnya sesuai kebutuhan fitur project.
