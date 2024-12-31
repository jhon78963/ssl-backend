<?php

namespace App\Cash\Controllers;

use App\Cash\Requests\CashOperationCreateRequest;
use App\Cash\Services\CashOperationService;
use App\Cash\Services\CashService;
use App\Schedule\Services\ScheduleService;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;


class CashOperationController
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
            $newCash['schedule_id'] = $this->scheduleService->get();
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
        return response()->json([
            'total' => (float) $this->cashOperationService->total($cash->id),
        ]);
    }
}
