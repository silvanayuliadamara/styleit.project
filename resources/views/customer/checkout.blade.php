@extends('layouts.app', ['title' => 'Checkout Booking'])

@section('content')
<section class="page-hero compact"><div class="container"><p class="hero-label">CHECKOUT</p><h1>Checkout Booking</h1><p>Upload bukti DP jika sudah transfer. Bisa juga checkout dulu dan upload nanti lewat admin.</p></div></section>
<section class="section-padding">
    <div class="container">
        <form action="{{ route('customer.checkout.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="glass-card">
                        <h3>Data Booking</h3>
                        @foreach($cart as $item)
                            <div class="booking-list-item">
                                <div><strong>{{ $item['package_name'] }}</strong><p>{{ \Illuminate\Support\Carbon::parse($item['booking_date'])->format('d M Y') }} · DP Rp{{ number_format($item['dp_amount'], 0, ',', '.') }}</p></div>
                                <strong>Rp{{ number_format($item['total_price'], 0, ',', '.') }}</strong>
                            </div>
                        @endforeach
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control rounded-4" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="Contoh: 08123456789">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Username Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-4 bg-light border-end-0">@</span>
                                    <input type="text" name="instagram" class="form-control rounded-end-4 border-start-0" value="{{ old('instagram', auth()->user()->instagram) }}" placeholder="username_ig">
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <textarea name="address" class="form-control rounded-4" rows="2" placeholder="Masukkan alamat lengkap Anda">{{ old('address', auth()->user()->address) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-bold">Catatan untuk admin</label>
                            <textarea name="notes" class="form-control rounded-4" rows="4" placeholder="Contoh: request look natural, alamat acara, jam acara, dll.">{{ old('notes') }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label class="form-label fw-bold">Upload Bukti DP</label>
                            <input type="file" name="proof_image" class="form-control rounded-4" accept="image/*">
                            <small class="muted">Format jpg/png/webp, maksimal 2 MB.</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="booking-panel">
                        <h3>Pembayaran DP</h3>
                        <p class="muted">Transfer DP ke rekening/nomor yang ditentukan admin, lalu upload bukti.</p>
                        <div class="total-box">
                            <div><span>Total layanan</span><strong>Rp{{ number_format(collect($cart)->sum('total_price'), 0, ',', '.') }}</strong></div>
                            <div><span>Total DP</span><strong>Rp{{ number_format(collect($cart)->sum('dp_amount'), 0, ',', '.') }}</strong></div>
                            <div><span>Sisa bayar</span><strong>Rp{{ number_format(collect($cart)->sum('remaining_payment'), 0, ',', '.') }}</strong></div>
                        </div>
                        <button type="submit" class="btn-dark-custom w-100 mt-3 border-0">Buat Booking</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
