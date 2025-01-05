<?php

namespace App\Booking\Controllers;

use App\Booking\Models\Booking;
use App\Product\Models\Product;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Requests\ModifyRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class BookingProductController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Booking $booking, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->attach(
                $booking,
                'products',
                $product->id,
                $product->price,
                $request->input('quantity'),
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            DB::commit();
            return response()->json(['message' => 'Product added to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function modify(ModifyRequest $request, Booking $booking, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->modify(
                $booking,
                'products',
                $product->id,
                null,
                $request->input('quantity'),
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            DB::commit();
            return response()->json(['message' => 'Product modified to the booking.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(Booking $booking, Product $product, int $quantity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($booking, 'products', $product->id);
            $editBooking = [
                'total' => $booking->total - $product->price * $quantity,
            ];
            $this->modelService->update($booking, $editBooking);
            DB::commit();
            return response()->json(['message' => 'Product removed from the booking']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
