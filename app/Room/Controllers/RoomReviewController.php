<?php

namespace App\Room\Controllers;

use App\Review\Models\Review;
use App\Review\Requests\ReviewAddRequest;
use App\Review\Resources\ReviewResource;
use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class RoomReviewController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(ReviewAddRequest $request, Room $room): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach($room, 'reviews', $request->input('reviewId'));
            DB::commit();
            return response()->json(['message' => 'Review added to the room.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function getAll(Room $room): JsonResponse
    {
        $reviews = $room->reviews()
            ->where('is_deleted', false)
            ->orderBy('id', 'desc')
            ->get();
        return response()->json( ReviewResource::collection($reviews));
    }

    public function remove(Room $room, Review $review): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($room, 'reviews', $review->id);
            DB::commit();
            return response()->json(['message' => 'Review removed to the room.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }
}
