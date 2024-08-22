<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    Route::patch('auth/me', [AuthController::class, 'updateMe']);
    Route::post('auth/me', [AuthController::class, 'getMe']);
    Route::post('auth/change-password', [AuthController::class,'changePassword']);
    Route::post('auth/logout', [AuthController::class,'logout']);
    Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);
});
