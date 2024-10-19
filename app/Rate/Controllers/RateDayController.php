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
use Illuminate\Http\JsonResponse;
use DB;

class RateDayController extends Controller
{
    protected RateDayService $rateDayService;
    protected SharedService $sharedService;

    public function __construct(RateDayService $rateDayService, SharedService $sharedService)
    {
        $this->rateDayService = $rateDayService;
        $this->sharedService = $sharedService;
    }

    public function create(RateDayCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newRateDay = $this->sharedService->convertCamelToSnake($request->validated());
            $this->rateDayService->create($newRateDay);
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
            $rateDayValidated = $this->rateDayService->validate($rateDay, 'RateDay');
            $this->rateDayService->delete($rateDayValidated);
            DB::commit();
            return response()->json(['message' => 'Rate day deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(RateDay $rateDay): JsonResponse
    {
        $rateDayValidated = $this->rateDayService->validate($rateDay, 'RateDay');
        return response()->json(new RateDayResource($rateDayValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Rate',
            'RateDay',
            'duration'
        );

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
            $editRateDay = $this->sharedService->convertCamelToSnake($request->validated());
            $rateDayValidated = $this->rateDayService->validate($rateDay, 'RateDay');
            $this->rateDayService->update($rateDayValidated, $editRateDay);
            DB::commit();
            return response()->json(['message' => 'Rate day updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
