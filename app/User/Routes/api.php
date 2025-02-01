<?php

use App\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function() {
    Route::post('/users', 'create');
    Route::patch('/users/{user}', 'update');
    Route::delete('/users/{user}', 'delete');
    Route::get('/users', 'getAll');
    Route::get('/users/{user}', 'get');
});
