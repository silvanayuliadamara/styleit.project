<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home Sementara</title>
</head>
<body>
    <h1>Home Sementara</h1>
    <p>Halaman ini hanya untuk testing route.</p>

    @auth
        <p>Login sebagai: {{ auth()->user()->name }}</p>
    @else
        <a href="{{ route('login') }}">Masuk ke Login</a>
    @endauth
</body>
