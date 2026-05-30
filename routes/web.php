<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CustomerBookingController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/profil', [PublicPageController::class, 'profil'])->name('profil');
Route::get('/layanan', [PublicPageController::class, 'layanan'])->name('layanan.index');
Route::get('/layanan/{slug}', [PublicPageController::class, 'kategori'])->name('layanan.kategori');
Route::get('/paket/{code}', [PublicPageController::class, 'paket'])->name('paket.show');
Route::get('/portofolio', [PublicPageController::class, 'portofolio'])->name('portofolio');
Route::get('/pricelist', [PublicPageController::class, 'pricelist'])->name('pricelist');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{key}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/owner/dashboard', fn () => 'Owner Dashboard')->name('owner.dashboard');
    Route::get('/admin/dashboard', fn () => 'Admin Dashboard')->name('admin.dashboard');
});
