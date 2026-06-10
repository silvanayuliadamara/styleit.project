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

## Rencana Perubahan Berikutnya

### Added

- Menambahkan dokumentasi fitur pada docs/features.md.
- Menambahkan dokumentasi refactoring pada docs/refactoring.md.
- Menambahkan dokumentasi GitHub Actions pada docs/github-actions.md.

### Changed

- Memperbarui changelog secara berkala setiap ada perubahan fitur, dependency, dokumentasi, atau refactoring.

### Refactor

- Merapikan struktur controller agar sesuai standar PSR-4.
- Merapikan route berdasarkan role user.
- Memisahkan logic bisnis dari controller ke service jika diperlukan pada tahap final.
