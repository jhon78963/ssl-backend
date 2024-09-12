<?php

namespace App\Room\Controllers;

use App\Amenity\Models\Amenity;
use App\Amenity\Requests\AmenityAddRequest;
use App\Room\Models\Room;
use App\Room\Services\RoomRelationService;
use App\Shared\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class RoomAmenityController extends Controller
{
    protected RoomRelationService $roomRelationService;

    public function __construct(RoomRelationService $roomRelationService)
    {
        $this->roomRelationService = $roomRelationService;
    }

    public function add(AmenityAddRequest $request, Room $room): JsonResponse
    {
        $result = $this->roomRelationService->attach($room, 'amenities', $request->input('amenityId'));
        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function remove(Room $room, Amenity $amenity): JsonResponse
    {
        $result = $this->roomRelationService->detach($room, 'amenities', $amenity->id);
        return response()->json(['message' => $result['message']], $result['status']);
    }
}
