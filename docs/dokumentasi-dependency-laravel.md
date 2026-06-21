# Dokumentasi Dependency/Package Laravel pada Proyek PBL Lisa Yuli Belti / Style It

## Pendahuluan

Dokumentasi ini berisi identifikasi dependency/package Laravel yang digunakan dan direncanakan pada proyek PBL. Proyek yang dikembangkan adalah sistem booking layanan Lisa Yuli Belti / Style It yang mencakup fitur autentikasi pengguna, role customer/admin/owner, booking layanan, pembayaran DP, invoice, laporan keuangan, portofolio, sertifikasi, upload foto, dan integrasi WhatsApp.

Dependency dibagi menjadi dua bagian, yaitu dependency utama yang sesuai dengan kebutuhan sistem saat ini dan dependency rencana pengembangan yang kemungkinan digunakan pada tahap berikutnya.

---

# A. Dependency Utama

## 1. Laravel Authentication

### What
Laravel Authentication adalah fitur Laravel untuk mengatur proses autentikasi pengguna, seperti login, logout, session, dan pengecekan user yang sedang aktif.

### Why
Dependency ini dibutuhkan karena sistem memiliki beberapa jenis pengguna, yaitu customer, admin, dan owner. Setiap pengguna harus login agar dapat mengakses fitur sesuai perannya.

### Who
Digunakan oleh customer untuk login dan melakukan booking, admin untuk masuk ke dashboard admin, serta owner untuk masuk ke dashboard owner.

### When
Digunakan saat user melakukan login, logout, register, dan saat sistem mengecek akses halaman tertentu.

### Where
Digunakan pada halaman login, register, dashboard customer, dashboard admin, dashboard owner, dan middleware akses halaman.

### How
Laravel Authentication digunakan melalui fitur bawaan Laravel seperti `Auth::attempt()`, `Auth::login()`, `Auth::logout()`, session, guard, dan provider authentication. Laravel menyediakan konfigurasi autentikasi melalui file `config/auth.php`.

### Referensi
Laravel Documentation — Authentication
https://laravel.com/docs/13.x/authentication

---

## 2. Laravel Validation

### What
Laravel Validation adalah fitur Laravel untuk memvalidasi input form sebelum data diproses atau disimpan ke database.

### Why
Dependency ini dibutuhkan agar data yang masuk valid, misalnya email wajib diisi, format email harus benar, kata sandi minimal 8 karakter, nomor HP wajib diisi, dan konfirmasi kata sandi harus sesuai.

### Who
Digunakan oleh customer saat login/register/booking, admin saat memantau atau mencatat data, dan owner saat mengelola data layanan, jadwal, portofolio, serta sertifikasi.

### When
Digunakan ketika user mengirim form, seperti form login, register, booking, checkout, pembayaran DP, portofolio, sertifikasi, dan ulasan.

### Where
Digunakan pada controller seperti `AuthController`, `BookingController`, `PaymentController`, `PackageController`, `ScheduleController`, dan controller lain yang menangani form.

### How
Validasi dilakukan menggunakan `$request->validate()` atau Form Request. Laravel menyediakan banyak aturan validasi seperti `required`, `email`, `unique`, `min`, `max`, `same`, serta validasi file.

### Referensi
Laravel Documentation — Validation
https://laravel.com/docs/13.x/validation

---

## 3. Laravel File Storage

### What
Laravel File Storage adalah fitur Laravel untuk mengelola penyimpanan file seperti gambar, logo, banner, foto portofolio, foto paket layanan, dan foto sertifikat.

### Why
Dependency ini dibutuhkan karena sistem memiliki fitur upload file, terutama untuk profil usaha, paket layanan, portofolio, sertifikasi, bukti pembayaran DP, dan kemungkinan foto ulasan.

### Who
Digunakan oleh owner untuk upload logo, banner, foto paket, portofolio, dan sertifikat. Customer menggunakannya untuk upload bukti pembayaran DP saat checkout.

### When
Digunakan saat user mengunggah file melalui form.

### Where
Digunakan pada fitur profil usaha, paket layanan, portofolio, sertifikasi, checkout, dan ulasan.

### How
Laravel File Storage bekerja melalui konfigurasi disk pada file `config/filesystems.php`. Laravel menyediakan beberapa driver penyimpanan seperti local, SFTP, dan Amazon S3, sehingga penyimpanan dapat disesuaikan dengan kebutuhan aplikasi. Symbolic link dibuat menggunakan perintah `php artisan storage:link` agar file yang diupload dapat diakses publik.

### Referensi
Laravel Documentation — File Storage
https://laravel.com/docs/13.x/filesystem

---

## 4. Spatie Laravel Permission

### What
Spatie Laravel Permission adalah package untuk mengelola role dan permission pengguna pada aplikasi Laravel.

### Why
Package ini dibutuhkan karena sistem memiliki pembagian role, yaitu customer, admin, dan owner. Setiap role memiliki akses halaman dan fitur yang berbeda. Dengan Spatie, pembatasan akses dapat dikelola secara terpusat melalui middleware tanpa perlu pengecekan manual di setiap controller.

### Who
Digunakan oleh customer, admin, dan owner. Customer dapat melakukan booking dan melihat riwayat booking, admin memantau booking dan pembayaran, sedangkan owner mengelola data utama usaha seperti paket layanan, portofolio, dan laporan.

### When
Digunakan ketika sistem perlu membatasi akses halaman atau fitur berdasarkan role pengguna, yaitu saat user login dan diarahkan ke dashboard sesuai rolenya.

### Where
Digunakan pada middleware route, dashboard customer, dashboard admin, dashboard owner, dan saat proses register untuk assign role customer secara otomatis.

### How
Package ini diinstal menggunakan Composer, kemudian konfigurasi dan migration dipublish. Trait `HasRoles` ditambahkan pada model `User`. Role dibuat melalui seeder, dan setiap user yang register otomatis mendapat role customer melalui `$user->assignRole('customer')` di `AuthController`. Route dilindungi menggunakan middleware `role:customer`, `role:admin`, dan `role:owner`.

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

### Referensi
Spatie Laravel Permission Documentation
https://spatie.be/docs/laravel-permission

---

## 5. Laravel DomPDF

### What
Laravel DomPDF adalah package untuk membuat file PDF dari tampilan Blade Laravel.

### Why
Package ini dibutuhkan karena sistem memiliki fitur invoice. Invoice booking dapat dicetak atau diunduh dalam bentuk PDF oleh customer maupun owner.

### Who
Digunakan oleh customer untuk melihat atau mengunduh invoice, admin untuk melihat invoice booking, dan owner untuk arsip transaksi.

### When
Digunakan setelah booking berhasil dibuat atau ketika user membuka halaman invoice dan menekan tombol cetak/download.

### Where
Digunakan pada fitur booking, pembayaran DP, dan invoice.

### How
Laravel DomPDF bekerja dengan mengubah view Blade menjadi file PDF. Package `barryvdh/laravel-dompdf` merupakan wrapper DomPDF untuk Laravel dan diinstal menggunakan Composer.

```bash
composer require barryvdh/laravel-dompdf --no-audit
```

### Referensi
barryvdh/laravel-dompdf Documentation
https://github.com/barryvdh/laravel-dompdf

---

## 6. Midtrans PHP

### What
Midtrans PHP adalah library untuk mengintegrasikan payment gateway Midtrans pada aplikasi PHP/Laravel.

### Why
Package ini dibutuhkan karena sistem memiliki kebutuhan pembayaran DP secara online. Dengan Midtrans, customer dapat melakukan pembayaran digital tanpa harus transfer manual dan statusnya terupdate secara otomatis (real-time).

### Who
Digunakan oleh customer untuk melakukan pembayaran DP online secara langsung, dan sistem untuk menerima serta memverifikasi status pembayaran dari Midtrans melalui webhook.

### When
Digunakan saat customer melakukan checkout dan memilih metode pembayaran online, serta saat sistem menerima notifikasi callback/webhook dari Midtrans setelah pembayaran diselesaikan.

### Where
Digunakan pada fitur checkout, pembayaran DP, halaman status transaksi, dan route webhook callback `/midtrans/notification`.

### How
Package diinstal menggunakan Composer. Konfigurasi API key Midtrans disimpan di file `.env`. Sistem mengirim data transaksi ke Midtrans dan menerima callback untuk memperbarui status pembayaran.

```bash
composer require midtrans/midtrans-php
```

### Referensi
Midtrans PHP Documentation
https://github.com/Midtrans/midtrans-php

---

# B. Dependency Rencana Pengembangan

## 1. Laravel Excel

### What
Laravel Excel adalah package untuk melakukan export dan import data dalam format Excel (.xlsx) pada aplikasi Laravel.

### Why
Package ini dibutuhkan karena owner memerlukan laporan booking, pembayaran, dan transaksi dalam format Excel untuk keperluan rekap dan arsip keuangan.

### Who
Digunakan oleh owner untuk mengekspor laporan transaksi dan rekap booking dalam format Excel.

### When
Digunakan saat owner membuka halaman laporan dan menekan tombol export Excel.

### Where
Digunakan pada fitur laporan keuangan dan rekap transaksi di dashboard owner.

### How
Package diinstal menggunakan Composer. Data dari database diambil menggunakan Eloquent kemudian diekspor ke file Excel melalui class Export yang disediakan package.

```bash
composer require maatwebsite/excel
```

### Referensi
Laravel Excel Documentation
https://laravel-excel.com

---

## 2. Intervention Image

### What
Intervention Image adalah package untuk memproses gambar pada aplikasi Laravel, seperti resize, crop, dan kompres.

### Why
Package ini dibutuhkan karena project StyleIt memiliki banyak file gambar yang diupload, seperti logo, banner, portofolio, sertifikat, dan foto paket layanan. Tanpa kompresi, file gambar dapat memperlambat performa website.

### Who
Digunakan oleh owner saat mengupload gambar profil usaha, paket layanan, portofolio, dan sertifikat.

### When
Digunakan saat user mengunggah file gambar melalui form upload.

### Where
Digunakan pada fitur profil usaha, paket layanan, portofolio, dan sertifikasi.

### How
Package diinstal menggunakan Composer. Setiap gambar yang diupload diproses terlebih dahulu untuk dikompres atau diresize sebelum disimpan ke storage.

```bash
composer require intervention/image-laravel
```

### Referensi
Intervention Image Documentation
https://image.intervention.io

---

## 3. WhatsApp Integration

### What
WhatsApp Integration adalah integrasi sederhana menggunakan link `wa.me` untuk mengarahkan user ke WhatsApp tanpa membutuhkan package Composer tambahan.

### Why
Customer membutuhkan media komunikasi cepat dengan pihak usaha untuk konfirmasi booking, pertanyaan layanan, atau informasi tambahan.

### Who
Digunakan oleh customer yang ingin menghubungi pihak usaha secara langsung melalui WhatsApp.

### When
Digunakan saat customer menekan tombol atau link WhatsApp yang tersedia di halaman profil usaha atau halaman lainnya.

### Where
Digunakan pada halaman profil usaha dan kemungkinan pada halaman detail paket layanan.

### How
Integrasi dilakukan menggunakan link `wa.me` yang menyertakan nomor WhatsApp dan pesan awal yang sudah diformat. Tidak membutuhkan instalasi package tambahan.

```text
https://wa.me/628122754551?text=Halo%20saya%20ingin%20bertanya%20tentang%20booking
```

### Referensi
WhatsApp Click to Chat Documentation
https://faq.whatsapp.com/425247423114725

---

## 4. Laravel Mail / Notification

### What
Laravel Mail adalah fitur bawaan Laravel untuk mengirim email, sedangkan Laravel Notification adalah sistem notifikasi yang dapat mengirim pesan melalui berbagai saluran seperti email, SMS, dan database.

### Why
Fitur ini dibutuhkan untuk mengirim notifikasi booking, konfirmasi pembayaran DP, dan invoice ke email customer secara otomatis setelah transaksi berhasil.

### Who
Digunakan oleh sistem untuk mengirim notifikasi ke customer setelah booking berhasil dibuat atau pembayaran DP dikonfirmasi.

### When
Digunakan setelah booking berhasil dibuat, setelah pembayaran DP dikonfirmasi, dan setelah invoice diterbitkan.

### Where
Digunakan pada fitur booking, checkout, pembayaran DP, dan invoice.

### How
Laravel Mail menggunakan konfigurasi SMTP pada file `.env`. Email dikirim menggunakan Mailable class yang merender tampilan Blade sebagai isi email. Tidak membutuhkan package tambahan karena sudah tersedia sebagai fitur bawaan Laravel.

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

### Referensi
Laravel Documentation — Mail
https://laravel.com/docs/13.x/mail
