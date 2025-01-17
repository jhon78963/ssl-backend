<?php

namespace App\Inventory\Services;

use App\Inventory\Models\Inventory;
use App\Shared\Services\ModelService;

class InventoryService
{
    protected ModelService $modelService;
    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newInventory): void
    {
        $this->modelService->create(new Inventory(), $newInventory);
    }

    public function delete(Inventory $inventory): void
    {
        $this->modelService->delete($inventory);
    }

    public function update(Inventory $inventory, array $editInventory): Inventory
    {
        return $this->modelService->update($inventory, $editInventory);
    }

    public function validate(Inventory $inventory, string $modelName): Inventory
    {
        return $this->modelService->validate($inventory, $modelName);
    }
}
