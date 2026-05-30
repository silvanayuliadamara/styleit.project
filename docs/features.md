# Feature Documentation

Dokumen ini menjelaskan fitur-fitur utama pada project StyleIt / Lisa Yuli Belti Wedding Gallery dan Makeup Artist.

---

## 1. Login

### Tujuan Fitur
Fitur login digunakan agar user dapat masuk ke sistem menggunakan akun yang sudah terdaftar.

### Aktor
- Customer
- Admin
- Owner

### Alur Fitur
User membuka halaman login, lalu memasukkan email dan password. Sistem akan memvalidasi data login. Jika data benar, user diarahkan ke halaman dashboard sesuai role masing-masing. Jika data salah, sistem menampilkan pesan error.

### Route / Controller Terkait
- Route: `GET /login`
- Route: `POST /login`
- Controller: `AuthController`

### Screenshot Fitur
![Screenshot Login](screenshots/login.png)

---

## 2. Register

### Tujuan Fitur
Fitur register digunakan agar customer dapat membuat akun baru sebelum melakukan booking layanan.

### Aktor
- Customer

### Alur Fitur
Customer membuka halaman register, lalu mengisi data akun seperti nama, email, password, dan konfirmasi password. Sistem melakukan validasi data. Jika data valid, akun customer disimpan ke database dan customer dapat login ke sistem.

### Route / Controller Terkait
- Route: `GET /register`
- Route: `POST /register`
- Controller: `AuthController`

### Screenshot Fitur
![Screenshot Register](screenshots/register.png)

---

## 3. Home

### Tujuan Fitur
Fitur home digunakan sebagai halaman utama yang menampilkan informasi awal mengenai Lisa Yuli Belti Wedding Gallery dan Makeup Artist.

### Aktor
- Pengunjung
- Customer

### Alur Fitur
User membuka halaman utama website. Sistem menampilkan informasi usaha, banner utama, navigasi menu, ringkasan layanan, portofolio singkat, keunggulan usaha, dan tombol menuju halaman lain seperti layanan atau pricelist.

### Route / Controller Terkait
- Route: `GET /`
- Controller: `PublicPageController`

### Screenshot Fitur
![Screenshot Home](screenshots/home.png)

---

## 4. Profil Usaha

### Tujuan Fitur
Fitur profil usaha digunakan untuk menampilkan informasi mengenai usaha Lisa Yuli Belti Wedding Gallery dan Makeup Artist.

### Aktor
- Pengunjung
- Customer

### Alur Fitur
User membuka halaman profil usaha. Sistem menampilkan informasi tentang usaha, deskripsi layanan, alamat, kontak, jam operasional, dan media sosial yang dapat dihubungi oleh customer.

### Route / Controller Terkait
- Route: `GET /profil`
- Controller: `PublicPageController`

### Screenshot Fitur
![Screenshot Profil Usaha](screenshots/profil-usaha.png)

---

## 5. Portofolio

### Tujuan Fitur
Fitur portofolio digunakan untuk menampilkan dokumentasi hasil layanan Lisa Yuli Belti Wedding Gallery dan Makeup Artist.

### Aktor
- Pengunjung
- Customer

### Alur Fitur
User membuka halaman portofolio. Sistem menampilkan galeri foto hasil layanan, seperti hasil makeup, wedding gallery, prewedding, regular makeup, atau dokumentasi lainnya. User dapat melihat contoh hasil layanan sebelum melakukan booking.

### Route / Controller Terkait
- Route: `GET /portofolio`
- Controller: `PublicPageController`

### Screenshot Fitur
![Screenshot Portofolio](screenshots/portofolio.png)
---

## 6. Pricelist

### Tujuan Fitur
Fitur pricelist digunakan untuk menampilkan daftar harga atau paket layanan yang tersedia.

### Aktor
- Pengunjung
- Customer

### Alur Fitur
User membuka halaman pricelist. Sistem menampilkan daftar paket layanan, harga, DP, dan deskripsi singkat setiap paket. Customer dapat melihat informasi harga sebelum memilih layanan yang ingin dipesan.

### Route / Controller Terkait
- Route: `GET /pricelist`
- Controller: `PublicPageController`

### Screenshot Fitur
![Screenshot Pricelist](screenshots/pricelist.png)

---

## 7. Keranjang

### Tujuan Fitur
Fitur keranjang digunakan untuk menyimpan sementara paket layanan yang dipilih customer sebelum melakukan checkout.

### Aktor
- Customer

### Alur Fitur
Customer memilih paket layanan yang ingin dipesan. Sistem memasukkan paket tersebut ke dalam keranjang. Customer dapat membuka halaman keranjang untuk melihat daftar paket yang dipilih, mengecek detail pesanan, atau menghapus paket sebelum melanjutkan ke checkout.

### Route / Controller Terkait
- Route: `GET /customer/cart`
- Route: `DELETE /customer/cart/{key}`
- Controller: `CartController`

### Screenshot Fitur
![Screenshot Keranjang](screenshots/keranjang.png)

---

## 8. Checkout

### Tujuan Fitur
Fitur checkout digunakan untuk memproses paket layanan yang telah dipilih customer menjadi data booking.

### Aktor
- Customer

### Alur Fitur
Customer membuka halaman checkout setelah memilih paket layanan. Sistem menampilkan detail pesanan, tanggal booking, total harga, DP, dan sisa pembayaran. Customer mengisi data yang diperlukan, lalu sistem menyimpan data booking ke database.

### Route / Controller Terkait
- Route: `GET /customer/checkout`
- Route: `POST /customer/checkout`
- Controller: `CheckoutController`

### Screenshot Fitur
![Screenshot Checkout](screenshots/checkout.png)

---

## 9. Booking Customer

### Tujuan Fitur
Fitur booking customer digunakan untuk menampilkan daftar booking yang telah dibuat oleh customer.

### Aktor
- Customer

### Alur Fitur
Customer membuka halaman booking. Sistem menampilkan daftar booking yang dimiliki customer, seperti nama paket, tanggal booking, total harga, status booking, dan status pembayaran. Customer juga dapat melihat detail booking yang sudah dibuat.

### Route / Controller Terkait
- Route: `GET /customer/bookings`
- Route: `GET /customer/bookings/{booking}`
- Controller: `CustomerBookingController`

### Screenshot Fitur
![Screenshot Booking Customer](screenshots/booking-customer.png)

---

## 10. Dashboard Customer

### Tujuan Fitur
Dashboard customer digunakan untuk menampilkan ringkasan aktivitas customer setelah login.

### Aktor
- Customer

### Alur Fitur
Customer berhasil login ke sistem, lalu diarahkan ke dashboard customer. Sistem menampilkan informasi ringkasan seperti data akun customer, jumlah booking, status booking terbaru, dan akses cepat menuju halaman booking atau layanan.

### Route / Controller Terkait
- Route: `GET /customer/dashboard`
- Controller: `CustomerDashboardController`

### Screenshot Fitur
![Screenshot Dashboard Customer](screenshots/dashboard-customer.png)
