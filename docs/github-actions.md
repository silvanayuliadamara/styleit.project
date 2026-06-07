# GitHub Actions Documentation

Dokumen ini menjelaskan konfigurasi Continuous Integration (CI) menggunakan GitHub Actions untuk proyek StyleIt.

---

## Daftar Isi

- [Apa itu GitHub Actions?](#apa-itu-github-actions)
- [Tujuan CI pada Proyek Ini](#tujuan-ci-pada-proyek-ini)
- [Konfigurasi Workflow](#konfigurasi-workflow)
- [Cara Kerja Workflow](#cara-kerja-workflow)
- [Menjalankan Test Secara Lokal](#menjalankan-test-secara-lokal)
- [Status Badge](#status-badge)
- [Riwayat CI Run](#riwayat-ci-run)

---

## Apa itu GitHub Actions?

GitHub Actions adalah layanan CI/CD bawaan GitHub yang memungkinkan kita menjalankan otomatisasi (seperti testing, linting, atau deployment) setiap kali ada perubahan kode di repository.

Dengan GitHub Actions, setiap kali ada **push** atau **pull request** ke branch utama, sistem akan otomatis:
1. Menyiapkan environment PHP + MySQL
2. Menginstal semua dependency
3. Menjalankan seluruh test suite
4. Melaporkan hasilnya langsung di GitHub

---

## Tujuan CI pada Proyek Ini

- ✅ Memastikan kode yang di-push tidak merusak fitur yang sudah berjalan
- ✅ Mendeteksi error lebih awal sebelum masuk ke branch utama
- ✅ Menjaga kualitas kode secara konsisten di semua anggota tim
- ✅ Dokumentasi otomatis apakah test pass/fail di setiap PR

---

## Konfigurasi Workflow

Buat file berikut di repository:

**Path:** `.github/workflows/ci.yml`

```yaml
name: CI — StyleIt

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main, develop]

jobs:
  laravel-tests:
    runs-on: ubuntu-latest

    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: styleit_test
        ports:
          - 3306:3306
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=5

    steps:
      - name: Checkout kode
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mbstring, bcmath, pdo, pdo_mysql
          coverage: none

      - name: Salin file .env
        run: cp .env.example .env.testing

      - name: Konfigurasi .env untuk testing
        run: |
          sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env.testing
          sed -i 's/DB_HOST=.*/DB_HOST=127.0.0.1/' .env.testing
          sed -i 's/DB_PORT=.*/DB_PORT=3306/' .env.testing
          sed -i 's/DB_DATABASE=.*/DB_DATABASE=styleit_test/' .env.testing
          sed -i 's/DB_USERNAME=.*/DB_USERNAME=root/' .env.testing
          sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=root/' .env.testing

      - name: Install Composer dependencies
        run: composer install --prefer-dist --no-interaction --no-progress

      - name: Generate application key
        run: php artisan key:generate --env=testing

      - name: Jalankan migrasi database
        run: php artisan migrate --env=testing --force

      - name: Jalankan test
        run: php artisan test --env=testing
```

---

## Cara Kerja Workflow

### Trigger

Workflow berjalan otomatis saat:
- Ada **push** ke branch `main` atau `develop`
- Ada **pull request** yang menarget branch `main` atau `develop`

### Tahapan (Steps)

| # | Step | Keterangan |
|---|------|------------|
| 1 | Checkout | Mengambil kode terbaru dari repository |
| 2 | Setup PHP 8.2 | Menyiapkan PHP dengan ekstensi yang dibutuhkan |
| 3 | Salin .env | Membuat file konfigurasi untuk environment testing |
| 4 | Konfigurasi DB | Mengarahkan koneksi ke MySQL service yang berjalan di CI |
| 5 | Composer install | Menginstal semua dependency backend |
| 6 | Key generate | Membuat APP_KEY untuk enkripsi session/cookie |
| 7 | Migrate | Membuat struktur tabel di database testing |
| 8 | Run test | Menjalankan semua test dan melaporkan hasilnya |

> **Catatan:** Step seeder tidak dimasukkan karena saat ini data kategori, paket, addon, dan portofolio masih menggunakan `App\Support\PreviewData` (hardcoded). Seeder perlu ditambahkan ke workflow setelah migrasi data ke database dilakukan.

### Hasil di GitHub

Setelah workflow selesai, hasilnya akan muncul di:
- **Tab Actions** repository — untuk melihat log lengkap setiap step
- **Halaman Pull Request** — berupa tanda ✅ (pass) atau ❌ (fail) di bagian bawah PR

---

## Menjalankan Test Secara Lokal

Sebelum push, pastikan test sudah pass di lokal menggunakan XAMPP:

```bash
# 1. Pastikan XAMPP (Apache + MySQL) sudah berjalan

# 2. Buat database testing terpisah di phpMyAdmin
#    Nama: styleit_test

# 3. Buat file .env.testing (salin dari .env, ubah DB_DATABASE)
cp .env .env.testing

# 4. Edit DB_DATABASE di .env.testing menjadi styleit_test

# 5. Jalankan migrasi untuk database testing
php artisan migrate --env=testing

# 6. Jalankan semua test
php artisan test

# Atau jalankan test per kelompok
php artisan test --filter=AuthTest
php artisan test --filter=CartTest
php artisan test --filter=BookingTest
```

> **Catatan penting:** Karena fitur keranjang dan checkout saat ini menyimpan data di session (`cart`, `preview_bookings`), test yang menguji alur ini perlu menggunakan `$this->withSession([...])` atau menjalankannya sebagai request berantai dalam satu test case agar session tidak hilang antar request.

---

## Status Badge

Tambahkan badge berikut ke bagian atas `README.md` setelah workflow pertama kali berhasil dijalankan:

```markdown
![CI Status](https://github.com/USERNAME/REPO-NAME/actions/workflows/ci.yml/badge.svg)
```

Ganti `USERNAME` dan `REPO-NAME` dengan username GitHub dan nama repository proyek StyleIt.

**Contoh tampilan badge:**

![CI](https://img.shields.io/badge/CI-passing-brightgreen) ← akan terlihat seperti ini saat semua test pass.

---

## Riwayat CI Run

> Screenshot hasil tab Actions akan dilampirkan di sini setelah workflow pertama kali dijalankan di repository.

| Tanggal | Branch | Status | Keterangan |
|---------|--------|--------|------------|
| — | — | — | Belum ada run tercatat |

---

> **Tips:** Jika CI gagal karena file upload di `CheckoutController` (validasi `proof_image`), pastikan test menggunakan `UploadedFile::fake()->image('bukti.jpg')` dari `Illuminate\Http\UploadedFile` untuk mensimulasikan upload file tanpa membutuhkan file nyata.
