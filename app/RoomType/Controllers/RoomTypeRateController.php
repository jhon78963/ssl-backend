<?php

namespace App\RoomType\Controllers;

use App\Rate\Models\Rate;
use App\Rate\Resources\RateResource;
use App\RoomType\Models\RoomType;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ModelRelationService;
use Illuminate\Http\JsonResponse;

class RoomTypeRateController extends Controller
{
    protected ModelRelationService $modelRelationService;

    public function __construct(ModelRelationService $modelRelationService)
    {
        $this->modelRelationService = $modelRelationService;
    }
    public function add(RoomType $roomType, Rate $rate): JsonResponse
    {
        $result = $this->modelRelationService->attach($roomType, 'rates', $rate->id);
            return $result && isset($result['error'])
                ? response()->json(['message' => $result['error']])
                : response()->json(['message' => 'Rate added to the room.'], 201);
    }

    public function getAll(RoomType $roomType): JsonResponse
    {
        $rates = $roomType->rates()->orderBy('id', 'desc')->get();
        return response()->json( RateResource::collection($rates));
    }

    public function getLeft(RoomType $roomType): JsonResponse
    {
        $allRates = Rate::where('is_deleted', false)->get();
        $associatedRates = $roomType->rates()->pluck('id')->toArray();
        $leftRates = $allRates->whereNotIn('id', $associatedRates);
        return response()->json( RateResource::collection($leftRates));
    }

    public function remove(RoomType $roomType, Rate $rate): JsonResponse
    {
        $result = $this->modelRelationService->detach($roomType, 'rates', $rate->id);
        return $result && isset($result['error'])
            ? response()->json(['message' => $result['error']])
            : response()->json(['message' => 'Rate removed from the room']);
    }
}
