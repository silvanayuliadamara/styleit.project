<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::middleware('auth')->group(function () {
    Route::get('/customer/dashboard', function () {
        Route::get('/customer/dashboard', function () {
            return 'Customer Dashboard';
        })->name('customer.dashboard');

         Route::get('/owner/dashboard', function () {
            return 'Owner Dashboard';
        })->name('owner.dashboard');

        Route::get('/admin/dashboard', function () {
            return 'Admin Dashboard';
        })->name('admin.dashboard');
    });
});
