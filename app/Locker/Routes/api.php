<?php

use App\Locker\Controllers\LockerController;
use Illuminate\Support\Facades\Route;

Route::controller(LockerController::class)->group(function() {
    Route::post('/lockers', 'create');
    Route::post('/lockers/price', 'updatePrice');
    Route::patch('/lockers/change-status/{locker}', 'changeStatus');
    Route::patch('/lockers/{locker}', 'update');
    Route::delete('/lockers/{locker}', 'delete');
    Route::get('/lockers', 'getAll');
    Route::get('/lockers/available', 'getLockerAvailable');
    Route::get('/lockers/{locker}', 'get');
});
