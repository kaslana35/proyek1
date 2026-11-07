<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:3,1');

// Halaman Utama: Redirect otomatis ke /login atau dashboard/katalog
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->hasRole('admin') ? redirect()->route('dashboard') : redirect()->route('laptops.index');
    }
    return redirect()->route('login');
});

// Rute untuk semua (Logout & Profil)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/login');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Rute khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laptops', [LaptopController::class, 'manage'])->name('admin.laptops.manage');
    Route::get('/laptops/create', [LaptopController::class, 'create'])->name('admin.laptops.create');
    Route::post('/laptops', [LaptopController::class, 'store'])->name('admin.laptops.store');
    Route::get('/laptops/{laptop}/edit', [LaptopController::class, 'edit'])->name('admin.laptops.edit');
    Route::put('/laptops/{laptop}', [LaptopController::class, 'update'])->name('admin.laptops.update');
    Route::delete('/laptops/{laptop}', [LaptopController::class, 'destroy'])->name('admin.laptops.destroy');

    // Manajemen User & Role
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/role', [UserController::class, 'editRole'])->name('admin.users.edit_role');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.update_role');
});

// Rute untuk User (Katalog & Sewa)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/laptops', [LaptopController::class, 'index'])->name('laptops.index');
    Route::get('/laptops/{laptop}', [LaptopController::class, 'show'])->name('laptops.show');
    Route::post('/laptops/{laptop}/sewa', [LaptopController::class, 'storeSewa'])->name('laptops.sewa');
    Route::get('/riwayat-sewa', [TransactionController::class, 'index'])->name('transactions.index');
});
