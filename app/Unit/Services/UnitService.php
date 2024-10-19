<?php

namespace App\Unit\Services;
use App\Shared\Services\ModelService;
use App\Unit\Models\Unit;

class UnitService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newUnit): void
    {
        $this->modelService->create(new Unit(), $newUnit);
    }

    public function delete(Unit $unit): void
    {
        $this->modelService->delete($unit);
    }

    public function update(Unit $unit, array $editUnit): void
    {
        $this->modelService->update($unit, $editUnit);
    }

    public function validate(Unit $unit, string $modelName): Unit
    {
        return $this->modelService->validate($unit, $modelName);
    }
}
