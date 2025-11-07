<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LiveAccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LiveAccountCategoryController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ProductMetadataController;
use App\Http\Controllers\ProductAffiliateController;


// Route utama login
Route::get('/', function () {
    return view('login'); 
});

// Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); 
Route::post('/login', [AuthController::class, 'login'])->name('login');  
// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 


// Menampilkan form awal (input WhatsApp / OTP)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.sendOtp');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verifyOtp');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

// halaman setelah login
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::middleware('auth')->group(function () {
    // Halaman profil
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Ganti password
    Route::get('/profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

Route::get('/etalase', [LiveAccountController::class, 'index'])->middleware('auth')->name('etalase');
Route::get('/live-accounts', [LiveAccountController::class, 'index'])->name('live-accounts.index');
Route::get('/live-accounts/create', [LiveAccountController::class, 'create'])->name('live-accounts.create');
Route::post('/live-accounts', [LiveAccountController::class, 'store'])->name('live-accounts.store');
Route::get('/live-accounts/{id}/edit', [LiveAccountController::class, 'edit'])->name('live-accounts.edit');
Route::put('/live-accounts/{id}', [LiveAccountController::class, 'update'])->name('live-accounts.update');
Route::delete('/live-accounts/{id}', [LiveAccountController::class, 'destroy'])->name('live-accounts.destroy');

Route::get('/produk', [ProductController::class, 'index'])
    ->middleware('auth')
    ->name('produk.index');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('produk.detail');
Route::get('/kategori', [CategoryController::class, 'index'])->middleware('auth');
Route::get('/schedule', [ScheduleController::class, 'index'])->middleware('auth');

Route::get('/live-accounts/{liveAccount}/categories', [LiveAccountCategoryController::class, 'edit'])
    ->name('live_accounts.categories.edit');

Route::put('/live-accounts/{liveAccount}/categories', [LiveAccountCategoryController::class, 'update'])
    ->name('live_accounts.categories.update');

Route::delete('/live-accounts/{liveAccount}/categories/{categoryId}', [LiveAccountCategoryController::class, 'destroy'])
    ->name('live_accounts.categories.destroy');


// Route untuk membuka modal lupa password via session
Route::post('/modal/forgot-password', function () {
    session(['show_modal' => true]);
    return redirect()->route('login');
})->name('modal.forgot-password');

// (Opsional) Route untuk reset ulang proses jika user klik "ulangi proses dari awal"
Route::get('/forgot-password/reset', function () {
    session()->forget(['otp_phase', 'otp_phone', 'verified_phone']);
    session(['show_modal' => true]);
    return redirect()->route('login');
})->name('modal.forgot-password.reset');

Route::get('/detail', function () {
    return view('detail'); // file resources/views/detail.blade.php
})->name('detail');



Route::get('/products', [ProductAffiliateController::class, 'index'])
    ->name('products.index')
    ->middleware('auth');
Route::get('/products/{id}', [ProductAffiliateController::class, 'show'])->name('products.detail'); 