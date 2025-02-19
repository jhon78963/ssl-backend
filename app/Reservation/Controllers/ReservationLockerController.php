<?php

namespace App\Reservation\Controllers;

use App\Locker\Models\Locker;
use App\Reservation\Models\Reservation;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Requests\ModifyRequest;
use App\Shared\Resources\GetAllAddResource;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class ReservationLockerController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Reservation $reservation, Locker $locker): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $reservation,
                'lockers',
                $locker->id,
                $request->input('price'),
                1,
                $request->input('isPaid'),
                null,
                null,
                null,
                null,
                null,
                null,
                $request->input('consumption'),
            );
            DB::commit();
            return response()->json(['message' => 'Locker added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function changeToRoom(
        int $reservationId,
        int $lockerId,
        int $roomId,
        float $newPrice,
        float $oldPrice,
    ): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->changeModel(
                $reservationId,
                2,
                'reservation_locker',
                'locker_id',
                $lockerId,
                'reservation_room',
                'room_id',
                $roomId,
                $newPrice,
                $oldPrice,
            );
            DB::commit();
            return response()->json(['message' => 'Locker changed to Room.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function modify(ModifyRequest $request, Reservation $reservation, Locker $locker): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->modify(
                $reservation,
                'lockers',
                $locker->id,
                null,
                null,
                $request->input('isPaid'),
            );
            DB::commit();
            return response()->json(['message' => 'Locker modified to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function change(Reservation $reservation, Locker $locker, Locker $newLocker): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->change(
                'reservation_locker',
                'reservation_id',
                'locker_id',
                $reservation->id,
                $locker->id,
                $newLocker->id,
            );
            DB::commit();
            return response()->json(['message' => 'Locker changed to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(Reservation $reservation, Locker $locker, float $price): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($reservation, 'lockers', $locker->id);
            $editReservation = [
                'total' => $reservation->total - $price,
            ];
            $this->modelService->update($reservation, $editReservation);
            DB::commit();
            return response()->json(['message' => 'Locker removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateConsumptionReservationLocker(
        Reservation $reservation,
        int $lockerId,
        float $consumption,
    ): JsonResponse {
        DB::beginTransaction();
        try {
            $pivotData = $reservation->lockers()
                    ->where('locker_id', $lockerId)
                    ->first()
                    ->pivot;

            $reservation->lockers()->updateExistingPivot($lockerId, [
                'consumption' => $pivotData->consumption + $consumption,
            ]);
            DB::commit();
            return response()->json(['message' => 'Locker updated to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }
}
