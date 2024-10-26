<?php

namespace App\ReservationType\Controllers;

use App\ReservationType\Models\ReservationType;
use App\ReservationType\Requests\ReservationTypeCreateRequest;
use App\ReservationType\Requests\ReservationTypeUpdateRequest;
use App\ReservationType\Resources\ReservationTypeResource;
use App\ReservationType\Services\ReservationTypeService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class ReservationTypeController extends Controller
{
    protected ReservationTypeService $ReservationTypeService;
    protected SharedService $sharedService;

    public function __construct(ReservationTypeService $ReservationTypeService, SharedService $sharedService)
    {
        $this->ReservationTypeService = $ReservationTypeService;
        $this->sharedService = $sharedService;
    }

    public function create(ReservationTypeCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newReservationType = $this->sharedService->convertCamelToSnake($request->validated());
            $this->ReservationTypeService->create($newReservationType);
            DB::commit();
            return response()->json(['message' => 'Reservation type created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(ReservationType $reservationType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $reservationTypeValidated = $this->ReservationTypeService->validate($reservationType, 'ReservationType');
            $this->ReservationTypeService->delete($reservationTypeValidated);
            DB::commit();
            return response()->json(['message' => 'Product type deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(ReservationType $reservationType): JsonResponse
    {
        $reservationTypeValidated = $this->ReservationTypeService->validate($reservationType, 'ReservationType');
        return response()->json(new ReservationTypeResource($reservationTypeValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'ReservationType',
            'ReservationType',
            'description'
        );
        return response()->json(new GetAllCollection(
            ReservationTypeResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(ReservationTypeUpdateRequest $request, ReservationType $reservationType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editReservationType = $this->sharedService->convertCamelToSnake($request->validated());
            $reservationTypeValidated = $this->ReservationTypeService->validate($reservationType, 'ReservationType');
            $this->ReservationTypeService->update($reservationTypeValidated, $editReservationType);
            DB::commit();
            return response()->json(['message' => 'Product type updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
