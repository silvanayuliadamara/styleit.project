@extends('layouts.customer', ['title' => 'Ubah Profil Anda — LYB'])

@section('customer_content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer-profile.css') }}">
@endpush

{{-- Page Header --}}
<div class="profile-page-header">
    <h2>Pengaturan Profil</h2>
    <p>Perbarui informasi akun Anda untuk memudahkan proses pemesanan & verifikasi.</p>
</div>

<div class="profile-container">
    {{-- Left Summary Column --}}
    <div class="profile-summary-card lyb-animate-stagger-1">
        <div class="avatar-wrapper">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        </div>
        <div class="profile-summary-name">{{ $user->name }}</div>
        <div class="profile-summary-role">{{ ucfirst(Auth::user()->roles->first()->name ?? Auth::user()->role ?? 'Member') }}</div>

        <div class="profile-details-list">
            <div class="profile-details-item">
                <i class="bi bi-envelope"></i>
                <span class="text-truncate" title="{{ $user->email }}">{{ $user->email }}</span>
            </div>
            <div class="profile-details-item">
                <i class="bi bi-telephone"></i>
                <span>{{ $user->phone }}</span>
            </div>
            @if($user->instagram)
                <div class="profile-details-item">
                    <i class="bi bi-instagram"></i>
                    <span>{{ $user->instagram }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Right Form Column --}}
    <div class="profile-form-card lyb-animate-stagger-2">
        <form action="{{ route('customer.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <h3>Informasi Pribadi</h3>

            <div class="row">
                <div class="col-md-6 form-group-premium">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" 
                           class="form-control form-control-premium @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 form-group-premium">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" 
                           class="form-control form-control-premium @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" required autocomplete="email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 form-group-premium">
                    <label for="phone">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" 
                           class="form-control form-control-premium @error('phone') is-invalid @enderror" 
                           value="{{ old('phone', $user->phone) }}" required autocomplete="tel">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 form-group-premium">
                    <label for="instagram">Username Instagram (Opsional)</label>
                    <input type="text" id="instagram" name="instagram" 
                           class="form-control form-control-premium @error('instagram') is-invalid @enderror" 
                           value="{{ old('instagram', $user->instagram) }}" placeholder="e.g. username_anda">
                    @error('instagram')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password Section Divider --}}
            <div class="password-section-divider">
                <span class="password-section-title">Ubah Kata Sandi</span>
            </div>

            <p class="text-muted small mb-4" style="font-size: 12px; margin-top: -8px;">Biarkan kosong jika Anda tidak ingin mengubah kata sandi.</p>

            <div class="row">
                <div class="col-md-6 form-group-premium">
                    <label for="password">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" 
                           class="form-control form-control-premium @error('password') is-invalid @enderror" 
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 form-group-premium">
                    <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                           class="form-control form-control-premium" 
                           placeholder="Ulangi kata sandi baru">
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-save-profile">
                    <i class="bi bi-check2-circle me-1"></i> Perbarui Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
