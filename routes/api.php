<?php

use App\Auth\Controllers\AuthController;
use App\Company\Controllers\CompanyController;
use App\Company\Controllers\SocialNetworkController;
use App\Company\Models\SocialNetwork;
use App\Role\Controllers\RoleController;
use App\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTION');
// header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Auth-Token');

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);

Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    Route::patch('auth/me', [AuthController::class, 'updateMe']);
    Route::post('auth/me', [AuthController::class, 'getMe']);
    Route::post('auth/change-password', [AuthController::class,'changePassword']);
    Route::post('auth/logout', [AuthController::class,'logout']);

    Route::controller(RoleController::class)->group(function() {
        Route::post('/roles', 'create');
        Route::patch('/roles/{role}', 'update');
        Route::delete('/roles/{role}', 'delete');
        Route::get('/roles', 'getAll');
        Route::get('/roles/{role}', 'get');
    });

    Route::controller(UserController::class)->group(function() {
        Route::post('/users', 'create');
        Route::patch('/users/{user}', 'update');
        Route::delete('/users/{user}', 'delete');
        Route::get('/users', 'getAll');
        Route::get('/users/{user}', 'get');
    });

    Route::controller(CompanyController::class)->group(function() {
        Route::patch('/companies/{company}', 'update');
        Route::get('/companies/{company}', 'get');
    });

    Route::controller(SocialNetworkController::class)->group(function() {
        Route::post('/companies/add/social-network', 'add');
        Route::patch('/companies/edit/social-network/{socialNetwork}', 'edit');
        Route::delete('/companies/remove/social-network/{socialNetwork}', 'remove');
        Route::get('/companies/get/social-network/{socialNetwork}', 'get');
        Route::get('/companies/getAll/social-network/', 'getAll');
    });
});
