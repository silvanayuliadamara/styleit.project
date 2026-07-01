# Changelog

Dokumen ini berisi catatan perubahan pada project StyleIt. Changelog digunakan untuk mencatat fitur baru, perubahan dokumentasi, perbaikan, dependency, dan refactoring selama proses pengembangan project.

Format changelog mengikuti kategori:

- Added untuk fitur atau file baru.
- Changed untuk perubahan pada fitur atau dokumentasi.
- Fixed untuk perbaikan bug.
- Dependency untuk penambahan atau perubahan package.
- Refactor untuk perapian struktur kode.

---

## v1.0.0 - Dokumentasi Awal Project

### Added

- Menambahkan struktur awal project Laravel.
- Menambahkan halaman login.
- Menambahkan halaman register.
- Menambahkan halaman home.
- Menambahkan halaman profil usaha.
- Menambahkan halaman portofolio.
- Menambahkan halaman pricelist.
- Menambahkan fitur customer preview pages.
- Menambahkan controller customer seperti CartController, CheckoutController, CustomerBookingController, dan CustomerDashboardController.
- Menambahkan model seperti Booking, Payment, ServicePackage, ServiceCategory, PortfolioItem, dan User.
- Menambahkan dokumentasi dependency Laravel.
- Menambahkan README sebagai dokumentasi utama project.
- Menambahkan dokumentasi instalasi pada docs/installation.md.

### Changed

- Memperbarui struktur folder view pada resources/views.
- Memperbarui tampilan halaman autentikasi.
- Memperbarui dokumentasi project agar sesuai dengan modul dokumentasi PBL.
- Memperbarui branch development dengan hasil dokumentasi dan dependency terbaru.

### Fixed

- Memperbaiki struktur dokumentasi agar lebih mudah dipahami anggota kelompok.
- Menambahkan catatan troubleshooting instalasi pada dokumentasi.

### Dependency

- Menambahkan dependency barryvdh/laravel-dompdf untuk kebutuhan generate invoice PDF.
- Memperbarui composer.json.
- Memperbarui composer.lock.

### Refactor

- Mengelompokkan route customer menggunakan prefix customer.
- Memisahkan beberapa tampilan Blade ke dalam folder components, layouts, dan folder fitur.
- Menyiapkan dokumentasi refactoring untuk tahap final.

---

## v1.0.1 - Dokumentasi Dependency DomPDF

### Added

- Menambahkan catatan implementasi dependency DomPDF.
- Menambahkan analisis perubahan composer.json dan composer.lock.
- Menambahkan refleksi kendala SSL Composer.
- Menambahkan dampak dependency DomPDF terhadap project.

### Changed

- Memperbarui dokumentasi dependency agar sesuai dengan kebutuhan modul.
- Memperjelas status dependency DomPDF sebagai dependency yang sudah diimplementasikan.

### Fixed

- Menambahkan catatan solusi untuk kendala instalasi dependency pada salah satu laptop anggota.

### Dependency

- Package barryvdh/laravel-dompdf berhasil ditambahkan.
- Dependency pendukung yang ikut terinstall antara lain:
    - dompdf/dompdf
    - dompdf/php-font-lib
    - dompdf/php-svg-lib
    - masterminds/html5
    - sabberworm/php-css-parser
    - thecodingmachine/safe

---

## v1.0.2 - Dokumentasi README dan Installation

### Added

- Menambahkan README.md sebagai dokumentasi utama project.
- Menambahkan docs/installation.md sebagai panduan instalasi project.
- Menambahkan deskripsi project, tujuan aplikasi, target pengguna, fitur utama, tech stack, dan struktur folder penting.
- Menambahkan langkah instalasi project Laravel.
- Menambahkan troubleshooting instalasi.

### Changed

- Memperbarui dokumentasi project agar lebih siap digunakan oleh anggota kelompok dan developer lain.

### Fixed

- Menambahkan catatan instalasi untuk Composer, NPM, database, environment, cache, dan port server.

---

## v1.0.3 - Dokumentasi Dependency Lengkap dan Spatie Permission

### Added

- Menambahkan docs/dependency.md sebagai dokumentasi lengkap seluruh dependency project.
- Menambahkan implementasi spatie/laravel-permission untuk pengelolaan role customer, admin, dan owner.
- Menambahkan RoleSeeder untuk membuat role awal di database.
- Menambahkan tabel roles, permissions, model_has_roles, model_has_permissions, dan role_has_permissions ke database.
- Menambahkan trait HasRoles pada model User.
- Menambahkan docs/dokumentasi-dependency-laravel.md sebagai dokumentasi dependency berbasis kerangka What-Why-Who-When-Where-How.
- Menambahkan daftar dependency rencana pengembangan: Midtrans PHP, Laravel Excel, Intervention Image, WhatsApp Integration, dan Laravel Mail / Notification.

### Changed

- Memperbarui AuthController untuk assign role customer secara otomatis saat register.
- Memperbarui routes/web.php dengan middleware role untuk proteksi akses halaman per role.
- Memperbarui composer.json dan composer.lock setelah instalasi spatie/laravel-permission.
- Memperbarui app/Models/User.php dengan penambahan trait HasRoles.

### Fixed

- Menambahkan catatan solusi error assignRole() karena trait HasRoles belum ditambahkan ke model User.
- Menambahkan catatan solusi error RoleDoesNotExist karena tabel roles masih kosong saat register.
- Menambahkan catatan bahwa RoleSeeder harus dijalankan sebelum fitur register digunakan.

### Dependency

- Package spatie/laravel-permission ^6.0 berhasil ditambahkan.
- Dependency bawaan Laravel yang terdokumentasi: Laravel Authentication, Laravel Validation, Laravel File Storage.
- Dependency rencana pengembangan yang diidentifikasi: midtrans/midtrans-php, maatwebsite/excel, intervention/image-laravel.

---

## v1.0.4 - Dokumentasi Fitur

### Added

- Menambahkan docs/features.md sebagai dokumentasi fitur-fitur utama project StyleIt.
- Mendokumentasikan fitur Login dengan alur, aktor, dan route terkait (AuthController).
- Mendokumentasikan fitur Register dengan validasi enam field: Nama Lengkap, No. HP, Username Instagram, Email, Kata Sandi, dan Konfirmasi Kata Sandi.
- Mendokumentasikan fitur Home sebagai halaman utama publik (PublicPageController).
- Mendokumentasikan fitur Profil Usaha dengan informasi kontak WhatsApp dan Instagram.
- Mendokumentasikan fitur Portofolio dengan filter kategori: Semua, Prewedding, Wedding, Regular, dan Khusus Baju.
- Mendokumentasikan fitur Pricelist dengan pengelompokan paket berdasarkan kategori layanan.
- Mendokumentasikan fitur Keranjang untuk menyimpan sementara paket layanan sebelum checkout (CartController).
- Mendokumentasikan fitur Checkout dengan upload bukti pembayaran DP dan catatan untuk admin (CheckoutController).
- Mendokumentasikan fitur Booking Customer dengan kode unik booking format LYB-PREV-xxxxx (CustomerBookingController).
- Mendokumentasikan fitur Dashboard Customer sebagai halaman ringkasan aktivitas setelah login (CustomerDashboardController).

### Changed

- Memperbarui struktur folder resources/views dengan subfolder auth, customer, layanan, layouts, paket, dan components.

---

## v1.0.5 - Dokumentasi GitHub Actions CI

### Added

- Menambahkan docs/github-actions.md sebagai dokumentasi konfigurasi Continuous Integration.
- Menambahkan workflow Code Linting di .github/workflows/code-linting.yml untuk memeriksa sintaks PHP, HTML, dan CSS.
- Menambahkan workflow Automated Testing di .github/workflows/test-before-merge.yml untuk menjalankan migrasi dan test suite Laravel.
- Menambahkan konfigurasi SQLite sebagai database testing agar CI berjalan tanpa service MySQL terpisah.
- Menambahkan panduan menjalankan test secara lokal sebelum membuat Pull Request.
- Menambahkan panduan status badge untuk README.md.

### Changed

- Memperbarui alur kerja tim dengan trigger CI otomatis pada setiap pull request ke branch development.

### Fixed

- Menambahkan catatan bahwa test keranjang dan checkout perlu menggunakan withSession() karena data disimpan di session.
- Menambahkan catatan bahwa test upload file perlu menggunakan UploadedFile::fake()->image() untuk mensimulasikan upload.
- Menambahkan catatan agar tidak menggunakan query MySQL-specific seperti FULLTEXT atau JSON_EXTRACT di migration agar kompatibel dengan SQLite.

---

## v1.0.6 - Dokumentasi Refactoring

### Added

- Menambahkan docs/refactoring.md sebagai dokumentasi keputusan refactoring project StyleIt.
- Mengidentifikasi kandidat refactoring #1: Ekstraksi FormRequest untuk validasi di AuthController, CartController, dan CheckoutController.
- Mengidentifikasi kandidat refactoring #2: Migrasi PreviewData dari session ke database menggunakan Eloquent dan model yang sudah tersedia.
- Mengidentifikasi kandidat refactoring #3: Ekstraksi BookingService untuk memisahkan logika kalkulasi harga dari CartController.
- Mengidentifikasi kandidat refactoring #4: Grouping route dengan prefix, name, dan middleware secara konsisten di routes/web.php.
- Mengidentifikasi kandidat refactoring #5: Penggunaan Route Model Binding pada CustomerBookingController setelah migrasi ke database.

### Changed

- Mendokumentasikan prinsip refactoring yang digunakan: DRY, SRP, dan KISS.
- Mendokumentasikan kondisi saat ini dan target refactoring untuk setiap kandidat beserta contoh kode sebelum dan sesudah.

---

## Rencana Perubahan Berikutnya

### Added

- Mengerjakan refactoring kandidat #1: Ekstraksi FormRequest (LoginRequest, RegisterRequest, StoreCartRequest, StoreCheckoutRequest).
- Mengerjakan refactoring kandidat #2: Migrasi PreviewData ke Eloquent — ServiceCategory, ServicePackage, PortfolioItem, BlockedDate, Booking.
- Mengerjakan refactoring kandidat #3: Ekstraksi BookingService untuk logika kalkulasi harga booking.
- Mengintegrasikan dependency rencana: Midtrans PHP untuk pembayaran DP online.
- Mengintegrasikan dependency rencana: Laravel Excel untuk export laporan keuangan owner.
- Mengintegrasikan dependency rencana: Intervention Image untuk kompresi gambar upload.

### Changed

- Memperbarui changelog secara berkala setiap ada perubahan fitur, dependency, dokumentasi, atau refactoring.

### Refactor

- Mengerjakan refactoring kandidat #4: Grouping route dengan prefix dan middleware konsisten.
- Mengerjakan refactoring kandidat #5: Route Model Binding pada CustomerBookingController.
- Merapikan struktur controller agar sesuai standar PSR-4.
- Memisahkan logic bisnis dari controller ke service jika diperlukan pada tahap final.

---

## Released - Version 1.1.0 - 2026-06-18

### Added

- Menambahkan fitur pilihan metode pembayaran DP pada halaman checkout.
- Menambahkan tiga pilihan metode: Virtual Account (BCA/Mandiri/BNI/BRI), QRIS, dan E-Wallet (OVO/GoPay/DANA/ShopeePay).
- Menambahkan kolom metode_pembayaran pada tabel payments.

### Changed

- Memperbarui halaman checkout customer dengan pilihan metode pembayaran.
- Memperbarui halaman detail booking di sisi admin untuk menampilkan metode pembayaran yang dipilih.
- Memperbarui halaman detail booking di sisi owner untuk menampilkan metode pembayaran yang dipilih.

### Impacted Modules

- Checkout Module
- Payment Module
- Booking Detail Module (Admin & Owner)

---

## Released - Version 1.2.0 - 2026-06-21

### Added

- Mengintegrasikan payment gateway Midtrans Snap untuk pembayaran DP online pada customer checkout.
- Menambahkan class `app/Services/MidtransService.php` untuk mengelola pembuatan token Snap, pengecekan status transaksi, dan konfirmasi pembayaran.
- Menambahkan `app/Http/Controllers/Customer/MidtransController.php` untuk mengelola endpoint token pembayaran dan menerima callback webhook dari server Midtrans.
- Mengintegrasikan file `snap.js` dari Midtrans di halaman instruksi pembayaran (`payment-instruction.blade.php`) dan halaman checkout (`checkout.blade.php`).
- Menambahkan helper tombol simulasi webhook lokal pada halaman instruksi pembayaran untuk kebutuhan pengujian developer tanpa internet publik.
- Menambahkan statistik ringkasan booking (Total Booking, Aktif, Selesai) pada dashboard customer.

### Changed

- Memperbarui `CheckoutController.php` agar memproses token Snap Midtrans saat proses checkout dan mengembalikan respon JSON untuk membuka pop-up pembayaran secara langsung.
- Memperbarui model `Booking.php` dan `User.php` untuk mendukung sinkronisasi status pembayaran dengan Midtrans.
- Memperbarui halaman beranda (`home.blade.php`) dan penyesuaian layout CSS di `public/css/app.css` untuk memperindah tampilan visual.
- Memperbarui dokumen `docs/dependency.md` untuk menandai semua modul integrasi Midtrans telah selesai (100% completed).

### Dependency

- Menyelesaikan dan meresmikan penggunaan dependency `midtrans/midtrans-php` ^2.6 dari rencana pengembangan menjadi terimplementasi penuh.

### Impacted Modules

- Checkout Module
- Payment Module
- Booking Detail Module (Customer & Owner)
- Customer Dashboard Module
- Public Landing Page (Home)

---

## Released - Version 1.3.0 - 2026-06-24

### Added

- Menambahkan fitur verifikasi Captcha pada halaman login menggunakan library `mews/captcha` untuk mencegah serangan otomatis (brute force).
- Menambahkan tombol refresh captcha dengan transisi animasi ikon putar (`bi-spin`).
- Menambahkan fitur otomatisasi pembatalan booking kedaluwarsa setelah 1 jam jika belum melakukan pembayaran DP (`payment_status = belum_bayar`).
- Menambahkan middleware `CancelExpiredBookings` untuk memicu pembatalan otomatis di setiap request web.
- Menambahkan scheduler di `bootstrap/app.php` untuk menjalankan pembatalan otomatis setiap menit.
- Menambahkan fitur kompresi gambar otomatis untuk file gambar paket layanan yang diunggah oleh owner (lebar maksimal 800px, kualitas JPEG 80%).

### Changed

- Memperbarui `AuthController.php` untuk memvalidasi captcha saat proses login dan menangani endpoint refresh captcha.
- Memperbarui file view `resources/views/auth/login.blade.php` dengan input captcha dan JavaScript AJAX untuk refresh captcha.
- Memperbarui stylesheet `public/css/auth.css` untuk menyesuaikan layout dan animasi putar tombol refresh captcha.
- Memperbarui tampilan tanggal acara pada dasbor customer, tabel booking admin, dan tabel booking owner agar menampilkan informasi slot waktu (`slot_waktu`) jika ada.
- Memperbarui tampilan file cetak invoice (`shared/invoice.blade.php`) untuk menyertakan informasi slot waktu.
- Mengubah alur redirect pada `CheckoutController.php` ketika pembayaran tidak ditemukan atau sudah diproses agar langsung diarahkan ke dasbor customer.
- Memperbarui tata letak customer `resources/views/layouts/customer.blade.php` agar mendukung penampilan alert bertipe `warning`.
- Memperbarui `OwnerPackageController.php` pada method `store` dan `update` untuk memproses kompresi gambar menggunakan `ImageManager` sebelum disimpan.

### Dependency

- Menambahkan dependency `mews/captcha` ^3.5 untuk memproses pembuatan gambar captcha.
- Meresmikan integrasi penuh payment gateway `midtrans/midtrans-php` ^2.6 baik dari sisi backend maupun integrasi frontend Snap.js.
- Menambahkan dependency `intervention/image` ^4.1 untuk pemrosesan dan optimasi gambar.

### Impacted Modules

- Auth Module (Login)
- Booking Module (Admin, Owner, Customer)
- Invoice Module
- Checkout & Payment Module
- Owner Package Module (Image Compression)

### Released - Version 1.4.0 - 2026-07-01

### Added
Menambahkan fitur "Ingat Saya" (Remember Me) pada halaman login untuk mempertahankan sesi pengguna menggunakan persistent cookie bawaan Laravel.
Menambahkan kelas FormRequest khusus: app/Http/Requests/Auth/LoginRequest.php, app/Http/Requests/Auth/RegisterRequest.php, dan app/Http/Requests/Cart/StoreCartRequest.php.
Menambahkan dokumentasi pembagian tugas tim pengembang pada docs/kontribusi.md.

### Changed
Memperbarui AuthController.php dan CartController.php untuk menggunakan FormRequest khusus menggantikan validasi inline (menyelesaikan refactoring kandidat #1 secara penuh).
Memperbarui stylesheet public/css/auth.css dengan gaya visual khusus untuk checkbox "Ingat Saya" agar sesuai dengan tema emas premium StyleIt.
Memperbarui halaman masuk resources/views/auth/login.blade.php dengan checkbox "Ingat Saya".
Memperbarui dokumen docs/refactoring.md dengan status selesai pada seluruh kandidat refactoring dan mengisi tabel riwayat refactoring.
Memperbarui README.md untuk menautkan dokumen kontribusi pengembang dan pengujian sistem.

### Refactor
Ekstraksi logika validasi dari controller ke FormRequest terdedikasi (LoginRequest, RegisterRequest, StoreCartRequest).

### Impacted Modules
Auth Module (Login & Register)
Cart Module (Keranjang Belanja)
Documentation & Quality Assurance Modules
