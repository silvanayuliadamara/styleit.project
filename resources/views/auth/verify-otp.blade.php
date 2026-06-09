@extends('layouts.auth', ['title' => 'Verifikasi OTP - Lisa Yuli Belti'])

@section('content')
    <section class="single-auth-container">
        <div class="form-card otp-card">
            <a href="{{ route('password.request') }}" class="otp-back-link">
                <i class="bi bi-arrow-left"></i>
            </a>

            <h2>Masukkan Kode OTP</h2>

            <p class="forgot-description">
                Kode OTP telah dikirim ke email
                <strong>{{ session('reset_email') }}</strong>
            </p>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('password.otp.verify') }}" method="POST" id="otpForm">
                @csrf

                <input type="hidden" name="otp" id="otpValue">

                <div class="otp-input-group">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" inputmode="numeric" class="otp-input">
                    @endfor
                </div>

                @error('otp')
                    <div class="error otp-error">{{ $message }}</div>
                @enderror

                <p class="otp-note">
                    Kode berlaku selama 5 menit.
                </p>

                <button type="submit" class="btn-primary forgot-submit">
                    Lanjut
                </button>
            </form>

            <div class="auth-link">
                Email salah?
                <a href="{{ route('password.request') }}">Ubah email</a>
            </div>
        </div>
    </section>

    <script>
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpValue = document.getElementById('otpValue');
        const otpForm = document.getElementById('otpForm');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^0-9]/g, '');

                if (input.value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && !input.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        otpForm.addEventListener('submit', () => {
            otpValue.value = Array.from(otpInputs).map(input => input.value).join('');
        });
    </script>
@endsection
