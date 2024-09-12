<?php

namespace App\Room\Controllers;
use App\Amenity\Models\Amenity;
use App\Amenity\Requests\AmenityAddRequest;
use App\Amenity\Requests\AmenityRemoveRequest;
use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use DB;
use Illuminate\Http\JsonResponse;

class RoomAmenityController extends Controller
{
    public function add(AmenityAddRequest $request, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $room->amenities()->attach($request->input('amenityId'));
            DB::commit();
            return response()->json([
                'message' => 'Amenity added to the room.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function remove(Room $room, Amenity $amenity): JsonResponse {
        DB::beginTransaction();
        try {
            $room->amenities()->detach($amenity->id);
            DB::commit();
            return response()->json([
                'message' => 'Amenity removed from the room.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
