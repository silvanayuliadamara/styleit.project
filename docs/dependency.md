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
|   5 | Spatie Laravel Permission   | Mengelola role dan permission                    | Dibutuhkan untuk role customer, admin, dan owner                                | Rencana          | Perlu konfigurasi role yang rapi                                         |
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

### 4.1 Spatie Laravel Permission

#### Fungsi

Digunakan untuk mengelola role dan permission user.

#### Alasan Digunakan

Project StyleIt memiliki role customer, admin, dan owner. Setiap role memiliki hak akses yang berbeda.

#### Cara Install

```bash
composer require spatie/laravel-permission
```

#### Dampak pada Project

- Role user dapat dikelola lebih rapi.
- Akses fitur dapat dibatasi berdasarkan permission.
- Cocok untuk pengembangan dashboard admin dan owner.

#### Risiko

- Perlu konfigurasi role dan permission secara konsisten.
- Perlu migration tambahan.
- Jika salah konfigurasi, user dapat memiliki akses yang tidak sesuai.

---

### 4.2 Midtrans PHP

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

### 4.3 Laravel Excel

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

### 4.4 Intervention Image

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

### 4.5 WhatsApp Integration

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

## 5. Kendala Implementasi Dependency

Pada proses implementasi DomPDF, salah satu laptop anggota mengalami kendala SSL certificate Composer. Error yang muncul berkaitan dengan `curl error 60`, sehingga Composer tidak dapat mengakses Packagist melalui koneksi HTTPS.

Solusi yang dilakukan:

- Mengecek konfigurasi Composer.
- Mengecek konfigurasi PHP dan file certificate.
- Menjalankan instalasi dependency pada laptop anggota lain yang Composer-nya berjalan normal.
- Melakukan commit perubahan `composer.json` dan `composer.lock`.
- Anggota lain melakukan `git pull` dan menjalankan `composer install`.

---

## 6. Kesimpulan

Dependency pada project StyleIt digunakan untuk mendukung kebutuhan sistem agar lebih mudah dikembangkan. Pada tahap ini, dependency yang sudah berhasil diimplementasikan adalah `barryvdh/laravel-dompdf` untuk kebutuhan generate invoice PDF.

Dependency lain seperti Spatie Laravel Permission, Midtrans PHP, Laravel Excel, dan Intervention Image dapat digunakan pada tahap pengembangan berikutnya sesuai kebutuhan fitur project.
