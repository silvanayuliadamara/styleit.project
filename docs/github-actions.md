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

Dengan GitHub Actions, setiap kali ada **pull request** ke branch `development`, sistem akan otomatis menjalankan dua workflow secara terpisah:
1. **Code Linting** — memeriksa kualitas sintaks PHP, HTML, dan CSS
2. **Automated Testing** — menjalankan migrasi dan seluruh test suite Laravel

---

## Tujuan CI pada Proyek Ini

- ✅ Memastikan kode yang di-push tidak merusak fitur yang sudah berjalan
- ✅ Mendeteksi error sintaks PHP, HTML, dan CSS sebelum masuk ke branch utama
- ✅ Menjaga kualitas kode secara konsisten di semua anggota tim
- ✅ Dokumentasi otomatis apakah test pass/fail di setiap PR

---

## Konfigurasi Workflow

Project StyleIt menggunakan **dua workflow** yang berjalan secara terpisah. Kedua file berada di folder `.github/workflows/`.

---

### Workflow 1 — Code Linting

**Path:** `.github/workflows/code-linting.yml`

Workflow ini memeriksa kualitas sintaks kode setiap kali ada Pull Request ke branch `development`.

```yaml
name: Code Linting PHP HTML CSS
on:
  pull_request:
    branches:
      - development
jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: "8.4"
          extensions: mbstring, fileinfo, openssl, tokenizer, xml, ctype, json
          coverage: none

      - name: Check PHP Syntax
        run: find app routes database -name "*.php" -print0 | xargs -0 -n1 php -l

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: "20"

      - name: Install HTMLHint and Stylelint
        run: npm install -g htmlhint stylelint stylelint-config-recommended

      - name: Check HTML Files
        run: |
          if find . -name "*.html" | grep -q .; then
            htmlhint "**/*.html"
          else
            echo "Tidak ada file .html, project menggunakan Laravel Blade."
          fi

      - name: Create Stylelint Config
        run: |
          cat > .stylelintrc.json << 'EOF'
          {
            "extends": "stylelint-config-recommended",
            "rules": {
              "comment-whitespace-inside": null,
              "color-function-alias-notation": null,
              "media-feature-range-notation": null,
              "rule-empty-line-before": null,
              "color-hex-length": null,
              "shorthand-property-no-redundant-values": null,
              "declaration-block-no-redundant-longhand-properties": null,
              "no-descending-specificity": null
            }
          }
          EOF

      - name: Check CSS Files
        run: stylelint "public/**/*.css"
```

---

### Workflow 2 — Automated Testing

**Path:** `.github/workflows/test-before-merge.yml`

Workflow ini menjalankan migrasi database dan seluruh test Laravel setiap kali ada Pull Request ke branch `development`. Database yang digunakan adalah **SQLite** agar lebih ringan dan tidak membutuhkan service MySQL terpisah.

```yaml
name: Automasi Testing Sebelum Merge
on:
  pull_request:
    branches:
      - development
jobs:
  test:
    runs-on: ubuntu-latest
    env:
      APP_ENV: testing
      CACHE_STORE: array
      CACHE_DRIVER: array
      SESSION_DRIVER: array
      QUEUE_CONNECTION: sync
      DB_CONNECTION: sqlite
      DB_DATABASE: database/database.sqlite
    steps:
      - name: Checkout Repository
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: "8.4"
          extensions: mbstring, pdo, pdo_sqlite, fileinfo, openssl, tokenizer, xml, ctype, json
          coverage: none

      - name: Install Composer Dependencies
        run: composer install --no-interaction --prefer-dist --no-progress

      - name: Copy Environment File
        run: cp .env.example .env

      - name: Generate Application Key
        run: php artisan key:generate

      - name: Prepare SQLite Database
        run: |
          mkdir -p database
          touch database/database.sqlite

      - name: Run Database Migration
        run: php artisan migrate --force

      - name: Clear Laravel Cache
        run: php artisan optimize:clear

      - name: Check PHP Syntax
        run: find app routes database -name "*.php" -print0 | xargs -0 -n1 php -l

      - name: Run Laravel Tests
        run: php artisan test
```

---

## Cara Kerja Workflow

### Trigger

Kedua workflow berjalan otomatis saat ada **pull request** yang menarget branch `development`. Tidak ada trigger untuk `push` langsung.

### Tahapan — Workflow 1: Code Linting

| # | Step | Keterangan |
|---|------|------------|
| 1 | Checkout | Mengambil kode terbaru dari repository |
| 2 | Setup PHP 8.4 | Menyiapkan PHP dengan ekstensi yang dibutuhkan |
| 3 | Check PHP Syntax | Memeriksa sintaks seluruh file `.php` di folder `app`, `routes`, dan `database` |
| 4 | Setup Node.js 20 | Menyiapkan Node.js untuk menjalankan tool linting frontend |
| 5 | Install HTMLHint & Stylelint | Menginstal tool pemeriksa HTML dan CSS |
| 6 | Check HTML | Memeriksa file `.html` — jika tidak ada, ditampilkan pesan bahwa project menggunakan Blade |
| 7 | Check CSS | Memeriksa kualitas file CSS di folder `public/` |

### Tahapan — Workflow 2: Automated Testing

| # | Step | Keterangan |
|---|------|------------|
| 1 | Checkout | Mengambil kode terbaru dari repository |
| 2 | Setup PHP 8.4 | Menyiapkan PHP dengan ekstensi termasuk `pdo_sqlite` |
| 3 | Composer install | Menginstal semua dependency backend |
| 4 | Copy .env | Menyalin `.env.example` menjadi `.env` untuk environment testing |
| 5 | Key generate | Membuat APP_KEY untuk enkripsi session/cookie |
| 6 | Prepare SQLite | Membuat file `database/database.sqlite` sebagai database testing |
| 7 | Migrate | Membuat struktur tabel di database SQLite |
| 8 | Clear cache | Membersihkan cache Laravel agar tidak mengganggu hasil test |
| 9 | Check PHP Syntax | Pemeriksaan sintaks PHP tambahan sebelum test dijalankan |
| 10 | Run test | Menjalankan semua test dan melaporkan hasilnya |

> **Catatan:** Database testing menggunakan SQLite (bukan MySQL) agar CI berjalan lebih cepat dan tidak membutuhkan service database terpisah. Karena itu, pastikan tidak ada query yang hanya berjalan di MySQL (seperti `FULLTEXT` atau `JSON_EXTRACT`) di dalam migration atau model.

### Hasil di GitHub

Setelah workflow selesai, hasilnya akan muncul di:
- **Tab Actions** repository — untuk melihat log lengkap setiap step
- **Halaman Pull Request** — berupa tanda ✅ (pass) atau ❌ (fail) di bagian bawah PR

---

## Menjalankan Test Secara Lokal

Sebelum membuat Pull Request, pastikan test sudah pass di lokal:

```bash
# 1. Buat file SQLite untuk testing
touch database/database.sqlite

# 2. Jalankan migrasi dengan koneksi SQLite
php artisan migrate --env=testing

# 3. Jalankan semua test
php artisan test

# Atau jalankan test per kelompok
php artisan test --filter=AuthTest
php artisan test --filter=CartTest
php artisan test --filter=BookingTest
```

> **Catatan penting:** Karena fitur keranjang dan checkout saat ini menyimpan data di session (`cart`, `preview_bookings`), test yang menguji alur ini perlu menggunakan `$this->withSession([...])` atau menjalankannya sebagai request berantai dalam satu test case agar session tidak hilang antar request.

> **Catatan upload file:** Jika CI gagal karena validasi `proof_image` di `CheckoutController`, pastikan test menggunakan `UploadedFile::fake()->image('bukti.jpg')` dari `Illuminate\Http\UploadedFile` untuk mensimulasikan upload file tanpa file nyata.

---

## Status Badge

Tambahkan badge berikut ke bagian atas `README.md` setelah workflow pertama kali berhasil dijalankan:

```markdown
![Linting](https://github.com/USERNAME/REPO-NAME/actions/workflows/code-linting.yml/badge.svg)
![Testing](https://github.com/USERNAME/REPO-NAME/actions/workflows/test-before-merge.yml/badge.svg)
```

Ganti `USERNAME` dan `REPO-NAME` dengan username GitHub dan nama repository proyek StyleIt.

---

## Riwayat CI Run

> Screenshot hasil tab Actions akan dilampirkan di sini setelah workflow pertama kali dijalankan di repository.

| Tanggal | Branch | Workflow | Status | Keterangan |
|---------|--------|----------|--------|------------|
| — | — | — | — | Belum ada run tercatat |
