<?php

namespace App\Rate\Controllers;

use App\Rate\Models\Rate;
use App\Rate\Requests\RateCreateRequest;
use App\Rate\Requests\RateUpdateRequest;
use App\Rate\Resources\RateResource;
use App\Rate\Services\RateService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class RateController  extends Controller
{
    protected $rateService;
    protected $sharedService;

    public function __construct(RateService $rateService, SharedService $sharedService)
    {
        $this->rateService = $rateService;
        $this->sharedService = $sharedService;
    }

    public function create(RateCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->rateService->createRate($request->validated());
            DB::commit();
            return response()->json(['message' => 'Rate created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Rate $rate): JsonResponse
    {
        DB::beginTransaction();
        try {
            $rateValidated = $this->sharedService->validateModel($rate, 'Rate');
            $this->sharedService->deleteModel($rateValidated);
            DB::commit();
            return response()->json(['message' => 'Rate deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Rate $rate): JsonResponse
    {
        $rateValidated = $this->sharedService->validateModel($rate, 'Rate');
        return response()->json(new RateResource($rateValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Rate', 'Rate', 'duration');
        return response()->json(new GetAllCollection(
            RateResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(RateUpdateRequest $request, Rate $rate): JsonResponse
    {
        DB::beginTransaction();
        try {
            $rateValidated = $this->sharedService->validateModel($rate, 'Rate');
            $this->rateService->updateRate($rateValidated, $request->validated());
            DB::commit();
            return response()->json(['message' => 'Rate day updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
