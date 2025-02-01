<?php

use App\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth:sanctum',
], function (): void {
    Route::patch('auth/me', [AuthController::class, 'updateMe']);
    Route::post('auth/me', [AuthController::class, 'getMe']);
    Route::post('auth/change-password', [AuthController::class,'changePassword']);
});
