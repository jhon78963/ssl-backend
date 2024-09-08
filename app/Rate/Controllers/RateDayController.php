<?php

namespace App\Rate\Controllers;

use App\Rate\Models\RateDay;
use App\Rate\Requests\RateDayCreateRequest;
use App\Rate\Requests\RateDayUpdateRequest;
use App\Rate\Resources\RateDayResource;
use App\Rate\Services\RateDayService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use DB;
use Illuminate\Http\JsonResponse;

class RateDayController extends Controller
{
    protected $rateDayService;
    protected $sharedService;

    public function __construct(RateDayService $rateDayService, SharedService $sharedService)
    {
        $this->rateDayService = $rateDayService;
        $this->sharedService = $sharedService;
    }

    public function create(RateDayCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->rateDayService->createRateDay($request->validated());
            DB::commit();
            return response()->json(['message' => 'Rate day created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(RateDay $rateDay): JsonResponse
    {
        DB::beginTransaction();
        try {
            $rateDayValidated = $this->sharedService->validateModel($rateDay, 'RateDay');
            $this->sharedService->deleteModel($rateDayValidated);
            DB::commit();
            return response()->json(['message' => 'Rate day deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(RateDay $rateDay): JsonResponse
    {
        $rateDayValidated = $this->sharedService->validateModel($rateDay, 'RateDay');
        return response()->json(new RateDayResource($rateDayValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Rate', 'RateDay', 'duration');
        return response()->json(new GetAllCollection(
            RateDayResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(RateDayUpdateRequest $request, RateDay $rateDay): JsonResponse
    {
        DB::beginTransaction();
        try {
            $rateDayValidated = $this->sharedService->validateModel($rateDay, 'RateDay');
            $this->rateDayService->updateRateDay($rateDayValidated, $request->validated());
            DB::commit();
            return response()->json(['message' => 'Rate day updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
