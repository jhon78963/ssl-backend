<?php

namespace App\Locker\Controllers;

use App\Locker\Models\Locker;
use App\Locker\Requests\LockerChangeStatus;
use App\Locker\Requests\LockerCreateRequest;
use App\Locker\Requests\LockerUpdateRequest;
use App\Locker\Resources\LockerResource;
use App\Locker\Services\LockerService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use DB;

class LockerController extends Controller
{
    protected LockerService $lockerService;
    protected SharedService $sharedService;

    public function __construct(LockerService $lockerService, SharedService $sharedService)
    {
        $this->lockerService = $lockerService;
        $this->sharedService = $sharedService;
    }

    public function changeStatus(LockerChangeStatus $request, Locker $locker): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editLocker = $this->sharedService->convertCamelToSnake($request->validated());
            $lockerValidated = $this->lockerService->validate($locker, 'Locker');
            $this->lockerService->update($lockerValidated, $editLocker);
            DB::commit();
            return response()->json(['message' => 'Room status changed.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function create(LockerCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newLocker = $this->sharedService->convertCamelToSnake($request->validated());
            $this->lockerService->create($newLocker);
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
            $lockerValidated = $this->lockerService->validate($locker, 'Locker');
            $this->lockerService->delete($lockerValidated);
            DB::commit();
            return response()->json(['message' => 'Locker deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Locker $locker): JsonResponse
    {
        $lockerValidated = $this->lockerService->validate($locker, 'Locker');
        return response()->json(new LockerResource($lockerValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Locker',
            'Locker',
            'number'
        );
        return response()->json(new GetAllCollection(
            LockerResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(LockerUpdateRequest $request, Locker $locker): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editLocker = $this->sharedService->convertCamelToSnake($request->validated());
            $lockerValidated = $this->lockerService->validate($locker, 'Locker');
            $this->lockerService->update($lockerValidated, $editLocker);
            DB::commit();
            return response()->json(['message' => 'Locker updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
