<?php

namespace App\Cash\Services;

use App\Cash\Models\Cash;
use App\Shared\Services\ModelService;

class CashService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newCash): Cash
    {
        return $this->modelService->create(
            new Cash(),
            $newCash
        );
    }

    public function currentCash(): ?Cash
    {
        $cash = Cash::where('is_deleted', '=', false)
            ->where('status', 'OPEN')
            ->latest('id')
            ->first();
        return $cash ?: $cash;
    }

    public function update(Cash $cash, array $editCash): Cash
    {
        return $this->modelService->update($cash, $editCash);
    }

    public function validate(Cash $cash, string $modelName): Cash
    {
        return $this->modelService->validate($cash, $modelName);
    }
}
