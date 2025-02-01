<?php

use App\Booking\Controllers\BookingController;
use App\Booking\Controllers\BookingPaymentTypeController;
use App\Booking\Controllers\BookingProductController;
use App\Booking\Controllers\BookingRoomController;
use App\Booking\Controllers\BookingServiceController;
use Illuminate\Support\Facades\Route;

Route::controller(BookingController::class)->group(function() {
    Route::post('/bookings', 'create');
    Route::post('/bookings/check-schedule/room/{room}', 'checkSchedule');
    Route::patch('/bookings/change-status/{booking}', 'changeStatus');
    Route::patch('/bookings/{booking}', 'update');
    // Route::delete('/bookings/{booking}', 'delete');
    Route::get('/bookings', 'getAll');
    Route::get('/bookings/{booking}', 'get');
});

Route::controller(BookingRoomController::class)->group(function() {
    Route::post('bookings/{booking}/rooms/{room}', 'add');
    Route::patch('bookings/{booking}/rooms/{room}', 'modify');
    Route::delete('bookings/{booking}/rooms/{room}/price/{price}', 'remove');
});

Route::controller(BookingProductController::class)->group(function() {
    Route::post('bookings/{booking}/products/{product}', 'add');
    Route::patch('bookings/{booking}/products/{product}', 'modify');
    Route::delete('bookings/{booking}/products/{product}/quantity/{quantity}', 'remove');
});

Route::controller(BookingServiceController::class)->group(function() {
    Route::post('bookings/{booking}/services/{service}', 'add');
    Route::patch('bookings/{booking}/services/{service}', 'modify');
    Route::delete('bookings/{booking}/services/{service}/quantity/{quantity}', 'remove');
});

Route::controller(BookingPaymentTypeController::class)->group(function() {
    Route::post('bookings/{booking}/payment-types/{paymentType}', 'add');
    Route::delete(
        'bookings/{booking}/payment-types/{paymentTypeId}/payment/{payment}/cash-payment/{cashPayment}/card-payment/{cardPayment}',
        'remove'
    );
});
