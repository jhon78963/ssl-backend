<?php

use App\ReservationType\Controllers\ReservationTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(ReservationTypeController::class)->group(function() {
    Route::post('/reservation-types', 'create');
    Route::patch('/reservation-types/{reservationType}', 'update');
    Route::delete('/reservation-types/{reservationType}', 'delete');
    Route::get('/reservation-types', 'getAll');
    Route::get('/reservation-types/{reservationType}', 'get');
});
