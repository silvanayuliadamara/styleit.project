<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function refreshCaptcha()
    {
        return response()->json([
            'captcha' => captcha_img('login')
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        $otp = random_int(100000, 999999);

        session([
            'reset_email' => $request->email,
            'reset_otp_hash' => Hash::make($otp),
            'reset_otp_expires_at' => now()->addMinutes(5)->timestamp,
            'reset_otp_verified' => false,
        ]);

        Mail::raw(
            "Kode OTP reset kata sandi Anda adalah {$otp}. Kode ini berlaku selama 5 menit.",
            function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Kode OTP Reset Kata Sandi');
            }
        );

        return redirect()
            ->route('password.otp.form')
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showVerifyOtp()
    {
        if (! session('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus terdiri dari 6 digit.',
        ]);

        if (! session('reset_email') || ! session('reset_otp_hash')) {
            return redirect()->route('password.request');
        }

        if (now()->timestamp > session('reset_otp_expires_at')) {
            session()->forget([
                'reset_email',
                'reset_otp_hash',
                'reset_otp_expires_at',
                'reset_otp_verified',
            ]);

            return redirect()
                ->route('password.request')
                ->withErrors(['email' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.']);
        }

        if (! Hash::check($request->otp, session('reset_otp_hash'))) {
            return back()->withErrors(['otp' => 'Kode OTP tidak sesuai.']);
        }

        session(['reset_otp_verified' => true]);

        return redirect()->route('password.reset.form');
    }

    public function showResetPassword()
    {
        if (! session('reset_email') || ! session('reset_otp_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (! $user) {
            return redirect()->route('password.request');
        }

        $user->update(['password' => Hash::make($request->password)]);

        session()->forget([
            'reset_email',
            'reset_otp_hash',
            'reset_otp_expires_at',
            'reset_otp_verified',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Kata sandi berhasil diperbarui. Silakan masuk kembali.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'captcha' => ['required', 'captcha'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'captcha.required' => 'Captcha wajib diisi.',
            'captcha.captcha' => 'Kode captcha salah.',
        ]);

        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials)) {
            return back()
                ->with('login_error', 'Email atau kata sandi salah.')
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->hasRole('owner')) {
            return redirect()->route('owner.dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'instagram' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password_confirmation.required' => 'Konfirmasi sandi wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi sandi tidak sama.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'instagram' => $validated['instagram'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        $user->assignRole('customer');

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil. Silakan masuk ke akun Anda.');
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
