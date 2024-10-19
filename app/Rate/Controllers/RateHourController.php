<?php

namespace App\Rate\Controllers;

use App\Rate\Models\RateHour;
use App\Rate\Requests\RateHourCreateRequest;
use App\Rate\Requests\RateHourUpdateRequest;
use App\Rate\Resources\RateHourResource;
use App\Rate\Services\RateHourService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class RateHourController extends Controller
{
    protected RateHourService $rateHourService;
    protected SharedService $sharedService;

    public function __construct(RateHourService $rateHourService, SharedService $sharedService)
    {
        $this->rateHourService = $rateHourService;
        $this->sharedService = $sharedService;
    }

    public function create(RateHourCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newRateHour = $this->sharedService->convertCamelToSnake($request->validated());
            $this->rateHourService->create($newRateHour);
            DB::commit();
            return response()->json(['message' => 'Rate hour created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(RateHour $rateHour): JsonResponse
    {
        DB::beginTransaction();
        try {
            $rateHourValidated = $this->rateHourService->validate($rateHour, 'RateHour');
            $this->rateHourService->delete($rateHourValidated);
            DB::commit();
            return response()->json(['message' => 'Rate hour deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(RateHour $rateHour): JsonResponse
    {
        $rateHourValidated = $this->rateHourService->validate($rateHour, 'RateHour');
        return response()->json(new RateHourResource($rateHourValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Rate',
            'RateHour',
            'duration'
        );

        return response()->json(new GetAllCollection(
            RateHourResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(RateHourUpdateRequest $request, RateHour $rateHour): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editRateHour = $this->sharedService->convertCamelToSnake($request->validated());
            $rateHourValidated = $this->rateHourService->validate($rateHour, 'RateHour');
            $this->rateHourService->update($rateHourValidated, $editRateHour);
            DB::commit();
            return response()->json(['message' => 'Rate hour updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
