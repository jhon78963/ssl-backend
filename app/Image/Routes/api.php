<?php

use App\Image\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::controller(ImageController::class)->group(function() {
    Route::delete('/images/{image}', 'delete');
    Route::get('/images', 'getAll');
    Route::get('/images/{image}', 'get');
});
