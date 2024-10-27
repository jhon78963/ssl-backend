<?php

namespace App\Reservation\Controllers;

use App\Reservation\Models\Reservation;
use App\Reservation\Requests\ReservationCreateRequest;
use App\Reservation\Requests\ReservationUpdateRequest;
use App\Reservation\Resources\ReservationResource;
use App\Reservation\Services\ReservationService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
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

    public function get(Reservation $reservation): JsonResponse
    {
        $reservationValidated = $this->reservationService->validate($reservation, 'Reservation');
        return response()->json(new ReservationResource($reservationValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Reservation',
            'Reservation',
            'reservation_date'
        );
        return response()->json(new GetAllCollection(
            ReservationResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(ReservationUpdateRequest $request, Reservation $reservation): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editReservationValidated = $this->sharedService->convertCamelToSnake($request->validated());
            $reservationValidated = $this->reservationService->validate($reservation, 'ProductType');
            $this->reservationService->update($reservationValidated, $editReservationValidated);
            DB::commit();
            return response()->json(['message' => 'Reservation updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
