<?php

use App\Rate\Controllers\RateController;
use App\Rate\Controllers\RateDayController;
use App\Rate\Controllers\RateHourController;
use Illuminate\Support\Facades\Route;

Route::controller(RateDayController::class)->group(function() {
    Route::post('/rate-days', 'create');
    Route::patch('/rate-days/{rateDay}', 'update');
    Route::delete('/rate-days/{rateDay}', 'delete');
    Route::get('/rate-days', 'getAll');
    Route::get('/rate-days/{rateDay}', 'get');
});

Route::controller(RateHourController::class)->group(function() {
    Route::post('/rate-hours', 'create');
    Route::patch('/rate-hours/{rateHour}', 'update');
    Route::delete('/rate-hours/{rateHour}', 'delete');
    Route::get('/rate-hours', 'getAll');
    Route::get('/rate-hours/{rateHour}', 'get');
});

Route::controller(RateController::class)->group(function() {
    Route::post('/rates', 'create');
    Route::patch('/rates/{rate}', 'update');
    Route::delete('/rates/{rate}', 'delete');
    Route::get('/rates', 'getAll');
    Route::get('/rates/{rate}', 'get');
});
