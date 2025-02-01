<?php

use App\Category\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::controller(CategoryController::class)->group(function() {
    Route::post('/categories', 'create');
    Route::patch('/categories/{category}', 'update');
    Route::delete('/categories/{category}', 'delete');
    Route::get('/categories', 'getAll');
    Route::get('/categories/{category}', 'get');
});
