<?php

namespace App\Room\Controllers;

use App\Rate\Models\Rate;
use App\Rate\Requests\RateAddRequest;
use App\Rate\Resources\RateResource;
use App\Room\Models\Room;
use App\Room\Services\RoomRelationService;
use App\Shared\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class RoomRateController extends Controller
{
    protected RoomRelationService $roomRelationService;

    public function __construct(RoomRelationService $roomRelationService)
    {
        $this->roomRelationService = $roomRelationService;
    }
    public function add(Room $room, Rate $rate): JsonResponse
    {
        $result = $this->roomRelationService->attach($room, 'rates', $rate->id);
            return $result && isset($result['error'])
                ? response()->json(['message' => $result['error']])
                : response()->json(['message' => 'Rate added to the room.'], 201);
    }

    public function getAll(Room $room): JsonResponse
    {
        $rates = $room->rates()->orderBy('id', 'desc')->get();
        return response()->json( RateResource::collection($rates));
    }

    public function getLeft(Room $room): JsonResponse
    {
        $allRates = Rate::where('is_deleted', false)->get();
        $associatedRates = $room->rates()->pluck('id')->toArray();
        $leftRates = $allRates->whereNotIn('id', $associatedRates);
        return response()->json( RateResource::collection($leftRates));
    }

    public function remove(Room $room, Rate $rate): JsonResponse
    {
        $result = $this->roomRelationService->detach($room, 'rates', $rate->id);
        return $result && isset($result['error'])
            ? response()->json(['message' => $result['error']])
            : response()->json(['message' => 'Rate removed from the room']);
    }
}
