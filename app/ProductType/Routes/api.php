<?php

use App\ProductType\Controllers\ProductTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductTypeController::class)->group(function() {
    Route::post('/product-types', 'create');
    Route::patch('/product-types/{productType}', 'update');
    Route::delete('/product-types/{productType}', 'delete');
    Route::get('/product-types', 'getAll');
    Route::get('/product-types/{productType}', 'get');
});
