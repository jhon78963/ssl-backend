<?php

namespace App\Reservation\Controllers;

use App\Inventory\Models\Inventory;
use App\Reservation\Models\Reservation;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\AddRequest;
use App\Shared\Services\ModelService;
use Illuminate\Http\JsonResponse;
use DB;

class ReservationInventoryController extends Controller
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function add(AddRequest $request, Reservation $reservation, Inventory $inventory): JsonResponse
    {
        DB::beginTransaction();
        try {
            $pivotExists = $this->validatePivot($reservation->id, $inventory->id);
            $this->operatePivote(
                $pivotExists,
                $reservation,
                $inventory->id,
                $request->input('quantity')
            );
            $this->updateStockInventory($inventory, $request->input('quantity'));
            DB::commit();
            return response()->json(['message' => 'Inventory added to the reservation.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function remove(Reservation $reservation, Inventory $inventory, int $quantity): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->modelService->detach($reservation, 'inventories', $inventory->id);
            $this->updateStockInventory($inventory, $quantity, true);
            DB::commit();
            return response()->json(['message' => 'Inventory removed from the reservation']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    private function validatePivot(int $reservationId, int $inventorId): bool
    {
        return $this->modelService->validatePivote(
            'reservation_inventory',
            'reservation_id',
            'inventory_id',
            $reservationId,
            $inventorId
        );
    }

    private function operatePivote(
        bool $pivotExists,
        Reservation $reservation,
        int $inventoryId,
        int $quantity
    ):void {
        $method = $pivotExists ? 'modify' : 'attach';
        $this->modelService->$method(
            $reservation,
            'inventories',
            $inventoryId,
            null,
            $quantity
        );
    }

    private function updateStockInventory(
        Inventory $inventory,
        int $quantity,
        bool $isRemove = false
    ): void {
        $editInventory = [
            // 'stock_in_use' => $inventory->stock_in_use + ($isRemove ? -$quantity : $quantity),
            'stock_in_use' => $quantity,
        ];
        $this->modelService->update($inventory, $editInventory);
    }
}
