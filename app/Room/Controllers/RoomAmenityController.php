<?php

namespace App\Room\Controllers;

use App\Amenity\Models\Amenity;
use App\Amenity\Requests\AmenityAddRequest;
use App\Amenity\Resources\AmenityResource;
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

    public function add(Room $room, Amenity $amenity): JsonResponse
    {
        $result = $this->roomRelationService->attach($room, 'amenities', $amenity->id);
        return $result && isset($result['error'])
            ? response()->json(['message' => $result['error']])
            : response()->json(['message' => 'Amenity added to the room.'], 201);
    }

    public function getAll(Room $room): JsonResponse
    {
        $amenities = $room->amenities()->orderBy('id', 'desc')->get();
        return response()->json( AmenityResource::collection($amenities));
    }

    public function getLeft(Room $room): JsonResponse
    {
        $allAmenities = Amenity::where('is_deleted', false)->get();
        $associatedAmenities = $room->amenities()->pluck('id')->toArray();
        $leftAmenities = $allAmenities->whereNotIn('id', $associatedAmenities);
        return response()->json( AmenityResource::collection($leftAmenities));
    }

    public function remove(Room $room, Amenity $amenity)
    {
        $result = $this->roomRelationService->detach($room, 'amenities', $amenity->id);
        return $result && isset($result['error'])
            ? response()->json(['message' => $result['error']])
            : response()->json(['message' => 'Amenity removed from the room']);
    }
}
