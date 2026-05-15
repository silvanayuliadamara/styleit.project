<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Email atau kata sandi salah.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Berhasil keluar dari akun.');
    }
}
