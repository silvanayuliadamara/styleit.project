# Laporan Perubahan Terperinci (StyleIt Project)

Laporan ini menyajikan daftar penambahan fitur, perbaikan bug, dan perubahan struktur data yang telah diimplementasikan baru-baru ini (kemarin hingga hari ini) untuk menyempurnakan alur kerja sistem pemesanan MUA pada aplikasi **StyleIt Project**.

---

## 1. Ringkasan Perubahan Utama

Perubahan terbaru mencakup penyelesaian fungsionalitas inti untuk empat domain utama:
- **Checkout & Pembayaran**: Integrasi metode pembayaran **Transfer Bank Manual** di samping Midtrans Payment Gateway, pembaruan profil pelanggan otomatis saat checkout, verifikasi sisa kuota jadwal multi-hari, dan simulasi pembayaran lokal (sandbox).
- **Sistem Ulasan (Reviews)**: Implementasi halaman ulasan per paket dengan filter detail (rating, komentar, foto), serta penyematan sistem badge "Best Seller" / "Populer" yang dinamis dan teroptimasi cache.
- **Laporan Keuangan Owner**: Rekapitulasi keuangan bulanan/tahunan yang mencakup perhitungan biaya bersih owner setelah dikurangi biaya pihak ketiga (melati, henna, lainnya) dan fee gateway, serta dukungan ekspor laporan ke format **CSV** dan **PDF (Landscape A4)**.
- **Otomatisasi & Pembatalan**: Migrasi penambahan kolom pengembalian DP pada pembatalan transaksi, decrement/increment otomatis kuota jadwal saat status booking berubah, serta script otomatisasi pembatalan booking yang kadaluarsa.

---

## 2. Detail Implementasi per Fitur

### A. Alur Checkout & Pembayaran
1. **Pilihan Transfer Manual**: 
   - Menambahkan opsi pembayaran `Transfer Manual` saat checkout. Pelanggan diarahkan ke halaman instruksi transfer manual untuk mengunggah gambar bukti transfer.
   - Menyimpan bukti transfer di storage publik (`payment_proofs`) dan memperbarui status pembayaran booking menjadi `dp_diupload` serta status booking menjadi `menunggu_konfirmasi`.
2. **Pembaruan Profil Pelanggan**:
   - Menambahkan logika pembaruan nomor telepon, Instagram, dan alamat rumah pelanggan secara otomatis di database profil pengguna saat checkout dilakukan.
3. **Pemesanan Multi-Hari (Wedding / Prewedding)**:
   - Validasi ketersediaan jadwal untuk tanggal acara kedua (`booking_date_2`) dan ketiga (`booking_date_3`).
   - Menyimpan informasi tanggal dan slot waktu tambahan tersebut ke dalam kolom `notes` pesanan dengan format terstruktur agar dapat diproses oleh sistem penjadwalan.
4. **Validasi Kuota & Jadwal**:
   - Memastikan pemesanan kategori Wedding/Prewedding tidak melebihi kuota (maksimal 2 pemesanan di slot pagi, 1 pemesanan di slot siang, dan 1 pemesanan di slot sore) di seluruh tanggal acara yang diajukan.
   - Pengecekan status pemblokiran tanggal oleh owner sebelum pemesanan diproses.

### B. Manajemen Ulasan & Popularitas Paket
1. **Halaman Detail Ulasan**:
   - Menambahkan fitur penyaringan ulasan berdasarkan bintang (1-5), ulasan dengan komentar, atau ulasan dengan foto.
   - Menghitung statistik distribusi ulasan (total review rata-rata bintang dan persentase jumlah rating per bintang).
2. **Model Rating Dinamis & Cache**:
   - Paket populer kini ditentukan secara cerdas berdasarkan top 3 pesanan terbanyak dalam status aktif atau paket dengan rating rata-rata $\ge$ 4.5 bintang dari minimal 2 ulasan.
   - Hasil perhitungan disimpan di cache (`best_seller_package_id` dan `popular_package_id_X`) selama 60 detik untuk mempercepat performa *load* halaman layanan.

### C. Laporan Keuangan Owner & Ekspor Dokumen
1. **Perhitungan Margin Bersih Owner**:
   - Memecah pengeluaran otomatis untuk pihak ketiga (biaya melati, henna/kuku, dan biaya tambahan lainnya) yang bersumber dari data item paket maupun tambahan *add-ons* pesanan.
   - Perhitungan bersih owner dihitung secara real-time:
     $$\text{Bersih Owner} = \text{Total Dibayar} - \text{Biaya Pihak Lain} - \text{Gateway Fee}$$
2. **Ekspor CSV & PDF**:
   - CSV: Ekspor terperinci dengan separator `;` dan penambahan BOM UTF-8 agar kompatibel dengan Microsoft Excel tanpa merusak format teks.
   - PDF: Membangun template laporan cetak horizontal (A4 landscape) menggunakan library DOMPDF untuk mempermudah owner melakukan arsip cetak.

### D. Otomatisasi Jadwal & Pembatalan (Cron Jobs)
1. **Penanganan Pengembalian DP (Refund)**:
   - Menambahkan kolom status pengembalian DP (`dp_dikembalikan`) dan nominalnya (`jumlah_dp_dikembalikan`) pada database pengajuan pembatalan.
   - Mengubah status pembayaran menjadi `dp_dikembalikan` setelah owner menyetujui pengajuan pembatalan yang telah membayar DP.
2. **Sinkronisasi Kuota Jadwal**:
   - Kuota terpakai pada tabel jadwal otomatis didecrement ketika booking ditolak/dibatalkan oleh admin/owner, atau diincrement kembali jika status diubah menjadi disetujui (`diterima`).
3. **Sistem Pembersihan Otomatis (Cron)**:
   - Pembatalan otomatis untuk pesanan berstatus *pending* yang belum dibayar lewat dari 1 jam (`cancelExpiredBookings`).
   - Penyetujuan otomatis untuk pengajuan pembatalan pelanggan yang tidak diproses owner setelah 24 jam (`autoCancelPendingCancellations`).

---

## 3. Daftar File yang Terlibat

Berikut adalah rincian file yang ditambahkan atau mengalami modifikasi beserta link aksesnya:

### Core Logika & Controller
- [CheckoutController.php](file:///d:/styleit.project%20(3)/styleit.project/app/Http/Controllers/Customer/CheckoutController.php) - Menangani alur proses checkout, integrasi snap token Midtrans, simulasi sandbox, serta upload bukti transfer manual.
- [CheckoutService.php](file:///d:/styleit.project%20(3)/styleit.project/app/Services/CheckoutService.php) - Berisi transaksi database pembuatan booking, pembaruan profil user, penguncian baris (`lockForUpdate`) kuota jadwal, serta verifikasi pesanan multi-hari.
- [OwnerBookingController.php](file:///d:/styleit.project%20(3)/styleit.project/app/Http/Controllers/Owner/OwnerBookingController.php) - Pengelolaan persetujuan pembatalan oleh owner, kalkulasi data laporan keuangan, serta export CSV & PDF.
- [PublicPageController.php](file:///d:/styleit.project%20(3)/styleit.project/app/Http/Controllers/PublicPageController.php) - Membangun logika kalender ketersediaan 60 hari ke depan dan pemanggilan ulasan paket.
- [ReviewController.php](file:///d:/styleit.project%20(3)/styleit.project/app/Http/Controllers/Customer/ReviewController.php) - Mengontrol penyimpanan ulasan pelanggan baru beserta unggahan foto.

### Model Database & Migrasi
- [Booking.php](file:///d:/styleit.project%20(3)/styleit.project/app/Models/Booking.php) - Penambahan dynamic accessors untuk fee payment gateway, prioritas fitting baju, pemecahan pengeluaran pihak ketiga, dan pembersihan otomatis (auto-cancel).
- [CancellationRequest.php](file:///d:/styleit.project%20(3)/styleit.project/app/Models/CancellationRequest.php) - Menambahkan kolom properti pengembalian uang muka DP.
- [ServicePackage.php](file:///d:/styleit.project%20(3)/styleit.project/app/Models/ServicePackage.php) - Menentukan nilai default DP secara dinamis berdasarkan kategori, nama format, dan kalkulasi rating popularitas.
- [2026_07_13_000001_add_dp_dikembalikan_to_cancellation_requests.php](file:///d:/styleit.project%20(3)/styleit.project/database/migrations/2026_07_13_000001_add_dp_dikembalikan_to_cancellation_requests.php) - Migrasi database untuk mencatat status pengembalian DP.

### Rute & Halaman Antarmuka (Views)
- [web.php](file:///d:/styleit.project%20(3)/styleit.project/routes/web.php) - Rute baru untuk ulasan paket, laporan PDF keuangan, penolakan notifikasi pembatalan, dan sub-rute transfer manual.
- [payment-manual-transfer.blade.php](file:///d:/styleit.project%20(3)/styleit.project/resources/views/customer/payment-manual-transfer.blade.php) - Halaman instruksi transfer bank manual dan form unggah struk.
- [ulasan.blade.php](file:///d:/styleit.project%20(3)/styleit.project/resources/views/paket/ulasan.blade.php) - Halaman ulasan lengkap paket dengan visualisasi grafik rating bintang.
- [laporan-pdf.blade.php](file:///d:/styleit.project%20(3)/styleit.project/resources/views/owner/laporan-pdf.blade.php) - Struktur layout cetak PDF laporan keuangan.
