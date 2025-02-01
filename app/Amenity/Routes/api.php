<?php

use App\Amenity\Controllers\AmenityController;
use Illuminate\Support\Facades\Route;

Route::controller(AmenityController::class)->group(function() {
    Route::post('/amenities', 'create');
    Route::patch('/amenities/{amenity}', 'update');
    Route::delete('/amenities/{amenity}', 'delete');
    Route::get('/amenities', 'getAll');
    Route::get('/amenities/{amenity}', 'get');
});
