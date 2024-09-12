<?php

namespace App\Room\Controllers;

use App\Review\Models\Review;
use App\Review\Requests\ReviewAddRequest;
use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use DB;

class RoomReviewController extends Controller
{
    public function add(ReviewAddRequest $request, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $room->reviews()->attach($request->input('reviewId'));
            DB::commit();
            return response()->json([
                'message' => 'Review added to the room.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function remove(Room $room, Review $review): JsonResponse
    {
        DB::beginTransaction();
        try {
            $room->reviews()->detach($review->id);
            DB::commit();
            return response()->json([
                'message' => 'Review removed from the room.',
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
