<?php

namespace App\Booking\Controllers;

use App\Booking\Models\Booking;
use App\Booking\Services\BookingService;
use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Requests\ModifyRequest;
use App\Shared\Resources\GetAllAddResource;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class BookingRoomController extends Controller
{
    protected BookingService $bookingService;
    protected ModelService $modelService;

    public function __construct(BookingService $bookingService, ModelService $modelService)
    {
        $this->bookingService = $bookingService;
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Booking $booking, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $booking,
                'rooms',
                $room->id,
                $request->input('price'),
                1,
                null,
                null,
                null,
                null,
                null,
                $request->input('additionalPeople'),
                null,
            );
            DB::commit();
            $this->updateEndDate($booking, $room);
            return response()->json(['message' => 'Room added to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function modify(ModifyRequest $request, Booking $booking, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->modify(
                $booking,
                'rooms',
                $room->id,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                $request->input('additionalPeople'),
                null,
            );
            DB::commit();
            return response()->json(['message' => 'Room modified to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function getAll(Booking $booking): JsonResponse
    {
        $rooms = $booking->rooms()->get();
        return response()->json( GetAllAddResource::collection($rooms));
    }

    public function remove(Booking $booking, Room $room, float $price): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($booking, 'rooms', $room->id);
            $editBooking = [
                'total' => $booking->total - $price,
            ];
            $this->modelService->update($booking, $editBooking);
            DB::commit();
            return response()->json(['message' => 'Room removed from the booking']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function updateEndDate(Booking $booking, Room $room): void {
        $booking->end_date = $this->bookingService->increaseHours(
            $booking->start_date,
            $room->roomType->rental_hours,
        );
        $booking->save();
    }
}
