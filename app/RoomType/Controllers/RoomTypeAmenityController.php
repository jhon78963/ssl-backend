<?php

namespace App\RoomType\Controllers;

use App\Amenity\Models\Amenity;
use App\Amenity\Resources\AmenityResource;
use App\RoomType\Models\RoomType;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ModelRelationService;
use Illuminate\Http\JsonResponse;

class RoomTypeAmenityController extends Controller
{
    protected ModelRelationService $modelRelationService;

    public function __construct(ModelRelationService $modelRelationService)
    {
        $this->modelRelationService = $modelRelationService;
    }

    public function add(RoomType $roomType, Amenity $amenity): JsonResponse
    {
        $result = $this->modelRelationService->attach($roomType, 'amenities', $amenity->id);
        return $result && isset($result['error'])
            ? response()->json(['message' => $result['error']])
            : response()->json(['message' => 'Amenity added to the room.'], 201);
    }

    public function getAll(RoomType $roomType): JsonResponse
    {
        $amenities = $roomType->amenities()->orderBy('id', 'desc')->get();
        return response()->json( AmenityResource::collection($amenities));
    }

    public function getLeft(RoomType $roomType): JsonResponse
    {
        $allAmenities = Amenity::where('is_deleted', false)->get();
        $associatedAmenities = $roomType->amenities()->pluck('id')->toArray();
        $leftAmenities = $allAmenities->whereNotIn('id', $associatedAmenities);
        return response()->json( AmenityResource::collection($leftAmenities));
    }

    public function remove(RoomType $roomType, Amenity $amenity)
    {
        $result = $this->modelRelationService->detach($roomType, 'amenities', $amenity->id);
        return $result && isset($result['error'])
            ? response()->json(['message' => $result['error']])
            : response()->json(['message' => 'Amenity removed from the room']);
    }
}
