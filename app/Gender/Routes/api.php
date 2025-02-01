<?php

use App\Gender\Controllers\GenderController;
use Illuminate\Support\Facades\Route;

Route::controller(GenderController::class)->group(function() {
    Route::get('/genders', 'getAll');
    Route::get('/genders/{gender}', 'get');
});
