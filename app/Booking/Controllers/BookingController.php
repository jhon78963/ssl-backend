<?php

namespace App\Booking\Controllers;

use App\Booking\Models\Booking;
use App\Booking\Requests\BookingChangeStatusRequest;
use App\Booking\Requests\BookingCreateRequest;
use App\Booking\Requests\BookingUpdateRequest;
use App\Booking\Resources\BookingResource;
use App\Booking\Services\BookingService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected SharedService $sharedService;

    public function __construct(
        BookingService $bookingService,
        SharedService $sharedService,
    ) {
        $this->bookingService = $bookingService;
        $this->sharedService = $sharedService;
    }

    public function changeStatus(BookingChangeStatusRequest $request, Booking $booking): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editBooking = $this->sharedService->convertCamelToSnake($request->validated());
            $bookingValidated = $this->bookingService->validate(
                $booking,
                'Booking'
            );
            $this->bookingService->update($bookingValidated, $editBooking);
            DB::commit();
            return response()->json(['message' => 'Booking status changed.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function create(BookingCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newBooking = $this->sharedService->convertCamelToSnake($request->validated());
            $createdBooking = $this->bookingService->create($newBooking);
            DB::commit();
            return response()->json([
                'message' => 'Booking created.',
                'bookingId' => $createdBooking->id,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Booking $booking): JsonResponse
    {
        $bookingValidated = $this->bookingService->validate($booking, 'Booking');
        return response()->json(new BookingResource($bookingValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->bookingService->getAll(
            $request,
            'Booking',
            'Booking',
            $request->input('startDate'),
            $request->input('endDate'),
            $request->input('dni'),
        );
        return response()->json(new GetAllCollection(
            BookingResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(BookingUpdateRequest $request, Booking $booking): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editBookingValidated = $this->sharedService->convertCamelToSnake($request->validated());
            $bookingValidated = $this->bookingService->validate($booking, 'Booking');
            $this->bookingService->update(
                $bookingValidated,
                $editBookingValidated
            );
            DB::commit();
            return response()->json(['message' => 'Booking updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
