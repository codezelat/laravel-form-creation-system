<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication Routes
Route::prefix('hidden-admin')->group(function () {
    // Login routes (accessible without authentication)
    Route::get('/', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    
    // Protected admin routes (require authentication)
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
