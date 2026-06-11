@extends('layouts.app', ['title' => 'Invoice — LYB'])
@section('content')
    <section class="invoice-page">
        <div class="container">
            <div class="invoice-actions">
                <a href="{{ route('admin.dashboard') }}" class="invoice-back-btn"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="button" class="invoice-print-btn" onclick="window.print()"><i class="bi bi-printer"></i> Cetak
                    Invoice</button>
            </div>

            <div class="invoice-card">
                <div class="invoice-header">
                    <div class="invoice-brand">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo LYB">
                        <div>
                            <h1>LISA YULI BELTI</h1>
                            <p>Wedding Gallery dan Makeup Artist</p>
                        </div>
                    </div>
                    <div class="invoice-title">
                        <span>Invoice</span>
                        <strong>INV-BOOK-004</strong>
                        <small>Booking: BOOK-004</small>
                    </div>
                </div>

                <div class="invoice-divider"></div>

                <div class="invoice-info-grid">
                    <div class="invoice-billed">
                        <h2>Ditagihkan kepada</h2>
                        <strong>Dila Pratama</strong>
                        <p>0878-3344-5566</p>
                        <p>IG: @dilapratama</p>
                        <p>Jl. Mawar No. 21, Surabaya</p>
                    </div>

                    <div class="invoice-status-box">
                        <div>
                            <span>Status</span>
                            <strong class="invoice-status expired">Expired</strong>
                        </div>
                        <div>
                            <span>Tanggal Booking:</span>
                            <strong>Sabtu, 10 Mei 2025</strong>
                        </div>
                    </div>
                </div>

                <div class="invoice-table-wrap">
                    <table class="table invoice-table mb-0">
                        <thead>
                            <tr>
                                <th>Layanan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Paket Baju Pasangan</strong></td>
                                <td class="text-end"><strong>Rp750.000</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="invoice-summary">
                    <div><span>Total Harga Layanan</span><strong>Rp750.000</strong></div>
                    <div><span>DP</span><strong>Rp250.000</strong></div>
                    <div><span>Total Pembayaran Diterima</span><strong>Rp0</strong></div>
                    <div class="invoice-summary-total"><span>Sisa Pelunasan</span><strong>Rp750.000</strong></div>
                </div>

                <div class="invoice-footer-note">
                    <h3>LISA YULI BELTI — Wedding Gallery dan Makeup Artist</h3>
                    <p>Jl. Anggrek Cendana No. 17, Pekanbaru, Riau • +62 812-3456-7890</p>
                    <p>Terima kasih telah mempercayakan momen spesial Anda kepada kami.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
