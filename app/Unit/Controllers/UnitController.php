<?php

namespace App\Unit\Controllers;

use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use App\Unit\Models\Unit;
use App\Unit\Requests\UnitCreateRequest;
use App\Unit\Requests\UnitUpdateRequest;
use App\Unit\Resources\UnitResource;
use App\Unit\Services\UnitService;
use Illuminate\Http\JsonResponse;
use DB;

class UnitController
{
    protected SharedService $sharedService;
    protected UnitService $unitService;

    public function __construct(SharedService $sharedService, UnitService $unitService)
    {
        $this->sharedService = $sharedService;
        $this->unitService = $unitService;
    }

    public function create(UnitCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newUnit = $this->sharedService->convertCamelToSnake($request->validated());
            $this->unitService->create($newUnit);
            DB::commit();
            return response()->json(['message' => 'Unit created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Unit $unit): JsonResponse
    {
        DB::beginTransaction();
        try {
            $unitValidated = $this->unitService->validate($unit, 'Unit');
            $this->unitService->delete($unitValidated);
            DB::commit();
            return response()->json(['message' => 'Unit deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Unit $unit): JsonResponse
    {
        $unitValidated = $this->unitService->validate($unit, 'Unit');
        return response()->json(new UnitResource($unitValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Unit',
            'Unit',
            'unit'
        );
        return response()->json(new GetAllCollection(
            UnitResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(UnitUpdateRequest $request, Unit $unit): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editUnit = $this->sharedService->convertCamelToSnake($request->validated());
            $unitValidated = $this->unitService->validate($unit, 'Unit');
            $this->unitService->update($unitValidated, $editUnit);
            DB::commit();
            return response()->json(['message' => 'Unit updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
