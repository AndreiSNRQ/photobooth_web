<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;

// Gallery (public QR result page)
Route::get('/gallery/{sessionId}', [GalleryController::class, 'show'])->name('gallery.show');

// Admin Dashboard (protect with auth middleware in production)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sessions', [DashboardController::class, 'sessions'])->name('sessions');
    Route::get('/sessions/{sessionId}', [DashboardController::class, 'sessionDetail'])->name('session.detail');
    Route::delete('/sessions/{sessionId}', [DashboardController::class, 'deleteSession'])->name('session.delete');
    Route::get('/payments', [DashboardController::class, 'payments'])->name('payments');
});

Route::get('/', fn() => redirect('/admin'));
