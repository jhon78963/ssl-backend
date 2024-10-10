<?php

namespace App\Room\Controllers;

use App\Review\Models\Review;
use App\Review\Requests\ReviewAddRequest;
use App\Review\Resources\ReviewResource;
use App\Room\Models\Room;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ModelRelationService;
use Illuminate\Http\JsonResponse;

class RoomReviewController extends Controller
{
    protected ModelRelationService $modelRelationService;

    public function __construct(ModelRelationService $modelRelationService)
    {
        $this->modelRelationService = $modelRelationService;
    }

    public function add(ReviewAddRequest $request, Room $room): JsonResponse
    {
        $result = $this->modelRelationService->attach($room, 'reviews', $request->input('reviewId'));
        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function getAll(Room $room): JsonResponse
    {
        $reviews = $room->reviews()->where('is_deleted', false)->orderBy('id', 'desc')->get();
        return response()->json( ReviewResource::collection($reviews));
    }

    public function remove(Room $room, Review $review): JsonResponse
    {
        $result = $this->modelRelationService->detach($room, 'reviews', $review->id);
        return response()->json(['message' => $result['message']], $result['status']);
    }
}
