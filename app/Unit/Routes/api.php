<?php

use App\Unit\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::controller(UnitController::class)->group(function() {
    Route::post('/units', 'create');
    Route::patch('/units/{unit}', 'update');
    Route::delete('/units/{unit}', 'delete');
    Route::get('/units', 'getAll');
    Route::get('/units/{unit}', 'get');
});
