@extends('layouts.auth', ['title' => 'Lupa Kata Sandi - Lisa Yuli Belti'])

@section('content')
    <section class="single-auth-container">
        <div class="form-card forgot-password-card">
            <div class="forgot-icon">
                <i class="bi bi-envelope"></i>
            </div>

            <h2>Lupa Kata Sandi</h2>

            <p class="forgot-description">
                Masukkan email yang terdaftar. Kami akan mengirimkan kode OTP untuk proses pemulihan kata sandi.
            </p>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('password.otp.send') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>

                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Masukkan email terdaftar" class="@error('email') is-invalid @enderror">

                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-primary forgot-submit">
                    Kirim Kode OTP
                </button>
            </form>

            <div class="auth-link">
                Ingat kata sandi?
                <a href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </section>
@endsection
