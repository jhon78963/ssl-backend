<?php

use App\Reservation\Controllers\ReservationController;
use App\Reservation\Controllers\ReservationInventoryController;
use App\Reservation\Controllers\ReservationLockerController;
use App\Reservation\Controllers\ReservationPaymentTypeController;
use App\Reservation\Controllers\ReservationProductController;
use App\Reservation\Controllers\ReservationRoomController;
use App\Reservation\Controllers\ReservationServiceController;
use Illuminate\Support\Facades\Route;

Route::controller(ReservationController::class)->group(function() {
    Route::post('/reservations', 'create');
    Route::post('/reservations/export',  'export');
    Route::patch('/reservations/change-status/{reservation}', 'changeStatus');
    Route::patch('/reservations/{reservation}', 'update');
    // Route::delete('/reservations/{reservation}', 'delete');
    Route::get('/reservations', 'getAll');
    Route::get('/reservations/reservationTypes', 'reservationTypes');
    Route::get('/reservations/facilities/count', 'validateFacilities');
    Route::get('/reservations/facilities', 'facilities');
    Route::get('/reservations/products', 'products');
    Route::get('/reservations/{reservation}', 'get');
});

Route::controller(ReservationProductController::class)->group(function() {
    Route::post('/reservations/{reservation}/products/{product}', 'add');
    Route::patch('/reservations/{reservation}/products/{product}', 'modify');
    Route::delete('/reservations/{reservation}/products/{product}/quantity/{quantity}', 'remove');
});

Route::controller(ReservationServiceController::class)->group(function() {
    Route::post('/reservations/{reservation}/services/{service}', 'add');
    Route::patch('/reservations/{reservation}/services/{service}', 'modify');
    Route::delete('/reservations/{reservation}/services/{service}/quantity/{quantity}', 'remove');
});

Route::controller(ReservationLockerController::class)->group(function() {
    Route::post('/reservations/{reservation}/lockers/{locker}', 'add');
    Route::post('/reservations/{reservation}/lockers/{locker}/consumption/{consumption}', 'updateConsumptionReservationLocker');
    Route::post('/reservations/{reservation}/lockers/{locker}/new-lockers/{newLocker}', 'change');
    Route::post('/reservations/{reservationId}/lockers/{lockerId}/new-rooms/{roomId}/new-price/{newPrice}/old-price/{oldPrice}', 'changeToRoom');
    Route::patch('/reservations/{reservation}/lockers/{locker}', 'modify');
    Route::delete('/reservations/{reservation}/lockers/{locker}/price/{price}', 'remove');
});

Route::controller(ReservationRoomController::class)->group(function() {
    Route::post('/reservations/{reservation}/rooms/{room}', 'add');
    Route::post('/reservations/{reservation}/rooms/{room}/new-rooms/{newRoom}', 'change');
    Route::post('/reservations/{reservationId}/rooms/{roomId}/new-lockers/{lockerId}/new-price/{newPrice}/old-price/{oldPrice}', 'changeToLocker');
    Route::patch('/reservations/{reservation}/rooms/{room}', 'modify');
    Route::delete('/reservations/{reservation}/rooms/{room}/price/{price}', 'remove');
});

Route::controller(ReservationPaymentTypeController::class)->group(function() {
    Route::post('/reservations/{reservation}/payment-types/{paymentType}', 'add');
    Route::post('/reservations/{reservation}/refunded-amount/{refundedAmount}', 'refund');
    Route::delete(
        '/reservations/{reservation}/payment-types/{paymentTypeId}/payment/{payment}',
        'remove'
    );
});

Route::controller(ReservationInventoryController::class)->group(function() {
    Route::post('/reservations/{reservation}/inventories/{inventory}', 'add');
    Route::delete('/reservations/{reservation}/inventories/{inventory}/quantity/{quantity}', 'remove');
});
