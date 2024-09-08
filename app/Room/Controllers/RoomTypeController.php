<?php

namespace App\Room\Controllers;
use App\Room\Models\RoomType;
use App\Room\Requests\RoomTypeCreateRequest;
use App\Room\Requests\RoomTypeUpdateRequest;
use App\Room\Resources\RoomTypeResource;
use App\Room\Services\RoomTypeService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class RoomTypeController extends Controller
{
    protected $rommTypeService;
    protected $sharedService;

    public function __construct(RoomTypeService $rommTypeService, SharedService $sharedService)
    {
        $this->rommTypeService = $rommTypeService;
        $this->sharedService = $sharedService;
    }

    public function create(RoomTypeCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->rommTypeService->createRoomType($request->validated());
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
            $roomTypelidated = $this->sharedService->validateModel($roomType, 'RoomType');
            $this->sharedService->deleteModel($roomTypelidated);
            DB::commit();
            return response()->json(['message' => 'Room type deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(RoomType $roomType): JsonResponse
    {
        $roomTypeValidated = $this->sharedService->validateModel($roomType, 'RoomType');
        return response()->json(new RoomTypeResource($roomTypeValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Room', 'RoomType', 'name');
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
            $roomTypeValidated = $this->sharedService->validateModel($roomType, 'RoomType');
            $this->rommTypeService->updateRoomType($roomTypeValidated, $request->validated());
            DB::commit();
            return response()->json(['message' => 'Room type updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
