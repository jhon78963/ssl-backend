<?php

use App\Role\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::controller(RoleController::class)->group(function() {
    Route::post('/roles', 'create');
    Route::patch('/roles/{role}', 'update');
    Route::delete('/roles/{role}', 'delete');
    Route::get('/roles', 'getAll');
    Route::get('/roles/{role}', 'get');
});
