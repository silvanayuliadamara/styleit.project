@extends('layouts.auth')

@section('content')
<section class="auth-container register-container">
    @include('components.auth-welcome-card')

    <div class="form-card register-card">
        <h2>Daftar Sekarang</h2>

        <form action="{{ route('register.process') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                    >

                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">No. HP</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        required
                    >

                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full">
                    <label for="instagram">Username Instagram</label>
                    <input
                        type="text"
                        id="instagram"
                        name="instagram"
                        value="{{ old('instagram') }}"
                    >

                    @error('instagram')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                    >

                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full">
                    <label for="password">Kata Sandi</label>

                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                        >

                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i id="eyeIcon" class="bi bi-eye"></i>
                        </button>
                    </div>

                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group full">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn-primary">Daftar</button>
        </form>

        <div class="auth-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
        </div>
    </div>
</section>
@endsection
