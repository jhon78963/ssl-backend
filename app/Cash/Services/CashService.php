<?php

namespace App\Cash\Services;

use App\Cash\Models\CashOperation;
use App\CashType\Models\CashType;
use App\Reservation\Models\Reservation;
use App\Shared\Services\ModelService;

class CashService
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

    public function total(): mixed {
        return Reservation::where('is_deleted', '=', false)
            ->where('status', 'COMPLETED')
            ->sum('total');
    }

    public function validate() : CashType {
        $cashOperation = CashOperation::where('is_deleted', '=', false)
            ->orderBy('id', 'desc')
            ->first();

        return match ($cashOperation->cash_type_id) {
            1 => CashType::find(2),
            2 => CashType::find(1),
        };
    }
}
