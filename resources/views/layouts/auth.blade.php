<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Lisa Yuli Belti' }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ time() }}">

</head>
<body>
    <div class="auth-page">
        @include('components.auth-header')

        <main>
            @yield('content')
        </main>

        @include('components.auth-footer')
    </div>

        <script>
            function togglePassword(inputId, button) {
                const input = document.getElementById(inputId);
                const icon = button.querySelector('i');

                if (!input || !icon) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        </script>
</body>
</html>
