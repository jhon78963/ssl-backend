<?php

use App\Review\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::controller(ReviewController::class)->group(function() {
    Route::post('/reviews', 'create');
    Route::patch('/reviews/{review}', 'update');
    Route::delete('/reviews/{review}', 'delete');
    Route::get('/reviews', 'getAll');
    Route::get('/reviews/{review}', 'get');
});
