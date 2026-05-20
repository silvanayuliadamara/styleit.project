# Dokumentasi Dependency/Package Laravel pada Proyek PBL Lisa Yuli Belti / Style It

## Pendahuluan

Dokumentasi ini berisi identifikasi dependency/package Laravel yang kemungkinan digunakan pada proyek PBL. Proyek yang dikembangkan adalah sistem booking layanan Lisa Yuli Belti / Style It yang mencakup fitur autentikasi pengguna, role customer/admin/owner, booking layanan, pembayaran DP, invoice, laporan keuangan, portofolio, sertifikasi, upload foto, dan integrasi WhatsApp.

Dependency dibagi menjadi dua bagian, yaitu **dependency utama** yang sesuai dengan kebutuhan sistem saat ini dan **dependency rencana pengembangan** yang kemungkinan digunakan pada tahap berikutnya.

---

# A. Dependency Utama

## 1. Laravel Authentication

### What
Laravel Authentication adalah fitur Laravel untuk mengatur proses autentikasi pengguna, seperti login, logout, session, dan pengecekan user yang sedang aktif.

### Why
Dependency ini dibutuhkan karena sistem memiliki beberapa jenis pengguna, yaitu **customer**, **admin**, dan **owner**. Setiap pengguna harus login agar dapat mengakses fitur sesuai perannya.

### Who
Digunakan oleh customer untuk login dan melakukan booking, admin untuk masuk ke dashboard admin baju, serta owner untuk masuk ke dashboard owner.

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
Dependency ini dibutuhkan agar data yang masuk valid, misalnya email wajib diisi, format email harus benar, password minimal 8 karakter, nomor HP wajib diisi, dan konfirmasi password harus sesuai.

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
Dependency ini dibutuhkan karena sistem memiliki fitur upload file, terutama untuk profil usaha, paket layanan, portofolio, sertifikasi, dan kemungkinan foto ulasan.

### Who
Digunakan oleh owner untuk upload logo, banner, foto paket, portofolio, dan sertifikat. Customer dapat menggunakannya jika fitur upload foto ulasan diterapkan.

### When
Digunakan saat user mengunggah file melalui form.

### Where
Digunakan pada fitur profil usaha, paket layanan, portofolio, sertifikasi, dan ulasan.

### How
Laravel File Storage bekerja melalui konfigurasi disk pada file `config/filesystems.php`. Laravel menyediakan beberapa driver penyimpanan seperti local, SFTP, dan Amazon S3, sehingga penyimpanan dapat disesuaikan dengan kebutuhan aplikasi.

### Referensi
Laravel Documentation — File Storage  
https://laravel.com/docs/13.x/filesystem

---

## 4. Spatie Laravel Permission

### What
Spatie Laravel Permission adalah package untuk mengelola role dan permission pengguna pada aplikasi Laravel.

### Why
Package ini dibutuhkan karena sistem memiliki pembagian role, yaitu **customer**, **admin**, dan **owner**. Setiap role memiliki akses berbeda.

### Who
Digunakan oleh customer, admin, dan owner. Customer dapat melakukan booking, admin memantau booking/pembayaran/invoice khusus baju, sedangkan owner mengelola data utama usaha.

### When
Digunakan ketika sistem perlu membatasi akses halaman atau fitur berdasarkan role pengguna.

### Where
Digunakan pada middleware, dashboard, controller, dan menu navigasi yang berbeda sesuai role.

### How
Package ini digunakan dengan menambahkan trait `HasRoles` pada model `User`, kemudian user dapat diberikan role seperti `customer`, `admin`, atau `owner`.

### Referensi
Spatie Laravel Permission Documentation  
https://spatie.be/docs/laravel-permission

---

## 5. Laravel DomPDF

### What
Laravel DomPDF adalah package untuk membuat file PDF dari tampilan Blade Laravel.

### Why
Package ini dibutuhkan karena sistem memiliki fitur invoice. Invoice booking dapat dicetak atau diunduh dalam bentuk PDF.

### Who
Digunakan oleh customer untuk melihat atau mengunduh invoice, admin untuk melihat invoice booking khusus baju, dan owner untuk arsip transaksi.

### When
Digunakan setelah booking berhasil dibuat atau ketika user membuka halaman invoice dan menekan tombol cetak/download.

### Where
Digunakan pada fitur booking, pembayaran DP, dan invoice.

### How
Laravel DomPDF bekerja dengan mengubah view Blade menjadi file PDF. Package `barryvdh/laravel-dompdf` merupakan wrapper DomPDF untuk Laravel.

Contoh instalasi:

```bash
composer require barryvdh/laravel-dompdf
```

### Referensi
barryvdh/laravel-dompdf — GitHub  
https://github.com/barryvdh/laravel-dompdf

---

## 6. Midtrans PHP

### What
Midtrans PHP adalah package resmi untuk menghubungkan aplikasi PHP/Laravel dengan layanan payment gateway Midtrans.

### Why
Package ini digunakan karena proyek membutuhkan payment gateway untuk mendukung pembayaran DP secara online. Pada sistem ini, Midtrans difokuskan untuk pembayaran DP, sedangkan pelunasan dapat dicatat secara manual sesuai proses bisnis usaha.

### Who
Digunakan oleh customer untuk membayar DP, owner untuk melihat status pembayaran DP, admin untuk memantau pembayaran khusus baju, dan sistem untuk mencatat status transaksi payment gateway.

### When
Digunakan saat customer melakukan pembayaran DP setelah checkout atau saat booking dibuat.

### Where
Digunakan pada fitur checkout, booking, pembayaran DP, invoice, dan transaksi payment gateway.

### How
Package Midtrans PHP dapat diinstal melalui Composer. Midtrans menyediakan official PHP wrapper/library untuk Payment API yang dapat digunakan untuk menghubungkan sistem Laravel dengan layanan pembayaran online.

Contoh instalasi:

```bash
composer require midtrans/midtrans-php
```

### Referensi
Midtrans PHP — Packagist
https://packagist.org/packages/midtrans/midtrans-php

Midtrans Documentation
https://docs.midtrans.com/

---

## 7. Laravel Excel

### What
Laravel Excel adalah package untuk export dan import data Excel pada aplikasi Laravel.

### Why
Package ini dibutuhkan karena sistem memiliki fitur laporan keuangan bulanan. Data pembayaran DP, booking, invoice, dan transaksi dapat diekspor ke file Excel.

### Who
Digunakan oleh owner untuk mengunduh laporan keuangan dan rekap transaksi.

### When
Digunakan saat owner ingin mencetak, mengunduh, atau menyimpan laporan keuangan.

### Where
Digunakan pada fitur laporan keuangan, pembayaran, booking, invoice, dan transaksi keuangan.

### How
Laravel Excel digunakan dengan membuat class export/import, kemudian data dapat diunduh dalam format Excel.

Contoh instalasi:

```bash
composer require maatwebsite/excel
```

### Referensi
Laravel Excel Documentation  
https://docs.laravel-excel.com

---

## 8. Intervention Image

### What
Intervention Image adalah package untuk memproses gambar, seperti resize, crop, dan kompres gambar.

### Why
Package ini dibutuhkan karena sistem memiliki banyak fitur upload gambar. Gambar yang terlalu besar dapat diproses terlebih dahulu agar ukuran file lebih ringan dan tampilan website tetap cepat.

### Who
Digunakan oleh owner saat mengunggah foto paket layanan, portofolio, logo, banner, dan sertifikasi. Jika fitur foto ulasan digunakan, customer juga dapat memanfaatkannya.

### When
Digunakan saat user mengunggah gambar ke sistem.

### Where
Digunakan pada fitur profil usaha, paket layanan, portofolio, sertifikasi, dan ulasan.

### How
Intervention Image dapat diintegrasikan ke Laravel menggunakan package `intervention/image-laravel`.

Contoh instalasi:

```bash
composer require intervention/image-laravel
```

### Referensi
Intervention Image Documentation  
https://image.intervention.io

---

## 9. WhatsApp Integration

### What
WhatsApp Integration adalah integrasi untuk mengarahkan user ke WhatsApp, misalnya melalui link `wa.me` atau tombol WhatsApp pada sistem.

### Why
Dependency/fitur ini dibutuhkan karena sistem memiliki kebutuhan komunikasi antara customer dan pihak usaha, terutama untuk kontak makeup dan kontak khusus baju. Fitur ini juga sesuai dengan rancangan sistem yang memiliki pengaturan WhatsApp.

### Who
Digunakan oleh customer untuk menghubungi pihak usaha, admin untuk support baju, dan owner untuk mengelola kontak WhatsApp makeup atau baju.

### When
Digunakan saat customer membutuhkan bantuan, setelah booking, saat ingin konfirmasi informasi, atau saat diarahkan ke kontak makeup/baju.

### Where
Digunakan pada fitur profil usaha, booking, invoice, WhatsApp support, dan pengaturan WhatsApp.

### How
Integrasi sederhana dapat dilakukan menggunakan link WhatsApp, misalnya format `https://wa.me/nomor_telepon` dengan pesan default. Link tersebut dapat diarahkan dari tombol WhatsApp pada halaman website.

Contoh format:

```text
https://wa.me/6281234567890?text=Halo%20saya%20ingin%20bertanya%20tentang%20booking
```

### Referensi
WhatsApp Click-to-Chat Reference  
https://support.dotdigital.com/en/articles/11331301-click-to-chat-for-whatsapp

---

# B. Dependency Rencana Pengembangan

## 10. Laravel Mail / Notification

### What
Laravel Mail dan Notification adalah fitur Laravel untuk mengirim email atau notifikasi kepada pengguna.

### Why
Dependency ini tidak dimasukkan sebagai dependency utama karena belum menjadi fitur utama pada Project Charter. Namun, fitur ini dapat menjadi rencana pengembangan ke depannya untuk mengirim notifikasi booking, invoice, pembayaran DP, atau status pembatalan.

### Who
Dapat digunakan oleh customer untuk menerima informasi booking/invoice, owner untuk menerima informasi transaksi, dan admin untuk menerima informasi booking khusus baju.

### When
Dapat digunakan pada pengembangan berikutnya, setelah fitur utama seperti booking, pembayaran DP, invoice, dan WhatsApp berjalan.

### Where
Dapat diterapkan pada fitur booking, pembayaran DP, invoice, dan pengajuan pembatalan.

### How
Laravel menyediakan fitur Mail dan Notification untuk mengirim pesan kepada pengguna. Namun, pada tahap awal proyek, komunikasi utama lebih diarahkan melalui WhatsApp Integration.

### Referensi
Laravel Documentation — Mail  
https://laravel.com/docs/13.x/mail

Laravel Documentation — Notifications  
https://laravel.com/docs/13.x/notifications

---

# Ringkasan Dependency

| No | Dependency/Package | Jenis | Status | Kebutuhan pada Proyek |
|---:|---|---|---|---|
| 1 | Laravel Authentication | Fitur bawaan Laravel | Utama | Login, logout, session customer/admin/owner |
| 2 | Laravel Validation | Fitur bawaan Laravel | Utama | Validasi form login, register, booking, pembayaran DP |
| 3 | Laravel File Storage | Fitur bawaan Laravel | Utama | Upload foto paket, portofolio, sertifikat, logo, banner |
| 4 | Spatie Laravel Permission | Package tambahan | Utama/Disarankan | Role customer, admin, owner |
| 5 | Laravel DomPDF | Package tambahan | Utama | Cetak/download invoice PDF |
| 6 | Midtrans PHP | Package tambahan | Utama | Payment gateway untuk pembayaran DP online |
| 7 | Laravel Excel | Package tambahan | Opsional | Export laporan keuangan |
| 8 | Intervention Image | Package tambahan | Opsional | Resize/kompres gambar upload |
| 9 | WhatsApp Integration | Integrasi fitur | Utama | Tombol WhatsApp makeup dan baju |
| 10 | Laravel Mail/Notification | Fitur bawaan Laravel | Rencana pengembangan | Notifikasi email di tahap berikutnya |

---

# Penutup

Berdasarkan kebutuhan proyek PBL Lisa Yuli Belti / Style It, dependency/package yang paling sesuai adalah dependency yang mendukung autentikasi, validasi form, role pengguna, upload file, invoice, pembayaran DP online, laporan keuangan, pengelolaan gambar, dan integrasi WhatsApp. Midtrans PHP digunakan khusus untuk pembayaran DP online, sedangkan pelunasan dapat dicatat secara manual oleh owner sesuai proses bisnis. Laravel Mail/Notification tidak dimasukkan sebagai dependency utama karena belum menjadi fitur pada tahap awal, tetapi dapat dicantumkan sebagai rencana pengembangan.
