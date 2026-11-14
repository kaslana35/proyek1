<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AdminStokController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware(['throttle:login', 'throttle:login-global']);
    Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:password-reset');

// Halaman Utama: Redirect otomatis ke /login atau dashboard/katalog
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->hasRole('admin') ? redirect()->route('dashboard') : redirect()->route('laptops.index');
    }
    return redirect()->route('laptops.index');
});

// Rute untuk semua (Logout & Profil)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function (Illuminate\Http\Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    })->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Rute khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [TransactionController::class, 'adminIndex'])->name('admin.transactions.index');
    Route::put('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('admin.transactions.approve');
    Route::put('/transactions/{transaction}/complete', [TransactionController::class, 'complete'])->name('admin.transactions.complete');
    Route::put('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('admin.transactions.cancel');
    
    // Status Unit

    Route::get('/stok', [AdminStokController::class, 'manageStok'])->name('admin.stok.manage');
    Route::get('/produk', [AdminProdukController::class, 'manageProduk'])->name('admin.produk.manage');
    Route::get('/inspections', [InspectionController::class, 'index'])->name('admin.inspections.index');
    Route::get('/laptops/create', [AdminProdukController::class, 'create'])->name('admin.laptops.create');
    Route::post('/laptops', [AdminProdukController::class, 'store'])->name('admin.laptops.store');
    Route::get('/laptops/{laptop}/edit', [AdminProdukController::class, 'edit'])->name('admin.laptops.edit');
    Route::put('/laptops/{laptop}', [AdminProdukController::class, 'update'])->name('admin.laptops.update');
    Route::put('/stok/{laptop}/transfer', [AdminStokController::class, 'transferStok'])->name('admin.stok.transfer');
    Route::delete('/laptops/{laptop}', [AdminProdukController::class, 'destroy'])->name('admin.laptops.destroy');

    // Manajemen User & Role
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/role', [UserController::class, 'editRole'])->name('admin.users.edit_role');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.update_role');
});

// Rute untuk User / Publik (Katalog & Detail)
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/laptops', [CatalogController::class, 'index'])->name('laptops.index');
    Route::get('/laptops/{laptop}', [CatalogController::class, 'show'])->name('laptops.show');
    Route::get('/katalog', [CatalogController::class, 'index'])->name('katalog');
});

// Rute khusus User yang harus login & punya role user
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::post('/laptops/{laptop}/sewa', [CatalogController::class, 'storeSewa'])->name('laptops.sewa');
    Route::get('/riwayat-sewa', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
