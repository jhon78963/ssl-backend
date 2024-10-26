<?php

namespace App\Reservation\Controllers;

use App\Reservation\Requests\ReservationCreateRequest;
use App\Reservation\Services\ReservationService;
use App\Shared\Controllers\Controller;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class ReservationController extends Controller
{
    protected ReservationService $reservationService;
    protected SharedService $sharedService;

    public function __construct(ReservationService $reservationService, SharedService $sharedService)
    {
        $this->reservationService = $reservationService;
        $this->sharedService = $sharedService;
    }

    public function create(ReservationCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newReservation = $this->sharedService->convertCamelToSnake($request->validated());
            $this->reservationService->create($newReservation);
            DB::commit();
            return response()->json(['message' => 'Reservation created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
