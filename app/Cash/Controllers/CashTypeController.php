<?php

namespace App\Cash\Controllers;

use App\Cash\Services\CashTypeService;
use App\Shared\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CashTypeController extends Controller
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
