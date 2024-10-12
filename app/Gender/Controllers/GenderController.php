<?php

namespace App\Gender\Controllers;

use App\Gender\Models\Gender;
use App\Gender\Resources\GenderResource;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;

class GenderController
{
    protected SharedService $sharedService;

    public function __construct(SharedService $sharedService)
    {
        $this->sharedService = $sharedService;
    }

    public function get(Gender $gender): JsonResponse
    {
        $genderValidated = $this->sharedService->validateModel($gender, 'Gender');
        return response()->json(new GenderResource($genderValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Gender', 'Gender', 'number');
        return response()->json(new GetAllCollection(
            GenderResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }
}
