<?php

namespace App\Http\Controllers;

use App\Product\Models\Product;
use App\Reservation\Models\Reservation;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ModelService;
use DB;
use Illuminate\Http\JsonResponse;

class ReservationProductController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(Reservation $reservation, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach($reservation, 'products', $product->id);
            DB::commit();
            return response()->json(['message' => 'Product added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(Reservation $reservation, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($reservation, 'products', $product->id);
            DB::commit();
            return response()->json(['message' => 'Product removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
