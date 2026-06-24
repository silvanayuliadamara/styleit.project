# Dependency Documentation

Dokumen ini berisi daftar dependency/package yang digunakan dan direncanakan pada project StyleIt. Dependency digunakan untuk mendukung kebutuhan sistem, seperti autentikasi, validasi, upload file, invoice PDF, payment gateway, laporan, keamanan captcha, dan pengelolaan gambar.

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
|   7 | Mews Captcha                | Pembuatan dan validasi captcha                   | Keamanan tambahan pada form login untuk mencegah brute force                    | ^3.5             | Membutuhkan ekstensi PHP GD terinstall di server                         |
|   8 | Laravel Excel               | Export/import data Excel                         | Dibutuhkan untuk laporan keuangan dan rekap transaksi                           | Rencana/Opsional | Perlu maintenance package dan format data                                |
|   9 | Intervention Image          | Resize, crop, dan kompres gambar                 | Dibutuhkan untuk optimasi gambar upload                                         | ^4.1             | Membutuhkan ekstensi PHP GD terinstall di server                         |
|  10 | WhatsApp Integration        | Mengarahkan user ke WhatsApp                     | Dibutuhkan untuk komunikasi customer dengan pihak usaha                         | Link/Integrasi   | Nomor WhatsApp harus valid                                               |
|  11 | Laravel Mail / Notification | Mengirim email atau notifikasi                   | Rencana pengembangan untuk notifikasi booking/invoice                           | Bawaan Laravel   | Perlu konfigurasi mail server                                            |

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

#### Dampak pada Project
- Project dapat dikembangkan untuk generate invoice PDF.
- File `composer.json` dan `composer.lock` bertambah.
- Anggota kelompok lain dapat menjalankan `composer install` agar dependency terpasang sesuai versi yang sama.

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

#### Perubahan Kode Utama
**`app/Models/User.php`** — tambahkan trait `HasRoles`:
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
}
```

---

### 2.3 midtrans/midtrans-php

#### Fungsi
`midtrans/midtrans-php` adalah SDK resmi Midtrans untuk PHP yang digunakan untuk integrasi payment gateway. Dipakai untuk generate Snap token dan memvalidasi notifikasi webhook dari Midtrans.

#### Alasan Digunakan
Project StyleIt membutuhkan pembayaran DP secara online. Dengan Midtrans Snap, customer dapat melakukan pembayaran DP melalui berbagai metode (Virtual Account, QRIS, e-wallet) tanpa perlu upload bukti transfer manual. Status pembayaran diupdate otomatis via webhook callback dari Midtrans secara real-time.

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

---

### 2.4 mews/captcha

#### Fungsi
`mews/captcha` adalah package captcha untuk Laravel 5/6/7/8/9/10/11 yang aman dan mudah digunakan untuk memproses pembuatan gambar kode verifikasi acak dan memvalidasi inputnya.

#### Alasan Digunakan
Untuk mengamankan halaman login admin, owner, dan customer dari potensi serangan otomatis (seperti bot brute force atau credential stuffing). Pengguna diwajibkan memasukkan kode captcha yang benar sebelum sistem memproses autentikasi.

#### Cara Install
```bash
composer require mews/captcha
php artisan vendor:publish --provider="Mews\Captcha\CaptchaServiceProvider"
```

#### Perubahan File
Setelah dependency Captcha ditambahkan, file yang berubah atau ditambahkan adalah:
```text
composer.json
composer.lock
config/captcha.php               ← file baru
app/Http/Controllers/AuthController.php ← tambah validasi captcha & refreshCaptcha()
resources/views/auth/login.blade.php   ← tambah form input captcha, tombol refresh, dan JS fetch
public/css/auth.css              ← tambah styling layout captcha & animasi putar ikon refresh
routes/web.php                   ← tambah route refresh-captcha
```

---

### 2.5 intervention/image

#### Fungsi
`intervention/image` adalah library manipulasi gambar PHP yang digunakan untuk membuat, mengedit, dan mengompresi gambar.

#### Alasan Digunakan
Untuk mengoptimalkan ukuran gambar paket layanan yang diunggah oleh owner. Gambar diubah ukurannya (*scale*) ke lebar maksimal 800px dan dikompresi ke format JPEG dengan kualitas 80% guna menghemat ruang penyimpanan server dan mempercepat pemuatan halaman web.

#### Cara Install
```bash
composer require intervention/image
```

#### Perubahan File
Setelah dependency ditambahkan, file yang berubah adalah:
```text
composer.json
composer.lock
app/Http/Controllers/Owner/OwnerPackageController.php ← implementasi kompresi gambar saat simpan & update paket
```

---

## 3. Dependency Bawaan Laravel

### 3.1 Laravel Authentication
Mengelola login, logout, session, dan pembatasan hak akses via middleware bawaan (`auth`).

### 3.2 Laravel Validation
Memvalidasi masukan form seperti validasi panjang karakter, tipe data email, konfirmasi kata sandi, dan validasi kode captcha (`'captcha' => 'required|captcha'`).

### 3.3 Laravel File Storage
Mengelola berkas gambar upload seperti portofolio, logo banner, dan berkas transaksi.

---

## 4. Dependency Rencana Pengembangan

### 4.1 Laravel Excel
Direncanakan untuk mengekspor rekap laporan transaksi keuangan milik owner ke dalam format Excel (.xlsx).

### 4.2 WhatsApp Integration
Menggunakan tautan langsung (`https://wa.me/...`) untuk mempermudah customer berkonsultasi langsung dengan admin.

### 4.3 Laravel Mail / Notification
Fitur notifikasi email untuk mengabarkan status booking terbaru ke customer secara otomatis.

---

## 5. Kendala Implementasi Dependency

### 5.1 barryvdh/laravel-dompdf
Masalah sertifikat SSL lokal pada Composer (`curl error 60`). Solusinya adalah melakukan instalasi pada komputer tim lain yang normal, melakukan commit `composer.lock`, kemudian melakukan `git pull` dan `composer install` pada komputer yang terkendala.

### 5.2 spatie/laravel-permission
*Error* saat registrasi dikarenakan trait `HasRoles` belum didaftarkan pada model `User`, serta tabel roles kosong. Diselesaikan dengan mendaftarkan trait dan menjalankan `RoleSeeder` sebelum sistem digunakan.

### 5.3 midtrans/midtrans-php
Testing lokal terhambat karena webhook callback Midtrans membutuhkan URL publik. Diselesaikan menggunakan tunneling lokal (seperti ngrok) dan pengecualian token CSRF pada endpoint notifikasi di `bootstrap/app.php`.

### 5.4 mews/captcha
Membutuhkan pustaka grafik GD (`GD Library`) terpasang pada konfigurasi PHP server. Pastikan ekstensi `;extension=gd` telah diaktifkan (dihilangkan tanda titik komanya) pada file `php.ini` server PHP Anda.

---

## 6. Kesimpulan

Pada tahapan project StyleIt saat ini, dependency yang telah sukses diimplementasikan dan dikonfigurasi penuh adalah:
- `barryvdh/laravel-dompdf` — Cetak invoice PDF.
- `spatie/laravel-permission` — Pembagian hak akses akun.
- `midtrans/midtrans-php` — Pembayaran DP digital via Midtrans Snap.
- `mews/captcha` — Keamanan autentikasi halaman login.
- `intervention/image` — Kompresi dan manipulasi gambar paket layanan.
