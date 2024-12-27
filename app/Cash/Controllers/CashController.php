<?php

namespace App\Cash\Controllers;

use App\Cash\Requests\CashCreateRequest;
use App\Cash\Resources\CashResource;
use App\Cash\Services\CashService;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class CashController
{
    protected CashService $cashService;
    protected SharedService $sharedService;

    public function __construct(CashService $cashService, SharedService $sharedService)
    {
        $this->cashService = $cashService;
        $this->sharedService = $sharedService;
    }

    public function create(CashCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newCategory = $this->sharedService->convertCamelToSnake($request->validated());
            $this->cashService->create($newCategory);
            DB::commit();
            return response()->json(['message' => 'Cash created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function validate()
    {
        return response()->json($this->cashService->validate());
    }

    public function total(): JsonResponse {
        return response()->json([
            'total' => $this->cashService->total(),
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
