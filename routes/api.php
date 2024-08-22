<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('auth/login', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('auth/me', [AuthController::class, 'me']);
});


// Route::group([
//     'middleware' => 'auth:sanctum',
// ], function () {

//     Route::middleware(
//         'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value
//     )->group(function () {
//         Route::post('/auth/refresh-token', [AuthController::class, 'refreshToken']);
//     });
// });
