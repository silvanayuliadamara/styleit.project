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

        <form action="{{ route('login.process') }}" method="POST" class="login-form">
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

    @if(!config('captcha.disable'))
    <div class="form-group">
        <label for="captcha">Captcha</label>
        <div class="captcha-wrapper">
            <div class="captcha-img" id="captcha-img-container">
                {!! captcha_img('login') !!}
            </div>
            <button type="button" class="btn-refresh-captcha" id="btn-refresh" title="Segarkan Captcha">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        <input
            type="text"
            id="captcha"
            name="captcha"
            placeholder="Masukkan kode di atas"
            class="@error('captcha') is-invalid @enderror"
            required
            autocomplete="off"
        >
        @error('captcha')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    @endif

    <div class="form-group remember-me-group">
        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label for="remember" class="remember-me-label">Ingat Saya</label>
    </div>

    <button type="submit" class="btn-primary">Masuk</button>
</form>

        <div class="auth-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
        </div>
    </div>
</section>

<script>
    const btnRefresh = document.getElementById('btn-refresh');
    if (btnRefresh) {
        btnRefresh.addEventListener('click', function() {
            const btn = this;
            const icon = btn.querySelector('i');
            
            icon.classList.add('bi-spin');
            btn.disabled = true;
            
            fetch('{{ route("captcha.refresh") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captcha-img-container').innerHTML = data.captcha;
                })
                .catch(error => console.error('Error refreshing captcha:', error))
                .finally(() => {
                    icon.classList.remove('bi-spin');
                    btn.disabled = false;
                });
        });
    }
</script>
@endsection
