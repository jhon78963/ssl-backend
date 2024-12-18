<?php

namespace App\Reservation\Controllers;

use App\Reservation\Models\Reservation;
use App\Room\Models\Room;
use App\Service\Resources\ServiceGetAllAddResource;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Requests\ModifyRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class ReservationRoomController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Reservation $reservation, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $reservation,
                'rooms',
                $room->id,
                $request->input('price'),
                1,
                $request->input('isPaid'),
            );
            DB::commit();
            return response()->json(['message' => 'Room added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function modify(ModifyRequest $request, Reservation $reservation, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->modify(
                $reservation,
                'rooms',
                $room->id,
                null,
                null,
                $request->input('isPaid'),
            );
            DB::commit();
            return response()->json(['message' => 'Room modified to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function getAll(Reservation $reservation): JsonResponse
    {
        $rooms = $reservation->rooms()->get();
        return response()->json( ServiceGetAllAddResource::collection($rooms));
    }

    public function remove(Reservation $reservation, Room $room, float $price): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($reservation, 'rooms', $room->id);
            $editReservation = [
                'total' => $reservation->total - $price,
            ];
            $this->modelService->update($reservation, $editReservation);
            DB::commit();
            return response()->json(['message' => 'Room removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
