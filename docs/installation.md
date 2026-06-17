# Installation Documentation

Dokumen ini menjelaskan langkah-langkah instalasi project StyleIt Project berbasis Laravel.

## 1. Persyaratan Sistem

Sebelum menjalankan project, pastikan perangkat sudah memiliki beberapa kebutuhan berikut:

- PHP 8.2 atau versi yang sesuai dengan project.
- Composer.
- Node.js dan NPM.
- MySQL atau database lain yang didukung Laravel.
- Git.
- Visual Studio Code atau editor lain.
- Browser.

## 2. Clone Repository

Clone repository dari GitHub:
# Installation Documentation

Dokumen ini menjelaskan langkah-langkah instalasi project StyleIt berbasis Laravel.

## Daftar Isi

- [1. Persyaratan Sistem](#1-persyaratan-sistem)
- [2. Clone Repository](#2-clone-repository)
- [3. Install Dependency](#3-install-dependency)
- [4. Konfigurasi Environment](#4-konfigurasi-environment)
- [5. Konfigurasi Database](#5-konfigurasi-database)
- [6. Migrasi dan Seeder Database](#6-migrasi-dan-seeder-database)
- [7. Menjalankan Project](#7-menjalankan-project)
- [8. Konfigurasi Storage](#8-konfigurasi-storage)
- [9. Git Workflow](#9-git-workflow)
- [10. Troubleshooting](#10-troubleshooting)

---

## 1. Persyaratan Sistem

Sebelum menjalankan project, pastikan perangkat sudah memiliki kebutuhan berikut:

| Kebutuhan | Versi Minimum | Keterangan |
|---|---|---|
| PHP | 8.2 | Sesuaikan dengan konfigurasi project |
| Composer | 2.x | Dependency manager PHP |
| Node.js | 18.x | Untuk kompilasi aset frontend |
| NPM | 9.x | Disertakan bersama Node.js |
| MySQL | 8.0 | Atau database lain yang didukung Laravel |
| Git | 2.x | Untuk version control |
| XAMPP / Laragon | Terbaru | Untuk menjalankan MySQL secara lokal |
| Visual Studio Code | Terbaru | Atau editor lain sesuai preferensi |
| Browser | Terbaru | Chrome, Firefox, Edge, dll. |

---

## 2. Clone Repository

Clone repository dari GitHub ke direktori lokal:

```bash
git clone https://github.com/silvanayuliadamara/styleit.project.git
```

Masuk ke direktori project:

```bash
cd styleit.project
```

---

## 3. Install Dependency

### 3.1 PHP Dependency (Composer)

Install seluruh dependency Laravel menggunakan Composer:

```bash
composer install
```

Jika muncul peringatan soal versi, gunakan flag berikut:

```bash
composer install --ignore-platform-reqs
```

### 3.2 JavaScript Dependency (NPM)

Install dependency frontend:

```bash
npm install
```

---

## 4. Konfigurasi Environment

### 4.1 Salin File `.env`

Salin file environment contoh menjadi file `.env` aktif:

```bash
cp .env.example .env
```

### 4.2 Generate Application Key

Generate key unik untuk enkripsi aplikasi:

```bash
php artisan key:generate
```

### 4.3 Konfigurasi File `.env`

Buka file `.env` dan sesuaikan konfigurasi berikut:

```env
APP_NAME=StyleIt
APP_ENV=local
APP_KEY=                   # Terisi otomatis setelah key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=styleit_db     # Sesuaikan dengan nama database yang dibuat
DB_USERNAME=root           # Sesuaikan dengan username MySQL lokal
DB_PASSWORD=               # Sesuaikan dengan password MySQL lokal

FILESYSTEM_DISK=public
```

---

## 5. Konfigurasi Database

### 5.1 Buat Database Baru

Buka phpMyAdmin (http://localhost/phpmyadmin) atau gunakan MySQL CLI, lalu buat database baru:

```sql
CREATE DATABASE styleit_db;
```

Pastikan nama database sesuai dengan nilai `DB_DATABASE` di file `.env`.

### 5.2 Verifikasi Koneksi

Pastikan MySQL sudah berjalan melalui XAMPP Control Panel sebelum lanjut ke langkah migrasi.

---

## 6. Migrasi dan Seeder Database

### 6.1 Jalankan Migrasi

Buat seluruh tabel database berdasarkan file migrasi:

```bash
php artisan migrate
```

### 6.2 Jalankan Seeder (Opsional)

Isi data awal ke dalam database:

```bash
php artisan db:seed
```

Atau jalankan migrasi dan seeder sekaligus:

```bash
php artisan migrate --seed
```

Jika ingin mengulang dari awal (reset seluruh tabel):

```bash
php artisan migrate:fresh --seed
```

> ⚠️ Perintah `migrate:fresh` akan menghapus seluruh data yang ada di database. Gunakan hanya di environment lokal.

---

## 7. Menjalankan Project

### 7.1 Build Aset Frontend

Kompilasi aset CSS dan JavaScript:

```bash
npm run dev
```

Untuk mode production:

```bash
npm run build
```

### 7.2 Jalankan Development Server

Jalankan server Laravel:

```bash
php artisan serve
```

Project dapat diakses di browser melalui:

```
http://localhost:8000
```

> Untuk menjalankan frontend (Vite) dan backend (Laravel) secara bersamaan, buka dua terminal terpisah — satu untuk `npm run dev` dan satu untuk `php artisan serve`.

---

## 8. Konfigurasi Storage

Buat symbolic link agar file yang diupload bisa diakses publik:

```bash
php artisan storage:link
```

Pastikan folder `storage/app/public` sudah ada dan memiliki permission yang benar.

---

## 9. Git Workflow

Project StyleIt menggunakan branching strategy berikut:

| Branch | Fungsi |
|---|---|
| `main` | Branch production — hanya diperbarui melalui PR yang sudah disetujui |
| `development` | Branch integrasi utama — semua fitur di-merge ke sini |
| `nama_anggota` | Branch per anggota tim — tempat pengerjaan fitur masing-masing |

### 9.1 Alur Kerja Harian

Switch ke branch pribadi sebelum mulai bekerja:

```bash
git switch nama_branch_kamu
```

Ambil perubahan terbaru dari `development`:

```bash
git pull origin development
```

Setelah selesai mengerjakan fitur, commit dan push ke branch pribadi:

```bash
git add .
git commit -m "feat: deskripsi singkat perubahan"
git push origin nama_branch_kamu
```

Buat Pull Request dari branch pribadi ke `development` melalui GitHub.

### 9.2 Konvensi Commit Message

| Prefix | Digunakan untuk |
|---|---|
| `feat:` | Menambahkan fitur baru |
| `fix:` | Memperbaiki bug |
| `docs:` | Perubahan dokumentasi |
| `refactor:` | Refactoring kode tanpa mengubah fungsionalitas |
| `style:` | Perubahan tampilan/CSS |
| `test:` | Menambahkan atau mengubah test |
| `chore:` | Pemeliharaan project (config, dependency, dll.) |

---

## 10. Troubleshooting

### `php artisan key:generate` tidak bisa dijalankan

Pastikan file `.env` sudah ada. Jika belum:

```bash
cp .env.example .env
php artisan key:generate
```

---

### Error koneksi database

- Pastikan MySQL sudah berjalan di XAMPP Control Panel.
- Periksa kembali nilai `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`.
- Pastikan database sudah dibuat sebelum menjalankan `php artisan migrate`.

---

### Error `Class not found` atau `Target class does not exist`

Jalankan perintah berikut untuk memperbarui autoload Composer:

```bash
composer dump-autoload
```

---

### Aset CSS/JS tidak tampil

Pastikan `npm run dev` sedang berjalan, atau jalankan:

```bash
npm run build
```

Kemudian reload browser dengan hard refresh (`Ctrl + Shift + R`).

---

### File upload tidak bisa diakses

Pastikan symbolic link storage sudah dibuat:

```bash
php artisan storage:link
```

Jika sudah ada dan masih error, hapus dulu lalu buat ulang:

```bash
rm public/storage
php artisan storage:link
```

---

### Error saat `composer install` karena versi PHP

Gunakan flag berikut untuk melewati pengecekan versi:

```bash
composer install --ignore-platform-reqs
```

---

### Perubahan tidak muncul setelah pull dari GitHub

Cache config atau view mungkin perlu di-refresh:

```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

```bash
git clone https://github.com/silvanayuliadamara/styleit.project.git
