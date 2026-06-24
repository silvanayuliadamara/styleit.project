@extends('layouts.auth', ['title' => 'Reset Kata Sandi - Lisa Yuli Belti'])

@section('content')
    <section class="single-auth-container">
        <div class="form-card forgot-password-card">
            <div class="forgot-icon">
                <i class="bi bi-lock"></i>
            </div>

            <h2>Buat Kata Sandi Baru</h2>

            <p class="forgot-description">
                Gunakan kata sandi baru yang aman agar akun Anda tetap terlindungi.
            </p>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                @include('components.password-input', [
                    'label' => 'Kata Sandi Baru',
                    'id' => 'password',
                    'name' => 'password',
                    'placeholder' => 'Minimal 8 karakter'
                ])

                @include('components.password-input', [
                    'label' => 'Konfirmasi Kata Sandi',
                    'id' => 'password_confirmation',
                    'name' => 'password_confirmation',
                    'placeholder' => 'Ulangi kata sandi baru'
                ])

                <button type="submit" class="btn-primary forgot-submit">
                    Simpan Kata Sandi
                </button>
            </form>

            <div class="auth-link">
                Kembali ke
                <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </section>
@endsection
