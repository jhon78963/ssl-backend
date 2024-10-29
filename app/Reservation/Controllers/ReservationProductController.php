<?php

namespace App\Reservation\Controllers;

use App\Product\Models\Product;
use App\Product\Resources\ProductResource;
use App\Reservation\Models\Reservation;
use App\Shared\Controllers\Controller;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

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

    public function getAll(Reservation $reservation): JsonResponse
    {
        $products = $reservation->products()->orderBy('id', 'desc')->get();
        return response()->json( ProductResource::collection($products));
    }

    public function getLeft(Reservation $reservation): JsonResponse
    {
        $allProducts = Product::where('is_deleted', false)->get();
        $associatedProducts = $reservation->products()->pluck('id')->toArray();
        $leftProducts = $allProducts->whereNotIn('id', $associatedProducts);
        return response()->json( ProductResource::collection($leftProducts));
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
