@extends('layouts.auth')

@section('content')
<section class="auth-container register-container">
    @include('components.auth-welcome-card', [
        'title' => 'Halo, Tamu Istimewa.',
        'subtitle' => 'Daftarkan akun Anda untuk mulai merencanakan layanan makeup, wedding gallery, dan baju pengantin dengan lebih mudah.',
        'quote' => 'Setiap langkah menuju hari spesial layak dimulai dengan pengalaman yang indah.',
        'author' => 'LISA YULI BELTI'
    ])

    <div class="form-card register-card">
        <h2>Daftar Sekarang</h2>

        <form action="{{ route('register.process') }}" method="POST">
            @csrf

            <div class="form-grid">
                @include('components.form-input', [
                    'label' => 'Nama Lengkap',
                    'id' => 'name',
                    'name' => 'name'
                ])

                @include('components.form-input', [
                    'label' => 'No. HP',
                    'id' => 'phone',
                    'name' => 'phone'
                ])

                @include('components.form-input', [
                    'label' => 'Username Instagram',
                    'id' => 'instagram',
                    'name' => 'instagram',
                    'placeholder' => '@username',
                    'class' => 'full'
                ])

                @include('components.form-input', [
                    'label' => 'Email',
                    'id' => 'email',
                    'name' => 'email',
                    'type' => 'email',
                    'class' => 'full'
                ])

                @include('components.password-input', [
                    'label' => 'Kata Sandi',
                    'id' => 'password',
                    'name' => 'password',
                    'class' => 'full'
                ])

                @include('components.password-input', [
                    'label' => 'Konfirmasi Kata Sandi',
                    'id' => 'password_confirmation',
                    'name' => 'password_confirmation',
                    'class' => 'full'
                ])

                @if(!config('captcha.disable'))
                <div class="form-group full">
                    <label for="captcha">Captcha</label>
                    <div class="captcha-wrapper">
                        <div class="captcha-img" id="captcha-img-container">
                            {!! captcha_img('register') !!}
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
                        style="border: 1px solid #d9ccc3; border-radius: 10px; width: 100%; height: 46px; padding: 12px 16px; font-size: 13px;"
                    >
                    @error('captcha')
                        <div class="error" style="color: #b02a37; font-size: 11px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
                @endif
            </div>

            <button type="submit" class="btn-primary" style="margin-top: 15px;">Daftar</button>
        </form>

        <script>
            const btnRefresh = document.getElementById('btn-refresh');
            if (btnRefresh) {
                btnRefresh.addEventListener('click', function() {
                    const btn = this;
                    const icon = btn.querySelector('i');
                    
                    icon.classList.add('bi-spin');
                    btn.disabled = true;
                    
                    fetch('{{ route("captcha.refresh") }}?config=register')
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

        <div class="auth-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
        </div>
    </div>
</section>
@endsection
