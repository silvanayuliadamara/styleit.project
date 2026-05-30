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
            </div>

            <button type="submit" class="btn-primary">Daftar</button>
        </form>

        <div class="auth-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
        </div>
    </div>
</section>
@endsection
