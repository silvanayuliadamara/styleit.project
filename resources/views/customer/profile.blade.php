@extends('layouts.customer', ['title' => 'Ubah Profil Anda — LYB'])

@section('customer_content')
<style>
    /* Ultra-Premium Profile Styling */
    .profile-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 32px;
        align-items: start;
        margin-top: 10px;
    }

    /* Left Card: Summary */
    .profile-summary-card {
        background: #ffffff;
        border: 1px solid rgba(176, 138, 66, 0.15);
        border-radius: 24px;
        padding: 36px 24px;
        text-align: center;
        box-shadow: 0 8px 30px rgba(33, 19, 19, 0.02);
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .profile-summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(176, 138, 66, 0.06);
        border-color: rgba(176, 138, 66, 0.3);
    }

    .profile-summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #b08a42, #e0c897, #b08a42);
    }

    .avatar-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1c0e0e 0%, #3a2222 100%);
        color: #b08a42;
        font-size: 38px;
        font-weight: 700;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 24px rgba(33, 19, 19, 0.15);
        border: 3px solid #ffffff;
        outline: 2px solid rgba(176, 138, 66, 0.3);
        transition: all 0.4s ease;
    }

    .profile-summary-card:hover .profile-avatar {
        transform: scale(1.05);
        outline-color: rgba(176, 138, 66, 0.6);
        box-shadow: 0 12px 30px rgba(176, 138, 66, 0.25);
    }

    .profile-summary-name {
        font-family: 'Outfit', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #211313;
        margin-bottom: 4px;
    }

    .profile-summary-role {
        font-size: 11px;
        font-weight: 600;
        color: #b08a42;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 24px;
        display: inline-block;
        background: rgba(176, 138, 66, 0.08);
        padding: 4px 14px;
        border-radius: 30px;
        border: 1px solid rgba(176, 138, 66, 0.15);
    }

    .profile-details-list {
        text-align: left;
        border-top: 1px dashed rgba(176, 138, 66, 0.2);
        padding-top: 24px;
    }

    .profile-details-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        font-size: 13.5px;
        color: #4e3a27;
    }

    .profile-details-item i {
        color: #b08a42;
        font-size: 16px;
        background: rgba(176, 138, 66, 0.08);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Right Card: Form */
    .profile-form-card {
        background: #ffffff;
        border: 1px solid rgba(176, 138, 66, 0.15);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 8px 30px rgba(33, 19, 19, 0.02);
        position: relative;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .profile-form-card:hover {
        box-shadow: 0 20px 48px rgba(33, 19, 19, 0.05);
    }

    .profile-form-card h3 {
        font-size: 20px;
        font-weight: 700;
        color: #211313;
        margin-bottom: 28px;
        position: relative;
        padding-bottom: 10px;
    }

    .profile-form-card h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 48px;
        height: 2.5px;
        background: #b08a42;
        border-radius: 2px;
    }

    .form-group-premium {
        margin-bottom: 24px;
    }

    .form-group-premium label {
        font-size: 12.5px;
        font-weight: 600;
        color: #4e3a27;
        margin-bottom: 8px;
        display: block;
        letter-spacing: 0.2px;
    }

    .form-control-premium {
        height: 48px;
        border-radius: 14px !important;
        border: 1px solid rgba(176, 138, 66, 0.2) !important;
        padding: 0 18px !important;
        font-size: 14px !important;
        font-family: 'Outfit', sans-serif !important;
        background: #ffffff !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .form-control-premium:focus {
        border-color: #b08a42 !important;
        box-shadow: 0 0 0 3.5px rgba(176, 138, 66, 0.12), 0 8px 20px rgba(176, 138, 66, 0.05) !important;
        transform: translateY(-1px);
    }

    .form-control-premium::placeholder {
        color: #c4b4a0;
    }

    .password-section-divider {
        margin: 36px 0 24px;
        border-top: 1px solid rgba(176, 138, 66, 0.15);
        position: relative;
    }

    .password-section-title {
        position: absolute;
        top: 50%;
        left: 32px;
        transform: translateY(-50%);
        background: #ffffff;
        padding: 0 16px;
        font-size: 12px;
        font-weight: 700;
        color: #b08a42;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-save-profile {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #1c0e0e 0%, #3a2222 100%) !important;
        border: 1px solid rgba(176, 138, 66, 0.2) !important;
        border-radius: 14px !important;
        padding: 12px 36px !important;
        font-weight: 600 !important;
        color: #ffffff !important;
        font-size: 14.5px !important;
        box-shadow: 0 6px 20px rgba(33, 19, 19, 0.15);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    .btn-save-profile::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
        transition: left 0.6s ease;
    }

    .btn-save-profile:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 30px rgba(33, 19, 19, 0.24), 0 0 0 2px rgba(176, 138, 66, 0.2);
        background: linear-gradient(135deg, #2b1717 0%, #442626 100%) !important;
    }

    .btn-save-profile:hover::before {
        left: 100%;
    }

    .btn-save-profile:active {
        transform: translateY(-1px) scale(0.98);
    }

    /* Stagger Animations */
    .lyb-animate-stagger-1 {
        animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .lyb-animate-stagger-2 {
        animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.12s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(28px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Page Header */
    .profile-page-header {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        border-bottom: 1px solid rgba(176, 138, 66, 0.15);
        padding-bottom: 16px;
        margin-bottom: 32px;
    }

    .profile-page-header h2 {
        font-weight: 800;
        font-size: 28px;
        color: #211313;
        margin-bottom: 4px;
    }

    .profile-page-header p {
        color: #8a7a72;
        font-size: 14.5px;
        margin: 0;
    }

    @media (max-width: 900px) {
        .profile-container {
            grid-template-columns: 1fr;
        }
    }
</style>

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
