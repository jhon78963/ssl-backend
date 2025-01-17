<?php

namespace App\Inventory\Controllers;

use App\Inventory\Models\Inventory;
use App\Inventory\Requests\InventoryCreateRequest;
use App\Inventory\Requests\InventoryUpdateRequest;
use App\Inventory\Resources\InventoryResource;
use App\Inventory\Services\InventoryService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;
    protected SharedService $sharedService;

    public function __construct(
        InventoryService $inventoryService,
        SharedService $sharedService
    ) {
        $this->inventoryService = $inventoryService;
        $this->sharedService = $sharedService;
    }

    public function create(InventoryCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newInventory = $this->sharedService->convertCamelToSnake($request->validated());
            $this->inventoryService->create($newInventory);
            DB::commit();
            return response()->json(['message' => 'Inventory created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Inventory $inventory): JsonResponse
    {
        DB::beginTransaction();
        try {
            $inventoryValidated = $this->inventoryService->validate($inventory, 'Inventory');
            $this->inventoryService->delete($inventoryValidated);
            DB::commit();
            return response()->json(['message' => 'Inventory deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Inventory $inventory): JsonResponse
    {
        $inventoryValidated = $this->inventoryService->validate(
            $inventory,
            'Inventory'
        );
        return response()->json(new InventoryResource($inventoryValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Inventory',
            'Inventory',
            'description'
        );
        return response()->json(new GetAllCollection(
            InventoryResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(InventoryUpdateRequest $request, Inventory $inventory): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editInventory = $this->sharedService->convertCamelToSnake($request->validated());
            $inventory = $this->inventoryService->validate($inventory, 'Inventory');
            $this->inventoryService->update($inventory, $editInventory);
            DB::commit();
            return response()->json(['message' => 'Inventory updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
