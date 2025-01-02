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
            );
            DB::commit();
            return response()->json(['message' => 'Locker added to the reservation.'], 201);
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

    public function getAll(Reservation $reservation): JsonResponse
    {
        $lockers = $reservation->lockers()->get();
        return response()->json( GetAllAddResource::collection($lockers));
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
}
