<?php

namespace App\Cash\Controllers;

use App\Cash\Models\Cash;
use App\Cash\Requests\CashCreateRequest;
use App\Cash\Requests\CashUpdateRequest;
use App\Cash\Resources\CashResource;
use App\Cash\Services\CashOperationService;
use App\Cash\Services\CashService;
use App\Schedule\Services\ScheduleService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class CashController extends Controller
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

    public function create(CashCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newCash = $this->sharedService->convertCamelToSnake($request->validated());
            $newCash['schedule_id'] = $this->scheduleService->get();
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

    public function get():JsonResponse
    {
        return response()->json($this->cashService->currentCash());
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

    public function update(CashUpdateRequest $request, Cash $cash): JsonResponse
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
}
