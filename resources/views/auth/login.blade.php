@extends('layouts.auth')

@section('content')
<section class="auth-container">
    @include('components.auth-welcome-card', [
        'title' => 'Selamat datang kembali Cantik.',
        'subtitle' => 'Masuk untuk melanjutkan booking, melihat invoice, atau mengelola usaha Anda. Setiap detail kami rancang untuk pengalaman premium Anda.',
        'quote' => 'Kecantikan terbaik dimulai dari rasa percaya diri dan pelayanan yang tepat.',
        'author' => 'LISA YULI BELTI'
    ])

    <div class="form-card">
        <h2>Masuk ke Akun</h2>

        {{-- Error jika email/password salah --}}
        @if (session('login_error'))
            <div class="alert alert-danger">
                {{ session('login_error') }}
            </div>
        @endif

        {{-- Pesan sukses, misalnya setelah register/logout --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="email">Email</label>

        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            class="@error('email') is-invalid @enderror"
        >

        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    @include('components.password-input')

    @include('components.forgot-password-link')

    <button type="submit" class="btn-primary">Masuk</button>
</form>

        <div class="auth-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
        </div>
    </div>
</section>
@endsection
