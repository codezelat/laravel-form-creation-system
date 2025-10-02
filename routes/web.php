<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\FormController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication Routes
Route::prefix('hidden-admin')->group(function () {
    // Login routes (accessible without authentication)
    Route::get('/', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/', [AdminAuthController::class, 'login'])->name('admin.login.post');
    
    // Protected admin routes (require authentication)
    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/forms', [AdminAuthController::class, 'index'])->name('admin.forms.index');
        Route::get('/forms/create', [AdminAuthController::class, 'createForm'])->name('admin.forms.create');
        Route::get('/forms/{id}/analytics', [AdminAuthController::class, 'showAnalytics'])->name('admin.forms.analytics');
        Route::get('/forms/{formId}/submissions/{submissionId}', [AdminAuthController::class, 'showSubmission'])->name('admin.forms.submission');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        
        // Form management routes (admin only)
        Route::post('/forms/store', [FormController::class, 'store'])->name('admin.forms.store');
        Route::get('/forms/{id}/data', [FormController::class, 'getFormData'])->name('admin.forms.data');
        Route::post('/forms/{id}/publish', [FormController::class, 'publish'])->name('admin.forms.publish');
        Route::post('/forms/{id}/unpublish', [FormController::class, 'unpublish'])->name('admin.forms.unpublish');
        Route::delete('/forms/{id}', [FormController::class, 'destroy'])->name('admin.forms.destroy');
    });
});

// Public form routes
Route::get('/form/{slug}', [FormController::class, 'show'])->name('form.show');
Route::post('/form/{slug}/submit', [FormController::class, 'submit'])->name('form.submit');
