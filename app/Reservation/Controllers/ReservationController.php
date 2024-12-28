<?php

namespace App\Reservation\Controllers;

use App\Cash\Services\CashOperationService;
use App\Cash\Services\CashService;
use App\Reservation\Enums\ReservationStatus;
use App\Reservation\Models\Reservation;
use App\Reservation\Requests\ProductSearchRequest;
use App\Reservation\Requests\ReservationChangeStatus;
use App\Reservation\Requests\ReservationCreateRequest;
use App\Reservation\Requests\ReservationUpdateRequest;
use App\Reservation\Resources\FacilitiesResource;
use App\Reservation\Resources\ProductsResource;
use App\Reservation\Resources\ReservationResource;
use App\Reservation\Resources\ReservationTypeResource;
use App\Reservation\Services\ReservationService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class ReservationController extends Controller
{
    protected CashService $cashService;
    protected CashOperationService $cashOperationService;
    protected ReservationService $reservationService;
    protected SharedService $sharedService;

    public function __construct(
        CashService $cashService,
        CashOperationService $cashOperationService,
        ReservationService $reservationService,
        SharedService $sharedService
    ) {
        $this->cashService = $cashService;
        $this->cashOperationService = $cashOperationService;
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

    public function reservationTypes(): JsonResponse
    {
        return response()->json(
            ReservationTypeResource::collection(
                $this->reservationService->reservationTypes(),
            ),
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
            'reservation_type_id',
            $request->input('startDate'),
            $request->input('endDate'),
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
            $reservationUpdated = $this->reservationService->update(
                $reservationValidated,
                $editReservationValidated
            );
            DB::commit();
            if ($reservationUpdated->status == ReservationStatus::Completed) {
                $cash = $this->cashService->currentCash();
                $this->cashOperationService->create([
                    'cash_id' => $cash->id,
                    'cash_type_id' => 2,
                    'schedule_id' => $this->cashOperationService->schedule(),
                    'date' => now(),
                    'amount' => $reservationUpdated->total,
                ]);
            }
            return response()->json(['message' => 'Reservation updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
