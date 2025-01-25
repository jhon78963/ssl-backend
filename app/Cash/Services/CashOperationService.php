<?php

namespace App\Cash\Services;

use App\Cash\Models\CashOperation;
use App\Shared\Services\ModelService;

class CashOperationService
{
    protected CashService $cashService;
    protected ModelService $modelService;

    public function __construct(CashService $cashService, ModelService $modelService)
    {
        $this->cashService = $cashService;
        $this->modelService = $modelService;
    }

    public function create(array $newCash): void
    {
        $this->modelService->create(new CashOperation(), $newCash);
    }

    public function total(int $cashId): array
    {
        $cash = $this->cashService->currentCash();
        $pettyCash = (float) $cash->petty_cash_amount;
        $amount = (float) CashOperation::where('is_deleted', '=', false)
            ->where('cash_id', '=', $cashId)
            ->sum('amount');

        return [
            'employee' => $cash->name,
            'pettyCash' => $pettyCash,
            'amount' => $amount,
            'cashAmount' => (float) CashOperation::where('is_deleted', '=', false)
                ->where('cash_id', '=', $cashId)
                ->sum('cash_amount'),
            'cardAmount' => (float) CashOperation::where('is_deleted', '=', false)
                ->where('cash_id', '=', $cashId)
                ->sum('card_amount'),
            'total' => $pettyCash + $amount,
        ];
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
