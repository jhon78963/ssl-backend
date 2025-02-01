<?php

use App\Service\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::controller(ServiceController::class)->group(function() {
    Route::post('/services', 'create');
    Route::patch('/services/{service}', 'update');
    Route::delete('/services/{service}', 'delete');
    Route::get('/services', 'getAll');
    Route::get('/services/{service}', 'get');
});
