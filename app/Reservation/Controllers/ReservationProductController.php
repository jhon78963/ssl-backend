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

            // Buscar si existe un registro con el estado actual en BD
            $existingPaid = DB::table('reservation_product')
                ->where('reservation_id', $reservation->id)
                ->where('product_id', $product->id)
                ->where('is_paid', true)
                ->where('is_free', $isFree)
                ->first();

            $existingUnpaid = DB::table('reservation_product')
                ->where('reservation_id', $reservation->id)
                ->where('product_id', $product->id)
                ->where('is_paid', false)
                ->where('is_free', $isFree)
                ->first();

            if ($isPaidBd === false && $isPaid === true) {
                if ($existingPaid) {
                    // Si ya existe un producto con is_paid = true, sumamos la cantidad
                    DB::table('reservation_product')
                        ->where('reservation_id', $reservation->id)
                        ->where('product_id', $product->id)
                        ->where('is_paid', true)
                        ->where('is_free', $isFree)
                        ->increment('quantity', $quantity);
                } else {
                    // Si no existe, actualizamos el existente con is_paid = false a true
                    DB::table('reservation_product')
                        ->where('reservation_id', $reservation->id)
                        ->where('product_id', $product->id)
                        ->where('is_paid', false)
                        ->where('is_free', $isFree)
                        ->update(['is_paid' => true]);
                }
                // Eliminamos el registro con is_paid = false si existía
                DB::table('reservation_product')
                    ->where('reservation_id', $reservation->id)
                    ->where('product_id', $product->id)
                    ->where('is_paid', false)
                    ->where('is_free', $isFree)
                    ->delete();
            } elseif ($existingUnpaid) {
                // Si llega un false y ya existe un false, sumamos la cantidad
                DB::table('reservation_product')
                    ->where('reservation_id', $reservation->id)
                    ->where('product_id', $product->id)
                    ->where('is_paid', false)
                    ->where('is_free', $isFree)
                    ->increment('quantity', $quantity);
            } else {
                // Si no existe ni en true ni en false, insertamos un nuevo registro
                DB::table('reservation_product')->insert([
                    'reservation_id' => $reservation->id,
                    'product_id' => $product->id,
                    'is_paid' => $isPaid,
                    'is_free' => $isFree,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Product modified in the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
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
