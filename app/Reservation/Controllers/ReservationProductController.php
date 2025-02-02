<?php

namespace App\Reservation\Controllers;

use App\Product\Models\Product;
use App\Reservation\Models\Reservation;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Requests\ModifyRequest;
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

    public function add(AddRequest $request, Reservation $reservation, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $pivotExists = $this->validatePivot(
                $reservation->id,
                $product->id,
                $request->input('isPaid'),
                $request->input('isFree'),
            );

            $this->operatePivote(
                $pivotExists,
                $reservation,
                $product->id,
                $product->price,
                $request->input('quantity'),
                $request->input('isPaid'),
                $request->input('isFree'),
            );
            // $this->modelService->attach(
            //     $reservation,
            //     'products',
            //     $product->id,
            //     $product->price,
            //     $request->input('quantity'),
            //     $request->input('isPaid'),
            //     $request->input('isFree'),
            // );
            DB::commit();
            return response()->json(['message' => 'Product added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function modify(ModifyRequest $request, Reservation $reservation, Product $product): JsonResponse
    {
        DB::beginTransaction();
        try {
            $isFree = $request->input('isFree');
            $isPaid = $request->input('isPaid');
            $isPaidBd = $request->input('isPaidBd');
            $quantity = $request->input('quantity');

            DB::table('reservation_product')
                ->where('reservation_id', $reservation->id)
                ->where('product_id', $product->id)
                ->where('is_paid', $isPaidBd)
                ->where('is_free', $isFree)
                ->increment(
                    'quantity', $quantity,
                    [
                        'is_paid' => $isPaid,
                        'is_free' => $isFree
                    ]);

            // if ($isPaid != $isPaidBd) {

            // }

            DB::commit();
            return response()->json(['message' => 'Product modified to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(Reservation $reservation, Product $product, int $quantity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($reservation, 'products', $product->id);
            $this->updateReservation(
                $reservation,
                $product->price,
                $quantity
            );
            DB::commit();
            return response()->json(['message' => 'Product removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function updateReservation(Reservation $reservation, float $productPrice, float $productQuantity): void
    {
        $editReservation = [
            'total' => $reservation->total - $productPrice * $productQuantity,
            'total_paid' => $reservation->total_paid - $productPrice * $productQuantity,
            'consumptions_import' => $reservation->consumptions_import - $productPrice * $productQuantity,
        ];
        $this->modelService->update($reservation, $editReservation);
    }

    private function validatePivot(int $reservationId, int $productId, bool $isPaid, bool $isFree): bool
    {
        return DB::table('reservation_product')
            ->where('reservation_id', '=', $reservationId)
            ->where('product_id', '=', $productId)
            ->where('is_paid', '=', $isPaid)
            ->where('is_free', '=', $isFree)
            ->exists();
    }

    private function operatePivote(
        bool $pivotExists,
        Reservation $reservation,
        int $productId,
        float $price,
        int $quantity,
        bool $isPaid,
        bool $isFree,
    ) {
        if ($pivotExists) {
            DB::table('reservation_product')
                ->where('reservation_id', $reservation->id)
                ->where('product_id', $productId)
                ->where('is_paid', $isPaid)
                ->where('is_free', $isFree)
                ->increment('quantity', $quantity, [
                    'is_paid' => $isPaid,
                    'is_free' => $isFree
                ]);
        } else {
            $this->modelService->attach(
                $reservation,
                'products',
                $productId,
                $price,
                $quantity,
                $isPaid,
                $isFree,
            );
        }
    }
}
