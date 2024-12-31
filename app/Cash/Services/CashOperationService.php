<?php

namespace App\Cash\Services;

use App\Cash\Models\CashOperation;
use App\Shared\Services\ModelService;

class CashOperationService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newCash): void
    {
        $this->modelService->create(new CashOperation(), $newCash);
    }

    public function total(int $cashId): mixed
    {
        return CashOperation::where('is_deleted', '=', false)
            ->where('cash_id', '=', $cashId)
            ->sum('amount');
    }

    public function update(int $cashOperationId, array $editCashOperation): CashOperation
    {
        $cashOperation = CashOperation::find($cashOperationId);
        return $this->modelService->update($cashOperation, $editCashOperation);
    }

    public function validate(CashOperation $cashOperation, string $modelName): CashOperation
    {
        return $this->modelService->validate($cashOperation, $modelName);
    }
}
