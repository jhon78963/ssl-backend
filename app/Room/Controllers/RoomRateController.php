<?php

namespace App\Room\Controllers;

use App\Rate\Models\Rate;
use App\Rate\Requests\RateAddRequest;
use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use DB;

class RoomRateController extends Controller
{
    public function add(RateAddRequest $request, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $room->rates()->attach($request->input('rateId'));
            DB::commit();
            return response()->json([
                'message' => 'Rate added to the room.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function remove(Room $room, Rate $rate): JsonResponse
    {
        DB::beginTransaction();
        try {
            $room->rates()->detach($rate->id);
            DB::commit();
            return response()->json([
                'message' => 'Rate removed from the room.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
