<?php

use App\Cash\Controllers\CashController;
use App\Cash\Controllers\CashOperationController;
use App\Cash\Controllers\CashTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(CashController::class)->group(function() {
    Route::post('/cashes', 'create');
    Route::get('/cashes', 'getAll');
    Route::get('/cashes/current', 'get');
    Route::put('/cashes/{cash}', 'update');
});

Route::controller(CashOperationController::class)->group(function() {
    Route::post('/cash-operations', 'create');
    Route::get('/cash-operations/total', 'total');
});

Route::controller(CashTypeController::class)->group(function() {

    Route::get('/cash-types/get', 'get');
});
