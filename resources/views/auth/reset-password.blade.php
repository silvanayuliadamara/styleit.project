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

                <div class="form-group">
                    <label for="password">Kata Sandi Baru</label>

                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                        class="@error('password') is-invalid @enderror">

                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>

                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Ulangi kata sandi baru">
                </div>

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
