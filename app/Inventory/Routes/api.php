<?php

use App\Inventory\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::controller(InventoryController::class)->group(function() {
    Route::post('/inventories', 'create');
    Route::patch('/inventories/{inventory}', 'update');
    Route::delete('/inventories/{inventory}', 'delete');
    Route::get('/inventories', 'getAll');
    Route::get('/inventories/{inventory}', 'get');
});
