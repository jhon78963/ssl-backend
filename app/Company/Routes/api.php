<?php

use App\Company\Controllers\CompanyController;
use App\Company\Controllers\SocialNetworkController;
use Illuminate\Support\Facades\Route;

Route::controller(CompanyController::class)->group(function() {
    Route::patch('/companies/{company}', 'update');
    Route::get('/companies/{company}', 'get');
});

Route::controller(SocialNetworkController::class)->group(function() {
    Route::post('/companies/add/social-network', 'add');
    Route::patch('/companies/edit/social-network/{socialNetwork}', 'edit');
    Route::delete('/companies/remove/social-network/{socialNetwork}', 'remove');
    Route::get('/companies/get/social-network/{socialNetwork}', 'get');
    Route::get('/companies/getAll/social-network/', 'getAll');
});
