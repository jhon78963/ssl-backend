<?php

namespace App\Room\Controllers;
use App\Room\Models\Room;
use App\Room\Requests\RoomCreateRequest;
use App\Room\Requests\RoomUpdateRequest;
use App\Room\Resources\RoomResource;
use App\Room\Services\RoomService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class RoomController extends Controller
{
    protected $roomService;
    protected $sharedService;

    public function __construct(RoomService $roomService, SharedService $sharedService)
    {
        $this->roomService = $roomService;
        $this->sharedService = $sharedService;
    }

    public function create(RoomCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->roomService->createRoom($request->validated());
            DB::commit();
            return response()->json(['message' => 'Room created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Room  $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $roomValidated = $this->sharedService->validateModel($room, 'Room');
            $this->sharedService->deleteModel($roomValidated);
            DB::commit();
            return response()->json(['message' => 'Room deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Room  $room): JsonResponse
    {
        $roomValidated = $this->sharedService->validateModel($room, 'Room');
        return response()->json(new RoomResource( $roomValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Room', 'Room', 'name');
        return response()->json(new GetAllCollection(
            RoomResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(RoomUpdateRequest $request, Room  $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $roomValidated = $this->sharedService->validateModel($room, 'Room');
            $this->roomService->updateRoom($roomValidated, $request->validated());
            DB::commit();
            return response()->json(['message' => 'Room updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
