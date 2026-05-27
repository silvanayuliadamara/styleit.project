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
Package ini dibutuhkan karena sistem memiliki pembagian role, yaitu customer, admin, dan owner. Setiap role memiliki akses berbeda.

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
