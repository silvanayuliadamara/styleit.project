# Dokumentasi Kontribusi Pengembang (StyleIt Project)

Dokumen ini disusun untuk menjelaskan kontribusi masing-masing anggota tim dalam proyek **StyleIt** (Lisa Yuli Belti Wedding Gallery & MUA). Dokumen ini memberikan gambaran objektif mengenai pembagian tugas, metrik baris kode (*Lines of Code*), dan penjelasan mengenai perbedaan jumlah komit di GitHub.

---

## 👥 Profil Tim & Pembagian Modul Utama

| Nama Anggota | NIM | Peran Utama | Modul / Tanggung Jawab Utama |
| :--- | :--- | :--- | :--- |
| **Silvana Yulia Damara** | 2411081023 | Project Manager, Backend & Integration Developer | Manajemen repositori & integrasi, Autentikasi (Captcha, Remember Me), Sistem Transaksi Inti & Optimasi (sentralisasi `BookingService` & penanganan concurrency locking pada checkout), Scheduled Artisan Command untuk pembatalan booking otomatis, fitur pembatalan pengajuan, validasi refund dinamis di Dashboard, serta perbaikan layout responsif. |
| **Salwa Aprilia** | 2411082029 | System Analyst & Frontend Dev | Desain UI/UX, implementasi halaman publik (Home, Profil, Portofolio, Pricelist, Detail Layanan) beserta seluruh aset gambar dan CSS pendukung. |
| **Nurul Asyifa** | 2411082018 | Lead Programmer (Backend) | Alur transaksi utama (Keranjang Belanja, Checkout, Integrasi Payment Gateway Midtrans), penjadwalan pembatalan otomatis (*cron job*), dan email otomatis. |
| **Muhammad Abdul Hafiz** | 2311082025 | Quality Assurance (QA) | Pembuatan unit testing (`php artisan test`), skenario pengujian fungsionalitas, verifikasi bug, dan penyusunan dokumentasi pengujian. |

---

## 📊 Penjelasan Metrik Kontribusi di GitHub

Jika melihat grafik kontribusi di GitHub, terdapat perbedaan jumlah komit yang cukup signifikan. Hal ini dikarenakan adanya perbedaan gaya kerja (*workflow*) dan peran masing-masing anggota:

### 1. Perbedaan Jumlah Komit vs. Baris Kode (Lines of Code)
* **Gaya Komit Milestones (Nurul & Salwa):** Nurul (Lead Programmer) dan Salwa (Frontend Developer) bekerja secara lokal dan baru melakukan komit ketika seluruh fitur/modul selesai sepenuhnya (*milestone-based commits*).
  * **Nurul Asyifa** memiliki **42 komit**, tetapi berkontribusi sebanyak **8.781++ baris kode**.
  * **Salwa Aprilia** memiliki **41 komit**, tetapi berkontribusi sebanyak **7.534++ baris kode**.
  Ini membuktikan bahwa kontribusi kode riil mereka sangat besar.
* **Gaya Komit Aktif & Inkremental (Silvana):** Sebagai Project Manager dan Backend Developer, Silvana bertanggung jawab penuh atas integrasi arsitektur sistem, optimalisasi backend (seperti `BookingService` dan *concurrency control*), penulisan skrip otomasi cron job, perbaikan bug di seluruh modul, serta penyempurnaan UI. Gaya komit Silvana bersifat berkala dan detail (*feature & refactor-driven commits*), sehingga jumlah komitnya tercatat **163 komit** dengan kontribusi **43.244++ baris kode** yang mendokumentasikan evolusi sistem secara bertahap.

### 2. Peran Non-Code/QA (Hafiz)
* Sebagai Quality Assurance (QA), Muhammad Abdul Hafiz fokus pada pembuatan skenario uji dan pengujian fungsionalitas aplikasi. Tugas QA berfokus pada pembuatan unit & feature test di direktori `tests/` serta pengujian manual (*black-box testing*), dengan kontribusi tercatat **10 komit** dan **546++ baris kode**.

---

## 📈 Kesimpulan Kontribusi Tim

Seluruh anggota tim telah berkontribusi secara merata sesuai dengan peran masing-masing. Proyek **StyleIt** berhasil diselesaikan dengan baik berkat kolaborasi yang terstruktur antara manajemen proyek, desain frontend, pemrograman backend, dan penjaminan kualitas (QA).