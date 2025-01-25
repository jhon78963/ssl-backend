<?php

namespace App\Cash\Controllers;

use App\Cash\Requests\CashOperationCreateRequest;
use App\Cash\Services\CashOperationService;
use App\Cash\Services\CashService;
use App\Schedule\Services\ScheduleService;
use App\Shared\Controllers\Controller;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;


class CashOperationController extends Controller
{
    protected CashService $cashService;
    protected CashOperationService $cashOperationService;
    protected ScheduleService $scheduleService;
    protected SharedService $sharedService;

    public function __construct(
        CashService $cashService,
        CashOperationService $cashOperationService,
        ScheduleService $scheduleService,
        SharedService $sharedService,
    ) {
        $this->cashService = $cashService;
        $this->cashOperationService = $cashOperationService;
        $this->scheduleService = $scheduleService;
        $this->sharedService = $sharedService;
    }

    public function create(CashOperationCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newCash = $this->sharedService->convertCamelToSnake($request->validated());
            $newCash = $this->prepareCashData($newCash);
            $this->cashOperationService->create($newCash);
            DB::commit();
            return response()->json(['message' => 'Cash created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function total(): JsonResponse
    {
        $cash = $this->cashService->currentCash();
        return response()->json(
            $this->cashOperationService->total($cash->id)
        );
    }

    private function prepareCashData(array $newCash): array
    {
        $cash = $this->cashService->currentCash();
        $newCash['cash_id'] = $cash->id;
        $newCash['schedule_id'] = $this->scheduleService->get();
        switch ($newCash['cash_type_id']) {
            case 3:
                $newCash['amount'] = -$newCash['amount'];
                $newCash['cash_amount'] = -$newCash['amount'];
                break;
            case 4:
                $newCash['amount'] = -$newCash['amount'];
                $newCash['cash_amount'] = -$newCash['cash_amount'];
                $newCash['card_amount'] = -$newCash['card_amount'];
            default:
                $newCash['cash_amount'] = $newCash['amount'];
                break;
        }
        return $newCash;
    }
}
