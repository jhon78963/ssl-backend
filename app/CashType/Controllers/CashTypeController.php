<?php

namespace App\CashType\Controllers;

use App\CashType\Services\CashTypeService;
use Illuminate\Http\JsonResponse;

class CashTypeController
{
    protected CashTypeService $cashTypeService;
    public function __construct(
        CashTypeService $cashTypeService,
    ) {
        $this->cashTypeService = $cashTypeService;
    }
    public function get(): JsonResponse
    {
        return response()->json(
            $this->cashTypeService->get(),
        );
    }
}
