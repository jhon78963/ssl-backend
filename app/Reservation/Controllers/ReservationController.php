<?php

namespace App\Reservation\Controllers;

use App\Reservation\Models\Reservation;
use App\Reservation\Requests\ProductSearchRequest;
use App\Reservation\Requests\ReservationChangeStatus;
use App\Reservation\Requests\ReservationCreateRequest;
use App\Reservation\Requests\ReservationUpdateRequest;
use App\Reservation\Resources\FacilitiesResource;
use App\Reservation\Resources\ProductsResource;
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

    public function changeStatus(ReservationChangeStatus $request, Reservation $reservation)
    {
        DB::beginTransaction();
        try {
            $editLocker = $this->sharedService->convertCamelToSnake($request->validated());
            $reservationValidated = $this->reservationService->validate(
                $reservation,
                'Reservation'
            );
            $this->reservationService->update($reservationValidated, $editLocker);
            DB::commit();
            return response()->json(['message' => 'Reservation status changed.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function create(ReservationCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newReservation = $this->sharedService->convertCamelToSnake($request->validated());
            $createdReservation = $this->reservationService->create($newReservation);
            DB::commit();
            return response()->json([
                'message' => 'Reservation created.',
                'reservationId' => $createdReservation->id,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function facilities(): JsonResponse
    {
        return response()->json(
            FacilitiesResource::collection(
                $this->reservationService->facilities(),
            ),
        );
    }

    public function products(ProductSearchRequest $request): JsonResponse
    {
        $nameFilter = $request->input('name');
        $products = $nameFilter ? $this->reservationService->products($nameFilter) : [];
        return response()->json(
            ProductsResource::collection($products)
        );
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
