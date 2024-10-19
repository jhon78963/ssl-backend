<?php

namespace App\RoomType\Controllers;

use App\RoomType\Models\RoomType;
use App\RoomType\Requests\RoomTypeCreateRequest;
use App\RoomType\Requests\RoomTypeUpdateRequest;
use App\RoomType\Resources\RoomTypeResource;
use App\RoomType\Services\RoomTypeService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class RoomTypeController extends Controller
{
    protected $roomTypeService;
    protected $sharedService;

    public function __construct(RoomTypeService $roomTypeService, SharedService $sharedService)
    {
        $this->roomTypeService = $roomTypeService;
        $this->sharedService = $sharedService;
    }

    public function create(RoomTypeCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newRoomType = $this->sharedService->convertCamelToSnake($request->validated());
            $this->roomTypeService->create($newRoomType);
            DB::commit();
            return response()->json(['message' => 'Room type created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(RoomType $roomType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $roomTypelidated = $this->roomTypeService->validate($roomType, 'RoomType');
            $this->roomTypeService->delete($roomTypelidated);
            DB::commit();
            return response()->json(['message' => 'Room type deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(RoomType $roomType): JsonResponse
    {
        $roomTypeValidated = $this->roomTypeService->validate($roomType, 'RoomType');
        return response()->json(new RoomTypeResource($roomTypeValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'RoomType',
            'RoomType',
            'name',
        );

        return response()->json(new GetAllCollection(
            RoomTypeResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(RoomTypeUpdateRequest $request, RoomType $roomType): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editRoomType = $this->sharedService->convertCamelToSnake($request->validated());
            $roomTypeValidated = $this->roomTypeService->validate($roomType, 'RoomType');
            $this->roomTypeService->update($roomTypeValidated, $editRoomType);
            DB::commit();
            return response()->json(['message' => 'Room type updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
