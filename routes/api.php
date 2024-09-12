<?php

use App\Amenity\Controllers\AmenityController;
use App\Auth\Controllers\AuthController;
use App\Company\Controllers\CompanyController;
use App\Company\Controllers\SocialNetworkController;
use App\Rate\Controllers\RateController;
use App\Rate\Controllers\RateDayController;
use App\Rate\Controllers\RateHourController;
use App\Review\Controllers\ReviewController;
use App\Role\Controllers\RoleController;
use App\Room\Controllers\RoomAmenityController;
use App\Room\Controllers\RoomController;
use App\Room\Controllers\RoomImageController;
use App\Room\Controllers\RoomRateController;
use App\Room\Controllers\RoomReviewController;
use App\Room\Controllers\RoomTypeController;
use App\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTION');
// header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Auth-Token');

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);

Route::group([
    'middleware' => 'auth:sanctum',
], function () {
    Route::patch('auth/me', [AuthController::class, 'updateMe']);
    Route::post('auth/me', [AuthController::class, 'getMe']);
    Route::post('auth/change-password', [AuthController::class,'changePassword']);
    Route::post('auth/logout', [AuthController::class,'logout']);

    Route::controller(RoleController::class)->group(function() {
        Route::post('/roles', 'create');
        Route::patch('/roles/{role}', 'update');
        Route::delete('/roles/{role}', 'delete');
        Route::get('/roles', 'getAll');
        Route::get('/roles/{role}', 'get');
    });

    Route::controller(UserController::class)->group(function() {
        Route::post('/users', 'create');
        Route::patch('/users/{user}', 'update');
        Route::delete('/users/{user}', 'delete');
        Route::get('/users', 'getAll');
        Route::get('/users/{user}', 'get');
    });

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

    Route::controller(RoomTypeController::class)->group(function() {
        Route::post('/room-types', 'create');
        Route::patch('/room-types/{roomType}', 'update');
        Route::delete('/room-types/{roomType}', 'delete');
        Route::get('/room-types', 'getAll');
        Route::get('/room-types/{roomType}', 'get');
    });

    Route::controller(RoomController::class)->group(function() {
        Route::post('/rooms', 'create');
        Route::patch('/rooms/{room}', 'update');
        Route::delete('/rooms/{room}', 'delete');
        Route::get('/rooms', 'getAll');
        Route::get('/rooms/{room}', 'get');
    });

    Route::controller(RateDayController::class)->group(function() {
        Route::post('/rate-days', 'create');
        Route::patch('/rate-days/{rateDay}', 'update');
        Route::delete('/rate-days/{rateDay}', 'delete');
        Route::get('/rate-days', 'getAll');
        Route::get('/rate-days/{rateDay}', 'get');
    });

    Route::controller(RateHourController::class)->group(function() {
        Route::post('/rate-hours', 'create');
        Route::patch('/rate-hours/{rateHour}', 'update');
        Route::delete('/rate-hours/{rateHour}', 'delete');
        Route::get('/rate-hours', 'getAll');
        Route::get('/rate-hours/{rateHour}', 'get');
    });

    Route::controller(RateController::class)->group(function() {
        Route::post('/rates', 'create');
        Route::patch('/rates/{rate}', 'update');
        Route::delete('/rates/{rate}', 'delete');
        Route::get('/rates', 'getAll');
        Route::get('/rates/{rate}', 'get');
    });

    Route::controller(ReviewController::class)->group(function() {
        Route::post('/reviews', 'create');
        Route::patch('/reviews/{review}', 'update');
        Route::delete('/reviews/{review}', 'delete');
        Route::get('/reviews', 'getAll');
        Route::get('/reviews/{review}', 'get');
    });

    Route::controller(AmenityController::class)->group(function() {
        Route::post('/amenities', 'create');
        Route::patch('/amenities/{amenity}', 'update');
        Route::delete('/amenities/{amenity}', 'delete');
        Route::get('/amenities', 'getAll');
        Route::get('/amenities/{amenity}', 'get');
    });

    Route::controller(RoomAmenityController::class)->group(function() {
        Route::post('/amenities/{room}/add', 'add');
        Route::delete('/amenities/{room}/remove/{amenity}', 'remove');
    });

    Route::controller(RoomImageController::class)->group(function() {
        Route::post('/images/{room}/add', 'add');
        Route::delete('/images/{room}/remove/{picture}', 'remove');
    });

    Route::controller(RoomRateController::class)->group(function() {
        Route::post('/rates/{room}/add', 'add');
        Route::delete('/rates/{room}/remove/{rate}', 'remove');
    });

    Route::controller(RoomReviewController::class)->group(function() {
        Route::post('/reviews/{room}/add', 'add');
        Route::delete('/reviews/{room}/remove/{review}', 'remove');
    });
});
