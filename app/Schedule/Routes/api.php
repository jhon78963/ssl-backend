<?php

use App\Schedule\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::controller(ScheduleController::class)->group(function() {
    Route::get('/schedules/current', 'get');
    Route::get('/schedules', 'getAll');
});
