<?php

namespace App\Room\Controllers;

use App\Room\Models\Room;
use App\Room\Requests\RoomChangeStatus;
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
    protected RoomService $roomService;
    protected SharedService $sharedService;

    public function __construct(
        RoomService $roomService,
        SharedService $sharedService
    ) {
        $this->roomService = $roomService;
        $this->sharedService = $sharedService;
    }

    public function changeStatus(RoomChangeStatus $request, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editRoom = $this->sharedService->convertCamelToSnake($request->validated());
            $roomValidated = $this->roomService->validate($room, 'Room');
            $this->roomService->update($roomValidated, $editRoom);
            DB::commit();
            return response()->json(['message' => 'Room status changed.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function create(RoomCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newRoom = $this->sharedService->convertCamelToSnake($request->validated());
            $this->roomService->create($newRoom);
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
            $roomValidated = $this->roomService->validate($room, 'Room');
            $this->roomService->delete($roomValidated);
            DB::commit();
            return response()->json(['message' => 'Room deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Room  $room): JsonResponse
    {
        $roomValidated = $this->roomService->validate($room, 'Room');
        return response()->json(new RoomResource( $roomValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Room',
            'Room',
            'room_number'
        );
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
            $editRoom = $this->sharedService->convertCamelToSnake($request->validated());
            $roomValidated = $this->roomService->validate($room, 'Room');
            $this->roomService->update($roomValidated, $editRoom);
            DB::commit();
            return response()->json(['message' => 'Room updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
