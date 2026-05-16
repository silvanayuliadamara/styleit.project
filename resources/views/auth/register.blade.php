@extends('layouts.auth')

@section('content')
<section class="auth-container register-container">
    @include('components.auth-welcome-card')

    <div class="form-card register-card">
        <h2>Daftar Sekarang</h2>

        <form action="{{ route('register.process') }}" method="POST">
            @csrf

            <div class="form-grid">
                @include('components.form-input', [
                    'label' => 'Nama Lengkap',
                    'id' => 'name',
                    'name' => 'name',
                    'required' => true
                ])

                @include('components.form-input', [
                    'label' => 'No. HP',
                    'id' => 'phone',
                    'name' => 'phone',
                    'required' => true
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
                    'required' => true,
                    'class' => 'full'
                ])

                @include('components.password-input', [
                    'label' => 'Kata Sandi',
                    'id' => 'password',
                    'name' => 'password',
                    'iconId' => 'eyeIconPassword',
                    'class' => 'full'
                ])

                @include('components.password-input', [
                    'label' => 'Konfirmasi Kata Sandi',
                    'id' => 'password_confirmation',
                    'name' => 'password_confirmation',
                    'iconId' => 'eyeIconPasswordConfirmation',
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
