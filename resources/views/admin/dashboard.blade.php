@extends('layouts.admin', ['title' => 'Dashboard Admin — LYB'])

@section('admin_content')
    <header class="lyb-admin-page-header">
        <h2>Admin Baju</h2>
        <p>Akses terbatas: transaksi & pencatatan baju.</p>
    </header>

    <section class="lyb-admin-section">
        <h3>Pencatatan Baju yang Dibooking</h3>
        <div class="lyb-admin-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Paket Baju</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>BOOK-004</strong></td>
                            <td>Dila Pratama</td>
                            <td><span class="lyb-package-name"><i class="bi bi-bag-heart"></i> Paket Baju Pasangan</span></td>
                            <td>Sabtu, 10 Mei 2025</td>
                            <td>Rp750.000</td>
                            <td><span class="lyb-admin-status expired">Expired</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.bookings.show', 'BOOK-004') }}"
                                    class="lyb-admin-action-btn">Lihat</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="lyb-admin-section">
        <h3>WhatsApp Support Baju</h3>
        <div class="lyb-whatsapp-card">
            <div class="lyb-whatsapp-left">
                <div class="lyb-whatsapp-icon"><i class="bi bi-whatsapp"></i></div>
                <div>
                    <strong>Nomor Aktif: +62 813-9876-5432</strong>
                    <p>2 chat baru menunggu balasan</p>
                </div>
            </div>
            <a href="https://wa.me/6281398765432" target="_blank" class="lyb-whatsapp-button">Buka WhatsApp</a>
        </div>
    </section>
@endsection
