<?php

namespace App\RoomType\Controllers;

use App\Rate\Models\Rate;
use App\Rate\Resources\RateResource;
use App\RoomType\Models\RoomType;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ModelService;
use DB;
use Illuminate\Http\JsonResponse;

class RoomTypeRateController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }
    public function add(RoomType $roomType, Rate $rate): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach($roomType, 'rates', $rate->id);
            DB::commit();
            return response()->json(['message' => 'Rate added to the room.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function getAll(RoomType $roomType): JsonResponse
    {
        $rates = $roomType->rates()->orderBy('id', 'asc')->get();
        return response()->json( RateResource::collection($rates));
    }

    public function getLeft(RoomType $roomType): JsonResponse
    {
        $allRates = Rate::where('is_deleted', false)
            ->orderBy('price', 'asc')
            ->orderBy('day_id', 'asc')
            ->get();
        $associatedRates = $roomType->rates()->pluck('id')->toArray();
        $leftRates = $allRates->whereNotIn('id', $associatedRates);
        return response()->json( RateResource::collection($leftRates));
    }

    public function remove(RoomType $roomType, Rate $rate): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($roomType, 'rates', $rate->id);
            DB::commit();
            return response()->json(['message' => 'Rate removed from the room']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
