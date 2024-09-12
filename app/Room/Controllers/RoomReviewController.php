<?php

namespace App\Room\Controllers;

use App\Review\Models\Review;
use App\Review\Requests\ReviewAddRequest;
use App\Room\Models\Room;
use App\Room\Services\RoomRelationService;
use App\Shared\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class RoomReviewController extends Controller
{
    protected RoomRelationService $roomRelationService;

    public function __construct(RoomRelationService $roomRelationService)
    {
        $this->roomRelationService = $roomRelationService;
    }

    public function add(ReviewAddRequest $request, Room $room): JsonResponse
    {
        $result = $this->roomRelationService->attach($room, 'reviews', $request->input('reviewId'));
        return response()->json(['message' => $result['message']], $result['status']);
    }

    public function remove(Room $room, Review $review): JsonResponse
    {
        $result = $this->roomRelationService->detach($room, 'reviews', $review->id);
        return response()->json(['message' => $result['message']], $result['status']);
    }
}
