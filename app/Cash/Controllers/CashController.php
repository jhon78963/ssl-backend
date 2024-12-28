<?php

namespace App\Cash\Controllers;

use App\Cash\Models\Cash;
use App\Cash\Requests\CashCreateRequest;
use App\Cash\Requests\CashOperationCreateRequest;
use App\Cash\Requests\CashUpdateRequest;
use App\Cash\Resources\CashResource;
use App\Cash\Resources\ScheduleResource;
use App\Cash\Services\CashOperationService;
use App\Cash\Services\CashService;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class CashController
{
    protected CashService $cashService;
    protected CashOperationService $cashOperationService;
    protected SharedService $sharedService;

    public function __construct(
        CashService $cashService,
        CashOperationService $cashOperationService,
        SharedService $sharedService
    ) {
        $this->cashService = $cashService;
        $this->cashOperationService = $cashOperationService;
        $this->sharedService = $sharedService;
    }

    public function create(CashOperationCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newCash = $this->sharedService->convertCamelToSnake($request->validated());
            $newCash['schedule_id'] = $this->cashOperationService->schedule();
            $this->cashOperationService->create($newCash);
            DB::commit();
            return response()->json(['message' => 'Cash created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function createCash(CashCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newCash = $this->sharedService->convertCamelToSnake($request->validated());
            $newCash['schedule_id'] = $this->cashOperationService->schedule();
            $cash = $this->cashService->create($newCash);
            DB::commit();
            return response()->json([
                'message' => 'Cash created.',
                'id' => $cash->id,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function updateCash(CashUpdateRequest $request, Cash $cash): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editCashValidated = $this->sharedService->convertCamelToSnake($request->validated());
            $cashValidated = $this->cashService->validate($cash, 'Cash');
            $this->cashService->update(
                $cashValidated,
                $editCashValidated
            );
            DB::commit();
            return response()->json(['message' => 'Cash closed.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function currentCash() {
        return response()->json($this->cashService->currentCash());
    }

    public function currentSchedule() {
        return response()->json(
            new ScheduleResource($this->cashOperationService->currentSchedule())
        );
    }

    public function validate()
    {
        return response()->json($this->cashOperationService->validate());
    }

    public function total(): JsonResponse {
        $cash = $this->cashService->currentCash();
        return response()->json([
            'total' => (float) $this->cashOperationService->total($cash->id),
        ]);
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Cash',
            'CashOperation',
            'name'
        );
        return response()->json(new GetAllCollection(
            CashResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }
}
