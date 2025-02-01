<?php

use App\Room\Controllers\RoomController;
use App\Room\Controllers\RoomImageController;
use App\Room\Controllers\RoomReviewController;
use Illuminate\Support\Facades\Route;

Route::controller(RoomController::class)->group(function() {
    Route::post('/rooms', 'create');
    Route::patch('/rooms/change-status/{room}', 'changeStatus');
    Route::patch('/rooms/{room}', 'update');
    Route::delete('/rooms/{room}', 'delete');
    Route::get('/rooms', 'getAll');
    Route::get('/rooms/available', 'getRoomAvailable');
    Route::get('/rooms/{room}', 'get');
});

Route::controller(RoomImageController::class)->group(function() {
    Route::post('/images/{room}/multiple-add', 'multipleAdd');
    Route::post('/images/{room}/add/{image}', 'add');
    Route::delete('/images/{room}/remove/{image}', 'remove');
    Route::get('/images/{room}/all', 'getAll');
    Route::get('/images/{room}/left', 'getLeft');
});

Route::controller(RoomReviewController::class)->group(function() {
    Route::post('/reviews/{room}/add', 'add');
    Route::delete('/reviews/{room}/remove/{review}', 'remove');
    Route::get('/reviews/{room}/all', 'getAll');
});
