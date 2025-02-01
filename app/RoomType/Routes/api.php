<?php

use App\RoomType\Controllers\RoomTypeAmenityController;
use App\RoomType\Controllers\RoomTypeController;
use App\RoomType\Controllers\RoomTypeRateController;
use Illuminate\Support\Facades\Route;

Route::controller(RoomTypeController::class)->group(function() {
    Route::post('/room-types', 'create');
    Route::patch('/room-types/{roomType}', 'update');
    Route::delete('/room-types/{roomType}', 'delete');
    Route::get('/room-types', 'getAll');
    Route::get('/room-types/{roomType}', 'get');
});

Route::controller(RoomTypeAmenityController::class)->group(function() {
    Route::post('/amenities/{roomType}/add/{amenity}', 'add');
    Route::delete('/amenities/{roomType}/remove/{amenity}', 'remove');
    Route::get('/amenities/{roomType}/all', 'getAll');
    Route::get('/amenities/{roomType}/left', 'getLeft');
});

Route::controller(RoomTypeRateController::class)->group(function() {
    Route::post('/rates/{roomType}/add/{rate}', 'add');
    Route::delete('/rates/{roomType}/remove/{rate}', 'remove');
    Route::get('/rates/{roomType}/all', 'getAll');
    Route::get('/rates/{roomType}/left', 'getLeft');
});
