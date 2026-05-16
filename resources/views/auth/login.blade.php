@extends('layouts.auth')

@section('content')
<section class="auth-container">
    @include('components.auth-welcome-card')

    <div class="form-card">
        <h2>Masuk ke Akun</h2>

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
                    required
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
