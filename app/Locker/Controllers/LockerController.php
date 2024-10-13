<?php

namespace App\Locker\Controllers;

use App\Locker\Models\Locker;
use App\Locker\Requests\LockerCreateRequest;
use App\Locker\Requests\LockerUpdateRequest;
use App\Locker\Resources\LockerResource;
use App\Locker\Services\LockerService;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use DB;
use Illuminate\Http\JsonResponse;

class LockerController
{
    protected LockerService $lockerService;
    protected SharedService $sharedService;

    public function __construct(LockerService $lockerService, SharedService $sharedService)
    {
        $this->lockerService = $lockerService;
        $this->sharedService = $sharedService;
    }

    public function create(LockerCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->lockerService->createLocker($request->validated());
            DB::commit();
            return response()->json(['message' => 'Locker created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Locker $locker): JsonResponse
    {
        DB::beginTransaction();
        try {
            $lockerValidated = $this->sharedService->validateModel($locker, 'Locker');
            $this->sharedService->deleteModel($lockerValidated);
            DB::commit();
            return response()->json(['message' => 'Locker deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Locker $locker): JsonResponse
    {
        $lockerValidated = $this->sharedService->validateModel($locker, 'Locker');
        return response()->json(new LockerResource($lockerValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Locker', 'Locker', 'number');
        return response()->json(new GetAllCollection(
            LockerResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(LockerUpdateRequest $request, Locker $locker)
    {
        DB::beginTransaction();
        try {
            $lockerValidated = $this->sharedService->validateModel($locker, 'Locker');
            $this->lockerService->updateLocker($lockerValidated, $request->validated());
            DB::commit();
            return response()->json(['message' => 'Locker updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
