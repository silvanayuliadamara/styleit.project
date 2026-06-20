<?php

use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CustomerBookingController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\MidtransController;
use App\Http\Controllers\Owner\OwnerAddonController;
use App\Http\Controllers\Owner\OwnerBookingController;
use App\Http\Controllers\Owner\OwnerCategoryController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\OwnerPackageController;
use App\Http\Controllers\Owner\OwnerScheduleController;
use App\Http\Controllers\Owner\WhatsappSettingController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/profil', [PublicPageController::class, 'profil'])->name('profil');
Route::get('/layanan', [PublicPageController::class, 'layanan'])->name('layanan.index');
Route::get('/layanan/{slug}', [PublicPageController::class, 'kategori'])->name('layanan.kategori');
Route::get('/paket/{code}', [PublicPageController::class, 'paket'])->name('paket.show');
Route::get('/paket/{code}/slots', [PublicPageController::class, 'getAvailableSlots'])->name('paket.slots');
Route::get('/portofolio', [PublicPageController::class, 'portofolio'])->name('portofolio');
Route::get('/pricelist', [PublicPageController::class, 'pricelist'])->name('pricelist');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.otp.send');

Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.otp.form');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.otp.verify');

Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer routes
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
        Route::post('/cart/select', [CartController::class, 'select'])->name('cart.select');
        Route::delete('/cart/{key}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{booking}/invoice', [CustomerBookingController::class, 'invoice'])->name('bookings.invoice');
        Route::patch('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('bookings.review');
        Route::post('/cancellations/{id}/dismiss', [CustomerBookingController::class, 'dismissCancellationNotif'])->name('cancellations.dismiss');
        Route::get('/checkout/{booking}/snap-token', [MidtransController::class, 'getSnapToken'])->name('checkout.snap-token');
    });

// Midtrans webhook (public, no CSRF, no auth)
Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');

// Payment instruction route (authenticated, top-level url)
Route::middleware(['auth'])->group(function () {
    Route::get('/pembayaran/{booking_code}', [CheckoutController::class, 'paymentInstruction'])->name('customer.payment.instruction');
    Route::get('/pembayaran/{booking_code}/berhasil', [CheckoutController::class, 'paymentSuccess'])->name('customer.payment.success');
    Route::post('/pembayaran/{booking_code}/confirm', [CheckoutController::class, 'confirmPayment'])->name('customer.payment.confirm');
});

// Owner routes
Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [OwnerBookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{booking}/invoice', [OwnerBookingController::class, 'invoice'])->name('bookings.invoice');
        Route::patch('/bookings/{booking}/confirm-dp', [OwnerBookingController::class, 'confirmDp'])->name('bookings.confirmDp');
        Route::patch('/bookings/{booking}/lunas', [OwnerBookingController::class, 'confirmLunas'])->name('bookings.confirmLunas');
        Route::patch('/bookings/{booking}/status', [OwnerBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
        Route::patch('/bookings/{booking}/confirm-cancel', [OwnerBookingController::class, 'confirmCancel'])->name('bookings.confirmCancel');

        Route::get('/laporan', [OwnerBookingController::class, 'laporan'])->name('laporan');
        Route::get('/laporan/export', [OwnerBookingController::class, 'exportLaporanCsv'])->name('laporan.export');

        // Schedules
        Route::get('/schedules/wedding', [OwnerScheduleController::class, 'wedding'])->name('schedules.wedding');
        Route::get('/schedules/regular', [OwnerScheduleController::class, 'regular'])->name('schedules.regular');
        Route::get('/schedules/baju', [OwnerScheduleController::class, 'baju'])->name('schedules.baju');
        Route::post('/schedules', [OwnerScheduleController::class, 'store'])->name('schedules.store');
        Route::post('/schedules/toggle-block', [OwnerScheduleController::class, 'toggleBlock'])->name('schedules.toggleBlock');
        Route::delete('/schedules', [OwnerScheduleController::class, 'destroy'])->name('schedules.destroy');

        Route::resource('categories', OwnerCategoryController::class)->except(['show']);
        Route::resource('packages', OwnerPackageController::class)->except(['show']);
        Route::resource('addons', OwnerAddonController::class)->except(['show']);

        // WhatsApp Settings
        Route::get('/whatsapp', [WhatsappSettingController::class, 'index'])->name('whatsapp.index');
        Route::put('/whatsapp', [WhatsappSettingController::class, 'update'])->name('whatsapp.update');
    });

// Admin routes
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Redirect / ke dashboard
        Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('index');

        // Dashboard → pakai AdminDashboardController
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Bookings → pakai AdminBookingController
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{booking}/invoice', [AdminBookingController::class, 'invoice'])->name('bookings.invoice');
    });
