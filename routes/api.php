<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Photobooth API Routes
|--------------------------------------------------------------------------
| All routes consumed by the Kiosk (.exe) and Mobile PWA
*/

Route::prefix('v1')->middleware('api.key')->group(function () {

    // Session Management
    Route::post('/sessions', [SessionController::class, 'create']);
    Route::get('/sessions/{sessionId}', [SessionController::class, 'show']);
    Route::post('/sessions/{sessionId}/complete', [SessionController::class, 'complete']);

    // Photo Upload
    Route::post('/sessions/{sessionId}/photos', [PhotoController::class, 'upload']);
    Route::get('/sessions/{sessionId}/photos', [PhotoController::class, 'index']);

    // Payment
    Route::post('/sessions/{sessionId}/payment', [PaymentController::class, 'create']);
    Route::post('/sessions/{sessionId}/payment/confirm', [PaymentController::class, 'confirm']);
    Route::get('/sessions/{sessionId}/payment', [PaymentController::class, 'status']);
});
