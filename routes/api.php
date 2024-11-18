<?php

use App\Amenity\Controllers\AmenityController;
use App\Auth\Controllers\AuthController;
use App\Category\Controllers\CategoryController;
use App\Company\Controllers\CompanyController;
use App\Company\Controllers\SocialNetworkController;
use App\Customer\Controllers\CustomerController;
use App\Gender\Controllers\GenderController;
use App\Image\Controllers\ImageController;
use App\Locker\Controllers\LockerController;
use App\Product\Controllers\ProductController;
use App\ProductType\Controllers\ProductTypeController;
use App\Rate\Controllers\RateController;
use App\Rate\Controllers\RateDayController;
use App\Rate\Controllers\RateHourController;
use App\Reservation\Controllers\ReservationController;
use App\Reservation\Controllers\ReservationCustomerController;
use App\Reservation\Controllers\ReservationProductController;
use App\Reservation\Controllers\ReservationServiceController;
use App\ReservationType\Controllers\ReservationTypeController;
use App\Review\Controllers\ReviewController;
use App\Role\Controllers\RoleController;
use App\Room\Controllers\RoomController;
use App\Room\Controllers\RoomImageController;
use App\Room\Controllers\RoomReviewController;
use App\RoomType\Controllers\RoomTypeAmenityController;
use App\RoomType\Controllers\RoomTypeController;
use App\RoomType\Controllers\RoomTypeRateController;
use App\Service\Controllers\ServiceController;
use App\Unit\Controllers\UnitController;
use App\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTION');
// header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Auth-Token');

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);
Route::post('auth/logout', [AuthController::class,'logout']);

Route::group([
    'middleware' => 'auth:sanctum',
], function (): void {
    Route::patch('auth/me', [AuthController::class, 'updateMe']);
    Route::post('auth/me', [AuthController::class, 'getMe']);
    Route::post('auth/change-password', [AuthController::class,'changePassword']);

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
        Route::patch('/rooms/change-status/{room}', 'changeStatus');
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

    Route::controller(ImageController::class)->group(function() {
        Route::delete('/images/{image}', 'delete');
        Route::get('/images', 'getAll');
        Route::get('/images/{image}', 'get');
    });

    Route::controller(RoomTypeAmenityController::class)->group(function() {
        Route::post('/amenities/{roomType}/add/{amenity}', 'add');
        Route::delete('/amenities/{roomType}/remove/{amenity}', 'remove');
        Route::get('/amenities/{roomType}/all', 'getAll');
        Route::get('/amenities/{roomType}/left', 'getLeft');
    });

    Route::controller(RoomImageController::class)->group(function() {
        Route::post('/images/{room}/multiple-add', 'multipleAdd');
        Route::post('/images/{room}/add/{image}', 'add');
        Route::delete('/images/{room}/remove/{image}', 'remove');
        Route::get('/images/{room}/all', 'getAll');
        Route::get('/images/{room}/left', 'getLeft');
    });

    Route::controller(RoomTypeRateController::class)->group(function() {
        Route::post('/rates/{roomType}/add/{rate}', 'add');
        Route::delete('/rates/{roomType}/remove/{rate}', 'remove');
        Route::get('/rates/{roomType}/all', 'getAll');
        Route::get('/rates/{roomType}/left', 'getLeft');
    });

    Route::controller(RoomReviewController::class)->group(function() {
        Route::post('/reviews/{room}/add', 'add');
        Route::delete('/reviews/{room}/remove/{review}', 'remove');
        Route::get('/reviews/{room}/all', 'getAll');
    });

    Route::controller(GenderController::class)->group(function() {
        Route::get('/genders', 'getAll');
        Route::get('/genders/{gender}', 'get');
    });

    Route::controller(LockerController::class)->group(function() {
        Route::post('/lockers', 'create');
        Route::patch('/lockers/change-status/{locker}', 'changeStatus');
        Route::patch('/lockers/{locker}', 'update');
        Route::delete('/lockers/{locker}', 'delete');
        Route::get('/lockers', 'getAll');
        Route::get('/lockers/{locker}', 'get');
    });

    Route::controller(CustomerController::class)->group(function() {
        Route::post('/customers', 'create');
        Route::patch('/customers/{customer}', 'update');
        Route::delete('/customers/{customer}', 'delete');
        Route::get('/customers', 'getAll');
        Route::get('/customers/dni/{customer}', 'getByDni');
        Route::get('/customers/{customer}', 'get');
        Route::get('/consultation-dni/{dni}', 'searchByDni');
        Route::get('/consultation-ruc/{ruc}', 'searchByRuc');
    });

    Route::controller(UnitController::class)->group(function() {
        Route::post('/units', 'create');
        Route::patch('/units/{unit}', 'update');
        Route::delete('/units/{unit}', 'delete');
        Route::get('/units', 'getAll');
        Route::get('/units/{unit}', 'get');
    });

    Route::controller(CategoryController::class)->group(function() {
        Route::post('/categories', 'create');
        Route::patch('/categories/{category}', 'update');
        Route::delete('/categories/{category}', 'delete');
        Route::get('/categories', 'getAll');
        Route::get('/categories/{category}', 'get');
    });

    Route::controller(ProductTypeController::class)->group(function() {
        Route::post('/product-types', 'create');
        Route::patch('/product-types/{productType}', 'update');
        Route::delete('/product-types/{productType}', 'delete');
        Route::get('/product-types', 'getAll');
        Route::get('/product-types/{productType}', 'get');
    });

    Route::controller(ProductController::class)->group(function() {
        Route::post('/products', 'create');
        Route::patch('/products/{product}', 'update');
        Route::delete('/products/{product}', 'delete');
        Route::get('/products', 'getAll');
        Route::get('/products/{product}', 'get');
    });

    Route::controller(ServiceController::class)->group(function() {
        Route::post('/services', 'create');
        Route::patch('/services/{service}', 'update');
        Route::delete('/services/{service}', 'delete');
        Route::get('/services', 'getAll');
        Route::get('/services/{service}', 'get');
    });

    Route::controller(ReservationTypeController::class)->group(function() {
        Route::post('/reservation-types', 'create');
        Route::patch('/reservation-types/{reservationType}', 'update');
        Route::delete('/reservation-types/{reservationType}', 'delete');
        Route::get('/reservation-types', 'getAll');
        Route::get('/reservation-types/{reservationType}', 'get');
    });

    Route::controller(ReservationController::class)->group(function() {
        Route::post('/reservations', 'create');
        Route::patch('/reservations/change-status/{reservation}', 'update');
        Route::patch('/reservations/{reservation}', 'update');
        // Route::delete('/reservations/{reservation}', 'delete');
        Route::get('/reservations', 'getAll');
        Route::get('/reservations/facilities', 'facilities');
        Route::get('/reservations/{reservation}', 'get');
    });

    Route::controller(ReservationProductController::class)->group(function() {
        Route::post('/products/{reservation}/add/{product}', 'add');
        Route::delete('/products/{reservation}/remove/{product}/quantity/{quantity}', 'remove');
        Route::get('/products/{reservation}/all', 'getAll');
        Route::get('/products/{reservation}/left', 'getLeft');
    });

    Route::controller(ReservationServiceController::class)->group(function() {
        Route::post('/services/{reservation}/add/{service}', 'add');
        Route::delete('/services/{reservation}/remove/{service}/quantity/{quantity}', 'remove');
        Route::get('/services/{reservation}/all', 'getAll');
        Route::get('/services/{reservation}/left', 'getLeft');
    });

    Route::controller(ReservationCustomerController::class)->group(function() {
        Route::post('/customers/{reservation}/add/{customer}', 'add');
        Route::delete('/customers/{reservation}/remove/{customer}/price/{price}', 'remove');
        Route::get('/customers/{reservation}/all', 'getAll');
    });
});
