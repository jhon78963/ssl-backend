<?php

namespace App\RoomType\Controllers;

use App\Amenity\Models\Amenity;
use App\Amenity\Resources\AmenityResource;
use App\RoomType\Models\RoomType;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ModelService;
use DB;
use Illuminate\Http\JsonResponse;

class RoomTypeAmenityController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(RoomType $roomType, Amenity $amenity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach($roomType, 'amenities', $amenity->id);
            DB::commit();
            return response()->json(['message' => 'Amenity added to the room.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
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
        DB::beginTransaction();
        try {
            $this->modelService->detach($roomType, 'amenities', $amenity->id);
            DB::commit();
            return response()->json(['message' => 'Amenity removed from the room']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
