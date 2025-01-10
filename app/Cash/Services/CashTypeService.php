<?php

namespace App\Cash\Services;

use App\Cash\Models\CashOperation;
use App\Cash\Models\CashType;
use App\Shared\Services\ModelService;

class CashTypeService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }


    public function get() : CashType
    {
        $cashOperation = CashOperation::where('is_deleted', '=', false)
            ->orderBy('id', 'desc')
            ->first();

        return match ($cashOperation->cash_type_id ?? 4) {
            1 => CashType::find(4),
            2 => CashType::find(4),
            3 => CashType::find(4),
            4 => CashType::find(1),
        };
    }
}
