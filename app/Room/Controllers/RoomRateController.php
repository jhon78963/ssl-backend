<?php

namespace App\Room\Controllers;

use App\Rate\Models\Rate;
use App\Rate\Requests\RateAddRequest;
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
    public function add(RateAddRequest $request, Room $room): JsonResponse
    {
        $result = $this->roomRelationService->attach($room, 'rates', $request->input('rateId'));
        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function remove(Room $room, Rate $rate): JsonResponse
    {
        $result = $this->roomRelationService->detach($room, 'rates', $rate->id);
        return response()->json(['message' => $result['message']], $result['status']);
    }
}
